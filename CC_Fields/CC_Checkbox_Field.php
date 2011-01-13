<?php
// $Id: CC_Checkbox_Field.php,v 1.44 2010/11/11 04:28:32 patrick Exp $
//=======================================================================
// CLASS: CC_Checkbox_Field
//=======================================================================

/**
 * The CC_Checkbox_Field field represents a checkbox where a user can choose to select or de-select a specific value.
 *
 * @package CC_Fields
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */


class CC_Checkbox_Field extends CC_Field
{	
	/**
     * An optional value that one can store here
     *
     * @var mixed $optionalValue
     * @access private
     */
    
    var $optionalValue;
	
	
	/**
     * Whether or not the field's label should be used to make select the checkbox as well as the checkbox itself.
     *
     * @var bool $_linkableLabel
     * @access private
     */
     
     var $_linkableLabel;


	/**
     * An optional onClick action for processing when the button is clicked.
     *
     * @var string $onClickAction
     * @access private
     */
    
    var $onClickAction;
	
	
	/**
     * A two dimensional array who's first value is the associated field and the second is the value of the CC_Checkbox_Field that makes the associated field required.
     *
     * @var array $_associatedFields
     * @access private
     */
     
	var $_associatedFields = array();
	    

	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Checkbox_Field
	//-------------------------------------------------------------------

	/** 
	 * The CC_Checkbox_Field constructor sets its values here, yo. 
	 *
	 * @access public
	 * @param string $name The unique name of the field. Names must be unique so that N2O knows which fields to update when users submit data.
	 * @param string $label A text label to accompany the field to describe which input is either expected or displayed.
	 * @param bool $required Whether or not this field must contain data from the user before proceeding. Not required by default.
	 * @param string $defaultValue The value the field should contain before user's submit input. The field is blank by default.
	 * @param int $linkableLabel Whether or not the field's label should be used to make the checkbox selection as well as the checkbox itself.
	 * @param string $onClickString The optional action to take if the label is clicked.
	 */

	function CC_Checkbox_Field($name, $label = '', $required = false, $defaultValue = false, $linkableLabel = true, $onClickString = '')
	{
		$defaultValue = ( ($defaultValue == 1 || $defaultValue === true || (string)$defaultValue == 't') ? true : false );
		
		$this->CC_Field($name, $label, $required, $defaultValue);
		
		$this->_linkableLabel = $linkableLabel;

		$this->onClickAction = $onClickString;
	}
	

	//-------------------------------------------------------------------
	// METHOD: isChecked
	//-------------------------------------------------------------------
	
	/** 
	 * This gets whether or not the field is selected or not.
	 *
	 * @access public
	 * @return bool Whether or not the checkbox is selected.
	 */
	 
