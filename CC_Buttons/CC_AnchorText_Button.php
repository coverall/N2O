<?php
// $Id: CC_AnchorText_Button.php,v 1.4 2003/10/09 17:44:40 mike Exp $
//=======================================================================
// CLASS: CC_AnchorText_Button
//=======================================================================

/** 
 * This CC_Button subclass represents text links. This class is not a real button, as no handlers will be executed, but it does benefit from some of the nice things of the CC_Button class like being able to disable it, set a style, etc.
 *
 * @package CC_Buttons
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

class CC_AnchorText_Button extends CC_Text_Button
{
	var $_link;
	var $_target;


	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_AnchorText_Button
	//-------------------------------------------------------------------

	/**
	 * The constructor accepts a value for the button's label which is the text to use as the text link.
	 *
	 * @access public
	 * @param string $label The button's label text.
	 */

	function CC_AnchorText_Button($label, $link, $target = null)
	{
		$this->CC_Button($label);
		$this->_link = $link;
		$this->_target = $target;		
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
		if ($this->clickable)
		{
			return '<a href="' . $this->_link . '" class="' . $this->getStyle() . '"' . (isset($this->_target) ? ' target="' . $this->_target . '"' : '') . '>' . $this->label . '</a>';
		}
		else
		{
			return '<span class="ccDisabled">' . $this->label . '</span>';
		}
	}


	//-------------------------------------------------------------------
	// METHOD: registerHandler()
	//-------------------------------------------------------------------

	/**
	 * This method overrides CC_Button to produce a warning message.
	 *
	 * @access public
	 */

	function registerHandler(&$handler)
	{
		trigger_error('CC_AnchorText_Button does not support handlers.', E_USER_WARNING);
		parent::registerHandler($handler);
	}
}

?>