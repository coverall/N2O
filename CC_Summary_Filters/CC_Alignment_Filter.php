<?php
// $Id: CC_Alignment_Filter.php,v 1.1 2003/07/15 16:49:36 patrick Exp $
//=======================================================================
// CLASS: CC_Alignment_Filter
//=======================================================================

/**
 * This CC_Summary_Filter doesn't filter the value, but does let you
 * set the alignment on a column.
 * 
 * @package CC_Summary_Filters
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

class CC_Alignment_Filter extends CC_Summary_Filter
{
	//-------------------------------------------------------------------
	// CONSTRUCTOR
	//-------------------------------------------------------------------

	/**
	 * This is the constructor.
	 * 
	 * @access public
	 * @param string Alignment (left, center, or right)
	 */

	function CC_Alignment_Filter($alignment)
	{
		$this->alignment = $alignment;
	}
}

?>