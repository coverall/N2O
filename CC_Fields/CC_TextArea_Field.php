<?php
// $Id: CC_TextArea_Field.php,v 1.9 2004/08/25 21:16:45 patrick Exp $
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
		return '<textarea name="' . $this->getRecordKey() . $this->name . '" cols="' . $this->numColumns . '" rows="' . $this->numRows . '" wrap="soft" tabindex="' . $this->_tabIndex .'">' . $this->value . '</textarea>';
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
		else
		{
			return true;
		}
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
}

?>