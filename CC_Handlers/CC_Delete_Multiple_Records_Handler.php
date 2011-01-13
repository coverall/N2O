<?php
// $Id: CC_Delete_Multiple_Records_Handler.php,v 1.6 2003/10/05 09:47:40 jamie Exp $
//=======================================================================

/**
 * This CC_Action_Handler deletes multiple records from the database and maintains order.
 * 
 * @package CC_Action_Handlers
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 * @see CC_Delete_Multiple_Confirm_Window.php
 */

class CC_Delete_Multiple_Records_Handler extends CC_Action_Handler
{
	/**
	 * An array of record rows to delete.
	 *
	 * @access private
	 * @var int $recordsToDelete
	 */

	var $recordsToDelete;

	/**
	 * The name of the table we are deleting from.
	 *
	 * @access private
	 * @var string $tableNameToDelete
	 */

	var $tableNameToDelete;
	

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
	// CONSTRUCTOR: CC_Delete_Multiple_Records_Handler
	//-------------------------------------------------------------------

	/**
	 * This method sets the parameters of the handler for use in the process method.
	 *
	 * @access public
	 * @param string $tableNameToDelete The table name to delete from.
	 * @param array $recordsToDelete An array of recird ids to delete.
	 */

	function CC_Delete_Multiple_Records_Handler($tableNameToDelete, $recordsToDelete)
	{	
		$application = &$_SESSION['application'];
		
		$this->tableNameToDelete = $tableNameToDelete;
		$this->recordsToDelete = $recordsToDelete;
		
		$this->deleteAction = $application->getLastAction();
		$this->currentAction = $application->getAction();
				
		$this->CC_Action_Handler();
	}


	//-------------------------------------------------------------------
	// METHOD: process()
	//-------------------------------------------------------------------

	/**
	 * This method deletes the records and sets the application to display the previous window. It doesn't process if the user clicks more than once.
	 *
	 * @access public
	 * @param bool $multipleClick Whether or not the user clicked more than once.
	 */

	function process($multipleClick)
	{
		if ($multipleClick === false)
		{
			$application = &$_SESSION['application'];
			
			// cycle through the fields in the record and call the cleanup method
	
			for ($r = 0; $r < sizeof($this->recordsToDelete); $r++)
			{
				
				$deletedRecord = new CC_Record(getFieldListFromTable($this->tableNameToDelete, array('ID', 'DATE_ADDED', 'LAST_MODIFIED')), $this->tableNameToDelete, true, $this->recordsToDelete[$r]['ID']);
				
				$keys = array_keys($deletedRecord->fields);
				
				for ($i = 0 ; $i < sizeof($keys); $i++)
				{
					$field = &$deletedRecord->getField($keys[$i]);
					$field->deleteCleanup();
					unset($field);
				}
				
				$deleteQuery = "delete from " . $this->tableNameToDelete . " where ID='" . $this->recordsToDelete[$r]['ID'] . "'";
		
				$application->db->doDelete($deleteQuery);
				
				unset($deleteRecord, $deleteQuery);
			}
					
			$application->setAction($this->deleteAction);
		}
	}
}

?>