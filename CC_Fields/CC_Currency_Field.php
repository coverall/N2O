<?php
// $Id: CC_Currency_Field.php,v 1.3 2010/11/11 04:28:32 patrick Exp $
//=======================================================================
// CLASS: CC_Currency_Field
//=======================================================================

/**
 * The CC_Currency_Field field represents a dollar value.
 *
 * @package CC_Fields
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */
	
class CC_Currency_Field extends CC_FloatNumber_Field
{	

	/**
     * The field's currency name.
     *
     * @var string $currency
     * @access public
     */

	var $currency;


	/**
     * The field's currency symbol.
     *
     * @var string $currencySymbol
     * @access public
     */

	var $currencySymbol;
	

	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Currency_Field
	//-------------------------------------------------------------------

	/** 
	 * The CC_FloatNumber_Field constructor sets its values here, yo. 
	 *
	 * @access public
	 * @param string $name The unique name of the field. Names must be unique so that N2O knows which fields to update when users submit data.
	 * @param string $label A text label to accompany the field to describe which input is either expected or displayed.
	 * @param bool $required Whether or not this field must contain data from the user before proceeding. Not required by default.
	 * @param float $defaultValue The value the field should contain before user's submit input. The field is blank by default.
	 * @param int $size The visible size of the field, in characters.
	 * @param int $maxlength The maximum amount of data we can enter in the field, in characters.
	 */

	function CC_Currency_Field($name, $label, $required = false, $defaultValue = 0.00, $size = 6, $maxlength = 8, $currency = 'dollar')
	{
		$this->currency = $currency;
		
		switch (strtolower($this->currency))
		{
			case 'dollar':
			{
				$this->currencySymbol = '$';
			}
			break;
			
			case 'euro':
			{
				$this->currencySymbol ='&euro;';
			}
			break;
			
			default:
			{
				error_log('CC_Currency_Field: The currency "' . $this->currency . '" was not found! Assuming dollar;');
				$this->currencySymbol = '$';
			}
		}
		
		$this->CC_FloatNumber_Field($name, $label, $required, $defaultValue, $size, $maxlength);
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getViewHTML
	//-------------------------------------------------------------------

	/** 
	 * Returns a dollar string to two decimal places complete with dollar sign. 
	 *
	 * @access public
	 * @return float A float to two decimal points.
	 */

	function getViewHTML()
	{
		return sprintf($this->currencySymbol . "%.2f", $this->value);
	}
	

	//-------------------------------------------------------------------
	// METHOD: getEditHTML
	//-------------------------------------------------------------------

	/** 
	 * Returns HTML for an 'text' form field. Numbers displayed are to two decimal placesand are prefixed by a dollar sign.
	 *
	 * @access public
	 * @return string The HTML for the field.
	 */

	function getEditHTML()
	{
		return sprintf($this->currencySymbol . "<input type=\"text\" size=\"$this->size\" maxlength=\"$this->maxlength\" name=\"" . $this->getRecordKey() . "$this->name\" value=\"%.2f\" id=\"%s\">", $this->value, $this->id);
	}


	//-------------------------------------------------------------------
	// METHOD: setValue
	//-------------------------------------------------------------------

	/**
     * Sets the field's value.
     *
     * @access public
     * @param mixed $fieldValue The value to set the field to.
     * @see getValue()
     */	

	function setValue($fieldValue = 0.00)
	{
		// strip out anything that isn't a digit or a decimal place
		$fieldValue = ereg_replace('[^0-9\.-]', '', $fieldValue);
		
		parent::setValue(round($fieldValue, 2));
	}
	
	
	//-------------------------------------------------------------------
	// STATIC METHOD: getInstance
	//-------------------------------------------------------------------

	/**
	 * This is a static method called by CC_Record when it needs an instance
	 * of a field. The implementing field needs to return a constructed
	 * instance of itself.
	 *
	 * @access public
	 */

	function &getInstance($className, $name, $label, $value, $args, $required)
	{
		$currency = (isset($args->currency) ? $args->currency : 'dollar');
		
		$size = (isset($args->size) ? $args->size : 32);
		$maxlength = (isset($args->maxlength) ? $args->maxlength : 128);
		
		$field = new $className($name, $label, $required, $value, $size, $maxlength, $currency);

		unset($size, $maxlength, $currencySymbol);

		return $field;
	}
}

?>