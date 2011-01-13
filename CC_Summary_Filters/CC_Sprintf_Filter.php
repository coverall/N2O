<?php
// $Id: CC_Sprintf_Filter.php,v 1.1 2004/04/06 03:52:07 patrick Exp $
//=======================================================================
// CLASS: CC_Sprintf_Filter
//=======================================================================

/**
 * This CC_Sprintf_Filter lets you define an sprintf-compatible string to be filtered. Up to three substitution strings are allowed (the value is passed into sprintf() thrice).
 * 
 * @package CC_Summary_Filters
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

class CC_Sprintf_Filter extends CC_Summary_Filter
{
	//-------------------------------------------------------------------
	var $_sprintfString;


	//-------------------------------------------------------------------
	// CONSTRUCTOR
	//-------------------------------------------------------------------

	/**
	 * Constructs a CC_Sprintf_Filter.
	 *
	 * @param string $sprintfString An sprintf-compatible string.
	 * @see http://php.net/sprintf
	 * @access public
	 */

	function CC_Sprintf_Filter($sprintfString)
	{
		$this->_sprintfString = $sprintfString;
	}


	//-------------------------------------------------------------------
	// METHOD: processValue()
	//-------------------------------------------------------------------

	/**
	 * This method filters the passed value to the specified sprintf() string.
	 * 
	 * @access public
	 * @param mixed $value The value to filter. 
	 * @return string Sprintfed string.
	 */

	function processValue($value)
	{
		return sprintf($this->_sprintfString, $value, $value, $value);
	}
}

?>