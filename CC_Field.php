<?php
// $Id: CC_Field.php,v 1.59 2004/11/02 21:44:48 mike Exp $
//=======================================================================
// CLASS: CC_Field
//=======================================================================

define('CC_FIELD_ERROR_MISSING', 1);
define('CC_FIELD_ERROR_INVALID', 2);
define('CC_FIELD_ERROR_CUSTOM', 4);
define('CC_FIELD_ERROR_ALL', 7);

/**
 * This is the superclass for N2O fields. Fields provide applications with an interface to accept user input. Subclasses build on HTML form fields for display.
 *
 * @package CC_Fields
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 * @todo Should getValue() return "[value]" or '[value]'? In PHP, single quoted strings are literal -- that is no variables are expanding, and only few escaped characters will actually work. Double-quoted strings will more heavily processed -- embedded variables are expanded, and there are many special characters you can use by escaping characters. (eg. \n, \r, \t, etc.) For more information on how PHP handles strings, see the following URLs: http://www.php.net/manual/en/language.types.string.php http://www.zend.com/zend/tut/using-strings.php
 */

class CC_Field extends CC_Component
{
	/**
     * Whether or not the field is required (ie. the user *must* provide a value in order to continue).
     *
     * @var bool $required
     * @access private
     * @see setRequired()
     */

	var $required;
	
	
	/**
     * The input component's optional CSS style.
     *
     * @var string $inputStyle
     * @see setInputStyle()
     * @access private
     */

	var $inputStyle;
	

	/**
     * The text label for the item.
     *
     * @var string $label
     * @access private
     * @see getLabel()
     * @see setLabel()
     */	
     
	var $label;
	
	
	/**
	 * A field label that is unique across all application windows which is mainly used for CC_Summary spreadsheet downloads (instead of the $label variable which is not necessarily unique across all application windows).
     *
     * @var string $_summaryLabel
     * @access private
     * @see getSummaryLabel()
     * @see setSummaryLabel()
     */	
     
	var $_summaryLabel;
						
								 
	/**
	 * Indicates whether or not to display an asterisk near the field label when the field is required.
     *
     * @var string $showAsterisk
     * @access private
     * @see setShowAsterisk()
     */	

	var $showAsterisk = true;


	/**
	 * The value of the field (stored only after the form is processed and data is updated).
	 *
     * @var mixed $value
     * @see getValue()
     * @access private
     */	

	var $value;


	/**
     * Indicates whether or not the field is to be added to the database when a record is added.
     *
     * @var bool $addToDatabase
     * @see setAddToDatabase()
     * @see CC_Record::buildUpdateQuery()
     * @see CC_Record::buildInsertQuery()
     * @access private
     */	

	var $addToDatabase;


	/**
     * Indicated whether or not the current field has an error. Generally only true if the field is missing input or is invalid in some way.
     *
     * @var bool $_error
     * @see setError()
     * @access private
     */	

	var $_error;
	

	/**
     * The error mask indicates which error messages are to be displayed to the user. Values to check for are CC_FIELD_ERROR_MISSING, CC_FIELD_ERROR_INVALID, CC_FIELD_ERROR_CUSTOM and CC_FIELD_ERROR_ALL
     *
     * @var int $_errorMask
     * @see setErrorMask()
     * @access private
     */	

	var $_errorMask = CC_FIELD_ERROR_ALL;
	

	/**
     * The error message if a field's input is missing. 
     *
     * @var string $_missingError
     * @see setErrorMessage()
     * @access private
     */	

	var $_missingError = '';


	/**
     * The error message if a field's input is invalid. 
     *
     * @var string $_invalidError
     * @see setErrorMessage()
     * @access private
     */	

	var $_invalidError = '';


	/**
     * The custom error message for miscellaneous field errors. 
     *
     * @var string $_customError
     * @see setErrorMessage()
     * @access private
     */	

	var $_customError = '';
	

	/**
     * Indicates whether or not the field is encoded when added to the database.
     *
     * @var bool $encode
     * @see setEncode()
     * @see getEncode()
     * @access private
     */	

   	var $encode = false;


