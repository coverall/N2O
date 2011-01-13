<?php
// $Id: CC_AutoSubmit_Select_Field.php,v 1.21 2004/09/14 18:18:23 patrick Exp $
//=======================================================================
// CLASS: CC_AutoSubmit_Select_Field
//=======================================================================

/**
 * The CC_AutoSubmit_Select_Field field represents a single select list (or drop-down) form field that allows users to choose from a list of pre-defined selections. When a user makes a change, the field should automatically submit the form.
 *
 * @package CC_Fields
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */


class CC_AutoSubmit_Select_Field extends CC_SelectList_Field
{
	/**
     * A button the user can use to submit if the auto-submit doesn't work.
     *
     * @var CC_Button $goButton
     * @access private
     */
     
	var $goButton;
	
	
	/**
     * Whether or not to include the field's go button.
     *
     * @var bool $omitGoButton
     * @access private
     */
     
	var $omitGoButton;

	
	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_AutoSubmit_Select_Field
	//-------------------------------------------------------------------

	/** 
	 * The CC_AutoSubmit_Select_Field constructor sets its values here, yo and registers the go button with the window as well. 
	 *
	 * @access public
	 * @param string $name The unique name of the field. Names must be unique so that N2O knows which fields to update when users submit data.
	 * @param string $label A text label to accompany the field to describe which input is either expected or displayed.
	 * @param bool $required Whether or not this field must contain data from the user before proceeding. Not required by default.
	 * @param string $defaultValue The value the field should contain before user's submit input. The field is blank by default.
	 * @param int $unselectedValue The value to use when nothing is selected in the select list. Default is ' - Select - '.
	 * @param array $theOptions An array of the selection options.
	 * @param string $buttonLabel The label for the button ('Go', by default);
	 * @param boolean $omitGoButton If set to true, the 'Go' button will be omitted, and the select list's Javascript will submit the form rather than clicking the button.
	 */

	function CC_AutoSubmit_Select_Field($name, $label, $required = false, $defaultValue = '', $unselectedValue = ' - Select - ', $theOptions = null, $buttonLabel = 'Go', $omitGoButton = false)
	{
		$application = &$_SESSION['application'];
		
		if ($theOptions == null)
		{
			$theOptions = array();
		}
		
		$this->omitGoButton = $omitGoButton;
		
		if (!$this->omitGoButton)
		{
			$this->goButton = &new CC_Button($buttonLabel);
			$this->goButton->setValidateOnClick(false);
			
		/*	if (isset($application))
			{
				$window = &$application->getCurrentWindow();
	
				if (isset($window))
				{
					$window->registerComponent($this->goButton);
				}
				else
				{
					trigger_error('$goButton needs to be registered. Constructed outside a CC_Window context.', E_USER_WARNING);
				}
			}
			else
			{
				trigger_error('$goButton needs to be registered. Constructed outside a CC_Window context.', E_USER_WARNING);
			}*/
		}
		
		$this->CC_SelectList_Field($name, $label, $required, $defaultValue, $unselectedValue, $theOptions);
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: registerHandler()
	//-------------------------------------------------------------------

	/**
	 * This method registers a CC_Action_Handler with the field, which is assigned to the button.
	 *
	 * @access public
	 * @param CC_Action_Handler $aHandler The CC_Action_Handler subclass to register with the field.
	 * @see CC_Action_Handler
	 */

	function registerHandler(&$aHandler)
	{
		if (isset($this->goButton))
		{
			$this->goButton->registerHandler($aHandler);
		}
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getEditHTML
	//-------------------------------------------------------------------

	/** 
	 * Returns HTML for a 'select' form field with an accompanying go button. 
	 *
	 * @access public
	 * @return string The HTML for the field.
	 */

	function getEditHTML()
	{
		if (isset($this->goButton))
		{
			$selectHTML = '<nobr><select name="' . $this->getRecordKey() . $this->name . '" onChange="document.forms[\'CC_Form\'][\'_BCC_' . $this->goButton->getId() . '\'].click()" tabindex="' . $this->_tabIndex .'">' . "\n";
		}
		else
		{
			$selectHTML = '<select name="' . $this->getRecordKey() . $this->name . '" onChange="document.forms[\'CC_Form\'].submit()" tabindex="' . $this->_tabIndex .'">' . "\n";
		}

		$names = array_keys($this->options);
		
		if ($this->unselectedValue != '')
		{
			$selectHTML .= ' <option value="">' . $this->unselectedValue . "</option>\n";
		}
		
		$size = sizeof($names);
		
		for ($i = 0; $i < $size; $i++)
		{
			if (is_array($this->options[$names[$i]]))
			{
				$theArray = &$this->options[$names[$i]];

				$theValue = $theArray[0];
				$theName  = $theArray[1];				
			}
			else
			{
				$theValue = $this->options[$names[$i]];
				$theName  = $this->options[$names[$i]];
			}
			

			$selectHTML .= ' <option value="' . $theValue . '"';
			
			if (strcmp($theValue, $this->getValue()) == 0)
			{	
				$selectHTML .= ' selected';
			}
			
			$selectHTML .= '>' . $theName . "</option>\n";
		}
		
		$selectHTML .= '</select> ';
		
		if (isset($this->goButton))
		{
			$selectHTML .= $this->goButton->getHTML() . "</nobr>";
		}

		unset($size);
		
		return $selectHTML;
	}	


	//-------------------------------------------------------------------
	// METHOD: register
	//-------------------------------------------------------------------

	/**
	 * This is a callback method that gets called by the window when the
	 * component is registered. It's up to the component to decide which
	 * registerXXX() method it should call on the window. Should your
	 * custom component consist of multiple components, you may need to
	 * make multiple calls.
	 *
	 * @access public
	 */

	function register(&$window)
	{
		$window->registerCustomComponent($this);
		$window->registerField($this);

		if (isset($this->goButton))
		{
			$window->registerButton($this->goButton);
		}
	}


	//-------------------------------------------------------------------
	// METHOD: click()
	//-------------------------------------------------------------------

	/**
	 * Call this method to process the field's handlers. This effectively accomplishes the same thing as when a user actually selects a value but is done here programmatically.
	 *
	 * @access public
	 */

	function click()
	{
		if (isset($this->goButton))
		{
			$this->goButton->click();
		}
	}


	//-------------------------------------------------------------------
	// METHOD: getButton()
	//-------------------------------------------------------------------

	/**
	 * Returns the button object, or false if it doesn't exist.
	 *
	 * @access public
	 */

	function getButton()
	{
		if (isset($this->goButton))
		{
			return $this->goButton;
		}
		else
		{
			return false;
		}
	}
}

?>