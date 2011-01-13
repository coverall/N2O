<?php
// $Id: CC_IntegerNumber_Field.php,v 1.10 2004/10/25 21:19:23 patrick Exp $
//=======================================================================
// CLASS: CC_IntegerNumber_Field
//=======================================================================

/**
 * The CC_IntegerNumber_Field field represents an integer number.
 *
 * @package CC_Fields
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

class CC_IntegerNumber_Field extends CC_Text_Field
{	
	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_IntegerNumber_Field
	//-------------------------------------------------------------------

	/** 
	 * The CC_IntegerNumber_Field constructor sets its values here, yo. 
	 *
	 * @access public
	 * @param string $name The unique name of the field. Names must be unique so that N2O knows which fields to update when users submit data.
	 * @param string $label A text label to accompany the field to describe which input is either expected or displayed.
	 * @param bool $required Whether or not this field must contain data from the user before proceeding. Not required by default.
	 * @param float $defaultValue The value the field should contain before user's submit input. The field is blank by default.
	 * @param int $size The visible size of the field, in characters.
	 * @param int $maxlength The maximum amount of data we can enter in the field, in characters.
	 */

	function CC_IntegerNumber_Field($name, $label, $required = false, $defaultValue = "", $size = 6, $maxlength = 6)
	{
		$this->CC_Text_Field($name, $label, $required, $defaultValue, $size, $maxlength);
	}

	//-------------------------------------------------------------------
	// METHOD: validate
	//-------------------------------------------------------------------
	
	/** 
	 * This method checks if the entered value is an integer. 
	 *
	 * @access public
	 * @return bool Whether or not the field is an integer.
	 */
	 
	function validate()
	{
		if (is_numeric($this->getValue()))
		{
			if (intval($this->getValue()) == $this->getValue())
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}


	//-------------------------------------------------------------------
	// METHOD: setValue
	//-------------------------------------------------------------------
	
	function setValue($value)
	{
		parent::setValue(str_replace(',', '', $value));
	}

}

?>