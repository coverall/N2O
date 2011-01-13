<?php
// $Id: CC_RadioButton.php,v 1.36 2009/03/10 04:34:42 patrick Exp $
//=======================================================================
// CLASS: CC_RadioButton
//=======================================================================

/**
 * The CC_RadioButton class works in association with the parent CC_RadioButton_Field. The CC_RadioButton_Field contains an array on CC_RadioButton's, each with its own instance of this class. Only the CC_RadioButton_Field class has access to this.
 *
 * @package CC_Fields
 * @access private
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 * @see CC_RadioButton_Field
 */
 
class CC_RadioButton
{
	/**
     * The value of the radiobutton.
     *
     * @var mixed $value
     * @access private
     */
     
	var $value;
	
	
	/**
     * The radiobutton's label.
     *
     * @var string $label
     * @access private
     */
    
    var $label;
	
	
	/**
     * True if the radio button is selected.
     *
     * @var bool $isSelected
     * @access private
     */
    
    var $isSelected;
	
	
	/**
     * The array of radio buttons for this group.
     *
     * @var CC_RadioButton_Field The parent field object.
     * @access private
     */
    
    var $radioField;
	
	
	/**
     * An optional onClick action for processing when the button is clicked.
     *
     * @var string $onClickAction
     * @access private
     */
    
    var $onClickAction;


	/**
     * An optional onMouseOver action for processing when the button is clicked.
     *
     * @var string $onMouseOverAction
     * @access private
     */
    
    var $onMouseOverAction;
	
	
	/**
     * An optional onMouseOut action for processing when the button is clicked.
     *
     * @var string $onMouseOutAction
     * @access private
     */
    
    var $onMouseOutAction;
	
	
	/**
     * The index location of this radio button in the group.
     *
     * @var int $_index
     * @access private
     */
    
     var $_index;
     
     
     /**
     * The record that this radio button belongs to.
     *
     * @var CC_Record $record
     * @access private
     */
    
     var $_record;

	
	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_RadioButton
	//-------------------------------------------------------------------

	/** 
	 * The CC_RadioButton constructor sets its values here, yo.
	 *
	 * @access public
	 * @param mixed $option The option this radio button represents. The label and value are the same if $option is a single value. If it is an array, the first element is used as the label, the second item, the value.
	 * @param bool $isSelected Whether or not this field is selected.
	 * @param int $index The radiobutton's index.
	 * @param string $onClickString The optional action to take if the label is clicked.
	 */

