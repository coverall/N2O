<?php
// $Id: CC_Email_Field.php,v 1.13 2004/11/26 17:52:45 patrick Exp $
//=======================================================================
// CLASS: CC_Email_Field
//=======================================================================

/**
 * The CC_Email_Field field represents an e-mail address.
 *
 * @package CC_Fields
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

class CC_Email_Field extends CC_Text_Field
{	
	/**
     * Whether or not we can click on the field to trigger an e-mail action. Default is false.
     *
     * @var bool $linkable
     * @access private
     */	
     
     var $linkable = false;
	

	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Email_Field
	//-------------------------------------------------------------------

	/** 
	 * The CC_Email_Field constructor sets its values here, yo. 
	 *
	 * @access public
	 * @param string $name The unique name of the field. Names must be unique so that N2O knows which fields to update when users submit data.
	 * @param string $label A text label to accompany the field to describe which input is either expected or displayed.
	 * @param bool $required Whether or not this field must contain data from the user before proceeding. Not required by default.
	 * @param string $defaultValue The value the field should contain before user's submit input. The field is blank by default.
	 * @param int $size The visible size of the field, in characters.
	 * @param int $maxlength The maximum amount of characters we can enter for the e-mail address.
	 */

	function CC_Email_Field($name, $label, $required = false, $defaultValue = '', $size = 32, $maxlength = 64)
	{
		$this->CC_Text_Field($name, $label, $required, $defaultValue, $size, $maxlength);
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getViewHTML
	//-------------------------------------------------------------------
	
	/** 
	 * Returns HTML for the e-mail address linked only if it is set to be linkable. 
	 *
	 * @access public
	 * @return string The HTML for the field.
	 */
	
	function getViewHTML()
	{
		if ($this->isLinkable())
		{
			return '<a href="mailto:' . $this->getValue() . '">' . $this->getValue() . '</a>';		
		}
		else
		{
			return parent::getViewHTML();
		}
	}


	//-------------------------------------------------------------------
	// METHOD: validate
	//-------------------------------------------------------------------
	
	/** 
	 * This method checks if the e-mail address is in a valid format. 
	 *
	 * We should really read http://www.faqs.org/rfcs/rfc3696.html to allow
	 * all valid characters in the first part of the email address.
	 *
	 * @access public
	 * @return bool Whether or not the field has a valid e-mail address.
	 */
	 
	function validate()
	{
		if ((!ereg(".+\@.+\..+", $this->getValue())) || (!ereg("^[a-zA-Z0-9_@.+-]+$", $this->getValue())))
		{
			$this->setErrorMessage('You have entered an invalid email address');
			
			return false;
		}
		else
		{
			$this->clearErrorMessage();
		}
		
		$lastDotPosition = strrpos($this->value, '.');
		$atPosition = strpos($this->value, '@');
		
		if (($lastDotPosition) && ($atPosition))
		{
			if ($atPosition < ($lastDotPosition - 1))
			{
				if ($lastDotPosition < (strlen($this->value) - 2))
				{
					$this->clearErrorMessage();
					return true;
				}
			}
		}

		$this->setErrorMessage('You have entered an invalid email address');
		
		return false;
	}


	//-------------------------------------------------------------------
	// METHOD: setLinkable
	//-------------------------------------------------------------------
	
	/** 
	  * If passed true, getViewHTML() will make the email address a mailto: link.
	  *
	  * @access public
	  * @param bool $linkable Whether or not make the e-mail address linkable.
	  */
	
	function setLinkable($linkable)
	{
		$this->linkable = $linkable;
	}


	//-------------------------------------------------------------------
	// METHOD: isLinkable
	//-------------------------------------------------------------------
	
	/** 
	  * Returns whether or not the field is linkable.
	  *
	  * @access public
	  * @return bool Whether or not make the e-mail address linkable.
	  */
	  	
	function isLinkable()
	{
		return $this->linkable;
	}


}

?>