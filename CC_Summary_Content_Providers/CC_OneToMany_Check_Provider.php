<?php
// $Id: CC_OneToMany_Check_Provider.php,v 1.6 2004/04/15 18:06:31 patrick Exp $
//=======================================================================
// CLASS: CC_OneToMany_Checkbox_Provider
//=======================================================================

/**
 * This content provider is for use with the CC_OneToMany_Field to provide the HTML for the checkboxes set selectors.
 * 
 * @package CC_Summary_Content_Providers
 * @access private
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

class CC_OneToMany_Checkbox_Provider extends CC_Summary_Content_Provider
{

	/**
     * An array of the content provider's previously constructed CC_Checkbox_Field objects.
     *
     * @var array $checkboxes
     * @access private
     */	

	var $checkboxes = array();


	/**
     * An array of the record Ids selected in the set.
     *
     * @var array $oneToManySelectedIds
     * @access private
     */	

	var $oneToManySelectedIds;


	/**
     * A boolean of whether or not this is read-only
     *
     * @var array $readOnly
     * @access private
     */	

	var $readOnly;
	
	
	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_OneToMany_Checkbox_Provider
	//-------------------------------------------------------------------

	/**
	 * The constructor passes an array of record Ids that are already in the CC_OneToMany_Fields's record set.
	 * 
	 * @access public
	 * @author The Crew <N2O@coverallcrew.com>
	 * @copyright Copyright &copy; 2003, Coverall Crew
	 */

	function CC_OneToMany_Checkbox_Provider($oneToManySelectedIds, $readOnly = false)
	{
		$this->CC_Summary_Content_Provider('center');
		
		$this->oneToManySelectedIds = $oneToManySelectedIds;
		$this->readOnly = $readOnly;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getHTML()
	//-------------------------------------------------------------------
	
	/**
	 * This method returns the HTML checkbox which will be selected based on if the record Id passed is in the set of selected Ids.
	 * 
	 * @access public
	 * @param int $recordId The record we are processing the checkbox for.
	 * @return string The HTML for the checkbox representing the item in the current record's CC_OneToMany_Field set.
	 */

	function getHTML($recordId)
	{
		if (array_key_exists($recordId, $this->checkboxes))
		{
			 return $this->checkboxes[$recordId]->getHTML();
		}
		else
		{
			$checkBox = &new CC_Checkbox_Field($recordId, '');
			
			if (in_array($recordId, $this->oneToManySelectedIds))
			{
				$checkBox->setValue(true);
			}
			
			$checkBox->setReadOnly($this->readOnly);
			
			$application = &$_SESSION['application'];
			
			$window = &$application->getCurrentWindow();
			
			$window->registerComponent($checkBox);
			
			$this->checkboxes[$recordId] = &$checkBox;
			
			return $checkBox->getHTML();
		}
	}
}

?>