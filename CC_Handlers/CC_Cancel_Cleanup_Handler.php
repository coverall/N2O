<?php
// $Id: CC_Cancel_Cleanup_Handler.php,v 1.5 2004/04/27 03:14:12 patrick Exp $
//=======================================================================

/**
 * This CC_Action_Handler handles actions that take a user back one screen and call all the fields' cancelCleanup() methods.
 * 
 * @package CC_Action_Handlers
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

class CC_Cancel_Cleanup_Handler extends CC_Action_Handler
{
	/**
	 * The window we are currently on.
	 *
	 * @access private
	 * @var CC_Window $window
	 */

	var $window;
	
	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Cancel_Cleanup_Handler
	//-------------------------------------------------------------------

	/**
	 * This method sets the parameters of the handler for use in the process method.
	 *
	 * @access public
	 * @param CC_Window $window The window we are currently processing.
	 */

	function CC_Cancel_Cleanup_Handler(&$window)
	{	
		$this->window = &$window;
		
		$this->CC_Action_Handler();
	}


	//-------------------------------------------------------------------
	// METHOD: process()
	//-------------------------------------------------------------------

	/**
	 * This method calls all the window fields' (standalone and record) cancelCleanup() methods.
	 *
	 * @access public
	 */

	function process()
	{
		$application = &$_SESSION['application'];
	
		// go through each field on this page and call it's cancelCleanup() method
		
		for ($i = 0; $i < sizeof($this->window->fields); $i++)
		{
			$field = &$this->window->fields[$i];
			$field->cancelCleanup();
		}
		
		//go through each field from each record in the window and call it's 
		// cancelCleanup() method
		
		$recordKeys = array_keys($this->window->records);
		
		for ($j = 0; $j < sizeof($recordKeys); $j++)
		{
			$record = &$this->window->records[$recordKeys[$j]];
			
			$fieldKeys = array_keys($record->fields);
			
			for ($k = 0; $k < sizeof($fieldKeys); $k++)
			{	
				$field = &$record->fields[$fieldKeys[$k]];
				$field->cancelCleanup();
			}
		}
	}
}

?>