<?php
// $Id: CC_Checkbox_Query_Addition.php,v 1.11 2010/11/11 04:28:32 patrick Exp $
//=======================================================================
// CLASS: CC_Checkbox_Query_Addition
//=======================================================================

/** This class represents a checkbox query addition for the CC_Summary_Search_Component. This class is meant to be extended, and its getQueryAddition() method implemented. It should return a fragment of a where clause that will be placed inside parentheses.
  *
  * @package CC_Components
  * @access public
  * @author The Crew <N2O@coverallcrew.com>
  * @copyright Copyright &copy; 2003, Coverall Crew
  */

class CC_Checkbox_Query_Addition extends CC_Query_Addition
{
	/**
     * Whether or not to 'and' the query addition text, or 'or' it. 
     *
     * @var bool $_useAnd
     * @access private
     */
    
    var $_useAnd;


	/**
     * A 2D array of checkbox fields to use as filters with their respective query addition text.
     *
     * @var array $_checkboxFilters
     * @access private
     */
    
    var $_checkboxFilters;


    /**
     * If set to true, we an use this component dynamically (with a CC_Dynamic_Summary)
     *
     * @var bool $_isDynamic
     * @access private
     */
    
    var $_isDynamic = false;
    var $_dynamicSummaryName = '';
    var $_dynamicFilterName = '';


    /**
     * Save a cookie for the future!
     *
     * @var string $_cookieName
     * @access private
     */
    
    var $_cookieName = false;

    
    	
	//-------------------------------------------------------------------
	// CONSTRUCTOR
	//-------------------------------------------------------------------

	/**
	 * This method generates HTML for displaying the button. Sublasses override this appropriately.
	 *
	 * @access public
	 * @param bool $useAnd True for using 'and', false for using 'or' when adding the checkboxes to the query 
	 */

	function CC_Checkbox_Query_Addition($name, $useAnd = false)
	{
		$this->name = $name;
		$this->_useAnd = $useAnd;

		$this->_cookieName = session_name() . '_' . $this->name;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: addCheckboxFilter
	//-------------------------------------------------------------------

	/**
	 * This method registers a CC_Checkbox_Field with the window.
	 *
	 * @access public
	 * @param string $label The label for the checkbox. 
	 * @param string $queryAdditionText A CC_Query_Addition object. 
	 */

	function addCheckboxFilter($label, $queryAdditionText, $checked = false)
	{
		$numCheckboxes = sizeof($this->_checkboxFilters);
		$checkboxField =  new CC_Checkbox_Field($this->name  .'_cb_' . $numCheckboxes, $label, false, $checked);
		
		$newCheckboxEntry = array();
		$newCheckboxEntry[0] = &$checkboxField;
		$newCheckboxEntry[1] = $queryAdditionText;
		
		$this->_checkboxFilters[] = &$newCheckboxEntry;
		
		if ($this->_isDynamic)
		{
			$checkboxField->setOnClickAction("filterPage('" . $this->_dynamicSummaryName . "', '" . $this->_dynamicFilterName . "', $('" . $this->_dynamicFilterName . "'), '" . $this->getName() . "', " . $numCheckboxes . ");");
		
			$checkboxField->setId($this->getName() . '_' . $numCheckboxes);
		}

		$index = sizeof($this->_checkboxFilters);

		if (array_key_exists($this->_cookieName . '_' . ($index - 1), $_COOKIE))
		{	
			$checkboxField->setValue($_COOKIE[$this->_cookieName . '_' . ($index - 1)]);
		}

		unset($newCheckboxEntry);
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getCheckboxFilterAtIndex
	//-------------------------------------------------------------------

	/**
	 * This method returns a CC_Checkbox_Field filter at a given index.
	 *
	 * @access public
	 * @param int $index The index of the checkbox filter. 
	 */

	function &getCheckboxFilterAtIndex($index)
	{
		if ($index < sizeof($this->_checkboxFilters))
		{
			return $this->_checkboxFilters[$index][0];
		}
		else
		{
			trigger_error('There was no checkbox found at the given index.'. E_USER_WARNING);
		}		
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
		$booleanOperator = $this->_useAnd ? ' and ': ' or ';
		
		$first = true;
		$size = sizeof($this->_checkboxFilters);
		$queryAddition = '';
		
		for ($i = 0; $i < $size; $i++)
		{
			$checkBoxField = &$this->_checkboxFilters[$i][0];
			
			if ($checkBoxField->getValue())
			{
				$queryAddition .= ($first) ? $this->_checkboxFilters[$i][1] : $booleanOperator . $this->_checkboxFilters[$i][1]; 
				$first = false;

			}
			
			if (!headers_sent())
			{
				setcookie($this->_cookieName . '_' . $i, $checkBoxField->getValue() , time() + 60*60*24*30);
			}

			unset($checkBoxField);
		}

		unset($booleanOperator, $first, $size);
		
		return $queryAddition;
	}
	


	//-------------------------------------------------------------------
	// METHOD: setValueAtIndex
	//-------------------------------------------------------------------

	/**
	 * Set's the value of the search field...
	 *
	 * @access public
	 */

	function setValueAtIndex($value, $index)
	{
		$checkbox = &$this->_checkboxFilters[$index][0];
		$checkbox->setValue(($value == 'true' ? 1 : 0));
		
		if (!headers_sent())
		{
			setcookie($this->_cookieName . '_' . $index, $checkbox->getValue() , time() + 60*60*24*30);
		}
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
		$this->_dynamicSummaryName = $summaryName;
		$this->_dynamicFilterName = $filterName;
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
		
		$size = sizeof($this->_checkboxFilters);
		
		for ($i = 0; $i < $size; $i++)
		{
			$window->registerField($this->_checkboxFilters[$i][0]);
		}
		
		unset($size);		
	}
}

?>