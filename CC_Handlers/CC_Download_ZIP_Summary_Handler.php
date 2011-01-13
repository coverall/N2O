<?php
// $Id: CC_Download_ZIP_Summary_Handler.php,v 1.20 2006/02/18 02:15:36 patrick Exp $
//=======================================================================
// CLASS: CC_Download_ZIP_Summary_Handler
//=======================================================================

/**
 * This CC_Action_Handler handles ZIP file downloads.
 * 
 * @package CC_Action_Handlers
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 * @see CC_Summary_Download_Button
 */

class CC_Download_ZIP_Summary_Handler extends CC_Action_Handler
{
	/**
	 * The summary to download from.
	 *
	 * @access private
	 * @var CC_Summary $summaryToDownload
	 */
			
	var $summaryToDownload;


	/**
	 * The query to use for the downloaded records.
	 *
	 * @access private
	 * @var string $downloadQuery
	 */

	var $downloadQuery;


	/**
	 * The name to save the downloaded file as.
	 *
	 * @access private
	 * @var string $downloadFileName
	 */

	var $downloadFileName;
	
	
	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Download_ZIP_Summary_Handler
	//-------------------------------------------------------------------

	/**
	 * This method sets the parameters of the handler for use in the process method.
	 *
	 * @access public
	 * @param CC_Summary $summaryToDownload The summary we are downloading from.
	 * @param string $downloadQuery The query to use for downloading records.
	 * @param string $downloadFileName The name to save the downloaded file as.
	 */

	function CC_Download_ZIP_Summary_Handler(&$summaryToDownload, $downloadQuery, $downloadFileName)
	{
		$this->summaryToDownload = &$summaryToDownload;
		$this->downloadQuery = $downloadQuery;
		$this->downloadFileName = $downloadFileName;
		
		$this->CC_Action_Handler();
	}


	//-------------------------------------------------------------------
	// METHOD: process()
	//-------------------------------------------------------------------

	/**
	 * The method gets output from the CC_Summary as tab-delimited text based on the query, compresses the file (using ZIP compression) then uses the appropriate headers to stream the compressed file to the browser.
	 *
	 * @access public
	 */

	function process()
	{
		global $application;
		
		if (strstr($this->downloadFileName, '.txt') !== false)
		{
			$zipName = str_replace('.txt', '.zip', $this->downloadFileName);
			$filename = $this->downloadFileName;
		}
		else
		{
			$zipName = $this->downloadFileName . '.zip';
			$filename = $this->downloadFileName . '.txt';
		}
		
		if (false) //@function_exists('gzcompress'))
		{
			$zipFile = new CC_ZIP_File();
			$zipFile->addFile($this->summaryToDownload->getRawSummary($this->downloadQuery, true), $filename);

			header('Content-type: application/octet-stream; name=' . $zipName);
			header('Content-Disposition: attachment; filename=' . $zipName);
			header('Content-Length: ' . $zipFile->compressed_file_size);

			echo $zipFile->getFile();
		}
		else
		{	
			if (strstr($this->downloadFileName, '.txt') == false)
			{
				$this->downloadFileName = $filename;
			}

			$text = $this->summaryToDownload->getRawSummary($this->downloadQuery, true);
			
			header('Content-type: application/octet-stream; name=' . $this->downloadFileName);
			header('Content-Disposition: attachment; filename=' . $this->downloadFileName);
			header('Content-Length: ' . strlen($text));

			echo $text;
			
			$text = null;
			unset($text);
		}
		
		exit();
	}
}

?>