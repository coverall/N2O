<?php
// $Id: CC_Insert_Ordered_Record_Handler.php,v 1.7 2004/07/31 00:49:40 mike Exp $
//=======================================================================

/**
 * This CC_Action_Handler adds a record to the database and maintains order.
 * 
 * @package CC_Action_Handlers
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 * @see CC_Add_Ordered_Record_Window.php
 */

class CC_Insert_Ordered_Record_Handler extends CC_Action_Handler
{
	/**
	 * The position to insert the record.
	 *
	 * @access private
	 * @var int $insertPosition
	 */

	var $insertPosition;


	/**
	 * The id of the record we insert which is returned after we insert it.
	 *
	 * @access private
	 * @var int $insertedRecordId
	 */

	var $insertedRecordId;

	/**
	 * The name of the table we are adding to.
	 *
	 * @access private
	 * @var string $tableName
	 */

	var $tableName;
	

	/**
	 * The record we are adding.
	 *
	 * @access private
	 * @var CC_Record $recordToUpdate
	 */
	
	var $recordToUpdate;

	/**
	 * The screen to go to when the record has been inserted. It is set to the previous screen.
	 *
	 * @access private
	 * @var string $insertAction
	 */
	
	var $insertAction;


	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Insert_Ordered_Record_Handler
	//-------------------------------------------------------------------

	/**
	 * This method sets the parameters of the handler for use in the process method.
	 *
	 * @access public
	 * @param CC_Record $recordToUpdate The record to insert.
	 * @param string $tableName The table name to insert the record into.
	 * @param int $insertPosition The insert position for the new record.
	 */

	function CC_Insert_Ordered_Record_Handler(&$recordToUpdate, $tableName, $insertPosition = 0)
	{	
		$application = &$_SESSION['application'];
		
		$this->recordToUpdate = &$recordToUpdate;
		$this->tableName = $tableName;
		
		$this->insertAction = $application->getLastAction();
		$this->insertPosition = $insertPosition;
		
		$this->CC_Action_Handler();
	}


	//-------------------------------------------------------------------
	// METHOD: process()
	//-------------------------------------------------------------------

	/**
	 * This method inserts the record based on its order and sets the application to display the previous window. It doesn't process if the user clicks more than once.
	 *
	 * @access public
	 * @param bool $multipleClick Whether or not the user clicked more than once.
	 */

	function process($multipleClick = false)
	{
		if ($multipleClick === false)
		{
			$application = &$_SESSION['application'];
			
			$this->insertedRecordId = $application->db->doOrderedInsert($this->recordToUpdate->buildOrderedInsertQuery($this->insertPosition), $this->tableName, $this->insertPosition);
			
			$this->recordToUpdate->id = $this->insertedRecordId;
			
			$application->unregisterCurrentWindow();
			
			$application->setAction($this->insertAction);
		}
	}


	//-------------------------------------------------------------------
	// METHOD: getRecord
	//-------------------------------------------------------------------
	
	/**
	 *	Gets the record we are inserting.
	 *
	 *  @access public
	 *  @return CC_Record The record to insert.
	 */
	
	function &getRecord()
	{
		return $this->recordToUpdate;
	}

}

?>