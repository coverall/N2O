<?php
// $Id: CC_Credit_Card_Field.php,v 1.46 2004/12/10 21:13:34 patrick Exp $
//=======================================================================
// CLASS: CC_Credit_Card_Field
//=======================================================================

/**
 * The CC_Credit_Card_Field field allows users to input or view valid credit card numbers (viewing format depends on the level of security; non-secure connections can only view masked/incomplete numbers).
 *
 * @package CC_Fields
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

class CC_Credit_Card_Field extends CC_Text_Field
{	
	/**
     * Indicates if MasterCard is accepted. Default is true.
     *
     * @var bool $allowMasterCard
     * @access private
     */

	var $allowMasterCard = true;


	/**
     * Indicates if Visa is accepted. Default is true.
     *
     * @var bool $allowVisa
     * @access private
     */

	var $allowVisa = true;


	/**
     * Indicates if American Express is accepted. Default is true.
     *
     * @var bool $allowAmEx
     * @access private
     */

	var $allowAmEx = true;


	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Credit_Card_Field
	//-------------------------------------------------------------------

	/** 
	 * The CC_Credit_Card_Field constructor indicates that the field should be encoded in the database. 
	 *
	 * @access public
	 * @param string $name The unique name of the field. Names must be unique so that N2O knows which fields to update when users submit data.
	 * @param string $label A text label to accompany the field to describe which input is either expected or displayed.
	 * @param bool $required Whether or not this field must contain data from the user before proceeding. Not required by default.
	 * @param int $defaultValue The value the field should contain before user's submit input. Initially blank by default.
	 * @param int $size The visible size of the field.
	 */

	function CC_Credit_Card_Field($name, $label, $required = false, $defaultValue = '', $size = 24)
	{
		$this->CC_Text_Field($name, $label, $required, strSlide13($defaultValue), $size, 19);
		$this->setEncode(true);
	}
	

	//-------------------------------------------------------------------
	// METHOD: getValue()
	//-------------------------------------------------------------------
	
	function getValue()
	{
		return strSlide13($this->value);
	}
	

	//-------------------------------------------------------------------
	// METHOD: getViewHTML()
	//-------------------------------------------------------------------
	
	/** 
	 * Returns HTML for an 'text' form field with the credit card value. The full value is only shown if we are on using a secure SSL connection. Othwerise it is masked with *'s.
	 *
	 * @access public
	 * @return string The HTML for the field. 
	 */
	 
	function getViewHTML()
	{
		global $application;
		
		if ($application->isSecure() || !$this->hasValue())
		{
			return $this->space($this->getValue());
		}
		else
		{
			return $this->getMaskedNumber();
		}
	}

	
	//-------------------------------------------------------------------
	// METHOD: getEditHTML()
	//-------------------------------------------------------------------
	
	/** 
	 * Returns HTML for an <input type='text'> form field with the credit card value. The full value is only shown if we are on using a secure SSL connection. 
	 *
	 * @access public
	 * @return string The HTML for the field. 
	 */
	 
	function getEditHTML()
	{
		global $application;
		
		if ($application->isSecure())
		{
			return '<input type="text" size="' . $this->size. '" maxlength="' . $this->maxlength . '" name="' . $this->getRecordKey() . $this->name . '" value="' . htmlspecialchars($this->getValue()) . '" class="' . $this->inputStyle . '"' . ($this->disabled ? ' disabled="true"' : '') . '>';
		}
		else
		{
			return '<input type="text" size="' . $this->size . '" maxlength="' . $this->maxlength . '" name="' . $this->getRecordKey() . $this->name . '" value="' . $this->getMaskedNumber() . '" class="' . $this->inputStyle  . '"' . ($this->disabled ? ' disabled="true"' : '') . '>';
		}
	}

	
	//-------------------------------------------------------------------
	// METHOD: setAllowMasterCard
	//-------------------------------------------------------------------
	
	/** 
	 * This sets whether or not the field accepts MasterCard account numbers.
	 *
	 * @access public
	 * @param bool $allow Whether or not to allow MasterCard numbers.
	 */
	
	function setAllowMasterCard($allow = true)
	{
		$this->allowMasterCard = $allow;
	}

	
	//-------------------------------------------------------------------
	// METHOD: setAllowVisa
	//-------------------------------------------------------------------
	
	/** 
	 * This sets whether or not the field accepts Visa account numbers.
	 *
	 * @access public
	 * @param bool $allow Whether or not to allow Visa numbers.
	 */
	 
	function setAllowVisa($allow = true)
	{
		$this->allowVisa = $allow;
	}

	
	//-------------------------------------------------------------------
	// METHOD: setAllowAmEx
	//-------------------------------------------------------------------
	
	/** 
	 * This sets whether or not the field accepts American Express account numbers.
	 *
	 * @access public
	 * @param bool $allow Whether or not to allow American Express numbers.
	 */
	 
	function setAllowAmEx($allow = true)
	{
		$this->allowAmEx = $allow;
	}

	
	//-------------------------------------------------------------------
	// METHOD: setAllowDiscover
	//-------------------------------------------------------------------
	
	/** 
	 * This sets whether or not the field accepts Discover account numbers.
	 *
	 * @access public
	 * @param bool $allow Whether or not to allow Discover numbers.
	 */
	 
	function setAllowDiscover($allow = true)
	{
		$this->allowDiscover = $allow;
	}

	
	//-------------------------------------------------------------------
	// METHOD: isMasterCard
	//-------------------------------------------------------------------
	
	/** 
	  * This method returns true if the entered number is a MasterCard.
	  *
	  * @access public
	  * @return bool Whether or not the credit card entered is from MasterCard.
	  */
	
	function isMasterCard()
	{
		return ((substr($this->getValue(), 0, 1) == '5')  && (strlen($this->getValue()) == 16));
	}

	
	//-------------------------------------------------------------------
	// METHOD: isVisa
	//-------------------------------------------------------------------
	
	/** 
	  * This method returns true if the entered number is a Visa.
	  *
	  * @access public
	  * @return bool Whether or not the credit card entered is from Visa.
	  */
	
	function isVisa()
	{
		return ((substr($this->getValue(), 0, 1) == '4') && ((strlen($this->getValue()) == 13) || (strlen($this->getValue()) == 16)));
	}


	//-------------------------------------------------------------------
	// METHOD: isAmex
	//-------------------------------------------------------------------
	
	/** 
	  * This method returns true if the entered number is a American Express.
	  *
	  * @access public
	  * @return bool Whether or not the credit card entered is from American Express.
	  */
	
	function isAmex()
	{
		return ((strlen($this->getValue()) == 15) && (substr($this->getValue(), 0, 1) == '3'));
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: isDiscover
	//-------------------------------------------------------------------
	
	/** 
	  * This method returns true if the entered number is a Discover card.
	  *
	  * @access public
	  * @return bool Whether or not the credit card entered is from Discover.
	  */
	
	function isDiscover()
	{
		return ((strlen($this->getValue()) == 16) && (substr($this->getValue(), 0, 4) == '6011'));
	}


	//-------------------------------------------------------------------
	// METHOD: setValue
	//-------------------------------------------------------------------
	
	/** 
	 * This sets the value of the field. It is encoded in the field.
	 *
	 * @access public
	 * @param int $value The value to set.
	 */
	 
	function setValue($value)
	{
		// strip out anything that isn't a digit
		$value = ereg_replace('[^0-9]', '', $value);

		parent::setValue(strSlide13($value));
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getCreditCardType
	//-------------------------------------------------------------------
	
	/** 
	 * This gets the credit card type of the number in the field.
	 *
	 * @access public
	 * @return string The type of credit card, or unknown, if it is unknown.
	 */
	 
	function getCreditCardType()
	{
		if ($this->isAmex())
		{
			return 'American Express';
		}
		else if ($this->isVisa())
		{
			return 'Visa';
		} 
		else if ($this->isMastercard())
		{
			return 'MasterCard';
		}
		else if ($this->isDiscover())
		{
			return 'Discover';
		}
		else
		{
			return 'Unknown';
		}
	}


	//-------------------------------------------------------------------
	// METHOD: validate
	//-------------------------------------------------------------------
	
	/** 
	 * This method checks if the credit card satisfies Luhn's formula. 
	 *
	 * @access public
	 * @return bool Whether or not the field is a valid credit card number according to Luhn's formula.
	 */
	 
	function validate()
	{
		$checkSum = 0;

		$validateValue = $this->getValue();
		
		// check to make sure our number is the right format
		if (!($this->isVisa() || $this->isAmex() || $this->isMasterCard() || $this->isDiscover()))
		{	
			$this->setErrorMessage('You have entered an invalid card number.');
			return false;
		}

		$reverse = strrev($validateValue);
		
		for ($i = 0; $i < strlen($reverse); $i++)
		{
			$currentDigit = (int)(substr($reverse, $i, 1));

			if (($i % 2) == 0)
			{
				$checkSum += $currentDigit;
			}
			else
			{
				$currentDigit = 2 * $currentDigit;
				
				$checkSum += ($currentDigit > 9 ? ($currentDigit - 9) : $currentDigit);
			}
		}
		
		// Check the type of card
		$firstDigit = substr($validateValue, 0, 1);
		
		if (($firstDigit == 3) && !$this->allowAmEx)
		{
			$this->setErrorMessage('American Express not accepted.');
			return false;
		}
		else if (($firstDigit == 4) && !$this->allowVisa)
		{
			$this->setErrorMessage('Visa not accepted.');
			return false;
		}
		else if (($firstDigit == 5) && !$this->allowMasterCard)
		{
			$this->setErrorMessage('MasterCard not accepted.');
			return false;
		}
		else if (($firstDigit == 6) && !$this->allowDiscover)
		{
			$this->setErrorMessage('Discover not accepted.');
			return false;
		}
		
		if (($checkSum % 10) == 0)
		{		
			$this->clearErrorMessage();
			return true;
		}
		else
		{
			$this->setErrorMessage('Invalid credit card number.');
			return false;
		}
	}


	//-------------------------------------------------------------------
	// METHOD: getMaskedNumber
	//-------------------------------------------------------------------
	
	/** 
	 * This method masks a given credit card number by only showing the first and last four digits replacing the others with *'s. 
	 *
	 * @access public
	 * @return string The masked credit card number.
	 */
	
	function getMaskedNumber($aNumber = null)
	{
		if ($aNumber == null)
		{
			$aNumber = $this->getValue();
		}
		
		$maskedNumber = '';
		
		if (strlen($aNumber))
		{
			$maskedNumber .= substr($aNumber, 0, 4);
			$maskedNumber .= "********";
			$maskedNumber .= substr($aNumber, strlen($aNumber) - 4, 4);
		}
		
		return $maskedNumber;
	}


	//-------------------------------------------------------------------
	// METHOD: space
	//-------------------------------------------------------------------
	
	/** 
	 * This method spaces a given credit card number after every fourth number, if it is a 16 digit number. Otherwise it just returns the original number, unaltered.
	 *
	 * @access public
	 * @return string The spaced credit card number.
	 */

	function space($value)
	{
		if (strlen($value) == 16)
		{
			return substr($value, 0, 4) . ' ' . substr($value, 4, 4) . ' ' . substr($value, 8, 4) . ' ' . substr($value, 12, 4);
		}
		else
		{
			return $value;
		}
	}

}

?>