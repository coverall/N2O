<?php
// $Id: CC_Delete_File_Handler.php,v 1.3 2003/07/07 05:13:51 jamie Exp $
//=======================================================================

/**
 * This CC_Action_Handler deletes a given file.
 * 
 * @package CC_Action_Handlers
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

class CC_Delete_File_Handler extends CC_Action_Handler
{
	/**
	 * The path to the file to delete.
	 *
	 * @access private
	 * @var string $fileName
	 */

	var $fileName;
	

	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Delete_File_Handler
	//-------------------------------------------------------------------

	/**
	 * This method sets the parameters of the handler for use in the process method.
	 *
	 * @access public
	 * @param CC_Window $window The window we are on.
	 * @param string $fileName The path to the file to delete.
	 */

	function CC_Delete_File_Handler(&$window, $fileName)
	{	
		$this->fileName = $fileName;
				
		$this->CC_Action_Handler();
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
		if (file_exists($this->fileName))
		{
			unlink($this->fileName);
		}
	}
}

?>