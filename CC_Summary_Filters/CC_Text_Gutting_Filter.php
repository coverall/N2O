<?php
// $Id: CC_Text_Gutting_Filter.php,v 1.1 2003/11/15 08:43:42 patrick Exp $
//=======================================================================
// CLASS: CC_Text_Gutting_Filter
//=======================================================================

/**
 * This CC_Summary_Filter guts text values by ripping the center bit out, and replacing it with a "...". It also make it clickable so you can see the full value.
 * 
 * @package CC_Summary_Filters
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

class CC_Text_Gutting_Filter extends CC_Summary_Filter
{
	//-------------------------------------------------------------------
	var $_numCharactersOnLeftAndRight;


	//-------------------------------------------------------------------
	// CONSTRUCTOR
	//-------------------------------------------------------------------

	/**
	 * An integer value is passed indicating how many characters on either side of the string should be shown.
	 *
	 * @param int $numCharactersOnLeftAndRight
	 * @access private
	 */

	function CC_Text_Gutting_Filter($numCharactersOnLeftAndRight = 5)
	{
		$this->setCenterAlignment();
		$this->_numCharactersOnLeftAndRight = $numCharactersOnLeftAndRight;

	}


	//-------------------------------------------------------------------
	// METHOD: processValue()
	//-------------------------------------------------------------------

	function processValue($value, $id)
	{
		if ($this->_numCharactersOnLeftAndRight * 2 >= strlen($value))
		{
			return $value;
		}
		else
		{
			$firstBit = substr($value, 0, $this->_numCharactersOnLeftAndRight);
			$lastBit = substr($value, strlen($value) - $this->_numCharactersOnLeftAndRight, $this->_numCharactersOnLeftAndRight);
			
			return '<a href="javascript:window.alert(\'' . $value . '\');">' . $firstBit . '...' . $lastBit . '</a>';
		}
	}
}

?>