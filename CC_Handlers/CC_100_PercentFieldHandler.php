<?php
// $Id: CC_100_PercentFieldHandler.php,v 1.4 2003/07/07 05:13:51 jamie Exp $
//=======================================================================

/**
 * This CC_Action_Handler sets the value for a CC_Percentage_Field at 100% (ie. as complete).
 * 
 * @package CC_Action_Handlers
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 * @see CC_Percentage_Filter
 * @see CC_Percentage_Field
 */

class CC_100_PercentFieldHandler extends CC_Action_Handler
{
	/**
	 * The field who's complete button this handler acts on.
	 *
	 * @access private
	 * @var CC_Percentage_Field $field
	 */
	 
	var $field;
	
	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_100_PercentFieldHandler
	//-------------------------------------------------------------------

	function CC_100_PercentFieldHandler()
	{
		$this->CC_Action_Handler();
	}


	//-------------------------------------------------------------------
	// METHOD: setField()
	//-------------------------------------------------------------------

	/**
	 * This method sets the percentage field in the constructor of the CC_Percentage_Field.
	 *
	 * @access public
	 * @param CC_Percentage_Field $field The CC_Percentage_Field field. 
	 */

	function setField(&$field)
	{
		$this->field = &$field;
	}



	//-------------------------------------------------------------------
	// METHOD: process()
	//-------------------------------------------------------------------

	/**
	 * This method sets the value to the width of the button (ie. the maximum value). The parent class' value is set to 100.
	 *
	 * @access public
	 */

	function process()
	{
		$this->field->setValue($this->field->percentageButton->width);
	}
}

?>