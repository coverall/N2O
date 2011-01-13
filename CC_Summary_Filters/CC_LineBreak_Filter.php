<?php
// $Id: CC_LineBreak_Filter.php,v 1.5 2003/07/06 01:31:34 jamie Exp $
//=======================================================================
// CLASS: CC_LineBreak_Filter
//=======================================================================

/**
 * This CC_Summary_Filter filters convert text line breaks (ie. \r and \n) into HTML compatible <br> tags.
 * 
 * @package CC_Summary_Filters
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */
 
class CC_LineBreak_Filter extends CC_Summary_Filter
{
	//-------------------------------------------------------------------
	// METHOD: processValue()
	//-------------------------------------------------------------------

	/**
	 * This method filters the text to be HTML line-break compatible.
	 * 
	 * @access public
	 * @param string $value The text to filter. 
	 * @return string HTML with <br> tags instead of \r and \n return and newline characters.
	 */

	function processValue($value)
	{
		return ereg_replace("(\r\n|\n|\r)", "<br>", $value);
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: textFriendlyProcessValue()
	//-------------------------------------------------------------------

	/**
	 * This method returns the same text that it is passed.
	 * 
	 * @access public
	 * @param string $value The text to filter. 
	 * @param int $id The record id.
	 * @return string The unaltered text.
	 */

	function textFriendlyProcessValue($value, $id)
	{
		return $value;
	}
}

?>