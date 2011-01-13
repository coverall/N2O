<?php
// $Id: CC_Date_Range_Query_Addition.php,v 1.3 2010/11/11 04:28:32 patrick Exp $
//=======================================================================
// CLASS: CC_Date_Range_Query_Addition
//=======================================================================

class CC_Date_Range_Query_Addition extends CC_Query_Addition
{
    var $_startDateField;
    var $_endDateField;
    var $_startDateCookieName;
    var $_endDateCookieName;
    
    var $dateColumn;
    
	//-------------------------------------------------------------------
	// CONSTRUCTOR
	//-------------------------------------------------------------------

	/**
	 * This method generates HTML for displaying the button. Sublasses override this appropriately.
	 *
	 * @access public
	 * @param bool $useAnd True for using 'and', false for using 'or' when adding the checkboxes to the query 
	 */

	function CC_Date_Range_Query_Addition($name, $dateColumn = 'DATE_ADDED')
	{
		$this->name = $name;
		$this->dateColumn = $dateColumn;
		$this->_startDateCookieName = session_name() . '_' . $this->name . '_start';
		$this->_endDateCookieName = session_name() . '_' . $this->name . '_end';
		$this->_startDateField = new CC_Date_Field($name . '_start', 'After Date', false, 3, 3, date('Y'), date('Y') - 10, date('Y'));
		$this->_endDateField = new CC_Date_Field($name . '_end', 'Before Date', false, 3, 3, date('Y'), date('Y') - 10, date('Y'));
		
		if (array_key_exists($this->_startDateCookieName, $_COOKIE))
		{
			$defaultStartDateValue = date('Y-m-d', $_COOKIE[$this->_startDateCookieName]);
		}
		else
		{
			$defaultStartDateValue = date('Y-m-d');
		}
		
		if (array_key_exists($this->_endDateCookieName, $_COOKIE))
		{
			$defaultEndDateValue = date('Y-m-d', $_COOKIE[$this->_endDateCookieName]);
		}
		else
		{
			$defaultEndDateValue = date('Y-m-d');
		}
		
		$this->_startDateField->setValue($defaultStartDateValue);
		$this->_endDateField->setValue($defaultEndDateValue);
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getQueryAddition()
	//-------------------------------------------------------------------

	/**
	 * This method generates HTML for displaying the button. Sublasses override this appropriately.
	 *
	 * @access public
	 */

	function getQueryAddition()
	{
		$queryAddition = $this->dateColumn . " >= '" . $this->_startDateField->getValue('%u-%u-%u 00:00:00') . "' and " . $this->dateColumn . " <= '" .  $this->_endDateField->getValue('%u-%u-%u 23:59:59') . "'";
		
		error_log($queryAddition);
		
		if (!headers_sent())
		{
			setcookie($this->_startDateCookieName, convertMysqlDateToTimestamp($this->_startDateField->getValue('%u-%0u-%0u'), time() + 60*60*24*30));
			
			setcookie($this->_endDateCookieName, convertMysqlDateToTimestamp($this->_endDateField->getValue('%u-%0u-%0u'), time() + 60*60*24*30));			
		}

		return $queryAddition;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: register
	//-------------------------------------------------------------------

	/**
	 * This is a callback method that gets called by the window when the
	 * component is registered.
	 *
	 * @access public
	 */

	function register(&$window)
	{
		$window->registerCustomComponent($this);
		
		$window->registerField($this->_startDateField);
		$window->registerField($this->_endDateField);
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getHTML
	//-------------------------------------------------------------------

	function getHTML()
	{
		return 'Date Range: ' .  $this->_startDateField->getHTML() . ' to ' . $this->_endDateField->getHTML();
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getLabel
	//-------------------------------------------------------------------

	function getLabel()
	{
		return 'Date Range'; //$this->_startDateField->getLabel();
	}
}

?>