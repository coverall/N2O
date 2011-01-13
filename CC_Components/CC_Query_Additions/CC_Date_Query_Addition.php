<?php
// $Id: CC_Date_Query_Addition.php,v 1.5 2010/11/11 04:28:32 patrick Exp $
//=======================================================================
// CLASS: CC_Date_Query_Addition
//=======================================================================

/** This class represents a datetime query addition for the CC_Summary_Search_Component.
  *
  * @package CC_Components
  * @access public
  * @author The Crew <N2O@coverallcrew.com>
  * @copyright Copyright &copy; 2003, Coverall Crew
  */

class CC_Date_Query_Addition extends CC_Query_Addition
{
	/**
     * Whether or not to 'and' the query addition text, or 'or' it. 
     *
     * @var bool $_operator
     * @access private
     */
    
    var $_operator;


	/**
     * The datetime field...
     *
     * @var CC_Date_Field $_dateField
     * @access private
     */
    
    var $_dateField;
    
    
    /**
     * The field name to compare
     *
     * @var CC_Date_Field $_dateField
     * @access private
     */
    
    var $_fieldName;
    
    
    /**
     * Save cookies for the future!
     *
     * @var string $_dateCookieName
     * @var string $_monthCookieName
     * @var string $_yearCookieName
     * @access private
     */
    
    var $_dateCookieName = false;
    var $_monthCookieName = false;
    var $_yearCookieName = false;

    
    	
	//-------------------------------------------------------------------
	// CONSTRUCTOR
	//-------------------------------------------------------------------

	/**
	 * This method generates HTML for displaying the datetime field. Sublasses override this appropriately.
	 *
	 * @access public
	 * @param bool $useAnd True for using 'and', false for using 'or' when adding the checkboxes to the query 
	 */

