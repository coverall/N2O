<?php
// $Id: CC_AddFileUploadField_Handler.php,v 1.5 2003/08/25 22:16:11 mike Exp $
//=======================================================================
// CLASS: CC_Add_File_Upload_Field_Handler
//=======================================================================

/**
 * This CC_Action_Handler adds a CC_File_Upload_Field in the CC_Multiple_File_Upload_Field.
 * 
 * @package CC_Action_Handlers
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 * @see CC_Multiple_File_Upload_Field
 */

class CC_Add_File_Upload_Field_Handler extends CC_Action_Handler
{
	/**
	 * The CC_Multiple_File_Upload_Field we are adding to.
	 *
	 * @access private
	 * @var CC_Multiple_File_Upload_Field $multipleFileUploadField
	 */
	 
	var $multipleFileUploadField;

	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Add_File_Upload_Field_Handler
	//-------------------------------------------------------------------

	/**
	 * This method sets the parameters of the handler for use in the process method.
	 *
	 * @access public
	 * @param CC_Multiple_File_Upload_Field $multipleFileUploadField The field we are handling for.
	 */
	
	function CC_Add_File_Upload_Field_Handler(&$multipleFileUploadField)
	{
		$this->multipleFileUploadField = &$multipleFileUploadField;
	}
	
	//-------------------------------------------------------------------
	// METHOD: process()
	//-------------------------------------------------------------------

	/**
	 * This method adds upload fields while ensuring that there are the minimum number of blank fields present.
	 *
	 * @access public
	 */

	function process()
	{
		$application = &$_SESSION['application'];
		
		$minimumNumberBlankFields = $this->multipleFileUploadField->minimumNumberBlankFields;
		
		$start = sizeof($this->multipleFileUploadField->uploadFieldArray);
		
		for ($i = $start; $i < $start + $minimumNumberBlankFields; $i++)
		{	
			$this->multipleFileUploadField->uploadFieldArray[] = &$this->multipleFileUploadField->createNewUploadField();		
		}
	}
}

?>