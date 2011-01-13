<?php
// $Id: CC_SelectList_Query_Addition.php,v 1.2 2004/12/08 05:24:38 mike Exp $
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
		$this->_selectList = &new CC_AutoSubmit_Select_Field($name . '_queryaddition', '', false, '', '', null, 'Go', true);
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
		$this->_options[] = array($query, $label);
		
		$this->_selectList->setOptions($this->_options);
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
		
		return $this->_options[$selectedIndex][0];
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