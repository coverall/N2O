<?php
// $Id: CC_Back_Button_Handler.php,v 1.5 2003/07/07 05:13:51 jamie Exp $
//=======================================================================

/**
 * This CC_Action_Handler handles actions that take a user back one screen.
 * 
 * @package CC_Action_Handlers
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 * @todo I think this was replaced by CC_Cancel_Handler. Anyone using this, yo? Maybe we can delete it.
 */

class CC_Back_Button_Handler extends CC_Action_Handler
{
	/**
	 * The screen to go back to.
	 *
	 * @access private
	 * @var string $backAction
	 */

	var $backAction;
			 
	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Back_Button_Handler
	//-------------------------------------------------------------------

	/**
	 * This method sets the parameters of the handler for use in the process method.
	 *
	 * @access public
	 * @param string $action The screen to go back to.
	 */

	function CC_Back_Button_Handler($action = NULL)
	{
		$application = &$_SESSION['application'];
		
		if ($action == NULL)
		{
			$this->backAction = $application->getLastAction();
		}
		else
		{
			$this->backAction = $action;
		}
		
		$this->CC_Action_Handler();
	}


	//-------------------------------------------------------------------
	// METHOD: process()
	//-------------------------------------------------------------------

	/**
	 * This method sets the action to go back to.
	 *
	 * @access public
	 */

	function process()
	{
		$application = &$_SESSION['application'];
		
		$application->setAction($this->backAction);
	}
}

?>