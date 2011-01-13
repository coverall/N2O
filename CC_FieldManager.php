<?php
// $Id: CC_FieldManager.php,v 1.36 2004/12/21 20:18:47 patrick Exp $
//=======================================================================
// CLASS: CC_FieldManager
//=======================================================================

/** 
  * The CC_FieldManager uses the CC_FIELDS table to store pertinent information about each field in the application. Each column name must be unique across the entire application as only one place is reserved in the database for each field.
  *
  * The CC_FIELDS table has the following columns for each field:
  * -------------------------------------------------------------
  *
  * 1) COLUMN_NAME  - A unique name to identify the field in the application (ie. FIRST_NAME).
  * 2) FIELD_TYPE   - An N2O field derived from the CC_FIELD class (ie. CC_Text_Field).
  * 3) DISPLAY_NAME - The text label associated with the field (ie. First Name).
  * 4) ARGS         - A name/value list of optional arguments (ie. sex=female&maxlength=15).
  *
  * @package CC_Managers
  * @access public
  * @author The Crew <N2O@coverallcrew.com>
  * @copyright Copyright &copy; 2003, Coverall Crew
  */

class CC_FieldManager
{
	//---------------------------------------------------------------------
	
	/**
	 * An array of field names and associated data (ie. fieldType, displayName, args).
	 *
	 * $fieldNames array format:
	 * [ fieldName | information ]
	 *              [ fieldType | displayName | args]
	 *	
	 * @var array $fieldNames
	 * @access private
	 */

	var $fieldNames = array();		// array of field names.
	
	
	//---------------------------------------------------------------------
	// CONSTRUCTOR: CC_FieldManager
	//---------------------------------------------------------------------

	/**
	 * The constructor processes the contents of the CC_FIELDS table and stores the data in the CC_FieldManager for access by the application.
	 *
	 * @access public
	 * @param CC_Database $dbManager The application's database manager.
	 */

	function CC_FieldManager($dbManager, $skipDatabase = false)
	{
	
		// either something wasn't instantiated properly or
		// the application will be run without a database.
		
		if ($dbManager != null && !$skipDatabase)
		{
			$results = $dbManager->doSelect('select COLUMN_NAME, FIELD_TYPE, DISPLAY_NAME, ARGS from CC_FIELDS');
			
			if (DB::isError($results))
			{
				trigger_error('CC_FIELDS is invalid or does not exist!', E_USER_ERROR);
				
				$query = '';
				if ($dbManager->isPostgres())
				{
					$query = "CREATE TABLE CC_FIELDS (COLUMN_NAME char(128) NOT NULL DEFAULT '',FIELD_TYPE char(128) NOT NULL DEFAULT '',DISPLAY_NAME char(128) NOT NULL DEFAULT '',ARGS varchar(255) not null DEFAULT '',PRIMARY KEY (COLUMN_NAME))";
				}
				else
				{
					$query = "CREATE TABLE CC_FIELDS (COLUMN_NAME char(128) NOT NULL default '',FIELD_TYPE char(128) NOT NULL default '',DISPLAY_NAME char(128) NOT NULL default '',ARGS varchar(255) not null default '',PRIMARY KEY  (COLUMN_NAME)) TYPE=MyISAM";
				}

				trigger_error('CC_FIELDS has been automagically generated!', E_USER_WARNING);
				
				$results = $dbManager->doQuery($query);
			}
			else
			{
				while ($row = cc_fetch_row($results))
				{
					$this->addField($row[0], $row[1], $row[2], $row[3]);
				}
			}
			
			$this->fieldNames = array_change_key_case($this->fieldNames, CASE_LOWER);
		}

	// --------------------------------------------------------------------
		$this->addField('ID', 'CC_IntegerNumber_Field', 'ID');
		$this->addField('DATE_ADDED', 'CC_DateTime_Field', 'Date Added');
		$this->addField('LAST_MODIFIED', 'CC_Timestamp_Field', 'Last Modified');
	// --------------------------------------------------------------------
	}


	//---------------------------------------------------------------------
	// METHOD: addField
	//---------------------------------------------------------------------

	/**
	 * This method adds a field to the CC_FieldManager. This is useful when the database, and therefore the CC_FIELDS table, is not accessible.
	 *
	 * @access public
	 * @param CC_Database $dbManager The application's database manager.
	 */

	function addField($fieldName, $fieldType, $displayName, $argstring = '')
	{
		$fieldData = array();
		
		$fieldData[0] = $fieldType;
		$fieldData[1] = $displayName;
		
		if (strlen($argstring))
		{
			$rawArgs = explode('&', $argstring);
			
			$args = &new stdclass();
			
			$size = sizeof($rawArgs);
			
			for ($i = 0; $i < $size; $i++)
			{
				if ($index = strpos($rawArgs[$i], '='))
				{
					$key = substr($rawArgs[$i], 0, $index);
					$args->$key = substr($rawArgs[$i], $index + 1);
					unset($key);
				}
			}
			
			$fieldData[2] = $args;
			
			unset($args, $size);
		}
		else
		{
			// Remove error message for 'Undefined index:  args'
			$fieldData[2] = '';
		}

		$this->fieldNames[strtolower($fieldName)] = $fieldData;

		//echo '<div class="small">' . $fieldName . '(' . $fieldData['fieldType'] . ')' . '(' . $fieldData['displayName'] . ')' . '(' . $fieldData['args'] . ')</div>';
		
		unset($fieldData);
		unset($rawArgs);
		unset($args);
		
		//$this->fieldNames = array_change_key_case($this->fieldNames, CASE_LOWER);
	}
	
	
	//---------------------------------------------------------------------
	// METHOD: getFieldType
	//---------------------------------------------------------------------

	/**
	 * This method returns the N2O field type for a field of a given name.
	 *
	 * @access public
	 * @param string $column The column name you want the field type for.
	 * @return string The N2O field type of the field with the given name.
	 */

	function getFieldType($column)
	{
		$column = strtolower($column);
			
		if (array_key_exists($column, $this->fieldNames))
		{
			$columnData = $this->fieldNames[$column];
		
			return $columnData[0];
		}
		else
		{
			return 'CC_Text_Field';
		}
	}

	
	//---------------------------------------------------------------------
	// METHOD: getFieldData
	//---------------------------------------------------------------------

	/**
	 * This method returns the field data for a field of a given name.
	 *
	 * @access public
	 * @param string $column The column name you want the field data for.
	 * @return array The field data of the field with the given name (ie. fieldType, displayName, args).
	 */

	function getFieldData($column)
	{
		$column = strtolower($column);
			
		if (array_key_exists($column, $this->fieldNames))
		{
			return $this->fieldNames[$column];
		}
		else
		{
			trigger_error('CC_FieldManager::getFieldData(): No field data exists for the column "' . $column . '". Is it defined in the CC_Fields table?');
		}
	}

	
	//---------------------------------------------------------------------
	// METHOD: getDisplayName
	//---------------------------------------------------------------------

	/**
	 * This method returns the display name for a field of a given name.
	 *
	 * @access public
	 * @param string $column The column name you want the display name for.
	 * @return string The display name of the field with the given name.
	 */

	function getDisplayName($column)
	{
		if (array_key_exists(strtolower($column), $this->fieldNames))
		{
			return $this->fieldNames[strtolower($column)][1];
		}
		else
		{
			return $column;
		}
	}
}

?>