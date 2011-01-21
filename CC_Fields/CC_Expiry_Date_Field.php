<?php
// $Id: CC_Expiry_Date_Field.php,v 1.18 2004/04/14 17:39:39 patrick Exp $
//=======================================================================
// CLASS: CC_Expiry_Date_Field
//=======================================================================

/**
 * The CC_Expiry_Date_Field field represents a credit card expiry date field.
 *
 * @package CC_Fields
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

class CC_Expiry_Date_Field extends CC_Date_Field
{
	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Expiry_Date_Field
	//-------------------------------------------------------------------

	/** 
	 * The field is constructed and is identical to a CC_Date_Field except it has select lists for hour and minute as well. The select lists are constructed here.
	 *
	 * @access public
	 * @param string $name The unique name of the field. Names must be unique so that N2O knows which fields to update when users submit data.
	 * @param string $label A text label to accompany the field to describe which input is either expected or displayed.
	 * @param bool $required Whether or not the field is required. Default is false.
	 * @param int $defaultMonthValue The initial month to use. Expected values are from 1-12.
	 * @param int $defaultDateValue The initial date to use. Expected values are from 1-31.
	 * @param int $defaultYearValue The initial year to use. A valid year is expected here.
	 * @param int $defaultHourValue The initial hour to use. Expected values are from 0-23.
	 * @param int $defaultMinuteValue The initial minute to use. Expected values are from 0-59.
	 * @param int $startYear The start year in the year select list's range.	
	 * @param int $endYear The end year in the year select list's range.	
	 */

	function CC_Expiry_Date_Field($name, $label, $required = false, $defaultMonthValue = -1, $defaultDateValue = 1, $defaultYearValue = -1, $startYear = 2004, $endYear = 2012)
	{
		$bigEndYear = 0;
		
		$today = getdate(strtotime('today +1 month'));

		if ($defaultMonthValue == -1 && $defaultYearValue == -1)
		{
			$defaultMonthValue = $today['mon'];
			$defaultYearValue = $today['year'];
		}
		
		if ($startYear < intval($today['year']))
		{
			$startYear = intval($today['year']);
		}
		
		if (intval($endYear) < ($startYear + 8))
		{
			$endYear = $startYear + 8;
		}

		$this->CC_Date_Field($name, $label, $required, $defaultMonthValue, $defaultDateValue, $defaultYearValue, $startYear, $endYear);
		
		$this->setValidateIfNotRequired(false);
		
		unset($today);
	}

	
	//-------------------------------------------------------------------
	// METHOD: getDateHTML
	//-------------------------------------------------------------------

	/** 
	 * This method returns HTML for the date CC_SelectList_Field which doesn't exist since there is only a month and a year. 
	 *
	 * @access private
	 * @return string A blank string.
	 */

	function getDateHTML()
	{
		return '';
	}
	

	//-------------------------------------------------------------------
	// METHOD: getDateValue
	//-------------------------------------------------------------------

	/** 
	 * This method returns the field's date value.
	 *
	 * @access public
	 * @return int 1.
	 */

	function getDateValue()
	{
		return 1;
	}
	
	//-------------------------------------------------------------------
	// METHOD: getEditHTML
	//-------------------------------------------------------------------

	/** 
	 * This method returns HTML for the month and year CC_SelectList_Fields where the select list values can be edited. 
	 *
	 * @access public
	 * @return string The HTML for the field.
	 */

	function getEditHTML()
	{
		return $this->getMonthHTML() . ' ' . $this->getYearHTML();
	}


	//-------------------------------------------------------------------
	// METHOD: getViewHTML
	//-------------------------------------------------------------------

	/** 
	 * This method returns HTML for the month and year CC_SelectList_Fields where the select list values cannot be edited. The format is 09/2008)
	 *
	 * @access public
	 * @return string The HTML for the field.
	 */

	function getViewHTML()
	{
		return sprintf('%02u/%s', $this->getMonthValue(), $this->getYearValue());
	}
	

	//-------------------------------------------------------------------
	// METHOD: validate
	//-------------------------------------------------------------------
	
	/** 
	 * The method verifies whether or not a expired date was entered. 
	 *
	 * @access public
	 * @return bool Whether or not the date is expired.
	 */
	 
	function validate()
	{
		$today = getdate();
		
		// Make sure the expiry date is not before today...
		if ($this->getYearValue() < $today['year'])
		{
			return false;
		}
		else if ($this->getYearValue() == $today['year'])
		{
			if ($this->getMonthValue() < $today['mon'])
			{
				return false;
			}
		}

		return checkdate($this->getMonthValue(), $this->getDateValue(), $this->getYearValue());
	}
}

?>