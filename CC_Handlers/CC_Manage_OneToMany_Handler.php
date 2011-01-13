<?php
// $Id: CC_Manage_OneToMany_Handler.php,v 1.6 2004/04/15 18:07:44 patrick Exp $
//=======================================================================

/**
 * This CC_Action_Handler processes a CC_OneToMany_Field's 'Manage' button and calls the CC_OneToMany_Window or CC_OneToManyShared_Window.
 * 
 * @package CC_Action_Handlers
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 * @see CC_OneToMany_Window.php
 * @see CC_OneToManyShared_Window.php
 */

class CC_Manage_OneToMany_Handler extends CC_Action_Handler
{
	/**
	 * The CC_OneToMany_Field we are managing.
	 *
	 * @access private
	 * @var CC_OneToMany_Field $oneToManyField
	 */

	var $oneToManyField;


	/**
	 * Whether or not the field is read-only
	 *
	 * @access private
	 * @var bool $readOnly
	 */

	var $readOnly;
	
	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Manage_OneToMany_Handler
	//-------------------------------------------------------------------

	/**
	 * This method sets the parameters of the handler for use in the process method.
	 *
	 * @access public
	 * @param CC_OneToMany_Field $oneToManyField The CC_OneToMany_Field we are managing.
	 * @param bool $readOnly Whether or not the field is read-only. If nothing is passed, false is assumed.
	 */

	function CC_Manage_OneToMany_Handler(&$oneToManyField, $readOnly = false)
	{	
		$this->CC_Action_Handler();
		
		$this->oneToManyField = &$oneToManyField;
		$this->readOnly = $readOnly;
	}


	//-------------------------------------------------------------------
	// METHOD: process()
	//-------------------------------------------------------------------

	/**
	 * This method sets the action to CC_OneToMany_Window or CC_OneToManyShared_Window and passes parameters to the window.
	 *
	 * @access public
	 */

	function process()
	{
		$application = &$_SESSION['application'];
		
		parent::process();
		
		/* huh? this property doesn't exist, and neither does the window...
		if ($this->oneToManyField->shareRecords)
		{
			$windowName = CC_FRAMEWORK_PATH . '/CC_Windows/CC_OneToManyShared_Window?id=' . $this->oneToManyField->getValue() . "&oneToManySetTable=". $this->oneToManyField->setTable . "&oneToManySourceTable=" . $this->oneToManyField->sourceTable . '&oneToManyDisplayColumn=' . $this->oneToManyField->displayColumn . '&oneToManyLabel=' . $this->oneToManyField->label;
		}
		else
		{
			$windowName = CC_FRAMEWORK_PATH . '/CC_Windows/CC_OneToMany_Window?id=' . $this->oneToManyField->getValue() . "&oneToManySetTable=". $this->oneToManyField->setTable . "&oneToManySourceTable=" . $this->oneToManyField->sourceTable . '&oneToManyDisplayColumn=' . $this->oneToManyField->displayColumn . '&oneToManyLabel=' . $this->oneToManyField->label;
		}*/

		$windowName = CC_FRAMEWORK_PATH . '/CC_Windows/CC_OneToMany_Window?id=' . $this->oneToManyField->getValue() . "&oneToManySetTable=". $this->oneToManyField->setTable . "&oneToManySourceTable=" . $this->oneToManyField->sourceTable . '&oneToManyDisplayColumn=' . $this->oneToManyField->displayColumn . '&oneToManyLabel=' . $this->oneToManyField->label;
		
		//if ($application->isWindowRegistered($windowName))
		//{
		//	$application->unregisterWindow($windowName);
		//}

		$application->setArgument('oneToManySelectedIds', array_keys($this->oneToManyField->options));
		$application->setObject('oneToManyField', $this->oneToManyField);
		$application->setArgument('oneToManyReadOnly', $this->readOnly);
		$application->setAction($windowName);
	}
}

?>