<?php
// $Id: CC_Logout_Handler.php,v 1.5 2003/07/07 05:13:51 jamie Exp $
//=======================================================================

/**
 * This CC_Action_Handler handles logs a user out of the application
 * 
 * @package CC_Action_Handlers
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

class CC_Logout_Handler extends CC_Action_Handler
{			
	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Logout_Handler
	//-------------------------------------------------------------------

	/**
	 * This method sets the parameters of the handler for use in the process method.
	 *
	 * @access public
	 * @param string $action The screen to go to after logout.
	 * @todo The $action parameter is not being used, so what's the deal?
	 */

	function CC_Logout_Handler($action = NULL)
	{
		$this->CC_Action_Handler();
	}


	//-------------------------------------------------------------------
	// METHOD: process()
	//-------------------------------------------------------------------

	/**
	 * This method calls the application's logout() method.
	 *
	 * @access public
	 */

	function process()
	{
		$application = &$_SESSION['application'];
		$application->logout();
	}
}

?>