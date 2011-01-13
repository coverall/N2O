<?php
// $Id: CC_Rewrite_Url_Handler.php,v 1.4 2004/02/04 20:52:48 mike Exp $
//=======================================================================

/**
 * This CC_Action_Handler handles browser redirection to a given external URL.
 * 
 * @package CC_Action_Handlers
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

class CC_Rewrite_Url_Handler extends CC_Action_Handler
{
	/**
	 * The URL to go to.
	 *
	 * @access private
	 * @var string $redirectURL
	 */

	var $redirectURL;

			 
	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Rewrite_Url_Handler
	//-------------------------------------------------------------------

	/**
	 * This method sets the parameters of the handler for use in the process method.
	 *
	 * @access public
	 * @param string $URL The URL we would like to redirect to.
	 */

	function CC_Rewrite_Url_Handler($URL)
	{
		$this->redirectURL = $URL;
		
		$this->CC_Action_Handler();
	}


	//-------------------------------------------------------------------
	// METHOD: process()
	//-------------------------------------------------------------------

	/**
	 * This method sets an argument in the application to redirect to an external URL.
	 *
	 * @access public
	 * @see CC_Index
	 */

	function process()
	{
		header('Location: ' . $this->redirectURL);
		exit();
	}
}

?>