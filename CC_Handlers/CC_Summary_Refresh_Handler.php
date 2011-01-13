<?php
// $Id: CC_Summary_Refresh_Handler.php,v 1.2 2004/03/15 22:02:12 patrick Exp $
//=======================================================================
// CLASS: CC_Summary_Refresh_Handler
//=======================================================================

/**
 * This CC_Action_Handler handles actions of the refresh button in CC_Summary.
 * 
 * @package CC_Action_Handlers
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 * @see CC_Summary
 */

class CC_Summary_Refresh_Handler extends CC_Action_Handler
{						 
	/**
	 * The name of the CC_Summary the CC_SelectList_Field is part of.
	 *
	 * @access private
	 * @var string $summaryName
	 */
	 			 
	var $summaryName;

	
	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Summary_Refresh_Handler
	//-------------------------------------------------------------------

	/**
	 * This method sets the parameters of the handler for use in the process method.
	 *
	 * @access public
	 * @param string $summaryName The name of the summary we are manipulating.
	 */

	function CC_Summary_Refresh_Handler($summaryName)
	{
		$this->summaryName = $summaryName;
		$this->CC_Action_Handler();
	}


	//-------------------------------------------------------------------
	// METHOD: process()
	//-------------------------------------------------------------------

	/**
	 * This method sets the summary to selected page.
	 *
	 * @access public
	 */

	function process()
	{
		$application = &$_SESSION['application'];
		 
		$window = &$application->getCurrentWindow();
		$summaryObject = &$window->getSummary($this->summaryName);
		$summaryObject->update(true);
	}
}

?>