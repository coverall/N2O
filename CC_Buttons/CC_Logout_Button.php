<?php
// $Id: CC_Logout_Button.php,v 1.6 2003/08/26 08:56:18 patrick Exp $
//=======================================================================
// CLASS: CC_Logout_Button
//=======================================================================

/** 
 * This CC_Button subclass represents a logout button. It calls the CC_Logout_Handler which unsets the session and starts the application afresh.
 *
 * @package CC_Buttons
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 * @see CC_Logout_Handler()
 */

class CC_Logout_Button extends CC_Button
{	
	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Logout_Button
	//-------------------------------------------------------------------

	/**
	 * The constructor accepts an optional value for the button's label.
	 *
	 * @access public
	 * @param string $label The button's label text. It defaults to 'Logout'.
	 */

	function CC_Logout_Button($logoutText = 'Logout')
	{
		$this->CC_Button($logoutText);
				
		$logoutHandler = new CC_Logout_Handler();
		$this->registerHandler($logoutHandler);	
		
		$this->setValidateOnClick(false);
		$this->setFieldUpdater(false);
	}	
}

?>