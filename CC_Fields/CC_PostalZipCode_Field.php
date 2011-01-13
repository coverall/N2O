<?php
// $Id: CC_PostalZipCode_Field.php,v 1.5 2003/07/14 08:48:02 jamie Exp $
//=======================================================================
// CLASS: CC_PostalZipCode_Field
//=======================================================================

/**
 * The CC_PostalZipCode_Field field represents a North American postal or zip code.
 *
 * @package CC_Fields
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 * @see CC_PostalCode_Field
 */

class CC_PostalZipCode_Field extends CC_Text_Field
{	
	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_PostalZipCode_Field
	//-------------------------------------------------------------------

	/** 
	 * The CC_PostalZipCode_Field constructor sets its values here, yo. 
	 *
	 * @access public
	 * @param string $name The unique name of the field. Names must be unique so that N2O knows which fields to update when users submit data.
	 * @param string $label A text label to accompany the field to describe which input is either expected or displayed.
	 * @param bool $required Whether or not this field must contain data from the user before proceeding. Not required by default.
	 * @param string $defaultValue The value the field should contain before user's submit input. The field is blank by default.
	 */

	function CC_PostalZipCode_Field($name, $label = '', $required = false, $defaultValue = '')
	{
		$this->CC_Text_Field($name, $label, $required, $defaultValue, 12, 12);
	}


	//-------------------------------------------------------------------
	// METHOD: validate
	//-------------------------------------------------------------------
	
	/** 
	 * This method checks if the postal or zip code is in a valid format. 
	 *
	 * @access public
	 * @return bool Whether or not the field has a valid postal or zip code.
	 */
	 
	function validate()
	{
		// Postal Code
		if (ereg('^[A-Za-z]{1}[0-9]{1}[A-Za-z]{1}[ ]*[0-9]{1}[A-Za-z]{1}[0-9]{1}$', $this->getValue()))
		{
			return true;
		}
		// Zip Code
		else if (ereg('^[0-9]{5}[-]*[0-9]*$', $this->getValue()))
		{
			return true;
		}
		else
		{
			return false;
		}
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
}

?>