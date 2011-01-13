<?php
// $Id: CC_Text_Field.php,v 1.25 2010/11/11 04:28:32 patrick Exp $
//=======================================================================
// CLASS: CC_Text_Field
//=======================================================================

/**
 * The CC_Text_Field field allows users to input or view short text information for use in the application.
 *
 * In addition to what's supported globally by all CC_Fields (see documentation), this field supports the following arguments for the fourth argument of CC_FieldManager's addField() method:
 *
 * size=[n] - the size of the text field (where [n] is a positive integer).<br>
 * maxlength=[n] - the maximum number of characters the field will allow for input.<br>
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


	/**
     * If set, the select will use this javascript code on the onKeyup="" parameter.
     *
     * @var mixed $onKeyup
     * @access private
     */
     
	var $_onKeyup;


	
	
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
		return '<input type="text" id="' . $this->id . '"  size="' . $this->size. '" maxlength="' . $this->maxlength . '" name="' . $this->getRecordKey() . $this->name . '" value="' . htmlspecialchars($this->value) . '" class="' . $this->inputStyle . '"' . ($this->disabled ? ' disabled="true"' : '') . ($this->_onKeyup ? ' onKeyup="' . $this->_onKeyup . '"' : '' ) . '>';
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


	//-------------------------------------------------------------------
	// METHOD: setJavascriptOnKeyup
	//-------------------------------------------------------------------

	/** 
	 * This sets the Javascript code that will be used in the onKeyup="" parameter.
	 *
	 * @access public
	 * @param mixed $onChange The Javascript code.
	 */

	function setJavascriptOnKeyup($onKeyup)
	{
		$this->_onKeyup = $onKeyup;
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

	static function &getInstance($className, $name, $label, $value, $args, $required)
	{
		$size = (isset($args->size) ? $args->size : 32);
		$maxlength = (isset($args->maxlength) ? $args->maxlength : 128);
		
		$field = new $className($name, $label, $required, $value, $size, $maxlength);
		
		unset($size, $maxlength);

		return $field;
	}

}

?>