	function CC_Date_Query_Addition($name, $_fieldName, $_operator = '=')
	{
		$this->name = $name;
		$this->_dateField = new CC_Date_Field($name . '_queryaddition', '', false);
		$this->_operator = $_operator;
		$this->_fieldName = $_fieldName;
		
		$this->_dateCookieName = session_name() . '_' . $this->name . '_date';
		$this->_monthCookieName = session_name() . '_' . $this->name . '_month';
		$this->_yearCookieName = session_name() . '_' . $this->name . '_year';
		
		if (array_key_exists($this->_dateCookieName, $_COOKIE))
		{
			$dateSelectedIndex = $_COOKIE[$this->_dateCookieName];
			
			if ($this->_dateField->dateField->getNumberOfItems() > $dateSelectedIndex)
			{
				$this->_dateField->dateField->setSelectedIndex($dateSelectedIndex);
			}
		}
		
		if (array_key_exists($this->_monthCookieName, $_COOKIE))
		{
			$monthSelectedIndex = $_COOKIE[$this->_monthCookieName];
			
			if ($this->_dateField->monthField->getNumberOfItems() > $monthSelectedIndex)
			{
				$this->_dateField->monthField->setSelectedIndex($monthSelectedIndex);
			}
		}

		if (array_key_exists($this->_yearCookieName, $_COOKIE))
		{
			$yearSelectedIndex = $_COOKIE[$this->_yearCookieName];
			
			if ($this->_dateField->yearField->getNumberOfItems() > $yearSelectedIndex)
			{
				$this->_dateField->yearField->setSelectedIndex($yearSelectedIndex);
			}
		}

	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getSelectList
	//-------------------------------------------------------------------

	/**
	 * This method returns a CC_Date_Field filter at a given index.
	 *
	 * @access public
	 */

	function &getDateField()
	{
		return $this->_dateField;
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
		$queryAddition = $this->_fieldName . ' ' . $this->_operator . " '" . $this->_dateField->getValue() . "'";
		
		if (!headers_sent())
		{
			$dateSelectedIndex = $this->_dateField->dateField->getSelectedIndex();
			$monthSelectedIndex = $this->_dateField->monthField->getSelectedIndex();
			$yearSelectedIndex = $this->_dateField->yearField->getSelectedIndex();
			
			setcookie($this->_dateCookieName, $dateSelectedIndex, time() + 60*60*24*30);
			setcookie($this->_monthCookieName, $monthSelectedIndex, time() + 60*60*24*30);
			setcookie($this->_yearCookieName, $yearSelectedIndex, time() + 60*60*24*30);			
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
		
		$window->registerField($this->_dateField);
	}

	
	//-------------------------------------------------------------------
	// METHOD: setDynamic()
	//-------------------------------------------------------------------

	/**
	 * Sets the label of the component. Defaults to 'Search-O-Matic'.
	 *
	 * @access public
	 */

	function setDynamic($dynamic, $summaryName, $filterName)
	{
		$this->_isDynamic = $dynamic;
		
		unset($this->_dateField);
		
		if ($this->_isDynamic)
		{
			$this->_dateField = new CC_Date_Field($this->getName() . '_queryaddition', '', false);
			$this->_dateField->dateField->setId($this->getName() . '_queryaddition_date');
			$this->_dateField->monthField->setId($this->getName() . '_queryaddition_month');
			$this->_dateField->yearField->setId($this->getName() . '_queryaddition_year');
			
			$this->_dateField->monthField->setJavascriptOnChange("filterPage('" . $summaryName . "', '" . $filterName . "', $('" . $filterName . "'), '" . $this->getName() . "'); return false;");
			$this->_dateField->dateField->setJavascriptOnChange("filterPage('" . $summaryName . "', '" . $filterName . "', $('" . $filterName . "'), '" . $this->getName() . "'); return false;");
			$this->_dateField->yearField->setJavascriptOnChange("filterPage('" . $summaryName . "', '" . $filterName . "', $('" . $filterName . "'), '" . $this->getName() . "'); return false;");
		}
		else
		{
			$this->_dateField = new CC_Date_Field($this->getName() . '_queryaddition', '', false);
		}

		if (array_key_exists($this->_dateCookieName, $_COOKIE))
		{
			$dateIndex = $_COOKIE[$this->_dateCookieName];
			
			if ($this->_dateField->dateField->getNumberOfItems() > $dateIndex)
			{
				$this->_dateField->dateField->setSelectedIndex($dateIndex);
			}
		}
		
		if (array_key_exists($this->_monthCookieName, $_COOKIE))
		{
			$monthIndex = $_COOKIE[$this->_monthCookieName];
			
			if ($this->_dateField->monthField->getNumberOfItems() > $monthIndex)
			{
				$this->_dateField->monthField->setSelectedIndex($monthIndex);
			}
		}
		
		if (array_key_exists($this->_yearCookieName, $_COOKIE))
		{
			$yearIndex = $_COOKIE[$this->_yearCookieName];
			
			if ($this->_dateField->yearField->getNumberOfItems() > $yearIndex)
			{
				$this->_dateField->yearField->setSelectedIndex($yearIndex);
			}
		}
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setValue()
	//-------------------------------------------------------------------

	/**
	 * Set's the value of the search field...
	 *
	 * @access public
	 */

	function setValue($value)
	{
		$this->_dateField->setValue($value);
		
		if (!headers_sent())
		{
			$dateSelectedIndex = $this->_dateField->dateField->getSelectedIndex();
			$monthSelectedIndex = $this->_dateField->monthField->getSelectedIndex();
			$yearSelectedIndex = $this->_dateField->yearField->getSelectedIndex();
			
			setcookie($this->_dateCookieName, $dateSelectedIndex, time() + 60*60*24*30);
			setcookie($this->_monthCookieName, $monthSelectedIndex, time() + 60*60*24*30);
			setcookie($this->_yearCookieName, $yearSelectedIndex, time() + 60*60*24*30);			
		}
	}

}

?>