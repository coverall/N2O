<?php
// $Id: CC_Application.php,v 1.83 2005/03/06 00:46:02 patrick Exp $
//=======================================================================
// CLASS: CC_Application
//=======================================================================

/**
 * This is the main application class for all N20 applications. This class instantiates all manager classes (database, errors, fields, relationships etc...). Applications may derive subclasses if they need to do any special handling.
 *
 * @package N2O
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */


class CC_Application
{
	/**
     * The current action. The action is a string representing the path to the PHP file describing the screen display.
     *
     * @var string $action
     * @see CC_Application::setAction()
     * @access private
     */

	var $action;


	/**
     * This is the last action that occurred immediately previous to the current action.
     *
     * @var string $lastAction
     * @see CC_Application::setLastAction()
     * @access private
     */

	var $lastAction;


	/**
     * The current action key. This key is used so that 
     *
     * @var string $action
     * @see CC_Application::setAction()
     * @access private
     */

	var $actionKey;


	/**
     * The screen to go to when logging out of the application.
     *
     * @var string $logoutDestination
     * @access private
     */

	var $logoutDestination = BASE_URL;

	
	/**
     * An array of name-value pairs to keep data between subsequent window accesses.
     *
     * @var array $arguments
     * @access private
     */
     
	var $arguments = array(); 			
	

	/**
     * An array of name-object pairs to keep data between subsequent window accesses.
     *
     * @var array $objects
     * @access private
     */
     
	var $objects = array(); 
	
	
	/**
     * The CC_Database object.
     *
     * @var CC_Database $db
     * @see CC_Database
     * @access private
     */

	var $db = null;


	/**
     * The CC_User object describing the user using the system.
     *
     * @var CC_User $_user
     * @see CC_User
     * @access private
     */

	var $_user;


	/**
     * The CC_FieldManager object.
     *
     * @var CC_FieldManager $fieldManager
     * @see CC_FieldManager
     * @access private
     */

	var $fieldManager;


	/**
     * The CC_RelationshipManager object.
     *
     * @var CC_RelationshipManager $relationshipManager
     * @see CC_RelationshipManager
     * @access private
     */

	var $relationshipManager;


	/**
     * The CC_ErrorManager object.
     *
     * @var CC_ErrorManager $errorManager
     * @see CC_ErrorManager
     * @see CC_Error
     * @access private
     */

	var $errorManager;

	
	/**
     * The current CC_Window object which is also it's key in the windows array.
     *
     * @var CC_Window $currentWindowName
     * @access private
     */

	var $currentWindowName;
		
	
	/**
     * The array of the application's CC_Window objects.
     *
     * @var array $windows
     * @access private
     */

	var $windows = array();
	

	/**
     * The ID of the last clicked button.
     *
     * @var string $lastButtonClick
     * @access private
     */

	var $lastButtonClick;


	/**
     * The timestamp when the last button was clicked.
     *
     * @var date $lastButtonClickTime
     * @access private
     */

	var $lastButtonClickTime;


	/**
     * The number of seconds after which a double click expires.
     *
     * @var int $buttonExpiryTime
     * @see CC_Application::isMultipleClick()
     * @access private
     */

	var $buttonExpiryTime = 5;			
		
		
	/**
     * Does the application do a GET or POST (the default value is POST).
     *
     * @var bool $isGet
     * @access private
     */

	var $isGet = false;					
	
	
	/**
     * Indicates whether or not the user is "logged in". 
     *
     * @var bool $_loggedIn
     * @access private
     * @see isLoggedIn() and setLoggedIn()
     */

	var $_loggedIn = false;
	
	
	/**
     * The "language" for this field.
	 *
     * @var int $_language
     * @access private
     */

	var $_language = '';
	
		
	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Application
	//-------------------------------------------------------------------

	/**
	 * The CC_Application constructor instantiates all managers pertinent to the application. The database manager values are taken from CC_Database_Config.php. All subclasses must call its parent's constructor.
	 *
	 * @access public
	 * @todo Initialize CC_RelationshipManager only if it is needed and the CC_RELATIONSHIPS table exists in the database
	 */

