<?php
// $Id: CC_Download_File_Handler.php,v 1.9 2003/09/14 22:27:05 patrick Exp $
//=======================================================================

/**
 * This CC_Action_Handler handles general file downloads.
 * 
 * @package CC_Action_Handlers
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 * @see CC_File_Upload_Field
 */

class CC_Download_File_Handler extends CC_Action_Handler
{			
	/**
	 * The path of the file to download.
	 *
	 * @access private
	 * @var string $fileToDownload
	 */

	var $fileToDownload;


	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Download_File_Handler
	//-------------------------------------------------------------------

	/**
	 * This method sets the parameters of the handler for use in the process method.
	 *
	 * @access public
	 * @param string $fileToDownload The path of the file to download.
	 */

	function CC_Download_File_Handler($fileToDownload)
	{
		$this->fileToDownload = $fileToDownload;
		$this->CC_Action_Handler();
	}


	//-------------------------------------------------------------------
	// METHOD: process()
	//-------------------------------------------------------------------

	/**
	 * This method writes the appropriate headers and streams the given file to the browser.
	 *
	 * @access public
	 */

	function process()
	{
		$application = &$_SESSION['application'];
		
		$downloadFileName = substr($this->fileToDownload, strrpos($this->fileToDownload, '/') + 1);
		
		header('Content-type: application/octet-stream');
		header('Content-disposition: attachment; filename="' . $downloadFileName . '"'); 
		header('Content-transfer-encoding: binary'); 
		header('Content-length: ' . filesize($this->fileToDownload));
		
		// send file contents 
		$fp = fopen($this->fileToDownload, 'r'); 
		fpassthru($fp);	
		exit();
	}
}

?>