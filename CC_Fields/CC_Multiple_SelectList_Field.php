<?php
// $Id: CC_Multiple_SelectList_Field.php,v 1.3 2004/09/14 18:18:23 patrick Exp $
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

	function CC_Multiple_SelectList_Field($name, $label, $required = false, $defaultValue = '', $unselectedValue = ' - Select - ', $theOptions = null)
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
		$selectHTML = '<select multiple name="' . $this->getRecordKey() . $this->name . '[]"' . ($this->_onChange ? ' onChange="' . $this->_onChange . '"' : '' ) . ' class="' . $this->getInputStyle() . "\">\n";

		$names = array_keys($this->options);
		
		if (strlen($this->unselectedValue))
		{
			$selectHTML .= ' <option value="' . $this->_unselectedValueValue . '">' . htmlspecialchars($this->unselectedValue) . "</option>\n";
		}
		
		$size = sizeof($names);
		
		for ($i = 0; $i < $size; $i++)
		{
			if (is_array($this->options[$names[$i]]))
			{
				$theArray = &$this->options[$names[$i]];

				$theValue = $theArray[0];
				$theName  = $theArray[1];
			}
			else
			{
				$theValue = $this->options[$names[$i]];
				$theName  = $this->options[$names[$i]];
			}
			
			$selectHTML .= ' <option value="' . htmlspecialchars($theValue) . '"';
			
			if (in_array($theValue, $this->getValue()))
			{	
				$selectHTML .= ' selected';
			}
			
			$selectHTML .= '>' . $theName . "</option>\n";
		}
		
		unset($size);
		
		$selectHTML .= "</select>";

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
		$numValues = sizeof($this->value);
		
		for ($i = 0; $i < $numValues; $i++)
		{
			$commaDelimitedList .= $this->value[$i];
			
			if ($i < ($numValues - 1))
			{
				 $commaDelimitedList .= ',';
			}
		}
		
		return addslashes($commaDelimitedList);
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
		if (is_array($this->options[0]))
		{
			$optionsAreAnArray = true;
		}
		else
		{
			$optionsAreAnArray = false;
		}
		
		$size = sizeof($this->options);
	
		for ($i = 0; $i < $size; $i++)
		{
			if ($optionsAreAnArray)
			{
				$theArray = $this->options[$i];	
				$currentValue = $theArray[0];
				$displayValue = $theArray[1];
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
		
		$valueArray = array();
		
		for ($i = 0; $i < $size; $i++)
		{
			if (is_array($this->options[$i]))
			{
				$theArray = &$this->options[$i];

				$theValue = $theArray[0];
				$theName  = $theArray[1];
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
		
		for ($i = 0; $i < $size; $i++)
		{
			$names = array_keys($this->options);
		
			if (is_array($this->options[$names[$i]]))
			{
				$theArray = &$this->options[$names[$i]];

				$theValue = $theArray[0];
				$theName  = $theArray[1];
			}
			else
			{
				$theValue = $this->options[$names[$i]];
				$theName  = $this->options[$names[$i]];
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
		
		unset($size);
		
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
		
		for ($i = 0; $i < sizeof($indexArray); $i++)
		{
			$index = $indexArray[$i];
		
			if (strlen($this->unselectedValue))
			{
				$index--;
			}
			
			if ($index < sizeof($this->options) && $index >= 0)
			{
				$keys = array_keys($this->options);
				
				if (is_array($this->options[$keys[$index]]))
				{
					$currentValue = $this->options[$keys[$index]][0];
				}
				else
				{
					$currentValue = $this->options[$keys[$index]];
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
}

?>