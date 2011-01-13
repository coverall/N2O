<?php
// $Id: CC_Cancel_Button_Handler.php,v 1.5 2003/07/07 05:13:51 jamie Exp $
//=======================================================================

/**
 * This CC_Action_Handler handles actions on a CC_Cancel_Button that take a user back one screen.
 * 
 * @package CC_Action_Handlers
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

class CC_Cancel_Button_Handler extends CC_Action_Handler
{		
	/**
	 * The screen to go back to.
	 *
	 * @access private
	 * @var string $cancelAction
	 */
	
	var $cancelAction;
			 
	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Cancel_Button_Handler
	//-------------------------------------------------------------------

	/**
	 * This method sets the parameters of the handler for use in the process method.
	 *
	 * @access public
	 * @param string $action The screen to go back to.
	 */

	function CC_Cancel_Button_Handler($action = NULL)
	{
		$application = &$_SESSION['application'];
		
		if ($action == NULL)
		{
			$this->cancelAction = $application->getLastAction();
		}
		else
		{
			$this->cancelAction = $action;
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
				
		$application->setAction($this->cancelAction);
	}
}

?>