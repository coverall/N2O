<?php
// $Id: CC_Delete_Multiple_Selections_Handler.php,v 1.6 2003/10/05 09:48:46 jamie Exp $
//=======================================================================
// CLASS: CC_Delete_Multiple_Selections_Handler
//=======================================================================

/**
 * This class is used to handle deleting multiple records from a CC_Summary object.
 * 
 * @package CC_Action_Handlers
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */


class CC_Delete_Multiple_Selections_Handler extends CC_Multiple_Selections_Handler
{
	/**
	 * The display name of the CC_Summary records.
	 *
	 * @access private
	 * @var string $displayName
	 */
		
	var $displayName;


	/**
	 * The plural display name of the CC_Summary records.
	 *
	 * @access private
	 * @var string $pluralDisplayName
	 */

	var $pluralDisplayName;
	
	
	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Delete_Multiple_Selections_Handler
	//-------------------------------------------------------------------

	/**
	 * This method sets the parameters of the handler for use in the process method.
	 *
	 * @access public
	 * @param string $summaryName The name of the CC_Summary.
	 * @param string $tableName The name of the database table.
	 * @param string $displayName The display name to use for the records. Defaults to 'Record'.
	 * @param string $pluralDisplayName The plural display name to use for the records. Defaults to 'Records'.
	 */

	function CC_Delete_Multiple_Selections_Handler($summaryName, $tableName, $displayName = 'Record',  $pluralDisplayName = 'Records')
	{		
		$this->displayName = $displayName;
		$this->pluralDisplayName = $pluralDisplayName;
		$this->CC_Multiple_Selections_Handler($summaryName, $tableName);
	}


	//-------------------------------------------------------------------
	// METHOD: process()
	//-------------------------------------------------------------------

	/**
	 * This method calls the CC_Delete_Multiple_Confirm_Window to confirm deletion of the selected records.
	 *
	 * @access public
	 */

	function process()
	{
		parent::process();
		
		$application = &$_SESSION['application'];
		
		if (sizeof($this->records) > 0)
		{
			$displayName = (sizeof($this->records) == 1) ? $this->displayName : $this->pluralDisplayName;
		
			$application->setObject('recordsForDeleteMultiple', $this->records);
			$application->setAction(CC_FRAMEWORK_PATH . '/CC_Windows/CC_Delete_Multiple_Confirm_Window?displayNameForDeleteMultiple=' . $displayName . '&tableNameForDeleteMultiple=' . $this->tableName);
		}
	}
}

?>