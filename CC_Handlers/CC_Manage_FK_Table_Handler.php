<?php
// $Id: CC_Manage_FK_Table_Handler.php,v 1.6 2003/11/27 20:37:35 mike Exp $
//=======================================================================

/**
 * This CC_Action_Handler processes a CC_Foreign_Key_Field's 'Manage' button and calls the Manage_FK_Table_Window.
 * 
 * @package CC_Action_Handlers
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 * @see CC_Add_FK_Record_Window.php
 */

class CC_Manage_FK_Table_Handler extends CC_Action_Handler
{
	/**
	 * The name of the CC_Foreign_Key_Field we are managing.
	 *
	 * @access private
	 * @var string $foreignKey
	 */

	var $foreignKey;

	
	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Manage_FK_Table_Handler
	//-------------------------------------------------------------------

	/**
	 * This method sets the parameters of the handler for use in the process method.
	 *
	 * @access public
	 * @param string $foreignKey The name of the CC_Foreign_Key_Field we are managing.
	 */

	function CC_Manage_FK_Table_Handler($foreignKey)
	{	
		$this->CC_Action_Handler();

		$this->foreignKey = $foreignKey;
	}


	//-------------------------------------------------------------------
	// METHOD: process()
	//-------------------------------------------------------------------

	/**
	 * This method sets the action to CC_Manage_FK_Table_Window and passes the name of the field.
	 *
	 * @access public
	 */

	function process()
	{
		$application = &$_SESSION['application'];
		
		/*
		if ($application->isWindowRegistered(CC_FRAMEWORK_PATH . "/CC_Windows/CC_Manage_FK_Table_Window"))
		{
			$application->unRegisterWindow(CC_FRAMEWORK_PATH . "/CC_Windows/CC_Manage_FK_Table_Window");
		}
		*/
		
		$application->setArgument('foreignKeyField', $this->foreignKey);
		
		$application->setAction(CC_FRAMEWORK_PATH . '/CC_Windows/CC_Manage_FK_Table_Window');	
	}
}

?>