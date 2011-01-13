<?php
// $Id: CC_Checkbox_Query_Addition.php,v 1.4 2004/12/08 03:15:16 mike Exp $
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

	function addCheckboxFilter($label, $queryAdditionText)
	{
		$numCheckboxes = sizeof($this->_checkboxFilters);
		$checkboxField =  &new CC_Checkbox_Field($this->name  .'_cb_' . $numCheckboxes, $label);
		
		$newCheckboxEntry = array();
		$newCheckboxEntry[0] = &$checkboxField;
		$newCheckboxEntry[1] = $queryAdditionText;
		
		$this->_checkboxFilters[] = &$newCheckboxEntry;
		
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
		
		for ($i = 0; $i < $size; $i++)
		{
			$checkBoxField = &$this->_checkboxFilters[$i][0];
			
			if ($checkBoxField->getValue())
			{
				$queryAddition .= ($first) ? $this->_checkboxFilters[$i][1] : $booleanOperator . $this->_checkboxFilters[$i][1]; 
				$first = false;
			}
			
			unset($checkBoxField);
		}

		unset($booleanOperator, $first, $size);
		
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
		
		$size = sizeof($this->_checkboxFilters);
		
		for ($i = 0; $i < $size; $i++)
		{
			$window->registerField($this->_checkboxFilters[$i][0]);
		}
		
		unset($size);		
	}
}

?>