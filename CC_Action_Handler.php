<?php
// $Id: CC_Action_Handler.php,v 1.6 2003/07/07 05:18:52 jamie Exp $
//=======================================================================
// CLASS: CC_Action_Handler
//=======================================================================

	/**
     * Subclasses of this class are registered with CC_Button objects. The process() method is called when the button is clicked.
     *
     * @package CC_Action_Handlers
     * @access public
   	 * @see CC_Button::registerHandler()
	 * @author The Crew <N2O@coverallcrew.com>
	 * @copyright Copyright &copy; 2003, Coverall Crew
     */

class CC_Action_Handler
{
	/**
     * The window where the handler was called.
     *
     * @var CC_Window $sourceWindow
     * @access public
     * @todo deprecate this parameter as it only appears in CC_Delete_Ordered_Record_Handler and CC_Delete_Record_Handler  
     */
     
	var $sourceWindow = NULL;
	
	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Action_Handler
	//-------------------------------------------------------------------

	/**
	 * This is the parent constructor for all CC_Action_Handler 
	 * subclasses.
	 *
	 * @access public 
	 */

	function CC_Action_Handler()
	{
	}
			
	
	//-------------------------------------------------------------------
	// METHOD: process()
	//-------------------------------------------------------------------

	/**
	 * This method is called by the system when a registrant button is clicked. Note that this method may not get called if there are missing or invalid fields in the window and the button has been defined as one that must validate the window's fields when clicked.
	 *
	 * @access public
	 * @param bool $multipleClick This variable is true if we are processing this method for the second or third time in the case where a user accidentally double or triple clicks on a registrant button. It may be necessary for process() methods to take this into account. (eg. If process() is deleting items then one may only want to execute the deletion code the first time through the process method (ie. when $multipleClick is false))
	 * @see CC_Button::validateOnClick()
	 * @see CC_Button::registerHandler()
	 */

	function process($multipleClick = false)
	{
	}
}

?>