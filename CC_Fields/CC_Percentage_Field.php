<?php
// $Id: CC_Percentage_Field.php,v 1.7 2003/09/18 23:35:35 jamie Exp $
//=======================================================================

/**
 * This field allows users to click on a progress bar to indicate a value. The CC_Action_Handler sets the value for a CC_Percentage_Field at 100% (ie. as complete).
 * 
 * @package CC_Summary_Filters
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 * @see CC_Percentage_Filter
 * @see CC_Percentage_Field
 */

class CC_Percentage_Field extends CC_Field
{
	/**
     * The submit image.
     *
     * @var CC_Button $percentageButton
     * @access private
     */
     
	var $percentageButton;
	
	/**
     * The complete button, indicating %100 completion upon clicking.
     *
     * @var CC_Button $completeButton
     * @access private
     */
     
	var $completeButton;
	
	
	/**
     * The filter that returns the HTML for the percentage bar.
     *
     * @var CC_Percentage_Filter $percentageFilter
     * @access private
     */
    
    var $percentageFilter;
	
	
	/**
     * The number of divisions to calculate percentage.
     *
     * @var int $divisions
     * @access private
     */
    
    var $divisions;
	
	
	/**
     * The accuracy of the image x y coordinates.
     *
     * @var int $accuracy
     * @access private
     */
     
    var $accuracy;
	
	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Percentage_Field
	//-------------------------------------------------------------------

	/** 
	 * The CC_Percentage_Field constructor sets its values here, yo. 
	 *
	 * @access public
	 * @param string $name The unique name of the field. Names must be unique so that N2O knows which fields to update when users submit data.
	 * @param string $label A text label to accompany the field to describe which input is either expected or displayed.
	 * @param bool $percent 
	 * @param string $path The value the field should contain before user's submit input. The field is blank by default.
	 * @param int $width The visible width of the field in pixels. Defaults to 400 pixels.
	 * @param int $height The visible height of the field in pixels. Defaults to 40 pixels.
	 * @param int $border The size of the field's border, in pixels. Defaults to 0 pixels.
	 * @param int $divisions The number of divisions in the percentage bar. Defaults to 10.
	 * @param int $accuracy The accuracy of a user click. Defaults to 1.
	 */

	function CC_Percentage_Field($name, $label, $percent, $path = '/N2O/CC_Images/percentBar.gif', $width = 400, $height = 40, $border = 0, $divisions = 10, $accuracy = 1)
	{
		$application = &$_SESSION['application'];
		
		$window = &$application->getCurrentWindow();
		
		$this->percentageButton = new CC_Image_Button($label, $path, $width, $height, $border);
		$this->completeButton = new CC_Button('100%');
		$this->percentageFilter = new CC_Percentage_Filter();
		$this->divisions = $divisions;
		$this->accuracy = $accuracy;
		
		$xValue = $width * ($percent / 100);

		$this->CC_Field($name, $label, false, $xValue);
		
		$completeButtonHandler = new CC_100_PercentFieldHandler();
		$completeButtonHandler->setField($this);
		$this->completeButton->registerHandler($completeButtonHandler);
		
		$window->registerComponent($this->percentageButton);
		$window->registerComponent($this->completeButton);
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getEditHTML
	//-------------------------------------------------------------------

	/** 
	 * Returns clickable graphic HTML for the field. 
	 *
	 * @access public
	 * @return string The HTML for the field.
	 */

	function getEditHTML()
	{
		$output = "<table border=\"0\"><tr><td>" . $this->percentageButton->getHTML() . "</td><td valign=\"top\"><img src=\"/N2O/CC_Images/spacer.gif\" width=\"5\" height=\"11\" border=\"0\"><br>" . $this->completeButton->getHTML();
		$output .= "</td></tr><tr><td colspan=\"2\">";
		$output .= $this->percentageFilter->processValue($this->getValue(), -1, $this->percentageButton->width) . "</td></tr></table>";
		
		return $output;
	}


	//-------------------------------------------------------------------
	// METHOD: getViewHTML
	//-------------------------------------------------------------------

	/** 
	 * Returns unclickable graphical HTML for the field. 
	 *
	 * @access public
	 * @return string The HTML for the field.
	 */

	function getViewHTML()
	{
		return $this->percentageFilter->processValue($this->getValue(), -1); //, $this->percentageButton->width
	}
	

	//-------------------------------------------------------------------
	// METHOD: setValue
	//-------------------------------------------------------------------

	/** 
	 * This sets the value for the field.
	 *
	 * @access public
	 * @param int $value The value to set.
	 */

	function setValue($value)
	{
		$percent = ($value / $this->percentageButton->width) * 100;
		
		$increment = ($this->percentageButton->width / $this->divisions);
		
		if ($this->accuracy == 1)
		{
			$percent = round($percent,0);
		}
		else
		{
			for ($i = 0; $i <= 100; $i+=$this->accuracy)
			{
				if ($value > $i && $values <= $i + $this->accuracy)
				{
					$percent = ($i + $this->accuracy);
				}
			}
		}
	
		parent::setValue($percent);
		
		$this->percentageButton->label = $percent;
	}
}

?>