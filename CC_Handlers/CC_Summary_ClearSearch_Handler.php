<?php
// $Id: CC_Summary_ClearSearch_Handler.php,v 1.1 2003/07/15 17:05:16 patrick Exp $
//=======================================================================
// CLASS: CC_Summary_ClearSearch_Handler
//=======================================================================

/**
 * This CC_Action_Handler is used by the CC_Summary_Search_Compoment
 * class to alter the query of a CC_Summary.
 * 
 * @package CC_Action_Handlers
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 * @see CC_Summary_Search_Compoment
 */

class CC_Summary_ClearSearch_Handler extends CC_Action_Handler
{			
	var $_searchField;
	var $_searchButton;


	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Summary_ClearSearch_Handler
	//-------------------------------------------------------------------

	/**
	 * The constructor... Not sure what else to say.
	 *
	 * @access public
	 * @param CC_Text_Field $searchField The search field.
	 * @param CC_Button $searchButton The search button.
	 *
	 */

	function CC_Summary_ClearSearch_Handler(&$searchField, &$searchButton)
	{
		$this->CC_Action_Handler();
		
		$this->_searchField = &$searchField;
		$this->_searchButton = &$searchButton;
	}


	//-------------------------------------------------------------------
	// METHOD: process()
	//-------------------------------------------------------------------

	/**
	 * This method sets the text field's value to nothing, and then clicks
	 * the search button.
	 *
	 * @access public
	 */

	function process()
	{
		$this->_searchField->setValue();
		$this->_searchButton->click();
	}
}

?>