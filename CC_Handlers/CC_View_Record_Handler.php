<?php
// $Id: CC_View_Record_Handler.php,v 1.5 2003/07/07 05:13:51 jamie Exp $
//=======================================================================

/**
 * This CC_Action_Handler processes a 'View Record' button and calls the View_Record_Window.
 * 
 * @package CC_Action_Handlers
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 * @see CC_View_Record_Window.php
 */

class CC_View_Record_Handler extends CC_Action_Handler
{
	/**
	 * The id of the record we are viewing.
	 *
	 * @access private
	 * @var int $recordId
	 */

	var $recordId;

	/**
	 * The name of the table we are adding to.
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
	// CONSTRUCTOR: CC_View_Record_Handler
	//-------------------------------------------------------------------

	/**
	 * This method sets the parameters of the handler for use in the process method.
	 *
	 * @access public
	 * @param string $tableName The table name.
	 * @param int $record The id of the record in the given table.
	 * @param string $displayName The display name for records. If nothing is passed, it defaults to 'Record'.
	 */

	function CC_View_Record_Handler($tableName, $recordId, $displayName = 'Record')
	{	
		$this->tableName = $tableName;
		$this->recordId = $recordId;
		$this->displayName = $displayName;
		
		$this->CC_Action_Handler();
	}


	//-------------------------------------------------------------------
	// METHOD: process()
	//-------------------------------------------------------------------

	/**
	 * This method sets the application to display the CC_View_Record_Window for a record of a given id in the given table. The window will also be passed the record's display name.
	 *
	 * @access public
	 */

	function process()
	{
		$application = &$_SESSION['application'];
		
		$application->unregisterWindow(CC_FRAMEWORK_PATH . '/CC_Windows/CC_View_Record_Window');
		
		$application->setAction(CC_FRAMEWORK_PATH . '/CC_Windows/CC_View_Record_Window?tableNameForView=' . $this->tableName . '&viewRecordId=' . $this->recordId . '&displayNameForView=' . $this->displayName);
	}
}

?>