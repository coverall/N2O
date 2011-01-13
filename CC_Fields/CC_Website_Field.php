<?php
// $Id: CC_Website_Field.php,v 1.3 2003/09/09 20:21:57 mike Exp $
//=======================================================================
// CLASS: CC_Website_Field
//=======================================================================

/**
 * The CC_Website_Field field represents a website URL.
 *
 * @package CC_Fields
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 * @see CC_PostalZipCode_Field
 * @see CC_PostalCode_Field
 */

class CC_Website_Field extends CC_Text_Field
{	
	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Website_Field
	//-------------------------------------------------------------------

	/** 
	 * The CC_Website_Field constructor sets its values here, yo. 
	 *
	 * @access public
	 * @param string $name The unique name of the field. Names must be unique so that N2O knows which fields to update when users submit data.
	 * @param string $label A text label to accompany the field to describe which input is either expected or displayed.
	 * @param bool $required Whether or not this field must contain data from the user before proceeding. Not required by default.
	 * @param string $defaultValue The value the field should contain before user's submit input. The field is blank by default.
	 */

	function CC_Website_Field($name, $label = '', $required = false, $defaultValue = '')
	{
		$this->CC_Text_Field($name, $label, $required, $defaultValue);
	}


	//-------------------------------------------------------------------
	// METHOD: validate
	//-------------------------------------------------------------------
	
	/** 
	 * This method checks if the URL is in a valid format. 
	 *
	 * @access public
	 * @return bool Whether or not the field has a valid URL. (dubya, dubya, dubya!)
	 */
	 
	function validate()
	{
		// make sure it is a valid domain: domainname.ext
		if (ereg('^[a-zA-Z0-9-]+(\.[a-zA-Z]{2,4})+$', $this->getValue()))
		{
			return true;
		}

		return false;		
	}


	//-------------------------------------------------------------------
	// METHOD: setValue
	//-------------------------------------------------------------------
	
	/** 
	 * This method sets the field's value and removes that blasted "www".
	 *
	 * @access public
	 * @param string The URL to set.
	 */
	 
	function setValue($value)
	{
		// if they have a 'www.' in there, nuke it...
		if (ereg('^www\.', $value))
		{
			parent::setValue(substr($value, 4));
		}
		else
		{
			parent::setValue($value);
		}
	}
}

?>