	/**
     * Indicates whether or not the field is read-only.
     *
     * @var bool $readonly
     * @see setReadOnly()
     * @see isReadOnly()
     * @access private
     */	

   	var $readonly = false;


	/**
     * Indicates whether or not this field is a foreign key.
     *
     * @var bool $foreignkey
     * @access public
     */	

   	var $foreignKey = false;


	/**
     * Indicates whether or not this field uses the database's password() function when it is added to the database.
     *
     * @var bool $password
     * @see setPassword()
     * @see getPassword()
     * @access private
     */	

   	var $password = false;
   	

	/**
     * Set this to true for fields like LAST_MODIFIED that always needs its values updated direct from the database (as opposed to from the page via user input).
     *
     * @var bool $updateFromDatabase
     * @see setUpdateFromDatabase()
     * @see getUpdateFromDatabase()
     * @access private
     */	

   	var $updateFromDatabase = false;
	

	/**
     * A reference to the parent record, if there is one.
     *
     * @var CC_Record $record
     * @see setRecord()
     * @see getRecord()
     * @access private
     */	

	var $record;


	/**
     * Set this to false if you don't want required fields to execute their validate handlers.
     *
     * @var bool $validateIfNotRequired
     * @see setValidateIfNotRequired()
     * @access private
     */	

	var $validateIfNotRequired = true;

   	
	/**
     * Set this to true if you want the field disabled
     *
     * @var bool $disabled
     * @see setDisabled()
     * @access private
     */	

	var $disabled = false;

   	
	/**
     * Use this for an optional id entity.
     *
     * @var string $id
     * @see setId()
     * @access private
     */	

	var $id = '';


	/**
     * The "tabindex" for this field. This will allow us to control the order in which fields are tabbed.
	 *
     * @var int $_tabIndex
     * @access private
     */

	var $_tabIndex;

   	
   	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Field
	//-------------------------------------------------------------------

	/** 
	 * The CC_Field constructor should be called by each field subclass. 
	 *
	 * @access public
	 * @param string $name The unique name of the field. Names must be unique so that N2O knows which fields to update when users submit data.
	 * @param string $label A text label to accompany the field to describe which input is either expected or displayed.
	 * @param bool $required Whether or not this field must contain data from the user before proceeding. Fields are not required by default.
	 * @param mixed $defaultValue The value the field should contain before user's submit input. Fields are initially blank by default.
	 */

