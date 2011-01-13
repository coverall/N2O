<?php
// $Id: CC_Timestamp_Field.php,v 1.6 2005/05/11 00:31:52 patrick Exp $
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

class CC_Timestamp_Field extends CC_DateTime_Field
{
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

	function CC_Timestamp_Field($name, $label, $required = false, $defaultMonthValue = -1, $defaultDateValue = -1, $defaultYearValue = -1, $defaultHourValue = 12, $defaultMinuteValue = 0)
	{
		$this->CC_DateTime_Field($name, $label, $required, $defaultMonthValue, $defaultDateValue, $defaultYearValue, $defaultHourValue, $defaultMinuteValue);
		
		$this->setAddToDatabase(false);
		$this->setUpdateFromDatabase(true);
		$this->setReadOnly(true);
	}


	//-------------------------------------------------------------------
	// METHOD: setReadOnly
	//-------------------------------------------------------------------

	/**
     * Sets whether or not the field is read-only, or uneditable.
     *
     * @access public
     * @param bool $fieldReadOnly Whether or not the field is editable.
     * @see isReadOnly()
     */	

	function setReadOnly($readonly)
	{
		parent::setReadOnly(true);
	}

}

?>