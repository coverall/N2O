<?php
// $Id: CC_Dependant_Checkbox_Field.php,v 1.4 2004/12/21 20:14:18 patrick Exp $
//=======================================================================
// CLASS: CC_Dependant_Checkbox_Field
//=======================================================================

/**
 * The CC_Dependant_Checkbox_Field field represents a checkbox where a user can choose to select or de-select a specific value which makes an associated field (or fields) required. This is useful in questionnares and the like.
 *
 * @package CC_Fields
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */


class CC_Dependant_Checkbox_Field extends CC_Checkbox_Field
{		
	/**
     * A two dimensional array who's first value is the associated field and the second is the value of the CC_Checkbox_Field that makes the associated field required.
     *
     * @var array $_associatedFields
     * @access private
     */
     
	var $_associatedFields = array();


	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Dependant_Checkbox_Field
	//-------------------------------------------------------------------

	/** 
	 * The CC_Dependant_Checkbox_Field constructor sets its values here, yo. 
	 *
	 * @access public
	 * @param string $name The unique name of the field. Names must be unique so that N2O knows which fields to update when users submit data.
	 * @param string $label A text label to accompany the field to describe which input is either expected or displayed.
	 * @param bool $required Whether or not this field must contain data from the user before proceeding. Not required by default.
	 * @param string $defaultValue The value the field should contain before user's submit input. The field is blank by default.
	 * @param int $linkableLabel Whether or not the field's label should be used to make the checkbox selection as well as the checkbox itself.
	 */

	function CC_Dependant_Checkbox_Field($name, $label = '', $required = false, $defaultValue = false, $linkableLabel = true)
	{
		$this->CC_Checkbox_Field($name, $label, $required, $defaultValue, $linkableLabel);
	}
	

	//-------------------------------------------------------------------
	// METHOD: setAssociatedRequiredField
	//-------------------------------------------------------------------

	/** 
	 * This sets an associated field and the value of this field that makes it required.
	 *
	 * @access public
	 * @return int The number of selections to choose from.
	 */
	
	function setAssociatedField(&$associatedField, $requiredValue = true)
	{
		$associatedElement = array();
		$associatedElement[0] = &$associatedField;
		$associatedElement[1] = $requiredValue;
		
		$this->_associatedFields[] = &$associatedElement;
	}


	//-------------------------------------------------------------------
	// METHOD: validate
	//-------------------------------------------------------------------
	
	/**
	 * This method checks if the associated fields have values when they should. 
	 *
	 * @access public
	 * @return bool Whether or not the fields are filled in accordingly according to the associatedFields array.
	 */
	 
	function validate()
	{
		$valid = true;
		
		for ($i = 0; $i < sizeof($this->_associatedFields); $i++)
		{
			$currentAssociatedField = &$this->_associatedFields[$i][0];
			$currentRequiredValue = $this->_associatedFields[$i][1];
			
			if ($currentRequiredValue == $this->getValue())
			{
				$currentAssociatedField->setErrorMessage('Please include a value.', CC_FIELD_ERROR_MISSING);
				
				if (!$currentAssociatedField->hasValue())
				{
					$valid = false;
					$this->setErrorMessage('Some additional fields are required.', CC_FIELD_ERROR_CUSTOM);
					$currentAssociatedField->setError(true);
				}
				else
				{
					$currentAssociatedField->clearErrorMessage(CC_FIELD_ERROR_MISSING);
				}
			}
			else
			{
				$currentAssociatedField->clearErrorMessage(CC_FIELD_ERROR_MISSING);
			}
			
			unset($currentAssociatedField);
			unset($currentRequiredValue);
		}
		
		if ($valid)
		{
			$this->clearAllErrors();
		}
		
		return $valid;
	}
}

?>