<?php
// $Id: CC_File_Upload_Field.php,v 1.39 2010/11/11 04:28:32 patrick Exp $
//=======================================================================
// CLASS: CC_File_Upload_Field
//=======================================================================

/**
 * The CC_File_Upload_Field represents a single upload file in the database.
 *
 * @package CC_Fields
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

class CC_File_Upload_Field extends CC_Field
{
	
	/**
     * The parent CC_Multiple_File_Upload_Field.
     *
     * @var CC_Multiple_File_Upload_Field $parentField
     * @access private
     */
	
	var $parentField;
	
	
	/**
     * The maximum number of bytes a file's size can be.
     *
     * @var int $maxFileSize
     * @access private
     */
     
	var $maxFileSize;
	
	
	/**
     * The temporary path to the file on the server.
     *
     * @var string $tempFilePath
     * @access private
     */
     
	var $tempFilePath;
	
	
	/**
     * The size of the uploaded file in bytes.
     *
     * @var int $fileSize
     * @access private
     */
     
    var $fileSize;
	
	
	/**
     * The MIME type of the uploaded file.
     *
     * @var string $fileMIMEType
     * @access private
     */
    
    var $fileMIMEType;
	
	
	/**
     * The name of the uploaded file.
     *
     * @var string $fileName
     * @access private
     */
     
	var $fileName;
	
	
	/**
     * A reference to the record this field belongs to.
     *
     * @var string $recordKey
     * @access private
     */
    
    var $recordKey;
    
	
	/**
     * The field to mark if this file should be deleted.
     *
     * @var CC_Checkbox_Field $deleteFileCheckbox
     * @access private
     */
     
	var $deleteFileCheckbox;
	
	
	/**
     * The window this field belongs to.
     *
     * @var CC_Wijndow $window
     * @access private
     */
     
	var $window;
	
	
	/**
     * An array of accepted MIME types.
     *
     * @var array $fileTypes
     * @access private
     */
    
    var $fileTypes;
	
	
	/**
     * Set to true only when field has been flagged for deletion.
     *
     * @var bool $deleteMe
     * @access private
     */
     
	var $deleteMe = false;
	
	
		/**
     * The path on the server (relative to the application path) where to save the file. 
     *
     * @var string $rootSavePath
     * @access private
     */
     
	var $rootSavePath;
	
	/**
     * Set to true if the field is already part of the database. It's used to differentiate between before and after a file field is updated in the database
     *
     * @var bool $inDatabase
     * @access private
     */
     
	var $inDatabase;
	
	/**
     * The CC_Text_Button to use to download the file.
     *
     * @var CC_Text_Button $downloadButton
     * @access private
     */
     
	var $downloadButton;

	
	/**
     * A flag which determines whether or not the delete checkbox will be shown.
     *
     * @var boolean $allowDelete
     * @access private
     */
     
	var $allowDelete = true;

	
	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_File_Upload_Field
	//-------------------------------------------------------------------

	/** 
	 * The CC_File_Upload_Field constructor sets its values here, yo. 
	 *
	 * @access public
	 * @param CC_Multiple_File_Upload_Field $parentField The CC_Multiple_File_Upload_Field the field belongs to.
	 * @param CC_Window $window The window the field belongs to.
	 * @param string $name The unique name of the field. Names must be unique so that N2O knows 	which fields to update when users submit data.
	 * @param string $label A text label to accompany the field to describe which input is either expected or displayed.
	 * @param bool $required Whether or not this field must contain data from the user before proceeding. Not required by default.
	 * @param string $rootSavePath The path on the server (relative to the application path) where to save the file.
	 * @param int $maxFileSize The maximum acceptable file size in bytes. The default is 1,000,000,000.
	 * @param string $filePath The full path on the server to the file. Default is blank.
	 */

	function CC_File_Upload_Field(&$parentField, $name, $label, $required, $rootSavePath, $maxFileSize = 1000000000, $filePath = "")
	{	
		$this->CC_Field($name, $label, $required);
		
		$this->validateIfNotRequired = true;
		
		$this->rootSavePath = $rootSavePath;
		$this->value = $filePath;
		
		$this->maxFileSize = $maxFileSize;
		$this->parentField = &$parentField;
		
		$this->deleteFileCheckbox = new CC_Checkbox_Field('DELETE_' . $label, 'Delete');
	}
		
	
	//-------------------------------------------------------------------
	// METHOD: addFileType
	//-------------------------------------------------------------------

	/** 
	 * This method allows one to add mime types (and associated extensions) to determined which file types are accepted by the field.
	 *
	 * @access public
	 * @param string $mimeTYPE The MIME type (eg. 'audio/mp3').
	 * @param string $extension The file extension. (eg. 'mp3').
	 */

	function addFileType($mimeType, $extension = '')
	{
		$this->fileTypes[] = array($mimeType, $extension);
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getFileSize
	//-------------------------------------------------------------------

	/** 
	 * Returns the file size of the uploaded file.
	 *
	 * @access public
	 * @return string The HTML for the field.
	 */

	function getFileSize()
	{
		return $this->fileSize;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getFileName
	//-------------------------------------------------------------------

	/** 
	 * Returns the filename of the uploaded file.
	 *
	 * @access public
	 * @return string The HTML for the field.
	 */

	function getFileName()
	{
		return $this->fileName;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setTemporaryPath
	//-------------------------------------------------------------------

	/** 
	 * Returns the temporary path where the file uploads go before being stored elsewhere.
	 *
	 * @access public
	 * @return string The HTML for the field.
	 */

	function getTemporaryPath()
	{
		return $this->tempFilePath;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setTemporaryPath
	//-------------------------------------------------------------------

	/** 
	 * Sets the temporary path where the file uploads go before being copied elsewhere.
	 *
	 * @access public
	 * @param string $tempFilePath The full path to the temp directory.
	 */

	function setTemporaryPath($tempFilePath)
	{
		$this->tempFilePath = $tempFilePath;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setFileSize
	//-------------------------------------------------------------------

	/** 
	 * Sets the file size of the uploaded file.
	 *
	 * @access public
	 * @param $fileSize int The file's size, in bytes.
	 */

	function setFileSize($fileSize)
	{
		$this->fileSize = $fileSize;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setFileName
	//-------------------------------------------------------------------

	/** 
	 * Sets the file name of the uploaded file.
	 *
	 * @access private
	 * @param string $fileName The file's name.
	 */

	function setFileName($fileName)
	{
		$this->fileName = $fileName;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setFileMIMEType
	//-------------------------------------------------------------------

	/** 
	 * Sets the uploaded file's MIME type.
	 *
	 * @access private
	 * @param string $fileMIMEType The file's MIME type
	 */

	function setFileMIMEType($fileMIMEType)
	{
		$this->fileMIMEType = $fileMIMEType;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: resetField
	//-------------------------------------------------------------------
	
	/** 
	 * Resets the all the file's info to blank.
	 *
	 * @access public
	 */
	
	function resetField()
	{
		$this->setFileName('');
		$this->setFileMIMEType('');
		$this->setFileSize('');
		$this->setTemporaryPath('');
		$this->value = '';
		$this->clearErrorMessage();		
		$this->deleteMe = false;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: validate
	//-------------------------------------------------------------------

	/** 
	 * The method ensure that the uploaded file is of the correct MIME type and extension and is not larger that the maximum allowable size.
	 *
	 * @access public
	 * @return bool Whether or not the file is valid.
	 */

	function validate()
	{
		$requestArrayName = substr($this->getRequestArrayName(), 0, strlen($this->getRequestArrayName()) - 2);
		
		$isValidFileType = false;
		$numTypes = sizeof($this->fileTypes);
		$typesList = '';
		
		if ($numTypes > 0)
		{
			for ($i = 0; $i < $numTypes; $i++)
			{
				if ($this->fileMIMEType == $this->fileTypes[$i][0])
				{
					$isValidFileType = true;
				}
				
				//build the types list just in case
				if ($i < ($numTypes - 2))
				{
					$typesList .= $this->fileTypes[$i][1] . ', ';
				}
				else if ($i == ($numTypes - 2))
				{
					$typesList .= $this->fileTypes[$i][1] . ' or ';
				}
				else
				{
					$typesList .= $this->fileTypes[$i][1];
				}
			}
			
			if (!$isValidFileType)
			{
				$this->setErrorMessage("'$this->fileName' is not a $typesList file. (" . $this->fileMIMEType . ' received)');
				//trigger_error($this->errorMessage, E_USER_WARNING);
				return false;
			}
			else
			{
				$this->clearAllErrors();
			}
		}
		
		if (filesize($this->tempFilePath) <= $this->maxFileSize)
		{
			return true;
		}
		else
		{
			$this->setErrorMessage("'$this->fileName' is too large!<br>The maximum upload size is $this->maxFileSize bytes.");
			//trigger_error($this->errorMessage, E_USER_WARNING);
			return false;
		}
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setDeleted
	//-------------------------------------------------------------------

	/** 
	 * Sets whether the file should be flagged as deleted or not.
	 *
	 * @access public
	 * @param bool $deleteMe Delete this?
	 */

	function setDeleted($deleteMe)
	{
		$this->deleteMe = $deleteMe;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getDeleted
	//-------------------------------------------------------------------

	/** 
	 * Gets whether the file is flagged as deleted or not.
	 *
	 * @access public
	 * @return bool Delete this?
	 */

	function getDeleted()
	{
		return $this->deleteMe;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getSavePath
	//-------------------------------------------------------------------
	
	/** 
	 * Returns the full path to the uploaded file.
	 *
	 * @access public
	 * @return string The path to the file.
	 */
	
	function getSavePath()
	{
		return $this->rootSavePath . $this->fileName;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getRootSavePath
	//-------------------------------------------------------------------
	
	/** 
	 * Returns the full path to the folder where the file is going.
	 *
	 * @access public
	 * @return string The path to the file.
	 */
	
	function getRootSavePath()
	{
		return $this->rootSavePath;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getRelativeRootSavePath
	//-------------------------------------------------------------------
	
	/** 
	 * Returns the relative path (to the document root) of the folder where the file is going.
	 *
	 * @access public
	 * @return string The path to the file.
	 */
	
	function getRelativeRootSavePath()
	{
		return substr($this->rootSavePath, strlen($_SERVER['DOCUMENT_ROOT']));
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getRelativeSavePath
	//-------------------------------------------------------------------
	
	/** 
	 * Returns the relative path (to the document root) of the uploaded file.
	 *
	 * @access public
	 * @return string The path to the file.
	 */
	
	function getRelativeSavePath()
	{
		return $this->getRelativeRootSavePath() . $this->fileName;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getDetailsHTML
	//-------------------------------------------------------------------

	/** 
	 * Returns HTML for the file details (including file size, in bytes) along with a linkable button for downloading. 
	 *
	 * @access public
	 * @return string HTML for displaying the file details.
	 */

	function getDetailsHTML()
	{
		return '<span class="ccFileName">' . '<a href="'. $this->getRelativeSavePath() . '">' . $this->fileName . '</a>';
	}

	
	//-------------------------------------------------------------------
	// METHOD: getFormattedSize
	//-------------------------------------------------------------------
	
	/** 
	 * Returns human-readable filesize.
	 *
	 * @access public
	 * @return string Thehuman-readable filesize.
	 */
	
	function getFormattedSize()
	{
		$filesize = $this->fileSize;
		
		if ($filesize > 1048576)
		{
			return number_format($filesize / 1048576, 2) . ' MB';
		}
		else if ($filesize > 1024)
		{
			return number_format($filesize / 1024, 2) . ' KB';
		}
		else
		{
			return number_format($filesize) . ' bytes';
		}
	}

	
	//-------------------------------------------------------------------
	// METHOD: getEditHTML
	//-------------------------------------------------------------------

	/** 
	 * Returns HTML for a 'file' form field along with a 'remove' button to delete the file from the application (and the file server).
	 *
	 * @access public
	 * @return string The HTML for the field.
	 */

	function getEditHTML()
	{
		$this->setFileInfo();
		
		$editHTML = '';
		
		if ($this->hasError())
		{
			$editHTML = '<nobr><input type="hidden" name="MAX_FILE_SIZE" value="' . $this->maxFileSize . '"><input type="file" name="' . $this->getRequestArrayName() . '" value="' . $this->value . '" class="' . $this->style . '">'. "\n";
			$editHTML .= '<br><span class="error">' . $this->getErrorMessage() . '</span>';
		}
		else if ($this->getValue() != '')
		{	
			$editHTML .= '<table cellspacing="1" cellpadding="3" border="0" class="ccFileTable"><tr><td valign="top">' . $this->getIcon() . '</td>';
			$editHTML .= '<td valign="top">' . $this->getDetailsHTML() . '</td>';
			if ($this->allowDelete)
			{
				$editHTML .= '<td valign="top">' . $this->deleteFileCheckbox->getHTML() . ' ' . $this->deleteFileCheckbox->getLabel() . '</td>';
			}
			$editHTML .= '</tr></table>';
		}
		else
		{
			$editHTML = '<nobr><input type="hidden" name="MAX_FILE_SIZE" value="' . $this->maxFileSize . '"><input type="file" name="' . $this->getRequestArrayName() . '" value="' . $this->value . '" class="' . $this->style . '">' . "\n";
		}

		return $editHTML;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getViewHTML
	//-------------------------------------------------------------------

	/** 
	 * Returns HTML with the file's icon and download link.
	 *
	 * @access public
	 * @return string The HTML for the field.
	 */

	function getViewHTML()
	{
		$this->setFileInfo();
		
		if (strlen($this->getValue()) > 0)
		{	
			$iconHTML = $this->getIcon();

			return '<table cellspacing="1" cellpadding="3" border="0" class="ccFileTable"><tr valign="top"><td>' . $this->getIcon() . '</td><td>' . $this->getDetailsHTML() . '</td></tr></table>';
		}
		else
		{
			return '<br><span class="tinybold">No file specified</span>';
		}
	}
	

	//-------------------------------------------------------------------
	// METHOD: setFileInfo
	//-------------------------------------------------------------------
	
	/** 
	 * Sets the all the file's info based on information found in the browser's file array. It also maintains path information to the file.
	 *
	 * @access public
	 * @param int $index The index of the current file in the browser's file array.
	 */
	
	function setFileInfo($index = -1)
	{
		$requestArrayName = substr($this->getRequestArrayName(), 0, strlen($this->getRequestArrayName()) - 2);
		
		if (isset($_FILES[$requestArrayName]) && isset($_FILES[$requestArrayName]['tmp_name'][$index]) && file_exists($_FILES[$requestArrayName]['tmp_name'][$index]))
		{	
			$fileArray = $_FILES[$requestArrayName];
			$this->setFileName($fileArray['name'][$index]);
			$this->setFileMIMEType($fileArray['type'][$index]);
			$this->setFileSize($fileArray['size'][$index]);
			$this->setTemporaryPath($fileArray['tmp_name'][$index]);
		}
		
		if (strlen($this->fileName) > 0)
		{
			$this->value = $this->getSavePath();
		}
		
		if (file_exists($this->value))
		{
			$this->fileSize = filesize($this->getValue());
			$this->fileName = substr($this->getValue(), strrpos($this->getValue(), '/') + 1);
		}		
	}
	
	
	
	//-------------------------------------------------------------------
	// METHOD: getIcon
	//-------------------------------------------------------------------

	/** 
	 * The method returns the icon associated with the file. Subclasses can override this to include custom icons. 
	 *
	 * @access public
	 * @return string HTML for displaying the icon.
	 */

	function getIcon()
	{
		return '<img src="/N2O/CC_Images/cc_summary.view.gif" width="16" height="18" border="0">';
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getValue
	//-------------------------------------------------------------------
	
	/** 
	 * Gets the value of the field, which is the path to the file.
	 *
	 * @access public
	 * @return string The path to the contained file.
	 */
	
	function getValue()
	{
		return $this->value;
	}
		
	
	//-------------------------------------------------------------------
	// METHOD: moveTempFileToUploadsFolder
	//-------------------------------------------------------------------
	
	/** 
	 * This method copies a file from temporary browser storage to the file server in the uploads directory.
	 *
	 * @access public
	 */
	 
	function moveTempFileToUploadsFolder()
	{
		// create the save path, if it doesn't exist	
		if (file_exists($this->tempFilePath))
		{
			if (!file_exists($this->rootSavePath))
			{
				if (!(mkdir($this->rootSavePath, 0777)))
				{
					// directory couldn't be created
					trigger_error("CC_File_Upload_Field: couldn't create directory '" . $this->rootSavePath . "'");
				}
			}
		
			// rename the file to name'_copy'.xxx, if it already exists
			if (file_exists($this->getValue()))
			{
				if (($dotIndex = strrpos($this->fileName, '.')) > 0)
				{
					$fileNameCopy = substr($this->fileName, 0, $dotIndex) . '_copy' . substr($this->fileName, strrpos($this->fileName, '.'));
				}
				else
				{
					$fileNameCopy = $this->fileName . '_copy';
				}
				
				$fullFilePath = $this->rootSavePath . $fileNameCopy;
				
				while (file_exists($fullFilePath))
				{
					if ($copyPosition = strpos($fileNameCopy, '_copy'))
					{
						if (($dotPos = strrpos($fileNameCopy, '.')) > 0)
						{
							$length = $dotPos - ($copyPosition + 5);
							
							$number = substr($fileNameCopy, ($copyPosition + 5), $length);
							
							if (($copyPosition + 5) == $dotPos)
							{
								$number = 2;
							}
							else
							{
								$number++;
							}
							
							$fileNameCopy = substr($this->fileName, 0, strrpos($this->fileName, '.')) . '_copy' . $number . substr($this->fileName, strrpos($this->fileName, '.'));
							$fullFilePath = $this->rootSavePath . $fileNameCopy;
						}
						else
						{
							if ($fileNameCopy == $this->fileName . '_copy')
							{
								$number = 2;	
							}
							else
							{
								$number++;
							}
							
							$fileNameCopy = $this->fileName . '_copy' . $number;
							$fullFilePath = $this->rootSavePath . $fileNameCopy;
						}
					}
				}

				$this->setFileName($fileNameCopy);
				$this->setValue($this->getSavePath());
			}
		
			if (!(move_uploaded_file($this->tempFilePath, $this->getValue())))
			{
				// file could not be copied
				die("CC_File_Upload_Field: couldn't move file '" . $this->tempFilePath . "' to '" . $this->getValue() . "'");
			}
			else
			{
				$fileNameToCH = $this->getValue();
				
				if (file_exists($fileNameToCH))
				{
					chmod($fileNameToCH, 0664);
				}
			}
		}
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: deleteCleanup
	//-------------------------------------------------------------------
	
	/** 
	 * This method is called when the field is deleted. The uploaded file is deleted from the file system. 
	 *
	 * @access public
	 */
	 
	function deleteCleanup()
	{
		if (file_exists($this->getValue()) && $this->deleteMe)
		{
			unlink($this->getValue());
		}
		$this->setValue('');
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: cancelCleanup
	//-------------------------------------------------------------------

	/** 
	 * This method is called when the screen that the field is on is cancelled. The uploaded file is deleted from the file system if it is not already in the database. 
	 *
	 * @access public
	 */
	
	function cancelCleanup()
	{
		//if (!$this->inDatabase())
		{
			$this->deleteCleanup();
		}
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: inDatabase
	//-------------------------------------------------------------------
	
	/** 
	 * This method checks to see if the database has been updated with the contained file. 
	 *
	 * @access public
	 * @return bool Is the file in the database?
	 */
	 
	function inDatabase()
	{
		$application = &$_SESSION['application'];
		
		$record = &$this->window->getRecord();
		
		$checkIfPathInRecordQuery = "select ID from " . $this->parentField->tableName . " where ID='" . $record->id . "' and " . $this->parentField->name . " like '%" . $this->getSavePath() . "%'";
		
		$checkIfPathInRecordResult = $application->db->doSelect($checkIfPathInRecordQuery);

		if (cc_fetch_row($checkIfPathInRecordResult))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setIsInDatabase
	//-------------------------------------------------------------------
	
	/** 
	 * Sets whether the database has been updated with the contained file. 
	 *
	 * @access public
	 * @param bool Is the file in the database?
	 */
	 
	function setIsInDatabase($inDatabase)
	{
		$this->inDatabase = $inDatabase;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getIsInDatabase
	//-------------------------------------------------------------------
	
	/** 
	 * Gets whether or not the database has been updated with the contained file. 
	 *
	 * @access public
	 * @return bool Is the file in the database?
	 */
	 
	function getIsInDatabase()
	{
		return $this->inDatabase;
	}


	//-------------------------------------------------------------------
	// METHOD: setRecord
	//-------------------------------------------------------------------

	/** 
	 * The method sets the record the member CC_File_Upload_Fields belong to. 
	 *
	 * @access public
	 * @param CC_Record $record The record to set.
	 */

	function setRecord(&$record)
	{
		parent::setRecord($record);
		
		$this->deleteFileCheckbox->setRecord($record);
	}



	//-------------------------------------------------------------------
	// METHOD: setRootSavePath
	//-------------------------------------------------------------------
	
	/** 
	 * Sets the full path to the folder where the file is going.
	 *
	 * @access public
	 * @param string The path to the file.
	 */
	
	function setRootSavePath($rootSavePath)
	{
		$this->rootSavePath = $rootSavePath;
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

		$window->registerComponent($this->deleteFileCheckbox);
	}
	
}

?>