<?php
// $Id: CC_Image_Button.php,v 1.16 2004/11/02 21:24:51 patrick Exp $
//=======================================================================
// CLASS: CC_Image_Button
//=======================================================================

/** 
 * This CC_Button subclass represents form submitting image buttons (as opposed to CC_AnchorImage_Button which uses HREF tags. 
 *
 * @package CC_Buttons
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 * @see CC_AnchorImage_Button()
 */

class CC_Image_Button extends CC_Button
{
	/**
     * The path to the image.
     *
     * @var string $path
     * @access private
     */

	var $path;


	/**
     * The width of the image, in pixels.
     *
     * @var int $width
     * @access private
     */

	var $width;


	/**
     * The height of the image, in pixels.
     *
     * @var int $height
     * @access private
     */

	var $height;


	/**
     * The border value in pixels.
     *
     * @var int $border
     * @access private
     */

	var $border;

	
	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Image_Button
	//-------------------------------------------------------------------

	/**
	 * The constructor accepts parameters describing the image to use.
	 *
	 * @access public
	 * @param string $label The button's alternative label text (ie. for the ALT tag).
	 * @param string $path The path to the button's image.
	 * @param int $width The width of the image in pixels.
	 * @param int $height The height of the image in pixels.
	 * @param int $border The border thickness, in pixels, to show around the image.
	 */

	function CC_Image_Button($label, $path, $width, $height, $border = 0)
	{
		$this->path = $path;
		$this->width = $width;
		$this->height = $height;
		$this->border = $border;

		$this->CC_Button($label);
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setImagePath()
	//-------------------------------------------------------------------

	/**
 	 * This method sets the path of the image to use for the button.
	 * 
	 * @access public
	 * @param string $path The path to the image.
	 */

	function setImagePath($path)
	{
		$this->path = $path;
	}
	

	//-------------------------------------------------------------------
	// METHOD: getHTML()
	//-------------------------------------------------------------------

	/**
	 * This method overrides CC_Button and generates HTML for displaying the image button.
	 *
	 * @access public
	 */

	function getHTML()
	{	
		if ($this->clickable)
		{
			return '<input type="image" name="_PP_' . $this->id . '" value="' . $this->label . '" src="' . $this->path . '" width="' . $this->width . '" height="' . $this->height . '" border="' . $this->border . '" title="' . $this->label . '" alt="' . $this->getLabel() . '" class="' . $this->style . '" tabindex="' . $this->_tabIndex . '"' . (isset($this->_onClick) ? ' onClick="' . $this->_onClick . '"' : '' ) . '>';
		}
		else
		{
			return '<img src="' . $this->path . '" width="' . $this->width . '" height="' . $this->height . '" border="' . $this->border . '" alt="' . $this->getLabel() . '" class="' . $this->style . '">';
		}
	}


	//-------------------------------------------------------------------
	// METHOD: click
	//-------------------------------------------------------------------

	/**
	 * This method overrides CC_Button since it passes it's x and y coordinates to the process method. This effectively accomplishes the same thing as when a user actually clicks the button but is done here programmatically.
	 *
	 * @access public
	 * @param bool $multipleClick Whether or not we are dealing with an accidental multiple click.
	 * @return This method returns false when it encounters a handler that itself returns false.
	 * @todo Can we remove the extra parameters passed to the process method? When are they being used?
	 */

	function click($multipleClick = false)
	{
		$size = sizeof($this->handlers);

		for ($j = 0; $j < sizeof($this->handlers); $j++)
		{
			// If any handlers returns false, don't execute any more
			if ($this->handlers[$j]->process($multipleClick, $_REQUEST['_PP_' . $this->id . '_x'], $_REQUEST['_PP_' . $this->id . '_y']) === false)
			{
				unset($size);
				return;
			}
		}

		unset($size);
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
		return isset($_REQUEST['_PP_' . $this->id . '_x']);
	}
}

?>