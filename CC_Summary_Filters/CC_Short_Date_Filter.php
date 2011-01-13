<?php
// $Id: CC_Short_Date_Filter.php,v 1.4 2003/07/06 01:31:34 jamie Exp $
//=======================================================================
// CLASS: CC_Short_Date_Filter
//=======================================================================

/**
 * This CC_Summary_Filter filters a CC_Timestamp_Field by making its value human-readable. The format is Sep 9, 1974.
 * 
 * @package CC_Summary_Filters
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

class CC_Short_Date_Filter extends CC_Summary_Filter
{
	//-------------------------------------------------------------------
	// METHOD: processValue()
	//-------------------------------------------------------------------

	/**
	 * This method filters the passed date.
	 * 
	 * @access public
	 * @param date $date The date to filter. 
	 * @return string A human-readable date in short format (ie. Sep 9, 1974).
	 */

	function processValue($date)
	{
		if ($date == '0000-00-00' || $date == '') { return '-'; }

		return date('M j, Y', strtotime($date));
	}
}

?>