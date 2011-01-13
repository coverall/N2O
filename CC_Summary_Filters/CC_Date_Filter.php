<?php
// $Id: CC_Date_Filter.php,v 1.4 2003/07/06 01:31:34 jamie Exp $
//=======================================================================
// CLASS: CC_Date_Filter
//=======================================================================

/**
 * This CC_Summary_Filter filters a CC_Date_Field's value by making its value human-readable.
 * 
 * @package CC_Summary_Filters
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

class CC_Date_Filter extends CC_Summary_Filter
{
	//-------------------------------------------------------------------
	// METHOD: processValue()
	//-------------------------------------------------------------------

	/**
	 * This method filters the passed date.
	 * 
	 * @access public
	 * @param date $date The date to filter. 
	 * @return string A human-readable date in this format: September 9, 1974. Jamie's birthday (hint, hint).
	 */

	function processValue($date)
	{
		if ($date == "0000-00-00" || $date == "") { return "-"; }

		$year  = strval(substr($date,0,4));
		$month = strval(substr($date,5,2));
		$day   = strval(substr($date,8,2));

		return date("F j, Y", mktime(0,0,0,$month,$day,$year));
	}
}

?>