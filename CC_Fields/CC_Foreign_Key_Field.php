<?php
// $Id: CC_Foreign_Key_Field.php,v 1.31 2010/11/11 04:28:32 patrick Exp $
//=======================================================================
// CLASS: CC_Foreign_Key_Field
//=======================================================================

/**
 * The CC_Foreign_Key_Field field represents a selection of values from a separate, but related database table.
 *
 * In addition to what's supported globally by all CC_Fields (see documentation), this field supports the following arguments for the fourth argument of CC_FieldManager's addField() method:
 *
 * showAdd=[0/1] - Should we show an add button?<br>
 * addHandler=[string] - The class name for the handler for the add button.<br>
 * orderBy=[string] - The column to order by in the query.<br>
 * whereClause=[string] - An optional where clause for the query (eg. whereClause=where ID > 10)<br>
 * unselectedValue=[string] - The string for the unselected value in the select list. Defaults to "- Select -". If you set it to nothing, it won't be displayed.<br>
 *
 * @package CC_Fields
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

class CC_Foreign_Key_Field extends CC_SelectList_Field
{
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


	/**
	 * The add button handler class for adding new elements
	 *
     * @var string $addButtonHandlerClass
     * @access private
     */
     
     var $addButtonHandlerClass;

	
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
	
		$this->CC_SelectList_Field($name, $label, $required, $defaultValue, $unselectedValue, $theOptions);
		
		$this->relatedTableName = $relatedTableName;
		$this->setShowAddButton($showAddButton);

		$this->showAddButton = $showAddButton;
		$this->addButtonHandlerClass = $addButtonHandlerClass;

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
		global $application;
		
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
	// METHOD: register
	//-------------------------------------------------------------------

	/**
	 * This is a callback method that gets called by the window when the
	 * component is registered. It's up to the component to decide which
	 * registerXXX() method it should call on the window. Should your
	 * custom component consist of multiple components, you may need to
	 * make multiple calls.
	 *
	 * @access public
	 */

	function register(&$window)
	{
		parent::register($window);
		
		if ($this->showAddButton)
		{
			$this->manageButton = new CC_Button('Add ' . $this->label);
		
			$this->manageButton->setValidateOnClick(false);
			$this->manageButton->setFieldUpdater(true);
			
			$this->manageButton->registerHandler(new $this->addButtonHandlerClass($this->name));
		
			$window->registerComponent($this->manageButton);
		}
		
		unset($this->addButtonHandlerClass);
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

	static function getInstance($className, $name, $label, $value, $args, $required)
	{
		global $application;
		
		$showAdd = (isset($args->showAdd) ? $args->showAdd : false);
		$addHandler = (isset($args->addHandler) ? $args->addHandler : 'CC_Manage_FK_Table_Handler');
		$orderBy = (isset($args->orderBy) ? $args->orderBy : false);
		$whereClause = (isset($args->whereClause) ? $args->whereClause : false);
		$sourceTable = (isset($args->sourceTable) ? $args->sourceTable : false);
		$displayColumn = (isset($args->displayColumn) ? $args->displayColumn : false);
		$required = (isset($args->required) ? $args->required : 0);
		$unselectedValue = (isset($args->unselectedValue) ? $args->unselectedValue : '- Select -');
		
		if (!$application->relationshipManager->getRelatedTable($name))
		{
			$application->relationshipManager->addRelationship($name, $sourceTable, $displayColumn);
		}

		$field = &$application->relationshipManager->getField($name, $value, $label, $showAdd, $orderBy, $addHandler, $whereClause, $unselectedValue, $displayColumn, $required);

		unset($showAdd, $addHandler, $orderBy, $whereClause, $sourceTable, $displayColumn, $required);

		return $field;
	}
}

?>
