<?php
// $Id: CC_Button_Click_Handler.php,v 1.1 2004/02/11 18:57:27 patrick Exp $
//=======================================================================

/**
 * This CC_Action_Handler clicks a button that is passed in. This is useful if you want to trigger the handlers on another button.
 * 
 * @package CC_Action_Handlers
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

class CC_Button_Click_Handler extends CC_Action_Handler
{		
	/**
	 * The button to click.
	 *
	 * @access private
	 * @var string $cancelAction
	 */
	
	var $button;
	
			 
	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Button_Click_Handler
	//-------------------------------------------------------------------

	/**
	 * This method sets the parameters of the handler for use in the process method.
	 *
	 * @access public
	 * @param string $button The button to be clicked.
	 */

	function CC_Button_Click_Handler(&$button)
	{
		$this->button = &$button;
	}


	//-------------------------------------------------------------------
	// METHOD: process()
	//-------------------------------------------------------------------

	/**
	 * This method clicks the button.
	 *
	 * @access public
	 */

	function process()
	{
		$this->button->click();
	}
}

?>