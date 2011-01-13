<?php
// $Id: CC_Window.php,v 1.111 2004/12/15 19:34:48 jamie Exp $
//=======================================================================
// CLASS: CC_Window
//=======================================================================

/**
 * This class defines an application's window object.
 *
 * @package N2O
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

class CC_Window
{
	/**
     * The id (filename) of the window taken from the action.
     *
     * @var string $id
     * @access private
     * @see CC_Application::getAction()
     */

	var $id;
	

	/**
     * An array of CC_Button objects registered with the window.
     *
     * @var array $buttons
     * @access private
     * @see registerComponent()
     * @see getButton()
     * @see getButtonById()
     * @see buttonExists()
     */

	var $buttons = array();


	/**
     * An array of CC_Fields objects registered with the window.
     *
     * @var array $fields
     * @access private
     * @see registerComponent()
     * @see getField()
     */

	var $fields = array();


	/**
     * An array of CC_Record objects registered with the window.
     *
     * @var array $records
     * @access private
     * @see registerComponent()
     * @see getRecord()
     */

	var $records = array();


	/**
     * An array of CC_Summary objects registered with the window.
     *
     * @var array $summaries
     * @access private
     * @see registerComponent()
     * @see getSummary()
     */

	var $summaries = array();


	/**
     * An array of miscellaneous CC_Component objects registered with the window.
     *
     * @var array $components
     * @access private
     * @see registerComponent()
     * @see getComponent()
     */

	var $components = array();


	/**
     * Whether or not the window has invalid fields.
     *
     * @var bool $_error
     * @access private
     * @deprecated
     */

	var $_error = false;


	/**
     * The window's error message.
     *
     * @var string $_errorMessage
     * @access private
     * @see getErrorMessage()
     */

	var $_errorMessage;


	/**
     * The window's status message.
     *
     * @var string $_statusMessage
     * @access private
     * @see getStatusMessage()
     */

	var $_statusMessage;


	/**
     * Whether or not the window should display verbose 'field' errors.
     *
     * @var bool $_verboseErrors
     * @access private
     * @see setVerboseErrors()
     */

	var $_verboseErrors = false;	// give details about each field's error


	/**
	 * Whether or not a window's components are updateable (usually true unless a cancel event occurs).
	 * 
     * @var bool $updateable
     * @access private
     * @see setUpdateable()
     * @see isUpdateable()
     */

	var $updateable = true;


	/**
     * This is the default button which will be used if a user hits enter in a field.
     *
     * @var CC_Button $defaultButton
     * @access private
     * @see setDefaultButton()
     */

	var $defaultButton;
	

	/**
     * An array of objects stored for access in this window.
     *
     * @var array $objects
     * @access private
     * @see registerObject()
     * @see getObject()
     * @see objectExists()
     */

	var $objects = array();


	/**
     * An array of name-value pairs to keep data between subsequent window accesses.
     *
     * @var array $arguments
     * @access private
     * @see getArgument()
     * @see setArgument()
     */

	var $arguments = array();

	
	/**
     * The "tabindex" counter for components. This will allow us to control which button is submitted first when someone hits enter in a field. By default, buttons will start at 100, but making a button default by using CC_Window::setDefaultButton() will make it lower than the rest.
	 *
     * @var int $_tabIndex
     * @access private
     */

	var $_tabIndexCounter = 0;


	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Window
	//-------------------------------------------------------------------

	/**
     * The CC_Window constructor creates the window's id based on the application's current action. 
     *
	 * @access public
	 * @see CC_Application::getAction()
	 * @author  The Crew <N2O@coverallcrew.com>
	 * @copyright Copyright &copy; 2003, Coverall Crew 
	 * @todo Why do we unset the application object here?
	 */

	function CC_Window($id = null)
	{
		$application = &$_SESSION['application'];
	
		if ($id == null)
		{
			$this->id = $application->getAction();
		}
		else
		{
			$this->id = $id;
		}
		
		unset($application);
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: registerButton()
	//-------------------------------------------------------------------

	/**
     * This method registers a button with the window. Each button on a window must be registered for its handlers to be processsed by N2O. 
     *
	 * @access public
	 * @param CC_Button The button object to register.
	 */

	function registerButton(&$aButton)
	{
		//echo "Registering Button with id " . $aButton->id . "...<br>";
		$aButton->windowId = $this->id;
		$aButton->_tabIndex = 420 + ++$this->_tabIndexCounter;
				
		$this->buttons[] = &$aButton;
		
		return sizeof($this->buttons) - 1;
	}


	//-------------------------------------------------------------------
	// METHOD: updateFieldsFromPage
	//-------------------------------------------------------------------
	
	/**
     * This method is called by CC_Index when a button click triggers fields to be updated and/or validated. It updates and/or validates the fields based on the user's input in the window. updateFieldsFromDatabase updates fields from database values.
     *
	 * @access private
	 * @param bool $validateFields Whether or not fields should be validated as well as updated.
	 * @param array $fieldArray The fields to be updated. If empty, all the window's fields (all standalone fields as well as all records' fields) are updated from the page data entered.
	 * @see CC_Index
	 * @see updateFieldsFromDatabase()
	 */

	function updateFieldsFromPage($validateFields, $fieldArray = null)
	{
		global $application;
		
		if ($fieldArray == null)
		{
			$fieldArray = array();
		}
		
		$fieldArraySize = sizeof($fieldArray);
		
		$size = sizeof($this->fields);
		$keys = array_keys($this->fields);
		
		// search through all stray fields first
		for ($i = 0; $i < $size; $i++)
		{
			if (!$this->fields[$keys[$i]]->isReadOnly())
			{
				$this->updateFieldFromPage($this->fields[$keys[$i]], $validateFields && (($fieldArraySize == 0) || array_key_exists($this->fields[$keys[$i]]->getName(), $fieldArray)));
			}
		}
		
		unset($size, $keys);
			
		// search through all record fields
		$keys = array_keys($this->records);
		
		$size = sizeof($keys);
		
		for ($i = 0; $i < $size; $i++)
		{
			$fieldKeys = array_keys($this->records[$keys[$i]]->fields);

			$rSize = sizeof($fieldKeys);

			for ($j = 0; $j < $rSize; $j++)
			{
				if (!$this->records[$keys[$i]]->fields[$fieldKeys[$j]]->isReadOnly())
				{
					$this->updateFieldFromPage($this->records[$keys[$i]]->fields[$fieldKeys[$j]], $validateFields && (($fieldArraySize == 0) || array_key_exists($this->records[$keys[$i]]->fields[$fieldKeys[$j]]->getName(), $fieldArray)));
				}
			}
			
			unset($rSize);
			unset($fieldKeys);
		}

		unset($fieldArraySize, $size);
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: updateFieldsFromDatabase
	//-------------------------------------------------------------------
	
	/**
     * This method is called by CC_Index when a button click triggers fields to be updated and/or validated. It updates and/or validates the fields based on the field's value in the database. updateFieldsFromPage updates fields from page values.
     *
	 * @access private
	 * @param array $fieldArray The fields to be updated. If empty, all the window's fields (all standalone fields as well as all records' fields) are updated from the database.
	 * @see CC_Index
	 * @see updateFieldsFromPage()
	 */

	function updateFieldsFromDatabase($fieldArray = null)
	{
		if ($fieldArray == null)
		{
			$fieldArray = array();
		}
		
		$keys = array_keys($this->records);
		
		$size = sizeof($keys);
		
		for ($i = 0; $i < $size; $i++)
		{
			$record = &$this->records[$keys[$i]];
			
			$fieldKeys = array_keys($record->fields);

			$rSize = sizeof($fieldKeys);

			for ($j = 0; $j < $rSize; $j++)
			{
				$field = &$record->fields[$fieldKeys[$j]];
				
				if ($field != NULL)
				{
					if (((sizeof($fieldArray) == 0) || array_key_exists($field->name, $fieldArray)) && $field->getUpdateFromDatabase() === true)
					{
						$this->updateFieldFromDatabase($field, $record);
					}
				}
				
				unset($field);
			}
			
			unset($rSize);
			unset($fieldKeys);
			unset($record);
		}
		
		unset($size);
	}

	
	//-------------------------------------------------------------------
	// METHOD: updateFieldFromDatabase
	//-------------------------------------------------------------------
	
	/**
     * This method is called by updateFieldsFromDatabase() to update an individual field from the database.
     *
	 * @access private
	 * @param CC_Field $field The field to update.
	 * @param CC_Record $record The record to update.
	 * @see updateFieldsFromDatabase()
	 */

	function updateFieldFromDatabase(&$field, &$record)
	{
		$application = &$_SESSION['application'];

		//echo "$field->name : updateFieldFromDatabase!!<BR>";

		$selectQuery = 'select ' . $field->name . ' from ' . $record->table . ' where ID = \'' . $record->id . '\'';

		$results = $application->db->doSelect($selectQuery);

		if ($row = cc_fetch_array($results))
		{
			$fieldName = $field->name;
			$fieldData = $row[$fieldName];
		}

		//$record->fields[$fieldName] = $record->createFieldObject($fieldName, $fieldData);
		$field->setValue($fieldData);
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: updateFieldFromPage
	//-------------------------------------------------------------------
	
	/**
	 * This method is called by updateFieldsFromPage() to update an individual field from the page.
	 *
	 * @access private
	 * @param CC_Field $field The field to update.
	 * @param bool $validateFields Whether or not fields should be validated as well as updated.
	 * @see updateFieldsFromDatabase()
	 */

	function updateFieldFromPage(&$field, $validateFields)
	{
		$application = &$_SESSION['application'];
		
		//$fieldType = $application->fieldManager->getFieldType($field->name);
		
		$fieldType = get_class($field);
		
		$key = $field->getRecordKey() . $field->getName();
		
		//trigger_error("$key : $fieldType", E_USER_WARNING);
		
		switch (strtolower($fieldType))
		{
			case 'cc_date_field':
			{
				if (array_key_exists($key . '_year', $_REQUEST))
				{
					$field->setYearValue($_REQUEST[$key . '_year']);
					$field->setMonthValue($_REQUEST[$key . '_month']);
					$field->setDateValue($_REQUEST[$key . '_date']);
				}
				
				break;
			}

			case 'cc_expiry_date_field':
			{
				if (array_key_exists($key . '_year', $_REQUEST))
				{
					$field->setYearValue($_REQUEST[$key . '_year']);
					$field->setMonthValue($_REQUEST[$key . '_month']);
				}
				
				break;
			}
			
			case 'cc_datetime_field':
			{
				if (array_key_exists($key . '_year', $_REQUEST))
				{
					$field->setYearValue($_REQUEST[$key . '_year']);
					$field->setMonthValue($_REQUEST[$key . '_month']);
					$field->setDateValue($_REQUEST[$key . '_date']);
					$field->setHourValue($_REQUEST[$key . '_hour']);
					$field->setMinuteValue($_REQUEST[$key . '_minute']);
				}
				
				break;
			}
			
			case 'cc_timestamp_field':
			{
				if (array_key_exists($key . '_year', $_REQUEST))
				{
					$field->setYearValue($_REQUEST[$key . '_year']);
					$field->setMonthValue($_REQUEST[$key . '_month']);
					$field->setDateValue($_REQUEST[$key . '_date']);
					$field->setHourValue($_REQUEST[$key . '_hour']);
					$field->setMinuteValue($_REQUEST[$key . '_minute']);
				}
				
				break;
			}
			
			case 'cc_time_field':
			{
				if (array_key_exists($key . '_hour', $_REQUEST))
				{
					$field->setHourValue($_REQUEST[$key . '_hour']);
					$field->setMinuteValue($_REQUEST[$key . '_minute']);
					$field->setSecondValue($_REQUEST[$key . '_second']);
				}
				
				break;
			}
			case 'cc_checkbox_field':
			case 'cc_dependant_checkbox_field':
			{
				if (array_key_exists($key, $_REQUEST))
				{
					$field->setValue(1);
				}
				else
				{
					$field->setValue(0);
				}
				
				break;
			}
			
			case 'cc_onetomany_field':
			{
				break;
			}
			
			case 'cc_multiple_selectlist_field':
			{
				if (array_key_exists($key, $_REQUEST))
				{
					$ksize = sizeof($_POST[$field->getRequestArrayName()]);
					
					$selectionValues = array();
					
					for ($k = 0; $k < $ksize; $k++)
					{
						$selectionValues[] = stripslashes($_POST[$field->getRequestArrayName()][$k]);
					}
					
					$field->setValue($selectionValues);
				}
				
				break;
			}
			
			case 'cc_multiple_file_upload_field':
			{
				$field->resetInvalid();
	
				$ksize = sizeof(@$_FILES[$field->getRequestArrayName()]['tmp_name']);
	
				for ($k = 0; $k < $ksize; $k++)
				{
					if (file_exists($_FILES[$field->getRequestArrayName()]['tmp_name'][$k]))
					{
						$uploadField = &$field->uploadFieldArray[$k];
						
						$index = $k;
						
						// find the next empty upload field to fill
						while ($uploadField->getValue() != '')
						{
							if ($index < $ksize)
							{
								unset($uploadField);
								$uploadField = &$field->uploadFieldArray[$index++];
							}
							else
							{
								break;
							}
						}
						
						//update the field with the uploaded file info
						$uploadField->setFileInfo($k);

						if ($uploadField->validate())
						{
							$uploadField->moveTempFileToUploadsFolder();
							$field->clearAllErrors();
						}
						else
						{
							$field->setErrorMessage($uploadField->getErrorMessage());
						}
						
						unset($uploadField);
					}
				}
				
				unset($ksize);
				
				/*
				// handled by CC_Remove_File_Handler now
				$field->removeDeletedUploadFields();
				$field->setValue($field->getValue());
				*/
				
				break;
			}
			
			case 'cc_percentage_field':
			{
				$button = &$field->percentageButton;
			
				if (array_key_exists('_PP_' . $button->id . '_x', $_REQUEST))
				{
					$xValue = $_REQUEST['_PP_' . $button->id . '_x'];
					$yValue = $_REQUEST['_PP_' . $button->id . '_y'];
					$field->setValue($xValue);
				}
				
				unset($button);
				
				break;
			}
			
			case 'cc_credit_card_field':
			{
				if (array_key_exists($key, $_REQUEST))
				{
					$field->setValue(stripslashes($_REQUEST[$key]));
				}
				
				break;
			}
			
			// any other field type that doesn't require special handling
			default:
			{
				//echo "key = $key : fieldType = $fieldType : fieldValue = $_REQUEST[$key]<BR>";
				if (array_key_exists($key, $_REQUEST))
				{
					$field->setValue(stripslashes($_REQUEST[$key]));
				}
				
				break;
			}
		}
		
		//trigger_error("$key : $fieldType : " . $field->getValue(), E_USER_WARNING);
		$hasValue = $field->hasValue();
		
/*
		echo "required is $field->required<br>";
		echo "hasValue is $hasValue<br>";
		echo "validateFields is $validateFields<br>";
		echo "validateIfNotRequired is $field->validateIfNotRequired<br>";
		echo "validate is " . $field->validate() . "<br>";
*/		
		
		if ($field->required && !$hasValue && $validateFields)
		{
			//the field is required but the user didn't enter any data
			$field->setErrorMessage($field->label . ' is required.', CC_FIELD_ERROR_MISSING);
			$application->errorManager->addFieldError('000000', $field->getErrorMessage(CC_FIELD_ERROR_MISSING), $this->_verboseErrors);
		}
		else if (($field->required || ($field->validateIfNotRequired && $hasValue)) && $validateFields && !$field->validate())
		{
			//the field is invalid
			$field->setErrorMessage($field->label . ' is invalid.', CC_FIELD_ERROR_INVALID);
			$application->errorManager->addFieldError('000001', $field->getErrorMessage(CC_FIELD_ERROR_INVALID), $this->_verboseErrors);
		}
		else
		{
			$field->clearAllErrors();
		}
		
		unset($hasValue);
	}


	//-------------------------------------------------------------------
	// METHOD: registerComponent()
	//-------------------------------------------------------------------

	/**
     * This method registers a component with the window. This method should be called to register any type of component as the method will determine what type of component it is and call the appropriate method for the specific type.
     *
	 * @access public
	 * @param CC_Component $aComponent The object to register.
	 */

	function registerComponent(&$aComponent)
	{
		if ($aComponent == NULL)
		{
			trigger_error('CC_Window->registerComponent(): received an undefined component. (' . $this->id . ')');
		}
		else if (is_a($aComponent, 'CC_Component'))
		{
			$aComponent->register($this);
		}
		else
		{
			trigger_error('CC_Window->registerComponent(): did not expect the following type: ' . get_class($aComponent) . '. Make sure it extends CC_Component. (' . $this->id . ')');
		}
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: registerField()
	//-------------------------------------------------------------------

	/**
     * This method registers a field object with the window. Each field on a window must be registered for it to be properly updated and/or validated. This private method is called by the public registerComponent().
     *
	 * @access private
	 * @param CC_Field $aField The field object to register.
	 * @see registerComponent
	 */

	function registerField(&$aField)
	{
		$aField->_tabIndex = ++$this->_tabIndexCounter;
		$this->fields[$aField->getName()] = &$aField;
	}


	//-------------------------------------------------------------------
	// METHOD: registerRecord()
	//-------------------------------------------------------------------

	/**
     * This method registers a record object with the window. Each record on a window must be registered for its fields to be properly updated and/or validated. This private method is called by the public registerComponent().
     *
	 * @access private
	 * @param CC_Record $aRecord The record object to register.
	 * @see registerComponent
	 */

	function registerRecord(&$aRecord)
	{
		$keys = array_keys($aRecord->fields);
		
		$size = sizeof($keys);
		
		for ($i = 0; $i < $size; $i++)
		{
			$aRecord->fields[$keys[$i]]->_tabIndex = ++$this->_tabIndexCounter;
		}
		
		unset($keys, $size);

		$this->records[] = &$aRecord;
	}


	//-------------------------------------------------------------------
	// METHOD: registerSummary()
	//-------------------------------------------------------------------

	/**
     * This method registers a summary object with the window. Each summary on a window must be registered for its fields to be properly updated and/or validated. This private method is called by the public registerComponent().
     *
	 * @access private
	 * @param CC_Summary $summary The summary object to register.
	 * @see registerComponent
	 */

	function registerSummary(&$summary)
	{
		$this->summaries[$summary->getName()] = &$summary;
	}


	//-------------------------------------------------------------------
	// METHOD: registerOtherComponent()
	//-------------------------------------------------------------------

	/**
     * This method registers an object with the window that is not a field, record, summary or button. This private method is called by the public registerComponent().
     *
	 * @access private
	 * @param CC_Component $component The component object to register.
	 * @see registerComponent
	 */

	function registerCustomComponent(&$component)
	{
		$this->components[$component->getName()] = &$component;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: registerObject
	//-------------------------------------------------------------------

	/**
     * This method stores objects in the window for access.
     *
	 * @access public
	 * @param string $key The name by which to indentify the object for later access.
	 * @param mixed $aObject The object to register.
	 * @see getObject()
	 */

	function registerObject($key, &$aObject)
	{
		$this->objects[$key] = &$aObject;
	}
	

	//-------------------------------------------------------------------
	// METHOD: getComponent()
	//-------------------------------------------------------------------

	/**
     * This method retrieves a component from the window's components array. 
     *
	 * @access public
	 * @param string $name The name of the component to retrieve.
	 * @return mixed The CC_Component object of the given name.
	 * @see registerComponent()
	 */

	function &getComponent($name)
	{
		if (array_key_exists($name, $this->components))
		{
			$this->components[$name]->get($this);
			return $this->components[$name];
		}
		else
		{
			trigger_error("The CC_Component named '$name' was not found in the window $this->id. Perhaps you forgot to register it?");
		}
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getSummary()
	//-------------------------------------------------------------------

	/**
     * This method retrieves a CC_Summary object from the window's summaries array. 
     *
	 * @access public
	 * @param string $name The name of the CC_Summary to retrieve.
	 * @param bool $update Whether or not we should update the summary upon retrival.
	 * @return CC_Summary The CC_Summary object of the given name.
	 */

	function &getSummary($name, $update = false)
	{
		if (array_key_exists($name, $this->summaries))
		{
			if ($update)
			{
				$this->summaries[$name]->update();
			}
			
			return $this->summaries[$name];
		}
		else
		{
			trigger_error("The CC_Summary named '$name' was not found in the window $this->id. Perhaps you forgot to register it?");
		}
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getRecord()
	//-------------------------------------------------------------------

	/**
     * This method retrieves a CC_Record object from the window's records array. 
     *
	 * @access public
	 * @param string $key The key of the CC_Record to retrieve.
	 * @param bool $updateForeignKeys Whether or not we should update the record's foreign keys upon retrieval.
	 * @return CC_Record The CC_Record object with the given key.
	 */

	function &getRecord($key = NULL, $updateForeignKeys = false, $recordIndex = 0)
	{
		if ($key == NULL)
		{
			$record = &$this->records[$recordIndex];
			if ($updateForeignKeys)
			{
				$record->updateForeignKeys();
			}
			return $record;
		}
		else if (strlen($key) > 0)
		{
			trigger_error('getRecord(): Getting the record via a "key" is no longer supported. Use getRecordAtIndex() instead. (key: ' . $key . ')');
			
			$record = &$this->records[$recordIndex];
			if ($updateForeignKeys)
			{
				$record->updateForeignKeys();
			}

			return $record;
		}
		else
		{
			trigger_error("The CC_Record with the key '$key' was not found in the window $this->id. Perhaps you forgot to register it, or maybe you didn't unregister a window when you should have?");
		}
	}

	
	//-------------------------------------------------------------------
	// METHOD: getRecordAtIndex()
	//-------------------------------------------------------------------

	/**
     * This method retrieves a CC_Record object from the window's records array from a particular index. 
     *
	 * @access public
	 * @param int $recordIndex The index of the CC_Record to retrieve.
	 * @param bool $updateForeignKeys Whether or not we should update the record's foreign keys upon retrieval.
	 * @return CC_Record The CC_Record object with the given index.
	 */

	function &getRecordAtIndex($recordIndex = 0, $updateForeignKeys = false)
	{
		$size = sizeof($this->records);

		if ($size <= $recordIndex)
		{
			trigger_error('The CC_Record index is out of bounds. (index: ' . $recordIndex . ', # of records: ' . $size . ')');
		}
		else
		{
			return $this->getRecord(NULL, $updateForeignKeys, $recordIndex);
		}
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: isRecordRegisteredAtIndex()
	//-------------------------------------------------------------------

	/**
     * This method checks to see if a CC_Record object of a given index is registered with the window. 
     *
	 * @access public
	 * @param int $index The index of the CC_Record to check.
	 * @return bool Whether or not the CC_Record of given key is registered with the window.
	 */

	function isRecordRegisteredAtIndex($index)
	{
		return isset($this->records[$index]);
	}


	//-------------------------------------------------------------------
	// METHOD: isRecordRegistered()
	//-------------------------------------------------------------------

	/**
     * This method checks to see if a CC_Record object of a given key is registered with the window. 
     *
	 * @access public
	 * @param string $key The key of the CC_Record to check.
	 * @deprecated
	 * @return bool Whether or not the CC_Record of given key is registered with the window.
	 */

	function isRecordRegistered($key)
	{
		return array_key_exists($key, $this->records);
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: isComponentRegistered()
	//-------------------------------------------------------------------

	/**
     * This method checks to see if a CC_Component object of a given key is registered with the window. 
     *
	 * @access public
	 * @param string $key The key of the CC_Component to check.
	 * @deprecated
	 * @return bool Whether or not the CC_Component of given key is registered with the window.
	 */

	function isComponentRegistered($key)
	{
		return array_key_exists($key, $this->components);
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: isFieldRegistered()
	//-------------------------------------------------------------------

	/**
     * This method checks to see if a CC_Field object of a given name is registered with the window. 
     *
	 * @access public
	 * @param string $fieldName The name of the CC_Field to check.
	 * @return bool Whether or not the CC_Field of given name is registered with the window.
	 * @see registerComponent()
	 */

	function isFieldRegistered($fieldName)
	{
		return array_key_exists($fieldName, $this->fields);
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: isSummaryRegistered()
	//-------------------------------------------------------------------

	/**
     * This method checks to see if a CC_Summary object of a given name is registered with the window. 
     *
	 * @access public
	 * @param string $name The name of the CC_Summary to check.
	 * @return bool Whether or not the CC_Summary of given name is registered with the window.
	 * @see registerComponent()
	 */

	function isSummaryRegistered($name)
	{
		if (array_key_exists($name, $this->summaries))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: isUpdateable()
	//-------------------------------------------------------------------

	/**
     * This method checks to see if the window is updateable (ie. components thereon). Usually true unless we have are are processing a cancel button.
	 *
	 * @access public
	 * @return bool Whether or not the window is updateable.
	 * @see setUpdateable()
	 * @todo Who uses this?
	 */

	function isUpdateable()
	{
		return $this->updateable;
	}


	//-------------------------------------------------------------------
	// METHOD: setUpdateable()
	//-------------------------------------------------------------------

	/**
     * This method sets whether or not the window is updateable (ie. components thereon). Usually set to true unless we have are are processing a cancel button.
	 *
	 * @access public
	 * @param bool $updateable Whether or not the window is updateable.
	 * @see isUpdateable()
	 * @todo Who uses this?
	 */

	function setUpdateable($updateable)
	{
		$this->updateable = $updateable;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getButton()
	//-------------------------------------------------------------------

	/**
     * This method retrieves a CC_Button object from the window's buttons array. 
     *
	 * @access public
	 * @param string $aLabel The label of the CC_Button to retrieve.
	 * @return CC_Button The CC_Button object of the given label.
	 * @see registerComponent()
	 */

	function &getButton($aLabel)
	{
		$size = sizeof($this->buttons);
	
		for ($i = 0; $i < $size; $i++)
		{
			$button = &$this->buttons[$i];
			
			// (!) for some reason, ($button->label == $aLabel) was
			//     returning true for comparisons that weren't the same!!
			//     we should investigate why this is one day...

			if (strcmp($button->label, $aLabel) == 0)
			{
				unset($size);
				return $button;	
			}
			
			unset($button);
		}
		
		unset($size);
		
		//trigger_error("The Button with the following label was not found: " . $aLabel);
	}


	//-------------------------------------------------------------------
	// METHOD: getButtonAtIndex()
	//-------------------------------------------------------------------

	/**
     * This method retrieves a CC_Button object from the window's buttons array. 
     *
	 * @access public
	 * @param string $index The label of the CC_Button to retrieve.
	 * @return CC_Button The CC_Button object of the given label.
	 * @see registerComponent()
	 */

	function &getButtonAtIndex($index)
	{
		if (isset($this->buttons[$index]))
		{
			return $this->buttons[$index];
		}
		else
		{
			trigger_error('No button at index ' . $index . ' found.', E_USER_WARNING);
			return false;
		}
	}


	//-------------------------------------------------------------------
	// METHOD: buttonExists()
	//-------------------------------------------------------------------

	/**
     * This method checks to see if a CC_Button object of a given label is registered with the window. 
	 *
	 * @access public
	 * @param string $aLabel The name of the CC_Button to check.
	 * @return bool Whether or not the CC_Button of given label is registered with the window.
	 * @see registerComponent()
	 */

	function buttonExists($aLabel)
	{
		$size = sizeof($this->buttons);
	
		for ($i = 0; $i < $size; $i++)
		{
			if ($this->buttons[$i]->label == $aLabel)
			{
				unset($size);
				return true;	
			}
		}
		
		unset($size);
		
		return false;
	}


	//-------------------------------------------------------------------
	// METHOD: getButtonById()
	//-------------------------------------------------------------------

	/**
     * This method retrieves a CC_Button object from the window's buttons array. 
     *
	 * @access public
	 * @param string $aButtonId The id of the CC_Button to retrieve.
	 * @return CC_Button The CC_Button object of the given id.
	 * @see registerComponent()
	 */

	function &getButtonById($aButtonId)
	{
		$size = sizeof($this->buttons);
	
		for ($i = 0; $i < $size; $i++)
		{
			$button = &$this->buttons[$i];
			
			if ($button->id == $aButtonId)
			{
				unset($size);
				return $button;	
			}
			
			unset($button);
		}
		
		unset($size);
		
		trigger_error('[' . $this->id . '] The Button with the following id was not found: ' . $aButtonId);
		return false;
	}


	//-------------------------------------------------------------------
	// METHOD: getField()
	//-------------------------------------------------------------------

	/**
	 * This method retrieves a CC_Field object from the window's fields array. 
     *
	 * @access public
	 * @param string $fieldName The name of the CC_Field to retrieve.
	 * @return CC_Field The CC_Field object of the given name.
	 * @see registerComponent()
	 */

	function &getField($fieldName)
	{
		if (array_key_exists($fieldName, $this->fields))
		{
			$this->fields[$fieldName]->get($this);
			return $this->fields[$fieldName];
		}
		else
		{
			trigger_error("The CC_Field named '$fieldName' was not found in the window $this->id. Perhaps you forgot to register it?");
		}
	}
	

	//-------------------------------------------------------------------
	// METHOD: getObject()
	//-------------------------------------------------------------------

	/**
	 * This method retrieves an object from the window's objects array. 
     *
	 * @access public
	 * @param string $key The key of the object to retrieve.
	 * @return mixed The object of the given key.
	 * @see registerObject()
	 */

	function &getObject($key)
	{
		return $this->objects[$key];
	}
	

	//-------------------------------------------------------------------
	// METHOD: objectExists()
	//-------------------------------------------------------------------

	/**
     * This method checks to see if an object of a given key is registered with the window. 
	 *
	 * @access public
	 * @param string $key The key of the object to check.
	 * @return bool Whether or not the object of given key is registered with the window.
	 * @see registerObject()
	 * @deprecated
	 */

	function objectExists($key)
	{
		trigger_error('objectExists() is deprecated. Use hasObject() instead.', E_USER_WARNING);
		return hasObject($key);
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: hasObject()
	//-------------------------------------------------------------------

	/**
     * This method checks to see if an object of a given key is registered with the window. 
	 *
	 * @access public
	 * @param string $key The key of the object to check.
	 * @return bool Whether or not the object of given key is registered with the window.
	 * @see registerObject()
	 */

	function hasObject($key)
	{
		return array_key_exists($key, $this->objects);
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: hasSummary()
	//-------------------------------------------------------------------

	/**
     * This method checks to see if a CC_Summary of a given name is registered with the window. 
	 *
	 * @access public
	 * @param string $name The name of the CC_Summary to check.
	 * @return bool Whether or not the CC_Summary of given name is registered with the window.
	 * @see registerSummary()
	 */

	function hasSummary($name)
	{
		return array_key_exists($name, $this->summaries);
	}
	

	//-------------------------------------------------------------------
	// METHOD: _setError
	//-------------------------------------------------------------------

	/**
   	 * sets the _error boolean indicating that the window contains an error.
	 *
	 * @access public
	 * @param bool $error Whether or not the window has an error.
	 * @deprecated
	 */

	function _setError($error)
	{
		$this->_error = $error;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setVerboseErrors
	//-------------------------------------------------------------------
	
	/** 
	  * Indicates whether or not the 'field' errors should be displayed verbosely.
	  *
	  * @access public 
	  * @param bool $verboseErrors
	  */
	 
	function setVerboseErrors($verboseErrors)
	{
		$this->_verboseErrors = $verboseErrors;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: addError
	//-------------------------------------------------------------------
	
	/**
	 * Sets an error in the window.
	 *
	 * @access public
	 * @deprecated
	 * @todo Anyone using this method any more?
	 */
	
	function addError()
	{
		$this->_setError(true);
	}


	//-------------------------------------------------------------------
	// METHOD: clearError
	//-------------------------------------------------------------------
	
	/**
	 * @access public
	 * @deprecated See clearErrorMessage().
	 * Clears the error and the error message.
	 * @see clearErrorMessage()
	 */
	 
	function clearError()
	{	
		$this->clearErrorMessage();
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: clearErrorMessage
	//-------------------------------------------------------------------
	
	/**
	 * Clears the window's error message.
	 *
	 * @access public
	 */
	 
	function clearErrorMessage()
	{	
		$this->_errorMessage = '';
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: hasError
	//-------------------------------------------------------------------
	
	/**
	 * Returns whether or not the window contains errors. A window is defined as having errors if there are either field or user errors associated with it.
	 *
	 * @access public
	 * @return bool Whether or not the window has errors.
	 */
	 
	function hasError()
	{	
		$application = &$_SESSION['application'];
		
		return ($application->errorManager->hasFieldErrors() || $application->errorManager->hasUserErrors());
	}


	//-------------------------------------------------------------------
	// METHOD: setStatusMessage
	//-------------------------------------------------------------------
	
	/**
	 * This method sets a status message for the window.
	 *
	 * @access public
	 * @see getStatusMessage().
	 */
	 
	function setStatusMessage($statusMessage)
	{
		$this->_statusMessage = $statusMessage;
	}

	
	//-------------------------------------------------------------------
	// METHOD: getStatusMessage
	//-------------------------------------------------------------------
	
	/**
	 * This method sets a status message for the window.
	 *
	 * @access public
	 * @param bool $clearAfterDisplay If true, the status message will be cleared after it is returned.
	 * @see getStatusMessage().
	 */
	 
	function getStatusMessage($clearAfterDisplay = false)
	{
		if (strlen($this->_statusMessage))
		{
			$status = '<span class="ccStatus">' . $this->_statusMessage . '</span>';
			
			if ($clearAfterDisplay)
			{
				$this->clearStatusMessage();
			}
			
			return $status;
		}
		else
		{
			return;
		}
	}


	//-------------------------------------------------------------------
	// METHOD: clearStatusMessage
	//-------------------------------------------------------------------
	
	/**
	 * This method clears the status message.
	 *
	 * @access public
	 * @see getStatusMessage().
	 * @see setStatusMessage().
	 */
	 
	function clearStatusMessage()
	{
		$this->setStatusMessage('');
	}


	//-------------------------------------------------------------------
	// METHOD: hasStatus
	//-------------------------------------------------------------------
	
	/**
	 * This method returns a boolean to indicate if there is a status message for the window.
	 *
	 * @access public
	 * @return bool Whether or not the window has a status message.
	 * @see getStatusMessage().
	 * @see setStatusMessage().
	 */
	 
	function hasStatus()
	{
		return (strlen($this->_statusMessage) > 0);
	}


	//-------------------------------------------------------------------
	// METHOD: setErrorMessage
	//-------------------------------------------------------------------
	
	/**
	 * This method constructs sets 'user' error messages by simply trigger error.
	 *
	 * @access public
	 * @see CC_ErrorManager 
	 */
	 
	function setErrorMessage($errorMessage)
	{	
		trigger_error($errorMessage, E_USER_NOTICE);
	}

	
	//-------------------------------------------------------------------
	// METHOD: getErrorMessage
	//-------------------------------------------------------------------
	
	/** 
	 * This method constructs error messages based on the 'user' and 'field' error arrays in CC_Error_Manager. 'user' errors are displayed regardless of getVerboseErrors. 'field' errors are listed verbosely for each field if getVerboseErrors is true, otherwise a general message is displayed to check coloured fields for errors.
	 * 
	 * @access public
	 * @return string The error message.
	 */
	 
	function getErrorMessage()
	{	
		if ($this->hasError())
		{
			$application = &$_SESSION['application'];
	
			$this->_errorMessage = '';
			
			//user errors get displayed regardless of _verboseErrors
			if ($application->errorManager->hasUserErrors())
			{
				//cycle through the user errors	
				$userErrors = $application->errorManager->getUserErrors();
				
				$size = sizeof($userErrors);
				
				for ($i = 0; $i < $size; $i++)
				{
					$userError = $userErrors[$i];
					$this->_errorMessage .= '<span class="ccError">' . $userError->getMessage() . '</span><p>';
				}
				
				unset($size);
			}
	
			if ($application->errorManager->hasFieldErrors())
			{
				if ($this->_verboseErrors)
				{
					$this->_errorMessage .= '<span class="ccError">Please check the following fields and try again:</span><ul>';
												
					//cycle through the field errors
						
					$fieldErrors = $application->errorManager->getFieldErrors();
					
					$size = sizeof($fieldErrors);
					
					for ($j = 0; $j < $size; $j++)
					{
						$fieldError = $fieldErrors[$j];
						$this->_errorMessage .= '<li class="ccError">' . $fieldError->getMessage();
					}
					
					unset($size);
					
					$this->_errorMessage .= '</ul>';			
				}
				else 
				{
					$this->_errorMessage .= '<span class="ccError">There were problems with fields marked with this colour. Please check these and try again.</span><p>';
				}
			}
			
			return $this->_errorMessage;
		}
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setDefaultButton
	//-------------------------------------------------------------------

	/**
     * This method sets a default button with the window that is called if a user hits enter while in a field on the page. This method should always be called *after* the button has been registered.
     *
	 * @access public
	 * @param CC_Button The button to set as the default.
	 */

	function setDefaultButton(&$defaultButton)
	{
		$this->defaultButton = &$defaultButton;
		$this->defaultButton->_tabIndex = 420;
	}	


	//-------------------------------------------------------------------
	// METHOD: clearArgument
	//-------------------------------------------------------------------

	/**
     * This method clears a window argument of a given name.
     *
	 * @access public
	 * @param string $name The name of the argument to clear.
	 * @see setArgument()
	 */
	
	function clearArgument($name)
	{	
		unset($this->arguments[$name]);
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: argumentExists
	//-------------------------------------------------------------------

	/**
     * This method checks to see if an argument of a given name is registered with the window. 
	 *
	 * @access public
	 * @param string $name The key of the argument to check.
	 * @return bool Whether or not the argument of given name is registered with the window.
	 * @see registerArgument()
	 * @deprecated Use hasArgument() instead...
	 * @see hasArgument()
	 */

	function argumentExists($name)
	{
		trigger_error('argumentExists() is deprecated. Use hasArgument() instead.', E_USER_WARNING);
		return $this->hasArgument($name);
	}


	//-------------------------------------------------------------------
	// METHOD: hasArgument
	//-------------------------------------------------------------------

	/**
     * This method checks to see if an argument of a given name is registered with the window. 
	 *
	 * @access public
	 * @param string $name The key of the argument to check.
	 * @return bool Whether or not the argument of given name is registered with the window.
	 * @see registerArgument()
	 */

	function hasArgument($name)
	{	
		return array_key_exists($name . '', $this->arguments);
	}


	//-------------------------------------------------------------------
	// METHOD: getArgument
	//-------------------------------------------------------------------

	/**
     * This method retrieves a window argument of a given name. 
	 *
	 * @access public
	 * @param string $name The name of the argument to retrieve.
	 * @return mixed The argument of the given name.
	 * @see setArgument()
	 * @see argumentExists()
	 */

	function getArgument($name)
	{
		if (!isset($name) || $name == NULL)
		{
			trigger_error('CC_Window::getArgument() was passed an unset or NULL object.');
		}
		
		if (array_key_exists($name, $this->arguments))
		{
			return $this->arguments[$name];
		}
		else
		{	
			trigger_error('The following argument doesn\'t exist: ' . $name . '. If you don\'t want this error message, use CC_Window::argumentExists() before calling this method.');
		}
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setArgument
	//-------------------------------------------------------------------

	/**
     * This method registers a window argument of a given name. If you want to set an object in a window, use registerObject().
	 *
	 * @access public
	 * @param string $name The name of the argument to set.
	 * @param mixed $value The argument to register.
	 * @see getArgument()
	 * @see argumentExists()
	 * @see registerObject()
	 */

	function setArgument($name, $value)
	{	
		$this->arguments[$name] = $value;
	}
	

	//-------------------------------------------------------------------
	// METHOD: getHeader()
	//-------------------------------------------------------------------

	/** 
	  * This method is primarly for the default window classes, but you can (and should) use it for your own windows too. This should get called above all of your window display.
	  *
	  * @access public
	  * @return string The window header.
	  */

	function getHeader()
	{	
		return;
	}
	

	//-------------------------------------------------------------------
	// METHOD: getFooter()
	//-------------------------------------------------------------------

	/** 
	  * This method is primarly for the default window classes, but you can (and should) use it for your own windows too. This should get called below all of your window display.
	  *
	  * @access public
	  * @return string The window footer.
	  */

	function getFooter()
	{	
		return;
	}
}

?>