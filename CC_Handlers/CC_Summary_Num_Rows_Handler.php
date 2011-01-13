<?php
// $Id: CC_Summary_Num_Rows_Handler.php,v 1.11 2004/03/15 22:02:12 patrick Exp $
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
		$application = &$_SESSION['application'];
		 
		$window = &$application->getCurrentWindow();
		$summaryObject = &$window->getSummary($this->summaryName);
		
		setcookie(session_name() . '_' . $summaryObject->name . '_NUMROWS' , $summaryObject->numberRowsPerPageList->getValue(), time() + 31536000);
		
		$summaryObject->setPageNumber(1);
		$summaryObject->update(true);
	}
}

?>