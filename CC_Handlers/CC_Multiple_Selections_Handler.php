<?php
// $Id: CC_Multiple_Selections_Handler.php,v 1.8 2003/08/26 09:03:12 patrick Exp $
//=======================================================================

/**
 * This class is used to handle actions on multiple records from a CC_Summary object.
 * 
 * @package CC_Action_Handlers
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

class CC_Multiple_Selections_Handler extends CC_Action_Handler
{
	/**
	 * The name of the CC_Summary.
	 *
	 * @access private
	 * @var string $summaryName
	 */

	var $summaryName;


	/**
	 * The name of the database table the records belong to.
	 *
	 * @access private
	 * @var string $tableName
	 */

	var $tableName;


	/**
	 * The array of records to act on.
	 *
	 * @access private
	 * @var array $records
	 */

	var $records;

	
	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Multiple_Selections_Handler
	//-------------------------------------------------------------------

	/**
	 * This method sets the parameters of the handler for use in the process method.
	 *
	 * @access public
	 * @param string $summaryName The name of the CC_Summary.
	 * @param string $tableName The name of the database table.
	 * @todo Remove the parameters for the constructor, as they are not necessary.
	 */

	function CC_Multiple_Selections_Handler($summaryName = '', $tableName = '')
	{	
		$this->tableName = $tableName;
		$this->summaryName = $summaryName;		
		$this->CC_Action_Handler();
	}


	//-------------------------------------------------------------------
	// METHOD: process()
	//-------------------------------------------------------------------

	/**
	 * This method processes the selected CC_Summary records. Subclasses should override this method to do their own processing.
	 *
	 * @access public
	 */

	function process()
	{
		$application = &$_SESSION['application'];
		
		unset($this->records);
		$this->records = array();
		
		// subclasses must call this method and then add functionality to manipulate the 
		// records who's ids are in this class' $records array. They can then call 
		// $application->setAction('') to go to the appropriate screen
		
		$window = &$application->getCurrentWindow();
		$summary = &$window->getSummary($this->summaryName);
		
		if (array_key_exists($summary->_idColumn, $summary->rows[0]))
		{
			$idColumn = $summary->_idColumn;
		}
		else if (array_key_exists(strtolower($summary->_idColumn), $summary->rows[0]))
		{
			$idColumn = strtolower($summary->_idColumn);
		}

		for ($i = 0; $i < sizeof($summary->rows); $i++)
		{
			$recordId = $summary->rows[$i][$idColumn];

			if ($summary->multipleSelectionCheckboxes[$recordId]->isChecked())
			{
				$this->records[] = &$summary->rows[$i];
			}
			
			unset($recordId);
		}
		
		unset($window, $summary);
			
		/*
		
		//The code should look something like this and should do nothing if there are
		//no records selected
		
		if (sizeof($this->records) > 0)
		{
			$application->setArgument('recordsForDeleteMultiple', $this->records);
			$application->setAction(CC_FRAMEWORK_PATH . '/CC_Windows/CC_Delete_Multiple_Confirm_Window?displayNameForDeleteMultiple=' . $this->displayName);
		}
		*/
	}
}

?>