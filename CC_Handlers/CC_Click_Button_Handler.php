<?php
// $Id: CC_Click_Button_Handler.php,v 1.1 2003/11/19 22:15:22 patrick Exp $
//=======================================================================
// CLASS: CC_Click_Button_Handler
//=======================================================================

/**
 * This CC_Action_Handler handles is constructed with a button, and when processed, calls click() on the button.
 * 
 * @package CC_Action_Handlers
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

class CC_Click_Button_Handler extends CC_Action_Handler
{
	/**
	 * The window we are currently on.
	 *
	 * @access private
	 * @var CC_Button $_button
	 */

	var $_button;

	
	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Click_Button_Handler
	//-------------------------------------------------------------------

	/**
	 * This method sets the parameters of the handler for use in the process method.
	 *
	 * @access public
	 * @param CC_Window $window The window we are currently processing.
	 */

	function CC_Click_Button_Handler(&$button)
	{	
		$this->_button = &$button;
		
		$this->CC_Action_Handler();
	}


	//-------------------------------------------------------------------
	// METHOD: process()
	//-------------------------------------------------------------------

	function process()
	{
		$this->_button->click();
	}
}

?>