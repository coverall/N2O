<?php
// $Id: CC_Multiple_SelectList_Field.php,v 1.13 2010/11/11 04:28:32 patrick Exp $
//=======================================================================
// CLASS: CC_Multiple_SelectList_Field
//=======================================================================

/**
 * The CC_Multiple_SelectList_Field field represents a single select list (or drop-down) form field that allows users to make multiple selections from a list of pre-defined choices.
 *
 * @package CC_Fields
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

class CC_Multiple_SelectList_Field extends CC_SelectList_Field
{	
	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Multiple_SelectList_Field
	//-------------------------------------------------------------------

	/** 
	 * The CC_Multiple_SelectList_Field constructor sets its values here, yo. 
	 *
	 * @access public
	 * @param string $name The unique name of the field. Names must be unique so that N2O knows which fields to update when users submit data.
	 * @param string $label A text label to accompany the field to describe which input is either expected or displayed.
	 * @param bool $required Whether or not this field must contain data from the user before proceeding. Not required by default.
	 * @param string $defaultValue The value the field should contain before user's submit input. The field is blank by default.
	 * @param int $unselectedValue The value to use when nothing is selected in the select list. Default is ' - Select - '.
	 * @param array $theOptions An array of selection options. If this is a 1D array, the label and the value are the same. If it's a 2D array, the first item represents the value, the second represents the label.
	 */

	function CC_Multiple_SelectList_Field($name, $label, $required = false, $defaultValue = false, $unselectedValue = ' - Select - ', $theOptions = null)
	{
		if ($theOptions == null)
		{
			$theOptions = array();
		}
				
		$this->CC_SelectList_Field($name, $label, $required, $defaultValue, $unselectedValue, $theOptions);
	}
		
	
	//-------------------------------------------------------------------
	// METHOD: hasValue
	//-------------------------------------------------------------------

	/**
     * Gets whether or not the field has a value.
     *
     * @access public
     * @return bool Whether or not the field has a value.
     * 
     **/

	function hasValue()
	{
		return (sizeof($this->value) > 0);
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setValue
	//-------------------------------------------------------------------

	/**
     * Gets whether or not the field has a value.
     *
     * @access public
     * @return bool Whether or not the field has a value.
     * 
     **/

	function setValue($value)
	{
		if (is_array($value))
		{
			$this->value = $value;
		}
		else if ($value)
		{
			parent::setValue(explode(',', $value));
		}
		else
		{
			parent::setValue(array());
		}
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getValue
	//-------------------------------------------------------------------

	/**
     * Gets whether or not the field has a value.
     *
     * @access public
     * @return bool Whether or not the field has a value.
     * 
     **/

	function getValue()
	{
		return implode(',', $this->value);
	}
		

	//-------------------------------------------------------------------
	// METHOD: getEditHTML
	//-------------------------------------------------------------------

	/** 
	 * Returns HTML for a 'select' form field. 
	 *
	 * @access public
	 * @return string The HTML for the field.
	 */

	function getEditHTML()
	{
		$selectHTML = '<select multiple id="' . $this->id . '" name="' . $this->getRecordKey() . $this->name . '[]"' . ($this->_onChange ? ' onChange="' . $this->_onChange . '"' : '' ) . ' class="' . $this->getInputStyle() . "\">\n";

		if (strlen($this->unselectedValue))
		{
			$selectHTML .= ' <option value="' . $this->_unselectedValueValue . '">' . htmlspecialchars($this->unselectedValue) . "</option>\n";
		}
		
		$size = sizeof($this->options);
	
		for ($i = 0; $i < $size; $i++)
		{
			if ($i == 0)
			{
				$isArray = is_array($this->options[0]);
			}

			if ($isArray)
			{
				$theValue = $this->options[$i][0];
				$theName  = $this->options[$i][1];
			}
			else
			{
				$theValue = $this->options[$i];
				$theName  = $this->options[$i];
			}
			
			$selectHTML .= ' <option value="' . htmlspecialchars($theValue) . '"';
			
			if (in_array($theValue, $this->value))
			{	
				$selectHTML .= ' selected';
			}
			
			$selectHTML .= '>' . $theName . "</option>\n";
		}
		
		unset($size, $isArray, $theValue, $theName);
		
		$selectHTML .= '</select>';

		return $selectHTML;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getEscapedValue
	//-------------------------------------------------------------------

	/**
     * Returns a comma delimited list of values to be inserted into the database.
     *
     * @access public
     * @return mixed The field's escaped value.
     */	
	
	function getEscapedValue()
	{
		return addslashes($this->getValue());
	}	
	
	
	//-------------------------------------------------------------------
	// METHOD: getValueList
	//-------------------------------------------------------------------

	/**
     * Returns a comma delimited list of values.
     *
     * @access public
     * @param $delimiter The delimiter to use.
     * @return mixed The field's value as a comma delimited list.
     */	
	
	function getValueList($delimiter = ',')
	{
		$numValues = sizeof($this->value);
		
		if ($numValues > 0)
		{
			$value = implode($delimiter, $this->value);
		}
		else
		{
			$value = '';
		}
		
		return $value;
	}

	//-------------------------------------------------------------------
	// METHOD: getViewHTML
	//-------------------------------------------------------------------

	/** 
	 * Returns HTML with the raw value of the field for viewing. 
	 *
	 * @access public
	 * @return string The HTML for the field.
	 */

	function getViewHTML()
	{
		$isArray = is_array($this->options[0]);
		$size = sizeof($this->options);
	
		for ($i = 0; $i < $size; $i++)
		{
			if ($isArray)
			{
				$currentValue = $this->options[$i][0];
				$displayValue = $this->options[$i][1];
			}
			else
			{
				$currentValue = $displayValue = $this->options[$i];
			}
					
			if (in_array($currentValue, $this->value))
			{
				$valueList .= $displayValue;
				
				if ($i < ($size - 1))
				{
					$valueList .= ', ';
				}
			}
		}
					
		return $valueList;
	}
	

	//-------------------------------------------------------------------
	// METHOD: setVisibleValue
	//-------------------------------------------------------------------

	/** 
	 * This sets the value of the select list based on the visible value as opposed to the actual value.
	 *
	 * @access public
	 * @param mixed $visibleValue The value to set in terms of the selection option labels, not their respective, actual values.
	 */
	
	function setVisibleValue($visibleValue)
	{
		$this->setVisibleValues(array($visibleValue));
	}


	//-------------------------------------------------------------------
	// METHOD: setVisibleValues
	//-------------------------------------------------------------------

	/** 
	 * This sets the values of the multiple select list based on the visible values as opposed to the actual value.
	 *
	 * @access public
	 * @param array $visibleValues The values to set in terms of the selection option labels, not their respective, actual values.
	 */
	
	function setVisibleValues($visibleValues)
	{
		$size = sizeof($this->options);
		$isArray = is_array($this->options[0]);
		$valueArray = array();
		
		for ($i = 0; $i < $size; $i++)
		{
			if ($isArray)
			{
				$theValue = $this->options[$i][0];
				$theName  = $this->options[$i][1];
			}
			else
			{
				$theValue = $theName = $this->options[$i];
			}
						
			if (in_array($theName, $visibleValues))
			{
				$valueArray[] = $theValue;
			}
			
			unset($optionArray);
			
			$this->setValue($valueArray);
		}
		
		unset($size);
	}
	

	//-------------------------------------------------------------------
	// METHOD: getSelectedIndex
	//-------------------------------------------------------------------
	
	/** 
	 * Returns the index of the currently selected list item. 
	 *
	 * @access public
	 * @return int The currently selected zero-based index.
	 */
	 
	function getSelectedIndex()
	{
		return $this->getSelectedIndices();
	}


	//-------------------------------------------------------------------
	// METHOD: getSelectedIndices
	//-------------------------------------------------------------------
	
	/** 
	 * Returns the indices of the currently selected list items.
	 *
	 * @access public
	 * @return array The currently selected zero-based indices.
	 */
	 
	function getSelectedIndices()
	{
		$size = sizeof($this->options);
		$indexArray = array();
		
		$isArray = is_array($this->options[0]);

		for ($i = 0; $i < $size; $i++)
		{
			if ($isArray)
			{
				$theValue = $this->options[$i][0];
				$theName  = $this->options[$i][1];
			}
			else
			{
				$theValue = $this->options[$i];
				$theName  = $this->options[$i];
			}

			if (in_array($theValue, $this->getValue()))
			{
				$index = $i;

				// The unselected value should be counted in the index...
				if (strlen($this->unselectedValue))
				{
					$index++;
				}
				
				$indexArray[] = $index;
			}
		}
		
		unset($size, $isArray);
		
		return $indexArray;
	}


	//-------------------------------------------------------------------
	// METHOD: setSelectedIndex
	//-------------------------------------------------------------------
	
	/** 
	 * Sets the value of the select list to the selected index. 
	 *
	 * @access public
	 * @param int $index The index to set
	 */
	 
	function setSelectedIndex($index)
	{
		$this->setSelectedIndices(array($index));
	}


	//-------------------------------------------------------------------
	// METHOD: setSelectedIndices
	//-------------------------------------------------------------------
	
	/** 
	 * Sets the values of the select list to the selected indices. 
	 *
	 * @access public
	 * @param array $indexArray The indices to set
	 */
	 
	function setSelectedIndices($indexArray)
	{
		$newValue = array();
		$size = sizeof($indexArray);
		$offset = strlen($this->unselectedValue) ? 1 : 0;
		$isArray = is_array($this->options[0]);
		
		for ($i = 0; $i < $size; $i++)
		{
			$index = $indexArray[$i];
			$index -= $offset;
			
			if ($index < sizeof($this->options) && $index >= 0)
			{
				if ($isArray)
				{
					$currentValue = $this->options[$index][0];
				}
				else
				{
					$currentValue = $this->options[$index];
				}
				
				$newValue[] = $currentValue;
			}
			else
			{
				trigger_error('Index out of bounds (' . $index . ')', E_USER_WARNING);
			}
			
			unset($currentValue);
		}
		
		$this->setValue($newValue);
		
		unset($keys);
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
			
			if (in_array($currentRequiredValue, $this->getValue()))
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
				$errorField = &$errorFields[$i];
				$errorField->setErrorMessage('Please include a value.', CC_FIELD_ERROR_MISSING);
				unset($errorField);
			}
		}

		return $valid;
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
		$key = $this->getRequestArrayName();

		if (array_key_exists($key, $_REQUEST))
		{
			// it's an array, by jove!
			$this->value = $_REQUEST[$key];
		}
		else
		{
			$this->setValue(false);
		}
		
		unset($key);
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
		$unselected = (isset($args->unselectedValue) ? $args->unselectedValue : '- Select -');

		if (isset($args->options))
		{
			$options = explode(',', $args->options);
		}
		else
		{
			$options = array();
		}
				
		$field = new $className($name, $label, $required, $value, $unselected, $options);

		unset($unselected, $options);

		return $field;
	}
}

?>