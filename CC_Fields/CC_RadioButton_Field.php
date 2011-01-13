<?php
// $Id: CC_RadioButton_Field.php,v 1.43 2010/11/11 04:28:32 patrick Exp $
//=======================================================================
// CLASS: CC_RadioButton_Field
//=======================================================================

/**
 * The CC_RadioButton_Field allows a user to choose from a visible-at-once list of possible choices where only one choice can be selected. This class required the CC_RadioButton class to define each member radio button.
 *
 * @package CC_Fields
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 * @see CC_RadioButton
 */

class CC_RadioButton_Field extends CC_Multiple_Choice_Field
{	
	/**
     * The array of radio buttons for this group.
     *
     * @var array $radioButtons
     * @access private
     */
     
	var $radioButtons;
	
	
	/**
     * Whether or not the label can be clickable to select the field.
     *
     * @var bool $_linkableLabel
     * @access private
     */
     
    var $_linkableLabel;	
	
	
	/**
     * The index of the currently selected radio button.
     *
     * @var int $_index
     * @access private
     */
     
	var $_index = 0;


	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_RadioButton_Field
	//-------------------------------------------------------------------

	/** 
	 * The CC_RadioButton_Field constructor sets its values here, yo. 
	 *
	 * @access public
	 * @param string $name The unique name of the field. Names must be unique so that N2O knows which fields to update when users submit data.
	 * @param string $label A text label to accompany the field to describe which input is either expected or displayed.
	 * @param bool $required Whether or not this field must contain data from the user before proceeding. Not required by default.
	 * @param string $defaultValue The value the field should contain before user's submit input. The field is blank by default.
	 * @param array $options An array of selection options. If this is a 1D array, the label and the value are the same. If it's a 2D array, the first item represents the value, the second represents the label.
	 * @param int $linkableLabel Whether or not the field's label should be used to make the checkbox selection as well as the checkbox itself.
	 */

