<?php
// $Id: CC_NoBreak_Filter.php,v 1.5 2003/07/06 01:31:34 jamie Exp $
//=======================================================================
// CLASS: CC_NoBreak_Filter
//=======================================================================

/**
 * This CC_Summary_Filter adds a NOBR tag around text so it doesn't wrap in the CC_Summary.
 * 
 * @package CC_Summary_Filters
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

class CC_NoBreak_Filter extends CC_Summary_Filter
{
	//-------------------------------------------------------------------
	// METHOD: processValue()
	//-------------------------------------------------------------------

	/**
	 * This method wraps the text in a NOBR tag.
	 * 
	 * @access public
	 * @param string $value The text to wrap with the tag. 
	 * @return string HTML with the text wrapped in <NOBR></NOBR>. 
	 */

	function processValue($value)
	{
		return "<nobr>$value</nobr>";
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: textFriendlyProcessValue()
	//-------------------------------------------------------------------

	/**
	 * This method wraps the text in nuttin'. We wanna be text-friendly, yo.
	 * 
	 * @access public
	 * @param string $value The text to filter. 
	 * @param int $id The record id. 
	 * @return int The unaltered passed text value. It's text-friendly, ok?
	 */

	function textFriendlyProcessValue($value, $id)
	{
		return $value;
	}

}

?>