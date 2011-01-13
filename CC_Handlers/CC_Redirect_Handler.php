<?php
// $Id: CC_Redirect_Handler.php,v 1.9 2003/07/07 05:13:51 jamie Exp $
//=======================================================================

/**
 * This CC_Action_Handler handles browser redirection to a given screen.
 * 
 * @package CC_Action_Handlers
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

class CC_Redirect_Handler extends CC_Action_Handler
{
	/**
	 * The screen to go to.
	 *
	 * @access private
	 * @var string $redirectAction
	 */
			
	var $redirectAction;


	/**
	 * Whether or not to unregister the old window.
	 *
	 * @access private
	 * @var bool $clearWindow
	 */
	 	
	var $clearWindow;


	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Redirect_Handler
	//-------------------------------------------------------------------

	/**
	 * This method sets the parameters of the handler for use in the process method.
	 *
	 * @access public
	 * @param string $action The screen we would like to redirect to.
	 * @param bool $clearOldWindow Whether or not to unregister the old window.
	 */

	function CC_Redirect_Handler($action, $clearOldWindow = false)
	{
		$this->redirectAction = $action;
		
		$this->clearWindow = $clearOldWindow;

		$this->CC_Action_Handler();
		$this->setClearOldWindow($clearOldWindow);
	}


	//-------------------------------------------------------------------
	// METHOD: setClearOldWindow()
	//-------------------------------------------------------------------

	/**
	 * This method adds errors of any type to CC_Error_Manager's _errors
	 * array. 
	 *
	 * @access public
	 * @param int $code the error code which should be defined in CC_Error or the CC_Application subclass.
	 * @param string $userMessage a simple error message for the user 
	 * @param string $verboseMessage a more verbose error message for debugging 
	 * @return int the index of the error that was added to the user errors array
	 */

	function setClearOldWindow($clear)
	{
		$this->clearWindow = $clear;
	}


	//-------------------------------------------------------------------
	// METHOD: process()
	//-------------------------------------------------------------------

	/**
	 * This method sets the action to the new screen and unregisters the old window, if necessary.
	 *
	 * @access public
	 */

	function process()
	{
		$application = &$_SESSION['application'];
		
		if ($this->clearWindow)
		{
			$application->unregisterWindow($this->redirectAction);
		}
		
		$application->setAction($this->redirectAction);
	}
}

?>