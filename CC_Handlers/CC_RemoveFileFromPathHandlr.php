<?php
// $Id: CC_RemoveFileFromPathHandlr.php,v 1.6 2003/09/02 23:54:07 patrick Exp $
//=======================================================================
// CLASS: CC_Remove_File_From_Path_Handler
//=======================================================================

/**
 * This CC_Action_Handler deletes a given file.
 * 
 * @package CC_Action_Handlers
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

class CC_Remove_File_From_Path_Handler extends CC_Remove_File_Handler
{
	/**
	 * The path to the file to delete.
	 *
	 * @access private
	 * @var string $fileToDelete
	 */

	var $fileToDelete;

		
	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Remove_File_From_Path_Handler
	//-------------------------------------------------------------------

	/**
	 * This method sets the parameters of the handler for use in the process method.
	 *
	 * @access public
	 * @param CC_Window $window The window we are on.
	 * @param string $recordKey The record key of the pertinent record.
	 * @param string $fileToDelete The path to the file to delete.
	 * @param string $fieldName The name of the CC_Upload_Field we are working.
	 * @param string $tableName The table name the file field belongs to.
	 */

	function CC_Remove_File_From_Path_Handler(&$window, $recordKey, $fileToDelete, $fieldName, $tableName)
	{	
		$this->fileToDelete = $fileToDelete;
		
		$this->CC_Remove_File_Handler($window, $recordKey, $fieldName, $tableName);
	}


	//-------------------------------------------------------------------
	// METHOD: process()
	//-------------------------------------------------------------------

	/**
	 * This method deletes the file from the server's filesystem.
	 *
	 * @access public
	 */

	function process()
	{
		parent::process();
		
		if (file_exists($this->fileToDelete))
		{
			unlink($this->fileToDelete);
		}
	}
}

?>