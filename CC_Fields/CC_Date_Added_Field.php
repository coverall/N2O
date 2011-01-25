<?php
// $Id: CC_Date_Added_Field.php,v 1.6 2003/08/26 09:01:35 patrick Exp $
//=======================================================================
// CLASS: CC_Date_Added_Field
//=======================================================================

/**
 * The CC_Date_Added_Field field represents the date when a record was orginally added to the database. It is a required field for each of the user-defined tables in the application.
 *
 * @package CC_Fields
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */
 
class CC_Date_Added_Field extends CC_DateTime_Field
{	
	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Date_Added_Field
	//-------------------------------------------------------------------

	/** 
	 * The field is constructed and is identical to a CC_DateTime_Field except it is always read-only and is not added to the database (it is only read *from* the database). 
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

	function CC_Date_Added_Field($name, $label, $defaultMonthValue, $defaultDateValue, $defaultYearValue, $defaultHourValue, $defaultMinuteValue)
	{
		$this->CC_DateTime_Field($name, $label, false, $defaultMonthValue, $defaultDateValue, $defaultYearValue, $defaultHourValue, $defaultMinuteValue);

		$this->setReadOnly(true);
		$this->setAddToDatabase(false);
	}	


	//-------------------------------------------------------------------
	// METHOD: setReadOnly
	//-------------------------------------------------------------------
	
	/** 
	 * This overidden method ensures that the field remains read-only, even if explicitly set otherwise.
	 *
	 * @access public
	 * @param bool $readOnly The value to set (which isn't used anyway).
	 */

	
	function setReadOnly($readOnly)
	{
		parent::setReadOnly(true);
	}

}

?>