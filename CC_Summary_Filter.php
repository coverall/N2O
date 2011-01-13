<?php
// $Id: CC_Summary_Filter.php,v 1.9 2003/10/31 01:32:27 patrick Exp $
//=======================================================================
// CLASS: CC_Summary_Filter
//=======================================================================

/**
 * The CC_Summary_Filter class allows one to filter columns in a summary. The filters of these colums are provided by subclasses of this class in the processValue (and textFrienlyProcessValue) methods. Classes which extend this, can provide any filter processing they so desire.
 * 
 * @package CC_Summary_Filters
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 * @see CC_Summary::registerFilter()
 */

class CC_Summary_Filter
{
	/**
	 * The alignment to use for the content of the column.
	 *
	 * @var string $alignment
	 * @access private
	 */
	 
	var $alignment = 'left';


	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Summary_Filter
	//-------------------------------------------------------------------

	/**
	 * This method gets a field from the record of a given name.
	 *
	 * @access public
	 * @param string $fieldName The name of the record to return.
	 * @return CC_Field A reference to a field of the given name.
	 */


	function CC_Summary_Filter()
	{
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setLeftAlignment()
	//-------------------------------------------------------------------

	/**
	 * This method sets the summary column to be left-aligned.
	 *
	 * @access public
	 */
	
	function setLeftAlignment()
	{
		$this->alignment = 'left';
	}


	//-------------------------------------------------------------------
	// METHOD: setCenterAlignment()
	//-------------------------------------------------------------------
	
	/**
	 * This method sets the summary column to be centered.
	 *
	 * @access public
	 */

	function setCenterAlignment()
	{
		$this->alignment = 'center';
	}


	//-------------------------------------------------------------------
	// METHOD: setRightAlignment()
	//-------------------------------------------------------------------
	
	/**
	 * This method sets the summary column to be right-aligned.
	 *
	 * @access public
	 */

	function setRightAlignment()
	{
		$this->alignment = 'right';
	}


	//-------------------------------------------------------------------
	// METHOD: processValue()
	//-------------------------------------------------------------------

	/**
	 * This method does the filtering. Subclasses should return their filtered value from within this method.
	 * 
	 * @access public
	 * @param mixed $value The value of the column taken from the database.
	 * @param int $id The row's record id. This may or may not be needed to filter the value.
	 * @param array $row The entire row (associative array).
	 */

	function processValue($value, $id, $row)
	{
		return $value;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: textFriendlyProcessValue()
	//-------------------------------------------------------------------

	/**
	 * This method does the filtering for raw data (no html). By default, this method simply calls processValue().
	 * 
	 * @access public
	 * @param mixed $value The value of the column taken from the database.
	 * @param int $id The row's record id. This may or may not be needed to filter the value.
	 * @see processValue()
	 */

	function textFriendlyProcessValue($value, $id, $row)
	{
		return strip_tags($this->processValue($value, $id, $row));
	}
}

?>