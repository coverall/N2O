<?php
// $Id: CC_Save_Record_Handler.php,v 1.6 2003/07/07 05:13:51 jamie Exp $
//=======================================================================

/**
 * This handler saves the current record but DOES NOT exit to the previous screen.
 * 
 * @package CC_Action_Handlers
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

class CC_Save_Record_Handler extends CC_Action_Handler
{
	/**
	 * The record we are updating.
	 *
	 * @access private
	 * @var CC_Record $recordToUpdate
	 */
	
	var $recordToUpdate;
	

	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Save_Record_Handler
	//-------------------------------------------------------------------

	/**
	 * This method sets the parameters of the handler for use in the process method.
	 *
	 * @access public
	 * @param CC_Record $recordToUpdate The record to insert.
	 */

	function CC_Save_Record_Handler(&$recordToUpdate)
	{	
		$application = &$_SESSION['application'];
		
		$this->recordToUpdate = &$recordToUpdate;
		
		$this->CC_Action_Handler();
	}


	//-------------------------------------------------------------------
	// METHOD: process()
	//-------------------------------------------------------------------

	/**
	 * This method simply updates the record and remains on the same screen. It doesn't process if the user clicks more than once.
	 *
	 * @access public
	 * @param bool $multipleClick Whether or not the user clicked more than once.
	 */

	function process($multipleClick)
	{
		if ($multipleClick === false)
		{
			$application = &$_SESSION['application'];
			
			$application->db->doUpdate($this->recordToUpdate->buildUpdateQuery());
		}
	}
}

?>