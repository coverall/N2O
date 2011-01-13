<?php
// $Id: CC_SelectList_Field.php,v 1.43 2004/11/02 21:32:01 patrick Exp $
//=======================================================================
// CLASS: CC_SelectList_Field
//=======================================================================

/**
 * The CC_SelectList_Field field represents a single select list (or drop-down) form field that allows users to choose from a list of pre-defined selections.
 *
 * @package CC_Fields
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

class CC_SelectList_Field extends CC_Multiple_Choice_Field
{		
	/**
     * The value to be shown in the drop down select list when nothing has yet been selected.
     *
     * @var mixed $unselectedValue
     * @access private
     */
     
    var $unselectedValue;


	/**
     * If set, the select will use this javascript code on the onChange="" parameter.
     *
     * @var mixed $onChange
     * @access private
     */
     
	var $_onChange;


	/**
     * The value that will be posted for an unselected selectlist field.
     *
     * @var mixed $onChange
     * @access private
     */

	var $_unselectedValueValue = '';


	/**
     * If set to true, htmlspecialchars() will be called when outputting the select list.
     *
     * @var mixed $_escapeValues
     * @access private
     */

	var $_escapeValues = true;


	
	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_SelectList_Field
	//-------------------------------------------------------------------

	/** 
	 * The CC_SelectList_Field constructor sets its values here, yo. 
	 *
	 * @access public
	 * @param string $name The unique name of the field. Names must be unique so that N2O knows which fields to update when users submit data.
	 * @param string $label A text label to accompany the field to describe which input is either expected or displayed.
	 * @param bool $required Whether or not this field must contain data from the user before proceeding. Not required by default.
	 * @param string $defaultValue The value the field should contain before user's submit input. The field is blank by default.
	 * @param int $unselectedValue The value to use when nothing is selected in the select list. Default is ' - Select - '.
	 * @param array $theOptions An array of selection options. If this is a 1D array, the label and the value are the same. If it's a 2D array, the first item represents the value, the second represents the label.
	 */

	function CC_SelectList_Field($name, $label, $required = false, $defaultValue = '', $unselectedValue = ' - Select - ', $theOptions = null)
	{
		if ($theOptions == null)
		{
			$theOptions = array();
		}
		
		$this->options = $theOptions;		
		$this->unselectedValue = $unselectedValue;
		
		$this->CC_Multiple_Choice_Field($name, $label, $required, $defaultValue);
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
		return ($this->getValue() != $this->_unselectedValueValue);
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: resetValue
	//-------------------------------------------------------------------

	/**
     * Resets the field to its unselectedValue
     *
     * @access public
     * 
     **/

	function resetValue()
	{
		$this->setValue($this->_unselectedValueValue);
	}
		
	
	//-------------------------------------------------------------------
	// METHOD: setJavascriptOnChange
	//-------------------------------------------------------------------

	/** 
	 * This sets the Javascript code that will be used in the onChange="" parameter.
	 *
	 * @access public
	 * @param mixed $onChange The Javascript code.
	 */

	function setJavascriptOnChange($onChange)
	{
		$this->_onChange = $onChange;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getDisplayValue
	//-------------------------------------------------------------------
	
	/** 
	 * This gets the field's display value (as opposed to its actual value).
	 *
	 * @access public
	 * @return mixed The display value for the field. Same as the viewHTML.
	 */

	function getDisplayValue()
	{
		return $this->getViewHTML();
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
		$selectHTML = '<select id="' . $this->id . '" name="' . $this->getRecordKey() . $this->name . '"' . ($this->_onChange ? ' onChange="' . $this->_onChange . '"' : '' ) . ($this->disabled ? 'disabled="true"' : '') . ' class="' . $this->getInputStyle() . '" tabindex="' . $this->_tabIndex . "\">\n";

		$options = $this->getOptions();
		$names = array_keys($options);
		
		if (strlen($this->unselectedValue))
		{
			$selectHTML .= ' <option value="' . $this->_unselectedValueValue . '">' . htmlspecialchars($this->unselectedValue) . "</option>\n";
		}
		
		$size = sizeof($names);
		
		for ($i = 0; $i < $size; $i++)
		{
			if (is_array($options[$names[$i]]))
			{
				$theArray = &$options[$names[$i]];

				$theValue = $theArray[0];
				$theName  = $theArray[1];
				
				unset($theArray);
			}
			else
			{
				$theValue = $options[$names[$i]];
				$theName  = $options[$names[$i]];
			}
			

			$selectHTML .= ' <option value="' . ($this->_escapeValues ? htmlspecialchars($theValue) : $theValue) . '"';
			
			if (strcmp($theValue, $this->getValue()) == 0)
			{	
				$selectHTML .= ' selected';
			}
			
			$selectHTML .= '>' . $theName . "</option>\n";
			
			unset($theValue, $theName);
		}
		
		$selectHTML .= "</select>";

		unset($size, $options, $names);
		
		return $selectHTML;
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
		$options = $this->getOptions();
		
		if (is_array($options[0]))
		{
			$size = sizeof($options);
		
			for ($i = 0; $i < $size; $i++)
			{
				$theArray = $options[$i];
				if ($this->value == $theArray[0])
				{
					unset($size, $options);
					return $theArray[1];
				}
			}
			
			unset($size);
		}
		else
		{
			$index = $this->getSelectedIndex();
			
			if (strlen($this->unselectedValue))
			{
				$index--;
			}

			return $options[$index];
		}
		
		return $this->getValue();
	}
	

	//-------------------------------------------------------------------
	// METHOD: setUnselectedValue
	//-------------------------------------------------------------------

	/** 
	 * This sets the field's unselected value (ie. what to show when nothing has been selected).
	 *
	 * @access public
	 * @param mixed $unselectedValue The unselected value to set. If you pass a two-element array in, the first element will be used for the actual value that gets submitted, and the second will be used for the display value.
	 */

	function setUnselectedValue($unselectedValue = '')
	{
		if (is_array($unselectedValue))
		{
			$this->_unselectedValueValue = $unselectedValue[0];
			$this->unselectedValue = $unselectedValue[1];
		}
		else
		{
			$this->unselectedValue = $unselectedValue;
		}
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
		$options = $this->getOptions();
		
		$size = sizeof($options);
		
		for ($i = 0; $i < $size; $i++)
		{
			$optionArray = $options[$i];
			
			if ($optionArray[1] == $visibleValue)
			{
				$this->setValue($optionArray[0]);
			}
			
			unset($optionArray);
		}
		
		unset($size, $options);
	}
	

	//-------------------------------------------------------------------
	// METHOD: getNumberOfItems
	//-------------------------------------------------------------------

	/** 
	 * This returns the number of items in the select list.
	 *
	 * @access public
	 * @return int The number of selections to choose from.
	 */
	
	function getNumberOfItems()
	{
		return sizeof($this->getOptions());
	}


	//-------------------------------------------------------------------
	// METHOD: getOptions
	//-------------------------------------------------------------------

	/** 
	 * This returns the options array.
	 *
	 * @access public
	 * @return array The options array.
	 */
	
	function getOptions()
	{
		return $this->options;
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
		$options = $this->getOptions();
		$size = sizeof($options);
		$index = 0;
		
		for ($i = 0; $i < $size; $i++)
		{
			$names = array_keys($options);
		
			if (is_array($options[$names[$i]]))
			{
				$theArray = &$options[$names[$i]];

				$theValue = $theArray[0];
				$theName  = $theArray[1];
			}
			else
			{
				$theValue = $options[$names[$i]];
				$theName  = $options[$names[$i]];
			}

			if ($theValue == $this->getValue())
			{
				$index = $i;

				// The unselected value should be counted in the index...
				if (strlen($this->unselectedValue))
				{
					$index++;
				}
				
				$i = $size;
			}
		}
		
		unset($size, $options, $i);
		
		return $index;
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
		// The unselected value should be counted in the index...
		if (strlen($this->unselectedValue))
		{
			$index--;
		}

		$options = $this->getOptions();
		
		if ($index < sizeof($options) && $index >= 0)
		{
			$keys = array_keys($options);
			
			if (is_array($options[$keys[$index]]))
			{
				$this->setValue($options[$keys[$index]][0]);
			}
			else
			{
				$this->setValue($options[$keys[$index]]);
			}
		}
		else
		{
			trigger_error('Index out of bounds (' . $index . ')', E_USER_WARNING);
		}
		unset($keys, $options);
	}


	//-------------------------------------------------------------------
	// METHOD: setEscapeValues
	//-------------------------------------------------------------------
	
	/** 
	 * Sets the value of the select list to the selected index. 
	 *
	 * @access public
	 * @param int $index The index to set
	 */
	 
	function setEscapeValues($escape)
	{
		$this->_escapeValues = $escape;
	}
}

?>