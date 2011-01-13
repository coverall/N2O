<?php
// $Id: CC_Add_Record_Handler.php,v 1.7 2003/07/07 05:13:51 jamie Exp $
//=======================================================================

/**
 * This CC_Action_Handler processes an 'Add Record' button and calls the Add_Record_Window.
 * 
 * @package CC_Action_Handlers
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 * @see CC_Add_Record_Window.php
 */

class CC_Add_Record_Handler extends CC_Action_Handler
{
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
	// CONSTRUCTOR: CC_Add_Record_Handler
	//-------------------------------------------------------------------

	/**
	 * This method sets the parameters of the handler for use in the process method.
	 *
	 * @access public
	 * @param string $tableName The table name.
	 * @param string $displayName The display name for records. If nothing is passed, it defaults to 'Record'.
	 */

	function CC_Add_Record_Handler($tableName, $displayName = 'Record')
	{	
		$this->tableName = $tableName;
		$this->displayName = &$displayName;
		
		$this->CC_Action_Handler();
	}


	//-------------------------------------------------------------------
	// METHOD: process()
	//-------------------------------------------------------------------

	/**
	 * This method sets the application to display the CC_Add_Record_Window for a record in the handler's associated table. The window will also be passed the record's display name for display.
	 *
	 * @access public
	 */

	function process()
	{
		$application = &$_SESSION['application'];
		
		$application->unregisterWindow(CC_FRAMEWORK_PATH . '/CC_Windows/CC_Add_Record_Window');
		
		$application->setAction(CC_FRAMEWORK_PATH . '/CC_Windows/CC_Add_Record_Window?tableNameForAdd='. $this->tableName . '&displayNameForAdd='. $this->displayName);	
	}
}

?>