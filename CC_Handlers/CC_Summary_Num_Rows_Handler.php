<?php
// $Id: CC_Summary_Num_Rows_Handler.php,v 1.12 2005/06/03 02:47:26 patrick Exp $
//=======================================================================

/**
 * This CC_Action_Handler handles actions on the CC_SelectList_Field in CC_Summary that lets you choose the number of rows per page.
 * 
 * @package CC_Action_Handlers
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 * @see CC_Summary
 */

class CC_Summary_Num_Rows_Handler extends CC_Action_Handler
{			
	/**
	 * The name of the CC_Summary the CC_SelectList_Field is part of.
	 *
	 * @access private
	 * @var string $summaryName
	 */
	 			 
	var $summaryName;

	
	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Summary_Num_Rows_Handler
	//-------------------------------------------------------------------

	/**
	 * This method sets the parameters of the handler for use in the process method.
	 *
	 * @access public
	 * @param string $summaryName The name of the summary we are manipulating.
	 */

	function CC_Summary_Num_Rows_Handler($summaryName)
	{
		$this->summaryName = $summaryName;
		$this->CC_Action_Handler();
	}


	//-------------------------------------------------------------------
	// METHOD: process()
	//-------------------------------------------------------------------

	/**
	 * This method resets the summary to the first page and sets a cookie so that a preference is set for the current number of rows selection for the particular summary.
	 *
	 * @access public
	 */

	function process()
	{
		global $application;
		 
		$window = &$application->getCurrentWindow();
		$summary = &$window->getSummary($this->summaryName);
		
		setcookie(session_name() . '_' . $summary->name . '_NUMROWS' , $summary->numberRowsPerPageList->getValue(), time() + 31536000);
		
		$summary->setPageNumber(1);
		$summary->update(true);
	}
}

?>