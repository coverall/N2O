<?php
// $Id: CC_RelationshipManager.php,v 1.34 2010/11/11 04:28:32 patrick Exp $
//=======================================================================
// CLASS: CC_RelationshipManager
//=======================================================================

/** 
  * The CC_RelationshipManager uses the CC_RELATIONSHIPS and CC_MANY_RELATIONSHIPS tables to store pertinent information about the application's relational database hierarchy. 
  *
  * The CC_RELATIONSHIPS table has the following columns for each field:
  * --------------------------------------------------------------------
  *
  * 1) FOREIGN_KEY    - The name of the foreign key field.
  * 2) RELATED_TABLE  - The name of the table the foreign key belongs to.
  * 3) RELATED_COLUMN - The column in the foreign key table who's value is stored in the related table.
  * 4) DISPLAY_COLUMN - The column in the foreign key table who's value is used for display in the related table.
  *
  *
  * The CC_MANY_RELATIONSHIPS table has the following columns for each field:
  * -------------------------------------------------------------------------
  *
  * 1) FOREIGN_KEY    - The name of the foreign key field.
  * 2) SET_TABLE      - The table that stores the SOURCE_TABLE record ids in the related table's data set. Each entry consists of the ID stored in the related table's FOREIGN_KEY column and the FK_ID which refers to a record in the foreign key table that is part of the set.
  * 3) SOURCE_TABLE   - The table that holds the foreign key records.
  * 4) DISPLAY_COLUMN - The column in the foreign key table who's value is used for display in the related table.
  *
  * @package CC_Managers
  * @access public
  * @author The Crew <N2O@coverallcrew.com>
  * @copyright Copyright &copy; 2003, Coverall Crew
  */

class CC_RelationshipManager
{
	/**
	 * The application's CC_Database database manager object.
	 *
	 * @var CC_Database $dbManager
	 * @access private
	 */

	var $dbManager;


	/**
	 * An array of of foreign key field names.
	 *
	 * $foreignKeysOneToOne array format:
	 *	[ foreignName | information ]
	 *	              [ relatedTable | relatedColumn | displayColumn ]
	 *
	 * @var array $foreignKeysOneToOne
	 * @access private
	 */

	var $foreignKeysOneToOne = array();


	/**
	 * An array of of foreign key field names.
	 *
	 * $foreignKeysOneToMany array format:
	 *  [ foreignName | information ]
	 *	                [ setTable | sourceTable | displayColumn ]
	 *
	 * @var array $foreignKeysOneToMany
	 * @access private
	 */

	var $foreignKeysOneToMany = array();
	
	
	//---------------------------------------------------------------------
	// CONSTRUCTOR: CC_RelationshipManager
	//---------------------------------------------------------------------

	/**
	 * The constructor processes the contents of the CC_RELATIONSHIPS and CC_MANY_RELATIONSHIPS tables and stores the data in the CC_RelationshipManager for access by the application.
	 *
	 * @access public
	 * @param CC_Database $dbManager The application's database manager.
	 */

	function CC_RelationshipManager(&$dbManager, $skipDatabase = false)
	{
		$this->dbManager = &$dbManager;
	
		if ($dbManager != null && !$skipDatabase)
		{
			// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
			// get the one-to-one relationships
	
			$results = $dbManager->doSelect('select FOREIGN_KEY, RELATED_TABLE, DISPLAY_COLUMN from CC_RELATIONSHIPS');
			
			if (PEAR::isError($results))
			{
				trigger_error('CC_RELATIONSHIPS is invalid or does not exist!', E_USER_ERROR);
			}
			else
			{
				while ($row = cc_fetch_row($results))
				{
					$this->addRelationship($row[0], $row[1], $row[2]);
				}
			}
	
			// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
			// get the one-to-many relationships
	
			$results = $dbManager->doSelect("select FOREIGN_KEY, SET_TABLE, SOURCE_TABLE, DISPLAY_COLUMN from CC_MANY_RELATIONSHIPS");

			if (PEAR::isError($results))
			{
				trigger_error('CC_MANY_RELATIONSHIPS is invalid or does not exist!', E_USER_ERROR);
			}
			else
			{
				while ($row = cc_fetch_row($results))
				{
					$this->addManyRelationship($row[0], $row[1], $row[2], $row[3]);
				}
			}
		}
	}
	
	
	//---------------------------------------------------------------------
	// METHOD: getRelatedTable
	//---------------------------------------------------------------------

