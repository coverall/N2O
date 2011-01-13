<?php
// $Id: CC_Multiple_File_Upload_Field.php,v 1.19 2010/11/11 04:28:32 patrick Exp $
//=======================================================================
// CLASS: CC_Multiple_File_Upload_Field
//=======================================================================

/**
 * The CC_Multiple_File_Upload_Field field represents (multiple) file input from the user. Selected files are copied to the server to an uploads directory when they are uploaded.
 *
 * You need to add these lines to a .htaccess file at the level of your application (or higher) so that PHP will be able to upload large files
 * 
 * php_value upload_max_filesize 500M
 * php_value post_max_size 5000M
 * php_value file_uploads 10
 * php_value max_execution_time 600
 * php_value memory_limit 5001M
 * 
 * @package CC_Fields
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

class CC_Multiple_File_Upload_Field extends CC_Field
{
	//-------------------------------------------------------------------
	
	/**
     * The window!
     *
     * @var CC_Window $window
     * @access private
     */
     
	var $window;
	
	
	/**
     * An array of all the upload fields in this object.
     *
     * @var array $uploadFieldArray
     * @access private
     */
     
	var $uploadFieldArray;
							
	/**
     * The name of the files' CC_File_Upload class.
     *
     * @var string $uploadClass
     * @access private
     */
     
	var $uploadClass;
	
	
	/**
     * The button to click to add more files.
     *
     * @var CC_Button $addMoreFilesButton
     * @access private
     */
     
	var $addMoreFilesButton;
	
	
	/**
     * Whether to include the 'add more files' button.
     *
     * @var bool $allowFileAdditions
     * @access private
     */
     
	var $allowFileAdditions;
	
	
	/**
     * The minimum number of blank fields to have showing at all times.
     *
     * @var int $minimumNumberBlankFields
     * @access private
     */
     
	var $minimumNumberBlankFields;
		
	
	/**
     * The maximum file size for file uploads in bytes.
     *
     * @var int $maxFileSize
     * @access private
     */
     
	var $maxFileSize;
	
	
	/**
     * Variable set in CC_Window when validating member Upload Fields.
     *
     * @var isValid $isValid
     * @access private
     */
	
	var $isValid;


	/**
     * A flag which determines whether or not the delete checkbox will be shown.
     *
     * @var boolean $allowDelete
     * @access private
     */
     
	var $allowDelete = true;


	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Multiple_File_Upload_Field
	//-------------------------------------------------------------------

	/** 
	 * The CC_Multiple_File_Upload_Field constructor sets its values here, yo. 
	 *
	 * @access public
	 * @param string $name The unique name of the field. Names must be unique so that N2O knows which fields to update when users submit data.
	 * @param string $label A text label to accompany the field to describe which input is either expected or displayed.
	 * @param bool $required Whether or not this field must contain data from the user before proceeding. Not required by default.
	 * @param string $rootSavePath The path to upload the files to. The path has to have the appropriate permissions set so it will allow PHP to copy a file there.
	 * @param string $fileList The list of file paths in a pipe-delimited (|) list. Default is blank.
	 * @param int $minimumNumberBlankFields The minimum number of blank fields to have showing at all times. Default is 1.
	 * @param string $uploadClass The CC_File_Upload class to use for the file fields. Default is CC_File_Upload_Field.
	 * @param bool $allowFileAdditions Whether or not to allow the addition of more files. Default is true.
	 * @param int $maxFileSize The maximum allowable size for the file, in bytes. Default is 1,000,000,000 bytes.
	 */

	function CC_Multiple_File_Upload_Field($name, $label, $required, $rootSavePath, $fileList = '', $minimumNumberBlankFields = 1, $uploadClass = 'CC_File_Upload_Field', $allowFileAdditions = true, $maxFileSize = 1000000000)
	{
		$this->rootSavePath = $rootSavePath;
		$this->maxFileSize = $maxFileSize;
		
		$this->validateIfNotRequired = true;
			
		$this->CC_Field($name, $label, $required, $fileList);
		
		$this->uploadClass = $uploadClass;
		$this->minimumNumberBlankFields = $minimumNumberBlankFields; 
		
		$this->allowFileAdditions = $allowFileAdditions;
		
		// build array of File Upload fields based on the contents of the fileList variable
		// which is a comma-delimited list of path names as stored in the database
		
		$this->uploadFieldArray = array();
		
		if ($fileList)
		{
			$this->fileNameArray = explode('|', $fileList);
		}
	}
			
	
	//-------------------------------------------------------------------
	// METHOD: getEditHTML
	//-------------------------------------------------------------------

	/** 
	 * Returns HTML for a set of 'file' form fields. The member CC_File_Upload subclass calls its getEditHTML() to display these.
	 *
	 * @access public
	 * @return string The HTML for the field.
	 */

	function getEditHTML()
	{
		$editHTML = '';

		// the case for a single file upload field 
		// that has been removed
		
		if ($this->getNumberNonDeletedFields() == 0)
		{
			$this->uploadFieldArray[] = &$this->createNewUploadField();
		}
		
		$size = sizeof($this->uploadFieldArray);
		
		for ($i = 0; $i < $size; $i++)
		{
			$editHTML .= '<div class="ccFileUploadField">' . $this->uploadFieldArray[$i]->getEditHTML() . '</div>';
		}
		
		unset($size);
	
		if ($this->allowFileAdditions)
		{
			$editHTML .= $this->addMoreFilesButton->getHTML();
		}
		
		return $editHTML;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getViewHTML
	//-------------------------------------------------------------------

	/** 
	 * Returns HTML for displaying uploaded files without allowing for more additions. The CC_File_Upload class calls it's getViewHTML method for to display this. 
	 *
	 * @access public
	 * @return string The HTML for the field.
	 */

	function getViewHTML()
	{
		$viewHTML = '';

		$hasFiles = false;
		
		for ($i = 0; $i < sizeof($this->uploadFieldArray) ; $i++)
		{		
			$uploadField = &$this->uploadFieldArray[$i];
			
			if ($uploadField->getValue() != '')
			{
				$viewHTML .= $uploadField->getViewHTML() . "<p>";
				$hasFiles = true;
			}
			
			unset($uploadField);
		}
		
		if (!$hasFiles)
		{
			$viewHTML = "<span class=\"tinybold\">No files specified</span>";
		}

		return $viewHTML;
	}
		
	
	//-------------------------------------------------------------------
	// METHOD: getValue
	//-------------------------------------------------------------------
	
	/** 
	 * Returns the value as a comma delimited list of file paths.
	 *
	 * @access public
	 * @return string The value of the field as a comma delimited list of file paths.
	 */
	
	function getValue()
	{
		$this->value = '';
		
		for ($i = 0; $i < sizeof($this->uploadFieldArray); $i++)
		{			
			$uploadField = $this->uploadFieldArray[$i];
			
			if ($uploadField->getValue() != '')
			{
				$this->value .= $uploadField->getValue() . '|';
			}
		}
		
		$this->value = substr($this->value, 0, strlen($this->value) - 1);
		
		return $this->value;
	}	
		
	
	//-------------------------------------------------------------------
	// METHOD: hasValue
	//-------------------------------------------------------------------
	
	/** 
	 * Returns whether or not the field has a value (ie. any associated files).
	 *
	 * @access public
	 * @return bool Whether there are uploaded files associated with the field.
	 */
	 	
	function hasValue()
	{
		$size = sizeof($this->uploadFieldArray);

		for ($i = 0; $i < $size; $i++)
		{			
			$uploadField = $this->uploadFieldArray[$i];
			
			if ($uploadField->hasValue())
			{
				return true;
			}
		}
		
		unset($size);
		
		return false;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setRecord
	//-------------------------------------------------------------------

	/** 
	 * The method sets the record the membwer CC_File_Upload_Fields belong to. 
	 *
	 * @access public
	 * @param CC_Record $record The record to set.
	 */

	function setRecord(&$record)
	{
		parent::setRecord($record);
		
		$size = sizeof($this->uploadFieldArray);
		
		for ($i = 0; $i < $size; $i++)
		{
			$this->uploadFieldArray[$i]->setRecord($record);
		}

		unset($size);
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: createNewUploadField
	//-------------------------------------------------------------------

	/** 
	 * This method creates and returns a new upload field. 
	 *
	 * @access public
	 * @param string $filePath The path to the file. Default is blank.
	 * @return CC_File_Upload_Field The new upload.
	 */
	
	function &createNewUploadField($filePath = '')
	{
		$uniqueInt = sizeof($this->uploadFieldArray);
		$newUploadField = new $this->uploadClass($this, $this->getRequestArrayName() . '[]', $this->getRequestArrayName() . '_' . $uniqueInt, $this->required, $this->rootSavePath, $this->maxFileSize, $filePath);
		$newUploadField->allowDelete = $this->allowDelete;

		return $newUploadField;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: addUploadField
	//-------------------------------------------------------------------

	/** 
	 * This method adds a new upload field to the upload Fields array. 
	 *
	 * @access public
	 * @param CC_File_Upload_Field $newUploadField The path to the file. Default is blank.
	 * @param bool $hasError Whether or not the field has an error.
	 * @todo Do we need the $hasError parameter here?
	 */

	function addUploadField(&$newUploadField, $hasError = false)
	{
		$this->uploadFieldArray[] = &$newUploadField;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: resetInvalid
	//-------------------------------------------------------------------

	/** 
	 * This method resets all invalid upload fields. 
	 *
	 * @access private
	 * @see CC_Window::updateFieldFromPage()
	 */

	function resetInvalid()
	{		
		$this->clearErrorMessage();
						
		$size = sizeof($this->uploadFieldArray);

		for ($i = 0; $i < sizeof($this->uploadFieldArray); $i++)
		{
			if (isset($this->uploadFieldArray[$i]))
			{
				$uploadField = &$this->uploadFieldArray[$i];
				
				if ($uploadField->hasError())
				{
					// reset the upload file field from the object
					$uploadField->resetField();
				}
				
				unset($uploadField);
			}
		}		

		unset($size);
	}


	//-------------------------------------------------------------------
	// METHOD: getNumberNonDeletedFields
	//-------------------------------------------------------------------

	/** 
	 * This method returns the number of non-deleted upload fields. 
	 *
	 * @access private
	 * @return int The number of non-deleted upload fields.
	 */

	function getNumberNonDeletedFields()
	{
		$numNonDeletedFields = 0;
		
		$size = sizeof($this->uploadFieldArray);
		
		for ($i = 0; $i < $size; $i++)
		{
			if (!$this->uploadFieldArray[$i]->getDeleted())
			{
				$numNonDeletedFields++;
			}
		}

		unset($size);
		
		return $numNonDeletedFields;		
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: removeDeletedUploadFields
	//-------------------------------------------------------------------

	/** 
	 * This method removes upload fields flagged deleted. 
	 *
	 * @access private
	 * @see CC_Window::updateFieldFromPage()
	 */

	function removeDeletedUploadFields()
	{
		$size = sizeof($this->uploadFieldArray);
		
		for ($i = 0; $i < $size; $i++)
		{
			if ($this->uploadFieldArray[$i]->getDeleted())
			{
				// delete any files associated with this field
				$this->uploadFieldArray[$i]->deleteCleanup();
				
				$this->uploadFieldArray[$i]->resetField();

				if ($i < (sizeof($this->uploadFieldArray) - 1))
				{
					$i--;
					$size--;
				}
			}
		}
		
		unset($size);
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: deleteCleanup
	//-------------------------------------------------------------------

	/** 
	 * This method calls the deleteCleanup method in all member CC_File_Upload_Fields. 
	 *
	 * @access public
	 * @see CC_File_Upload_Field::deleteCleanup()
	 */

	function deleteCleanup()
	{
		$size = sizeof($this->uploadFieldArray);

		for ($i = 0; $i < $size; $i++)
		{
			$uploadField = &$this->uploadFieldArray[$i];
			$uploadField->deleteCleanup();
			unset($uploadField);
		}
		
		unset($size);
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: cancelCleanup
	//-------------------------------------------------------------------

	/** 
	 * This method calls the cancelCleanup method in all member CC_File_Upload_Fields. 
	 *
	 * @access public
	 * @see CC_File_Upload_Field::cancelCleanup()
	 */

	function cancelCleanup()
	{
		$size = sizeof($this->uploadFieldArray);

		for ($i = 0; $i < $size; $i++)
		{
			$uploadField = &$this->uploadFieldArray[$i];
			$uploadField->cancelCleanup();	
			unset($uploadField);
		}
		
		unset($size);
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: validate
	//-------------------------------------------------------------------

	/** 
	 * Returns whether or not the fiels has an error.
	 *
	 * @access public
	 * @return bool Is the field valid?
	 */

	function validate()
	{
		return !$this->hasError();
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: updateDatabase
	//-------------------------------------------------------------------

	/** 
	 * The method updates the database with the latest comma-delimited file list.
	 *
	 * @access private
	 */

	function updateDatabase()
	{
		global $application;
		
		// update the record's value, only if the file was there to begin with
		// it could have been added and not updated
		
		$record = &$this->window->getRecord();
		
		$updateQuery = 'update ' . $this->tableName . ' set ' . $this->name . "='" . $this->getValue() . "' where ID='" . $record->id . "'";
			
		$application->db->doUpdate($updateQuery);
	}


	//-------------------------------------------------------------------
	// METHOD: register
	//-------------------------------------------------------------------

	/**
	 * This is a callback method that gets called by the window when the
	 * component is registered. It's up to the component to decide which
	 * registerXXX() method it should call on the window. Should your
	 * custom component consist of multiple components, you may need to
	 * make multiple calls.
	 *
	 * @access public
	 */

	function register(&$window)
	{
		parent::register($window);

		if (isset($this->fileNameArray))
		{
			$numFiles = sizeof($this->fileNameArray);

			for ($i = 0; $i < $numFiles; $i++)
			{
				if (file_exists($this->fileNameArray[$i]))
				{
					$this->uploadFieldArray[] = &$this->createNewUploadField($this->fileNameArray[$i]);
				}
				else
				{
					trigger_error($this->fileNameArray[$i] . ' does not exist. Skipping...', E_USER_WARNING);
				}
			}
		}
		else
		{
			$numFiles = 0;
		}

		if ($this->allowFileAdditions)
		{
			// add the minimum number of blank fields on top of the files already present
			for ($j = $numFiles; $j < $numFiles + $this->minimumNumberBlankFields; $j++)
			{
				$this->uploadFieldArray[] = &$this->createNewUploadField();
			}
			
			$this->addMoreFilesButton = new CC_Button('Add More Files');
			$this->addMoreFilesButton->setStyle('ccFileUploadFieldAddButton');
			$this->addMoreFilesButton->setValidateOnClick(false);
			$this->addMoreFilesButton->setFieldUpdater(true);
			//$this->addMoreFilesButton->setFieldsToUpdate($this->name);
			
			$this->addMoreFilesButton->registerHandler(new CC_Add_File_Upload_Field_Handler($this));
		}
		else
		{
			// add fields so the total is minimumNumberBlankFields
			for ($j = $numFiles; $j < $this->minimumNumberBlankFields; $j++)
			{
				$this->uploadFieldArray[] = &$this->createNewUploadField();
			}
		}
		
		$size = sizeof($this->uploadFieldArray);
		
		for ($i = 0; $i < $size; $i++)
		{
			$window->registerComponent($this->uploadFieldArray[$i]->deleteFileCheckbox);
		}
		
		// there are no upload fields, we need to include at least one
		if ((sizeof($this->uploadFieldArray) == 0))
		{
			$this->uploadFieldArray[] = &$this->createNewUploadField();
		}

		if ($this->allowFileAdditions)
		{
			$window->registerComponent($this->addMoreFilesButton);
		}
	}

	
	//-------------------------------------------------------------------
	// METHOD: resetField
	//-------------------------------------------------------------------
	
	function resetField()
	{
		$this->uploadFieldArray = array();
		
		$this->uploadFieldArray[] = &$this->createNewUploadField();
		
		if ($this->allowFileAdditions)
		{
			// add the minimum number of blank fields on top of the files already present
			for ($j = $numFiles; $j < $numFiles + $this->minimumNumberBlankFields; $j++)
			{
				$this->uploadFieldArray[] = &$this->createNewUploadField();
			}			
		}
		else
		{	
			// add fields so the total is minimumNumberBlankFields
			for ($j = 1; $j < $this->minimumNumberBlankFields; $j++)
			{	
				$this->uploadFieldArray[] = &$this->createNewUploadField();
			}
		}
		
		// there are no upload fields, we need to include at least one
		if ((sizeof($this->uploadFieldArray) == 0))
		{
			$this->uploadFieldArray[] = &$this->createNewUploadField();
		}
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getUploadField
	//-------------------------------------------------------------------

	/**
	 * This method returns an upload field at a particular index. If the
	 * index is invalid or there aren't any fields, it returns false.
	 *
	 * @return CC_File_Upload_Field The upload field object at the specified index.
	 * @access public
	 */

	function &getUploadField($index)
	{
		if ($index < 0 || $index >= sizeof($this->uploadFieldArray))
		{
			return false;
		}
		else
		{
			return $this->uploadFieldArray[$index];
		}
	}


	//-------------------------------------------------------------------
	// METHOD: setAllowDelete
	//-------------------------------------------------------------------

	/**
	 * This method toggles whether or not the ability to delete the files will exist.
	 *
	 * @access public
	 */

	function setAllowDelete($delete)
	{
		$this->allowDelete = $delete;
		$size = sizeof($this->uploadFieldArray);
		
		for ($i = 0; $i < $size; $i++)
		{
			$this->uploadFieldArray[$i]->allowDelete = $delete;
		}
		
		unset($size, $i);
	}


	//-------------------------------------------------------------------
	// METHOD: handleUpdateFromRequest
	//-------------------------------------------------------------------

	/**
     * This method gets called by CC_Window when it's time to update the field from the $_REQUEST array. Most fields are straight forward, but some have additional fields in the request that need to be handled specially. Such fields should override this method, and update the field's value in their own special way.
     *
     * @access public
     * @param mixed $fieldValue The value to set the field to.
     * @see getValue()
     */	

	function handleUpdateFromRequest()
	{
		$this->resetInvalid();
		
		if (!isset($_FILES[$this->getRequestArrayName()]))
		{
			return;
		}
	
		$ksize = sizeof(@$_FILES[$this->getRequestArrayName()]['tmp_name']);
		
		for ($k = 0; $k < $ksize; $k++)
		{
			if (file_exists($_FILES[$this->getRequestArrayName()]['tmp_name'][$k]))
			{
				$uploadField = &$this->uploadFieldArray[$k];

				$index = $k;
				
				// find the next empty upload field to fill
				while ($uploadField->getValue() != '')
				{
					if ($index < sizeof($this->uploadFieldArray))
					{
						unset($uploadField);
						$uploadField = &$this->uploadFieldArray[$index++];
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
					$this->clearAllErrors();
				}
				else
				{
					$this->setErrorMessage($uploadField->getErrorMessage());
				}
				
				unset($uploadField);
			}
		}
		
		unset($ksize);
	}


	//-------------------------------------------------------------------
	// STATIC METHOD: getInstance
	//-------------------------------------------------------------------

	/**
	 * This is a static method called by CC_Record when it needs an instance
	 * of a field. The implementing field needs to return a constructed
	 * instance of itself.
	 *
	 * @access public
	 */

	static function &getInstance($className, $name, $label, $value, $args, $required)
	{
		$minFields = (isset($args->minFields) ? $args->minFields : 1);
		$fieldClass = (isset($args->fieldClass) ? $args->fieldClass : 'CC_File_Upload_Field');
		$showAdd = (isset($args->showAdd) ? ($args->showAdd == 1) : false);
		$uploadPath = (isset($args->uploadPath) ? $args->uploadPath : APPLICATION_PATH . 'uploads/');

		$field = new $className($name, $label, $required, $uploadPath, $value, $minFields, $fieldClass, $showAdd, 1000000000);

		unset($minFields, $fieldClass, $showAdd, $uploadPath);

		return $field;
	}

}
?>