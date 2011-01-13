<?php
// $Id: CC_Text_Shortening_Popup_Filter.php,v 1.1 2003/11/22 19:24:11 jamie Exp $
//=======================================================================
// CLASS: CC_Text_Shortening_Popup_Filter
//=======================================================================

class CC_Text_Shortening_Popup_Filter extends CC_Summary_Filter
{
	//-------------------------------------------------------------------
	var $_numCharactersOnLeftAndRight;


	//-------------------------------------------------------------------
	// CONSTRUCTOR
	//-------------------------------------------------------------------

	function CC_Text_Shortening_Popup_Filter($numCharactersOnLeftAndRight = 5)
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