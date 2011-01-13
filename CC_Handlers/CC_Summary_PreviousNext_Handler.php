<?php
// $Id: CC_Summary_PreviousNext_Handler.php,v 1.2 2005/09/14 23:53:22 patrick Exp $
//=======================================================================
// CLASS: CC_Summary_PreviousNext_Handler
//=======================================================================

/**
 * This CC_Action_Handler handles the CC_Summary's 'Next' button, which goes to the next page of record listings.
 * 
 * @package CC_Action_Handlers
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 * @see CC_Summary
 */

class CC_Summary_PreviousNext_Handler extends CC_Action_Handler
{						 
	/**
	 * The name of the summary object we are working.
	 *
	 * @access private
	 * @var string $summaryName
	 */

	var $summaryName;
	var $offset;
	

	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Summary_PreviousNext_Handler
	//-------------------------------------------------------------------

	/**
	 * This method sets the parameters of the handler for use in the process method.
	 *
	 * @access public
	 * @param string $summaryName The name of the summary we are manipulating.
	 */

	function CC_Summary_PreviousNext_Handler($summaryName, $offset = 1)
	{
		$this->summaryName = $summaryName;
		$this->offset = $offset;
		$this->CC_Action_Handler();
	}


	//-------------------------------------------------------------------
	// METHOD: process()
	//-------------------------------------------------------------------

	/**
	 * This method sets the summary page to the next page.
	 *
	 * @access public
	 */

	function process()
	{
		global $application;
		 
		$window = &$application->getCurrentWindow();
		$summary = &$window->getSummary($this->summaryName);
		
		$summary->setPageNumber($summary->getPageNumber() + $this->offset);
		$summary->update(true);
	}
}

?>