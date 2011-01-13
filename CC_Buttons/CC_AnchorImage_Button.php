<?php
// $Id: CC_AnchorImage_Button.php,v 1.8 2004/12/08 23:34:24 patrick Exp $
//=======================================================================
// CLASS: CC_AnchorImage_Button
//=======================================================================

/** 
 * This CC_Button subclass represents linked image buttons (as opposed to the form-submitting CC_Image_Button which uses the image button to submit the form). 
 *
 * @package CC_Buttons
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 * @see CC_Image_Button()
 */

class CC_AnchorImage_Button extends CC_Text_Button
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


	/**
     * If set true, the img tag will include a style="" parameter which enable 24-bit PNGs with alpha channels to work properly in IE for Windows.
     *
     * @var boolean $pngFix
     * @access private
     */

	var $pngFix;

	
	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_AnchorImage_Button
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

	function CC_AnchorImage_Button($label, $path, $width, $height, $border = 0)
	{		
		$this->path = $path;
		$this->width = $width;
		$this->height = $height;
		$this->border = $border;

		$this->CC_Text_Button($label);
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
	// METHOD: setPngFix()
	//-------------------------------------------------------------------

	/**
 	 * If set true, the img tag will include a style="" parameter which enable 24-bit PNGs with alpha channels to work properly in IE for Windows. Ah, the things we do for IE!
	 * 
	 * @access public
	 * @param string $path The path to the image.
	 */

	function setPngFix($fix)
	{
		$this->pngFix = $fix;
	}
	

	//-------------------------------------------------------------------
	// METHOD: getHTML()
	//-------------------------------------------------------------------

	/**
	 * This method overrides CC_Text_Button and generates HTML for displaying the image button using the IMG and HREF tags.
	 *
	 * @access public
	 */

	function getHTML()
	{
		$application = &getApplication();

		$img = '<img src="' . $this->path . '" width="' . $this->width . '" height="' . $this->height . '" border="' . $this->border . '" title="' . $this->label . '" alt="' . $this->getLabel() . '"' . ($this->pngFix ? ' style="behavior: url(\'/N2O/png_fix.htc\');"' : '') . '>';

		if ($this->clickable)
		{
			return '<a href="' . $application->getFormAction($this->getPath(), '_LL=' . $this->id . '&pageId=' . URLValueEncode($this->action) . '&pageIdKey=' . URLValueEncode($application->getActionKey()) . (SID ? '&' . SID : '')) . '" class="' . $this->getStyle() . '">' .  $img . '</a>';
		}
		else
		{
			return $img;
		}
	}
}

?>