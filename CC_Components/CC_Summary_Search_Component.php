<?php
// $Id: CC_Summary_Search_Component.php,v 1.31 2010/11/11 04:28:32 patrick Exp $
//=======================================================================
// CLASS: CC_Summary_Search_Component
//=======================================================================

/** This class a custom component used for add search capabilities into
  * a CC_Summary. It contains a search field, a search button, and
  * optionally a clear button.
  *
  * @package CC_Components
  * @access public
  * @author The Crew <N2O@coverallcrew.com>
  * @copyright Copyright &copy; 2003, Coverall Crew
  */

class CC_Summary_Search_Component extends CC_Component
{
	
	/**
     * The summary on which to search.
     *
     * @var CC_Summary $_summary
     * @access private
     */
    
    var $_summary;


	/**
     * The search field.
     *
     * @var CC_Text_Field $_searchField
     * @access private
     */
    
    var $_searchField;
    
    
	/**
     * The search button.
     *
     * @var CC_Button $_searchButton
     * @access private
     */
    
    var $_searchButton;


	/**
     * The clear button.
     *
     * @var CC_Button $_clearButton
     * @access private
     */
    
    var $_clearButton;


	/**
     * The flag to show the clear button.
     *
     * @var boolean $_showClearButton
     * @access private
     */
    
    var $_showClearButton;


	/**
     * A container to hold CC_Query_Addition objects.
     *
     * @var array $_queryAdditions
     * @access private
     */
    
    var $_queryAdditions;
    
    
    /**
     * The delimiter to use for separating values to be OR'd.
     *
     * @var array $_orDelimiter
     * @access private
     */
    
    var $_orDelimiter = ',';


    /**
     * If set to true, we will assume there's no where clause.
     *
     * @var bool $_assumeNoWhereClause
     * @access private
     */
    
    var $_assumeNoWhereClause = false;
    
    
    
    /**
     * If set to true, we an use this component dynamically (with a CC_Dynamic_Summary)
     *
     * @var bool $_isDynamic
     * @access private
     */
    
    var $_isDynamic = false;



	//-------------------------------------------------------------------
	// CONSTRUCTOR
	//-------------------------------------------------------------------

	/**
	 * This is the constructor. It is very important that when you contruct
	 * this class, you use the & to obtain a reference.
	 * eg. $search = new CC_Summary_Search_Component(...);
	 * 
	 * @access public
	 * @param string $name The name to set.
	 * @param CC_Summary $summary The summary.
	 * @param array $columns The columns to search.
	 * @param string $searchButtonLabel The label of the search field.
	 * @param boolean $showClearButton Do we show the clear button?
	 */

	function CC_Summary_Search_Component($name, &$summary, $columns, $searchButtonLabel = 'Search', $showClearButton = true)
	{
		$this->setName($name);
		
		$this->_summary = &$summary;
		if (preg_match('/Safari/', $_SERVER['HTTP_USER_AGENT']))
		{
			$this->_searchField = new CC_Safari_Search_Field($name, '', false, '', 24);
			$this->_searchField->setId($name);
		}
		else
		{
			$this->_searchField = new CC_Text_Field($name, 'Search-O-Matic:', false, '', 24);
			$this->_searchField->setId($name);
		}
		$this->_searchButton = new CC_Image_Button($searchButtonLabel, '/N2O/CC_Images/search.png', 16, 16);
		$this->_searchButton->registerHandler(new CC_Summary_Search_Handler($this, $columns));
		$this->_searchButton->setValidateOnClick(false);
		
		if ($showClearButton)
		{
			$this->_clearButton = new CC_Image_Button('Clear', '/N2O/CC_Images/clear.png', 16, 16);
			$this->_clearButton->registerHandler(new CC_Summary_ClearSearch_Handler($this->_searchField, $this->_searchButton));
			$this->_clearButton->setValidateOnClick(false);
			$this->_showClearButton = true;
		}
	}


	//-------------------------------------------------------------------
	// METHOD: setLabel()
	//-------------------------------------------------------------------

	/**
	 * Sets the label of the component. Defaults to 'Search-O-Matic'.
	 *
	 * @access public
	 */

