<?php
// $Id: CC_Delete_Ordered_Record_Handler.php,v 1.7 2006/05/19 00:28:37 jamie Exp $
//=======================================================================

/**
 * This CC_Action_Handler deletes a record from the database and maintains order.
 * 
 * @package CC_Action_Handlers
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 * @see CC_Delete_Ordered_Record_Window.php
 */

class CC_Delete_Ordered_Record_Handler extends CC_Action_Handler
{
	/**
	 * The position of the record to delete.
	 *
	 * @access private
	 * @var int $deleteSortId
	 */

	var $deleteSortId;


	/**
	 * The id of the record to delete.
	 *
	 * @access private
	 * @var int $recordIdToDelete
	 */

	var $recordIdToDelete;

	/**
	 * The name of the table we are deleting from.
	 *
	 * @access private
	 * @var string $tableNameToDelete
	 */

	var $tableNameToDelete;
	

	/**
	 * The record we are adding.
	 *
	 * @access private
	 * @var CC_Record $recordToUpdate
	 */
	
	var $recordToUpdate;

	/**
	 * This is used to get the window object.
	 *
	 * @access private
	 * @var string $currentAction
	 */
	
	var $currentAction;

	/**
	 * The screen to go to when the record has been deleted. It is set to the previous screen.
	 *
	 * @access private
	 * @var string $deleteAction
	 */
	
	var $deleteAction;
		
	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Delete_Ordered_Record_Handler
	//-------------------------------------------------------------------

	/**
	 * This method sets the parameters of the handler for use in the process method.
	 *
	 * @access public
	 * @param string $tableNameToDelete The table name to delete from.
	 * @param int $recordIdToDelete The id of the record to delete.
	 * @param int $deleteSortId The sort position for the deleted record.
	 */

	function CC_Delete_Ordered_Record_Handler($tableNameToDelete, $recordIdToDelete, $deleteSortId)
	{	
		$application = &$_SESSION['application'];
		
		$this->tableNameToDelete = $tableNameToDelete;
		$this->recordIdToDelete = $recordIdToDelete;
		$this->deleteSortId = $deleteSortId;
		
		$this->deleteAction = $application->getLastAction();
		$this->currentAction = $application->getAction();
				
		$this->CC_Action_Handler();
	}


	//-------------------------------------------------------------------
	// METHOD: process()
	//-------------------------------------------------------------------

	/**
	 * This method deletes the record based on its order and sets the application to display the previous window. It doesn't process if the user clicks more than once.
	 *
	 * @access public
	 * @param bool $multipleClick Whether or not the user clicked more than once.
	 */

	function process($multipleClick = false)
	{
		if ($multipleClick === false)
		{
			$application = &$_SESSION['application'];
			
			// cycle through the fields in the record and call the cleanup method
			$this->sourceWindow = &$application->getWindow($this->currentAction);
			
			$deletedRecord = &$this->sourceWindow->getRecord();
			
			$keys = array_keys($deletedRecord->fields);
			
			for ($i = 0 ; $i < sizeof($keys); $i++)
			{
				$field = &$deletedRecord->getField($keys[$i]);
				$field->deleteCleanup();			
			}
			
			error_log('table: ' . $this->tableNameToDelete);
			error_log('record id: ' . $this->recordIdToDelete);
			error_log('sort id: ' . $this->deleteSortId);
			
			$application->db->doOrderedDelete($this->tableNameToDelete, $this->recordIdToDelete, $this->deleteSortId);
					
			$application->setAction($this->deleteAction);	
		}
	}
}

?>