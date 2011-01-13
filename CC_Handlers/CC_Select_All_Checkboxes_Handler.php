<?php
// $Id: CC_Select_All_Checkboxes_Handler.php,v 1.3 2003/07/07 05:13:51 jamie Exp $
//=======================================================================

/**
 * This CC_Action_Handler handles clicking the 'Select All' button for multiple selections in a CC_Summary.
 * 
 * @package CC_Action_Handlers
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

class CC_Select_All_Checkboxes_Handler extends CC_Action_Handler
{
	/**
	 * The CC_Summary we are working (work it, baby).
	 *
	 * @access private
	 * @var CC_Summary $summary
	 */

	var $summary;
	
	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Select_All_Checkboxes_Handler
	//-------------------------------------------------------------------

	/**
	 * This method sets the parameters of the handler for use in the process method.
	 *
	 * @access public
	 * @param CC_Summary $summary The CC_Summary we are selecting from.
	 */

	function CC_Select_All_Checkboxes_Handler(&$summary)
	{	
		$this->summary = &$summary;
		
		$this->CC_Action_Handler();
	}


	//-------------------------------------------------------------------
	// METHOD: process()
	//-------------------------------------------------------------------

	/**
	 * This method sets all rows as selected or unselected, depending on the state of the button.
	 *
	 * @access public
	 */

	function process()
	{
		if($this->summary->multipleSelectionSelectAllButton->getLabel() == 'Select All')
		{
			$this->summary->multipleSelectionSelectAllButton->setLabel('Select None');
			
			for ($i = 0; $i < sizeof($this->summary->rows); $i++)
			{
				$row = $this->summary->rows[$i];
				$currentRecordId = $row['ID'];
				
				$this->summary->multipleSelectionCheckboxes[$currentRecordId]->setValue(true);
			}				
		}
		else
		{
			$this->summary->multipleSelectionSelectAllButton->setLabel('Select All');
			
			for ($i = 0; $i < sizeof($this->summary->rows); $i++)
			{
				$row = $this->summary->rows[$i];
				$currentRecordId = $row['ID'];
				
				$this->summary->multipleSelectionCheckboxes[$currentRecordId]->setValue(false);
			}
		}
	}
}

?>