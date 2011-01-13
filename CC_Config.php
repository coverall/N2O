<?php
// $Id: CC_Config.php,v 1.26 2004/12/21 20:18:25 patrick Exp $
//===================================================================
// PLEASE DO NOT EDIT BELOW THIS LINE!
//===================================================================

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// Application Dingles
//
	$session_name 		= '_SESSION_NAME_';
	$session_save_path	= '/tmp';
	
	// hours until the session expires. $cookieExpiryTime overrides $session_expiry
	$cookieExpiryTime   = 0;

	// by default, cookies are only readable by the domain that set
	// them. if you want to extend it to subdomains, you can set the
	// domain to something like this... the second example will clip
	// the subdomain off.
	//$cookieDomain		= '.coverallcrew.com';
	//$cookieDomain		= substr($_SERVER['SERVER_NAME'], strpos($_SERVER['SERVER_NAME'], '.'));

	// by default, cookies are set to the path of the application, so
	// if you want your session cookie readable outside the application
	// path, you can override the path here.
	//$cookiePath		= '/';

	$session_expiry 	= 0; 	// seconds until the session expires
	$application_name 	= '_APPLICATION_NAME_';
	$version_number 	= '_VERSION_';
	$relative_path 		= '_RELATIVE_PATH';
	// define if real path is not the same as the "cosmetic" path
	//$cosmetic_path		= '_COSMETIC_PATH';
	$start_point 		= '_START_PAGE_';
	$application_class 	= '_APPLICATION_CLASS_';
	$window_class		= '_CC_WINDOW_CLASS_';
	$localPath			= $_SERVER['DOCUMENT_ROOT'];
	$headerFile 		= '_HEADER_';
	$footerFile 		= '_FOOTER_';
	$isDebugOn	 		= false; // true or false
	$show_action_in_url = false; // to be implemented soon
	

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// Email Dangles
//
	// this address will be used by sendmail as the ultimate 'from' address.
	// bouncebacks will go to this address, and it must be valid.
	$sendmailFromEmailAddress = '_FROM_ADDRESS_';
	$sendmailHost = '127.0.0.1';


// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// Window Dangles
//
	$ccContentAlignment			= 'left';	 	// the alignment of the content
	$ccContentBackgroundColour	= '#ffffff';	// the colour around the content
	$ccContentBorderColour		= '#000000';	// the colour of the border around the content
	$ccTitleBarColour			= '#003366';	// the colour of the titlebar
	$ccRecordOddRowColour		= '#ffffff';	// the colour of the odd row shading
	$ccRecordEvenRowColour		= '#eeeeee';	// the colour of the even row shading
	$ccButtonBarRowColour		= '#cccccc';	// the colour of the row which contains the
	$ccRecordHighlightRowColour = '#ddddff';	// the colour of the highlight shading
	$ccDefaultRecordsPerPage    = 5;			// the default number of rows per page in a CC_Summary


// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// Error Logging
//
	$logUserErrors			= true;	// Log user errors
	$logApplicationErrors	= true;	// Log application errors

	// If you want to enable PHP's error reporting, specify which types of errors you want.
	// See: http://ca2.php.net/manual/en/phpdevel-errors.php#internal.e-error
	//
	//$showErrorsOfType		= E_ALL; // All errors
	//$showErrorsOfType		= E_ERROR | E_WARNING | E_PARSE; // All errors, warnings, and parse errors
	$showErrorsOfType		= E_ALL ^ E_NOTICE; // PHP's default


// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// Database Dongles
//
	$noDatabase = false;		 // Setting this to true will disable database support
	$noCCFieldManagerDatabase = false;	 // Setting this to true will cause N2O to not do a select * on the CC_FIELDS table.
	$ccDatabaseAlertEmail = 'support@coverallcrew.com'; // The email address that will receive database failure alerts.
	$noRelationshipManager = false;		// Settings this to true will cause N2O to not construct a relationship manager.

	// You can configure an alternate location for the database config.
	// By default, we look for CC_Database_Config.php in the root of
	// your application folder.
	//$databaseConfigPath = '/path/to/db.php'; 


//===================================================================
// PLEASE DO NOT EDIT ABOVE THIS LINE!
//===================================================================

?>