<?php
// $Id: CC_Percentage_Filter.php,v 1.15 2006/05/29 19:42:54 patrick Exp $
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
	var $width;

	
	//-------------------------------------------------------------------
	// CONSTRUCTOR
	//-------------------------------------------------------------------

	function CC_Percentage_Filter($width = 100)
	{
		$this->width = $width;
	}


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
			$width = $this->width;
		}
		
		$percent = round($percent, 2);

		$actualWidth = round(($percent / 100) * ($width - 2));
		
		$img = '<div class="ccPercent" style="width:' . ($width - 2) . 'px" title="' . $percent . '%">';
		
		if ($percent == 100)
		{
			$type = '-100';
		}
		else if ($percent >= 66 && $percent < 100)
		{
			$type = '-yellow';
		}
		else if ($percent >= 33 && $percent < 66)
		{
			$type = '-orange';
		}
		else if ($percent > 0 && $percent < 33)
		{
			$type = '-red';
		}
		
		if ($percent > 0)
		{
			$img .= '<img src="/N2O/CC_Images/bar' . $type . '.gif" width="' . ($actualWidth + ($percent < 100 ? 0 : 0)) . '" height="12" border="0">';
		}
		if ($percent > 0 && $percent < 96)
		{
			$img .= '<img src="/N2O/CC_Images/bar-end.png" width="4" height="12" border="0">';
		}
		$img .= '</div>';
		
		return $img;
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