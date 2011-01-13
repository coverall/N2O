<?php
// $Id: CC_Insert_Record_Handler.php,v 1.18 2004/08/25 03:15:22 patrick Exp $
//=======================================================================

/**
 * This CC_Action_Handler adds a record to the database.
 * 
 * @package CC_Action_Handlers
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 * @see CC_Add_Record_Window.php
 */

class CC_Insert_Record_Handler extends CC_Action_Handler
{
	/**
	 * The id of the record we insert which is returned after we insert it.
	 *
	 * @access private
	 * @var int $insertedRecordId
	 */

	var $insertedRecordId;
	

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
	
	var $insertAction = false;


	/**
	 * If true, the handler will unregister the window.
	 *
	 * @access private
	 * @var boolean $unregisterWindow
	 */
	
	var $unregisterWindow;


	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Insert_Record_Handler
	//-------------------------------------------------------------------

	/**
	 * This method adds errors of any type to CC_Error_Manager's _errors
	 * array. 
	 *
	 * @access public
	 * @param CC_Record $recordToUpdate The record to insert.
	 * @param mixed $insertAction The screen to go to upon updating or if this is false, stay on the same screen.
	 * @param boolean $unregisterWindow If true, the handler will unregister the window.
	 */

	function CC_Insert_Record_Handler(&$recordToUpdate, $insertAction = '', $unregisterWindow = true)
	{	
		$application = &$_SESSION['application'];
		
		$this->recordToUpdate = &$recordToUpdate;
		
		if ($insertAction !== false)
		{
			if ($insertAction == '')
			{
				$this->insertAction = $application->getLastAction();
			}
			else
			{
				$this->insertAction = $insertAction;
			}
		}
		
		$this->unregisterWindow = $unregisterWindow;
		
		$this->CC_Action_Handler();
	}


	//-------------------------------------------------------------------
	// METHOD: process()
	//-------------------------------------------------------------------

	/**
	 * This method inserts the record and sets the application to display the previous window. It doesn't process if the user clicks more than once.
	 *
	 * @access public
	 * @param bool $multipleClick Whether or not the user clicked more than once.
	 */

	function process($multipleClick = false)
	{
		if ($multipleClick === false)
		{
			$application = &$_SESSION['application'];
			
			$this->insertedRecordId = $application->db->doInsert($this->recordToUpdate->buildInsertQuery());
			
			if (PEAR::isError($this->insertedRecordId))
			{
				$window->setErrorMessage('Could not add record. (' . $this->insertedRecordId->getMessage() . ')');
				
				return false;
			}
			else
			{
				$this->recordToUpdate->id = $this->insertedRecordId;
				
				if ($this->unregisterWindow)
				{
					$application->unregisterCurrentWindow();
				}
				
				if ($this->insertAction !== false && strlen($this->insertAction))
				{
					$application->setAction($this->insertAction);
				}
				
				$this->recordToUpdate->setId($this->recordToUpdate->id);
				
				return true;
			}
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
	
	
	//-------------------------------------------------------------------
	// METHOD: getInsertedRecordId
	//-------------------------------------------------------------------

	/**
	 *	Gets the id of the inserted record.
	 *
	 *  @access public
	 *  @return int The record id.
	 */
	
	function getInsertedRecordId()
	{
		return $this->insertedRecordId;
	}
	
	

}

?>