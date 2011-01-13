<?php
// $Id: CC_Download_XLS_Summary_Handler.php,v 1.22 2010/11/11 04:28:32 patrick Exp $
//=======================================================================
// CLASS: CC_Download_XLS_Summary_Handler
//=======================================================================

/**
 * This CC_Action_Handler handles the converstion of a summary into a Micro$oft Excel file (.xls).
 * 
 * @package CC_Action_Handlers
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 * @see CC_Summary_Download_Button
 */

class CC_Download_XLS_Summary_Handler extends CC_Action_Handler
{
	/**
	 * The summary to download from.
	 *
	 * @access private
	 * @var CC_Summary $summary
	 */
			
	var $summary;


	/**
	 * The name to save the downloaded file as.
	 *
	 * @access private
	 * @var string $downloadFileName
	 */

	var $downloadFileName;
	
	
	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Download_XLS_Summary_Handler
	//-------------------------------------------------------------------

	/**
	 * This method sets the parameters of the handler for use in the process method.
	 *
	 * @access public
	 * @param CC_Summary $summary The summary we are downloading from.
	 * @param string $downloadQuery The query to use for downloading records.
	 * @param string $downloadFileName The name to save the downloaded file as.
	 */

	function CC_Download_XLS_Summary_Handler(&$summary, $downloadFileName = null)
	{
		$this->summary = &$summary;
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
		require_once('Spreadsheet/Excel/Writer.php');
	
		// pass false to get a raw data array
		$rawData = $this->summary->getRawSummary($this->summary->getDownloadAllQuery(), false);
		
		// Creating a workbook 
		$workbook = new Spreadsheet_Excel_Writer();
		$workbook->setVersion(8);

		// sending HTTP headers 
		$filename = (isset($this->downloadFileName) ? $this->downloadFileName : $this->summary->downloadAllFileName) . '.xls';
		$filename = $filename;
		$workbook->send($filename);
		
		$format_bold = &$workbook->addFormat();
		$format_bold->setSize(12);
		$format_bold->setBold();
		$format_bold->setColor('grey');
		//$format_bold->setAlign('center');
		
		$format_title = &$workbook->addFormat();
		$format_title->setSize(14);
		$format_title->setBold();
		$format_title->setColor('white');
		$format_title->setPattern(1);
		$format_title->setBgColor('black');
		//$format_title->setAlign('merge');
		
		$evenColour = $workbook->setCustomColor(9, 230, 230, 230);
		$oddColour = $workbook->setCustomColor(10, 255, 255, 255);
		$borderColour = $workbook->setCustomColor(11, 200, 200, 200);

		$format_even_row = &$workbook->addFormat();
		$format_even_row->setFgColor($evenColour);
		$format_even_row->setBorder(1);
		$format_even_row->setBorderColor($borderColour);
		
		$format_odd_row = &$workbook->addFormat();
		$format_odd_row->setFgColor($oddColour);
		$format_odd_row->setBorder(1);
		$format_odd_row->setBorderColor($borderColour);
		
		$numberCols = sizeof($rawData[0]);
		$numberRows = sizeof($rawData);
		
		// Creating a worksheet 
		$worksheet = &$workbook->addWorksheet('Summary Data');
		if (function_exists('iconv'))
		{
			$worksheet->setInputEncoding('UTF-8');
		}
		$worksheet->setColumn(1, 1, 15);
		$worksheet->setColumn(2, $numberCols, 20);

		// title
		$worksheet->write(0, 0, $this->summary->pluralDisplayName, $format_title);
		for ($i = 1; $i < $numberCols; $i++)
		{
			$worksheet->write(0, $i, '', $format_title);
		}

		// headers
		for ($i = 0; $i < $numberCols; $i++)
		{
			$worksheet->write(1, $i, $rawData[0][$i], $format_bold);
		}
		
		// data
		$evenOddRowCounter = 1;
		for ($j = 1; $j < $numberRows; $j++)
		{
			$format_row = ($evenOddRowCounter++ % 2 == 0) ? $format_even_row : $format_odd_row;

			for ($i = 0; $i < $numberCols; $i++)
			{
				if (is_numeric($rawData[$j][$i]))
				{
					$worksheet->writeNumber($j + 1, $i, $rawData[$j][$i], $format_row);
				}
				else
				{
					$worksheet->write($j + 1, $i, $rawData[$j][$i], $format_row);
				}
			}
		}
				
		$workbook->close();

		exit();
	}
}

?>