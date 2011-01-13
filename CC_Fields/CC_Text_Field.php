<?php
// $Id: CC_Text_Field.php,v 1.16 2004/12/01 20:27:25 patrick Exp $
//=======================================================================
// CLASS: CC_Text_Field
//=======================================================================

/**
 * The CC_Text_Field field allows users to input or view short text information for use in the application. 
 *
 * @package CC_Fields
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

class CC_Text_Field extends CC_Field
{
	/**
     * The visible size of the text field in characters.
     *
     * @var int $size
     * @access private
     */
     
	var $size;
	
	
	/**
     * The number of allowable characters in the field.
     *
     * @var int $maxlength
     * @access private
     */
     
     var $maxlength;
	
	
	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Text_Field
	//-------------------------------------------------------------------

	/** 
	 * The CC_Text_Field constructor sets its values here, yo. 
	 *
	 * @access public
	 * @param string $name The unique name of the field. Names must be unique so that N2O knows which fields to update when users submit data.
	 * @param string $label A text label to accompany the field to describe which input is either expected or displayed.
	 * @param bool $required Whether or not this field must contain data from the user before proceeding. Not required by default.
	 * @param string $defaultValue The value the field should contain before user's submit input. The field is blank by default.
	 * @param int $size The visible size of the field, in characters.
	 * @param int $maxlength The maximum amount of data we can enter in the field, in characters.
	 */

	function CC_Text_Field($name, $label = '', $required = false, $defaultValue = '', $size = 32, $maxlength = 128)
	{
		$this->maxlength = $maxlength;
		$this->size = $size;
		
		$this->CC_Field($name, $label, $required, $defaultValue);
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getEditHTML
	//-------------------------------------------------------------------

	/** 
	 * Returns HTML for an 'text' form field. 
	 *
	 * @access public
	 * @return string The HTML for the field.
	 */

	function getEditHTML()
	{
		return '<input type="text" id="' . $this->id . '"  size="' . $this->size. '" maxlength="' . $this->maxlength . '" name="' . $this->getRecordKey() . $this->name . '" value="' . htmlspecialchars($this->value) . '" class="' . $this->inputStyle . '"' . ($this->disabled ? ' disabled="true"' : '') . ' tabindex="' . $this->_tabIndex .'">';
	}
	
	//-------------------------------------------------------------------
	// METHOD: setSize
	//-------------------------------------------------------------------

	/** 
	 * This sets the visible size of the text field, in characters.
	 *
	 * @access public
	 * @param int $size The size to set.
	 */

	function setSize($size)
	{
		$this->size = $size;
	}
}

?>