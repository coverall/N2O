<?php
// $Id: CC_Text_Button.php,v 1.25 2008/05/30 23:41:30 mike Exp $
//=======================================================================
// CLASS: CC_Text_Button
//=======================================================================

/** 
 * This CC_Button subclass represents text link buttons. IMPORTANT NOTE: Text buttons cannot be used to POST data. Beware!
 *
 * @package CC_Buttons
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

class CC_Text_Button extends CC_Button
{
	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Text_Button
	//-------------------------------------------------------------------

	/**
	 * The constructor accepts a value for the button's label which is the text to use as the text link.
	 *
	 * @access public
	 * @param string $label The button's label text.
	 */

	function CC_Text_Button($label)
	{
		$this->CC_Button($label);
		
		if (isset($_SESSION['application']))
		{
			global $application;
			$this->action = $application->getAction();
		}
		else
		{
			global $start_point;
			$this->action = $start_point;
		}

		// since data is not posted with a get request, we don't want
		// to update the fields to be blank.
		$this->setFieldUpdater(false);
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getHTML()
	//-------------------------------------------------------------------

	/**
	 * This method overrides CC_Button and generates HTML for displaying the text link.
	 *
	 * @access public
	 */

	function getHTML()
	{
		$application = &getApplication();
		
		if ($this->clickable)
		{
			return '<a href="' . $application->getFormAction($this->getPath(), '_LL=' . $this->id . '&pageId=' . URLValueEncode($this->action) . '&pageIdKey=' . URLValueEncode($application->getActionKey())) . '" class="' . $this->getStyle() . '" ' . (isset($this->_onClick) ? ' onClick="' . $this->_onClick . '"' : '' ) . '>' . $this->label . '</a>';
		}
		else
		{
			return '<span class="ccDisabled">' . $this->label . '</span>';
		}
	}


	//-------------------------------------------------------------------
	// METHOD: isClickInRequest()
	//-------------------------------------------------------------------

	/**
	 * Returns boolean that indicates whether this button was clicked in the current request ($_GET or $_POST). 
	 *
	 * @access public
	 */

	function isClickInRequest()
	{
		return isset($_REQUEST['_LL']) && $_REQUEST['_LL'] == $this->id;
	}


	//-------------------------------------------------------------------
	// METHOD: isGetRequest()
	//-------------------------------------------------------------------

	/**
	 * Returns boolean that indicates whether this button causes a GET request. 
	 *
	 * @access public
	 */

	function isGetRequest()
	{
		return true;
	}

}

?>