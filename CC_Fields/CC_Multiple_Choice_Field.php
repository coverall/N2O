<?php

//=======================================================================
// CLASS: CC_Multiple_Choice_Field
//=======================================================================

/**
 * The CC_Multiple_Choice_Field field represents fields that offer multiple selections (ie. CC_SelectList_Field and CC_RadioButton_Field) and implements some special features for these fields (eg. associated required fields).
 *
 * @package CC_Fields
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 * @todo This field requires that you list all associated fields *before* the parent field so that the associated fields are updated before the validate() method is called. This should be removed as a requirement.
 */

class CC_Multiple_Choice_Field extends CC_Field
{
	/**
     * An array of select list options.
     *
     * @var array $options
     * @access private
     */
     
	var $options;
	
	
	/**
     * A two dimensional array who's first value is the associated field and the second is the value of the CC_Multiple_Choice_Field that makes the associated field required.
     *
     * @var array $_associatedFields
     * @access private
     */
     
	var $_associatedFields = array();
	    
    
    //-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Multiple_Choice_Field
	//-------------------------------------------------------------------

	/** 
	 * The CC_Multiple_Choice_Field constructor sets its values here, yo. 
	 *
	 * @access public
	 * @param string $name The unique name of the field. Names must be unique so that N2O knows which fields to update when users submit data.
	 * @param string $label A text label to accompany the field to describe which input is either expected or displayed.
	 * @param bool $required Whether or not this field must contain data from the user before proceeding. Not required by default.
	 * @param string $defaultValue The value the field should contain before user's submit input. The field is blank by default.
	 */

	function CC_Multiple_Choice_Field($name, $label, $required = false, $defaultValue = '')
	{
		$this->CC_Field($name, $label, $required, $defaultValue);
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setAssociatedRequiredField
	//-------------------------------------------------------------------

	/** 
	 * This sets an associated field and the value of this field that makes it required.
	 * IMPORTANT: The main field needs to be placed last in the CC_Record construction so
	 * it gets updated LAST!
	 *
	 * @access public
	 * @return int The number of selections to choose from.
	 */
	
	function setAssociatedField(&$associatedField, $requiredValue)
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
		$errorFields = array();
		
		for ($i = 0; $i < sizeof($this->_associatedFields); $i++)
		{
			$currentAssociatedField = &$this->_associatedFields[$i][0];
			$currentRequiredValue = $this->_associatedFields[$i][1];
			
			if ($currentRequiredValue == $this->getValue())
			{	
				if (!$currentAssociatedField->hasValue())
				{
					$valid = false;
					$errorFields[] = &$currentAssociatedField;
				}
			}
				
			unset($currentAssociatedField);
			unset($currentRequiredValue);
		}
		
		if ($valid)
		{
			$this->clearAllErrors();
		}
		else
		{
			$this->setErrorMessage('Some additional fields are required', CC_FIELD_ERROR_CUSTOM);
			
			for ($i = 0; $i < sizeof($errorFields); $i++)
			{
				$errorFields[$i]->setErrorMessage('Please include a value.', CC_FIELD_ERROR_MISSING);
			}
		}

		return $valid;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setOptions
	//-------------------------------------------------------------------

	/** 
	 * This sets the field's options array. If this is a 1D array, the label and the value are the same. If it's a 2D array, the first item represents the value, the second represents the label.
	 *
	 * @access public
	 * @param array $options The options array to set.
	 */

	function setOptions($options)
	{
		$this->options = $options;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getOptions
	//-------------------------------------------------------------------

	/** 
	 * This gets the field's options array.
	 *
	 * @access public
	 * @return array An array of the selection options.
	 */

	function getOptions()
	{
		return $this->options;
	}
}

?>