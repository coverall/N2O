<?php
// $Id: CC_PostalCode_Field.php,v 1.7 2009/09/10 02:10:20 patrick Exp $
//=======================================================================
// CLASS: CC_PostalCode_Field
//=======================================================================

/**
 * The CC_PostalCode_Field field represents a Canadian postal code.
 *
 * @package CC_Fields
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 * @see CC_PostalZipCode_Field
 * @see CC_ZipCode_Field
 */

class CC_PostalCode_Field extends CC_Text_Field
{	

	/**
     * The country field to associate for validation.
     *
     * @var CC_Country_Field or CC_ISO_Country_Field $countryField
     * @access private
     */	
     
	var $countryField;
	
		
	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_PostalCode_Field
	//-------------------------------------------------------------------

	/** 
	 * The CC_PostalCode_Field constructor sets its values here, yo. 
	 *
	 * @access public
	 * @param string $name The unique name of the field. Names must be unique so that N2O knows which fields to update when users submit data.
	 * @param string $label A text label to accompany the field to describe which input is either expected or displayed.
	 * @param bool $required Whether or not this field must contain data from the user before proceeding. Not required by default.
	 * @param string $defaultValue The value the field should contain before user's submit input. The field is blank by default.
	 */

	function CC_PostalCode_Field($name, $label = '', $required = false, $defaultValue = '')
	{
		$this->CC_Text_Field($name, $label, $required, $defaultValue, 12, 7);
	}


	//-------------------------------------------------------------------
	// METHOD: validate
	//-------------------------------------------------------------------
	
	/** 
	 * This method checks if the postal code is in a valid format. 
	 *
	 * @access public
	 * @return bool Whether or not the field has a valid postal code.
	 */
	 
	function validate()
	{
		$CAValue = ($this->countryField instanceof CC_ISO_Country_Field) ? 'CA' : 'Canada'; 
		
		if (!isset($this->countryField) || ($this->countryField->getValue() == $CAValue))
		{
			return ereg('^[A-Za-z]{1}[0-9]{1}[A-Za-z]{1}[ ]*[0-9]{1}[A-Za-z]{1}[0-9]{1}$', $this->getValue());
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
	 * @param mixed The postal or zip code to set.
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