	function setLabel($label)
	{
		$this->_searchField->setLabel($label);
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setORDelimiter()
	//-------------------------------------------------------------------

	/**
	 * Sets the OR delimiter. Defaults to ','.
	 *
	 * @access public
	 */

	function setORDelimiter($_orDelimiter)
	{
		$this->_orDelimiter = $_orDelimiter;
	}
	

	//-------------------------------------------------------------------
	// METHOD: getHTML()
	//-------------------------------------------------------------------

	/**
	 * This method generates HTML for displaying the button. Sublasses override this appropriately.
	 *
	 * @access public
	 */

	function getHTML($showLabel = true)
	{
		$html  = '<table class="ccSummarySearch" border="0"><tr valign="middle"><td>';
		
		if ($showLabel)
		{
			$html .= $this->_searchField->getLabel() . '</td><td>';
		}
		
		$html .= $this->_searchField->getHTML() . '</td><td>';
		$html .= $this->_searchButton->getHTML() . '</td>';
		if ($this->_showClearButton)
		{
			$html .= '<td>' . $this->_clearButton->getHTML() . '</td>';
		}
		$html .= '</tr></table>';
		
		
		if ($this->_isDynamic)
		{
			$html .= $this->getJavascript();
		}
		
		return $html;
	}


	//-------------------------------------------------------------------
	// METHOD: getLabel()
	//-------------------------------------------------------------------

	/**
	 * This method get the label for the search component.
	 *
	 * @access public
	 */

	function getLabel()
	{
		return $this->_searchField->getLabel();
	}


	//-------------------------------------------------------------------
	// METHOD: setStyle
	//-------------------------------------------------------------------

	/**
	 * This method sets the component's CSS style.
	 *
	 * @access public
	 * @param string $style The CSS style to set. 
	 */

	function setStyle($style)
	{
		$this->_searchField->setStyle($style);
		$this->_searchButton->setStyle($style);

		if ($this->_showClearButton)
		{		
			$this->_clearButton->setStyle($style);
		}
	}


	//-------------------------------------------------------------------
	// METHOD: setSearchButtonDefault
	//-------------------------------------------------------------------

	/**
	 * This method makes the search button default in the window. It must
	 * be called after the component is registered with the window.
	 *
	 * @access public
	 * @param string $style The CSS style to set. 
	 */

	function setSearchButtonDefault(&$window)
	{
		$window->setDefaultButton($this->_searchButton);
	}


	//-------------------------------------------------------------------
	// METHOD: getSearchButton
	//-------------------------------------------------------------------

	/**
	 * This method returns a reference to the search button.
	 *
	 * @access public
	 */

	function &getSearchButton()
	{
		return $this->_searchButton;
	}


	//-------------------------------------------------------------------
	// METHOD: search
	//-------------------------------------------------------------------

	/**
	 * This method clicks the search button.
	 *
	 * @access public
	 */

	function &search()
	{
		$this->_searchButton->click();
	}


	//-------------------------------------------------------------------
	// METHOD: clear
	//-------------------------------------------------------------------

	/**
	 * This method clicks the clear button.
	 *
	 * @access public
	 */

	function &clear()
	{
		$this->_clearButton->click();
	}


	//-------------------------------------------------------------------
	// METHOD: getSearchField
	//-------------------------------------------------------------------

	/**
	 * This method returns a reference to the search field.
	 *
	 * @access public
	 */

	function &getSearchField()
	{
		return $this->_searchField;
	}


	//-------------------------------------------------------------------
	// METHOD: register
	//-------------------------------------------------------------------

	/**
	 * This is a callback method that gets called by the window when the
	 * component is registered. It's up to the component to decide which
	 * registerXXX() method it should call on the window. Should your
	 * custom component consist of multiple components, you may need to
	 * make multiple calls.
	 *
	 * @access public
	 */

	function register(&$window)
	{
		$window->registerField($this->_searchField);
		$window->registerButton($this->_searchButton);

		if ($this->_showClearButton)
		{		
			$window->registerButton($this->_clearButton);
		}
		
		$size = sizeof($this->_queryAdditions);
		
		$clickButtonHandler = new CC_Click_Button_Handler($this->_searchButton);
		
		for ($i = 0; $i < $size; $i++)
		{
			if (!$this->_isDynamic)
			{
				if ($this->_queryAdditions[$i] instanceof CC_SelectList_Query_Addition)
				{
					$this->_queryAdditions[$i]->_selectList->registerHandler($clickButtonHandler);
				}
			}
			$window->registerComponent($this->_queryAdditions[$i]);
		}
		
		$window->registerCustomComponent($this);
		
		// make sure the first view is the filtered view!
		$this->search();
	}


	//-------------------------------------------------------------------
	// METHOD: addQueryAddition
	//-------------------------------------------------------------------

	/**
	 * This method registers a CC_Query_Addition with the search component.
	 *
	 * @access public
	 * @param CC_Query_Addition $queryAddition A CC_Query_Addition object. 
	 */

	function addQueryAddition(&$queryAddition)
	{
		if ($queryAddition instanceof CC_Query_Addition)
		{
			$this->_queryAdditions[] = &$queryAddition;
		}
		else
		{
			trigger_error('Received an object that does not extend CC_Query_Addition. Ignoring...', E_USER_WARNING);
		}
	}


	//-------------------------------------------------------------------
	// METHOD: showClearButton()
	//-------------------------------------------------------------------

	/**
	 * Show the clear button?
	 *
	 * @param boolean $show Show the button? (true/false)
	 * @access public
	 */

	function showClearButton($show)
	{
		$this->_showClearButton = $show;
	}


	//-------------------------------------------------------------------
	// METHOD: setAssumeNoWhereClause()
	//-------------------------------------------------------------------

	/**
	 * Sets the label of the component. Defaults to 'Search-O-Matic'.
	 *
	 * @access public
	 */

	function setAssumeNoWhereClause($noWhere)
	{
		$this->_assumeNoWhereClause = $noWhere;
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
		$this->_searchField->setValue($value);
	}


	//-------------------------------------------------------------------
	// METHOD: setDynamic()
	//-------------------------------------------------------------------

	/**
	 * Sets the label of the component. Defaults to 'Search-O-Matic'.
	 *
	 * @access public
	 */

	function setDynamic($dynamic)
	{
		$this->_isDynamic = $dynamic;
		
		if ($this->_isDynamic)
		{
			$this->_searchField->setJavascriptOnKeyup("filterPage('" . $this->_summary->getName() . "', '" . $this->getName() . "', this); return false;");
		}
	}


	//-------------------------------------------------------------------
	// METHOD: getJavascript()
	//-------------------------------------------------------------------

	/** 
	  * Javascript methods needed for dynamic functionality
	  *
	  * @access public
	  * @return javascript methods...
	  */

	function getJavascript()
	{
		return '<script type="text/javascript" src="/N2O/javascript/cc_summary_search_component.js"></script>';
	}

}

?>