	function isChecked()
	{
		return $this->value;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getValue
	//-------------------------------------------------------------------
	
	/** 
	 * This gets the fields value. Returns 0 if false, 1 if true.
	 *
	 * @access public
	 * @return int 0 if false, 1 if true.
	 */

	function getValue()
	{
		return ( ($this->value === true) ? 1 : 0 );
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getYesNoValue
	//-------------------------------------------------------------------
	
	/** 
	 * This gets the fields value. Returns 'No' if false, 'True' if true.
	 *
	 * @access public
	 * @return 'False' if false, 'True' if true.
	 */

	function getYesNoValue()
	{
		return ( ($this->value === true) ? 'Yes' : 'No' );
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getViewHTML
	//-------------------------------------------------------------------
	
	/** 
	 * Returns Yes or No depending on the field's value. 
	 *
	 * @access public
	 * @return string Yes or No.
	 */
	 
	function getViewHTML()
	{
		return $this->getYesNoValue();
	}

	
	//-------------------------------------------------------------------
	// METHOD: setValue
	//-------------------------------------------------------------------
	
	/** 
	 * This sets the value for the field.
	 *
	 * @access public
	 * @param bool $value The value to set. true for checked, false for unchecked.
	 */
	 
	function setValue($value = 0)
	{
		$this->value = ( ($value == 1 || $value === true || (string)$value == 't') ? true : false );
	}

	
	//-------------------------------------------------------------------
	// METHOD: setOptionalValue
	//-------------------------------------------------------------------
	
	/** 
	 * This sets an optional value for the text field.
	 *
	 * @access public
	 * @param mixed $value The optional value to set.
	 */
	 
	function setOptionalValue($optionalValue)
	{
		$this->optionalValue = $optionalValue;
	}

	
	//-------------------------------------------------------------------
	// METHOD: setLinkableLabel
	//-------------------------------------------------------------------
	
	/** 
	 * This sets whether or not the field's label can be used to select as well as the checkbox itself.
	 *
	 * @access public
	 * @param bool $value The value to set.
	 */
	 
	function setLinkableLabel($value)
	{
		$this->_linkableLabel = ( ($value == 1 || $value === true) ? true : false );
	}

	
	//-------------------------------------------------------------------
	// METHOD: getOptionalValue
	//-------------------------------------------------------------------
	
	/** 
	 * This gets the field's optional value.
	 *
	 * @access public
	 * @return mixed The optional value that was set.
	 */
	 
	function getOptionalValue()
	{
		return $this->optionalValue;
	}

	
	//-------------------------------------------------------------------
	// METHOD: getEditHTML
	//-------------------------------------------------------------------
	
	/** 
	 * Returns HTML for an 'checkbox' form field.
	 *
	 * @access public
	 * @return string The HTML for the field.
	 */
	  
	function getEditHTML()
	{
		$checkboxHTML = '<input type="checkbox" name="' . $this->getRecordKey() . $this->name . '" value="1"';
		
		if ($this->value === true)
		{
			$checkboxHTML .= ' checked';
		}
		
		if ($this->onClickAction != '')
		{
			$checkboxHTML .= ' onClick="' . $this->onClickAction . '"';
		}
		
		if ($this->isDisabled())
		{
			$checkboxHTML .= ' disabled';
		}
		
		if (strlen($this->id) > 0)
		{
			$checkboxHTML .= ' id="' . $this->id . '"';
		}
		
		$checkboxHTML .= '>';
		
		return $checkboxHTML;
	}


	//-------------------------------------------------------------------
	// METHOD: getLabel
	//-------------------------------------------------------------------
	
	/** 
	 * Returns the HTML for the field's label, linkable or not, depending on the indicated preference.
	 *
	 * @access public
	 * @return string The HTML for the label.
	 */
	 
	function getLabel()
	{
		$style = ($this->_error ? 'ccLabelError' : $this->labelStyle);

		$labelText = '';
		
		if ($this->_linkableLabel && !$this->isDisabled() && !$this->readonly)
		{
			$labelText .= '<span class="ccClickableLabel" onClick="document.getElementsByName(\'' . $this->getRecordKey() . $this->name . '\')[0].checked = !document.getElementsByName(\'' . $this->getRecordKey() . $this->name . '\')[0].checked; ' . $this->onClickAction . '">';
			
			if ($style)
			{
				$labelText .= '<span class="' . $style . '">' . $this->label . '</span>';
			}
			else
			{
				$labelText .= $this->label;
			}

			$labelText .= '</span>';
		}
		else
		{
			if ($style)
			{
				$labelText .= '<span class="' . $style . '">' . $this->label . '</span>';
			}
			else
			{
				$labelText .= $this->label;
			}
		}
		
		if ($this->required && $this->showAsterisk)
		{
			$labelText .= '<sup>*</sup>';
		}
		
		unset($style);
		
		return $labelText;
	}


	//-------------------------------------------------------------------
	// METHOD: validate
	//-------------------------------------------------------------------

	/**
	 * Validates by checking to see if the field is required, and if it is, it must be checked for it to be valid. This is useful for situations like the user must check the field to indicate they have read some sort of agreement.
	 *
	 * @access public
	 * @return bool Whether or not the field is valid or not.
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
			
			if ($this->isRequired())
			{
				return $this->isChecked();
			}
			else
			{
				return true;
			}
		}
		else
		{
			$this->setErrorMessage('Some additional fields are required', CC_FIELD_ERROR_CUSTOM);
					
			for ($i = 0; $i < sizeof($errorFields); $i++)
			{
				$errorField = &$errorFields[$i];
				$errorField->setErrorMessage('Please include a value.', CC_FIELD_ERROR_MISSING);
				unset($errorField);
			}
			
			return false;
		}
	}


	//-------------------------------------------------------------------
	// METHOD: setOnClickAction
	//-------------------------------------------------------------------
	 
	/** 
	 * Sets an optional action for an onClick event on the checkbox.
	 *
	 * @access public
	 * @param string The action to take.
	 */
	 
	function setOnClickAction($onClickString)
	{	
		$this->onClickAction = $onClickString;
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
	// METHOD: handleUpdateFromRequest
	//-------------------------------------------------------------------

	/**
     * This method gets called by CC_Window when it's time to update the field from the $_REQUEST array. Most fields are straight forward, but some have additional fields in the request that need to be handled specially. Such fields should override this method, and update the field's value in their own special way.
     *
     * @access public
     * @param mixed $fieldValue The value to set the field to.
     * @see getValue()
     */	

	function handleUpdateFromRequest()
	{
		if (!$this->isDisabled())
		{
			if (array_key_exists($this->getRequestArrayName(), $_REQUEST))
			{
				$this->setValue(1);
			}
			else
			{
				$this->setValue(0);
			}
		}
	}


	//-------------------------------------------------------------------
	// STATIC METHOD: getInstance
	//-------------------------------------------------------------------

	/**
	 * This is a static method called by CC_Record when it needs an instance
	 * of a field. The implementing field needs to return a constructed
	 * instance of itself.
	 *
	 * @access public
	 */

	static function &getInstance($className, $name, $label, $value, $args, $required)
	{
		if ($value)
		{
			$value = ($value == 1 || $value == 't') ? true : false;
		}
		else
		{
			$value = (isset($args->checked) ? ($args->checked == 1) : false);
		}

		$field = new $className($name, $label, $required, $value);
		
		if (isset($args->optionalValue))
		{
			$field->setOptionalValue($args->optionalValue);
		}
		
		return $field;
	}

}

?>