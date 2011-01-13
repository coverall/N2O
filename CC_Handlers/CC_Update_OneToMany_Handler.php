<?php
// $Id: CC_Update_OneToMany_Handler.php,v 1.7 2003/07/07 05:13:51 jamie Exp $
//=======================================================================

/**
 * This CC_Action_Handler updates changes to a CC_OneToMany_Window or CC_OneToManyShared_Window.
 * 
 * @package CC_Action_Handlers
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 * @see CC_OneToMany_Window.php
 * @see CC_OneToManyShared_Window.php
 */

class CC_Update_OneToMany_Handler extends CC_Action_Handler
{
	/**
	 * The CC_OneToMany_Field we are updating.
	 *
	 * @access private
	 * @var CC_OneToMany_Field $oneToManyField
	 */

	var $oneToManyField;

	/**
	 * The CC_OneToMany_Field we are updating.
	 *
	 * @access private
	 * @var CC_OneToMany_Field $oneToManyField
	 */

	var $contentProvider;

	
	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Update_OneToMany_Handler
	//-------------------------------------------------------------------

	/**
	 * This method sets the parameters of the handler for use in the process method.
	 *
	 * @access public
	 * @param CC_OneToMany_Field $oneToManyField The CC_OneToMany_Field we are managing.
	 * @param CC_OneToMany_Checkbox_Provider $contentProvider The content provider that converts the set info to checkboxes.
	 */

	function CC_Update_OneToMany_Handler(&$oneToManyField, &$contentProvider)
	{	
		$this->CC_Action_Handler();
		
		$this->oneToManyField = &$oneToManyField;
		$this->contentProvider = &$contentProvider;
	}


	//-------------------------------------------------------------------
	// METHOD: process()
	//-------------------------------------------------------------------

	/**
	 * This method updates the CC_OneToManyField. It doesn't process if the user clicks more than once.
	 *
	 * @access public
	 * @param bool $multipleClick Whether or not the user clicked more than once.
	 */

	function process($multipleClick)
	{
		if ($multipleClick === false)
		{
			parent::process($multipleClick);
	
			$application = &$_SESSION['application'];
			
			$db = &$application->db;
			
			// if the oneToManyField has an existing id, delete all the records
			// in the set table
			if (strcmp($this->oneToManyField->getValue(), "-1") != 0)
			{
				$db->doDelete("delete from " . $this->oneToManyField->setTable . " where ID=" . $this->oneToManyField->getValue());
			}
			
			$options = array();
			
			$keys = array_keys($this->contentProvider->checkboxes);
			
			// iterate through the checkboxes to find out which ones were selected
			for ($i = 0; $i < sizeof($this->contentProvider->checkboxes); $i++)
			{
				$checkbox = &$this->contentProvider->checkboxes[$keys[$i]];
				
				if ($checkbox->isChecked())
				{
					$options[] = $checkbox->name;
				}
				
				unset($checkbox);
			}
			
			$this->oneToManyField->updateOptions($options);
		}
	}
}

?>