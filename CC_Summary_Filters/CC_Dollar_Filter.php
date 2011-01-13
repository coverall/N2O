<?php
// $Id: CC_Dollar_Filter.php,v 1.7 2004/12/20 21:42:36 patrick Exp $
//=======================================================================
// CLASS: CC_Dollar_Filter
//=======================================================================

/**
 * This CC_Summary_Filter filters a number value by making it conform to dollar-based currency format (ie. $42.42).
 * 
 * @package CC_Summary_Filters
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

class CC_Dollar_Filter extends CC_Summary_Filter
{

	//-------------------------------------------------------------------
	// CONSTRUCTOR
	//-------------------------------------------------------------------

	/**
	 * The alignment is right justified automatically in the constructor.
	 *
	 * @param bool $short
	 * @access private
	 */

	function CC_Dollar_Filter()
	{
		parent::setRightAlignment();
	}


	//-------------------------------------------------------------------
	// METHOD: processValue()
	//-------------------------------------------------------------------

	/**
	 * This method filters the passed timestamp to the appropriate text relative to now.
	 * 
	 * @access public
	 * @param mixed $value The number to filter. 
	 * @return string A dollar string with a dollar sign and cents to two decimal places (ie. $42.42).
	 */

	function processValue($value)
	{
		return '$' . number_format($value, 2);
	}


	//-------------------------------------------------------------------
	// METHOD: textFriendlyProcessValue()
	//-------------------------------------------------------------------

	/**
	 * This method does the filtering for raw data (no html). By default, this method simply calls processValue().
	 * 
	 * @access public
	 * @param mixed $value The value of the column taken from the database.
	 * @param int $id The row's record id. This may or may not be needed to filter the value.
	 * @see processValue()
	 */

	function textFriendlyProcessValue($value, $id)
	{
		return $value;
	}
}

?>