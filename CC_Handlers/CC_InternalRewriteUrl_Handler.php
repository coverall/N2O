<?php
// $Id: CC_InternalRewriteUrl_Handler.php,v 1.5 2004/12/08 03:12:56 patrick Exp $
//=======================================================================
// CLASS: CC_InternalRewriteUrl_Handler
//=======================================================================

/**
 * This CC_Action_Handler handles browser redirection to a given internal URL.
 * 
 * @package CC_Action_Handlers
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

class CC_InternalRewriteUrl_Handler extends CC_Action_Handler
{			
	var $_target;


	//-------------------------------------------------------------------
	// CONSTRUCTOR
	//-------------------------------------------------------------------

	function CC_InternalRewriteUrl_Handler($target)
	{
		$this->_target = $target;
	}



	//-------------------------------------------------------------------
	// FUNCTION: process
	//-------------------------------------------------------------------

	function process()
	{
		header('Location: ' . BASE_URL . $this->_target . ((isset($_COOKIE[session_name()]) && ($_COOKIE[session_name()] == session_id())) ? '' : ('?' . SID)));
		exit();
	}
}

?>