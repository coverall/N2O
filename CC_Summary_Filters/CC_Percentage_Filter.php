<?php
// $Id: CC_Percentage_Filter.php,v 1.7 2003/09/18 23:38:43 jamie Exp $
//=======================================================================
// CLASS: CC_Percentage_Filter
//=======================================================================

/**
 * This CC_Summary_Filter filters a percentage and displays it graphically as HTML.
 * 
 * @package CC_Summary_Filters
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

class CC_Percentage_Filter extends CC_Summary_Filter
{
	//-------------------------------------------------------------------
	// METHOD: processValue()
	//-------------------------------------------------------------------

	/**
	 * This method filters the passed integer and returns HTML based on the passed width.
	 * 
	 * @access public
	 * @param date $date The timestamp to filter. 
	 * @return string A human-readable phrase which expresses the passed timestamp in terms relative to now.
	 * @todo Remove the $recordId parameter. We don't be using it, mon.
	 */

	function processValue($percent, $recordId, $width = 100)
	{
		if (is_array($width))
		{
			$width = 100;
		}

		$actualWidth = ($percent / 100) * $width;
		
		if ($percent == 100)
		{
			//return "<b>Complete</b>";
			return "<nobr><img src=\"/N2O/CC_Images/bar-end.gif\" width=\"1\" height=\"10\"><img src=\"/N2O/CC_Images/bar-100.gif\" width=\"" . ($actualWidth + 2) . "\" height=\"10\"><img src=\"/N2O/CC_Images/bar-end.gif\" width=\"1\" height=\"10\">&nbsp;<span class=\"small\">(" . $percent . "%)</span></nobr>";
		}
		else if ($percent >= 98 && $percent < 100)
		{
			return "<nobr><img src=\"/N2O/CC_Images/bar-end.gif\" width=\"1\" height=\"10\"><img src=\"/N2O/CC_Images/bar-yellow.gif\" width=\"" . ($actualWidth + 2) . "\" height=\"10\"><img src=\"/N2O/CC_Images/bar-end.gif\" width=\"1\" height=\"10\">&nbsp;<span class=\"small\">(" . $percent . "%)</span></nobr>";
		}
		else if ($percent >= 66 && $percent < 98)
		{
			return "<nobr><img src=\"/N2O/CC_Images/bar-end.gif\" width=\"1\" height=\"10\"><img src=\"/N2O/CC_Images/bar-yellow.gif\" width=\"" . $actualWidth . "\" height=\"10\"><img src=\"/N2O/CC_Images/bar-end.gif\" width=\"1\" height=\"10\"><img src=\"/N2O/CC_Images/bar-empty-left.gif\" width=\"2\" height=\"10\"><img src=\"/N2O/CC_Images/bar-empty.gif\" width=\"" . ($width - $actualWidth - 2) . "\" height=\"10\"><img src=\"/N2O/CC_Images/bar-end.gif\" width=\"1\" height=\"10\">&nbsp;<span class=\"small\">(" . $percent . "%)</span></nobr>";
		}
		else if ($percent >= 33 && $percent < 66)
		{
			return "<nobr><img src=\"/N2O/CC_Images/bar-end.gif\" width=\"1\" height=\"10\"><img src=\"/N2O/CC_Images/bar-orange.gif\" width=\"" . $actualWidth . "\" height=\"10\"><img src=\"/N2O/CC_Images/bar-end.gif\" width=\"1\" height=\"10\"><img src=\"/N2O/CC_Images/bar-empty-left.gif\" width=\"2\" height=\"10\"><img src=\"/N2O/CC_Images/bar-empty.gif\" width=\"" . ($width - $actualWidth - 2) . "\" height=\"10\"><img src=\"/N2O/CC_Images/bar-end.gif\" width=\"1\" height=\"10\">&nbsp;<span class=\"small\">(" . $percent . "%)</span></nobr>";
		}
		else if ($percent > 0 && $percent < 33)
		{
			return "<nobr><img src=\"/N2O/CC_Images/bar-end.gif\" width=\"1\" height=\"10\"><img src=\"/N2O/CC_Images/bar-red.gif\" width=\"" . $actualWidth . "\" height=\"10\"><img src=\"/N2O/CC_Images/bar-end.gif\" width=\"1\" height=\"10\"><img src=\"/N2O/CC_Images/bar-empty-left.gif\" width=\"2\" height=\"10\"><img src=\"/N2O/CC_Images/bar-empty.gif\" width=\"" . ($width - $actualWidth - 2) . "\" height=\"10\"><img src=\"/N2O/CC_Images/bar-end.gif\" width=\"1\" height=\"10\">&nbsp;<span class=\"small\">(" . $percent . "%)</span></nobr>";
		}
		else if ($percent == 0)
		{
			return "<nobr><img src=\"/N2O/CC_Images/bar-end.gif\" width=\"1\" height=\"10\"><img src=\"/N2O/CC_Images/bar-empty-left.gif\" width=\"2\" height=\"10\"><img src=\"/N2O/CC_Images/bar-empty.gif\" width=\"" . ($width - 1) . "\" height=\"10\"><img src=\"/N2O/CC_Images/bar-end.gif\" width=\"1\" height=\"10\">&nbsp;<span class=\"small\">(" . $percent . "%)</span></nobr>";
		}
		else
		{
			return "<img src=\"/N2O/CC_Images/bar-end.gif\" width=\"1\" height=\"10\"><img src=\"/N2O/CC_Images/bar.gif\" width=\"" . $actualWidth . "\" height=\"10\"><img src=\"/N2O/CC_Images/bar-end.gif\" width=\"1\" height=\"10\">&nbsp;<span class=\"small\">(" . $percent . "%)</span>";
		}
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: textFriendlyProcessValue()
	//-------------------------------------------------------------------

	/**
	 * This method returns the passed integer as is in order to be text-friendly.
	 * 
	 * @access public
	 * @param int $value The percentage to filter. 
	 * @param int $id The record id. 
	 * @return int The unaltered passed percentage value. It's text-friendly, ok?
	 * @todo Remove the $id parameter. We don't be using it, mon.
	 */

	function textFriendlyProcessValue($value, $id)
	{
		return $value;
	}
}

?>