	function CC_RadioButton($option, $isSelected, $index = 0, $onClickString = '')
	{
		if (is_array($option))
		{
			$this->value = $option[1];
			$this->label = $option[0];
		}
		else
		{	
			$this->value = $option;
			$this->label = $option;
		}
		
		$this->isSelected = $isSelected;
		
		$this->onClickAction = $onClickString;
		
		$this->_index = $index;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setRadioButtonField
	//-------------------------------------------------------------------
	
	/** 
	 * This sets the field's parent CC_RadioButton_Field field.
	 *
	 * @access public
	 * @param CC_RadioButton_Field $radioField The CC_RadioButton_Field to set.
	 */
	 
	function setRadioButtonField(&$radioField)
	{
		$this->radioField = &$radioField;
		$this->_index = $radioField->_index++;
	}


	//-------------------------------------------------------------------
	// METHOD: getHTML
	//-------------------------------------------------------------------
	
	/** 
	 * Returns HTML for a 'radio' form field.
	 *
	 * @access public
	 * @return string The HTML for the field.
	 */
	
	function getHTML($showLabel = true)
	{
		$radioHTML = '<input type="radio" name="' . $this->getRecordKey() . $this->radioField->name . '" value="' . $this->value . '"';
		
		if (strlen($this->onClickAction))
		{
			$radioHTML .= ' onClick="' . $this->onClickAction . '"';
		}
		
		if ($this->isSelected)
		{
			$radioHTML .= ' checked';
		}
		
		if ($this->radioField->isDisabled())
		{
			$radioHTML .= ' disabled';
		}
		
		$radioHTML .= '>';
		
		if ($showLabel)
		{
			$radioHTML .= ' ' . $this->getLabel();
		}
		
		return $radioHTML;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setLabel
	//-------------------------------------------------------------------
	
	/** 
	 * This sets the radio button label.
	 *
	 * @access public
	 * @param string $label The label text.
	 */
	  
	function setLabel($label)
	{
		$this->label = $label;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getLabel
	//-------------------------------------------------------------------
	
	/** 
	 * This returns the radio button label.
	 *
	 * @access public
	 * @param String $extra Extra code to go into the span tag around the label.
	 * @return string The radio button label.
	 */
	  
	function getLabel($extra = null)
	{
		if ($this->radioField->hasLinkableLabel())
		{
			$label = '<span class="ccClickableLabel"';
		}
		else
		{
			$label = '<span';
		}
		
		
		if ($this->radioField->hasLinkableLabel() && !$this->radioField->isDisabled() && !$this->radioField->readonly)
		{
			$label .= ' onClick="document.getElementsByName(\'' . $this->getRecordKey() . $this->radioField->name . '\')';
			if (sizeof($this->radioField->radioButtons) > 1)
			{
				$label .= '[' . $this->_index . ']';
			}
			$label .= '.checked = 1; ' . ($this->onClickAction ? $this->onClickAction . ';' : '') . ' return true;"';
		}
		
		if ($this->onMouseOverAction)
		{
			$label .= ' onMouseOver="' . $this->onMouseOverAction . '"';
		}
		
		if ($this->onMouseOutAction)
		{
			$label .= ' onMouseOut="' . $this->onMouseOutAction . '"';
		}
		
		$label .= '>' . $this->label . '</span>';

		return $label;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setSelected
	//-------------------------------------------------------------------
	
	/** 
	 * Sets the whether or not the radio button is selected. 
	 *
	 * @access public
	 * @param bool $isSelected Is it selected?
	 */
	 
	function setSelected($isSelected)
	{	
		$this->isSelected = $isSelected;
	}


	//-------------------------------------------------------------------
	// METHOD: isSelected
	//-------------------------------------------------------------------
	
	/** 
	 * Gets the whether or not the radio button is selected. 
	 *
	 * @access public
	 * @return bool Is it selected?
	 */
	  
	function isSelected()
	{	
		return $this->isSelected;
	}


	//-------------------------------------------------------------------
	// METHOD: setOnClickAction
	//-------------------------------------------------------------------
	 
	/** 
	 * Sets an optional action for an onClick event on the radio button. 
	 *
	 * @access public
	 * @param string The action to take.
	 */
	 
	function setOnClickAction($onClickString)
	{	
		$this->onClickAction = $onClickString;
	}

	
	//-------------------------------------------------------------------
	// METHOD: setOnMouseOverAction
	//-------------------------------------------------------------------
	 
	/** 
	 * Sets an optional action for an onMouseOver event on the radio button. 
	 *
	 * @access public
	 * @param string The action to take.
	 */
	 
	function setOnMouseOverAction($string)
	{	
		$this->onMouseOverAction = $string;
	}

	
	//-------------------------------------------------------------------
	// METHOD: setOnMouseOutAction
	//-------------------------------------------------------------------
	 
	/** 
	 * Sets an optional action for an onMouseOver event on the radio button. 
	 *
	 * @access public
	 * @param string The action to take.
	 */
	 
	function setOnMouseOutAction($string)
	{	
		$this->onMouseOutAction = $string;
	}

	
	//-------------------------------------------------------------------
	// METHOD: setIndex
	//-------------------------------------------------------------------
	
	/** 
	 * Sets the radio button's index.
	 *
	 * @access public
	 * @param int $index The index to set.
	 */
	  
	function setIndex($index)
	{	
		$this->_index == $index;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setRecord
	//-------------------------------------------------------------------

	function setRecord(&$record)
	{
		$this->_record = &$record;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getRecordKey
	//-------------------------------------------------------------------

	function getRecordKey()
	{
		if ($this->isInRecord())
		{
			return ($this->_record->getKeyID($this->_record->table, $this->_record->id)) . '|';
		}
		else
		{
			//trigger_error('The field ' .  $this->name . ' did not belong to a record.', E_USER_WARNING);
			return false;
		}
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: isInRecord
	//-------------------------------------------------------------------

	function isInRecord()
	{
		return isset($this->_record);
	}
}

?>