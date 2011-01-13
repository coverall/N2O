<?php
// $Id: CC_Record.php,v 1.132 2005/02/28 19:19:59 mike Exp $
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
		
		if ($id != -1)
		{
			// get the record from the database
			$selectQuery = 'select ';
			
			$size = sizeof($fieldNameArray);
			
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
			
			unset($size);
			
			$selectQuery .= ' from ' . $this->table . ' where ' . $this->idColumnName . '=\'' . $id . '\'';
			
			//echo $selectQuery . "<p>";
			
			$results = $application->db->doSelect($selectQuery);
			
			if (PEAR::isError($results))
			{
				$this->initializeFields($fieldNameArray);
				trigger_error('Query failed: ' . $results->getMessage() . '. The query was: ' . $selectQuery, E_USER_WARNING);
			}
			else
			{
				if ($row = cc_fetch_assoc($results))
				{
					$size = sizeof($fieldNameArray);
					
					for ($i = 0; $i < $size; $i++)
					{
						$fieldName = $fieldNameArray[$i];
						$fieldData = $row[$fieldName];
						
						$fieldObject = &$this->createFieldObject($fieldName, $fieldData);
						$fieldObject->setRecord($this);
						
						// make sure field keys are all upper case in this array
						$this->fields[strtoupper($fieldName)] = &$fieldObject;
	
						unset($fieldName);
						unset($fieldData);
						unset($fieldObject);
					}
					
					unset($size);
				}
				else
				{
					trigger_error('The record with id ' . $this->id . ' doesn\'t exist. The query was: ' . $selectQuery, E_USER_WARNING);
					$this->initializeFields($fieldNameArray);
				}
			}
			
			unset($selectQuery, $results);
		}
		else
		{
			$this->initializeFields($fieldNameArray);
		}
		
		unset($fieldNameArray);
   	}	
	
	
	//-------------------------------------------------------------------
	// METHOD: initializeFields
	//-------------------------------------------------------------------

	/**
	 * This method initializes the fields array.
	 *
	 * @access private
	 */

	function initializeFields($fieldNameArray)
	{
		// no record exists, so create blank fields.
		$size = sizeof($fieldNameArray);
		
		for ($i = 0; $i < $size; $i++)
		{
			$fieldName = $fieldNameArray[$i];
			
			$fieldObject = &$this->createFieldObject($fieldName, '');
			
			// make sure field keys are all upper case in this array
			$this->fields[strtoupper($fieldName)] = &$fieldObject;
			
			unset($fieldObject);
		}
		
		unset($size);
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
		
		if (array_key_exists($fieldName, $this->fields))
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
		
		$keys = array_keys($this->fields);
		
		$size = sizeof($keys);
		
		for ($i = 0; $i < $size; $i++)
		{
			$this->fields[$keys[$i]]->setReadOnly(!$editable);
		}
		
		unset($keys, $size);
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
		
		$keys = array_keys($this->fields);
		
		$size = sizeof($keys);
		
		for ($i = 0; $i < $size; $i++)
		{
			$this->fields[$keys[$i]]->setDisabled($disabled);
		}
		
		unset($keys, $size);
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
		$keys = array_keys($this->fields);
		
		$size = sizeof($keys);
		
		for ($i = 0; $i < $size; $i++)
		{
			$this->fields[$keys[$i]]->setRequired($required);
		}
		
		unset($keys, $size);
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
		$keys = array_keys($this->fields);
		
		$size = sizeof($keys);
		
		for ($i = 0; $i < $size; $i++)
		{
			$this->fields[$keys[$i]]->setShowAsterisk($show);
		}
		
		unset($keys, $size);
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
			$field = &$this->fields[$keys[$i]];
			
			if ($field->hasError())
			{
				$errors = true;
			}
			
			unset($field);
		}
		
		unset($size);
		
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
				$insertQuery .= $fieldList . ') values (nextval(\'' . $tableIdSequence . '\'), now(), ' . $valueList . ')';
			break;
			
			case DB_MYSQL:
			default:
				$insertQuery .= $fieldList . ') values (now(), ' . $valueList . ')';
			break;
		}

		return $insertQuery;
	}

	
	//-------------------------------------------------------------------
	// METHOD: createFieldObject()
	//-------------------------------------------------------------------

	/** 
	  * This method creates a field object based on the field's name and associated data, for an existing record, or default/blank data for a new record. This method is called in the CC_Record constructor when building the record's member fields.
	  *
	  * @access private
	  * @param string $fieldName The name of the field to create.
	  * @param string $fieldData The data to fill the field with upon creation.
	  * @return CC_Field A reference to the created field object.
	  */

	function &createFieldObject($fieldName, $fieldData)
	{
		global $application;
		$application->fieldManager = &$application->fieldManager;
		
		$ccFieldData = $application->fieldManager->getFieldData($fieldName);
		$fieldType   = $ccFieldData[0];
		$displayName = $ccFieldData[1];
			
		$required = (isset($ccFieldData[2]->required) ? $ccFieldData[2]->required : false);

		switch (strtolower($fieldType))
		{
			case 'cc_text_field':
			case 'cc_number_field':
			case 'cc_dollar_field':
			case 'cc_integernumber_field':
			case 'cc_floatnumber_field':
			case 'cc_phone_field':
			{
				if (isset($ccFieldData[2]->size))
				{
					$size = $ccFieldData[2]->size;
				}
				else
				{
					$size = 32;				
				}
				
				if (isset($ccFieldData[2]->maxlength))
				{
					$maxlength = $ccFieldData[2]->maxlength;
				}
				else
				{
					$maxlength = 128;
				}
				
				
				if (isset($ccFieldData[2]->value))
				{
					$fieldData = $ccFieldData[2]->value;
				}
				
				$fieldObject = &new $fieldType($fieldName, $displayName, $required, $fieldData, $size, $maxlength);
				
				unset($size);
				unset($maxlength);
				unset($value);
				
				break;
			}
			case 'cc_checkbox_field':
			case 'cc_dependant_checkbox_field':
			{
				if (strlen($fieldData))
				{
					if ($fieldData == 1 || (string)$fieldData == 't')
					{
						$checked = true;
					}
					else
					{
						$checked = false;
					}
				}
				else
				{
					if (isset($ccFieldData[2]->checked))
					{
						$checked = ($ccFieldData[2]->checked == 1 ? true : false);
					}
					else
					{
						$checked = false;
					}
				}
				
				$fieldObject = &new $fieldType($fieldName, $displayName, $required, $checked);
				
				if (isset($ccFieldData[2]->optionalValue))
				{
					$fieldObject->setOptionalValue($ccFieldData[2]->optionalValue);
				}
				
				unset($checked);
	
				break;
			}
			case 'cc_textarea_field':
			{
				if (isset($ccFieldData[2]->x) && isset($ccFieldData[2]->y))
				{
					$fieldObject = &new $fieldType($fieldName, $displayName, $required, $fieldData, $ccFieldData[2]->x, $ccFieldData[2]->y);
				}
				else
				{
					$fieldObject = &new $fieldType($fieldName, $displayName, $required, $fieldData, 50, 5);
				}
				
				if (isset($ccFieldData[2]->autolink) && $ccFieldData[2]->autolink)
				{
					$fieldObject->setAutolink(true);
				}
				
				break;
			}
			case 'cc_credit_card_field':
			{
				$fieldObject = &new $fieldType($fieldName, $displayName, $required, strSlide13($fieldData));
				
				break;
			}
			case 'cc_country_field':
			case 'cc_iso_country_field':
			case 'cc_postalcode_field':
			case 'cc_zipcode_field':
			case 'cc_postalzipcode_field':
			case 'cc_encoded_field':
			case 'cc_domain_field':
			{
				$fieldObject = &new $fieldType($fieldName, $displayName, $required, $fieldData);

				break;
			}
			case 'cc_state_field':
			{
				$fieldObject = &new CC_ProvinceState_Field($fieldName, $displayName, $required, $fieldData);
				$fieldObject->setIncludeStates(true);
				$fieldObject->setIncludeProvinces(false);
				
				switch ($application->getLanguage())
				{
					case 'French':
					{
						$fieldObject->setUnselectedValue('- S&eacute;l&eacute;ctionnez un &Eacute;tat -');
					}
					break;
					
					default:
					{
						$fieldObject->setUnselectedValue('- Select State -');
					}
				}
				break;
			}
			case 'cc_province_field':
			{
				$fieldObject = &new CC_ProvinceState_Field($fieldName, $displayName, $required, $fieldData);
				$fieldObject->setIncludeStates(false);
				$fieldObject->setIncludeProvinces(true);
				break;
			}
			case 'cc_stateprovince_field':
			{
				$fieldObject = &new CC_ProvinceState_Field($fieldName, $displayName, $required, $fieldData);
				$fieldObject->setIncludeStates(true, true);
				$fieldObject->setIncludeProvinces(true);
				break;
			}
			case 'cc_provincestate_field':
			{
				$fieldObject = &new $fieldType($fieldName, $displayName, $required, $fieldData);
				$showFirst = '';
				$NAFirst = '';
				
				if (isset($ccFieldData[2]->showFirst))
				{
					$showFirst = $ccFieldData[2]->showFirst;
				}
				
				if (isset($ccFieldData[2]->NAFirst))
				{
					$NAFirst = $ccFieldData[2]->NAFirst;
				}
				
				if (isset($ccFieldData[2]->abbreviated))
				{
					$fieldObject->setAbbreviated($ccFieldData[2]->abbreviated);
				}
				
				if (isset($ccFieldData[2]->includeStates))
				{
					$fieldObject->setIncludeStates($ccFieldData[2]->includeStates, ($showFirst == 'States'));
				}
				
				if (isset($ccFieldData[2]->includeProvinces))
				{
					$fieldObject->setIncludeProvinces($ccFieldData[2]->includeProvinces, ($showFirst == 'Provinces'));
				}
				
				if (isset($ccFieldData[2]->includeNA))
				{
					$fieldObject->setIncludeNA($ccFieldData[2]->includeNA, $NAFirst);
				}
				
				break;
			}
			case 'cc_email_field':
			{
				$fieldObject = &new $fieldType($fieldName, $displayName, $required, $fieldData, (isset($ccFieldData[2]->size) ? $ccFieldData[2]->size : 32));

				if (isset($ccFieldData[2]->linkable))
				{
					$fieldObject->setLinkable(true);
				}
				
				break;
			}
			case 'cc_percentage_field':
			{
				$fieldObject = &new $fieldType($fieldName, $displayName, $fieldData);

				break;			
			}
			case 'cc_date_field':
			{
				$parsedDate = getDate(convertMysqlDateToTimestamp($fieldData));
				
				$month = $parsedDate['mon'];
				$day   = $parsedDate['mday'];
				$year  = $parsedDate['year'];
	
				if (isset($ccFieldData[2]->startYear))
				{
					$startYear = $ccFieldData[2]->startYear;
				}
				else
				{
					$startYear = $year - 2;
				}
	
				if (isset($ccFieldData[2]->endYear))
				{
					$endYear = $ccFieldData[2]->endYear;
				}
				else
				{
					$endYear = $year + 10;
				}
				
				$fieldObject = &new $fieldType($fieldName, $displayName, $required, $month, $day, $year, $startYear, $endYear);

				if (isset($ccFieldData[2]->allowBlank))
				{
					$fieldObject->setAllowBlankValue($ccFieldData[2]->allowBlank);
				}
	
				unset($parsedDate);
				unset($month);
				unset($day);
				unset($year);
				unset($startYear);
				unset($endYear);

				break;
			}
			case 'cc_expiry_date_field':
			{
				if ((strcmp($fieldData, '0000-00-00') == 0) || (strlen($fieldData) == 0))
				{
					$parsedDate = getDate(strtotime('today +1 month'));
				}
				else
				{
					$parsedDate = getDate(convertMysqlDateToTimestamp($fieldData));
				}
	
				$month = $parsedDate['mon'];
				$year  = $parsedDate['year'];
	
				$fieldObject = &new $fieldType($fieldName, $displayName, $required, $month, 1, $year);

				break;
			}
			case 'cc_datetime_field':
			{
				$parsedDate = getDate(convertMysqlDateTimeToTimestamp(substr($fieldData, 0, 19)));
				
				$month = $parsedDate['mon'];
				$day   = $parsedDate['mday'];
				$year  = $parsedDate['year'];
				
				$hour  = $parsedDate['hours'];
				$minute  = $parsedDate['minutes'];
				
				$parameter1 = $ccFieldData[2]->startYear;	// (int) start year
				$parameter2 = $ccFieldData[2]->endYear;	// (int) end year
				
				if (strlen($parameter1) > 0) 
				{
					$startDate = $parameter1;
				}
				else
				{
					$startDate = $year - 2;
				}
				
				if (strlen($parameter2) > 0)
				{
					$endDate = $parameter2;
				}
				else
				{
					$endDate = $year + 10;
				}
				
				$fieldObject = &new $fieldType($fieldName, $displayName, $required, $month, $day, $year, $hour, $minute, $startDate, $endDate, 1);
				
				if (isset($ccFieldData[2]->allowBlank))
				{
					$fieldObject->setAllowBlankValue($ccFieldData[2]->allowBlank);
				}

				unset($parameter1);
				unset($parameter2);
				unset($startDate);
				unset($endDate);
				unset($parsedDate);
				unset($month);
				unset($day);
				unset($year);
				unset($hour);
				unset($minute);

				break;
			}
			case 'cc_time_field':
			{
				$parsedTime = explode(':', $fieldData);
				
				$hour    = $parsedTime[0];
				$minute  = $parsedTime[1];
				$second  = $parsedTime[2];
				
				$fieldObject = &new $fieldType($fieldName, $displayName, $required, $hour, $minute, $second);
				
				unset($hour);
				unset($minute);
				unset($second);

				break;
			}
			case 'cc_date_added_field':
			{
				$parsedDate = getDate(convertMysqlDateTimeToTimestamp($fieldData));
				
				$month = $parsedDate['mon'];
				$day   = $parsedDate['mday'];
				$year  = $parsedDate['year'];
				
				$hour  = $parsedDate['hours'];
				$minute  = $parsedDate['minutes'];
				
				$fieldObject = &new $fieldType($fieldName, $displayName, $month, $day, $year, $hour, $minute);
	
				unset($parsedDate);
				unset($month);
				unset($day);
				unset($year);
				unset($hour);
				unset($minute);

				break;
			}
			case 'cc_timestamp_field':
			{
				$parsedDate = getDate(convertMysqlTimestampToPHPTimestamp($fieldData));
				
				$month = $parsedDate['mon'];
				$day   = $parsedDate['mday'];
				$year  = $parsedDate['year'];
				
				$hour  = $parsedDate['hours'];
				$minute  = $parsedDate['minutes'];
				
				$fieldObject = &new $fieldType($fieldName, $displayName, $required, $month, $day, $year, $hour, $minute, $year - 2, $year + 10);
	
				unset($parsedDate);
				unset($month);
				unset($day);
				unset($year);
				unset($hour);
				unset($minute);

				break;
			}
			case 'cc_hidden_field':
			case 'cc_hidden_number_field':
			{
				$fieldObject = &new $fieldType($fieldName, $fieldData);

				break;
			}
			case 'cc_radiobutton_field':
			{
				// get the delimiter...
				if (isset($ccFieldData[2]->delimiter))
				{
					$delimiter = $ccFieldData[2]->delimiter;
				}
				else
				{
					$delimiter = ',';
				}

				// (string) delimited list of options
				if (isset($ccFieldData[2]->options) > 0)
				{
					$options = explode($delimiter, $ccFieldData[2]->options);
				}	
				else
				{
					$options = array();
				}

				// the default value
				if (!strlen($fieldData) && isset($ccFieldData[2]->value))
				{
					$fieldData = $ccFieldData[2]->value;
				}

				// the index of the default value.
				if (isset($ccFieldData[2]->index))
				{
					$index = $ccFieldData[2]->index;
				}
								
				$fieldObject = &new $fieldType($fieldName, $displayName, $required, $fieldData, $options);
				
				if (!strlen($fieldData) && isset($index) && cc_is_int($index))
				{
					$fieldObject->setSelectedAtIndex($index);
				}
				
				unset($fieldData, $index);

				break;
			}
			case 'cc_selectlist_field':
			case 'cc_autosubmit_select_field':
			{
				if (isset($ccFieldData[2]->unselectedValue))
				{
					$unselectedValue = $ccFieldData[2]->unselectedValue;	// the default unselectedValue
				}
				else
				{
					$unselectedValue = '- Select -';
				}
				
				if (isset($ccFieldData[2]->options))
				{
					if (strpos($ccFieldData[2]->options, '='))
					{
						$preoptions = explode(',', $ccFieldData[2]->options);
						$size = sizeof($preoptions);
						$options = array();
						
						for ($i = 0; $i < $size; $i++)
						{
							$suboptions = explode('=', $preoptions[$i]);
							$options[] = array($suboptions[0], $suboptions[1]);
							unset($suboptions);
						}
						unset($preoptions, $size);
					}
					else
					{
						$options = explode(',', $ccFieldData[2]->options);
					}
				}	
				else
				{
					$options = array();
				}

				if (isset($ccFieldData[2]->value))
				{
					$fieldData = $ccFieldData[2]->value;
				}
				
				$fieldObject = &new $fieldType($fieldName, $displayName, $required, $fieldData, $unselectedValue, $options);
				
				unset($options);

				break;
			}
			case 'cc_multiple_selectlist_field':
			{
				if (isset($ccFieldData[2]->unselectedValue))
				{
					$unselectedValue = $ccFieldData[2]->unselectedValue;	// the default unselectedValue
				}
				else
				{
					$unselectedValue = '- Select -';
				}
				
				if (isset($ccFieldData[2]->options))
				{
					$options = explode(',', $ccFieldData[2]->options);
				}	
				else
				{
					$options = array();
				}

				if (isset($ccFieldData[2]->value))
				{
					$fieldData = explode(',', $ccFieldData[2]->value);
				}
				
				
				$fieldObject = &new $fieldType($fieldName, $displayName, $required, $fieldData, $unselectedValue, $options);
				
				unset($options);

				break;
			}
			case 'cc_foreign_key_field':
			case 'cc_fk_field':
			{
				// args:
				//
				// showAdd (boolean)
				// addHandler (string)
				// orderBy (string)
				// whereClause (string)
				
				if (isset($ccFieldData[2]->showAdd) && ($ccFieldData[2]->showAdd == 'false' || $ccFieldData[2]->showAdd == 0))
				{
					$showButton = false;
				}
				else
				{
					$showButton = true;
				}
				
				if (isset($ccFieldData[2]->addHandler) && class_exists($ccFieldData[2]->addHandler))
				{
					$fieldObject = &$application->relationshipManager->getField($fieldName, $fieldData, $displayName, $showButton, (isset($ccFieldData[2]->orderBy) ? $ccFieldData[2]->orderBy : ''), $ccFieldData[2]->addHandler, (isset($ccFieldData[2]->whereClause) ? $ccFieldData[2]->whereClause : ''));
				}
				else
				{
					$fieldObject = &$application->relationshipManager->getField($fieldName, $fieldData, $displayName, $showButton, (isset($ccFieldData[2]->orderBy) ? $ccFieldData[2]->orderBy : ''), 'CC_Manage_FK_Table_Handler', (isset($ccFieldData[2]->whereClause) ? $ccFieldData[2]->whereClause : ''));
				}
				
				break;
			}
			case 'cc_onetomany_field':
			{
				if (isset($ccFieldData[2]->handler))
				{
					$handlerClass = $ccFieldData[2]->handler;
				}
				else
				{
					$handlerClass = 'CC_Manage_FK_Table_Handler';
				}
	
				if (isset($ccFieldData[2]->class))
				{
					$fieldClass = $ccFieldData[2]->class;
				}
				else
				{
					$fieldClass = 'CC_OneToMany_Field';
				}
	
				$fieldObject = &$application->relationshipManager->getOneToManyField($fieldName, $fieldData, $displayName, $required, $handlerClass, $fieldClass);
				
				unset($handlerClass, $fieldClass);

				break;
			}
			case 'cc_password_field':
			{
				if (isset($ccFieldData[2]->size))
				{
					$sizeOfField = $ccFieldData[2]->size;	// (int) size
				}
				else
				{
					$sizeOfField = 16;
				}
				
				if (isset($ccFieldData[2]->showPassword))
				{
					$showPassword = $ccFieldData[2]->showPassword;
				}
				else
				{
					$showPassword = false;
				}
				
				$fieldObject = &new $fieldType($fieldName, $displayName, $required, $fieldData, $sizeOfField, 32, $showPassword);
				
				unset($sizeOfField, $showPassword);

				break;
			}
			case 'cc_file_upload_field':
			{	
				$fieldObject = &new CC_Multiple_File_Upload_Field($fieldName, $displayName, $required, APPLICATION_PATH . 'uploads/', $fieldData, false, 'CC_File_Upload_Field', false, 1000000000);

				break;
			}
			case 'cc_image_upload_field':
			{	
				$fieldObject = &new CC_Multiple_File_Upload_Field($fieldName, $displayName, $required, APPLICATION_PATH . 'uploads/', $fieldData, false, 'CC_Image_Upload_Field', false, 1000000000);

				break;
			}
			case 'cc_multiple_file_upload_field':
			{	
				// Minimum Number of Fields
				if (!isset($ccFieldData[2]->minFields))
				{
					$minFields = 1;
				}
				else
				{
					$minFields = $ccFieldData[2]->minFields;
				}

				// File Upload Field class
				if (!isset($ccFieldData[2]->fieldClass))
				{
					$fieldClass = 'CC_File_Upload_Field';
				}
				else
				{
					$fieldClass = $ccFieldData[2]->fieldClass;
				}

				// Allow 'add file' button
				if (isset($ccFieldData[2]->showAdd))
				{
					if ($ccFieldData[2]->showAdd == 'true' || $ccFieldData[2]->showAdd == 1)
					{
						$showAdd = true;
					}
					else
					{
						$showAdd = false;
					}
				}
				else
				{
					$showAdd = false;
				}

				// Path to uploads
				if (isset($ccFieldData[2]->uploadPath))
				{
					$uploadPath = $ccFieldData[2]->uploadPath;
				}
				else
				{
					$uploadPath = APPLICATION_PATH . 'uploads/';
				}

				
				$fieldObject = &new $fieldType($fieldName, $displayName, $required, $uploadPath, $fieldData, $minFields, $fieldClass, $showAdd, 1000000000);
	
				unset($minFields);
				unset($fieldClass);
				unset($showAdd);

				break;
			}
			default:
			{
				$fieldObject = &$this->createOtherFieldObject($fieldName, $fieldType, $fieldData, $displayName, $ccFieldData);
				
				if ($fieldObject == false)
				{
					$fieldObject = &new CC_Text_Field($fieldName, $displayName, $required, $fieldData, 32, 128);
				}

				break;
			}
		}
		
		if (!$fieldObject->isReadOnly())
		{
			$fieldObject->setReadOnly(!$this->editable);
		}
		
		// set the summary label, if it exists
		if (isset($ccFieldData[2]->summaryLabel))
		{
			$fieldObject->setSummaryLabel($ccFieldData[2]->summaryLabel);
		}
		
		return $fieldObject;
	}


	//-------------------------------------------------------------------
	// METHOD: createOtherFieldObject()
	//-------------------------------------------------------------------


	/** If you want to use custom (extended) fields created by a CC_Record object, you will need to also extend CC_Record, and override this method. This method will be where you define how these other fields are created, and will ultimately return the new field.
	  *
	  * @access public
	  * @param string $fieldName The name of the field to create.
	  * @param string $fieldType The type of N2O field to create.
	  * @param string $fieldData The data to fill the field with upon creation.
	  * @param string $displayName The field's text label.
	  * @param string $ccFieldData Other field parameters from the ARGS column in CC_FIELDS.
	  * @return CC_Field The field object (in subclasses that override this method)
	  */

	function &createOtherFieldObject($fieldName, $fieldType, $fieldData, $displayName, $ccFieldData)
	{
		return false;
	}



	//-------------------------------------------------------------------
	// METHOD: saveAndDuplicateRecord()
	//-------------------------------------------------------------------
	
	/** This method saves the current record and then duplicates all the values into a new record. After running this, your record's id WILL change to the new id.
	  *
	  * @access public
	  * @return int The id of the newly created record.
	  *
	  */

	function saveAndDuplicateRecord()
	{
		$application = &$_SESSION['application'];
		
		$db = &$application->db;

		if (strcmp($this->id, '-1'))
		{
			$query = $this->buildInsertQuery();
			$db->doInsert($query);
		}
		else
		{
			$query = $this->buildUpdateQuery();
			$db->doUpdate($query);
		}
		
		$query = $this->buildInsertQuery();
		
		$newRecordId = $db->doInsert($query);
		
		$this->id = $newRecordId;
		
		return $this->id;
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
		}
		
		unset($keys, $size);

		$window->registerRecord($this);
	}
}

?>