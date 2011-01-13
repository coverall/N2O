<?php
// $Id: CC_DateTime_Field.php,v 1.32 2011/01/13 02:35:36 jamie Exp $
//=======================================================================
// CLASS: CC_DateTime_Field
//=======================================================================

/**
 * The CC_DateTime_Field field represents a date and time to the minute.
 *
 * @package CC_Fields
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

class CC_DateTime_Field extends CC_Date_Field
{
	/**
     * The CC_SelectList_Field that represents the hour.
     *
     * @var CC_SelectList_Field $hourField
     * @access private
     */	

	var $hourField;


	/**
     * The CC_SelectList_Field that represents the minutes.
     *
     * @var CC_SelectList_Field $minuteField
     * @access private
     */	

	var $minuteField;
	

	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_DateTime_Field
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
	 * @param int $minuteIncrements The increments for the minute select list. Default is 15 (so the select list values are 0,15,30,45).
	 */

	function CC_DateTime_Field($name, $label, $required = false, $defaultMonthValue = -1, $defaultDateValue = -1, $defaultYearValue = -1, $defaultHourValue = 12, $defaultMinuteValue = 0, $startYear = 2002, $endYear = 2020, $minuteIncrements = 15)
	{
		$this->CC_Date_Field($name, $label, $required, $defaultMonthValue, $defaultDateValue, $defaultYearValue, $startYear, $endYear);
		
		for ($i = 0; $i <= 23; $i++)
		{
			$hourArray[] = sprintf('%02u', $i);
		}
		
		for ($i = 0; $i < 60; $i += $minuteIncrements)
		{
			$minuteArray[] = sprintf('%02u', $i);
		}
		
		$this->hourField = new CC_SelectList_Field($this->name . '_hour', '', false, $defaultHourValue, '', $hourArray);
		$this->minuteField = new CC_SelectList_Field($this->name . '_minute', '', false, $defaultMinuteValue, '', $minuteArray);
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
		$this->hourField->setRecord($record);
		$this->minuteField->setRecord($record);
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
		$this->hourField->setDisabled($disabled);
		$this->minuteField->setDisabled($disabled);
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getHourHTML
	//-------------------------------------------------------------------

	/** 
	 * This method returns HTML for the hour CC_SelectList_Field. 
	 *
	 * @access private
	 * @return string The HTML for the hour CC_SelectList_Field.
	 */

	function getHourHTML()
	{
		return $this->hourField->getHTML();
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getMinuteHTML
	//-------------------------------------------------------------------

	/** 
	 * This method returns HTML for the minutes CC_SelectList_Field. 
	 *
	 * @access private
	 * @return string The HTML for the minutes CC_SelectList_Field.
	 */

	function getMinuteHTML()
	{
		return $this->minuteField->getHTML();
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getEditHTML
	//-------------------------------------------------------------------

	/** 
	 * This method returns HTML for the month, day, year, hour and minutes CC_SelectList_Fields where the select list values can be edited. 
	 *
	 * @access public
	 * @return string The HTML for the field.
	 */

	function getEditHTML()
	{
		return $this->getMonthHTML() . " " . $this->getDateHTML() . ", " . $this->getYearHTML() . " " . $this->getHourHTML() . ":" . $this->getMinuteHTML();
	}



	//-------------------------------------------------------------------
	// METHOD: getViewHTML
	//-------------------------------------------------------------------

	/** 
	 * This method returns HTML for the month, day, year, hour and minutes CC_SelectList_Fields (in the form September 9, 1974, 10:34am) The values cannot be edited. 
	 *
	 * @access public
	 * @return string The HTML for the field.
	 */

	function getViewHTML()
	{
		return date('F d, Y, g:ia', convertMysqlDateTimeToTimestamp($this->getValue()));
	}
	
		
	//-------------------------------------------------------------------
	// METHOD: getFormattedDate
	//-------------------------------------------------------------------

	/** 
	 * The method returns the field's value as a string of the form 'September 9, 1974'. 
	 *
	 * @access public
	 * @return string $defaultValue A string of the form 'September 9, 1974'.
	 */

	function getFormattedDate()
	{
		return date('F d, Y', convertMysqlDateTimeToTimestamp($this->getValue()));
	}
	
		
	//-------------------------------------------------------------------
	// METHOD: getHourValue
	//-------------------------------------------------------------------

	/** 
	 * This method returns the field's hour value.
	 *
	 * @access public
	 * @return int A number from 0-23, depending on the hour.
	 */

	function getHourValue()
	{
		return $this->hourField->getValue();
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getMinuteValue
	//-------------------------------------------------------------------

	/** 
	 * This method returns the field's minutes value.
	 *
	 * @access public
	 * @return int A number from 0-59, depending on the minutes.
	 */

	function getMinuteValue()
	{
		return $this->minuteField->getValue();
	}
	
	
	///-------------------------------------------------------------------
	// METHOD: setHourValue
	//-------------------------------------------------------------------

	/** 
	 * This method sets the field's hour value.
	 *
	 * @access public
	 * @param int A number from 0-23 for the hour.
	 */

	function setHourValue($hourValue)
	{
		$this->hourField->setValue($hourValue);
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setMinuteValue
	//-------------------------------------------------------------------

	/** 
	 * This method sets the field's minutes value.
	 *
	 * @access public
	 * @param int A number from 0-59 for the minutes.
	 */

	function setMinuteValue($minuteValue)
	{
		$this->minuteField->setValue($minuteValue);
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setReadOnly
	//-------------------------------------------------------------------
	
	/** 
	 * This method ensures that hour and minutes CC_SelectList_Fields have the same read-only value as the master CC_DateTime_Field.
	 *
	 * @access public
	 * @param bool $readOnly The value to set (which isn't used anyway).
	 */

	function setReadOnly($fieldReadOnly)
	{
		$this->hourField->setReadOnly($fieldReadOnly);
		$this->minuteField->setReadOnly($fieldReadOnly);
		
		parent::setReadOnly($fieldReadOnly);
	}
	

	//-------------------------------------------------------------------
	// METHOD: getValue
	//-------------------------------------------------------------------

	/** 
	 * The method returns the field's value as a string of the form YYYY-MM-DD HH:MM. 
	 *
	 * @access public
	 * @param String format string for sprintf(). Will be passed year, month, day, hour, second, in that order.
	 * @return string $defaultValue A string of the form YYYY-MM-DD HH:MM representing a date and time.
	 */

	function getValue($format = '%u-%0u-%0u %0u:%0u')
	{
		return sprintf($format, $this->getYearValue(), $this->getMonthValue(), $this->getDateValue(), $this->getHourValue(), $this->getMinuteValue());
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
		parent::setAllowBlankValue($allow);
		
		if ($allow)
		{
			$this->hourField->setUnselectedValue('-HH-');
			$this->minuteField->setUnselectedValue('-MM-');
		}
		else
		{
			$this->hourField->setUnselectedValue();
			$this->minuteField->setUnselectedValue();
		}
	}


	//-------------------------------------------------------------------
	// METHOD: setValue
	//-------------------------------------------------------------------

	/** 
	 * The method returns the field's value as a string of the form YYYY-MM-DD HH:MM. 
	 *
	 * @access public
	 * @return string $defaultValue A string of the form YYYY-MM-DD HH:MM representing a date and time.
	 */

	function setValue($rawValue = '', $clearIfBlank = false)
	{
		if (strlen($rawValue) > 0)
		{
			$parsedDate = getDate(convertMysqlDateTimeToTimestamp(substr($rawValue, 0, 19)));

			$this->setMonthValue($parsedDate['mon']);
			$this->setDateValue($parsedDate['mday']);
			$this->setYearValue($parsedDate['year']);
			
			$this->setHourValue($parsedDate['hours']);
			$this->setMinuteValue($parsedDate['minutes']);
		}
		else if ($clearIfBlank)
		{
			$this->setMonthValue('');
			$this->setDateValue('');
			$this->setYearValue('');
			
			$this->setHourValue('');
			$this->setMinuteValue('');
		}
	}


	//-------------------------------------------------------------------
	// METHOD: handleUpdateFromRequest
	//-------------------------------------------------------------------

	/**
     * This method gets called by CC_Window when it's time to update the field from the $_REQUEST array. Most fields are straight forward, but some have additional fields in the request that need to be handled specially. Such fields should override this method, and update the field's value in their own special way.
     *
     * @access public
     * @param mixed $fieldValue The value to set the field to.
     * @see getValue()
     */	

	function handleUpdateFromRequest()
	{
		parent::handleUpdateFromRequest();
		
		$key = $this->getRequestArrayName();

		if (array_key_exists($key . '_year', $_REQUEST))
		{
			$this->setHourValue($_REQUEST[$key . '_hour']);
			$this->setMinuteValue($_REQUEST[$key . '_minute']);
		}
		
		unset($key);
	}

}

?>