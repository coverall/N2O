<?php
// $Id: CC_Id_Link_Filter.php,v 1.3 2006/12/11 00:06:30 jamie Exp $
//=======================================================================
// CLASS: CC_Id_Link_Filter
//=======================================================================

/**
 * This CC_Id_Link_Filter incorporates the row's id into a url. You define the url prefix and suffix.
 * 
 * @package CC_Summary_Filters
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

class CC_Id_Link_Filter extends CC_Summary_Filter
{
	//-------------------------------------------------------------------
	var $_urlPrefix;
	var $_urlSuffix;
	var $_target;

	//-------------------------------------------------------------------
	// CONSTRUCTOR
	//-------------------------------------------------------------------

	/**
	 * The alignment is right justified automatically in the constructor.
	 *
	 * @param bool $short
	 * @access private
	 */

	function CC_Id_Link_Filter($urlPrefix = '', $urlSuffix = '', $target = '')
	{
		$this->_urlPrefix = $urlPrefix;
		$this->_urlSuffix = $urlSuffix;
		$this->_target = $target;
	}


	//-------------------------------------------------------------------
	// METHOD: processValue()
	//-------------------------------------------------------------------

	/**
	 * This method filters the passed timestamp to the appropriate text relative to now.
	 * 
	 * @access public
	 * @param mixed $value The number to filter. 
	 * @return string A link.
	 */

	function processValue($value, $id)
	{
		if ($this->_target != '')
		{
			$targetString = ' target="' . $this->_target . '"';
		}
		else
		{
			$targetString = '';
		}
		
		return '<a href="' . $this->_urlPrefix . $id . $this->_urlSuffix . (!isset($_COOKIE[session_name()]) ? '?' . session_name() . '=' . session_id() : '') . '"' . $targetString . '>' . $value . '</a>';
	}
}

?>