<?php
// $Id: CC_Unregister_Window_Handler.php,v 1.1 2005/05/26 20:04:03 patrick Exp $
//=======================================================================

/**
 * This CC_Action_Handler unregisters a given window.
 * 
 * @package CC_Action_Handlers
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

class CC_Unregister_Window_Handler extends CC_Action_Handler
{
	/**
	 * The name of the window to unregister.
	 *
	 * @access private
	 * @var string $windowToUnregister
	 */

	var $windowToUnregister;

	
	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Unregister_Window_Handler
	//-------------------------------------------------------------------

	/**
	 * This method sets the parameters of the handler for use in the process method.
	 *
	 * @access public
	 * @param string $windowName The window to unregister. Defaults to the current window.
	 */

	function CC_Unregister_Window_Handler($windowName = NULL)
	{
		$application = &$_SESSION['application'];
		
		if ($windowName == NULL)
		{
			$this->windowToUnregister = $application->getAction();
		}
		else
		{
			$this->windowToUnregister = $windowName;
		}
		
		$this->CC_Action_Handler();
	}


	//-------------------------------------------------------------------
	// METHOD: process()
	//-------------------------------------------------------------------

	/**
	 * This method unregisters the given window.
	 *
	 * @access public
	 */

	function process()
	{
		$application = &$_SESSION['application'];	
		$application->unregisterWindow($this->windowToUnregister);
	}
}

?>