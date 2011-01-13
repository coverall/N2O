<?php
// $Id: CC_Insert_FK_Record_Handler.php,v 1.12 2003/11/27 02:43:19 mike Exp $
//=======================================================================
// CLASS: CC_Insert_FK_Record_Handler
//=======================================================================

/**
 * This CC_Action_Handler adds a foreign key record to the database.
 * 
 * @package CC_Action_Handlers
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 * @see CC_Add_FK_Record_Window.php
 */

class CC_Insert_FK_Record_Handler extends CC_Insert_Record_Handler
{	
	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Insert_FK_Record_Handler
	//-------------------------------------------------------------------

	/**
	 * This method calls its parent which sets the parameters of the handler for use in the process method.
	 *
	 * @access public
	 */

	function CC_Insert_FK_Record_Handler(&$recordToUpdate)
	{	
		$this->CC_Insert_Record_Handler($recordToUpdate);
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

	function process($multipleClick)
	{
		if (!$multipleClick)
		{
			$application = &$_SESSION['application'];
			
			parent::process($multipleClick);
			
			// select the currently added FK field in the selectlist when returning to
			// the previous screen
			
			$application->setArgument('recentlyAddedForeignKeyValue' . $application->getArgument('foreignKeyField'), $this->insertedRecordId);
		}
	}
}

?>