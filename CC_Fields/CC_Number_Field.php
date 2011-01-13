<?php
// $Id: CC_Number_Field.php,v 1.6 2004/10/25 21:19:23 patrick Exp $
//=======================================================================
// CLASS: CC_Number_Field
//=======================================================================

/**
 * The CC_Number_Field field represents a number.
 *
 * @package CC_Fields
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

class CC_Number_Field extends CC_Text_Field
{	
	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Number_Field
	//-------------------------------------------------------------------

	/** 
	 * The CC_FloatNumber_Field constructor sets its values here, yo. 
	 *
	 * @access public
	 * @param string $name The unique name of the field. Names must be unique so that N2O knows which fields to update when users submit data.
	 * @param string $label A text label to accompany the field to describe which input is either expected or displayed.
	 * @param bool $required Whether or not this field must contain data from the user before proceeding. Not required by default.
	 * @param float $defaultValue The value the field should contain before user's submit input. The field is blank by default.
	 * @param int $size The visible size of the field, in characters. Default is 6.
	 * @param int $maxlength The maximum amount of data we can enter in the field, in characters. Default is 10.
	 */

	function CC_Number_Field($name, $label, $required = false, $defaultValue = "", $size = 6, $maxlength = 10)
	{
		$this->CC_Text_Field($name, $label, $required, $defaultValue, $size, $maxlength);
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getEditHTML
	//-------------------------------------------------------------------

	/** 
	 * Returns HTML for an 'text' form field. Numbers displayed are to two decimal places.
	 *
	 * @access public
	 * @return string The HTML for the field.
	 */

	function getEditHTML()
	{
		return sprintf("<input type=\"text\" size=\"$this->size\" maxlength=\"$this->maxlength\" name=\"$this->name\" value=\"%.2f\">", $this->value);
	}


	//-------------------------------------------------------------------
	// METHOD: getViewHTML
	//-------------------------------------------------------------------

	/** 
	 * Returns a number to two decimal places. 
	 *
	 * @access public
	 * @return float A number to two decimal points.
	 */

	function getViewHTML()
	{
		return sprintf("%.2f", $this->value);
	}


	//-------------------------------------------------------------------
	// METHOD: validate
	//-------------------------------------------------------------------
	
	/** 
	 * This method checks if the entered value is indeed a number. 
	 *
	 * @access public
	 * @return bool Whether or not the field is a number.
	 */
	 
	function validate()
	{
		return is_numeric($this->getValue());
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