<?php
// $Id: CC_Record.php,v 1.154 2010/06/04 17:40:23 patrick Exp $
//=======================================================================
// CLASS: CC_Record
//=======================================================================

/**
 * This class handles adding, viewing and editing record screens in the application.
 *
 * @package N2O
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

class CC_Record extends CC_Component
{
	/**
     * An array of the record's field objects.
     *
     * @var array $fields An array of CC_Field objects.
     * @access private
     * @see getField()
     */	

	var $fields;


	/**
     * The name of the main table this record belongs to.
     *
     * @var string $table
     * @access private
     */	

	var $table;


	/**
     * The id of the record.
     *
     * @var string $id
     * @access private
     * @see getId()
     * @see setId()
     */	

	var $id;


	/**
     * The idColumnName used for the record, defaults to 'ID'
     *
     * @var string $idColumnName
     * @access private
     */	

	var $idColumnName;


	/**
     * Indicated whether or not the record is editable or not.
     *
     * @var bool $editable
     * @access private
     * @see isEditable()
     * @see setEditable()
     */	

	var $editable;
	
	
	/**
     * Indicated whether or not the record is disabled or not.
     *
     * @var bool $disabled
     * @access private
     * @see isDisabled()
     * @see setDisabled()
     */	

	var $disabled;


	/**
     * The hidden record key field
     *
     * @var string $hiddenKey
     * @access private
     * @todo Where is this used? or is it?
     */	

	var $hiddenKey;


	/**
     * An array of handlers.
     *
     * @var array $handlers An array of CC_Action_Handler objects.
     * @access private
     * @todo I don't think this is used anywhere.
     */	
	
	var $handlers = array();


	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Record
	//-------------------------------------------------------------------

	/**
	 * This constructor instantiates all given fields and, if the record already exists in the database (ie. it has a valid id), the field values are set to those in the database. All fields must appear in the given table as well as be defined in the CC_FIELDS table so that N2O knows what type of N2O fields they are.
	 *
	 * @access public
	 * @param string $fieldList A comma-delimited list of fields to include in the CC_Record object.
	 * @param string $table The name of the table the record belongs to.
	 * @param bool $editable Whether or not the record is editable. It is not by default.
	 * @param int $id The record's id in the table. This value is set to -1 for new records.
	 * @param string $idColumnName The record id's column name for the table. This value is set to 'ID' by default.
	 */


	function CC_Record($fieldList, $table, $editable = false, $id = -1, $idColumnName = 'ID')
	{
		global $application;
		
		$this->table = $table;
		$this->editable = $editable;
		$this->id = $id;
		$this->idColumnName = $idColumnName;
		
		$fieldNameArray = explode(',', $fieldList);
		
		$key = $this->getKeyID($table, $id);
		$size = sizeof($fieldNameArray);
		$row = false;
		
		if ($id != -1)
		{
			// get the record from the database
			$selectQuery = 'select ';
			
			for ($i = 0; $i < $size; $i++)
			{
				$fieldType = $application->fieldManager->getFieldType($fieldNameArray[$i]);
				
				switch ($fieldType)
				{
					case 'cc_credit_card_field':
					{
						$selectQuery .= 'decode(' . $fieldNameArray[$i] . ', \'' . $application->db->getEncodePassword() . '\') as ' . $fieldNameArray[$i] . ', ';
					}
					break;
					
					case 'cc_foreign_key_multiple_field':
					{
						$foreignKeys[] = $fieldNameArray[$i];
					}
					break;
					
					default:
					{
						$selectQuery .= $fieldNameArray[$i] . ', ';
					}
					break;
				}
				
				unset($fieldType);
			}
			
			$selectQuery = substr($selectQuery, 0, strlen($selectQuery) - 2);
			
			$selectQuery .= ' from ' . $this->table . ' where ' . $this->idColumnName . '=\'' . $id . '\'';
			
			$row = $application->db->doGetRow($selectQuery);
			
			//echo $selectQuery;
			
			if (PEAR::isError($row))
			{
				trigger_error('Query failed: ' . $row->getMessage() . '. The query was: ' . $selectQuery, E_USER_WARNING);
				eval('$this = $row;');
				return;
			}
			else
			{
				if (!$row)
				{
					trigger_error('The record with id ' . $this->id . ' doesn\'t exist. The query was: ' . $selectQuery, E_USER_WARNING);
					eval('$this = PEAR::raiseError(\'The record with id \' . $this->id . \' does not exist. The query was: \' . $selectQuery, 0, PEAR_ERROR_RETURN);');
					return;
				}
			}
			
			unset($selectQuery, $results);
		}
		
		$this->initialize($fieldNameArray, $size, $row);

		unset($row, $size, $fieldNameArray);		
   	}	
	

	//-------------------------------------------------------------------
	// METHOD: initialize
	//-------------------------------------------------------------------
	
	function initialize($fieldNameArray, $size, $row)
	{
		global $application;

		for ($i = 0; $i < $size; $i++)
		{
			if (!$ccFieldData = $application->fieldManager->getFieldData($fieldNameArray[$i]))
			{
				$ccFieldData = array();
				$ccFieldData[0] = 'cc_text_field';
				$ccFieldData[1] = $fieldNameArray[$i];
				$ccFieldData[2] = new stdclass();
			}

			$className = $ccFieldData[0];
			
			if ($className == 'cc_foreign_key_multiple_field')
			{
				$label = $ccFieldData[1];
				$relationsShipData = $ccFieldData[2];
				$value = 0;
				
				$application->relationshipManager->addManyRelationship($fieldNameArray[$i], $relationsShipData->setTable, $relationsShipData->setTableMainKey, $relationsShipData->setTableSourceKey, $relationsShipData->sourceTable, $relationsShipData->displayColumn);

				$fieldObject = call_user_func(array((string)$className, 'getInstance'), $className, $fieldNameArray[$i], $label, "", $ccFieldData[2], 1);
				
				if (!$fieldObject->isReadOnly())
				{
					$fieldObject->setReadOnly(!$this->editable);
				}
				
				// set the summary label, if it exists
				if (isset($ccFieldData[2]->summaryLabel))
				{
					$fieldObject->setSummaryLabel($ccFieldData[2]->summaryLabel);
				}
				
				// make sure field keys are all upper case in this array
				$this->fields[strtoupper($fieldNameArray[$i])] = &$fieldObject;
				
				$fieldObject->setValue($value, $this->id);
				
				unset($fieldObject, $ccFieldData, $className, $label);
			}
			else if ($className == 'cc_foreign_key_field')
			{
				$label = $ccFieldData[1];
				$relationsShipData = $ccFieldData[2];
				
				$value = ($row ? $row[$fieldNameArray[$i]] : (isset($ccFieldData[2]->value) ? $ccFieldData[2]->value : false));
				
				$application->relationshipManager->addRelationship($fieldNameArray[$i], $relationsShipData->sourceTable, $relationsShipData->displayColumn);
				
				if (!isset($relationsShipData->required))
				{
					$relationsShipData->required = false;
				}
				
				$fieldObject = call_user_func(array((string)$className, 'getInstance'), $className, $fieldNameArray[$i], $label, "", $ccFieldData[2], $relationsShipData->required);
				
				if (!$fieldObject->isReadOnly())
				{
					$fieldObject->setReadOnly(!$this->editable);
				}
				
				// set the summary label, if it exists
				if (isset($ccFieldData[2]->summaryLabel))
				{
					$fieldObject->setSummaryLabel($ccFieldData[2]->summaryLabel);
				}
				
				// make sure field keys are all upper case in this array
				$this->fields[strtoupper($fieldNameArray[$i])] = &$fieldObject;
				
				$fieldObject->setValue($value);
				
				unset($fieldObject, $ccFieldData, $className, $label);
			}
			else
			{
				$label       = $ccFieldData[1];
				$required    = (isset($ccFieldData[2]->required) ? $ccFieldData[2]->required : false);
				$value       = ($row ? $row[$fieldNameArray[$i]] : (isset($ccFieldData[2]->value) ? $ccFieldData[2]->value : false));
				
				$fieldObject = call_user_func(array((string)$className, 'getInstance'), $className, $fieldNameArray[$i], $label, $value, $ccFieldData[2], $required);
				
				if (!$fieldObject->isReadOnly())
				{
					$fieldObject->setReadOnly(!$this->editable);
				}
				
				// set the summary label, if it exists
				if (isset($ccFieldData[2]->summaryLabel))
				{
					$fieldObject->setSummaryLabel($ccFieldData[2]->summaryLabel);
				}
				
				// make sure field keys are all upper case in this array
				$this->fields[strtoupper($fieldNameArray[$i])] = &$fieldObject;
				
				unset($fieldObject, $ccFieldData, $className, $label, $required, $value);
			}

		}
	
		unset($row, $size, $fieldNameArray);		
	}

	
	//-------------------------------------------------------------------
	// METHOD: getField
	//-------------------------------------------------------------------

	/**
	 * This method gets a field from the record of a given name.
	 *
	 * @access public
	 * @param string $fieldName The name of the record to return.
	 * @return CC_Field A reference to a field of the given name.
	 */

	function &getField($fieldName)
	{
		$fieldName = strtoupper($fieldName);
		
		if (isset($this->fields[$fieldName]))
		{
			return $this->fields[$fieldName];
		}
		else
		{
			trigger_error("The field named $fieldName could not be found in this record ($this->table_$this->id).");
		}
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

	function getKeyID($table, $id = -1)
	{
		return $table . '_' . str_replace('.', '_', $id);
	}



	//-------------------------------------------------------------------
	// METHOD: getRecordKey
	//-------------------------------------------------------------------

	/**
     * Gets the record key of the record
     *
     * @access public
     * @return mixed A string representing the record's key
     */	

	function getRecordKey()
	{
		return ($this->getKeyID($this->table, $this->id)) . '|';
	}

	
	//-------------------------------------------------------------------
	// METHOD: updateForeignKeys
	//-------------------------------------------------------------------
	
	/**
	 * This method updates a record's foreign key field who's options may have been changed if a user just returned from managing (ie. editing, adding or deleting foreign keys).
	 *
	 * @access public
	 */

	function updateForeignKeys()
	{
		$application = &$_SESSION['application'];
		
		$keys = array_keys($this->fields);
		
		$size = sizeof($keys);
		
		for ($i = 0; $i < $size; $i++)
		{
			$field = &$this->fields[$keys[$i]];
			
			if ($field->foreignKey)
			{
				if ($application->hasArgument('recentlyAddedForeignKeyValue' . $field->name))
				{
					$selectedValue = $application->getArgument('recentlyAddedForeignKeyValue' . $field->name);
					$application->clearArgument('recentlyAddedForeignKeyValue' . $field->name);
				}
				else
				{
					$selectedValue = $field->getValue();
				}
				
				$newField = &$application->relationshipManager->getField($field->name, $selectedValue, $field->label, false, '', 'CC_Manage_FK_Table_Handler', $field->whereClause);
				
				$field->setOptions($newField->getOptions());
				
				$field->setSelectedIndex($newField->getSelectedIndex());
				
				unset($newField);
			}
		}
		
		unset($size);
	}


	//-------------------------------------------------------------------
	// METHOD: doBatchChange
	//-------------------------------------------------------------------

	/** 
	 * This method sets whether or not the record (and it's associated fields) are editable.
	 *
	 * @access private
	 * @param bool $editable Whether or not the record is editable.
	 */

	function doBatchChange($functionName, $value)
	{
		$keys = array_keys($this->fields);
		
		$size = sizeof($keys);
		
		for ($i = 0; $i < $size; $i++)
		{
			$this->fields[$keys[$i]]->$functionName($value);
		}
		
		unset($keys, $size);
	}


	//-------------------------------------------------------------------
	// METHOD: setEditable
	//-------------------------------------------------------------------

	/** 
	 * This method sets whether or not the record (and it's associated fields) are editable.
	 *
	 * @access public
	 * @param bool $editable Whether or not the record is editable.
	 * @see isEditable()
	 */

	function setEditable($editable)
	{
		$this->editable = $editable;
		
		$this->doBatchChange('setReadOnly', !$editable);
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setDisabled
	//-------------------------------------------------------------------

	/** 
	 * This method sets whether or not the record (and it's associated fields) are editable.
	 *
	 * @access public
	 * @param bool $editable Whether or not the record is editable.
	 * @see isEditable()
	 */

	function setDisabled($disabled)
	{
		$this->disabled = $disabled;
		
		$this->doBatchChange('setDisabled', $disabled);
	}

	
	//-------------------------------------------------------------------
	// METHOD: isDisabled
	//-------------------------------------------------------------------

	/** 
	 * This method gets whether or not the record (and it's associated fields) are disabled.
	 *
	 * @access public
	 * @return bool Whether or not the record is disabled.
	 * @see setDisabled()
	 */

	function isDisabled()
	{
		return $this->disabled;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: isEditable
	//-------------------------------------------------------------------

	/** 
	 * This method gets whether or not the record (and it's associated fields) are editable.
	 *
	 * @access public
	 * @return bool Whether or not the record is editable.
	 * @see setEditable()
	 */

	function isEditable()
	{
		return $this->editable;
	}


	//-------------------------------------------------------------------
	// METHOD: setRequiredFields
	//-------------------------------------------------------------------
	
	/** 
	  * This method sets the required status of one or more fields. To set more than one argument, simply pass more arguments into the method.
	  *
	  * ie. setRequiredFields(true, $field1, $field2, field3, ...);
	  *
	  * @access public
	  * @param bool $required Whether or not the field(s) is/are required.
	  * @param CC_Field $fields, ... The field names to set.
	  */
	
	function setRequiredFields($required, $field)
	{
		$argumentCount = func_num_args();
		
		for ($i = 0; $i < $argumentCount - 1; $i++)
		{
			if (array_key_exists(func_get_arg($i + 1), $this->fields))
			{
				$this->fields[func_get_arg($i + 1)]->setRequired($required);
			}
			else
			{
				trigger_error('Tried to call setRequiredFields on non-existent field, ' . func_get_arg($i + 1), E_USER_WARNING);
			}
		}
	}
	

	//-------------------------------------------------------------------
	// METHOD: setRequired
	//-------------------------------------------------------------------
	
	/** 
	  * This method sets the required status of ALL the record's fields.
	  *
	  * @access public
	  * @param bool $required Whether or not the field(s) is/are required.
	  * @see CC_Field::setRequired()
	  */

	function setRequired($required)
	{
		$this->doBatchChange('setRequired', $required);
	}


	//-------------------------------------------------------------------
	// METHOD: setShowAsterisk
	//-------------------------------------------------------------------
	
	/** 
	  * This function will call setShowAsterisk() on all the records' fields. This is if you want to turn of the asterisk that gets displayed on fields that are required.
	  *
	  * @access public
	  * @param bool $show Whether or not an asterisk should be shown next to a required field.
	  * @see CC_Field::setShowAsterisk()
	  */

	function setShowAsterisk($show)
	{
		$this->doBatchChange('setShowAsterisk', $show);
	}


	//-------------------------------------------------------------------
	// METHOD: errorsExist
	//-------------------------------------------------------------------
	
	/** 
	  * This function returns whether any of the records' fields has an error associated with it.
	  *
	  * @access public
	  * @return bool Whether or not one or more of this records' fields has an error.
	  * @see CC_Field::hasError()
	  */

	function errorsExist()
	{
		$keys = array_keys($this->fields);
		
		$errors = false;
		
		$size = sizeof($keys);
		
		for ($i = 0; $i < $size; $i++)
		{
			if ($this->fields[$keys[$i]]->hasError())
			{
				unset($keys, $size);
				return true;
			}
		}
		
		unset($keys, $size);
		
		return $errors;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getDatabaseUpdateableFieldNames()
	//-------------------------------------------------------------------

	/** 
	  * This function returns an array of field names that are to be added to the database.	  		
	  *
	  * @access public
	  * @return array An array of strings representing the names of fields that are to be added to the database. This is used when building update and insert queries for adding/updating recrods in the database.
	  * @see CC_Field::addToDatabase()
	  * @see CC_Field::setAddToDatabase()
	  * @see buildUpdateQuery()
	  * @see buildInsertQuery()
	  */

	function getDatabaseUpdateableFieldNames()
	{
		$fieldNames = array();
		
		$keys = array_keys($this->fields);
		
		$size = sizeof($keys);
		
		for ($i = 0; $i < $size; $i++)
		{
			$currentField = &$this->fields[$keys[$i]];

			if ($currentField->addToDatabase())
			{
				// keep all our fieldnames uppercase internall to cc_record.
				$fieldNames[] = strtoupper($this->fields[$keys[$i]]->getName());
			}
			
			unset($currentField);
		}
		
		unset($size);

		return $fieldNames;
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
		error_log('CC_Record->buildUpdateQuery() is deprecated. Use CC_Record->update() instead.');

		// CC_Database fields should be excluded from the update
		$application = &$_SESSION['application'];
		
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
		
		$updateQuery .= ' where ' . $this->idColumnName . ' =\'' . $this->id . '\'';
		
		return $updateQuery;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: buildOrderedInsertQuery()
	//-------------------------------------------------------------------

	/** 
	  * This function builds a database insert query for database-updateable fields in the record. The record is inserted at the given index.   		
	  *
	  * @access public
	  * @param int $insertPosition The position where this record should be inserted.
	  * @return string An update query to add databsae-updateable fields to the database.
	  * @see CC_Field::addToDatabase()
	  * @see CC_Field::setAddToDatabase()
	  * @see getDatabaseUpdateableFieldNames()
	  */


	function buildOrderedInsertQuery($insertPosition = 0)
	{
		// CC_Database fields should be excluded from the update
		$application = &$_SESSION['application'];
		
		$insertQuery = 'insert into ' . $this->table . ' (DATE_ADDED, SORT_ID, ';
			
		$keys = $this->getDatabaseUpdateableFieldNames();
		
		$size = sizeof($keys);
		
		$valueList = '';
		$fieldList = '';
		
		for ($i = 0; $i < $size; $i++)
		{
			if ($keys[$i] != "SORT_ID")
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
		}
		
		unset($size);
		
		$insertQuery .= $fieldList . ') values (now(), ' . $insertPosition . ', ' . $valueList . ')';

		return $insertQuery;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: insert()
	//-------------------------------------------------------------------

	/** 
	  * This function inserts data into the database for this record, including set tables
	  *
	  * @access public
	  * @return string An update query to add database-updateable fields to the database.
	  * @see CC_Field::addToDatabase()
	  * @see CC_Field::setAddToDatabase()
	  * @see getDatabaseUpdateableFieldNames()
	  */

	function insert()
	{
		// CC_Database fields should be excluded from the update
		$application = &$_SESSION['application'];
		
		if ($application->db->isPostgres())
		{
			$insertQuery = 'insert into ' . $this->table . ' (' . $this->idColumnName . ', DATE_ADDED, ';
		}
		else // if ($application->db->isMysql())
		{
			$insertQuery = 'insert into ' . $this->table . ' (DATE_ADDED, ';
		}

		$keys = $this->getDatabaseUpdateableFieldNames();
		
		$size = sizeof($keys);
		
		$valueList = '';
		$fieldList = '';

		$oneToManyFields = array();
		
		for ($i = 0; $i < $size; $i++)
		{
			$field = &$this->fields[$keys[$i]];
			$this->fields[$keys[$i]]->updated = false;
			
			// one to many field
			if (get_class($field) == 'cc_foreign_key_multiple_field')
			{
				$oneToManyFields[] = $field;
			}
			else if ($field->addToDatabase()) // && !$field->isReadOnly())
			{
				$fieldList .= $keys[$i] . ', ';
				
				if ($field->getEncode())
				{
					$valueList .= 'encode(\'' . $field->getEscapedValue() . '\', \'' . $application->db->getEncodePassword() . '\'), ';
				}
				else if ($field->getPassword())
				{
					$valueList .= '\'' . md5($field->getEscapedValue()) . '\', ';
				}
				else
				{	
					$valueList .= '\'' . $field->getEscapedValue() . '\', ';
				}				
			}
					
			unset($field);
		}
		
		$fieldList = substr($fieldList, 0, $fieldList - 2);
		$valueList = substr($valueList, 0, $valueList - 2);
		
		unset($size);
		
		switch ($application->db->_databaseType)
		{
			case DB_POSTGRES:
				$tableIdSequence = strtolower($this->table . '_id_seq');
				$insertQuery .= $fieldList . ') values (nextval(\'' . $tableIdSequence . '\'), now(), ' . $valueList . ')';
			break;
			
			case DB_MYSQL:
			default:
				$insertQuery .= $fieldList . ') values (now(), ' . $valueList . ')';
			break;
		}

		$recordId = $application->db->doInsert($insertQuery);
		
		if (!PEAR::isError($recordId))
		{
			$this->setId($recordId);
			// process all the foreign_key_multiple_fields
			for ($i = 0; $i < sizeof($oneToManyFields); $i++)
			{
				$field = $oneToManyFields[$i];
				
				$sourceTableIds = array_keys($field->checkboxes);
				
				for ($j = 0; $j < sizeof($sourceTableIds); $j++)
				{
					if ($field->checkboxes[$sourceTableIds[$j]]->isChecked())
					{
						$setId = $sourceTableIds[$j];
						$query = 'insert into ' . $field->setTable . '(' . $field->setTableMainKey . ', ' . $field->setTableSourceKey . ') values (' . $this->getId() . ', ' . $setId . ')';
						$result = $application->db->doInsert($query);
						
						if (PEAR::isError($result))
						{
							$window->setErrorMessage($recordId->getMessage());
							return false;
						}

					}
				}
			}
			
			return $recordId;
		}
		else
		{
			$window->setErrorMessage($recordId->getMessage());
			return false;
		}
	}


	//-------------------------------------------------------------------
	// METHOD: update()
	//-------------------------------------------------------------------

	/** 
	  * This function updates data into the database for this record, including set tables
	  *
	  * @access public
	  * @see CC_Field::addToDatabase()
	  * @see CC_Field::setAddToDatabase()
	  * @see getDatabaseUpdateableFieldNames()
	  */

	function update()
	{
		// CC_Database fields should be excluded from the update
		global $application;
		
		$updateQuery = 'update ' . $this->table . ' set ';
		
		$keys = $this->getDatabaseUpdateableFieldNames();		
		$size = sizeof($keys);
		
		$oneToManyFields = array();

		for ($i = 0; $i < $size; $i++)
		{
			$field = $this->fields[$keys[$i]];
			
			$this->fields[$keys[$i]]->updated = false;
			
			// one to many field
			if (get_class($field) == 'cc_foreign_key_multiple_field')
			{
				$oneToManyFields[] = $field;
			}
			else if ($field->addToDatabase()) // && !$field->isReadOnly())
			{
				if ($field->getEncode())
				{
					$updateQuery .= $keys[$i] . '=encode(\'' . $field->getEscapedValue() . '\', \'' . $application->db->getEncodePassword() . '\'), ';
				}
				else if ($field->getPassword())
				{
					if (strlen($field->getValue()) == 32)
					{
						$updateQuery .= $keys[$i] . '=\'' . $field->getEscapedValue() . '\', ';
					}
					else
					{
						$updateQuery .= $keys[$i] . '=\'' . md5($field->getEscapedValue()) . '\', ';
					}
				}
				else
				{	
					$updateQuery .= $keys[$i] . '=\'' . $field->getEscapedValue() . '\', ';
				}

				$updatedField = true;
			}
			
			unset($field);
		}
		
		unset($size);
		
		$updateQuery = substr($updateQuery, 0, strlen($updateQuery) - 2);
		
		$updateQuery .= ' where ' . $this->idColumnName . ' =\'' . $this->id . '\'';		
		
		$result = $application->db->doUpdate($updateQuery);
		
		if (!PEAR::isError($result))
		{
			for ($i = 0; $i < sizeof($oneToManyFields); $i++)
			{
				$field = $oneToManyFields[$i];

				// delete existing set entries				
				$query = 'delete from ' . $field->setTable . ' where ' . $field->setTableMainKey . ' = ' . $this->getId();
				$result = $application->db->doDelete($query);
				
				// insert set entries afresh
				$sourceTableIds = array_keys($field->checkboxes);				
				for ($j = 0; $j < sizeof($sourceTableIds); $j++)
				{
					if ($field->checkboxes[$sourceTableIds[$j]]->isChecked())
					{
						$setId = $sourceTableIds[$j];
						$query = 'insert into ' . $field->setTable . '(' . $field->setTableMainKey . ', ' . $field->setTableSourceKey . ') values (' . $this->getId() . ', ' . $setId . ')';
						
						$result = $application->db->doInsert($query);
						
						if (PEAR::isError($result))
						{
							$window->setErrorMessage($recordId->getMessage());
							return false;
						}

					}
				}
			}
		}
		else
		{
			$window->setErrorMessage($result->getMessage());
			return false;
		}
		
		return true;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: delete()
	//-------------------------------------------------------------------

	/** 
	  * This function deletes data from the database for this record, including set tables
	  *
	  * @access public
	  * @see CC_Field::addToDatabase()
	  * @see CC_Field::setAddToDatabase()
	  * @see getDatabaseUpdateableFieldNames()
	  */

	function delete()
	{
		// CC_Database fields should be excluded from the update
		global $application;
		
		$deleteQuery = 'delete from ' . $this->table;		
		$deleteQuery .= ' where ' . $this->idColumnName . ' =\'' . $this->id . '\'';		
		
		$result = $application->db->doDelete($deleteQuery);
		
		if (!PEAR::isError($result))
		{
			$keys = $this->getDatabaseUpdateableFieldNames();		
			$size = sizeof($keys);
			
			$oneToManyFields = array();
	
			for ($i = 0; $i < $size; $i++)
			{
				$field = $this->fields[$keys[$i]];
				
				// one to many field
				if (get_class($field) == 'cc_foreign_key_multiple_field')
				{
					$oneToManyFields[] = $field;
				}
	
				unset($field);
			}
			
			unset($size);

			for ($i = 0; $i < sizeof($oneToManyFields); $i++)
			{
				$field = $oneToManyFields[$i];

				// delete existing set entries				
				$query = 'delete from ' . $field->setTable . ' where ' . $field->setTableMainKey . ' = ' . $this->getId();
				$result = $application->db->doDelete($query);				
			}
		}
		else
		{
			$window->setErrorMessage($result->getMessage());
			return false;
		}
		
		return true;
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
		error_log('CC_Record->buildInsertQuery() is deprecated. Use CC_Record->insert() instead.');
		
		// CC_Database fields should be excluded from the update
		$application = &$_SESSION['application'];
		
		if ($application->db->isPostgres())
		{
			$insertQuery = 'insert into ' . $this->table . ' (' . $this->idColumnName . ', ' . (isset($this->fields['DATE_ADDED']) ? '' : 'DATE_ADDED, ');
		}
		else // if ($application->db->isMysql())
		{
			$insertQuery = 'insert into ' . $this->table . ' (' . (isset($this->fields['DATE_ADDED']) ? '' : 'DATE_ADDED, ');
		}

		$keys = $this->getDatabaseUpdateableFieldNames();
		
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
		
		switch ($application->db->_databaseType)
		{
			case DB_POSTGRES:
				$tableIdSequence = strtolower($this->table . '_id_seq');
				$insertQuery .= $fieldList . ') values (nextval(\'' . $tableIdSequence . '\'), ' . (isset($this->fields['DATE_ADDED']) ? '' : 'now(), ') . $valueList . ')';
			break;
			
			case DB_MYSQL:
			default:
				$insertQuery .= $fieldList . ') values (' . (isset($this->fields['DATE_ADDED']) ? '' : 'now(), ') . $valueList . ')';
			break;
		}

		return $insertQuery;
	}
	

	//-------------------------------------------------------------------
	// METHOD: setId
	//-------------------------------------------------------------------
	
	/** 
	 * This method sets the record's id.
	 *
	 * @access private
	 * @param int $id The record's id.
	 * @see getId()
	 */

	function setId($id)
	{
		$this->id = $id;
	}


	//-------------------------------------------------------------------
	// METHOD: getId
	//-------------------------------------------------------------------
	
	/** 
	 * This method gets the record's id.
	 *
	 * @access public
	 * @return int $id The record's id.
	 * @see setId()
	 */

	function getId()
	{
		return $this->id;
	}


	//-------------------------------------------------------------------
	// METHOD: register
	//-------------------------------------------------------------------

	/**
	 * This is a callback method that gets called by the window when the
	 * component is registered. It's up to the component to dece which
	 * registerXXX() method it should call on the window. Should your
	 * custom component consist of multiple components, you may need to
	 * make multiple calls.
	 *
	 * @access public
	 */

	function register(&$window)
	{
		$keys = array_keys($this->fields);
		
		$size = sizeof($keys);
		
		for ($i = 0; $i < $size; $i++)
		{
			$this->fields[$keys[$i]]->setRecord($this);
			$this->fields[$keys[$i]]->register($window);
		}
		
		unset($keys, $size);

		$window->registerRecord($this);
	}
}

?>