	function CC_Application()
	{
		global $noDatabase, $noCCFieldManagerDatabase, $databaseConfigPath;
		
		if (!$noDatabase)
		{
			// initialize the database object
			
			if (!isset($databaseConfigPath))
			{
				include(APPLICATION_PATH . '/CC_Database_Config.php');
			}
			else
			{
				include($databaseConfigPath);
			}
			
			$this->db = &new CC_Database($DATABASE_HOST, $DATABASE_NAME, $DATABASE_USER, $DATABASE_PASSWORD, $DATABASE_ENCODE_PASSWORD, (isset($DATABASE_TYPE) ? $DATABASE_TYPE : DB_MYSQL));

			// initialize the relationship manager
			//
			// (!) we should defer this until it's needed!
			//
			if (!isset($GLOBALS['noRelationshipManager']) || $GLOBALS['noRelationshipManager'] == false)
			{
				$this->relationshipManager = &new CC_RelationshipManager($this->db, $noCCFieldManagerDatabase);
			}
		}

		
		// initialize the field manager
		$this->fieldManager = &new CC_FieldManager($this->db, $noCCFieldManagerDatabase);

		// initialize the error manager
		$this->errorManager = &new CC_ErrorManager();
	}
	
	
	//---------------------------------------------------------------------
	// METHOD: setUser
	//---------------------------------------------------------------------
	
	/** This method sets the application's references to the user object.
	 * Use of the "user" metaphor is optional in N2O.
	 *
	 * @access public
	 * @param CC_User $aUser The CC_User object to set
	 * @see CC_User
	 */
	 
	function setUser(&$aUser)
	{
		if (is_a($aUser, 'CC_User'))
		{
			$this->_user = &$aUser;
		}
		else
		{
			trigger_error("CC_Application::setUser() did not receive a CC_User object. All user objects must extend the CC_User object.");
		}
	}
	
	
	//---------------------------------------------------------------------
	// METHOD: getUser
	//---------------------------------------------------------------------
	
	/** This method gets the application's references to the user object.
	  * Use of the "user" metaphor is optional in N2O.
	  *
 	  * @access public
 	  * @return CC_User A reference to the application's CC_User object
	  * @see CC_User
	  */
	
	function &getUser()
	{
		return $this->_user;
	}


	//---------------------------------------------------------------------
	// METHOD: registerWindow
	//---------------------------------------------------------------------
	
	/** This method registers a window in the application's $windows array by id
	 *
	 * @access public
	 * @param CC_Window $window The CC_Window object to register with the application
	 */