	/**
	 * This method gets the related table for a given foreign key field.
	 *
	 * @access public
	 * @param string $foreignKey The foreign key field name to look for.
	 * @return string The name of the related table of a given foreign key.
	 */

	function getRelatedTable($foreignKey)
	{
		if (array_key_exists($foreignKey, $this->foreignKeysOneToOne))
		{
			return $this->foreignKeysOneToOne[$foreignKey][0];
		}
		else
		{
			error_log("N2O could not find the following foreign key: $foreignKey");
			return false;
		}
	}
	
	
	//---------------------------------------------------------------------
	// METHOD: getDisplayColumn
	//---------------------------------------------------------------------

	/**
	 * This method gets the display column for a given foreign key field.
	 *
	 * @access public
	 * @param string $foreignKey The foreign key field name to look for.
	 * @return string The name of the display column of a given foreign key.
	 */

	function getDisplayColumn($foreignKey)
	{
		if (array_key_exists($foreignKey, $this->foreignKeysOneToOne))
		{
			return $this->foreignKeysOneToOne[$foreignKey][1];
		}
		else
		{
			trigger_error('N2O could not find the following foreign key: $foreignKey', E_USER_ERROR);
		}
	}
	
	
	//---------------------------------------------------------------------
	// METHOD: getField
	//---------------------------------------------------------------------

	/**
	 * This method returns a foreign key field with the given parameters. This method is called by CC_Record in createFieldObject when it encounters a foreign key field.
	 *
	 * @access private
	 * @param CC_Window $window The window where the field is displayed. Used to register the field's button.
	 * @param string $foreignKey The foreign key field name.
	 * @param mixed $foreignKeyValue The value of the foreign key.
	 * @param string $displayLabel The text label used to describe the foreign key.
	 * @param bool $showButton Whether or not to show the button used to manage the field.
	 * @param string $orderBy The column to order the foreign keys by.
	 * @param CC_ActionHandler $handlerClass The handler to process when clicking on the field's button.
	 * @param string $additionalWhereClause Optional filter when selecting foreign key values.
	 * @return CC_Foreign_Key_Field The newly constructed CC_Foreign_Key_Field.
	 * @see CC_Record::createFieldObject()
	 */

	function &getField($foreignKey, $foreignKeyValue, $displayLabel, $showButton = true, $orderBy = '', $handlerClass = 'CC_Manage_FK_Table_Handler', $additionalWhereClause = '', $unselectedValue = '- Select -', $displayColumn = '', $required = false)
	{
		global $application;
		
		if (!isset($this->foreignKeysOneToOne[$foreignKey]))
		{
			trigger_error('No one-to-one relationship exists for ' . $foreignKey, E_USER_WARNING);
			return false;
		}
		else
		{
			$columnData = $this->foreignKeysOneToOne[$foreignKey];
			
			if (strlen($orderBy) == 0)
			{
				$orderBy = $columnData[1];
			}
			
			if (strlen($columnData[2]) > 0)
			{
				$args = $columnData[2];
				
				$rawArgs = explode('&', $args);
				
				$size = sizeof($rawArgs);
				
				for ($i = 0; $i < $size; $i++)
				{
					if ($index = strpos($rawArgs[$i], '='))
					{
						$key = substr($rawArgs[$i], 0, $index);
						$value = substr($rawArgs[$i], $index + 1);
						
						$$key = $value;
					}
				}
				unset($args, $size);
			}
			
			if (sizeof($columnData) != 0)
			{
				$query = 'select ID, ' . $columnData[1] . ' from ' . $columnData[0] . ' ' . $additionalWhereClause . ' order by ' . $orderBy;
				
				$result = $application->db->doSelect($query);

				$options = array();
				
				if (PEAR::isError($result))
				{
					$options[] = array('', 'Query error: ' . $result->getMessage());
				}
				else
				{				
					while ($row = cc_fetch_row($result))
					{
						$options[] = array($row[0], $row[1]);
					}
				}

				$field = new CC_Foreign_Key_Field($foreignKey, $displayLabel, $columnData[0], $required, $foreignKeyValue, $unselectedValue, $options, $showButton, $handlerClass);

				unset($options);
			}
			else
			{
				$field = new CC_Foreign_Key_Field($foreignKey, $displayLabel, $columnData[0], $required, $foreignKeyValue, ' - EMPTY - ', array());
			}
			
			$field->whereClause = $additionalWhereClause;
			
			return $field;
		}
	}


