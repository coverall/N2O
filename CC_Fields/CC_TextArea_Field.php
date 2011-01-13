<?php
// $Id: CC_TextArea_Field.php,v 1.23 2010/11/11 04:28:32 patrick Exp $
//=======================================================================
// CLASS: CC_TextArea_Field
//=======================================================================

/**
 * The CC_TextArea_Field field allows users to input or view long text information for use in the application. 
 *
 * @package CC_Fields
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

class CC_TextArea_Field extends CC_Field
{
	/**
     * The number of columns in the text area.
     *
     * @var int $numColumns
     * @access private
     */
     
	var $numColumns;
	
	
	/**
     * The number of rows in the text area.
     *
     * @var int $numRows
     * @access private
     */
     
	var $numRows;
	
	
	/**
     * Link "http" dingles to the internet!
     *
     * @var boolean $autolink
     * @access private
     */
     
	var $autolink;


	/**
     * The maximum number of words you can have in here.
     *
     * @var int $maxWords
     * @access private
     */
     
	var $maxWords = 0;


	/**
     * The maximum number of characters you can have in here.
     *
     * @var int $maxLength
     * @access private
     */
     
	var $maxLength = 0;
	
	
	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_TextArea_Field
	//-------------------------------------------------------------------

	/** 
	 * The CC_TextArea_Field constructor sets its values here, yo. 
	 *
	 * @access public
	 * @param string $name The unique name of the field. Names must be unique so that N2O knows which fields to update when users submit data.
	 * @param string $label A text label to accompany the field to describe which input is either expected or displayed.
	 * @param bool $required Whether or not this field must contain data from the user before proceeding. Not required by default.
	 * @param string $defaultValue The value the field should contain before user's submit input. The field is blank by default.
	 * @param int $numColumns The number of columns in the text area.
	 * @param int $numRows The number of rows in the text area.
	 */

	function CC_TextArea_Field($name, $label, $required, $defaultValue, $numColumns, $numRows)
	{
		$this->numColumns = $numColumns;
		$this->numRows = $numRows;
		
		$this->CC_Field($name, $label, $required, $defaultValue);
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getEditHTML
	//-------------------------------------------------------------------

	/** 
	 * Returns HTML for an 'textarea' form field. 
	 *
	 * @access public
	 * @return string The HTML for the field.
	 */

	function getEditHTML()
	{
		return '<textarea name="' . $this->getRecordKey() . $this->name . '" cols="' . $this->numColumns . '" rows="' . $this->numRows . '" wrap="soft" class="' . $this->getInputStyle() . '" maxwords="' . $this->maxWords . '">' . $this->value . '</textarea>';
	}


	//-------------------------------------------------------------------
	// METHOD: getViewHTML
	//-------------------------------------------------------------------

	/** 
	 * Returns the text of the field in HTML, replacing return and newlines with <br> tags. 
	 *
	 * @access public
	 * @return string The HTML for the field.
	 */

	function getViewHTML()
	{
		$html = ereg_replace("(\r\n|\n|\r)", "<br>", $this->getValue());
		
		if ($this->autolink)
		{
			$html = linkify($html);
		}
		
		return $html;
	}


	//-------------------------------------------------------------------
	// METHOD: validate
	//-------------------------------------------------------------------
	
	/** 
	 * This method verifies whether or not the field has a value and is therefore valid. 
	 *
	 * @access public
	 * @return bool Whether or not the text area has a value.
	 * @todo Do we really need this validate method? Doesn't this logic only apply to required fields?
	 */
	
	function validate()
	{
		if (strlen($this->getValue()) == 0)
		{
			return false;
		}
		
		if ($this->maxWords > 0)
		{
			$words = explode(' ', $this->getValue());
		
			if (sizeof($words) > $this->maxWords)
			{
				unset($words);
				$this->setErrorMessage($this->label . ' contains too many words.', CC_FIELD_ERROR_INVALID);
				return false;
			}
			
			unset($words);
		}
		
		if ($this->maxLength > 0)
		{
			$mblength = cc_strlen($this->getValue());
			//$length = strlen($this->getValue());
		
			if ($mblength > $this->maxLength)
			{
				unset($length);
				$this->setErrorMessage($this->label . ' contains too many characters.', CC_FIELD_ERROR_INVALID);
				return false;
			}
			
			unset($length);
		}
		
		return true;
	}

	
	//-------------------------------------------------------------------
	// METHOD: setAutolink
	//-------------------------------------------------------------------
	
	/** 
	 * This method activates autolinking of read-only text produced by this field.
	 *
	 */
	
	function setAutolink($autolink)
	{
		$this->autolink = $autolink;
	}


	//-------------------------------------------------------------------
	// METHOD: setMaxWords
	//-------------------------------------------------------------------
	
	/** 
	 * This method sets the maximum number of words you can have.
	 *
	 */
	
	function setMaxWords($maxWords)
	{
		$this->maxWords = $maxWords;
	}


	//-------------------------------------------------------------------
	// METHOD: setMaxLength
	//-------------------------------------------------------------------
	
	/** 
	 * This method sets the maximum number of characters you can have.
	 *
	 */
	
	function setMaxLength($maxLength)
	{
		$this->maxLength = $maxLength;
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
		$field = new $className($name, $label, $required, $value, (isset($args->x) ? $args->x : 50), (isset($args->y) ? $args->y : 5));
		
		$field->setMaxWords((isset($args->maxWords) ? $args->maxWords : 0));
		$field->setMaxLength((isset($args->maxLength) ? $args->maxLength : 0));
		
		if (isset($args->autolink))
		{
			$field->setAutolink($args->autolink);
		}
		
		return $field;
	}

}

?>