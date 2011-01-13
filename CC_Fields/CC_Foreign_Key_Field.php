<?php
// $Id: CC_Foreign_Key_Field.php,v 1.21 2004/09/14 18:18:23 patrick Exp $
//=======================================================================
// CLASS: CC_Foreign_Key_Field
//=======================================================================

/**
 * The CC_Foreign_Key_Field field represents a selection of values from a separate, but related database table.
 *
 * @package CC_Fields
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

class CC_Foreign_Key_Field extends CC_SelectList_Field
{
	/**
     * The window which the field belongs to. It is used to register the add button for this field.
     *
     * @var CC_Window $window
     * @access private
     */
	
	var $window;
	
	
	/**
	 * A button to add a new record to the related table.
	 *
     * @var CC_Button $manageButton
     * @access private
     */
     
	var $manageButton;
	
	/**
	 * The table the foreign key is related to.
	 *
     * @var string $relatedTableName
     * @access private
     */
     
	var $relatedTableName;
	
	
	/**
	 * Whether or not to show the add button.
	 *
     * @var bool $showAddButton
     * @access private
     */
     
    var $showAddButton;
	
	
	/**
	 * An optional where clause for selecting the choice of records in the related table.
	 *
     * @var string $whereClause
     * @access private
     */
     
     var $whereClause;

	
	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Foreign_Key_Field
	//-------------------------------------------------------------------

	/** 
	 * The CC_Foreign_Key_Field constructor sets its values here, yo. 
	 *
	 * @access public
	 * @param CC_Window $window The window the field belongs to.
	 * @param string $name The unique name of the field. Names must be unique so that N2O knows which fields to update when users submit data.
	 * @param string $label A text label to accompany the field to describe which input is either expected or displayed.
	 * @param string $relatedTableName The name of the related table.
	 * @param bool $required Whether or not this field must contain data from the user before proceeding. Not required by default.
	 * @param string $defaultValue The value the field should contain before user's submit input. -1 by default.
	 * @param int $unselectedValue The value to use when nothing is selected in the select list. Default is ' - Select - '.
	 * @param array $theOptions An array of selection options. If this is a 1D array, the label and the value are the same. If it's a 2D array, the first item represents the value, the second represents the label.
	 * @param bool $showAddButton Whether or not we should show the add button.
	 * @param string $addButtonHandlerClass The add button handler to execute upon clicking.
	 */

	function CC_Foreign_Key_Field($name, $label, $relatedTableName, $required = false, $defaultValue = -1, $unselectedValue = ' - Select - ', $theOptions = null, $showAddButton = true, $addButtonHandlerClass = 'CC_Manage_FK_Table_Handler')
	{
		if ($theOptions == null)
		{
			$theOptions = array();
		}
	
		$application = &$_SESSION['application'];
		
		$this->CC_SelectList_Field($name, $label, $required, $defaultValue, $unselectedValue, $theOptions);
		
		$this->window = &$application->getCurrentWindow();
		$this->relatedTableName = $relatedTableName;
		$this->setShowAddButton($showAddButton);

		if ($showAddButton)
		{
			$this->manageButton = new CC_Button('Add ' . $this->label);
		
			$this->manageButton->setValidateOnClick(false);
			$this->manageButton->setFieldUpdater(true);
			
			$this->manageButton->registerHandler(new $addButtonHandlerClass($this->name));
		
			$this->window->registerComponent($this->manageButton);
		}
		
		$this->foreignKey = true;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setShowAddButton
	//-------------------------------------------------------------------

	/**
	 * Sets whether or not to show the add button.
	 *
	 * @access public
     * @param bool $showAddButton To show the add button or not to show the add button.
     */

	function setShowAddButton($showAddButton)
	{
		$this->showAddButton = $showAddButton;
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
		$application = &$_SESSION['application'];
		
		if ($application->hasArgument($this->name))
		{	
			$this->setValue($application->getArgument($this->name));
			$application->clearArgument($this->name);
		}
		
		$editHTML = '<nobr>' . parent::getEditHTML();

		if ($this->showAddButton)
		{
			$editHTML .= $this->manageButton->getHTML();
		}
		$editHTML .= '</nobr>';
		
		return $editHTML;
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
		$size = sizeof($this->options);
	
		for ($i = 0; $i < $size; $i++)
		{
			$valueArray = $this->options[$i];
			
			if (strcmp("$valueArray[0]", $this->getValue()) == 0)
			{
				return $valueArray[1];
			}
		}
		
		unset($size);
		
		return $this->unselectedValue;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getRecord
	//-------------------------------------------------------------------
	
	/** 
	 * This returns a record from the related table.
	 *
	 * @access public
	 * @return CC_Record A record from the related table.
	 */
	 
	function getRecord()
	{
		return new CC_Record($fieldList, $this->relateTableName, $editable = false, $this->getValue());
	}

	
	//-------------------------------------------------------------------
	// METHOD: getViewHTML
	//-------------------------------------------------------------------

	/** 
	 * Returns HTML with the display value of the field for viewing. 
	 *
	 * @access public
	 * @return string The HTML for the field.
	 */

	function getViewHTML()
	{
		return $this->getDisplayValue();
	}
}

?>
