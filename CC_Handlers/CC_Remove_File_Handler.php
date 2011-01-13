<?php
// $Id: CC_Remove_File_Handler.php,v 1.12 2004/06/22 04:33:02 patrick Exp $
//=======================================================================
// CLASS: CC_Remove_File_From_Path_Handler
//=======================================================================

/**
 * This CC_Action_Handler deletes a file from a given record.
 * 
 * @package CC_Action_Handlers
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

class CC_Remove_File_Handler extends CC_Action_Handler
{
	/**
	 * The field to remove the file from.
	 *
	 * @access private
	 * @var CC_Record $record
	 */
	 
	var $record;
	var $multipleFileField;
	var $fileField;
	
	
	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Remove_File_Handler
	//-------------------------------------------------------------------

	/**
	 * This method sets the parameters of the handler for use in the process method.
	 *
	 * @access public
	 * @param CC_Upload_Field $fieldName The CC_Upload_Field we are working.
	 */

	function CC_Remove_File_Handler(&$object)
	{	
		$this->CC_Action_Handler();

		if (is_a($object, 'CC_Record'))
		{
			$this->record = &$object;
		}
		else if (is_a($object, 'CC_Multiple_File_Upload_Field'))
		{
			$this->multipleFileField = &$object;
		}
		else if (is_a($object, 'CC_File_Upload_Field'))
		{
			$this->fileField = &$object;
		}
		else
		{
			trigger_error('The passed object was not a CC_Record, a CC_Multiple_File_Upload_Field, or a CC_File_Upload_Field. It was a "' . get_class($object) . '".', E_USER_WARNING);
		}
	}


	//-------------------------------------------------------------------
	// METHOD: process()
	//-------------------------------------------------------------------

	/**
	 * This method resets the field and calls the field's deleteCleanup() method. Subclasses delete the actual file depending on how it is stored.
	 *
	 * @access public
	 */

	function process()
	{
		global $application;
		
		if (isset($this->record))
		{
			$this->processRecord();
		}
		else if (isset($this->multipleFileField))
		{
			$this->processMultipleFileField($this->multipleFileField);
		}
		else if (isset($this->fileField))
		{
			$this->processFileField($this->fileField);
		}
		else
		{
			trigger_error('Nothing is set!...');
		}
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: processRecord()
	//-------------------------------------------------------------------

	function processRecord()	
	{
		$fieldNames = array();
		
		$keys = array_keys($this->record->fields);
		
		$size = sizeof($keys);
		
		for ($i = 0; $i < $size; $i++)
		{
			$currentField = &$this->record->fields[$keys[$i]];
			
			if (is_a($currentField, 'CC_File_Upload_Field'))
			{
				$this->processFileField($currentField);
			}
			else if (is_a($currentField, 'CC_Multiple_File_Upload_Field'))
			{
				$this->processMultipleFileField($currentField);
			}

			unset($currentField);
		}
		
		unset($size);
	}


	//-------------------------------------------------------------------
	// METHOD: processMultipleFileField()
	//-------------------------------------------------------------------

	function processMultipleFileField(&$field)
	{
		$sizeMultiFile = sizeof($field->uploadFieldArray);

		for ($j = 0; $j < $sizeMultiFile; $j++)
		{		
			$fileField = &$field->uploadFieldArray[$j];
			
			if ($fileField->getFileName() != '')
			{
				if ($fileField->deleteFileCheckbox->isChecked())
				{
					$fileField->setDeleted(true);
				}
			}
				
			unset($fileField);
		}

		unset($sizeMultiFile);
		
		$field->removeDeletedUploadFields();
		$field->setValue($field->getValue());
	}


	//-------------------------------------------------------------------
	// METHOD: processFileField()
	//-------------------------------------------------------------------

	function processFileField(&$field)
	{
		if ($field->getFileName() != '')
		{
			if ($field->deleteFileCheckbox->isChecked())
			{
				$field->setDeleted(true);
			}
			
			$field->deleteCleanup();
		}
	}


}

?>