<?php
// $Id: CC_Delete_Record_Handler.php,v 1.8 2004/08/01 00:00:41 mike Exp $
//=======================================================================


//=======================================================================
// CLASS: CC_Delete_Record_Handler
//=======================================================================

/**
 * This CC_Action_Handler sets the value for a CC_Percentage_Field at 100% (ie. as complete).
 * 
 * @package CC_Action_Handlers
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 * @see CC_Percentage_Filter
 * @see CC_Percentage_Field
 */

class CC_Delete_Record_Handler extends CC_Action_Handler
{
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
	// CONSTRUCTOR: CC_Delete_Record_Handler
	//-------------------------------------------------------------------

	/**
	 * This method sets the parameters of the handler for use in the process method.
	 *
	 * @access public
	 * @param string $tableNameToDelete The table name to delete from.
	 * @param int $recordIdToDelete The id of the record to delete.
	 */

	function CC_Delete_Record_Handler($tableNameToDelete, $recordIdToDelete)
	{	
		$application = &$_SESSION['application'];
		
		$this->tableNameToDelete = $tableNameToDelete;
		$this->recordIdToDelete = $recordIdToDelete;
		
		$this->deleteAction = $application->getLastAction();
		$this->currentAction = $application->getAction();
				
		$this->CC_Action_Handler();
	}


	//-------------------------------------------------------------------
	// METHOD: process()
	//-------------------------------------------------------------------

	/**
	 * This method deletes the record and sets the application to display the previous window. It doesn't process if the user clicks more than once.
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
			
			$deleteQuery = "delete from " . $this->tableNameToDelete . " where " . $deletedRecord->idColumnName . "='" . $this->recordIdToDelete . "'";
	
			$application->db->doDelete($deleteQuery);
			
			$application->setAction($this->deleteAction);
		}
	}
}

?>