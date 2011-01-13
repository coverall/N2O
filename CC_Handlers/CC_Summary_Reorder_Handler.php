<?php
// $Id: CC_Summary_Reorder_Handler.php,v 1.9 2004/10/05 22:54:36 jamie Exp $
//=======================================================================
// CLASS: CC_Summary_Reorder_Handler
//=======================================================================

/**
 * This CC_Action_Handler handles sorting a sorted CC_Summary.
 * 
 * @package CC_Action_Handlers
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 * @see CC_Ordered_Summary
 */

class CC_Summary_Reorder_Handler extends CC_Action_Handler
{			
	/**
	 * The summary object we are working.
	 *
	 * @access private
	 * @var CC_Summary $summary
	 */

	var $summary;

	
	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Summary_Reorder_Handler
	//-------------------------------------------------------------------

	/**
	 * This method sets the parameters of the handler for use in the process method.
	 *
	 * @access public
	 * @param CC_Summary $summary The summary we are manipulating.
	 */

	function CC_Summary_Reorder_Handler(&$summary)
	{
		$this->summary = &$summary;
	}


	//-------------------------------------------------------------------
	// METHOD: updateSortOrder()
	//-------------------------------------------------------------------

	/**
	 * This method updates the sort order of the summary in the database, based on the CC_Ordered_Summary's sortArray.
	 *
	 * @access private
	 */

	function updateSortOrder()
	{
		$application = &$_SESSION['application'];
		
		$recordIds = array_keys($this->summary->sortArray);
				
		for ($t = 0; $t < sizeof($recordIds); $t++)
		{
			$updateQuery = 'update ' . $this->summary->mainTable . ' set SORT_ID=\'' . $recordIds[$t] . '\' where ID=\'' . $this->summary->sortArray[$recordIds[$t]] . '\'';

			//trigger_error($updateQuery, E_USER_WARNING);	

			$application->db->doUpdate($updateQuery);
			
			$selectList = &$this->summary->window->getField($this->summary->name . '_' . $this->summary->sortArray[$recordIds[$t]]);
			$selectList->setValue($recordIds[$t]);
		}
		
		$this->summary->update(true);
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: bubblesort()
	//-------------------------------------------------------------------

	/**
	 * This method updates CC_Ordered_Summary's sortArray based on a user's sort change.
	 *
	 * @access private
	 */

	function bubblesort($previousIndex, $currentIndex)
	{
		//echo "$previousIndex is previousIndex<br>";
		//echo "$currentIndex is currentIndex<br>";
		
		if ($currentIndex > $previousIndex) //bubble up
		{
			for ($k = $currentIndex; $k > $previousIndex; $k--)
			{
				$kswap = $this->summary->sortArray[$previousIndex];
				$this->summary->sortArray[$previousIndex] = $this->summary->sortArray[$k];
				$this->summary->sortArray[$k] = $kswap;
				
			} 
		}
		else //bubble down
		{
			for ($l = $currentIndex; $l < $previousIndex; $l++)
			{
				$lswap = $this->summary->sortArray[$previousIndex];
				$this->summary->sortArray[$previousIndex] = $this->summary->sortArray[$l];
				$this->summary->sortArray[$l] = $lswap;
			}
		}
	}

	
	//-------------------------------------------------------------------
	// METHOD: process()
	//-------------------------------------------------------------------

	/**
	 * This method reorders a CC_Ordered_Summary first in the sortArray, and then in the database.
	 *
	 * @access public
	 */

	function process()
	{
		$rows = $this->summary->rows;
			
		//sort
		$counter = 0;
		$changedFieldFound = false;
		
		while (!$changedFieldFound && ($counter < sizeof($rows)))
		{
			$currentRow = $rows[$counter];
			
			$id = $currentRow['ID'];
			$previousSortId = array_search($id, $this->summary->sortArray, true);
			
			/*
			outputArrayKeys($this->summary->sortArray);
			trigger_error("id is $id", E_USER_WARNING);			
			trigger_error("previousSortId is $previousSortId", E_USER_WARNING);
			*/
			
			$sortIdOrderSelectList = $this->summary->window->getField($this->summary->name . '_' . $id);
			
			$currentSortId = $sortIdOrderSelectList->getValue();
			
			if ($previousSortId != $currentSortId)
			{
				$this->bubblesort($previousSortId, $currentSortId);
				$changedFieldFound = true;
			}
			
			$counter++;
		}
		
		$this->updateSortOrder();
	}	
}

?>