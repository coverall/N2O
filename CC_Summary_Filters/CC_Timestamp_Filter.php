<?php
// $Id: CC_Timestamp_Filter.php,v 1.5 2003/07/06 01:31:34 jamie Exp $
//=======================================================================
// CLASS: CC_Timestamp_Filter
//=======================================================================

/**
 * This CC_Summary_Filter filters a CC_Timestamp_Field by making its value human-readable. I mean, I can figure out what date it is if it is 346574932898 seconds since January 1, 1970 but I have better things to do with my time... like making smoothies! Thank goodness for the CC_Timestamp_Filter and other filters, too, without them, lots of things would get through.
 * 
 * @package CC_Summary_Filters
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

class CC_Timestamp_Filter extends CC_Summary_Filter
{
	/**
	 * Whether or not we should use the short or long version of this filter.
	 *
	 * @var bool $short
	 * @access private
	 */

	var $short;

	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Timestamp_Filter
	//-------------------------------------------------------------------

	/**
	 * A boolean value is passed to the filter to determine whether or how to filter the timestamp. Pass true to filter in short format (Sep 9/74 10:35) or false for long format (Sep 09, 1974 10:35). The alignment is centered automatically.
	 *
	 * @param bool $short
	 * @access private
	 */

	function CC_Timestamp_Filter($short = false)
	{
		$this->setCenterAlignment();	
		$this->short = $short;
	}

	
	//-------------------------------------------------------------------
	// METHOD: processValue()
	//-------------------------------------------------------------------

	/**
	 * This method filters the passed timestamp.
	 * 
	 * @access public
	 * @param date $date The timestamp to filter. 
	 * @return string A human-readable date in short or long format.
	 */

	function processValue($date)
	{
		if ($date == "00000000000000" || $date == "") { return ""; }
		
		$yr=strval(substr($date,0,4));
		$mo=strval(substr($date,4,2));
		$da=strval(substr($date,6,2));
		
		$hr=strval(substr($date,8,2));
		$mi=strval(substr($date,10,2));
		$se=strval(substr($date,12,2));
				
		if ($this->short)
		{
			return date("M j/y H:i", mktime($hr,$mi,$se,$mo,$da,$yr));
		}
		else
		{
			return date("M d, Y H:i", mktime($hr,$mi,$se,$mo,$da,$yr));
		}
	}
		
}

?>