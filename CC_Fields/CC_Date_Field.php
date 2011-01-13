<?php
// $Id: CC_Date_Field.php,v 1.20 2004/12/08 10:21:55 jamie Exp $
//=======================================================================
// CLASS: CC_Date_Field
//=======================================================================

/**
 * This class represents a date to the day.
 *
 * @package CC_Fields
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

class CC_Date_Field extends CC_Field
{
	/**
     * An array representing the month names.
     *
     * @var array $monthsArray
     * @access private
     */	
     
     var $monthsArray = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
	
	
	/**
     * The CC_SelectList_Field that represents the month.
     *
     * @var CC_SelectList_Field $monthField
     * @access private
     */	

	var $monthField;


	/**
     * The CC_SelectList_Field that represents the date.
     *
     * @var CC_SelectList_Field $dateField
     * @access private
     */	

	var $dateField;


	/**
     * The CC_SelectList_Field that represents the year.
     *
     * @var CC_SelectList_Field $yearField
     * @access private
     */	

	var $yearField;


	/**
     * Whether or not the field allows a blank date.
     *
     * @var bool $_allowBlank
     * @access private
     */	

	var $_allowBlank = false;
	

	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Date_Field
	//-------------------------------------------------------------------

	/** 
	 * The field is constructed and is identical to a CC_Date_Field except it has select lists for hour and minute as well. The select lists are constructed here.
	 *
	 * @access public
	 * @param string $name The unique name of the field. Names must be unique so that N2O knows which fields to update when users submit data.
	 * @param string $label A text label to accompany the field to describe which input is either expected or displayed.
	 * @param int $defaultMonthValue The initial month to use. Expected values are from 1-12.
	 * @param int $defaultDateValue The initial date to use. Expected values are from 1-31.
	 * @param int $defaultYearValue The initial year to use. A valid year is expected here.
	 * @param int $defaultHourValue The initial hour to use. Expected values are from 0-23.
	 * @param int $defaultMinuteValue The initial minute to use. Expected values are from 0-59.
	 * @param int $startYear The start year in the year select list's range.	
	 * @param int $endYear The end year in the year select list's range.	
	 */

	function CC_Date_Field($name, $label, $required = false, $defaultMonthValue = -1, $defaultDateValue = -1, $defaultYearValue = -1, $startYear = 1993, $endYear = 2013)
	{
		$this->CC_Field($name, $label, $required);
		
		$size = sizeof($this->monthsArray);
		
		for ($j = 0; $j < $size; $j++)
		{
			$monthItem[0] = $j + 1;
			$monthItem[1] = $this->monthsArray[$j];
			$monthArray[] = $monthItem;
			
			unset($monthItem);
		}
		
		unset($size);
		
		for ($k = 1; $k <= 31; $k++)
		{
			$dayArray[] = $k;
		}
		
		for ($l = $startYear; $l <= $endYear; $l++)
		{
			$yearArray[] = $l;
		}
		
		$today = getdate();
		
		if ($defaultMonthValue == -1)
		{
			$defaultMonthValue = $today['mon'];				
		}
		
		if ($defaultDateValue == -1)
		{
			$defaultDateValue = $today['mday']; 	
		}
		
		if ($defaultYearValue == -1)
		{
			$defaultYearValue = $today['year'];
		}
		
		$this->monthField = &new CC_SelectList_Field($this->name . '_month', '', false, $defaultMonthValue, '', $monthArray);
		$this->dateField = &new CC_SelectList_Field($this->name . '_date', '', false, $defaultDateValue, '', $dayArray);
		$this->yearField = &new CC_SelectList_Field($this->name . '_year', '', false, $defaultYearValue, '', $yearArray);
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setRecord
	//-------------------------------------------------------------------

	/** 
	 * This overrides CC_Field so that each member CC_SelectList field has the parent's record associated with it as well.
	 *
	 * @access public
	 * @param CC_Record $record The record to set.
	 */

	function setRecord(&$record)
	{	
		parent::setRecord($record);
		$this->monthField->setRecord($record);
		$this->dateField->setRecord($record);
		$this->yearField->setRecord($record);
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: reset
	//-------------------------------------------------------------------

	/** 
	 * This resets the date. If setAllowBlankValue(true) was called, it will reset it to blank; otherwise, it will reset it to today's date.
	 *
	 * @access public
	 */

	function reset()
	{
		if ($this->_allowBlank)
		{
			$this->monthField->setValue('');
			$this->dateField->setValue('');
			$this->yearField->setValue('');
		}
		else
		{
			$today = getdate();
			$this->monthField->setValue($today['mon']);
			$this->dateField->setValue($today['mday']);
			$this->yearField->setValue($today['year']);
		}
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setDisabled
	//-------------------------------------------------------------------

	/** 
	 * This overrides CC_Field so that each member CC_SelectList field has the parent's record associated with it as well is disabled.
	 *
	 * @access public
	 * @param boolean $disabled Whether to disable the field or not.
	 */


	function setDisabled($disabled)
	{	
		parent::setDisabled($disabled);
		$this->monthField->setDisabled($disabled);
		$this->dateField->setDisabled($disabled);
		$this->yearField->setDisabled($disabled);
	}
	
	//-------------------------------------------------------------------
	// METHOD: getMonthHTML
	//-------------------------------------------------------------------

	/** 
	 * This method returns HTML for the month CC_SelectList_Field. 
	 *
	 * @access private
	 * @return string The HTML for the month CC_SelectList_Field.
	 */

	function getMonthHTML()
	{
		return $this->monthField->getHTML();
	}
	

	//-------------------------------------------------------------------
	// METHOD: getDateHTML
	//-------------------------------------------------------------------

	/** 
	 * This method returns HTML for the date CC_SelectList_Field. 
	 *
	 * @access private
	 * @return string The HTML for the date CC_SelectList_Field.
	 */

	function getDateHTML()
	{
		return $this->dateField->getHTML();
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getYearHTML
	//-------------------------------------------------------------------

	/** 
	 * This method returns HTML for the year CC_SelectList_Field. 
	 *
	 * @access private
	 * @return string The HTML for the year CC_SelectList_Field.
	 */

	function getYearHTML()
	{
		return $this->yearField->getHTML();
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getEditHTML
	//-------------------------------------------------------------------

	/** 
	 * This method returns HTML for the month, day and year CC_SelectList_Fields where the select list values can be edited. 
	 *
	 * @access public
	 * @return string The HTML for the field.
	 */

	function getEditHTML()
	{
		return $this->getMonthHTML() . " " . $this->getDateHTML() . ", " . $this->getYearHTML();
	}


	//-------------------------------------------------------------------
	// METHOD: setReadOnly
	//-------------------------------------------------------------------
	
	/** 
	 * This method ensures that month, day and year CC_SelectList_Fields have the same read-only value as the master CC_Date_Field.
	 *
	 * @access public
	 * @param bool $readOnly The value to set (which isn't used anyway).
	 */

	function setReadOnly($fieldReadOnly)
	{
		parent::setReadOnly($fieldReadOnly);

		$this->monthField->setReadOnly($fieldReadOnly);
		$this->dateField->setReadOnly($fieldReadOnly);
		$this->yearField->setReadOnly($fieldReadOnly);
	}
	

	//-------------------------------------------------------------------
	// METHOD: getViewHTML
	//-------------------------------------------------------------------

	/** 
	 * This method returns HTML for the month, day and year CC_SelectList_Fields where the select list values cannot be edited. 
	 *
	 * @access public
	 * @return string The HTML for the field.
	 */

	function getViewHTML()
	{
		if ($this->_allowBlank && $this->getValue() == '0000-00-00')
		{
			return '-';
		}
		else
		{
			return date('F d, Y', convertMysqlDateToTimestamp($this->getValue()));
		}
	}
	
		
	//-------------------------------------------------------------------
	// METHOD: getMonthValue
	//-------------------------------------------------------------------

	/** 
	 * This method returns the field's month value.
	 *
	 * @access public
	 * @return int A number from 1-12, depending on the month.
	 */

	function getMonthValue()
	{
		return intval($this->monthField->getValue());
	}
	

	//-------------------------------------------------------------------
	// METHOD: getDateValue
	//-------------------------------------------------------------------

	/** 
	 * This method returns the field's date value.
	 *
	 * @access public
	 * @return int A number from 1-31, depending on the date.
	 */

	function getDateValue()
	{
		return intval($this->dateField->getValue());
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getYearValue
	//-------------------------------------------------------------------

	/** 
	 * This method returns the field's year value.
	 *
	 * @access public
	 * @return int A number representing a valid year.
	 */

	function getYearValue()
	{
		return intval($this->yearField->getValue());
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setMonthValue
	//-------------------------------------------------------------------

	/** 
	 * This method sets the field's month value.
	 *
	 * @access public
	 * @param int A number from 1-12 for the month.
	 */

	function setMonthValue($monthValue = '')
	{
		$this->monthField->setValue($monthValue);
	}
	

	//-------------------------------------------------------------------
	// METHOD: setDateValue
	//-------------------------------------------------------------------

	/** 
	 * This method sets the field's date value.
	 *
	 * @access public
	 * @param int A number from 1-31 for the date.
	 */

	function setDateValue($dateValue = '')
	{
		$this->dateField->setValue($dateValue);
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setValue
	//-------------------------------------------------------------------

	/** 
	 * This method sets the field's value.
	 *
	 * @access public
	 * @param String a date in the format YYYY-MM-DD.
	 */

	function setValue($value)
	{
		if (preg_match('/[0-9]{4}-[0-9]{2}-[0-9]{2}/', $value))
		{
			$parsedDate = getDate(convertMysqlDateToTimestamp($value));
			
			$this->setMonthValue($parsedDate['mon']);
			$this->setDateValue($parsedDate['mday']);
			$this->setYearValue($parsedDate['year']);
		}
		else
		{
			if ($value)
			{
				trigger_error('You must specify date in YYYY-MM-DD format (' . $value . ').', E_USER_WARNING);
			}
		}
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setYearValue
	//-------------------------------------------------------------------

	/** 
	 * This method sets the field's year value.
	 *
	 * @access public
	 * @param int A number representing the year.
	 */

	function setYearValue($yearValue = '')
	{
		$this->yearField->setValue($yearValue);
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setAllowBlankValue
	//-------------------------------------------------------------------

	/** 
	 * This method sets whether or not this field will allow a blank value.
	 *
	 * @access public
	 * @param bool Allow blank value
	 */

	function setAllowBlankValue($allow)
	{
		$this->_allowBlank = $allow;

		if ($allow)
		{
			$this->yearField->setUnselectedValue('- Year -');
			$this->monthField->setUnselectedValue('- Month -');
			$this->dateField->setUnselectedValue('- Date -');
		}
		else
		{
			$this->yearField->setUnselectedValue('');
			$this->monthField->setUnselectedValue('');
			$this->dateField->setUnselectedValue('');
		}
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getValue
	//-------------------------------------------------------------------

	/** 
	 * The method returns the field's value as a string of the form YYYY-MM-DD. 
	 *
	 * @access public
	 * @return string $defaultValue A string of the form YYYY-MM-DD representing a date.
	 */

	function getValue()
	{
		return sprintf('%04u-%02u-%02u', $this->getYearValue(), $this->getMonthValue(), $this->getDateValue());
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getMMValue
	//-------------------------------------------------------------------

	/** 
	 * This method returns an expiry date value in the format 'MM' for use with credit card processing gateways.)
	 *
	 * @access public
	 * @return string The value of the field in MM format.
	 */

	function getMMValue()
	{
		return sprintf('%02u', $this->getMonthValue());
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getYYValue
	//-------------------------------------------------------------------

	/** 
	 * This method returns an expiry date value in the format 'YY' for use with credit card processing gateways.)
	 *
	 * @access public
	 * @return string The value of the field in YY format.
	 */

	function getYYValue()
	{
		return substr($this->getYearValue(), 2, 2);
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getMMYYValue
	//-------------------------------------------------------------------

	/** 
	 * This method returns an expiry date value in the format 'MMYY' for use with credit card processing gateways.)
	 *
	 * @access public
	 * @return string The value of the field in MMYY format.
	 */

	function getMMYYValue()
	{
		return sprintf('%02u', $this->getMonthValue()) . substr($this->getYearValue(), 2, 2);
	}
	

	//-------------------------------------------------------------------
	// METHOD: getYYMMValue
	//-------------------------------------------------------------------

	/** 
	 * This method returns an expiry date value in the format 'YYMM' for use with credit card processing gateways like Moneris.)
	 *
	 * @access public
	 * @return string The value of the field in YYMM format.
	 */

	function getYYMMValue()
	{
		return substr($this->getYearValue(), 2, 2) . sprintf('%02u', $this->getMonthValue());
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: validate
	//-------------------------------------------------------------------
	
	/** 
	 * The method verifies whether or not a valid date was entered. 
	 *
	 * @access public
	 * @return bool Whether or not the date is valid.
	 */

	function validate()
	{
		return ($this->_allowBlank || checkdate($this->getMonthValue(), $this->getDateValue(), $this->getYearValue()));
	}
}

?>