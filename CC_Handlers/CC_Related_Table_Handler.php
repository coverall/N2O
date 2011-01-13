<?php
// $Id: CC_Related_Table_Handler.php,v 1.5 2003/07/07 05:13:51 jamie Exp $
//=======================================================================

/**
 * This CC_Action_Handler handles related tables. 
 * 
 * @package CC_Action_Handlers
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 * @todo Didn't this become a ForeignKey fields? Can't we trash this stash?
 */

class CC_Related_Table_Handler extends CC_Action_Handler
{
	var $recordId;		// the ID value of the parent table
	var $relatedTable;	// the name of the CC_Related_Table_Field 
	var $parentTable;	// the name of the parent table (append '_ID')
						// to search for it in the related table
						 
	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Related_Table_Handler
	//-------------------------------------------------------------------

	function CC_Related_Table_Handler($recordId, $relatedTable, $parentTable)
	{
		$this->CC_Action_Handler();
	}


	//-------------------------------------------------------------------
	// METHOD: process()
	//-------------------------------------------------------------------

	function process()
	{
		$application = &$_SESSION['application'];
		
		$query = 'select * from ' . $relatedTable . ' where ' . $parentTable . '_ID=\'' . $recordId. '\'';
				
		$application->setAction('CC_Framework/CC_Default_Summary_Window');
		$application->setArgument('query', $query);
	}
}

?>