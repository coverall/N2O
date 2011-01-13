<?php
// $Id: CC_Delete_Ordered_Confirm_Handler.php,v 1.7 2003/07/07 05:13:51 jamie Exp $
//=======================================================================

/**
 * This CC_Action_Handler processes an 'Delete Record' button and calls the CC_Delete_Ordered_Confirm_Window.
 * 
 * @package CC_Action_Handlers
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 * @see CC_Delete_Ordered_Confirm_Window.php
 */

class CC_Delete_Ordered_Confirm_Handler extends CC_Action_Handler
{
	/**
	 * The array if record ids in the currently sorted order.
	 *
	 * @access private
	 * @var array $sortArray
	 */

	var $sortArray;


	/**
	 * The id of the record we are viewing.
	 *
	 * @access private
	 * @var int $recordId
	 */

	var $recordId;

	/**
	 * The name of the table we are deleting from.
	 *
	 * @access private
	 * @var string $tableName
	 */

	var $tableName;


	/**
	 * The type of record we are adding, for display purposes. Defaults to 'Record'.
	 *
	 * @access private
	 * @var string $displayName
	 */

	var $displayName;


	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Add_Ordered_Record_Handler
	//-------------------------------------------------------------------
	
	/**
	 * This method sets the parameters of the handler for use in the process method.
	 *
	 * @access public
	 * @param string $tableName The table name.
	 * @param int $recordId The id of the record to delete.
	 * @param array $sortArray The ordered array of record ids.
	 * @param string $displayName The display name for records.
	 */

	function CC_Delete_Ordered_Confirm_Handler($tableName, $recordId, &$sortArray, $displayName = 'Record')
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
	 * This method sets the application to display the CC_Delete_Ordered_Record_Window for a record in the specified table at the specified position. The window will also be passed the record's display name for display. It doesn't process if the user clicks more than once.
	 *
	 * @access public
	 * @param bool $multipleClick Whether or not the user clicked more than once.
	 */

	function process($multipleClick)
	{
		if ($multipleClick === false)
		{
			$application = &$_SESSION['application'];
			
			$setAction = $this->getSetActionFunction();
			$unregisterAction = $this->getUnregisterWindowFunction();
			
			$application->$unregisterAction($this->getDeleteAction());
			
			$application->setAction($this->getDeleteAction() . '?tableNameForDelete=' . $this->tableName . '&deleteRecordId=' . $this->recordId . '&deleteSortId=' . array_search($this->recordId, $this->sortArray) . '&displayNameForDeleteOrdered=' . $this->displayName); 
		}
	}


	//-------------------------------------------------------------------
	// METHOD: getDeleteAction()
	//-------------------------------------------------------------------
	
	/** 
	 * This function will return the action to be used. Override this if you want to change it.
	 *
	 * @access public
	 */

	function getDeleteAction()
	{
		return CC_FRAMEWORK_PATH . '/CC_Windows/CC_Delete_Ordered_Confirm_Window';
	}


	//-------------------------------------------------------------------
	// METHOD: getSetActionFunction
	//-------------------------------------------------------------------
	
	/** 
	 * This function the name of the function to use to set the action. If your application has alternate functions (ie. setUserAction()) then you can override this.
	 *
	 * @access public
	 */

	function getSetActionFunction()
	{
		return 'setAction';
	}


	//-------------------------------------------------------------------
	// METHOD: getUnregisterWindowFunction
	//-------------------------------------------------------------------
	
	/** 
	 * This function the name of the function to use to unregister the window. If your application has alternate functions (ie. unregisterUserWindow()) then you can override this.
	 *
	 * @access public
	 */

	function getUnregisterWindowFunction()
	{
		return 'unregisterWindow';
	}
}

?>