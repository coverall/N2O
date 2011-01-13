<?php
// $Id: CC_Edit_Ordered_Record_Handler.php,v 1.5 2003/07/07 05:13:51 jamie Exp $
//=======================================================================

/**
 * This CC_Action_Handler displays the CC_Edit_Ordered_Record_Window.
 * 
 * @package CC_Action_Handlers
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 * @see CC_Edit_Ordered_Record_Window.php
 */

class CC_Edit_Ordered_Record_Handler extends CC_Action_Handler
{
	/**
	 * The array if record ids in the currently sorted order.
	 *
	 * @access private
	 * @var array $sortArray
	 */

	var $sortArray;


	/**
	 * The id of the record we are editing.
	 *
	 * @access private
	 * @var int $recordId
	 */

	var $recordId;

	/**
	 * The name of the table we are editing.
	 *
	 * @access private
	 * @var string $tableName
	 */

	var $tableName;


	/**
	 * The type of record we are editing, for display purposes. Defaults to 'Record'.
	 *
	 * @access private
	 * @var string $displayName
	 */

	var $displayName;
	
	
	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Edit_Ordered_Record_Handler
	//-------------------------------------------------------------------

	/**
	 * This method sets the parameters of the handler for use in the process method.
	 *
	 * @access public
	 * @param string $tableName The table name to edit.
	 * @param int $recordId The id of the record to edit.
	 * @param array $sortArray An ordered array of record ids.
	 * @param int $displayName The display name for the record.
	 */

	function CC_Edit_Ordered_Record_Handler($tableName, $recordId, &$sortArray, $displayName = 'Record')
	{	
		$this->tableName = $tableName;
		$this->recordId = $recordId;
		$this->sortArray = &$sortArray;
		$this->displayName = &$displayName;
		
		$this->CC_Action_Handler();
	}


	//-------------------------------------------------------------------
	// METHOD: process()
	//-------------------------------------------------------------------

	/**
	 * This method sets the application to display the CC_Edit_Ordered_Record_Window for a record in the specified table at the specified position. The window will also be passed the record's display name for display.
	 *
	 * @access public
	 */

	function process()
	{
		$application = &$_SESSION['application'];

		$application->unregisterWindow(CC_FRAMEWORK_PATH . '/CC_Windows/CC_Edit_Ordered_Record_Window');
		
		$application->setAction(CC_FRAMEWORK_PATH . '/CC_Windows/CC_Edit_Ordered_Record_Window?tableNameForEdit=' . $this->tableName . '&editRecordId=' . $this->recordId . '&displayNameForEditOrdered=' . $this->displayName);
	}
}

?>