	function CC_Field($name, $label, $required = false, $defaultValue = '')
	{
		$this->setName($name);
		$this->label = $label;

		$this->setRequired($required);
		$this->_error = false;
		$this->setValue($defaultValue);
		$this->addToDatabase = true;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setStyle
	//-------------------------------------------------------------------

	/**
	 * This method sets the component's CSS style for the input and the label. The styles are described in cc_styles.css but can overriden for each application provided the appropriate CSS file is referenced in the application's header file(s).
	 *
	 * @access public
	 * @param string $style The CSS style to set. 
	 */

	function setStyle($style)
	{
		$this->setLabelStyle($style);
		$this->setInputStyle($style);
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setInputStyle
	//-------------------------------------------------------------------

	/**
	 * This method sets the input component's CSS style. The styles are described in cc_styles.css but can overriden for each application provided the appropriate CSS file is referenced in the application's header file(s).
	 *
	 * @access public
	 * @param string $style The CSS style to set. 
	 */

	function setInputStyle($style)
	{
		$this->inputStyle = $style;
	}


	//-------------------------------------------------------------------
	// METHOD: getInputStyle
	//-------------------------------------------------------------------

	/**
	 * This method gets the input component's CSS style.
	 *
	 * @access public
	 * @return string The component's CSS style. 
	 * @see setStyle()
	 */

	function getInputStyle()
	{
		return $this->inputStyle;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setLabelStyle
	//-------------------------------------------------------------------

	/**
	 * This method sets the label's CSS style. The styles are described in cc_styles.css but can overriden for each application provided the appropriate CSS file is referenced in the application's header file(s).
	 *
	 * @access public
	 * @param string $style The CSS style to set for the label. 
	 */

	function setLabelStyle($style)
	{
		$this->labelStyle = $style;
	}


	//-------------------------------------------------------------------
	// METHOD: getLabelStyle
	//-------------------------------------------------------------------

	/**
	 * This method gets the label's CSS style.
	 *
	 * @access public
	 * @return string The label's CSS style. 
	 * @see setStyle()
	 */

	function getLabelStyle()
	{
		return $this->labelStyle;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setShowAsterisk
	//-------------------------------------------------------------------
	
	/** 
	 * This method sets whether or not to display an asterisk for required fields. If set to true it is common to provide a user with a message saying 'required fields are marked with an asterisk'. 
	 *
	 * @access public
	 * @param bool $show Whether or not to show the asterisk.
	 */

	function setShowAsterisk($show)
	{
		$this->showAsterisk = $show;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getLabelText
	//-------------------------------------------------------------------

	/** 
	 * This method gets the field's text label as text.
	 *
	 * @access public
	 * @return string Text of the field's label.
	 * @see setLabel()
	 */

	function getLabelText()
	{
		return $this->label;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getLabel
	//-------------------------------------------------------------------

	/** 
	 * This method gets the field's text label as HTML. If an error is encountered, the ccError styles is used, otherwise the field's current style is selected.
	 *
	 * @access public
	 * @return string HTML of the field's label.
	 * @see setLabel()
	 */

	function getLabel()
	{
		
		$style = ($this->_error ? 'ccLabelError' : $this->labelStyle);

		if ($style)
		{
			$labelText = '<span class="' . $style . '">';
		}
		else
		{
			$labelText = '';
		}
		
		$labelText .= $this->label;
		
		if ($this->required && $this->showAsterisk)
		{
			$labelText .= '<sup>*</sup>';
		}
		
		
		if ($style)
		{
			$labelText .= '</span>';
		}
		
		unset($style);
		
		return $labelText;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setLabel
	//-------------------------------------------------------------------

	/** 
	 * This method sets the field's text label.
	 *
	 * @access public
	 * @param string $labelToSet The field's label.
	 * @see getLabel()
	 */

	function setLabel($labelToSet)
	{
		$this->label = $labelToSet;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setSummaryLabel
	//-------------------------------------------------------------------

	/** 
	 * This method sets the field's summary text label. This is the label used to differentiate fields when downloading field data from a CC_Summary since fields may have the same labels in the application.
	 *
	 * @access public
	 * @param string $labelToSet The field's summary label.
	 * @see getSummaryLabel()
	 */

	function setSummaryLabel($labelToSet)
	{
		$this->_summaryLabel = $labelToSet;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getSummaryLabel
	//-------------------------------------------------------------------

	/** 
	 * This method gets the field's summary text label. This is the label used to differentiate fields when downloading field data from a CC_Summary since fields may have the same labels in the application.
	 *
	 * @access public
	 * @param string $labelToSet The field's summary label.
	 * @see setSummaryLabel()
	 */


	function getSummaryLabel($labelToSet)
	{
		return $this->_summaryLabel;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getHTML
	//-------------------------------------------------------------------

	/** 
	 * This method gets the HTML of the field for display in the window. Subclasses will want to override getViewHTML and getEditHTML.
	 *
	 * @access public
	 * @return string The HTML for the field.
	 * @see getEditHTML()
	 * @see getViewHTML()
	 */


	function getHTML()
	{
		if ($this->readonly)
		{
			return $this->getViewHTML();
		}
		else
		{
			return $this->getEditHTML();
		}
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getEditHTML
	//-------------------------------------------------------------------

	/** 
	 * This method gets the HTML of the field for editable fields (ie. not read-only).
	 *
	 * @access private
	 * @return string The HTML for the editable field.
	 * @see getViewHTML()
	 * @see getHTML()
	 * @see isReadOnly()
	 */


	function getEditHTML()
	{
		return '';
	}


	//-------------------------------------------------------------------
	// METHOD: getViewHTML
	//-------------------------------------------------------------------

	/** 
	 * This method gets the HTML of the field for non-editable fields (ie. read-only).
	 *
	 * @access private
	 * @return string The HTML for the non-editable field.
	 * @see getEditHTML()
	 * @see getHTML()
	 * @see isReadOnly()
	 */

	function getViewHTML()
	{
		if ($this->inputStyle)
		{
			return '<span class="' . $this->inputStyle . '">' . $this->getValue() . '</span>';
		}
		else
		{
			return $this->getValue();
		}
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getEncode
	//-------------------------------------------------------------------

	/**
     * Gets whether or not the field is encoded when added to the database.
     *
     * @access public
     * @return bool Whether or not the field should be encoded in the database.
     * @see setEncode()
     */	

	function getEncode()
	{
		return $this->encode;
	}


	//-------------------------------------------------------------------
	// METHOD: setEncode
	//-------------------------------------------------------------------

	/**
     * Sets whether or not the field is encoded when added to the database.
     *
     * @access public
     * @param bool $encode Whether or not the field should be encoded in the database.
     * @see getEncode()
     */	

	function setEncode($encode)
	{
		$this->encode = $encode;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getPassword
	//-------------------------------------------------------------------

	/**
     * Gets whether or not the field is encoded using the password() function when added to the database.
     *
     * @access public
     * @return bool Whether or not the field should be encoded with the password() function in the database.
     * @see setPassword()
     * @todo is this only for MYSQL?
     */	

	function getPassword()
	{
		return $this->password;
	}


	//-------------------------------------------------------------------
	// METHOD: setPassword
	//-------------------------------------------------------------------

	/**
     * Sets whether or not the field is encoded using the password() function when added to the database.
     *
	 * @access public
     * @param bool $password Whether or not the field should be encoded with the password() function in the database.
     * @see getPassword()
     */	

	function setPassword($password)
	{
		$this->password = $password;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setValue
	//-------------------------------------------------------------------

	/**
     * Sets the field's value.
     *
     * @access public
     * @param mixed $fieldValue The value to set the field to.
     * @see getValue()
     */	

	function setValue($fieldValue = '')
	{
		$this->value = $fieldValue;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: isReadOnly
	//-------------------------------------------------------------------

	/**
     * Gets whether or not the field is read-only, or uneditable.
     *
     * @access public
     * @return bool Whether or not the field is editable.
     * @see setReadOnly()
     */	

	function isReadOnly()
	{
		return $this->readonly;
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

	function setReadOnly($fieldReadOnly)
	{
		$this->readonly = $fieldReadOnly;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setAddToDatabase
	//-------------------------------------------------------------------

	/**
     * Sets whether or not the field should be added to the database when the record is inserted or updated.
     *
     * @access public
     * @param bool $addToDatabase Whether or not the field should be added to the database.
     * @see CC_Record::buildUpdateQuery()
     * @see CC_Record::buildInsertQuery()
     */	

	function setAddToDatabase($addToDatabase)
	{
		$this->addToDatabase = $addToDatabase;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setUpdateFromDatabase
	//-------------------------------------------------------------------

	/**
     * Sets whether or not the field should be updated from its database value when the field is updated by N2O (as opposed to its page value). LAST_MODIFIED is the most popular field that gets updated from the database.
     *
     * @access public
     * @param bool $updateFromDatabase Whether or not the field should be update from the database.
     */	

	function setUpdateFromDatabase($updateFromDatabase)
	{
		$this->updateFromDatabase = $updateFromDatabase;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getUpdateFromDatabase
	//-------------------------------------------------------------------

	/**
     * Gets whether or not the field should be updated from its database value when the field is updated by N2O (as opposed to its page value. LAST_MODIFIED is the most popular field that gets updated from the database.
     *
     * @access public
     * @return bool Whether or not the field should be update from the database.
     */	

	function getUpdateFromDatabase()
	{
		return $this->updateFromDatabase;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: addToDatabase
	//-------------------------------------------------------------------

	/**
     * Gets whether or not the field should be added to the database when the record is inserted or updated.
     *
     * @access public
     * @return bool Whether or not the field should be added to the database.
     * @see CC_Record::buildUpdateQuery()
     * @see CC_Record::buildInsertQuery()
     */	

	function addToDatabase()
	{
		return $this->addToDatabase;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getValue
	//-------------------------------------------------------------------

	/**
     * Gets the value of the field. This method should return a value that is suitable for insertion into a [MySQL] database.
     *
     * @access public
     * @return mixed The field's value.
     * @see setValue()
     */	
	
	function getValue()
	{
		return $this->value;
	}	
	
	
	//-------------------------------------------------------------------
	// METHOD: getEscapedValue
	//-------------------------------------------------------------------

	/**
     * Gets the escaped value of the field, suitable for insertion into a database.
     *
     * @access public
     * @return mixed The field's escaped value.
     */	
	
	function getEscapedValue()
	{
		return addslashes($this->getValue());
	}	
	
	
	//-------------------------------------------------------------------
	// METHOD: hasValue
	//-------------------------------------------------------------------

	/**
     * Gets whether or not the field has a value.
     *
     * @access public
     * @return bool Whether or not the field has a value.
     * @see setValue()
     * @see getValue()
     */	
	
	function hasValue()
	{
		return !(strlen($this->getValue()) == 0);
	}

	
	//-------------------------------------------------------------------
	// METHOD: validate
	//-------------------------------------------------------------------
	
	/**
	 * This method should be overriden in subclasses to validate field input. Subclasses should use setErrorMessage('', CC_FIELD_ERROR_INVALID) to set the validation error message if the user input is found to be invalid.
	 *
	 * @access protected
	 * @return bool Whether or not the field is valid.
	 */

	function validate()
	{
		$this->clearErrorMessage(CC_FIELD_ERROR_INVALID);
		return true;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setInvalidMessage()
	//-------------------------------------------------------------------

	/**
	 * This method sets the validation message in CC_Field
	 *
	 * @access public
	 * @param string $message The invalidity message to set subclasses' validate() method.
	 * @deprecated
	 */
	
	function setInvalidMessage($message)
	{
		trigger_error('setInvalidMessage is a deprecated method', E_USER_WARNING);
		$this->setErrorMessage('');
	}
	

	//-------------------------------------------------------------------
	// METHOD: getInvalidMessage()
	//-------------------------------------------------------------------
	
	/**
	 * This method gets the validation message set in CC_Field subclasses' validate() method
	 *
	 * @access public
	 * @return string The invalidity message. 
	 * @deprecated
	 */

	function getInvalidMessage()
	{
		trigger_error('getInvalidMessage is a deprecated method', E_USER_WARNING);
		return $this->getErrorMessage(CC_FIELD_ERROR_INVALID);
	}

	
	//-------------------------------------------------------------------
	// METHOD: deleteCleanup
	//-------------------------------------------------------------------
	
	/**
     * Subclasses should override this method if any cleanup needs to be done upon their deletion (eg. deleting associated files).
     *
     * @access public
     */	

	function deleteCleanup()
	{
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: cancelCleanup
	//-------------------------------------------------------------------
	
	/**
     * Fields should override this method if any cleanup needs to be done upon cancellation (eg. CC_File_Upload_To_Path_Field is one case).
	 *
     * @access public
     */	

	function cancelCleanup()
	{
	}


	//-------------------------------------------------------------------
	// METHOD: setError
	//-------------------------------------------------------------------
	
	/** 
	  * This function sets whether or not this field has an error
	  *
	  * @access public
	  * @param bool $error Whether or not the field has an error.
	  */
	
	function setError($error = true)
	{
		$this->_error = $error;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: _addError
	//-------------------------------------------------------------------
	
	/** 
	  * This function adds an error to the field
	  *
	  * @access private
	  * @param string $errorMessage the error message to set, resets if nothing is passed
	  * @deprecated
	  */

	function _addError($errorMessage = '')
	{
		trigger_error('_addError() is a deprecated method. Use setErrorMessage($message, CC_FIELD_ERROR_CUSTOM) instead.', E_USER_WARNING);
		$this->setErrorMessage($errorMessage, CC_FIELD_ERROR_CUSTOM);
	}
	

	//-------------------------------------------------------------------
	// METHOD: addMissingError
	//-------------------------------------------------------------------
	
	/** 
	  * @access public
	  * @deprecated
	  */
	
	function addMissingError()
	{  
		trigger_error('addMissingError() is a deprecated method. Use setErrorMessage($message, CC_FIELD_ERROR_MISSING) instead.', E_USER_WARNING);
		$this->setErrorMessage($this->label . ' is required', CC_FIELD_ERROR_MISSING);
	}


	//-------------------------------------------------------------------
	// METHOD: addInvalidError
	//-------------------------------------------------------------------
	
	/** 
	  * @access public
	  * @deprecated
	  *
	  */
	
	function addInvalidError()
	{
		trigger_error('addInvalidError() is a deprecated method. Use setErrorMessage($message, CC_FIELD_ERROR_INVALID) instead.', E_USER_WARNING);
		$this->setErrorMessage($this->label . ' is invalid.', CC_FIELD_ERROR_INVALID);
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setMissingError
	//-------------------------------------------------------------------
	
	/** 
	  * This function sets an error for missing data.
	  *
	  * @access public
	  * @deprecated
	  */
	
	function setMissingError()
	{  
		trigger_error('setMissingError() is a deprecated method. Use setErrorMessage($message, CC_FIELD_ERROR_MISSING) instead.', E_USER_WARNING);
		$this->setErrorMessage($this->label . ' is required', CC_FIELD_ERROR_MISSING);
	}


	//-------------------------------------------------------------------
	// METHOD: setInvalidError
	//-------------------------------------------------------------------
	
	/** 
	  * This function sets an error for invalid data. _errorMessage here comes from the field's validate() handler or other error-checking methods
	  *
	  * @access public
	  * @deprecated
	  */
	
	function setInvalidError()
	{
		trigger_error('setInvalidError() is a deprecated method. Use setErrorMessage($message, CC_FIELD_ERROR_INVALID) instead.', E_USER_WARNING);
		$this->setErrorMessage($this->label . ' is invalid.', CC_FIELD_ERROR_INVALID);
	}


	//-------------------------------------------------------------------
	// METHOD: hasError
	//-------------------------------------------------------------------
	
	/** 
	  * This function returns whether or not this field has an error associated with it.
	  *
	  * @access public
	  * @return bool Whether this field has an error.
	  * @see setErrorMessage()
	  *
	  */
	
	function hasError()
	{
		return $this->_error;
	}
	

	//-------------------------------------------------------------------
	// METHOD: setErrorMask
	//-------------------------------------------------------------------
	
	/** 
	  * This function sets this field's error mask, describing which error messages are to be displayed
	  *
	  * @access public
	  * @param int $mask The mask to set.
	  */

	function setErrorMask($mask = CC_FIELD_ERROR_ALL)
	{
		$this->_errorMask = $mask;
	}
	
	//-------------------------------------------------------------------
	// METHOD: setErrorMessage
	//-------------------------------------------------------------------
	
	/** 
	  * This function sets an error message in the field. The type of error message will depends on the error level passed. Choices are CC_FIELD_ERROR_MISSING, CC_FIELD_ERROR_INVALID or CC_FIELD_ERROR_CUSTOM.
	  *
	  * @access public
	  * @param string $message The error message to add.
	  * @param int $errorlevel The error type being added.
	  */

	function setErrorMessage($message, $errorLevel = CC_FIELD_ERROR_CUSTOM)
	{
		switch ($errorLevel)
		{
			case CC_FIELD_ERROR_MISSING:
			{
				$this->setError();
				$this->_missingError = $message;
			}
			break;
			
			case CC_FIELD_ERROR_INVALID:
			{
				$this->setError();
				$this->_invalidError = $message;
			}
			break;
			
			case CC_FIELD_ERROR_CUSTOM:
			{
				$this->setError();
				$this->_customError = $message;
			}
			break;
		
			default:
			{
				trigger_error('An invalid error type was passed', E_USER_WARNING);
			}
			break;
		}
	}
	
	//-------------------------------------------------------------------
	// METHOD: getErrorMessage
	//-------------------------------------------------------------------
	
	/** 
	  * This function gets an error message based on the error level passed. Choices are CC_FIELD_ERROR_MISSING, CC_FIELD_ERROR_INVALID or CC_FIELD_ERROR_CUSTOM.
	  *
	  * @access public
	  * @param int $errorlevel The error type being added.
	  * @return string The error message of the given level.
	  */

	function getErrorMessage($errorLevel = CC_FIELD_ERROR_CUSTOM)
	{
		if ($this->_errorMask & $errorLevel)
		{
			switch ($errorLevel)
			{
				case CC_FIELD_ERROR_MISSING:
					return $this->_missingError;
				break;
			
				case CC_FIELD_ERROR_INVALID:
					return $this->_invalidError;
				break;
			
				case CC_FIELD_ERROR_CUSTOM:
					return $this->_customError;
				break;
				
				default:
					trigger_error('An invalid error type was passed.', E_USER_WARNING);
					return '';
				break;
			}
		}
		else
		{
			trigger_error('An error message of the given error level was not found.', E_USER_WARNING);
			return '';
		}
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getErrorMessageDisplay
	//-------------------------------------------------------------------
	
	/**
	  * This function gets this field's error message as HTML. The error returned depends on the error level passed.
	  * 
	  * @access public
	  * @param int errorLevel The level of error message to retrieve.
	  * @return string The error message of given level.
	  */

	function getErrorMessageDisplay($errorLevel = CC_FIELD_ERROR_CUSTOM)
	{
		if ($this->hasError())
		{
			if ($this->_errorMask & $errorLevel)
			{
				switch ($errorLevel)
				{
					case CC_FIELD_ERROR_MISSING:
						return '<span class="ccError">' . $this->_missingError . '</span>';
					break;
				
					case CC_FIELD_ERROR_INVALID:
						return '<span class="ccError">' . $this->_invalidError . '</span>';
					break;
				
					case CC_FIELD_ERROR_CUSTOM:
						return '<span class="ccError">' . $this->_customError . '</span>';
					break;
					
					default:
						trigger_error('An invalid error type was passed', E_USER_WARNING);
						return '';
					break;
				}
			}
			else
			{	
				trigger_error('An error message of the given error level was not found.', E_USER_WARNING);
				return '';
			}
		}
	}
	

	//-------------------------------------------------------------------
	// METHOD: clearErrorMessage
	//-------------------------------------------------------------------
	
	/** 
	  * This function clears the field error of the given error level.
	  *
	  * @access public
	  * @param int $errorLevel The level of errors to clear.
	  */
	  
	function clearErrorMessage($errorLevel = CC_FIELD_ERROR_CUSTOM)
	{
		switch ($errorLevel)
		{
			case CC_FIELD_ERROR_MISSING:
				$this->_missingError = '';
				if (($this->_invalidError == '') && ($this->_customError == ''))
				{
					$this->setError(false);
				}
			break;
		
			case CC_FIELD_ERROR_INVALID:
				$this->_invalidError = '';
				if (($this->_missingError == '') && ($this->_customError == ''))
				{
					$this->setError(false);
				}
			break;
		
			case CC_FIELD_ERROR_CUSTOM:
				$this->_customError = '';
				if (($this->_missingError == '') && ($this->_invalidError == ''))
				{
					$this->setError(false);
				}
			break;

			case CC_FIELD_ERROR_ALL:
				$this->clearAllErrors();
			break;
			
			default:
				trigger_error('An invalid error type was passed', E_USER_WARNING);
			break;
		}
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: clearAllErrors
	//-------------------------------------------------------------------
	
	/** 
	  * This function clears all levels of field errors.
	  *
	  * @access public
	  * @see setErrorMessage()
	  */

	function clearAllErrors()
	{
		$this->setError(false);
		$this->_missingError = '';
		$this->_invalidError = '';
		$this->_customError = '';
	}
	

	//-------------------------------------------------------------------
	// METHOD: setRequired
	//-------------------------------------------------------------------
	
	/**
	 * Sets whether or not the field is required.
     *
     * @access public
     * @param bool $required Whether or not the field is required.
     * @see isRequired()
     */	

	function setRequired($required)
	{
		$this->required = $required;
		
		if ($required)
		{
			$this->setLabelStyle('ccRequiredField');
		}
		else
		{
			$this->setLabelStyle('ccNotRequiredField');
		}
	}


	//-------------------------------------------------------------------
	// METHOD: isRequired
	//-------------------------------------------------------------------
	
	/**
	 * Returns whether or not the field is required.
     *
     * @access public
     * @see setRequired()
     */	

	function isRequired()
	{
		return $this->required;
	}


	//-------------------------------------------------------------------
	// METHOD: setRecord
	//-------------------------------------------------------------------

	/**
     * Sets the record that the field belongs to. It is used by CC_Record when creating field objects in the constructor. Subclasses can extend this method if they are built up of more than one field (ie. CC_Date_Field, CC_Time_Field etc...).
     *
	 * @access private
	 * @see getRecord()
	 * @see CC_Record::CC_Record()
     */	

	function setRecord(&$record)
	{
		$this->record = &$record;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getRecord
	//-------------------------------------------------------------------

	/**
     * Gets the record that the field belongs to.
     *
     * @access public
     * @return CC_Record The record that this field belongs to.
     * @see setRecord()
     */	

	function &getRecord()
	{
		return $this->record;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: isInRecord
	//-------------------------------------------------------------------

	/**
     * Returns whether or not this field is part of a record or a standalone field.
     *
     * @access public
     * @return bool Whether or not this field belongs to a record.
     * @see getRecord()
     */	

	function isInRecord()
	{
		return isset($this->record);
	}


	//-------------------------------------------------------------------
	// METHOD: getRecordKey
	//-------------------------------------------------------------------

	/**
     * Gets the record key of the parent record or false if it is a standalone field.
     *
     * @access public
     * @return mixed A string representing the owner record's key or false if it is a standalone field (ie. not part of a record).
     * @see isInRecord()
     */	

	function getRecordKey()
	{
		if ($this->isInRecord())
		{
			return ($this->record->getKeyID($this->record->table, $this->record->id)) . '|';
		}
		else
		{
			//trigger_error('The field ' .  $this->name . ' did not belong to a record.', E_USER_WARNING);
			return false;
		}
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getRequestArrayName()
	//-------------------------------------------------------------------

	/**
     * Gets the name of the field as it appears in the request array.
     *
     * @access public
     * @return string A string representing the field's request array name or false if it is a standalone field (ie. not part of a record).
     * @see isInRecord()
     * @see getRecordKey()
     * @see getName()
     */	

	function getRequestArrayName()
	{
		return $this->getRecordKey() . $this->getName();
	}


	//-------------------------------------------------------------------
	// METHOD: setValidateIfNotRequired()
	//-------------------------------------------------------------------

	/**
     * Sets whether or not the field should be validated even if it is not a required field.
     *
     * @access public
     * @param bool $validate Whether or not the field should be validated even if it isn't required.
     */	

	function setValidateIfNotRequired($validate)
	{
		$this->validateIfNotRequired = $validate;
	}


	//-------------------------------------------------------------------
	// METHOD: setDisabled()
	//-------------------------------------------------------------------

	/**
     * Sets whether or not the field should be disabled. Note that this
     * may not work in all fields (yet).
     *
     * @access public
     * @param bool $disable Whether to disable the field or not (true or false)
     */	

	function setDisabled($disable)
	{
		$this->disabled = $disable;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: isDisabled()
	//-------------------------------------------------------------------

	/**
     * Sets whether or not the field should be disabled. Note that this
     * may not work in all fields (yet).
     *
     * @access public
     * @return bool Whether the field is disabled or not
     */	

	function isDisabled()
	{
		return $this->disabled;
	}


	//-------------------------------------------------------------------
	// METHOD: setId()
	//-------------------------------------------------------------------

	/**
     * Sets the id entity for the field
     *
     * @access public
     * @param bool $disable Whether to disable the field or not (true or false)
     */	

	function setId($id)
	{
		$this->id = $id;
	}


	//-------------------------------------------------------------------
	// METHOD: register
	//-------------------------------------------------------------------

	/**
	 * This is a callback method that gets called by the window when the
	 * component is registered. It's up to the component to decide which
	 * registerXXX() method it should call on the window. Should your
	 * custom component consist of multiple components, you may need to
	 * make multiple calls.
	 *
	 * @access public
	 */

	function register(&$window)
	{
		$window->registerField($this);
	}
}
?>