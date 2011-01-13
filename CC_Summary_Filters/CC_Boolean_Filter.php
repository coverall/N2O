<?php
// $Id: CC_Boolean_Filter.php,v 1.2 2004/02/17 18:17:49 patrick Exp $
//=======================================================================
// CLASS: CC_Boolean_Filter
//=======================================================================

/**
 * This CC_Summary_Filter filters a boolean value and returns 'Yes' or 'No' as opposed to the more esoteric 'true' or 'false'.
 * 
 * @package CC_Summary_Filters
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */
 
class CC_Boolean_Filter extends CC_Summary_Filter
{
	//-------------------------------------------------------------------
	// CONSTRUCTOR
	//-------------------------------------------------------------------

	/**
	 * The alignment is centered automatically in the constructor.
	 *
	 * @access private
	 */

	function CC_Boolean_Filter()
	{
		$this->setCenterAlignment();
	}


	//-------------------------------------------------------------------
	// METHOD: processValue()
	//-------------------------------------------------------------------

	/**
	 * This method filters boolean values.
	 * 
	 * @access public
	 * @param bool $value The boolean value to filter. 
	 * @return string 'Yes' for true, 'No' for false, '-' otherwise.
	 */

	function processValue($value)
	{
		if ($value == 1 || (string)$value == 't' || (string)$value == 'true')
		{
			return 'Yes';
		}
		else if ($value == 0 || (string)$value == 'f' || (string)$value == 'false')
		{
			return 'No';
		}
		else
		{
			return '-';
		}
	}
}

?>