	function CC_RadioButton_Field($name, $label, $required = false, $defaultValue = '', $options = null, $linkableLabel = true)
	{
		if ($options == null)
		{
			$options = array();
		}
		
		$this->CC_Multiple_Choice_Field($name, $label, $required, $defaultValue);

		$this->setOptions($options);		
		
		$this->_linkableLabel = $linkableLabel;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: addRadioButton
	//-------------------------------------------------------------------
	
	/** 
	 * This adds a radio button to the field.
	 *
	 * @access public
	 * @param CC_RadioButton $radioButton The radio button to add.
	 */
	  
	function addRadioButton(&$radioButton)
	{
		// set the parent manually
		$radioButton->setRadioButtonField($this);
		
		// set the index manually
		$radioButton->setIndex(sizeof($this->radioButtons));
		
		$this->radioButtons[] = &$radioButton;
	}
	

	//-------------------------------------------------------------------
	// METHOD: getButtonHTML
	//-------------------------------------------------------------------
	
	/** 
	 * This returns the button HTML for the radio button at the given index.
	 *
	 * @access public
	 * @param int $index The index of the radio button.
	 * @return string The radio button HTML.
	 */
	  
	function getButtonHTML($buttonName, $showLabel = true)
	{
		$this->radioButtons[$buttonName]->setSelected($buttonName == $this->value);
		
		return $this->radioButtons[$buttonName]->getHTML($showLabel);
	}
	

	//-------------------------------------------------------------------
	// METHOD: getButtonHTMLAtIndex
	//-------------------------------------------------------------------
	
	/** 
	 * This returns the button HTML for the radio button at the given index.
	 *
	 * @access public
	 * @param int $index The index of the radio button.
	 * @return string The radio button HTML.
	 */
	  
	function getButtonHTMLAtIndex($index, $showLabel = true)
	{
		$this->radioButtons[$index]->setSelected($this->radioButtons[$index]->value == $this->value);
		
		if ($this->radioButtons[$index])
		{
			return $this->radioButtons[$index]->getHTML($showLabel);
		}
		else
		{	
			return "<!-- There is no radio button at index " . $index . "! -->";
		}
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getButtonLabel
	//-------------------------------------------------------------------
	
	/** 
	 * This returns the button label for the radio of the given name.
	 *
	 * @access public
	 * @param string $buttonName The name of the radio button.
	 * @return string The radio button label.
	 */
	  
	function getButtonLabel($buttonName)
	{
		return $this->radioButtons[$buttonName]->getLabel();
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getButtonLabelAtIndex
	//-------------------------------------------------------------------
	
	/** 
	 * This returns the button label for the radio button at the given index.
	 *
	 * @access public
	 * @param int $index The index of the radio button.
	 * @return string The radio button label.
	 */
	  
	function getButtonLabelAtIndex($index)
	{
		$radioButtonKeys = array_keys($this->radioButtons);
		$radioButtonName = $radioButtonKeys[$index];
		
		if ($this->radioButtons[$radioButtonName])
		{
			return $this->radioButtons[$radioButtonName]->getLabel();
		}
		else
		{	
			return "<!-- There is no radio button at index " . $index . "! -->";
		}
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setOptions
	//-------------------------------------------------------------------

	/** 
	 * This sets the field's options array. If this is a 1D array, the label and the value are the same. If it's a 2D array, the first item of each entry represents the value, the second represents the label.
	 *
	 * @access public
	 * @param array $options The options array to set.
	 */

	function setOptions($options)
	{
		$this->options = $options;
		
		$this->radioButtons = array();

		$size = sizeof($options);
		
		$value = $this->getValue();

		for ($i = 0; $i < $size; $i++)
		{
			if (is_array($options[$i]))
			{
				$isSelected = ($options[$i][0] === $value);
			}
			else
			{
				$isSelected = ($options[$i] === $value);
			}

			$this->addRadioButton(new CC_RadioButton($options[$i], $isSelected));
		}
		
		unset($size);
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
	// METHOD: setValue
	//-------------------------------------------------------------------
	
	function setValue($valueToSet)
	{
		parent::setValue($valueToSet);
		
		$size = sizeof($this->radioButtons);
		
		for ($i = 0; $i < $size; $i++)
		{
			$selectedRadioButton = &$this->radioButtons[$i];
			
			if ($selectedRadioButton->value === $valueToSet)
			{
				$selectedRadioButton->setSelected(true);
			}
			else
			{
				$selectedRadioButton->setSelected(false);
			}
		}
		
		unset($size);
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setSelectedAtIndex
	//-------------------------------------------------------------------
	
	/** 
	 * Sets the index of the currently selected radio button. 
	 *
	 * @access public
	 * @param int $index The selected zero-based index.
	 */
	 
	function setSelectedAtIndex($index)
	{
		if (!is_numeric($index))
		{
			$index = 0;
			trigger_error('Received a blank index. Resetting to 0...', E_USER_WARNING);
		}
		else if (intval($index) >= sizeof($this->radioButtons))
		{
			$index = 0;
			trigger_error('Received an index greater than the number of radio buttons. Resetting to 0...', E_USER_WARNING);
		}
		else if (intval($index) < 0)
		{
			$index = 0;
			trigger_error('Received an index less than the zero. Resetting to 0...', E_USER_WARNING);
		}
		
		$this->radioButtons[intval($index)]->setSelected(true);
		parent::setValue($this->radioButtons[intval($index)]->value);
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
		$size = sizeof($this->radioButtons);
		
		for ($i = 0; $i < $size; $i++)
		{
			if ($this->radioButtons[$i]->isSelected())
			{
				unset($size);

				return $i;
			}
		}
		
		unset($size);
		
		return -1;
	}


	
	//-------------------------------------------------------------------
	// METHOD: getEditHTML
	//-------------------------------------------------------------------

	/** 
	 * Returns HTML for 'radio' form fields based on the contained radio buttons.
	 *
	 * @access public
	 * @return string The HTML for the field.
	 */

	function getEditHTML($delimiter = '<br>')
	{
		$size = sizeof($this->radioButtons);
		
		$radioHTML = '';
		
		for ($i = 0; $i < $size; $i++)
		{
			$radioHTML .= $this->getButtonHTMLAtIndex($i) . $delimiter;
		}
		
		unset($size);
		
		return $radioHTML;
	}
	

	//-------------------------------------------------------------------
	// METHOD: hasValue
	//-------------------------------------------------------------------
	
	/** 
	 * Returns whether or not the field has a selected value. 
	 *
	 * @access public
	 * @return bool True or false depending on the value.
	 */
	 
	function hasValue()
	{
		if ($this->getSelectedIndex() == -1)
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	

	//-------------------------------------------------------------------
	// METHOD: getViewHTML
	//-------------------------------------------------------------------

	/** 
	 * Returns the field's value as text. 
	 *
	 * @access public
	 * @return mixed The field's value.
	 */

	function getViewHTML()
	{
		if (is_array($this->options[0]))
		{
			$size = sizeof($this->options);
			
			for ($i = 0; $i < $size; $i++)
			{
				$theArray = $this->options[$i];
				if ($this->value == $theArray[0])
				{
					unset($size);
					return $theArray[1];
				}
			}
			
			unset($size);
		}
		
		return $this->getValue();
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
	// METHOD: hasLinkableLabel
	//-------------------------------------------------------------------
	
	/** 
	 * This gets whether or not the field's label can be used to select as well as the checkbox itself.
	 *
	 * @access public
	 * @param bool $value Whether the label is linkable or not.
	 */
	 
	function hasLinkableLabel()
	{
		return $this->_linkableLabel;
	}


	//-------------------------------------------------------------------
	// METHOD: getSize
	//-------------------------------------------------------------------

	/**
	 * Returns the number of radio buttons in this field.
	 *
	 * @access public
	 * @return int The number of radio buttons in this field.
	 */
	
	function getSize()
	{
		return sizeof($this->radioButtons);
	}


	//-------------------------------------------------------------------
	// METHOD: setOnClickAtIndex
	//-------------------------------------------------------------------
	
	/** 
	 * Sets an optional action for an onClick event on the radio button at specified index. 
	 *
	 * @access public
	 * @param string $event The onclick javascript event
	 * @param int $index The selected zero-based index.
	 */
	 
	function setOnClickAtIndex($index, $event = '')
	{
		if (!cc_is_int($index))
		{
			$index = 0;
			trigger_error('Received a blank index. Resetting to 0...', E_USER_WARNING);
		}
		else if ((int)$index >= sizeof($this->radioButtons))
		{
			$index = 0;
			trigger_error('Received an index greater than the number of radio buttons. Resetting to 0...', E_USER_WARNING);
		}
		
		$this->radioButtons[$index]->setOnClickAction($event);
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setRecord
	//-------------------------------------------------------------------

	function setRecord(&$record)
	{
		$this->record = &$record;
		
		$size = sizeof($this->radioButtons);
		
		for ($i = 0; $i < $size; $i++)
		{
			$this->radioButtons[$i]->setRecord($this->record);
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
		$delimiter = (isset($args->delimiter) ? $args->delimiter : ',');
		$index = (isset($args->index) ? $args->index : 0);

		if (isset($args->options))
		{
			$options = explode($delimiter, $args->options);
		}
		else
		{
			$options = array();
		}
		
		if (!strlen($value) && isset($args->value))
		{
			$value = $args->value;
		}

		$field = new $className($name, $label, $required, $value, $options);

		if (!strlen($value))
		{
			$field->setSelectedAtIndex($index);
		}

		unset($delimiter, $options, $index);

		return $field;
	}
}

?>