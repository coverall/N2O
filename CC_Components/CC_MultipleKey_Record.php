<?php
// $Id: CC_MultipleKey_Record.php,v 1.9 2005/12/09 03:56:11 jamie Exp $
//=======================================================================
// CLASS: CC_MultipleKey_Record
//=======================================================================

/**
 * This class handles adding, viewing and editing record screens in the application.
 * This is a subclass of CC_Record, this class provides access to records that require
 * multiple keys.
 *
 * @package N2O
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

class CC_MultipleKey_Record extends CC_Record
{
	/**
     * Array of id's (keys) for the record.
     *
     * @var array $id
     * @access private
     * @see getId()
     * @see setId()
     */	

	var $id;


	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_MultipleKey_Record
	//-------------------------------------------------------------------

	/**
	 * This constructor instantiates all given fields and, if the record already exists in the database (ie. it has a valid id), the field values are set to those in the database. All fields must appear in the given table as well as be defined in the CC_FIELDS table so that N2O knows what type of N2O fields they are.
	 *
	 * @access public
	 * @param string $fieldList A comma-delimited list of fields to include in the CC_Record object.
	 * @param string $table The name of the table the record belongs to.
	 * @param bool $editable Whether or not the record is editable. It is not by default.
	 * @param array $id The record's id in the table. This value is set to array(-1) for new records.
	 * @param array $idColumnName The record id's column name for the table. This value is set to array('ID') by default.
	 */

	function CC_MultipleKey_Record($fieldList, $table, $editable = false, $id = array(-1), $idColumnName = array('ID'))
	{
		global $application;
		
		$this->window = &$application->getCurrentWindow();
		$this->table = $table;
		$this->editable = $editable;
		
		$idColumnSize = sizeof($idColumnName);
		
		for ($i = 0; $i < $idColumnSize; $i++)
		{
			$this->id[$idColumnName[$i]] = $id[$i];
		}
		
		$fieldNameArray = explode(',', $fieldList);
		
		$key = $this->getKeyID($table, $id);
		$size = sizeof($fieldNameArray);
		$row = false;
		
		if ($id[0] != -1)
		{
			// get the record from the database
			$selectQuery = 'select ';
			
			for ($i = 0; $i < $size; $i++)
			{
				$fieldType = $application->fieldManager->getFieldType($fieldNameArray[$i]);
				
				switch (strtolower($fieldType))
				{
					case 'cc_credit_card_field':
					{
						$selectQuery .= 'decode(' . $fieldNameArray[$i] . ', \'' . $application->db->getEncodePassword() . '\') as ' . $fieldNameArray[$i];
						
						break;
					}
					default:
					{
						$selectQuery .= $fieldNameArray[$i];
						break;
					}
				}
				
				if ($size > $i + 1)
				{
					$selectQuery .= ', ';
				}
			}
			
			$selectQuery .= ' from ' . $this->table . ' where ';
			
			for ($i = 0; $i < $idColumnSize; $i++)
			{
				$selectQuery .= $idColumnName[$i] . ' =\'' . $id[$i] . '\'';
				
				if ($i + 1 < $idColumnSize)
				{
					$selectQuery .= ' and ';
				}
			}
				
			//echo $selectQuery . "<p>";
			
			$results = $application->db->doSelect($selectQuery);
			
			if (PEAR::isError($results))
			{
				$this->initialize($fieldNameArray);
				trigger_error('Query failed: ' . $results->getMessage() . '. The query was: ' . $selectQuery, E_USER_WARNING);
			}
			
			$row = cc_fetch_assoc($results);
		}

		$this->initialize($fieldNameArray, $size, $row);

		unset($row, $size, $fieldNameArray);		
   	}	
	
	
	//-------------------------------------------------------------------
	// METHOD: getKeyID
	//-------------------------------------------------------------------

	/**
	 * This method gets the record's key id. The key id is used to uniquely identify a record object in a window.
	 *
	 * @access public
	 * @param string $table The name of the table the record belongs to.
	 * @param int $id The record id in the table.
	 * @return string The record's key id.
	 */

	function getKeyID($table, $id = array(-1))
	{
		$idSize = sizeof($id);
		
		$uniqueKey = $table;
		
		$keys = array_keys($id);
		
		for ($i = 0; $i < $idSize; $i++)
		{
			$uniqueKey .= '_' . str_replace('.', '_', $id[$keys[$i]]);
		}
		
		unset($keys);
		
		return $uniqueKey;
	}

	
	//-------------------------------------------------------------------
	// METHOD: buildUpdateQuery()
	//-------------------------------------------------------------------

	/** 
	  * This function builds a database update query for database-updateable fields in the record.	  		
	  *
	  * @access public
	  * @return string An update query to add databsae-updateable fields to the database.
	  * @see CC_Field::addToDatabase()
	  * @see CC_Field::setAddToDatabase()
	  * @see getDatabaseUpdateableFieldNames()
	  */

	function buildUpdateQuery()
	{
		// CC_Database fields should be excluded from the update
		global $application;
		
		$updateQuery = 'update ' . $this->table . ' set ';
			
		$keys = $this->getDatabaseUpdateableFieldNames();
		
		$updatedField = false;

		$size = sizeof($keys);

		for ($i = 0; $i < $size; $i++)
		{
			$field = $this->fields[$keys[$i]];
			
			if ($field->addToDatabase()) // && !$field->isReadOnly())
			{
				if ($updatedField && ($i > 0)) // + 1 < sizeof($keys)
				{
					$updateQuery .= ", ";
				}
				
				if ($field->getEncode())
				{
					$updateQuery .= $keys[$i] . '=encode(\'' . $field->getEscapedValue() . '\', \'' . $application->db->getEncodePassword() . '\')';
				}
				else if ($field->getPassword())
				{
					if (strlen($field->getValue()) == 32)
					{
						$updateQuery .= $keys[$i] . '=\'' . $field->getEscapedValue() . '\'';
					}
					else
					{
						$updateQuery .= $keys[$i] . '=\'' . md5($field->getEscapedValue()) . '\'';
					}
				}
				else
				{	
					$updateQuery .= $keys[$i] . '=\'' . $field->getEscapedValue() . '\'';
				}

				$updatedField = true;
			}
			
			unset($field);
		}
		
		unset($size);
		
		$updateQuery .= ' where ';

		$idSize = sizeof($this->id);
		
		$keys = array_keys($this->id);
		
		for ($i = 0; $i < $idSize; $i++)
		{
			$updateQuery .= $keys[$i] . ' =\'' . $this->id[$keys[$i]] . '\'';
			
			if ($i + 1 < $idSize)
			{
				$updateQuery .= ' and ';
			}
		}
		
		return $updateQuery;
	}
	
	
	
	//-------------------------------------------------------------------
	// METHOD: buildInsertQuery()
	//-------------------------------------------------------------------

	/** 
	  * This function builds a database insert query for database-updateable fields in the record.
	  *
	  * @access public
	  * @return string An update query to add database-updateable fields to the database.
	  * @see CC_Field::addToDatabase()
	  * @see CC_Field::setAddToDatabase()
	  * @see getDatabaseUpdateableFieldNames()
	  */

	function buildInsertQuery()
	{
		// CC_Database fields should be excluded from the update
		global $application;
		
		$insertQuery = 'insert into ' . $this->table . ' (';
	
		$idSize = sizeof($this->id);
		
		$idkeys = array_keys($this->id);
		
		$keys = $this->getDatabaseUpdateableFieldNames();
		
		for ($i = 0; $i < $idSize; $i++)
		{
			if (!in_array($idkeys[$i], $keys))
			{
				$insertQuery .= $idkeys[$i] . ',';
			}
		}
		
		$insertQuery .= ' DATE_ADDED, ';

		$size = sizeof($keys);
		
		$valueList = '';
		$fieldList = '';

		for ($i = 0; $i < $size; $i++)
		{
			$field = &$this->fields[$keys[$i]];
			
			if ($field->addToDatabase()) // && !$field->isReadOnly())
			{
				$fieldList .= $keys[$i];
				
				if ($field->getEncode())
				{
					$valueList .= 'encode(\'' . $field->getEscapedValue() . '\', \'' . $application->db->getEncodePassword() . '\')';
				}
				else if ($field->getPassword())
				{
					$valueList .= '\'' . md5($field->getEscapedValue()) . '\'';
				}
				else
				{	
					$valueList .= '\'' . $field->getEscapedValue() . '\'';
				}
				
				if ($i + 1 < sizeof($keys))
				{
					$fieldList .= ', ';
					$valueList .= ', ';
				}
			}
					
			unset($field);
		}
		
		unset($size);


		$keyValues = '';
		for ($i = 0; $i < $idSize; $i++)
		{
			if (!in_array($idkeys[$i], $keys))
			{
				$keyValues .= "'" . $this->id[$idkeys[$i]] . "',";
			}
		}
		
		$insertQuery .= $fieldList . ') values (' . $keyValues . ' now(), ' . $valueList . ')';
		
		unset($fieldList, $keyValues, $valueList);

		return $insertQuery;
	}


	//-------------------------------------------------------------------
	// METHOD: setId()
	//-------------------------------------------------------------------

	function setId($column, $id)
	{
		$this->id[$column] = $id;
	}

}

?>