<?php
// $Id: CC_DateTime_Filter.php,v 1.9 2005/03/11 18:38:22 mike Exp $
//=======================================================================
// CLASS: CC_DateTime_Filter
//=======================================================================

/**
 * This CC_Summary_Filter filters a CC_DateTime_Field's value by making its value human-readable.
 * 
 * @package CC_Summary_Filters
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

class CC_DateTime_Filter extends CC_Summary_Filter
{
	/**
	 * Whether or not we should use the short or long version of this filter.
	 *
	 * @var int $short
	 * @access private
	 */

	var $short;


	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_DateTime_Filter
	//-------------------------------------------------------------------

	/**
	 * A boolean value is passed to the filter to determine whether or how to filter the timestamp. Pass 1 (or true) to filter in short format (Sep 9/74 10:35), 2 for pretty short (September 9, 1974), 3 for really short (Sep 9/74), or 0 (or false) for long format (Sep 09, 1974 10:35). The alignment is centered automatically.
	 *
	 * @param int $short
	 * @access private
	 */

	function CC_DateTime_Filter($short = 1)
	{
		$this->short = $short;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: processValue()
	//-------------------------------------------------------------------

	/**
	 * This method filters the passed datetime.
	 * 
	 * @access public
	 * @param datetime $date The datetime to filter. 
	 * @return string A human-readable date in short(Sep 9/74 10:35) or long(Sep 09, 1974 10:35) format.
	 */

	function processValue($date)
	{
		if ($date == '0000-00-00 00:00:00' || $date == '') { return '-'; }
		
		$time = convertMysqlDateTimeToTimestamp(substr($date, 0, 18));

		switch ($this->short)
		{
			case 1:
			{
				return date("M j/y H:i", $time);
			}
			break;
			
			case 2:
			{
				return date("M d, Y", $time);
			}
			break;

			case 3:
			{
				return date("M j/y", $time);
			}
			break;
			
			case 4:
			{
				return date("M d, Y H:i:s", $time);
			}
			break;
			
			case 0:
			default:
			{
				return date("M d, Y H:i", $time);
			}
			break;
		}
	}
}

?>