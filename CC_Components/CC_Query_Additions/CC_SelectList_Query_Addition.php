<?php
// $Id: CC_SelectList_Query_Addition.php,v 1.10 2010/11/11 04:28:32 patrick Exp $
//=======================================================================
// CLASS: CC_SelectList_Query_Addition
//=======================================================================

/** This class represents a select list query addition for the CC_Summary_Search_Component.
  *
  * @package CC_Components
  * @access public
  * @author The Crew <N2O@coverallcrew.com>
  * @copyright Copyright &copy; 2003, Coverall Crew
  */

class CC_SelectList_Query_Addition extends CC_Query_Addition
{
	/**
     * Whether or not to 'and' the query addition text, or 'or' it. 
     *
     * @var bool $_useAnd
     * @access private
     */
    
	var $_useAnd;


	/**
     * The select list...
     *
     * @var CC_SelectList_Field $_selectList
     * @access private
     */
    
	var $_selectList;
    
    
	/**
     * An array of select list options.
     *
     * @var array $_options
     * @access private
     */
     
	var $_options;
     
     
   	/**
     * An array of the queries.
     *
     * @var array $_queries
     * @access private
     */
     
	var $_queries;
        
    	
    /**
     * If set to true, we an use this component dynamically (with a CC_Dynamic_Summary)
     *
     * @var bool $_isDynamic
     * @access private
     */
    
    var $_isDynamic = false;


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

	function CC_SelectList_Query_Addition($name, $useAnd = false)
	{
		$this->name = $name;
		$this->_useAnd = $useAnd;
		$this->_cookieName = session_name() . '_' . $this->name;
		
		$this->_selectList = new CC_AutoSubmit_Select_Field($name . '_queryaddition', '', false, '', '', null, 'Go', true);

		if (array_key_exists($this->_cookieName, $_COOKIE))
		{
			$selectedIndex = $_COOKIE[$this->_cookieName];
			
			if ($this->_selectList->getNumberOfItems() > $selectedIndex)
			{
				$this->_selectList->setSelectedIndex($selectedIndex);
			}
		}
		
		$this->_selectList->setId($name);
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: addQuery
	//-------------------------------------------------------------------

	/**
	 * This method registers a CC_Checkbox_Field with the window.
	 *
	 * @access public
	 * @param string $label The label for the checkbox. 
	 * @param string $queryAdditionText A CC_Query_Addition object. 
	 */

	function addQuery($label, $query)
	{
		$index = sizeof($this->_options);
		$this->_options[] = array($index, $label);		
		$this->_queries[$index] = $query;
		
		$this->_selectList->setOptions($this->_options);

		if (array_key_exists($this->_cookieName, $_COOKIE))
		{
			$selectedIndex = $_COOKIE[$this->_cookieName];
			
			if ($this->_selectList->getNumberOfItems() > $selectedIndex)
			{
				$this->_selectList->setSelectedIndex($selectedIndex);
			}
		}
	}


	//-------------------------------------------------------------------
	// METHOD: getSelectList
	//-------------------------------------------------------------------

	/**
	 * This method returns a CC_SelectList_Field filter at a given index.
	 *
	 * @access public
	 */

	function &getSelectList()
	{
		return $this->_selectList;
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
		$selectedIndex = $this->_selectList->getSelectedIndex();
		
		if (!headers_sent())
		{
			setcookie($this->_cookieName, $selectedIndex, time() + 60*60*24*30);
		}
		
		return $this->_queries[$selectedIndex];
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
		$this->_selectList->setValue($value);
		
		if (!headers_sent())
		{
			setcookie($this->_cookieName, $this->_selectList->getSelectedIndex(), time() + 60*60*24*30);
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
		
		unset($this->_selectList);
		
		if ($this->_isDynamic)
		{
			$this->_selectList = new CC_SelectList_Field($this->getName() . '_queryaddition', '', false, '', '', null);
		
			$this->_selectList->setId($this->getName());

			$this->_selectList->setJavascriptOnChange("filterPage('" . $summaryName . "', '" . $filterName . "', $('" . $filterName . "'), '" . $this->getName() . "'); return false;");
		}
		else
		{
			$this->_selectList = new CC_AutoSubmit_Select_Field($this->getName() . '_queryaddition', '', false, '', '', null, 'Go', true);
		}

		if (array_key_exists($this->_cookieName, $_COOKIE))
		{
			$selectedIndex = $_COOKIE[$this->_cookieName];
			
			if ($this->_selectList->getNumberOfItems() > $selectedIndex)
			{
				$this->_selectList->setSelectedIndex($selectedIndex);
			}
		}
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
		
		$window->registerField($this->_selectList);
	}
}

?>