	function registerWindow(&$window, $key = null)
	{
		if ($key == null)
		{
			$this->windows[$window->id] = &$window;
		}
		else
		{
			if (isset($this->windows[$window->id]))
			{
				if (!is_array($this->windows[$window->id]))
				{
					$this->windows[$window->id] = array();
				}
			}
			else
			{
				$this->windows[$window->id] = array();
			}

			$this->windows[$window->id][$key] = &$window;
		}
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: unregisterWindow
	//-------------------------------------------------------------------
	
	/** This method unregisters the window with the passed id from the application
	 *
	 * @access public
	 * @param string $windowId
	 */

	function unregisterWindow($windowId)
	{
		unset($this->windows[$windowId]);
	}

	
	//-------------------------------------------------------------------
	// METHOD: unregisterAllWindows
	//-------------------------------------------------------------------
	
	/** This method unregisters all the windows from the application.
	 *
	 * @access public
	 */

	function unregisterAllWindows()
	{
		unset($this->windows);
		
		$this->windows = array();
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: unregisterCurrentWindow
	//-------------------------------------------------------------------
	
	/** This method unregisters the current window from the application
	 *
	 * @access public
	 */

	function unregisterCurrentWindow()
	{
		$this->unregisterWindow($this->getAction());
	}
	

	//-------------------------------------------------------------------
	// METHOD: setAction
	//-------------------------------------------------------------------

	/**
	 * This method sets the next action (ie. screen) the application should go to. 
	 *
	 * @access public
	 * @param string $action The action to go to.
	 */

	function setAction($action, $actionKey = '')
	{
		/**
		 * Sometimes hitting reload in the browser reposts information
		 * that shouldn't be there.
		 */

		if ($action != $this->action)
		{
			$this->lastAction = $this->action;
			
			$this->action = $this->setActionArguments($action);
		}
		
		$this->lastActionKey = $this->actionKey;
		$this->actionKey = $actionKey;
		
		// output debug info if necessary
		if (DEBUG)
		{
			if (!file_exists($this->action . '.php'))
			{
				trigger_error("$this->action.php doesn't exist.", E_USER_WARNING);
			}
		}
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getAction
	//-------------------------------------------------------------------

	/**
	 * This method gets the currently set action (ie. screen).
	 *
	 * @access public
	 * @return string The currently set action.
	 */

	function getAction()
	{	
		return $this->action;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getActionKey
	//-------------------------------------------------------------------

	/**
	 * This method gets the currently set action (ie. screen).
	 *
	 * @access public
	 * @return string The currently set action.
	 */

	function getActionKey()
	{	
		return $this->actionKey;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getActionURL
	//-------------------------------------------------------------------

	/**
	 * This method gets the URL of currently set action (ie. screen) minus any query string parameters. Used exclusively in CC_Index.
	 *
	 * @access private
	 * @return string The currently set action without the query string.
	 * @see CC_Index
	 */

	function getActionURL()
	{	
		if (!strstr($this->action, '?'))
		{
		 	return $this->action;
		}
		else
		{
			return substr($this->action, 0, strpos($this->action, '?'));
		}
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setActionArguments
	//-------------------------------------------------------------------

	/**
	 * This method takes the query string name/value pairs of the passed action and adds them as arguments to the application's arguments array. It returns the $action minus the query string much like the return value of getActionURL();
	 *
	 * @access private
	 * @param string $action The action to parse.
	 * @return string The currently set action without the query string.
	 * @see CC_Application::setArgument()
	 */

	function setActionArguments($action)
	{	
		if (strstr($action, '?'))
		{
			$pairArray = array();
		
			$rpos = strpos($action, '?');
			$start = $rpos + 1;
			$length = strlen($action) - $rpos;
			
			$nameValueRequestString = substr($action, $start, $length);
		
			$pairArray = explode('&', $nameValueRequestString);
	
			$size = sizeof($pairArray);
	
			for ($i=0 ; $i < $size; $i++)
			{
				$nameValueArray = explode('=', $pairArray[$i]);
				
				$this->setArgument($nameValueArray[0], $nameValueArray[1]);
				
				unset($nameValueArray);
			}
			
			unset($size);
			
			return substr($action, 0, $start - 1);
		}
		else
		{
			return $action;
		}
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setCurrentWindowName
	//-------------------------------------------------------------------

	/**
	 * This method sets the name of the current window. 
	 *
	 * @access private
	 * @param string $windowName The name of the window.
	 * @todo verify that this method is even used anywhere, doesn't appear so
	 */
	
	function setCurrentWindowName($windowName)
	{	
		$this->currentWindowName = $windowName;
	}

	//-------------------------------------------------------------------
	// METHOD: jumpTo
	//-------------------------------------------------------------------

	/**
	 * This method should be overriden by sublasses to process calls to the application that jump to certain actions bypassing the application's start_point. WARNING!!! - You may need to do some checking to see if a user is logged in before allowing any access to actions. The URL to a jump action would look like http://<application path>/?jmp=jmpAction&name1=value1&name2=value2 where the name/value pairs can be used to pass additional parameters to the method.
	 *
	 * @access public
	 * @param string $jmpAction The name of the window.
	 * {inline @internal{@see CC_Index}}
	 */

	function jumpTo($jmpAction)
	{	
	/**
	 * @example
		//override this to process jumpTo requests
		if ($jmpAction == "ENTER_FUNCTION_NAME_HERE")
		{
			// get the parameters from the URL
			$id = $_REQUEST['id'];
			$actionToJumpTo = ['jmpAction'];
			//...
		}
	*/
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getCurrentWindow
	//-------------------------------------------------------------------

	/**
	 * This method gets a reference to the current window.
	 *
	 * @access public
	 * @return CC_Window A reference to the current window object.
	 */

	function &getCurrentWindow()
	{	
		return $this->getWindow($this->getAction(), $this->actionKey);
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getWindow
	//-------------------------------------------------------------------

	/**
	 * This method gets a reference to the window with the passed name (or id).
	 *
	 * @access public
	 * @param string $windowName The name or id of the window to retrieve.
	 * @return CC_Window A reference to a window object.
	 */

	function &getWindow($windowName, $actionKey = '')
	{
		if ($actionKey)
		{
			if (!isset($this->windows[$windowName]))
			{
				trigger_error('CC_Application->getWindow(): ' . $windowName . ' not found.', E_USER_WARNING);
				return false;
			}
			else
			{
				return $this->windows[$windowName][$actionKey];
			}
		}
		else
		{
			return $this->windows[$windowName];
		}
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getLastAction
	//-------------------------------------------------------------------

	/**
	 * This method gets the last action (ie. screen previous to the current one).
	 *
	 * @access public
	 * @return string The previously set action.
	 * @see setLastAction()
	 */

	function getLastAction()
	{	
		return $this->lastAction;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setLastAction
	//-------------------------------------------------------------------

	/**
	 * This method sets the last action (ie. screen) the application accessed. 
	 *
	 * @access private
	 * @param string $action The action to go to.
	 * @see getLastAction()
	 */

	function setLastAction()
	{	
		$this->lastAction = $lastAction;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setArgument
	//-------------------------------------------------------------------

	/**
	 * This method sets an argument with the application which can be accessed anywhere throughout the application. 
	 *
	 * @access public
	 * @param string $name The name of the argument.
	 * @param mixed $value The value of the argument. Use setObject for objects.
	 * @see getArgument()
	 * @see popArgument()
	 */

	function setArgument($name, $value)
	{	
		if (is_object($value))
		{
			trigger_error('An object was passed to setArgument(). Use setObject() instead.', E_USER_WARNING);
		}
		
		$this->arguments[$name] = $value;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setObject
	//-------------------------------------------------------------------

	/**
     * This method stores objects in the application for access.
     *
	 * @access public
	 * @param string $key The name by which to indentify the object for later access.
	 * @param mixed $aObject The object to register.
	 * @see getObject()
	 */

	function setObject($key, &$aObject)
	{
		$this->objects[$key] = &$aObject;
	}


	//-------------------------------------------------------------------
	// METHOD: getObject()
	//-------------------------------------------------------------------

	/**
	 * This method retrieves an object from the application's objects array. 
     *
	 * @access public
	 * @param string $key The key of the object to retrieve.
	 * @return mixed The object of the given key.
	 * @see setObject()
	 */

	function &getObject($key)
	{
		return $this->objects[$key];
	}


	//-------------------------------------------------------------------
	// METHOD: popArgument
	//-------------------------------------------------------------------

	/**
	 * This method returns an argument of a given name then removes it from the arguments array. 
	 *
	 * @access public
	 * @param string $name The name of the argument.
	 * @return mixed The value of the named argument.
	 */
	
	function popArgument($name)
	{
		$argument = $this->getArgument($name);
		$this->clearArgument($name);
		
		return $argument;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: clearArgument
	//-------------------------------------------------------------------
	
	/**
	 * This method removes an argument of a given name from the application. 
	 *
	 * @access public
	 * @param string $name The name of the argument to remove.
	 * @see getArgument()
	 * @see popArgument()
	 * @see setArgument()
	 */

	function clearArgument($name)
	{	
		unset($this->arguments[$name]);
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getArgument
	//-------------------------------------------------------------------

	/**
	 * This method gets an argument of a given name.
	 *
	 * @access public
	 * @param string $name The name of the argument to get
	 * @see setArgument()
	 */

	function &getArgument($name)
	{
		if (!isset($name) || $name == NULL)
		{
			trigger_error('CC_Application::getArgument() was passed an unset or NULL object.');
		}
		
		if (array_key_exists($name, $this->arguments))
		{
			return $this->arguments[$name];
		}
		else
		{	
			trigger_error('The following argument doesn\'t exist: ' . $name . '. If you don\'t want this error message, use CC_Application::argumentExists() before calling this method.');
		}
	}
	

	//-------------------------------------------------------------------
	// METHOD: argumentExists
	//-------------------------------------------------------------------

	/**
	 * This method checks to see if an argument of a given name exists.
	 *
	 * @access public
	 * @param string $name The name of the argument to check
	 * @see getArgument()
	 * @see popArgument()
	 * @see setArgument()
	 *
	 * @deprecated Use hasArgument() instead.
	 */

	function argumentExists($name)
	{	
		return $this->hasArgument($name);
	}


	//-------------------------------------------------------------------
	// METHOD: hasArgument
	//-------------------------------------------------------------------

	/**
	 * This method checks to see if an argument of a given name exists.
	 *
	 * @access public
	 * @param string $name The name of the argument to check
	 * @see getArgument()
	 * @see popArgument()
	 * @see setArgument()
	 */

	function hasArgument($name)
	{	
		return array_key_exists($name . '', $this->arguments);
	}


	//-------------------------------------------------------------------
	// METHOD: objectExists
	//-------------------------------------------------------------------

	/**
	 * This method checks to see if an object of a given name exists.
	 *
	 * @access public
	 * @param string $name The name of the object to check
	 * @see setObject()
	 * @see getObject()
	 * @see hasObject()
	 *
	 * @deprecated Use hasObject() instead.
	 */

	function objectExists($name)
	{	
		return $this->hasObject($name);
	}


	//-------------------------------------------------------------------
	// METHOD: hasObject
	//-------------------------------------------------------------------

	/**
	 * This method checks to see if an object of a given name exists.
	 *
	 * @access public
	 * @param string $name The name of the object to check
	 * @see setObject()
	 * @see getObject()
	 */

	function hasObject($name)
	{	
		return array_key_exists($name . '', $this->objects);
	}


	//-------------------------------------------------------------------
	// METHOD: isWindowRegistered
	//-------------------------------------------------------------------

	/**
	 * This method checks to see if a window of a given name is registered with the application. 
	 *
	 * @access public
	 * @param string $windowName The name or id of the window to check.
	 * @return bool Whether or not the window is registered.
	 */

	function isWindowRegistered($windowName, $actionKey = '')
	{	
		if (!$actionKey)
		{
			return array_key_exists($windowName, $this->windows);
		}
		else
		{
			return (array_key_exists($windowName, $this->windows) && is_array($this->windows[$windowName]) && isset($this->windows[$windowName][$actionKey]));
		}
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: isCurrentWindowRegistered
	//-------------------------------------------------------------------

	/**
	 * This method checks to see if the current window is registered with the application. 
	 *
	 * @access public
	 * @return bool Whether or not the current window is registered.
	 * @see registerWindow()
	 */

	function isCurrentWindowRegistered()
	{	
		return $this->isWindowRegistered($this->getAction(), $this->actionKey);
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: logout
	//-------------------------------------------------------------------

	/**
	 * This method logs a user out of the application, resets their application cookie, destroys their session and presents then with the $logoutDestination screen. 
	 *
	 * @access public
	 * @see setLogoutDestination()
	 */

	function logout()
	{
		unset($_SESSION['application']);

		setCookie(session_name(), '', 0, BASE_URL);
		
		//session_unset();
		session_destroy();
		
		header('Location: ' . $this->logoutDestination);
		exit();
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setLogoutDestination
	//-------------------------------------------------------------------

	/**
	 * This method sets the action to go to upon logging out. It basically sets the $logoutDestination variable. 
	 *
	 * @access public
	 * @see CC_Application::$logoutDestination
	 */

	function setLogoutDestination($logoutDestination)
	{
		$this->logoutDestination = $logoutDestination;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: registerButtonClick
	//-------------------------------------------------------------------

	/**
	 * This method registers the button click in CC_Index so we can keep track of whether or not the button was accidentally clicked multiple times.
	 *
	 * @access private
	 * @param string $buttonId
	 * @see CC_Index
	 */

	function registerButtonClick($buttonId)
	{	
		$this->lastButtonClick = $buttonId; 
		$this->lastButtonClickTime = time(); 
	}


	//-------------------------------------------------------------------
	// METHOD: isButtonClickRegistered
	//-------------------------------------------------------------------

	/**
	 * This method checks to see if a button with the given id is already registered (ie. has already been clicked.)
	 *
	 * @access private
	 * @param string $buttonClick
	 * @return bool Whether or nor the button id passed is registered (ie. if the button was double clicked).
	 * @todo Check if this method is being called anywhere.
	 */

	function isButtonClickRegistered($buttonClick)
	{
		return ($this->lastButtonClick == $buttonClick);
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: isMultipleClick
	//-------------------------------------------------------------------

	/**
	 * This method checks to see if a button with the given id has already been clicked within the button click expiry time.
	 *
	 * @access private
	 * @param string $buttonClick
	 * @return bool Whether or not the button has already been clicked within the button click expiry time.
	 * @see $buttonExpiryTime
	 * @see $lastButtonClickTime
	 */

	function isMultipleClick($buttonClick)
	{
		if ($this->lastButtonClick == $buttonClick)
		{
			return ((time() - $this->lastButtonClickTime) < $this->buttonExpiryTime);
		}
		
		return false;
	}
	

	//-------------------------------------------------------------------
	// METHOD: setButtonExpiryTime
	//-------------------------------------------------------------------

	/**
	 * This method sets the button expiry time after which a multiple click is okay.
	 *
	 * @access private
	 * @param int $buttonExpiryTime
	 * @see $buttonExpiryTime
	 */

	function setButtonExpiryTime($buttonExpiryTime)
	{
		if ((int)$buttonExpiryTime > 0)
		{
			$this->buttonExpiryTime = $buttonExpiryTime;
		}
	}
	

	//-------------------------------------------------------------------
	// METHOD: getLastButtonClick
	//-------------------------------------------------------------------

	/**
	 * This method gets the timestamp of the last button click. Used for calculating if the button has expired
	 *
	 * @access private
	 * @return date The time the last button was clicked.
	 * @see $lastButtonClickTime
	 */

	function getLastButtonClick()
	{
		return $this->lastButtonClick;
	}
	

	//-------------------------------------------------------------------
	// METHOD: cc_die
	//-------------------------------------------------------------------

	/**
	  * This method halts application execution when something fatal occurs, like not being able to return a summary, record, or field. If you would like to handle this in your own way, override it in your application's subclass. The passed error message is displayed in the window with an accompanying logout button.
	  *
	  * @access public 
	  * @param string $message The error message to display.
	  * @todo Do we need this method any more now that we have trigger_error?
	  */

	function cc_die($message)
	{
		$logoutButton = new CC_Logout_Button('Reset Application');
		
		$window = &$this->getCurrentWindow();

		if (!$window)
		{
			$window = &new CC_Window();
			$this->registerWindow($window);
		}

		$window->registerComponent($logoutButton);

		die($message . ' ' . $logoutButton->getHTML());
	}


	//-------------------------------------------------------------------
	// METHOD: isGet
	//-------------------------------------------------------------------
	
	/**
	  * This method returns whether or not the application uses the GET protocol to transmit field data.
	  *
	  * @access public 
	  * @return bool If we are using the GET method.
	  */

	function isGet()
	{
		return $this->isGet;
	}


	//-------------------------------------------------------------------
	// METHOD: setGet
	//-------------------------------------------------------------------
	
	/**
	  * This method sets whether or not the application uses HTTP's GET protocol to transmit field data.
	  *
	  * @access public 
	  * @param bool $get Whether or not should use the GET method.
	  */

	function setGet($get)
	{
		return $this->isGet = $get;
	}


	//-------------------------------------------------------------------
	// METHOD: getMethod
	//-------------------------------------------------------------------
	
	/**
	  * This method returns strings with a value of 'GET' or 'POST' depending on the HTTP method used by the application to transmit field data.
	  *
	  * @access public 
	  * @return string A string representing the current HTTP data transmission method.
	  * @see setGet() 
	  * @see isGet() 
	  */
	
	function getMethod()
	{
		return ($this->isGet ? 'GET' : 'POST');
	}


	//-------------------------------------------------------------------
	// METHOD: isSecure
	//-------------------------------------------------------------------
	
	/**
	  * This method returns whether or not the application is running on a secure server.
	  *
	  * @access public 
	  * @return bool If the application is currently running on a secure server.
	  */

	function isSecure()
	{
		return ($_SERVER['SERVER_PORT'] == 443);
	}


	//-------------------------------------------------------------------
	// METHOD: transferArgumentToCurrentWindow
	//-------------------------------------------------------------------
	
	/**
	  * This method takes a registered argument, and moves it and its value into the current window. Returns false if the argument doesn't exist, true if the operation was successful.
	  *
	  * @access public 
	  * @param string $arg The name of the argument to transfer.
	  * @return bool Whether or not the operation was successful.
	  */

	
	function transferArgumentToCurrentWindow($arg)
	{
		if ($this->hasArgument($arg))
		{
			$window = &$this->getCurrentWindow();
			
			$window->setArgument($arg, $this->getArgument($arg));
			
			$this->clearArgument($arg);
			
			unset($window);
		}
		else
		{
			return false;
		}
	}
	

	//-------------------------------------------------------------------
	// METHOD: preprocess()
	//-------------------------------------------------------------------

	/**
	 * This method will get called at the begining of a request. You can override this method in your application and implement anything you want to.
	 * 
	 * An example use for this would be to parse the $_SERVER['PATH_INFO']
	 *
	 * @access public
	 */

	function preprocess()
	{
		
	}


	//-------------------------------------------------------------------
	// METHOD: setLoggedIn()
	//-------------------------------------------------------------------

	/**
	 * For applications that require users to login, you can set this to
	 * true when the user successfully logs in. This way, you can later
	 * use isLoggedIn() in the preprocess() function to do double-checks.
	 * 
	 * @param $loggedIn boolean Is the user logged in?
	 * @access public
	 */

	function setLoggedIn($loggedIn)
	{
		$this->_loggedIn = $loggedIn;	
	}


	//-------------------------------------------------------------------
	// METHOD: isLoggedIn()
	//-------------------------------------------------------------------

	/**
	 * If you've called setLoggedIn() when the user logged in, you can
	 * use this function to check the status.
	 *
	 * @return boolean Is the user logged in?
	 * @access public
	 */

	function isLoggedIn()
	{
		return $this->_loggedIn;	
	}

	
	//-------------------------------------------------------------------
	// METHOD: setLanguage
	//-------------------------------------------------------------------

	/**
	 * This method sets the input component's language.
	 *
	 * @access public
	 * @param string $style The CSS style to set. 
	 */

	function setLanguage($language)
	{
		$this->_language = $language;
	}


	//-------------------------------------------------------------------
	// METHOD: getLanguage
	//-------------------------------------------------------------------

	/**
	 * This method gets the input component's language.
	 *
	 * @access public
	 * @return string The component's CSS style. 
	 * @see setStyle()
	 */

	function getLanguage()
	{
		return $this->_language;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getFormAction()
	//-------------------------------------------------------------------

	/**
	 * This returns what the path to the action="" tag in the form tag
	 * that CC_Index inserts. Override this if you want to customize it
	 * based on conditions.
	 *
	 * @return string The path to the action for the form tag.
	 * @access public
	 */

	function getFormAction($suffix = '', $queryString = null)
	{
		$action = BASE_URL . $suffix;
		
		if (isset($_COOKIE[session_name()]) && $_COOKIE[session_name()] == session_id())
		{
			if ($queryString)
			{
				$action .= '?' . $queryString;
			}
		}
		else
		{
			$action .= '?' . SID;

			if ($queryString)
			{
				$action .= '?' . $queryString;
			}
		}
		
		return $action;
	}


	//-------------------------------------------------------------------
	// METHOD: getFormOpen
	//-------------------------------------------------------------------

	/**
	 * This displays the open form HTML need by N2O.
	 *
	 * @return void
	 * @access public
	 */

	function getFormOpen()
	{
?>
<form method="POST" name="CC_Form" enctype="multipart/form-data" action="<?php echo $this->getFormAction(); ?>">
<input type="hidden" name="pageId" value="<?php echo URLValueEncode($this->getAction()); ?>">
<input type="hidden" name="pageIdKey" value="<?php echo URLValueEncode($this->getActionKey()); ?>">
<?php
	}


	//-------------------------------------------------------------------
	// METHOD: getFormClose
	//-------------------------------------------------------------------

	/**
	 * This displays the close form HTML need by N2O.
	 *
	 * @return void
	 * @access public
	 */

	function getFormClose()
	{
?>
</form>
<?php
	}
}
?>