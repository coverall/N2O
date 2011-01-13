<?php
// $Id: CC_Foreign_Key_Multiple_Field.php,v 1.7 2010/11/11 04:28:32 patrick Exp $
//=======================================================================
// CLASS: CC_Foreign_Key_Multiple_Field
//=======================================================================

/**
 * One-to-many fields require a table which creates a "set" linking many records in one table (the "source" table) to a single record in the table where this field belongs.
 *
 * The set table MUST have the following structure/create statement:
 *
 * create table [SET TABLE NAME]
 * (
 *		ID int not null default 0 auto_increment,
 *		FK_ID int not null default 0,
 *		primary key (ID, FK_ID)
 * );
 *
 * @package CC_Fields
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

class CC_Foreign_Key_Multiple_Field extends CC_Field
{
	/**
     * The name of the table which contains the sets.
     *
     * @var string $setTable
     * @access private
     */
    
    var $setTable;


	/**
     * The key name in the set table that refers to the main table.
     *
     * @var string $setTableMainKey
     * @access private
     */
    
    var $setTableMainKey;


	/**
     * The key name in the set table that refers to the source table.
     *
     * @var string $setTableSourceKey
     * @access private
     */
    
    var $setTableSourceKey;

	
	/**
     * The name of the table which contains the source data.
     *
     * @var string $sourceTable
     * @access private
     */
     
	var $sourceTable;


	/**
     * The name of the column in the source table to use for display.
     *
     * @var string $displayColumn
     * @access private
     */
     
	var $displayColumn;
	
	
	/**
     * The array of selected entries.
     *
     * @var array $options
     * @access private
     */
     
	var $options = array();
	
	
	/**
	 * A button to add a new record to the related table.
	 *
     * @var CC_Button $manageButton
     * @access private
     */
     
	var $manageButton;
	
	
	/**
	 * A button to view selected records in the related table.
	 *
     * @var CC_Button $viewButton
     * @access private
     */
     
	var $viewButton;
	
	
	/**
     * If this is true, we need to update the database to reflect the user's addition.
     *
     * @var bool $optionsUpdated
     * @access private
     */
     
	var $optionsUpdated = false;


	/**
     * If this is true, we need to update the database to reflect the user's addition.
     *
     * @var bool $optionsUpdated
     * @access private
     */
     
	var $checkboxes = null;


	/**
     * The number of selections required
     *
     * @var int $minRequired
     * @access private
     */
     
	var $minRequired = 0;


	/**
     * The number of columns in the checkbox display
     *
     * @var int $numColumns
     * @access private
     */
     
	var $numColumns = 1;


	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Foreign_Key_Multiple_Field
	//-------------------------------------------------------------------

	/** 
	 * The CC_Foreign_Key_Field constructor sets its values here, yo. 
	 *
	 * @access public
	 * @param string $name The unique name of the field. Names must be unique so that N2O knows which fields to update when users submit data.
	 * @param string $label A text label to accompany the field to describe which input is either expected or displayed.
	 * @param string $setTable The name of the set table.
	 * @param string $sourceTable The name of the source table.
	 * @param string $displayColumn The name of the column in the source table to use a display column.
	 * @param bool $required Whether or not this field must contain data from the user before proceeding. Not required by default.
	 * @param string $defaultValue The value the field should contain before user's submit input. -1 by default.
	 */

	function CC_Foreign_Key_Multiple_Field($name, $label, $setTable, $setTableMainKey, $setTableSourceKey, $sourceTable, $displayColumn, $minRequired, $numColumns)
	{
		$this->setTable = $setTable;
		$this->sourceTable = $sourceTable;
		$this->displayColumn = $displayColumn;
		$this->setTableMainKey = $setTableMainKey;
		$this->setTableSourceKey = $setTableSourceKey;
		$this->minRequired = $minRequired;
		$this->numColumns = $numColumns;
		
		$this->CC_Field($name, $label, true, -1);

		$this->setAddToDatabase(false);
				
		$handlerClass = $this->getSelectButtonHandlerClass();
		
		$handler = new $handlerClass($this);
		$viewHandler = new $handlerClass($this, true);
		
		$this->manageButton = new CC_Button("Select $label");
		$this->manageButton->registerHandler($handler);
		$this->manageButton->setValidateOnClick(false);

		$this->viewButton = new CC_Text_Button("View $label");
		$this->viewButton->registerHandler($viewHandler);
		$this->viewButton->setValidateOnClick(false);
		
		$application = &$_SESSION['application'];
		
		$window = &$application->getCurrentWindow();
		$window->registerComponent($this->manageButton);
		$window->registerComponent($this->viewButton);
			
		$this->generateCheckboxes();
	}
	

	//-------------------------------------------------------------------
	// METHOD: generateCheckboxes
	//-------------------------------------------------------------------
	
	function generateCheckboxes()
	{
		global $application;
		$window = $application->getCurrentWindow();
		
		$this->generateOptions();
		
		$numOptions = sizeof($this->options);
		
		for ($i = 0; $i < $numOptions; $i++)
		{
			$id = $this->options[$i][0];
			$label = $this->options[$i][1];
			
			$checkbox = new CC_Checkbox_Field($this->setTable . '_' . $id, $label, false);
			
			$this->checkboxes[$id] = &$checkbox;
		}		
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: register
	//-------------------------------------------------------------------
	
	function register(&$window)
	{
		$keys = array_keys($this->checkboxes);
		
		for ($i = 0; $i < sizeof($keys); $i++)
		{
			$window->registerField($this->checkboxes[$keys[$i]]);
		}		

		$window->registerField($this);
	}


	//-------------------------------------------------------------------
	// METHOD: generateOptions
	//-------------------------------------------------------------------

	function generateOptions()
	{
		global $application;
		
		//get options
		$query = 'select ID, ' . $this->displayColumn . ' from ' . $this->sourceTable . ' order by ' . $this->displayColumn;
		
		$result = $application->db->doSelect($query);
		
		while ($row = cc_fetch_row($result))
		{
			$this->options[] = array($row[0], $row[1]);
		}
	}


	//-------------------------------------------------------------------
	// METHOD: getSelectButtonHandlerClass
	//-------------------------------------------------------------------
	
	/** 
	 * This method defines which class will be used for the Select Button. (This way subclasses can easily change that.)
	 *
	 * @return string The handler class' name.
	 */

	function getSelectButtonHandlerClass()
	{
		return 'CC_Manage_OneToMany_Handler';
	}


	//-------------------------------------------------------------------
	// METHOD: getEditHTML
	//-------------------------------------------------------------------

	/** 
	 * Returns HTML for a 'select' form field with multiple selections based on the records in the source table. 
	 *
	 * @access public
	 * @return string The HTML for the mulitple select list.
	 */

	function getEditHTML()
	{
		$html = '<table cellpadding="0" cellspacing="0" border="0" class="ccForeignKeyMultipleField">';

		$keys = array_keys($this->checkboxes);
		
		$html .= '<tr>';
				
		for ($i = 0; $i < sizeof($keys); $i++)
		{
			$html .= '<td>' . $this->checkboxes[$keys[$i]]->getHTML() . ' ' . $this->checkboxes[$keys[$i]]->getLabel() . '</td>';
			
			if (($i + 1) % $this->numColumns == 0)
			{
				$html .= '</tr><tr>';
			}
		}
		
		$html .= '</tr></table>';
		
		/*
			$html = "<table border=\"0\"><tr valign=\"top\"><td>";
			
			$numberOfOptions = sizeof($this->options);
			
			if ($numberOfOptions > 10)
			{
				$numberOfOptions = 10;
			}
			
			$html .= "<select name=\"" . $this->getRecordKey() . "$this->name\" size=\"$numberOfOptions\" multiple>\n";
	
			$keys = array_keys($this->options);
					
			for ($i = 0; $i < sizeof($keys); $i++)
			{
				$html .= " <option value=\"" . $keys[$i] . "\"";
				$html .= ">" . $this->options[$keys[$i]] . "</option>\n";
			}
			
			$html .= "</select></td>";
			$html .= "<td>" . $this->manageButton->getHTML() . "</td></tr></table>\n";
	
			return $html;
		*/
	
 		return $html;
	}
	

	//-------------------------------------------------------------------
	// METHOD: getViewHTML
	//-------------------------------------------------------------------

	/** 
	 * Returns HTML as a comma-delimited list of records that are in the set. 
	 *
	 * @access public
	 * @return string The HTML for the field.
	 */

	function getViewHTML()
	{
		$html = '';
		
		$keys = array_keys($this->checkboxes);
		
		for ($i = 0; $i < sizeof($keys); $i++)
		{
			if ($this->checkboxes[$keys[$i]]->isChecked())
			{
				$html .= $this->checkboxes[$keys[$i]]->getLabel() . ', ';			
			}
		}
		
		/*
			$html = "<table border=\"0\"><tr valign=\"top\"><td>";
			
			$numberOfOptions = sizeof($this->options);
			
			if ($numberOfOptions > 10)
			{
				$numberOfOptions = 10;
			}
			
			$html .= "<select name=\"" . $this->getRecordKey() . "$this->name\" size=\"$numberOfOptions\" multiple>\n";
	
			$keys = array_keys($this->options);
					
			for ($i = 0; $i < sizeof($keys); $i++)
			{
				$html .= " <option value=\"" . $keys[$i] . "\"";
				$html .= ">" . $this->options[$keys[$i]] . "</option>\n";
			}
			
			$html .= "</select></td>";
			$html .= "<td>" . $this->manageButton->getHTML() . "</td></tr></table>\n";
	
			return $html;
		*/
	
 		return substr($html, 0, strlen($html) - 2);
	}


	//-------------------------------------------------------------------
	// METHOD: updateOptions
	//-------------------------------------------------------------------
	
	/** 
	 * This method updates the options based on the contents of the set. The select list is updated with the latest selected options.
	 *
	 * @access public
	 * @param array $idArray An array of selected ids.
	 */
	 
	function updateOptions($idArray)
	{
		$this->options = array();
		
		if (sizeof($idArray) == 0)
		{
			$this->options[-1] = "No $this->label";
		}
		else
		{
			$application = &$_SESSION['application'];
			
			$db = &$application->db;
			
			$query = "select ID, $this->displayColumn from $this->sourceTable where ID in (";
			
			for ($i = 0; $i < sizeof($idArray); $i++)
			{
			
				$query .= $idArray[$i];
				
				if ($i + 1 < sizeof($idArray))
				{
					$query .= ", ";	
				}
			}
			
			$query .= ")";
			
			$result = $db->doSelect($query);
			
			while ($row = cc_fetch_row($result))
			{
				$this->options[$row[0]] = $row[1];
			}
		}

		$this->optionsUpdated = true;
	}


	//-------------------------------------------------------------------
	// METHOD: setValue
	//-------------------------------------------------------------------

	/** 
	 * The method sets the field's value as an integer representing the ID in the set table. 
	 *
	 * @access public
	 * @param int $value The ID of the set in the set table.
	 */
	 
	function setValue($value = 0, $recordId = -1)
	{
		global $application;
		
		$this->value = $value;
		
		if ($value != "-1")
		{	
			$query = 'select ' . $this->setTableSourceKey . ' from ' . $this->setTable . ' where ' . $this->setTableMainKey . ' = ' . $recordId;
			$result = $application->db->doSelect($query);
			
			while ($row = cc_fetch_row($result))
			{
				if (array_key_exists($row[0], $this->checkboxes))
				{
					$this->checkboxes[$row[0]]->setValue(1);
				}
			}			
		}
	}


	//-------------------------------------------------------------------
	// METHOD: getValue
	//-------------------------------------------------------------------

	/** 
	 * The method returns the field's value as an integer representing the ID in the set table. 
	 *
	 * @access public
	 * @return int The ID of the set in the set table.
	 */

	function getValue()
	{
		if ($this->optionsUpdated)
		{
			$application = &$_SESSION['application'];
			
			$db = &$application->db;
			
			$keys = array_keys($this->options);
			
			// if we are a new entry, insert one record to get our id
			if ($this->value == "-1")
			{
				$query = "insert into $this->setTable (FK_ID) values (" . $keys[0] . ")";
				
				$this->value = $db->doInsert($query);
				
				$startIndex = 1;
			}
			else
			{
				$startIndex = 0;
			}
			
			// now insert the rest
			for ($i = $startIndex; $i < sizeof($keys); $i++)
			{
				$query = "insert into $this->setTable (ID, FK_ID) values (" . $this->value . ", " . $keys[$i] . ")";
				
				$db->doInsert($query);
			}
		
			$this->optionsUpdated = false;
		}
		
		return $this->value;
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
		global $application;
		$window = &$application->getCurrentWindow();
		
		$numSelected = 0;
		
		$keys = array_keys($this->checkboxes);
		
		for ($i = 0; $i < sizeof($keys); $i++)
		{
			if ($this->checkboxes[$keys[$i]]->isChecked())
			{
				$numSelected++;
			}
		}
		
		if ($numSelected < $this->minRequired)
		{
			$this->setErrorMessage('This field expects at least ' . $this->minRequired . ' selection(s)!', CC_FIELD_ERROR_CUSTOM) ;
			return false;
		}
		else
		{
			$this->clearAllErrors();
			return true;
		}
		
	/*
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
	*/
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
		$addHandler = (isset($args->addHandler) ? $args->addHandler : '');
		$orderBy = (isset($args->orderBy) ? $args->orderBy : false);
		$whereClause = (isset($args->whereClause) ? $args->whereClause : false);
		$setTable = (isset($args->setTable) ? $args->setTable : false);
		$setTableMainKey = (isset($args->setTableMainKey) ? $args->setTableMainKey : false);
		$setTableSourceKey = (isset($args->setTableSourceKey) ? $args->setTableSourceKey : false);
		$sourceTable = (isset($args->sourceTable) ? $args->sourceTable : false);
		$displayColumn = (isset($args->displayColumn) ? $args->displayColumn : false);
		$minRequired = (isset($args->minRequired) ? $args->minRequired : 0);
		$numColumns = (isset($args->numColumns) ? $args->numColumns : 1);
		
		$field = &$application->relationshipManager->getOneToManyField($name, $value, $label, $showAdd, $addHandler, $whereClause, $orderBy, $setTable, $setTableMainKey, $setTableSourceKey, $sourceTable, $displayColumn, $minRequired, $numColumns);

		unset($showAdd, $addHandler, $orderBy, $whereClause, $setTable, $setTableMainKey, $setTableSourceKey, $sourceTable, $displayColumn, $minRequired, $numColumns);

		return $field;
	}
}

?>