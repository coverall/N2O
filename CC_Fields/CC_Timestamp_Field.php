<?php
// $Id: CC_Timestamp_Field.php,v 1.5 2003/11/26 19:45:18 mike Exp $
//=======================================================================
// CLASS: CC_Timestamp_Field
//=======================================================================

/**
 * The CC_Timestamp_Field field represents a database timestamp.
 *
 * @package CC_Fields
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

class CC_Timestamp_Field extends CC_Field
{
	/**
     * The value for the month.
     *
     * @var int $monthValue
     * @access private
     */	

	var $monthValue;
	
	
	/**
     * The value for the day of the month.
     *
     * @var int $dateValue
     * @access private
     */	

	var $dateValue;


	/**
     * The value for the year.
     *
     * @var int $yearValue
     * @access private
     */	

	var $yearValue;	


	/**
     * The value for the hour.
     *
     * @var int $hourValue
     * @access private
     */		

	var $hourValue;


	/**
     * The value for the minute.
     *
     * @var int $minuteValue
     * @access private
     */		

	var $minuteValue;
	

	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Timestamp_Field
	//-------------------------------------------------------------------

	/** 
	 * The field is constructed and is set to be updated from the database and not added to the database.
	 *
	 * @access public
	 * @param string $name The unique name of the field. Names must be unique so that N2O knows which fields to update when users submit data.
	 * @param string $label A text label to accompany the field to describe which input is either expected or displayed.
	 * @param int $defaultMonthValue The initial month to use. Expected values are from 1-12.
	 * @param int $defaultDateValue The initial date to use. Expected values are from 1-31.
	 * @param int $defaultYearValue The initial year to use. A valid year is expected here.
	 * @param int $defaultHourValue The initial hour to use. Expected values are from 0-23.
	 * @param int $defaultMinuteValue The initial minute to use. Expected values are from 0-59.
	 */

	function CC_Timestamp_Field($name, $label, $required = false, $defaultMonthValue = -1, $defaultDateValue = -1, $defaultYearValue = -1, $defaultHourValue = -1, $defaultMinuteValue = -1)
	{
		$this->CC_Field($name, $label, $required);
		
		$this->setAddToDatabase(false);
		$this->setUpdateFromDatabase(true);
		
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
		
		if ($defaultHourValue == -1)
		{
			$defaultHourValue = 12;
		}
		
		if ($defaultMinuteValue == -1)
		{
			$defaultMinuteValue = 0;
		}
		
		$this->setMonthValue($defaultMonthValue);
		
		$this->setDateValue($defaultDateValue);

		$this->setYearValue($defaultYearValue);
		
		$this->setHourValue($defaultHourValue);
		
		$this->setMinuteValue($defaultMinuteValue);
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
		return date("F d, Y, g:ia", convertMysqlTimestampToPHPTimestamp($this->getValue()));
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getEditHTML
	//-------------------------------------------------------------------

	/** 
	 * This method returns HTML for the month, day, year, hour and minutes CC_SelectList_Fields (in the form September 9, 1974, 10:34am) The values cannot be edited. 
	 *
	 * @access public
	 * @return string The HTML for the field.
	 */

	function getEditHTML()
	{
		return $this->getViewHTML();
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
		return $this->monthValue;
	}
	

	//-------------------------------------------------------------------
	// METHOD: getDateValue
	//-------------------------------------------------------------------

	/** 
	 * This method returns the field's day value.
	 *
	 * @access public
	 * @return int A number from 1-31, depending on the day.
	 */

	function getDateValue()
	{
		return $this->dateValue;
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
		return $this->yearValue;
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
		return $this->hourValue;
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
		return $this->minuteValue;
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

	function setMonthValue($monthValue)
	{
		return $this->monthValue = $monthValue;
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

	function setDateValue($dateValue)
	{
		return $this->dateValue = $dateValue;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setYearValue
	//-------------------------------------------------------------------

	/** 
	 * This method sets the field's date value.
	 *
	 * @access public
	 * @param int A number from 1-31 for the date.
	 */

	function setYearValue($yearValue)
	{
		return $this->yearValue = $yearValue;
	}
	
	
	//-------------------------------------------------------------------
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
		return $this->hourValue = $hourValue;
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
		return $this->minuteValue = $minuteValue;
	}
	
		
	//-------------------------------------------------------------------
	// METHOD: getValue
	//-------------------------------------------------------------------

	/** 
	 * The method returns the field's value as a string of the form YYYYMMDDHHMM00. 
	 *
	 * @access public
	 * @return string A string of the form YYYYMMDDHHMM00 representing a timestamp.
	 */

	function getValue()
	{
		return $this->getYearValue() . zeroPad($this->getMonthValue()) . zeroPad($this->getDateValue()) . zeroPad($this->getHourValue()) . zeroPad($this->getMinuteValue()) . "00";
	}
}

?>