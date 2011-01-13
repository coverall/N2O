<?php
//=======================================================================

/**
 * This CC_Action_Handler displays all summary record windows edit,delete,
 * add and view.
 * 
 * @package CC_Action_Handlers
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 * @see CC_Edit_Record_Window.php
 */

class CC_Summary_Record_Handler extends CC_Action_Handler
{
	/**
	 * The id of the record we are dealing with.
	 *
	 * @access private
	 * @var int $recordId
	 */

	var $recordId = -1;

	/**
	 * The name of the table we are dealing with.
	 *
	 * @access private
	 * @var string $tableName
	 */

	var $tableName;


	/**
	 * The type of record we are dealing with, for display purposes. Defaults to 'Record'.
	 *
	 * @access private
	 * @var string $displayName
	 */

	var $displayName;
	
	
	/**
	 * The label of the CC_Summary button that was clicked. 
	 * (ie. Add, Edit, Delete or View).
	 *
	 * @access private
	 * @var string $label
	 */

	var $label;
	
	
	/**
     * The name of the column to use as the "ID".
     *
     * @var string $idColumn
     * @access private
     */	

	var $idColumn = 'ID';
	
		
	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Summary_Record_Handler
	//-------------------------------------------------------------------

	/**
	 * This method sets the parameters of the handler for use in the process method.
	 *
	 * @access public
	 * @param string $tableName The table name to edit.
	 * @param int $recordId The id of the record to edit.
	 * @param int $displayName The display name for the record.
	 * @param string $targetWidow The window to go to in order to process the record.
	 * @param string $label The button label that was clicked (ie. Edit, Delete or View).
	 * @param string $idColumn The name of the primary key column.
	 */

	function CC_Summary_Record_Handler($tableName, $recordId, $displayName, $targetWindow, $label, $idColumn = 'ID')
	{	
		$this->tableName = $tableName;
		$this->recordId = $recordId;
		$this->displayName = $displayName;
		$this->targetWindow = $targetWindow;
		$this->label = $label;
		$this->idColumn = $idColumn;
		
		/*
		trigger_error('tableName is ' . $tableName . "\n",E_USER_WARNING);
		trigger_error('recordId is ' . $recordId . "\n",E_USER_WARNING);
		trigger_error('displayName is ' . $displayName . "\n",E_USER_WARNING);
		trigger_error('targetWindow is ' . $targetWindow . "\n",E_USER_WARNING);
		trigger_error('label is ' . $label . "\n",E_USER_WARNING);
		*/
		
		$this->CC_Action_Handler();
	}


	//-------------------------------------------------------------------
	// METHOD: process()
	//-------------------------------------------------------------------

	/**
	 * This method sets the application to display the appropriate window for a record in the specified table at the specified position. The window will also be passed the record's display name for display.
	 *
	 * @access public
	 */

	function process()
	{
		$application = &$_SESSION['application'];
		
		$application->unregisterWindow($this->targetWindow);
		
		$questionMarkOrAmpersand = '?';
		
		if (strpos($this->targetWindow, '?') !== false)
		{
			$questionMarkOrAmpersand = '&';
		}

		$application->setAction($this->targetWindow . $questionMarkOrAmpersand . 'tableNameFor' . $this->label . '=' . $this->tableName . '&' . strtolower($this->label) . 'RecordId=' . $this->recordId . '&displayNameFor' . $this->label . '=' . $this->displayName . '&idColumn=' . $this->idColumn);
	}
}

?>