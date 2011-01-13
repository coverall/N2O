<?php
// $Id: CC_Encoded_Field.php,v 1.6 2003/07/16 08:09:40 jamie Exp $
//=======================================================================
// CLASS: CC_Encoded_Field
//=======================================================================

/**
 * The CC_Encoded_Field field represents an encoded field.
 *
 * @package CC_Fields
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

class CC_Encoded_Field extends CC_Text_Field
{	
	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Encoded_Field
	//-------------------------------------------------------------------

	/** 
	 * The CC_Encoded_Field constructor sets its values here, yo. 
	 *
	 * @access public
	 * @param string $name The unique name of the field. Names must be unique so that N2O knows which fields to update when users submit data.
	 * @param string $label A text label to accompany the field to describe which input is either expected or displayed.
	 * @param bool $required Whether or not this field must contain data from the user before proceeding. Not required by default.
	 * @param float $defaultValue The value the field should contain before user's submit input. The field is blank by default. Default is '0.00'.
	 * @param int $size The visible size of the field, in characters. Default is 6.
	 * @param int $maxlength The maximum amount of data we can enter in the field, in characters. Default is 6.
	 */

	function CC_Encoded_Field($name, $label, $required = false, $defaultValue = "", $size = 12, $maxlength = 64)
	{
		$this->CC_Text_Field($name, $label, $required, $defaultValue, $size, $maxlength);
		$this->setEncode(true);
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getEditHTML
	//-------------------------------------------------------------------

	/** 
	 * Returns HTML for a 'password' form field.
	 *
	 * @access public
	 * @return string The HTML for the field.
	 */

	function getEditHTML()
	{
		return "<input type=\"password\" size=\"$this->size\" maxlength=\"$this->maxlength\" name=\"" . $this->getRecordKey() . "$this->name\" value=\"\">";
	}


	//-------------------------------------------------------------------
	// METHOD: getViewHTML
	//-------------------------------------------------------------------

	/** 
	 * Returns '********'. 
	 *
	 * @access public
	 * @return string '********'.
	 */

	function getViewHTML()
	{
		return "********";
	}
}

?>