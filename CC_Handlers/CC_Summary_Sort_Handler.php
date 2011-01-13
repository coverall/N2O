<?php
// $Id: CC_Summary_Sort_Handler.php,v 1.12 2008/06/05 18:25:56 mike Exp $
//=======================================================================
// CLASS: CC_Summary_Sort_Handler
//=======================================================================

/**
 * This CC_Action_Handler handles sort actions on the CC_Summary when a user clicks on the column headers.
 * 
 * @package CC_Action_Handlers
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 * @see CC_Summary
 */

class CC_Summary_Sort_Handler extends CC_Action_Handler
{			
	/**
	 * The column by which we sort.
	 *
	 * @access private
	 * @var string $sortByColumn
	 */

	var $sortByColumn;


	/**
	 * The summary object we are working.
	 *
	 * @access private
	 * @var CC_Summary $summary
	 */

	var $summary;


	/**
	 * The sort button we are working.
	 *
	 * @access private
	 * @var CC_Button $sortButton
	 */

	var $sortButton;

	
	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Summary_Sort_Handler
	//-------------------------------------------------------------------

	/**
	 * This method sets the parameters of the handler for use in the process method.
	 *
	 * @access public
	 * @param CC_Summary $summary The summary we are manipulating.
	 * @param string $sortByColumn The column the sort button represents.
	 */

	function CC_Summary_Sort_Handler(&$summary, $sortByColumn)
	{
		$this->summary = &$summary;
		$this->sortByColumn = $sortByColumn;
	}


	//-------------------------------------------------------------------
	// METHOD: setSortButton()
	//-------------------------------------------------------------------

	/**
	 * This method sets the button we are sorting on. 
	 *
	 * @access public
	 * @param CC_Button $sortButton The button the user clicked.
	 */

	function setSortButton(&$sortButton)
	{
		$this->sortButton = &$sortButton;
	}


	//-------------------------------------------------------------------
	// METHOD: getSummaryName()
	//-------------------------------------------------------------------

	/**
	 * Get the name of the summary attached to this handler!
	 *
	 * @access public
	 */

	function getSummaryName()
	{
		return $this->summary->getName();
	}


	//-------------------------------------------------------------------
	// METHOD: getSummaryColumn()
	//-------------------------------------------------------------------

	/**
	 * Get the name of the summary column attached to this handler!
	 *
	 * @access public
	 */

	function getSummaryColumn()
	{
		return $this->sortByColumn;
	}



	//-------------------------------------------------------------------
	// METHOD: process()
	//-------------------------------------------------------------------

	/**
	 * This method toggles sorting on the selected column and sets a cookie to set the last user selection as a preference for future accesses to this summary.
	 *
	 * @access public
	 */

	function process()
	{
		if ($this->summary->sortByColumn == $this->sortByColumn)
		{
			if ($this->summary->sortByDirection == 'DESC')
			{
				$this->summary->setSortByDirection('ASC');
				$this->sortButton->setAscending();
				
			}
			else
			{
				$this->summary->setSortByDirection('DESC');
				$this->sortButton->setDescending();
			}
			//$this->sortButton->setCurrentlySorting(true);
		}
		else
		{
			//$this->summary->lastSortByColumn = $this->summary->sortByColumn;
			$this->summary->setSortByColumn($this->sortByColumn);
		
			//$this->summary->sortByDirection = 'ASC';
			//$this->sortButton->setCurrentlySorting(false);

			if ($this->summary->sortByDirection == 'ASC')
			{
				$this->sortButton->setAscending();
			}
			else
			{
				$this->sortButton->setDescending();
			}
		}
		
		setcookie(session_name() . '_' . $this->summary->name . '_SORTBY' , $this->summary->sortByColumn, time() + 31536000);
		setcookie(session_name() . '_' . $this->summary->name . '_SORTBYDIR' , $this->summary->sortByDirection, time() + 131536000);
		
		$this->summary->setPageNumber(1);
		$this->summary->update(true);
	}
}

?>