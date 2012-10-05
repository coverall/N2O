<?php
// $Id: CC_Dollar_Field.php,v 1.7 2003/12/16 19:21:41 patrick Exp $
//=======================================================================
// CLASS: CC_Dollar_Field
//=======================================================================

/**
 * The CC_Dollar_Field field represents a dollar value.
 *
 * @package CC_Fields
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

class CC_Dollar_Field extends CC_FloatNumber_Field
{
	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Dollar_Field
	//-------------------------------------------------------------------

	/**
	 * The CC_FloatNumber_Field constructor sets its values here, yo.
	 *
	 * @access public
	 * @param string $name The unique name of the field. Names must be unique so that N2O knows which fields to update when users submit data.
	 * @param string $label A text label to accompany the field to describe which input is either expected or displayed.
	 * @param bool $required Whether or not this field must contain data from the user before proceeding. Not required by default.
	 * @param float $defaultValue The value the field should contain before user's submit input. The field is blank by default.
	 * @param int $size The visible size of the field, in characters.
	 * @param int $maxlength The maximum amount of data we can enter in the field, in characters.
	 */

	function CC_Dollar_Field($name, $label, $required = false, $defaultValue = 0.00, $size = 6, $maxlength = 8)
	{
		$this->CC_FloatNumber_Field($name, $label, $required, $defaultValue, $size, $maxlength);
	}


	//-------------------------------------------------------------------
	// METHOD: getViewHTML
	//-------------------------------------------------------------------

	/**
	 * Returns a dollar string to two decimal places complete with dollar sign.
	 *
	 * @access public
	 * @return float A float to two decimal points.
	 */

	function getViewHTML()
	{
		return sprintf("\$%.2f", $this->value);
	}


	//-------------------------------------------------------------------
	// METHOD: getEditHTML
	//-------------------------------------------------------------------

	/**
	 * Returns HTML for an 'text' form field. Numbers displayed are to two decimal placesand are prefixed by a dollar sign.
	 *
	 * @access public
	 * @return string The HTML for the field.
	 */

	function getEditHTML()
	{
		return '$' . sprintf('<input type="text" id="' . $this->id . '"  size="' . $this->size. '" maxlength="' . $this->maxlength . '" name="' . $this->getRecordKey() . $this->name . '" value="%.2f"' . ' class="' . $this->inputStyle . '"' . ($this->disabled ? ' disabled="true"' : '') . ' tabindex="' . $this->_tabIndex .'">', $this->value);
	}


	//-------------------------------------------------------------------
	// METHOD: setValue
	//-------------------------------------------------------------------

	/**
     * Sets the field's value.
     *
     * @access public
     * @param mixed $fieldValue The value to set the field to.
     * @see getValue()
     */

	function setValue($fieldValue = 0.00)
	{
		parent::setValue(round($fieldValue, 2));
	}
}

?>