<?php
// $Id: CC_Phone_Field.php,v 1.8 2003/07/29 18:56:53 patrick Exp $
//=======================================================================
// CLASS: CC_Phone_Field
//=======================================================================

/**
 * The CC_Phone_Field field represents a North American phone number.
 *
 * @package CC_Fields
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

class CC_Phone_Field extends CC_Text_Field
{	
	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Phone_Field
	//-------------------------------------------------------------------

	/** 
	 * The CC_Phone_Field constructor sets its values here, yo. 
	 *
	 * @access public
	 * @param string $name The unique name of the field. Names must be unique so that N2O knows which fields to update when users submit data.
	 * @param string $label A text label to accompany the field to describe which input is either expected or displayed.
	 * @param bool $required Whether or not this field must contain data from the user before proceeding. Not required by default.
	 * @param string $defaultValue The value the field should contain before user's submit input. The field is blank by default.
	 * @param int $size The visible size of the field, in characters.
	 * @param int $maxlength The maximum amount of data we can enter in the field, in characters.
	 */

	function CC_Phone_Field($name, $label = '', $required = false, $defaultValue = '', $size = 32, $maxlength = 128)
	{
		$this->CC_Text_Field($name, $label, $required, $defaultValue, $size, $maxlength);
	}


	//-------------------------------------------------------------------
	// METHOD: validate
	//-------------------------------------------------------------------
	
	/** 
	 * This method checks if the phone number is in a valid format.
	 *
	 * @access public
	 * @return bool Whether or not the field has a valid phone number.
	 */
	 
	function validate()
	{
		$numberCount = 0;
		
		for ($i = 0; $i < strlen($this->value); $i++)
		{
			$currentChar = substr($this->value, $i, 1);
			
			if (is_numeric($currentChar))
			{
				$numberCount++;
			}
		}
		
		if ($numberCount >= 7)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}

?>