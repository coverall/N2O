<?php
// $Id: CC_FloatNumber_Field.php,v 1.8 2003/09/28 23:48:15 patrick Exp $
//=======================================================================
// CLASS: CC_FloatNumber_Field
//=======================================================================

/**
 * The CC_FloatNumber_Field field represents a float number.
 *
 * @package CC_Fields
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

class CC_FloatNumber_Field extends CC_Text_Field
{	
	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_FloatNumber_Field
	//-------------------------------------------------------------------

	/** 
	 * The CC_FloatNumber_Field constructor sets its values here, yo. 
	 *
	 * @access public
	 * @param string $name The unique name of the field. Names must be unique so that N2O knows which fields to update when users submit data.
	 * @param string $label A text label to accompany the field to describe which input is either expected or displayed.
	 * @param bool $required Whether or not this field must contain data from the user before proceeding. Not required by default.
	 * @param float $defaultValue The value the field should contain before user's submit input. The field is blank by default. Default is '0.00'.
	 * @param int $size The visible size of the field, in characters. Default is 6.
	 * @param int $maxlength The maximum amount of data we can enter in the field, in characters. Default is 6.
	 */

	function CC_FloatNumber_Field($name, $label, $required = false, $defaultValue = 0.00, $size = 6, $maxlength = 6)
	{
		$this->CC_Text_Field($name, $label, $required, $defaultValue, $size, $maxlength);
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getViewHTML
	//-------------------------------------------------------------------

	/** 
	 * Returns a float to two decimal places. 
	 *
	 * @access public
	 * @return string A float to two decimal points.
	 */

	function getViewHTML()
	{
		return sprintf("%.2f", $this->value);
	}
	

	//-------------------------------------------------------------------
	// METHOD: getValue
	//-------------------------------------------------------------------

	/** 
	 * Returns the value of the field, casted to a float.
	 *
	 * @access public
	 * @return float Returns the value of the field, casted to a float.
	 */

	function getValue()
	{
		return (float)parent::getValue();
	}
	

	//-------------------------------------------------------------------
	// METHOD: getEditHTML
	//-------------------------------------------------------------------

	/** 
	 * Returns HTML for a 'text' form field. Numbers displayed are to two decimal places.
	 *
	 * @access public
	 * @return string The HTML for the field.
	 */

	function getEditHTML()
	{
		return sprintf("<input type=\"text\" size=\"$this->size\" maxlength=\"$this->maxlength\" name=\"" . $this->getRecordKey() . "$this->name\" value=\"%.2f\">", $this->value);
	}


	//-------------------------------------------------------------------
	// METHOD: validate
	//-------------------------------------------------------------------
	
	/** 
	 * This method checks if the entered value is a number. 
	 *
	 * @access public
	 * @return bool Whether or not the field is a number.
	 */
	 
	function validate()
	{
		return is_numeric($this->value);
	}

}

?>