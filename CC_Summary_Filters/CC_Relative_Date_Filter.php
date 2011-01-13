<?php
// $Id: CC_Relative_Date_Filter.php,v 1.6 2004/01/23 22:38:45 patrick Exp $
//=======================================================================
// CLASS: CC_Relative_Date_Filter
//=======================================================================

/**
 * This CC_Summary_Filter filters a CC_Date_Field's value by making its value human readable relative to the current date. For instance, if the field is set to September 9, 1974 and the current date is September 17, 2003... the filter will return 53 weeks, one day ago. That's the idea.
 * 
 * @package CC_Summary_Filters
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 * @see CC_Relative_Timestamp_Filter
 */

class CC_Relative_Date_Filter extends CC_Summary_Filter
{
	//-------------------------------------------------------------------
	var $alert;
	

	//-------------------------------------------------------------------
	// CONSTRUCTOR
	//-------------------------------------------------------------------

	/**
	 * Constructs a CC_Relative_Date_Filter.
	 * 
	 * @access public
	 * @param boolean $alert Boolean to indicate whether dates in the past should be treated as an "alert" (ie. overdue). If true, the value will take on the "alert" CSS style.
	 */

	function CC_Relative_Date_Filter($alert = true)
	{
		$this->alert = $alert;
	}


	//-------------------------------------------------------------------
	// METHOD: processValue()
	//-------------------------------------------------------------------

	/**
	 * This method filters the passed date to the appropriate text relative to now.
	 * 
	 * @access public
	 * @param date $date The date to filter. 
	 * @return string A human-readable phrase which expresses the passed date in terms relative to now.
	 */

	function processValue($date)
	{
		if ($date == "0000-00-00" || $date == "") { return "-"; }
		
		$yr = strval(substr($date,0,4));
		$mo = strval(substr($date,5,2));
		$da = strval(substr($date,8,2));
		
		$today = getDate();
		$todayMo = $today["mon"];
		$todayDa = $today["mday"];
		$todayYr = $today["year"];

		$days = intval( (mktime(0,0,0,$todayMo,$todayDa,$todayYr)  - mktime(0,0,0,$mo,$da,$yr)) / (60*60*24) );
		
		if ($days == 0)
		{
			return "Today";
		}
		else if ($days == 1)
		{
			return "Yesterday";
		}
		else if ($days == -1)
		{
			return "Tomorrow";
		}
		else if ($days <= -7)
		{
			$weeks = intval($days / 7);
			$leftover = intval($days % 7);
			
			$returnValue = abs($weeks) . " week";	
			if (abs($weeks) > 1)
			{
				$returnValue .= "s";
			}
			
			if (abs($leftover) > 0)
			{
				$returnValue .= ", " . abs($leftover) . " day";
				if (abs($leftover) > 1)
				{
					$returnValue .= "s";
				}
			}
			
			return $returnValue;
		}
		else if ($days >= 7)
		{
			$weeks = intval($days / 7);
			$leftover = intval($days % 7);
			
			$returnValue = abs($weeks) . " week";
			if (abs($weeks) > 1)
			{
				$returnValue .= "s";
			}
			
			if (abs($leftover) > 0)
			{
				$returnValue .= ", " . abs($leftover) . " day";
				if (abs($leftover) > 1)
				{
					$returnValue .= "s";
				}
			}
			
			if ($this->alert)
			{
				return "<span class=\"alert\">" . $returnValue . " ago</span>";
			}
			else
			{
				return $returnValue;
			}
		}
		else if ($days < -1)
		{
			return abs($days) . " days";
		}
		else if ($days > 1)
		{
			return abs($days) . " days ago";
		}
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: textFriendlyProcessValue()
	//-------------------------------------------------------------------

	/**
	 * This method filters the passed date to the appropriate text relative to now returning text friendly output (ie. no HTML).
	 * 
	 * @access public
	 * @param date $date The date to filter. 
	 * @return string A human-readable phrase which expresses the passed date in terms relative to now. The output is text friendly (ie. no HTML).
	 */

	function textFriendlyProcessValue($date)
	{
		if ($date == "0000-00-00" || $date == "") { return "-"; }
		
		$yr = strval(substr($date,0,4));
		$mo = strval(substr($date,5,2));
		$da = strval(substr($date,8,2));
		
		$today = getDate();
		$todayMo = $today["mon"];
		$todayDa = $today["mday"];
		$todayYr = $today["year"];

		$days = intval( (mktime(0,0,0,$todayMo,$todayDa,$todayYr)  - mktime(0,0,0,$mo,$da,$yr)) / (60*60*24) );
		
		if ($days == 0)
		{
			return "Today";
		}
		else if ($days == 1)
		{
			return "Yesterday";
		}
		else if ($days == -1)
		{
			return "Tomorrow";
		}
		else if ($days <= -7)
		{
			$weeks = intval($days / 7);
			$leftover = intval($days % 7);
			
			$returnValue = abs($weeks) . " week";	
			if (abs($weeks) > 1)
			{
				$returnValue .= "s";
			}
			
			if (abs($leftover) > 0)
			{
				$returnValue .= ", " . abs($leftover) . " day";
				if (abs($leftover) > 1)
				{
					$returnValue .= "s";
				}
			}
			
			return $returnValue;
		}
		else if ($days >= 7)
		{
			$weeks = intval($days / 7);
			$leftover = intval($days % 7);
			
			$returnValue = abs($weeks) . " week";
			if (abs($weeks) > 1)
			{
				$returnValue .= "s";
			}
			
			if (abs($leftover) > 0)
			{
				$returnValue .= ", " . abs($leftover) . " day";
				if (abs($leftover) > 1)
				{
					$returnValue .= "s";
				}
			}
			
			return $returnValue . " ago";
		}
		else if ($days < -1)
		{
			return abs($days) . " days";
		}
		else if ($days > 1)
		{
			return abs($days) . " days ago";
		}
	}
}

?>