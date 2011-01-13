<?php
// $Id: CC_Safari_Search_Field.php,v 1.4 2008/06/04 21:46:59 patrick Exp $
//=======================================================================
// CLASS: CC_Safari_Search_Field
//=======================================================================

/**
 * The CC_Safari_Search_Field field allows users to input or view short text information for use in the application.
 *
 * In addition to what's supported globally by all CC_Fields (see documentation), this field supports the following arguments for the fourth argument of CC_FieldManager's addField() method:
 *
 * size=[n] - the size of the text field (where [n] is a positive integer).
 * maxlength=[n] - the maximum number of characters the field will allow for input.
 *
 * @package CC_Fields
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

class CC_Safari_Search_Field extends CC_Text_Field
{
	var $_placeHolder;
	

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
		return '<input type="search" placeholder="' . $this->_placeHolder . '" autosave="' . $this->getName() . '" results="5" id="' . $this->id . '"  size="' . $this->size. '" maxlength="' . $this->maxlength . '" name="' . $this->getRecordKey() . $this->name . '" value="' . htmlspecialchars($this->value) . '" class="' . $this->inputStyle . '"' . ($this->disabled ? ' disabled="true"' : '') . ($this->_onKeyup ? ' onKeyup="' . $this->_onKeyup . '"' : '' ) . '>';
	}


	//-------------------------------------------------------------------
	// METHOD: setPlaceHolder
	//-------------------------------------------------------------------
	
	function setPlaceHolder($placeHolder)
	{
		$this->_placeHolder = $placeHolder;
	}

}

?>