<?php
// $Id: CC_Button.php,v 1.40 2004/12/07 20:05:43 mike Exp $
//=======================================================================
// CLASS: CC_Button
//=======================================================================

/**
 * This class defines all buttons in N2O.
 *
 * @package CC_Buttons
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

class CC_Button extends CC_Component
{
	/**
     * The button's text label.
     *
     * @var string $label
     * @see setLabel()
     * @access private
     */

	var $label;


	/**
     * An array of all the button's registered handlers.
     *
     * @var array $handlers
     * @see registerHandler()
     * @access private
     */

	var $handlers = array();


	/**
     * The button's unique id.
     *
     * @var string $id
     * @access private
     */

	var $id;							// the id of the button


	/**
     * The window id of the window containing the button.
     *
     * @var string $windowId
     * @access private
     */
     
	var $windowId;
	
	
	/**
     * Defines whether or not fields on the same window will be updated when the button is clicked
     *
     * @var bool $fieldUpdater
     * @access private
     * @see setFieldUpdater()
     * @see isFieldUpdater()
     */
     
    var $fieldUpdater = true;
    
	
	/**
     * Defines whether or not fields on the same window will be validated when the button is clicked
     *
     * @var bool $validateOnClick
     * @access private
     * @see setValidateOnClick()
     * @see validateOnClick()
     */
    
    var $validateOnClick = true;
    
    
	/**
     * Defines whether or not the button is clickable or not (ie. active or inactive)
     *
     * @var bool $clickable
     * @see setClickable()
     * @see getClickable()
     * @access private
     */
     
    var $clickable = true; 				// if the button is clickable, this should be true
	
	
	/**
     * The screen from which the button was clicked.
     *
     * @var string $action
     * @todo Are we using this krunker any more. Doesn't look like it, nelly!
     * @todo We are indeed still using this. Don't delete it!
     * @access private
     */
	
	var $action;


	/**
     * An array of field names to update when the button is clicked. If this array is empty, it is assumed that all fields are to be updated (ie. only if 'fieldUpdater' is true).
	 *
     * @var array $fieldsToValidateArray
     * @access private
     */

	var $fieldsToValidateArray = array();
		
	
	/**
	* An optional path that will be used in the redirect. Useful to get
	* around caching issues, particularly with Windows Internet Exploder.
	*
	* @access private
	*/
	
	var $_redirectPath = '';
	

	/**
     * The "tabindex" for this button. This will allow us to control which button is submitted first when someone hits enter in a field. By default, buttons will start at 100, but making a button default by using CC_Window::setDefaultButton() will make it lower than the rest.
	 *
     * @var int $_tabIndex
     * @access private
     */

	var $_tabIndex;


	/**
     * The javascript for the onClick handler.
	 *
     * @var string $_onClick
     * @access private
     */

	var $_onClick;
		
	
	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Button
	//-------------------------------------------------------------------

	/**
	 * The CC_Button constructor accepts a value for the button's label and constructs the button's unique id. 
	 *
	 * @access public
	 * @param string $label The button's label text.
	 * @param string $id The button's id.
	 * @todo Do we need the application object here? The code is commented out. Maybe we should delete it.
	 */

	function CC_Button($label, $id = null)
	{
		$this->label = $label;
		
		if ($id == null)
		{
			$this->id = ereg_replace('[\'|"|&|#]', '!', substr($label, 0, 1)) . ereg_replace('[\'|"|&|#]', '!', substr($label, strlen($label) - 1, 1)) . rand();
		}
		else
		{
			$this->id = $id;
		}
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setFieldsToValidate()
	//-------------------------------------------------------------------

	/**
	 * You can pass one or more CC_Field objects into this method and these fields alone will be updated and validated when the button is clicked.
	 *
	 * @access public
	 * @param CC_Field $field1,... A list of field objects to update/validate.
	 */

	function setFieldsToValidate(&$field1)
	{
		$argumentCount = func_num_args();
		
		for ($i = 0; $i < $argumentCount; $i++)
		{
			$field = &func_get_arg($i);
			
			if (is_object($field))
			{
				$this->fieldsToValidateArray[$field->getName()] = true;
			}
			else
			{
				trigger_error('setFieldsToValidate() was passed a null field.' , E_USER_WARNING);
			}
			
			unset($field);
		}
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setRecordsToValidate()
	//-------------------------------------------------------------------

	/**
	 * You can pass one or more CC_Record objects into this method and the fields of these records alone will be updated and validated when the button is clicked.
	 *
	 * @access public
	 * @param CC_Record $field1,... A list of field objects to update/validate.
	 */

	function setRecordsToValidate(&$record1)
	{
		$argumentCount = func_num_args();
		
		for ($i = 0; $i < $argumentCount; $i++)
		{
			$record = &func_get_arg($i);
			
			$keys = array_keys($record->fields);
			$size = sizeof($keys);
			
			for ($j = 0; $j < $size; $j++)
			{
				$this->fieldsToValidateArray[$record->fields[$keys[$j]]->getName()] = true;
			}
			
			unset($record, $keys, $size, $j);
		}
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setFieldsToUpdate()
	//-------------------------------------------------------------------

	/**
	 * @access public
	 * @param CC_Field $field1,... A list of field objects to update/validate.
 	 * @deprecated
	 * @see setFieldsToValidate()
	 *
	 */

	function setFieldsToUpdate(&$field1)
	{
		trigger_error('setFieldsToUpdate() is deprecated. Use setFieldsToValidate() instead.', E_USER_WARNING);
		
		$argumentCount = func_num_args();
		
		for ($i = 0; $i < $argumentCount; $i++)
		{
			$field = &func_get_arg($i);
			
			if (is_object($field))
			{
				$this->fieldsToValidateArray[$field->getName()] = true;
			}
			else
			{
				trigger_error('setFieldsToValidate() was passed a null field.' , E_USER_WARNING);
			}
			
			unset($field);
		}
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: registerHandler()
	//-------------------------------------------------------------------

	/**
	 * This method registers CC_Action_Handlers with the button. The CC_Action_Handler subclass' process() method is called when this button is clicked.
	 *
	 * @access public
	 * @param CC_Action_Handler $aHandler The CC_Action_Handler subclass to register with the button.
	 * @see CC_Action_Handler
	 */

	function registerHandler(&$aHandler)
	{
		$this->handlers[] = &$aHandler;
	}


	//-------------------------------------------------------------------
	// METHOD: clearHandlers
	//-------------------------------------------------------------------

	/**
	 * Call this method to clear the button's previously registered handlers.
	 *
	 * @access public
	 * @see registerHandler()
	 */

	function clearHandlers()
	{
		unset($this->handlers);
		$this->handlers = array();
	}


	//-------------------------------------------------------------------
	// METHOD: click
	//-------------------------------------------------------------------

	/**
	 * Call this method to process a button's handlers. This effectively accomplishes the same thing as when a user actually clicks the button but is done here programmatically.
	 *
	 * @access public
	 * @param bool $multipleClick Whether or not we are dealing with an accidental multiple click (for use exclusively in CC_index).
	 * @todo Decide whether or not this method should be privatized like BC Ferries. Nice new logo, Gordo ya criminal!
	 */

	function click($multipleClick = false)
	{
		$size = sizeof($this->handlers);

		for ($j = 0; $j < $size; $j++)
		{
			// If any handlers returns false, don't execute any more
			if ($this->handlers[$j]->process($multipleClick, $this) === false)
			{
				unset($size);
				return;
			}
		}

		unset($size);
	}


	//-------------------------------------------------------------------
	// METHOD: getHTML()
	//-------------------------------------------------------------------

	/**
	 * This method generates HTML for displaying the button.
	 *
	 * @access public
	 */

	function getHTML()
	{
		$button = '<input type="submit" name="_BCC_' . $this->id . '" value="' . $this->label . '" class="' . $this->style . '" tabindex="' . $this->_tabIndex .'"';

		if ($this->clickable)
		{
			return $button . (isset($this->_onClick) ? ' onClick="' . $this->_onClick . '"' : '' ) . '>';
		}
		else
		{
			return $button . ' disabled>';
		}
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getId()
	//-------------------------------------------------------------------

	/**
	 * This method returns the button's unique id.
	 *
	 * @access public
	 * @return string The button's unique id.
	 */

	function getId()
	{
		return $this->id;
	}


	//-------------------------------------------------------------------
	// METHOD: setFieldUpdater()
	//-------------------------------------------------------------------
	
	/** This method sets whether or not you want your button to cause the fields on the window to be updated with the latest content. A cancel button, for example, would set this to be false.
	  *
	  * @access public
	  * @param bool $update Whether or not the button should update fields.
	  */

	function setFieldUpdater($update)
	{
		return $this->fieldUpdater = $update;
	}


	//-------------------------------------------------------------------
	// METHOD: setLabel()
	//-------------------------------------------------------------------
	
	/** This method sets the label text for the button.
	  *
	  * @access public
	  * @param string $label The button text label to set.
	  */

	function setLabel($label)
	{
		$this->label = $label;
	}


	//-------------------------------------------------------------------
	// METHOD: getLabel()
	//-------------------------------------------------------------------
	
	/** This method gets the label for the button.
	  *
	  * @access public
	  * @return string The button's label text.
	  */

	function getLabel()
	{
		return $this->label;
	}


	//-------------------------------------------------------------------
	// METHOD: isFieldUpdater()
	//-------------------------------------------------------------------
	
	/** This method gets whether or not the button is a field updater.
	  *
	  * @access public
	  * @return bool Whether or not the button is a field updater.
	  * @see setFieldUpdater()
	  */

	function isFieldUpdater()
	{
		return $this->fieldUpdater;
	}


	//-------------------------------------------------------------------
	// METHOD: setValidateOnClick()
	//-------------------------------------------------------------------
	
	/** This method sets whether or not you want your button to cause the fields on the window to be validated on a click. Buttons which take the user away from a screen temporarily would set this to be false.
	  *
	  * @access public
	  * @param bool $validate Whether or not this button should validate fields (and perhpas, depending on the situation), stop execution to display an error if some fields are invalid.
	  * @see validateOnClick()
	  */

	function setValidateOnClick($validate)
	{
		$this->validateOnClick = $validate;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setClickable()
	//-------------------------------------------------------------------
	
	/** This method sets whether or not you want your button to be clickable. All buttons are clickable by default.
	  *
	  * @access public
	  * @param bool $clickable Whether or not the button is clickable.
	  */

	function setClickable($clickable)
	{
		$this->clickable = $clickable;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getClickable()
	//-------------------------------------------------------------------
	
	/** This method gets whether or not the button is clickable.
	  *
	  * @access public
	  * @return bool Whether or not the button is clickable.
	  * @see setClickable()
	  */
	  
	function getClickable()
	{
		return $this->clickable;
	}


	//-------------------------------------------------------------------
	// METHOD: validateOnClick()
	//-------------------------------------------------------------------
	
	/** This method gets whether or not the button should validate fields when clicked.
	  *
	  * @access public
	  * @return bool Whether or not the button validates fields.
	  * @see setValidateOnClick()
	  */

	function validateOnClick()
	{
		return $this->validateOnClick;
	}


	//-------------------------------------------------------------------
	// METHOD: setPath()
	//-------------------------------------------------------------------

	/**
	 * An optional path that will be used in the redirect. Useful to get
 	 * around caching issues, particularly with Windows Internet Exploder. 
	 *
	 * The path gets appended in CC_Index to the Location: header. For
	 * things to work properly, you need to do some funky Apache stuff
	 * like setting "ErrorDocument 404 /" (or whatever the path to your
	 * app is) so that any URL with take you into the app.
	 *
	 * @access public
	 * @param string $path The screen to go to for the redirect.
	 */

	function setPath($path)
	{
		$this->_redirectPath = $path;
	}


	//-------------------------------------------------------------------
	// METHOD: getPath()
	//-------------------------------------------------------------------

	/**
	 * Gets the optional path that will be used in the redirect. Useful to get
	 * around caching issues, particularly with Windows Internet Exploder.
	 *
	 * @access public
	 * @see setPath()
	 */

	function getPath()
	{
		return $this->_redirectPath;
	}


	//-------------------------------------------------------------------
	// METHOD: setOnClick()
	//-------------------------------------------------------------------

	/**
	 * Set an action for the javascript onClick handler. 
	 *
	 * @access public
	 * @param string $onClick The javascript to execute.
	 */

	function setOnClick($onClick)
	{
		$this->_onClick = $onClick;
	}


	//-------------------------------------------------------------------
	// METHOD: isClickInRequest()
	//-------------------------------------------------------------------

	/**
	 * Returns boolean that indicates whether this button was clicked in the current request ($_GET or $_POST). 
	 *
	 * @access public
	 */

	function isClickInRequest()
	{
		return isset($_REQUEST['_BCC_' . $this->id]);
	}


	//-------------------------------------------------------------------
	// METHOD: isGetRequest()
	//-------------------------------------------------------------------

	/**
	 * Returns boolean that indicates whether this button causes a GET request. 
	 *
	 * @access public
	 */

	function isGetRequest()
	{
		return false;
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
		$window->registerButton($this);
	}
}

?>