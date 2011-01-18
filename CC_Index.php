<?php
// $Id: CC_Index.php,v 1.87 2005/02/14 01:09:37 patrick Exp $

	//--- Software Version Info -----------------------------------------
	
	define ('CURRENT_VERSION', $version_number);	// current version of the software

	//--- Path Information ----------------------------------------------
	
	define('CC_FRAMEWORK_PATH', $localPath . '/N2O');
	define('APPLICATION_PATH', $localPath . $relative_path);
	define('BASE_URL', (isset($cosmetic_path) ? $cosmetic_path : $relative_path));
	define('DEBUG', $isDebugOn);

	//--- Entry point for the application -------------------------------
	
	define('START_POINT', $start_point);
	
	//--- Require the CC_Framework files --------------------------------

	require_once(CC_FRAMEWORK_PATH . '/_RequireOnceFiles.php');
	if (!isset($require_admin) || $require_admin != false)
	{
		require_once(CC_FRAMEWORK_PATH . '/_RequireOnceAdminFiles.php');
	}
	//require_once(APPLICATION_PATH . '_n2o_extras.php');
	requireAllFilesInFolderIfExists(APPLICATION_PATH . 'required/');
	requireAllFilesInFolderIfExists(APPLICATION_PATH . 'handlers/');
	requireAllFilesInFolderIfExists(APPLICATION_PATH . 'filters/');
	requireAllFilesInFolderIfExists(APPLICATION_PATH . 'fields/');
	requireAllFilesInFolderIfExists(APPLICATION_PATH . 'contentproviders/');

	// Provide a hook so applications can require other files...
	if (function_exists('_n2o_init'))
	{
		_n2o_init();
	}
		
	//--- Session Starting ----------------------------------------------
	
	header('Cache-control: private, must-revalidate, max-age=0');
	
	if (isset($session_name))
	{
		if (isset($_GET[$session_name]))
		{
			//trigger_error('A session_id was found in the request (' . $_REQUEST[$session_name] . ')', E_USER_WARNING);
			session_id($_GET[$session_name]);
		}
		session_name($session_name);
	}
	
	// Override the session save path if it's set
	if (isset($session_save_path))
	{
		session_save_path($session_save_path);
	}
	
	// $cookieExpiryTime is in hours
	if ($cookieExpiryTime > 0)
	{
		// convert cookieExpiryTime (hours) to seconds.
		// place in $session_expiry value.
		
		$session_expiry = $cookieExpiryTime * 3600;
	}
	else
	{
		$session_expiry = 0;
	}
	
	session_set_cookie_params($session_expiry, (isset($cookiePath) ? $cookiePath : BASE_URL), (isset($cookieDomain) ? $cookieDomain : ''));
	session_start();
	
	if (isset($_SESSION['application']))
	{
		$application = &$_SESSION['application'];
		
		// watch for action resetting
		if (!empty($_GET['resetAction']))
		{
			$application->setAction($start_point);
			header('Location: ' . BASE_URL);
			exit(0);
		}
		
		$newSession = false;
	}
	else //--- Instantiate the application if it is not already (ie. the first time only)
	{
		if (!$application_class)
		{
			$application_class = 'CC_Application';
		}
		$_SESSION['application'] = &new $application_class();
		$application = &$_SESSION['application'];
		
		$application->setAction($start_point);
		$newSession = true;

		// Clear any lingering linkity lacks...		
		if (isset($_GET['_LL']))
		{
			header('Location: ' . BASE_URL);
			exit(0);
		}
	}
	
	// look for jmpAction parameter or error log in URL and go there otherwise set the action 
	if (!empty($_REQUEST['jmp']))
	{
		$jmpAction = $_REQUEST['jmp'];
		$application->jumpTo($jmpAction);
	}
	else if (isset($_REQUEST['err']))
	{
		echo $application->errorManager->displayApplicationErrors();
		exit(0);
	}
	// process only the first button click if multiple clicks have been encountered and 
	else if (!$newSession) // (!isset($_REQUEST['clickCounter']) || !($_REQUEST['clickCounter'] > 1))
	{	
		// Call preprocess() in case the application has overridden it.
		$application->preprocess();

		// Reset the field errors
		$application->errorManager->clearFieldErrors();
		$application->errorManager->clearUserErrors();

		//--- UPDATE FIELDS and EXECUTE BUTTON HANDLERS --------------------
		
		// If the back button was clicked, the application->getAction() will
		// be out of date, so we should use the pageId instead.
				
		if (!$newSession)
		{
			if (!empty($_REQUEST['pageId']))
			{
				// pageId is the window where a button was clicked.
				$pageId = URLValueDecode($_REQUEST['pageId']);
				if (!empty($_REQUEST['pageIdKey']) && $_REQUEST['pageIdKey'])
				{
					$pageIdKey = URLValueDecode($_REQUEST['pageIdKey']);
				}
				else
				{
					$pageIdKey = '';
				}

				$application->setAction($pageId, $pageIdKey);
			}
		}

		// Check to see which button was clicked and if the user erroneously
		// double-clicked
		
		$button = false;
		$buttonFound = false;
		$linkityLack = false;
		$multipleClick = false;
		
		if ($application->isWindowRegistered($application->getAction(), $application->getActionKey()))
		{
			// if no pageId exists, use the current action value as the current page.
			$window = &$application->getWindow($application->getAction(), $application->getActionKey());
			$fieldsUpdated = false;

			$size = sizeof($window->buttons);

			for ($i = 0; $i < $size; $i++)
			{
				if ($window->buttons[$i]->isClickInRequest())
				{
					$buttonFound = true;
					$button = &$window->buttons[$i];
					$buttonId = $button->getId();
					$linkityLack = $button->isGetRequest();
					break;
				}
			}
			
			unset($size);

			if ($buttonFound)
			{
				// Check to see if someone double-clicks on a button and let the handler
				// decide what to do with multiple clicks.
				if ($application->isMultipleClick($buttonId))
				{
					$multipleClick = true;
				}
				else
				{
					$application->registerButtonClick($buttonId);
				}

				// If the button is false, we have a problem. It is likely the user is a Windows IE user.
				/*if ($button === false)
				{
					trigger_error('Button ' . $buttonId . ' was not found in window ' . $application->getAction() . '. Did you register it?', E_USER_WARNING);
					header('Location: ' . $application->getFormAction());
					exit();
				}*/
								
				// Update the fields from the form if appropriate
				if ($button->isFieldUpdater() && !$fieldsUpdated)
				{
					$window->updateFieldsFromPage($button->validateOnClick(), $button->fieldsToValidateArray);
				}

				// Buttons which don't require validation should process their handlers
				if (!$button->validateOnClick() || !$window->hasError())
				{
					// pass whether or not the button has been double clicked, so the
					// handler can decide whether how to process().
					$button->click($multipleClick);
				}
	
				// Update the fields from the database if appropriate
				if ($button->isFieldUpdater() && !$fieldsUpdated)
				{
					$window->updateFieldsFromDatabase($button->fieldsToValidateArray);
					$fieldsUpdated = true;
				}
				
				if ($linkityLack)
				{
					//trigger_error('Redirecting to: ' . 'Location: ' . $application->getFormAction($button->getPath()), E_USER_WARNING);
					header('Location: ' . $application->getFormAction($button->getPath()));
					exit();
				}
			}	
			else if (!empty($_POST['pageId']))
			{
				// If no button was clicked, but the form was submitted, we
				// know that the user hit enter in a field. We will see if
				// the window has a default button associated with it, and
				// then click that button to execute the handlers.
				
				// Make sure the field data is not lost!!
				$window->updateFieldsFromPage(false);
				
				if (isset($window->defaultButton))
				{
					if ($window->defaultButton->isFieldUpdater())
					{
						$window->updateFieldsFromPage($window->defaultButton->validateOnClick(), $window->defaultButton->fieldsToValidateArray);
					}
	
					if (!$window->defaultButton->validateOnClick() || !$window->hasError())
					{
						$window->defaultButton->click();
					}
	
					if ($window->defaultButton->isFieldUpdater())
					{
						$window->updateFieldsFromDatabase($window->defaultButton->fieldsToValidateArray);
					}
				}
			}
		}
		
		unset($newSession, $linkityLack, $buttonFound, $button, $fieldsUpdated, $window);
	}
	else
	{
		// Call preprocess() in case the application has overridden it.
		$application->preprocess();
	}
	
	//--- page display message -----------------------------------------

	include($headerFile);
	
	$application->getFormOpen();

	include($application->getActionURL() . '.php');
	
	if (DEBUG)
	{
		$debugLogoutButton = &new CC_Logout_Button();
		$logoutWindow = &$application->getCurrentWindow();
		$logoutWindow->registerComponent($debugLogoutButton);
		echo $debugLogoutButton->getHTML();
		unset($logoutWindow);
		unset($debugLogoutButton);
	}

	$application->getFormClose();

	include($footerFile);
?>
