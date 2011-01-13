<?php
// $Id: CC_Download_Excel_Button.php,v 1.1 2003/09/13 23:22:23 jamie Exp $
//=======================================================================
// CLASS: CC_Download_Excel_Button
//=======================================================================

/** 
 * This CC_Button subclass represents a button that downloads an Excel spreadsheet using data passed in the query. It calls the CC_Download_Excel_Handler which constructs an Excel file out of the database data represented by the given query.
 *
 * @package CC_Buttons
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 * @see CC_Download_Excel_Handler()
 */

class CC_Download_Excel_Button extends CC_Button
{	
	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Download_Excel_Button
	//-------------------------------------------------------------------

	/**
	 * The constructor accepts an optional value for the button's label.
	 *
	 * @access public
	 * @param string $label The button's label text. It defaults to 'Logout'.
	 */

	function CC_Download_Excel_Button($downloadQuery, $downloadFileName, $pluralDisplayName, $downloadText = 'Download')
	{
		$this->CC_Button($downloadText);
				
		$excelHandler = new CC_Download_Excel_Handler($downloadQuery, $downloadFileName, $pluralDisplayName);
		$this->registerHandler($excelHandler);	
		
		$this->setValidateOnClick(false);
		$this->setFieldUpdater(false);
	}	
}

?>