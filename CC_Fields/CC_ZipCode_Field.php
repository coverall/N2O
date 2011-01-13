<?php
// $Id: CC_ZipCode_Field.php,v 1.6 2005/07/07 21:59:19 jamie Exp $
//=======================================================================
// CLASS: CC_ZipCode_Field
//=======================================================================

/**
 * The CC_ZipCode_Field field represents a US zip code.
 *
 * @package CC_Fields
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 * @see CC_PostalZipCode_Field
 * @see CC_PostalCode_Field
 */

class CC_ZipCode_Field extends CC_Text_Field
{	
	/**
     * The country field to associate for validation.
     *
     * @var CC_Country_Field or CC_ISO_Country_Field $countryField
     * @access private
     */	
     
	var $countryField;
	
	
	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_ZipCode_Field
	//-------------------------------------------------------------------

	/** 
	 * The CC_ZipCode_Field constructor sets its values here, yo. 
	 *
	 * @access public
	 * @param string $name The unique name of the field. Names must be unique so that N2O knows which fields to update when users submit data.
	 * @param string $label A text label to accompany the field to describe which input is either expected or displayed.
	 * @param bool $required Whether or not this field must contain data from the user before proceeding. Not required by default.
	 * @param string $defaultValue The value the field should contain before user's submit input. The field is blank by default.
	 */

	function CC_ZipCode_Field($name, $label = '', $required = false, $defaultValue = '')
	{
		$this->CC_Text_Field($name, $label, $required, $defaultValue, 12, 10);
	}


	//-------------------------------------------------------------------
	// METHOD: validate
	//-------------------------------------------------------------------
	
	/** 
	 * This method checks if the zip code is in a valid format. 
	 *
	 * @access public
	 * @return bool Whether or not the field has a valid zip code.
	 */
	 
	function validate()
	{
		$USValue = is_a($this->countryField, 'CC_ISO_Country_Field') ? 'US' : 'United States'; 
		
		if (!isset($this->countryField) || ($this->countryField->getValue() == $USValue))
		{
			return ereg('^[0-9]{5}[-]*[0-9]*$', $this->getValue());
		}
		
		return true;
	}


	//-------------------------------------------------------------------
	// METHOD: setValue
	//-------------------------------------------------------------------
	
	/** 
	 * This method sets the field's value and sets the letters to uppercase.
	 *
	 * @access public
	 * @param mixed The zip code to set.
	 */
	 
	function setValue($value)
	{
		parent::setValue(strtoupper($value));
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setCountryField
	//-------------------------------------------------------------------
	
	/** 
	 * Set the country field to associate with this field, for validation.
	 *
	 * @access public
	 * @param CC_ISO_Country_Field or CC_Country_Field The country field to set.
	 */
	 
	function setCountryField(&$countryField)
	{
		$this->countryField = &$countryField;
	}
}

?>