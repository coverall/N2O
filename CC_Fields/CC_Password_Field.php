<?php
// $Id: CC_Password_Field.php,v 1.24 2004/08/25 21:16:45 patrick Exp $
//=======================================================================
// CLASS: CC_Password_Field
//=======================================================================

/**
 * The CC_Password_Field field represents a password.
 *
 * @package CC_Fields
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

class CC_Password_Field extends CC_Text_Field
{	
	// If set to true, getEditHTML() will include the password in the
	// value when it's called.
	//
	var $showPassword;

	var $minimumLength = 0;		// the minimum length of the password
	var $_requireAlphaNumeric = false;

	
	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Password_Field
	//-------------------------------------------------------------------

	/** 
	 * The CC_Password_Field constructor sets its values here, yo. 
	 *
	 * @access public
	 * @param string $name The unique name of the field. Names must be unique so that N2O knows which fields to update when users submit data.
	 * @param string $label A text label to accompany the field to describe which input is either expected or displayed.
	 * @param bool $required Whether or not this field must contain data from the user before proceeding. Not required by default.
	 * @param float $defaultValue The value the field should contain before user's submit input. The field is blank by default. Default is '0.00'.
	 * @param int $size The visible size of the field, in characters. Default is 6.
	 * @param int $maxlength The maximum amount of data we can enter in the field, in characters. Default is 6.
	 * @param bool $showPassword Whether or not to show the password in an editable view.
	 */

	function CC_Password_Field($name, $label, $required = false, $defaultValue = '', $size = 16, $maxlength = 32, $showPassword = false)
	{
		$this->CC_Text_Field($name, $label, $required, $defaultValue, $size, $maxlength);
		$this->setPassword(true);
		$this->setShowPassword(true);
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
		if ($this->showPassword)
		{
			$value = $this->getValue();
		}
		else
		{
			$value = '';
		}

		return '<input type="password" size="' . $this->size . '" maxlength="' . $this->maxlength . '" name="' . $this->getRecordKey() . $this->name . '" value="' . $value . '" class="' . $this->inputStyle . '" tabindex="' . $this->_tabIndex .'">';
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
		return '<span class="' . $this->inputStyle . '">********</span>';
	}


	//-------------------------------------------------------------------
	// METHOD: validate
	//-------------------------------------------------------------------
	
	/** 
	 * This method checks if the entered value has enough characters. 
	 *
	 * @access public
	 * @return bool Whether or not the passwod is long enough.
	 */
	 
	function validate()
	{
		if (strlen($this->getValue()) >= $this->minimumLength)
		{
			if ($this->_requireAlphaNumeric && (preg_match('/^[A-Za-z]+$/', $this->getValue()) != 0) || (preg_match('/^[0-9]+$/', $this->getValue()) != 0))
			{
				$this->setErrorMessage("Must contain letters and both numbers.", CC_FIELD_ERROR_CUSTOM);
				return false;
			}
			$this->clearErrorMessage();
			return true;
		}
		else
		{
			$this->setErrorMessage("Must be at least $this->minimumLength characters.", CC_FIELD_ERROR_CUSTOM);
			return false;
		}
	}


	//-------------------------------------------------------------------
	// METHOD: setShowPassword
	//-------------------------------------------------------------------
	
	/** 
	 * This sets whether or not to display the password in editable mode (it is masked in the field when displayed).
	 *
	 * @access public
	 * @param bool $showPassword Whether or not to show the password.
	 */
	 
	function setShowPassword($showPassword)
	{
		$this->showPassword = $showPassword;
	}


	//-------------------------------------------------------------------
	// METHOD: setMinimumLength
	//-------------------------------------------------------------------
	
	/** 
	 * This sets the minimum password length.
	 *
	 * @access public
	 * @param int $minimumLength The minimum password length to set.
	 */
	 
	function setMinimumLength($minimumLength)
	{
		$this->minimumLength = $minimumLength;
	}


	//-------------------------------------------------------------------
	// METHOD: setRequireAlphaNumeric
	//-------------------------------------------------------------------
	
	/** 
	 * This sets whether or not the password must be alpha-numeric.
	 *
	 * @access public
	 * @param bool $requireAlphaNumeric
	 */
	 
	function setRequireAlphaNumeric($requireAlphaNumeric)
	{
		$this->_requireAlphaNumeric = $requireAlphaNumeric;
	}


}

?>