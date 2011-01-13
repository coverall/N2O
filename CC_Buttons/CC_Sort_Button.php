<?php
// $Id: CC_Sort_Button.php,v 1.22 2008/06/05 18:25:56 mike Exp $
//=======================================================================
// CLASS: CC_Sort_Button
//=======================================================================

/** 
 * This CC_Button subclass represents the button at the top of CC_SUmmary columns used for indicating which columns to sort by.
 *
 * @package CC_Buttons
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 * @see CC_Summary()
 */
 
class CC_Sort_Button extends CC_Text_Button
{
   /**
	* Indicates the direction of the sort.
	*
	* @var bool $up
	* @access private
	*/

	var $up = true;


   /**
	* Indicates if we are currently sorting on this button or not.
	*
	* @var bool $curentlySorting
	* @access private
	*/

	var $currentlySorting = false;




	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Button
	//-------------------------------------------------------------------

	/**
	 * The constructor accepts a value for the button's label and the handler used to sort the columns.
	 *
	 * @access public
	 * @param string $label The button's label text.
	 * @param CC_Action_Handler $sortHandler The handler to sort with.
	 */

	function CC_Sort_Button($label, &$sortHandler)
	{
		$this->CC_Text_Button($label, true);
		
		$this->registerHandler($sortHandler);
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setAscending
	//-------------------------------------------------------------------

	/**
 	 * This method sets the button to indicate an ascending sort direction.
	 * 
	 * @access public
	 * @see setDescending()
	 */

	function setAscending()
	{
		$this->up = true;
	}


	//-------------------------------------------------------------------
	// METHOD: setDescending
	//-------------------------------------------------------------------

	/**
 	 * This method sets the button to indicate an descending sort direction.
	 * 
	 * @access public
	 * @see setAscending()
	 */

	function setDescending()
	{
		$this->up = false;
	}


	//-------------------------------------------------------------------
	// METHOD: setCurrentlySorting
	//-------------------------------------------------------------------

	/**
 	 * This method sets the button to indicate whether we are currently sorting on this button's column or not.
	 * 
	 * @access public
	 * @param bool $currentlySorting Whether or not we are sorting on this button.
	 */


	function setCurrentlySorting($currentlySorting)
	{
		$this->currentlySorting = $currentlySorting;
	}


	//-------------------------------------------------------------------
	// METHOD: getHTML()
	//-------------------------------------------------------------------

	/**
	 * This method generates HTML for displaying the text link button in the CC_Summary column. An up or down arrow may be included to indicate the sort direction if the column is currently being sorted on.
	 *
	 * @access public
	 * @return string The HTML representing this button.
	 */

	function getHTML()
	{
		$application = &getApplication();
		
		$handler = $this->handlers[0];
		$summaryName = $handler->getSummaryName();
		$summaryColumn = $handler->getSummaryColumn();

		$direction = 'up';
		
		if ($this->currentlySorting)
		{
			if ($this->up)
			{
				$direction = 'up';
			}
			else
			{
				$direction = 'down';
			}
		}

		$display = ($this->currentlySorting ? 'inline' : 'none');
		
		return '<nobr><a alt="' . $this->label .'" href="' . $application->getFormAction($this->getPath(), '_LL=' . $this->id . '&pageId=' . URLValueEncode($this->action) . '&pageIdKey=' . URLValueEncode($application->getActionKey())) . '" class="' . $this->style . '"' . (isset($this->_onClick) ? ' onClick="' . $this->_onClick . '"' : '' ) . '>' . $this->label . '<img id="' . $summaryName . '_' . $summaryColumn . '_sort" style="display:' . $display . '" src="/N2O/CC_Images/' . $direction . '_arrow.png" width="10" height="10" border="0" style="behavior: url(\'/N2O/png_fix.htc\');"></a></nobr>';

	}
}

?>