	//---------------------------------------------------------------------
	// METHOD: getOneToManyField
	//---------------------------------------------------------------------

	/**
	 * This method returns a one to many field with the given parameters. This method is called by CC_Record in createFieldObject when it encounters a CC_OneToMany_Field field.
	 *
	 * @access private
	 * @param string $foreignKey The foreign key field name.
	 * @param mixed $foreignKeyValue The value of the foreign key.
	 * @param string $displayLabel The text label used to describe the foreign key.
	 * @param bool $showButton Whether or not to show the button used to manage the field.
	 * @param CC_ActionHandler $handlerClass The handler to process when clicking on the field's button.
	 * @param string $oneToManyFieldClass The name of the CC_OneToMany_Field class to use.
	 * @return mixed The newly constructed CC_OneToMany_Field field of the given class.
	 * @see CC_Record::createFieldObject()
	 */

	function &getOneToManyField($foreignKey, $foreignKeyValue, $displayLabel, $showButton = true, $handlerClass = 'CC_Manage_FK_Table_Handler', $whereClause = "", $orderBy = "", $setTable = "", $setTableMainKey = "", $setTableSourceKey = "", $sourceTable = "", $displayColumn = "", $minRequired = "0", $numColumns = "1")
	{
		if (!isset($this->foreignKeysOneToMany[$foreignKey]))
		{
			trigger_error('No one-to-many relationship exists for ' . $foreignKey, E_USER_WARNING);
			return false;
		}
		else
		{
			$columnData = $this->foreignKeysOneToMany[$foreignKey];
			
			if (strlen($foreignKeyValue) == 0)
			{
				$foreignKeyValue = '-1';
			}
			
			$field = new CC_Foreign_Key_Multiple_Field($foreignKey, $displayLabel, $setTable, $setTableMainKey, $setTableSourceKey, $sourceTable, $displayColumn, $minRequired, $numColumns);
						
			return $field;
		}
	}


	//---------------------------------------------------------------------
	// METHOD: addRelationship
	//---------------------------------------------------------------------

	/**
	 * This adds a one-to-one relationship.
	 *
	 * @param string $key The column key.
	 * @param string $relatedTable The related table.
	 * @param string $displayColumn The column in the related table holding the displayable value.
	 * @param string $whereClause additional where clause.
	 *
	 */
	
	function addRelationship($key, $relatedTable, $displayColumn, $whereClause = '')
	{
		$this->foreignKeysOneToOne[$key] = array($relatedTable, $displayColumn, $whereClause);
	}


	//---------------------------------------------------------------------
	// METHOD: addManyRelationship
	//---------------------------------------------------------------------

	/**
	 * This adds a one-to-many relationship.
	 *
	 * @param string $key The column key.
	 * @param string $setTable The set table.
	 * @param string $sourceTable The source table.
	 * @param string $displayColumn The column in the related table holding the displayable value.
	 * @see CC_OneToMany_Field
	 *
	 */
	
	function addManyRelationship($key, $setTable, $setTableMainKey, $setTableSourceKey, $sourceTable, $displayColumn)
	{
		$this->foreignKeysOneToMany[$key] = array($setTable, $setTableMainKey, $setTableSourceKey, $sourceTable, $displayColumn);
	}


}

?>