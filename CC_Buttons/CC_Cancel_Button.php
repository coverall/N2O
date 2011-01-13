<?php
// $Id: CC_Cancel_Button.php,v 1.5 2003/08/26 08:55:53 patrick Exp $
//=======================================================================
// CLASS: CC_Cancel_Button
//=======================================================================

/** 
 * This CC_Button subclass represents a cancel button. It calls the CC_ancel_Button_Handler which takes you back to the previous screen without validating or updating fields.
 *
 * @package CC_Buttons
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 * @see CC_Cancel_Button_Handler()
 */

class CC_Cancel_Button extends CC_Button
{	
	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Cancel_Button
	//-------------------------------------------------------------------

	/**
	 * The constructor accepts an optional value for the button's label.
	 *
	 * @access public
	 * @param string $label The button's label text. It defaults to 'Cancel'.
	 */

	function CC_Cancel_Button($label = 'Cancel')
	{
		$this->CC_Button($label);
		
		$cancelHandler = new CC_Cancel_Button_Handler();
		$this->registerHandler($cancelHandler);
		
		$this->setValidateOnClick(false);
		$this->setFieldUpdater(false);
	}	
}

?>