<?php
// $Id: CC_Update_Record_Handler.php,v 1.15 2008/06/12 04:18:13 jamie Exp $
//=======================================================================

/**
 * This CC_Action_Handler updates a record to the database.
 * 
 * @package CC_Action_Handlers
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 * @see CC_Edit_Record_Window.php
 * @see CC_Edit_Ordered_Record_Window.php
 */

class CC_Update_Record_Handler extends CC_Action_Handler
{
	/**
	 * The record we are updating.
	 *
	 * @access private
	 * @var CC_Record $recordToUpdate
	 */
	
	var $recordToUpdate;


	/**
	 * The screen to go to when the record has been inserted. It is set not to go anywhere by default.
	 *
	 * @access private
	 * @var string $updateAction
	 */
	
	var $updateAction = false;
	

	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Update_Record_Handler
	//-------------------------------------------------------------------

	/**
	 * This method sets the parameters of the handler for use in the process method.
	 *
	 * @access public
	 * @param CC_Record $recordToUpdate The record to insert.
	 * @param mixed $updateAction The screen to go to upon updating or if this is false, stay on the same screen.
	 */

	function CC_Update_Record_Handler(&$recordToUpdate, $updateAction = '')
	{	
		global $application;
		
		//echo "In Update construcor method!<br>";
		
		$this->recordToUpdate = &$recordToUpdate;
		
		if ($updateAction !== false)
		{
			if ($updateAction == '')
			{
				$this->updateAction = $application->getLastAction();
			}
			else
			{
				$this->updateAction = $updateAction;
			}
		}
		
		$this->CC_Action_Handler();
	}


	//-------------------------------------------------------------------
	// METHOD: process()
	//-------------------------------------------------------------------

	/**
	 * This method updates the record and sets the application to display the set action if it is not false. It doesn't process if the user clicks more than once.
	 *
	 * @access public
	 * @param bool $multipleClick Whether or not the user clicked more than once.
	 */

	function process($multipleClick = false)
	{
		if ($multipleClick === false)
		{
			global $application;
			
			if ($this->recordToUpdate->update())
			{
				if ($this->updateAction !== false)
				{	
					$application->setAction($this->updateAction);
				}
				
				return true;
			}
		}
	}
}

?>