<?php
// $Id: CC_Reltve_Timestamp_Fltr.php,v 1.7 2004/01/23 23:29:57 patrick Exp $
//=======================================================================
// CLASS: CC_Relative_Timestamp_Filter
//=======================================================================

/**
 * This CC_Summary_Filter filters a CC_Timestamp_Field by making its value human readable relative to the current timestamp. For instance, if the field is set to September 9, 1974 and the current date is September 17, 2003... the filter will return 53 weeks, one day ago. That's the idea.
 * 
 * @package CC_Summary_Filters
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

class CC_Relative_Timestamp_Filter extends CC_Summary_Filter
{
	//-------------------------------------------------------------------
	var $alert;


	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Relative_Timestamp_Filter
	//-------------------------------------------------------------------

	/**
	 * The alignment is centered automatically in the constructor.
	 *
	 * @param bool $short
	 * @access private
	 */

	function CC_Relative_Timestamp_Filter($alert = true)
	{
		$this->setCenterAlignment();

		$this->alert = $alert;
	}

	
	//-------------------------------------------------------------------
	// METHOD: processValue()
	//-------------------------------------------------------------------

	/**
	 * This method filters the passed timestamp to the appropriate text relative to now.
	 * 
	 * @access public
	 * @param date $date The timestamp to filter. 
	 * @return string A human-readable phrase which expresses the passed timestamp in terms relative to now.
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
	 * This method filters the passed timestamp to the appropriate text relative to now returning text friendly output (ie. no HTML).
	 * 
	 * @access public
	 * @param date $date The timestamp to filter. 
	 * @return string A human-readable phrase which expresses the passed timestamp in terms relative to now. The output is text friendly (ie. no HTML).
	 */

	function textFriendlyProcessValue($date)
	{
		if ($date == "00000000000000" || $date == "") { return ""; }
		
		$yr=strval(substr($date,0,4));
		$mo=strval(substr($date,4,2));
		$da=strval(substr($date,6,2));
		
		$hr=strval(substr($date,8,2));
		$mi=strval(substr($date,10,2));
		$se=strval(substr($date,12,2));
				
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