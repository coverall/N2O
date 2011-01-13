<?php
// $Id: CC_Time_Field.php,v 1.7 2004/12/08 11:35:59 jamie Exp $
//=======================================================================
// CLASS: CC_Time_Field
//=======================================================================

/**
 * The CC_Time_Field field represents a certain time to the second.
 *
 * @package CC_Fields
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

class CC_Time_Field extends CC_Field
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
	
	
	/**
     * The CC_SelectList_Field that represents the seconds.
     *
     * @var CC_SelectList_Field $secondField
     * @access private
     */	
     
	var $secondField;
	

	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Time_Field
	//-------------------------------------------------------------------

	/**
	 * The hours, seconds and minutes select lists are initialized here.
	 *
	 * @access public
	 * @param string $name The unique name of the field. Names must be unique so that N2O knows which fields to update when users submit data.
	 * @param string $label A text label to accompany the field to describe which input is either expected or displayed.
	 * @param bool $required Whether or not the field is required. Default is false.
	 * @param int $defaultHourValue The initial hour to use. Expected values are from 0-23.
	 * @param int $defaultMinuteValue The initial minutes to use. Expected values are from 0-59.
	 * @param int $defaultSecondValue The initial seconds to use. Expected values are from 0-59.
	 */

	function CC_Time_Field($name, $label, $required = false, $defaultHourValue = 12, $defaultMinuteValue = 0, $defaultSecondValue = 0)
	{
		$this->CC_Field($name, $label, $required);
		
		for ($i = 0; $i < 24; $i++)
		{
			$hourArray[] = sprintf('%02u', $i);
		}
		

		for ($j = 0; $j < 60; $j++)
		{
			$minuteArray[] = sprintf('%02u', $j);
		}
		

		for ($k = 0; $k < 60; $k++)
		{
			$secondArray[] = sprintf('%02u', $k);
		}
		

		$this->hourField   = new CC_SelectList_Field($this->name . '_hour', '', false, $defaultHourValue, '', $hourArray);
		$this->minuteField = new CC_SelectList_Field($this->name . '_minute', '', false, $defaultMinuteValue, '', $minuteArray);
		$this->secondField = new CC_SelectList_Field($this->name . '_second', '', false, $defaultSecondValue, '', $secondArray);
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
		$this->secondField->setDisabled($disabled);
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
		$this->secondField->setRecord($record);
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
	// METHOD: getSecondHTML
	//-------------------------------------------------------------------

	/** 
	 * This method returns HTML for the seconds CC_SelectList_Field. 
	 *
	 * @access private
	 * @return string The HTML for the seconds CC_SelectList_Field.
	 */

	function getSecondHTML()
	{
		return $this->secondField->getHTML();
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getEditHTML
	//-------------------------------------------------------------------

	/** 
	 * This method returns HTML for the hour, minutes and seconds CC_SelectList_Fields where the select list values can be edited. 
	 *
	 * @access public
	 * @return string The HTML for the field.
	 */

	function getEditHTML()
	{
		return $this->getHourHTML() . ':' . $this->getMinuteHTML() . ':' . $this->getSecondHTML();
	}



	//-------------------------------------------------------------------
	// METHOD: getViewHTML
	//-------------------------------------------------------------------

	/** 
	 * This method returns HTML for the hour, minutes and seconds CC_SelectList_Fields (in the form 10:34:45) The values cannot be edited. 
	 *
	 * @access public
	 * @return string The HTML for the field.
	 */

	function getViewHTML()
	{
		return $this->getHourValue() . ':' . $this->getMinuteValue() . ':' . $this->getSecondValue();
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
	
	
	//-------------------------------------------------------------------
	// METHOD: getSecondValue
	//-------------------------------------------------------------------

	/** 
	 * This method returns the field's seconds value.
	 *
	 * @access public
	 * @return int A number from 0-59, depending on the seconds.
	 */

	function getSecondValue()
	{
		return $this->secondField->getValue();
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
	// METHOD: setSecondValue
	//-------------------------------------------------------------------

	/** 
	 * This method sets the field's seconds value.
	 *
	 * @access public
	 * @param int A number from 0-59 for the seconds.
	 */

	function setSecondValue($secondValue)
	{
		$this->secondField->setValue($secondValue);
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setReadOnly
	//-------------------------------------------------------------------
	
	/** 
	 * This method ensures that hour and minutes CC_SelectList_Fields have the same read-only value as the master CC_Time_Field.
	 *
	 * @access public
	 * @param bool $fieldReadOnly The value to set.
	 */
	 
	function setReadOnly($fieldReadOnly)
	{
		$this->hourField->setReadOnly($fieldReadOnly);
		$this->minuteField->setReadOnly($fieldReadOnly);
		$this->secondField->setReadOnly($fieldReadOnly);
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getValue
	//-------------------------------------------------------------------

	/** 
	 * The method returns the field's value as a string of the form HH:MM:SS. 
	 *
	 * @access public
	 * @return string $defaultValue A string of the form HH:MM:SS representing a time.
	 */

	function getValue()
	{
		return sprintf('%04u:%02u:%02u', $this->getHourValue(), $this->getMinuteValue(), $this->getSecondValue());
	}
}

?>