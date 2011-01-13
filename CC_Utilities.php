<?php
// $Id: CC_Utilities.php,v 1.70 2004/12/21 20:20:47 patrick Exp $
//=======================================================================
// FILE: CC_Utilities
//=======================================================================

/**
 * CC_Utilities contains static functions available to all classes in N2O.
 * 
 * @package N2O
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

//-------------------------------------------------------------------
// FUNCTION: outputArrayKeys
//-------------------------------------------------------------------
 
/**
 * This method echos (via PHPs 'echo' command) the keys and values of the given array to the screen. It is used primarily for debug puposes in order to examine the contents of an array.
 *
 * @param array $anArray The array to get the keys for.
 */
  
function outputArrayKeys($anArray)
{
	$keys = array_keys($anArray);
	
	echo '<!-- ----- CC_Utilities::outputArrayKeys() --------- -->' . "\n";
	
	for ($i = 0; $i < sizeof($keys); $i++)
	{
		echo $keys[$i] . ': ' . $anArray[$keys[$i]] . '<br>' . "\n";
	}

	echo '<!-- ----------------------------------------------- -->' . "\n";
}


//=======================================================================
// METHOD: writeFile
//=======================================================================

/**
 * This method writes content to a file.
 *
 * @param string $file The path to the file to write.
 * @param string $content The data to write to the file.
 */
 
function writeFile($file, $content)
{
	$mode = true;

	if ($fp = fopen($file, 'w'))
	{
		if (flock($fp, LOCK_EX, $mode))
		{
			if (!fwrite($fp, $content))
			{
			   trigger_error('Could not write to ' . $file, E_USER_WARNING);
			}
		
			flock($fp, LOCK_UN, $mode);
		}
		
		fclose($fp);

		if (!@chmod($file, 0664))
		{
		   trigger_error('Could not chmod ' . $file, E_USER_WARNING);
		}
	}
	else
	{
		trigger_error('Could not open ' . $file . ' for writing.', E_USER_WARNING);
	}
}


//-------------------------------------------------------------------
// FUNCTION: linkify
//-------------------------------------------------------------------

/**
 * This method takes a string and outputs HTML link (ie. an HREF tag) around links. It is used for generating HTML display text where active page links are desired.
 *
 * eg. 
 * The output of the following:
 * -----------------------------
 * echo linkify('Please visit http://coverallcrew.com/ or download docs at ftp://ftp.coverallcrew.com/ for more info.');
 *
 * Would be:
 * ---------
 * Please visit <a href="http://coverallcrew.com/">http://coverallcrew.com/</a> or download docs at <a href="ftp://ftp.coverallcrew.com/">ftp://ftp.coverallcrew.com/</a> for more info.
 *
 * @access public
 * @param string $string The text to parse.
 */

function linkify($string)
{
	return ereg_replace('[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]', '<a href="\0">\0</a>', $string);
}


//-------------------------------------------------------------------
// FUNCTION: isURL
//-------------------------------------------------------------------

/**
 * This method returns whether or not a given string is a URL with the format "<protocol>://<URL>".
 *
 * @access public
 * @param string $string The string to analyze.
 */

function isURL($string)
{
	return ereg('^[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]', $string);
}


//-------------------------------------------------------------------
// FUNCTION: getFieldListFromTable
//-------------------------------------------------------------------

/**
 * This method returns an array of field names from the given table, possibly excluding some certain field names.
 *
 * @param string $table The name of the table to search.
 * @param array $fieldsToExlcude An array of field names to exclude.
 * @return string A comma delimited list of field names.
 */

function getFieldListFromTable($table, $fieldsToExclude = null)
{
	$application = &$_SESSION['application'];
	
	if ($fieldsToExclude == null)
	{
		$fieldsToExclude = array();
	}
	
	$fieldList = '';
	
	$arrayOfFields = $application->db->cc_get_fields($table);
	
	$arraySize = sizeof($arrayOfFields);
	$newArray = array();
	
	for ($i = 0; $i < sizeof($fieldsToExclude); $i++)
	{
		$fieldsToExclude[$i] = strtoupper($fieldsToExclude[$i]);
	}
	
	for ($i = 0; $i < $arraySize; $i++)
	{
		$column = $arrayOfFields[$i];
		
		if (!(in_array(strtoupper($column), $fieldsToExclude)))
		{
			$newArray[] = $arrayOfFields[$i];
		}
	}
	
	return implode(',', $newArray);
}


//-------------------------------------------------------------------
// FUNCTION: getEditableFieldListFromTable
//-------------------------------------------------------------------

/**
 * This method returns an array of field names from the given table, excluding the ID, DATE_ADDED and LAST_MODIFIED N2O fields, which aren't editable.
 *
 * @access public
 * @param string $table The name of the table to search.
 * @return string A comma delimited list of field names.
 */

function getEditableFieldListFromTable($table)
{
	return getFieldListFromTable($table, array('ID', 'DATE_ADDED', 'LAST_MODIFIED'));
}


//-------------------------------------------------------------------
// FUNCTION: zeroPad
//-------------------------------------------------------------------

/**
 * This method 0 pads single digits with a zero for use mostly with the CC_Timestamp_Field and the like.
 *
 * @access public
 * @param int $number The number to zero pad.
 * @return A two digit number with zero padding, if applicable.
 */

function zeroPad($number)
{
	if ($number < 10)
	{
		return '0' . $number;
	}
	else
	{
		return $number;
	}
}


//-------------------------------------------------------------------
// FUNCTION: strSlide13
//-------------------------------------------------------------------

/**
 * This method is used as a low-security encryption scheme. Call strslide again to encrypt AND depcrypt the string. It is used primarlily in N2O for encoding/decoding application URLs and their parameters but can also be used for other low-security encryption needs.
 *
 * @access public
 * @param string $string The string to slide.
 * @return string The encrypted or decrypted string.
 */

function strSlide13($string)
{
	$from = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXY.Z_';
	$to	=   '9876543210nopqrstuvwxyzabcdefghijklmNOPQRSTUVWXY.ABCDEFGHIJKLM_Z';

	return strtr($string, $from, $to);
}


//-------------------------------------------------------------------
// FUNCTION: cc_strrpos
//-------------------------------------------------------------------

/**
 * This method is like PHP's strpos except that it recursively finds the *last* occurence of a string in another string.
 *
 * @access public
 * @param string $haystack The string to search.
 * @param string $needle The string to look for in $haystack. 
 * @param int $stringPosition The string position to start at.
 * @param int $previousStringPosition (reserved for use in the method's recursive algorithm, it is false initially).
 * @return mixed An int representing the last occurence of the string or false if not found.
 */

function cc_strrpos($haystack, $needle, $stringPosition = 0, $previousStringPosition = false)
{	
	$stringPosition = strpos($haystack, $needle, $stringPosition);
	
	if ($stringPosition == 0)
	{
		return $previousStringPosition;
	}
	else
	{
		$previousStringPosition = $stringPosition;
		
		return cc_strrpos($haystack, $needle, $stringPosition + 1, $previousStringPosition);
	}
}


//-------------------------------------------------------------------
// FUNCTION: URLValueEncode
//-------------------------------------------------------------------

/**
 * This method encodes a string primarily if it to be used an application URL. It uses the strSlide13 method to perform simple encryption.
 *
 * @access public
 * @param string $URLValue The string to encode.
 * @return mixed An int representing the last occurence of the string or false if not found.
 * @see strSlide13()
 */


function URLValueEncode($URLValue)
{
	return urlencode(strSlide13($URLValue));
}


//-------------------------------------------------------------------
// FUNCTION: URLValueDecode
//-------------------------------------------------------------------

/**
 * This method decodes a URL encoded with URLValueEncode. Used primarily with application URLs. It uses the strSlide13 method to perform simple encryption.
 *
 * @access public
 * @param string $URLValue The string to decode.
 * @return mixed An int representing the last occurence of the string or false if not found.
 * @see strSlide13()
 */


function URLValueDecode($URLValue)
{
	return strSlide13(urldecode($URLValue));
}


//-------------------------------------------------------------------
// FUNCTION: is_a
//-------------------------------------------------------------------

/**
 * This method is used since the real is_a function is only available in PHP 4.2 or greater. It returns whether a given object is of a specified class.
 *
 * @access public
 * @param mixed $object The object to verify.
 * @param string $class_name The name of the class to check for the object's membership. 
 * @return bool Whether or not the object is of the specified class.
 */
 
if (!function_exists('is_a'))
{
	function is_a($object, $class_name)
	{
		$class_name = strtolower($class_name);
		
		if (get_class($object) == $class_name)
		{
			return true;
		}
		else
		{
			return is_subclass_of($object, $class_name);
		}
	}
}

//-------------------------------------------------------------------
// FUNCTION: cc_is_int
//-------------------------------------------------------------------

/**
 * This method is used since the PHP is_int function does not work robustly for all data types.
 *
 * @access public
 * @param mixed $x The data to verify.
 * @return bool Whether or not the data is actually an integer.
 */

function cc_is_int($x) 
{
	return is_numeric($x) ? (intval($x) == $x) : false;
}


//-------------------------------------------------------------------
// FUNCTION: array_change_key_case
//-------------------------------------------------------------------

/**
 * This method is used since the real array_change_key_case function is only available in PHP 4.2 or greater. It changes the keys in the input array to be all lowercase or uppercase. The change depends on the last optional case parameter. You can pass two constants there CASE_UPPER and CASE_LOWER. The default is CASE_LOWER . The function will leave the number indices as is. 
 *
 * @access public
 * @param array $array The array to process.
 * @param int $case CASE_LOWER or CASE_UPPER depending on what is required. 
 */

if (!function_exists('array_change_key_case'))
{
	define('CASE_UPPER', 1);
	define('CASE_LOWER', 2);
	function array_change_key_case($array, $case = CASE_LOWER)
	{
		$newArray = array();
		
		$keys = array_keys($array);
		
		for ($i = 0; $i < sizeof($array); $i++)
		{
			if ($case == CASE_UPPER)
			{
				$newArray[strtoupper($keys[$i])] = $array[$keys[$i]];
			}
			else
			{
				$newArray[strtolower($keys[$i])] = $array[$keys[$i]];
			}
		}
		
		return $newArray;
	}
}

//-------------------------------------------------------------------
// FUNCTION: verifyClassType
//-------------------------------------------------------------------

/**
 * This method verifies an object is of a certain class and returns true or it triggers a fatal error and halt application execution.
 *
 * @access public
 * @param mixed $anObject The object to verify.
 * @param string $aClassName The name of the class to check for the object's membership. 
 * @return bool True if the object is of the specified class otherwise it triggers a fatal error and the application quits.
 */

function verifyClasstype($anObject, $aClassName)
{
	if (!is_a($anObject, $aClassName))
	{
		trigger_error('The passed object of type ' . get_class($anObject) . ' did not match the requested type: ' . $aClassName, E_USER_ERROR);
	}
	else
	{
		return true;
	}
}


// ----------------------------------------------------------------
// REQUIRE-O-MATIC (tm): requireAllFilesInFolder
// ----------------------------------------------------------------

/**
 * This method is used to make all PHP files in a given folder available to other classes. Unlike requireAllFilesInFolderIfExists, this method does *not* perform a check to verify that the given folder exists.
 *
 * @access public
 * @param string $folder The relative path to the folder. 
 * @see requireAllFilesInFolderIfExists()
 */

function requireAllFilesInFolder($folder)
{
	$handle = opendir($folder);

	while (false !== ($file = readdir($handle))) 
	{ 
		$fullFile = $folder . $file;

		if (is_dir($fullFile) && substr($file, 0, 1) != '.')
		{
			requireAllFilesInFolder($fullFile . '/');
		}
		else if (preg_match('/\.php$/', $fullFile))
		{
			require_once($fullFile);
		}
	}
}


// ----------------------------------------------------------------
// REQUIRE-O-MATIC (tm): requireAllFilesInFolderIfExists
// ----------------------------------------------------------------

/**
 * This method is used to make all PHP files in a given folder available to other classes. Unlike requireAllFilesInFolder, this method does a check to verify that the given folder exists.
 *
 * @access public
 * @param string $folder The relative path to the folder. 
 * @see requireAllFilesInFolder()
 */

function requireAllFilesInFolderIfExists($folder)
{
	if (file_exists($folder))
	{
		requireAllFilesInFolder($folder);
	}
}


// ----------------------------------------------------------------
// REQUIRE-O-MATIC (tm): requireIfExists
// ----------------------------------------------------------------

/**
 * This method is used to make a single PHP file available to other classes. Unlike PHP's own require_once() function, requireIfExists does a check to verify that the given folder exists so no errors are triggered.
 *
 * @access public
 * @param string $file The relative path to the file. 
 * @see require_once()
 */

function requireIfExists($file)
{
	if (file_exists($file))
	{
		require_once($file);
	}
}


//-------------------------------------------------------------------
// FUNCTION: convertMysqlDateTimeToTimestamp
//-------------------------------------------------------------------

/**
 * This method converts a MySQL datetime to a UNIX timestamp.
 *
 * @access public
 * @param string $datetime The MySQL datetime to convert. 
 * @return string The datetime as a UNIX timestamp.
 */

function convertMysqlDateTimeToTimestamp($datetime)
{
	if ($datetime == '0000-00-00 00:00' || $datetime == '')
	{
		$datetime = 'today';
	}

	return strtotime($datetime);
}


//-------------------------------------------------------------------
// FUNCTION: convertMysqlDateTimeToHuman
//-------------------------------------------------------------------

/**
 * This method converts a MySQL datetime to a human-readable format.
 *
 * @access public
 * @param string $datetime The MySQL datetime to convert. 
 * @return string A human readable date and time.
 */

function convertMysqlDateTimeToHuman($datetime)
{
	if ($datetime == '0000-00-00 00:00' || $datetime == '')
	{ 
		$datetime = 'today';
	}

	return date('F d, Y, H:i', strtotime($datetime));
}


//-------------------------------------------------------------------
// FUNCTION: convertMysqlTimestampToPHPTimestamp
//-------------------------------------------------------------------

/**
 * This method converts a MySQL timestamp to a PHP timestamp.
 *
 * @access public
 * @param string $mysqlTimestamp The MySQL timestamp to convert. 
 * @return string A PHP timestamp.
 */

function convertMysqlTimestampToPHPTimestamp($mysqlTimestamp)
{
	if ($mysqlTimestamp == '00000000000000' || $mysqlTimestamp == '')
	{ 
		$today = getdate();
		
		$month = $today['mon'];
		$day = $today['mday']; 
		$year = $today['year'];
		$hour = 12;
		$minute = 0;
	}
	else
	{
		// 2002 03 08 18 02 30
		$year=strval(substr($mysqlTimestamp,0,4));
		$month=strval(substr($mysqlTimestamp,4,2));
		$day=strval(substr($mysqlTimestamp,6,2));
		$hour=strval(substr($mysqlTimestamp,8,2));
		$minute=strval(substr($mysqlTimestamp,10,2));
	}
	
	return mktime($hour, $minute, 0, $month, $day, $year);
}


//-------------------------------------------------------------------
// FUNCTION: convertMysqlTimestampToHuman
//-------------------------------------------------------------------

/**
 * This method converts a MySQL timestamp to a human-readable format.
 *
 * @access public
 * @param string $mysqlTimestamp The MySQL timestamp to convert. 
 * @param bool $withWords Whether or not to use words for the date (as opposed to numbers). 
 * @param bool $showTime Whether or not to return the hours and minutes as well. 
 * @return string A human readable date.
 */

function convertMysqlTimestampToHuman($mysqlTimestamp, $withWords = false, $showTime = true)
{
	if (!$withWords)
	{
		return date ('Y-m-d' . ($showTime ? ' H:i:s' : ''), convertMysqlTimestampToPHPTimestamp($mysqlTimestamp));
	}
	else
	{
		return date ('F d, Y' . ($showTime ? ', H:i' : ''), convertMysqlTimestampToPHPTimestamp($mysqlTimestamp));
	}
}


//-------------------------------------------------------------------
// FUNCTION: convertMysqlDateToHuman
//-------------------------------------------------------------------

/**
 * This method converts a MySQL date to a human-readable format.
 *
 * @access public
 * @param string $date The MySQL date to convert. 
 * @return string A human readable date.
 */

function convertMysqlDateToHuman($date)
{
	if ($date == '0000-00-00' || $date == '')
	{ 
		$timestamp = time();
	}
	else
	{
		$timestamp = strtotime($date);
	}
	
	return date('F d, Y', $timestamp);
}


//-------------------------------------------------------------------
// FUNCTION: convertMysqlDateToSortable
//-------------------------------------------------------------------

/**
 * This method converts a MySQL date to a spreadsheet sortable format.
 *
 * @access public
 * @param string $date The MySQL date to convert. 
 * @return string A spreadsheet sortable date.
 */

function convertMysqlDateToSortable($date)
{
	if ($date == '0000-00-00' || $date == '')
	{ 
		$timestamp = time();
	}
	else
	{
		$timestamp = strtotime($date);
	}
	
	return date('Y-m-d H:i:s', $timestamp);
}


//-------------------------------------------------------------------
// FUNCTION: convertMysqlDatelToTimestamp
//-------------------------------------------------------------------

/**
 * This method converts a MySQL date to a UNIX timestamp.
 *
 * @access public
 * @param string $date The MySQL date to convert. 
 * @return string A UNIX timestamp.
 */

function convertMysqlDateToTimestamp($date)
{
	if ($date == '0000-00-00' || $date == '')
	{ 
		return strtotime('today 00:00:00');
	}
	else
	{
		return strtotime($date);
	}
}


//-------------------------------------------------------------------
// FUNCTION: convertMysqlTimeStampToPHP
//-------------------------------------------------------------------

/**
 * This method converts a MySQL timestamp to the passed PHP format.
 *
 * @access public
 * @param string $mysqlTimestamp The MySQL timestamp to convert.
 * @param string $timeStampOrDate Whether or not to use words for the date (as opposed to numbers). 
 * @return string A human readable date.
 */

function convertMysqlTimeStampToPHP($date, $timeStampOrDate)
{
	if ($date == '00000000000000' || $date == '') 
	{ 
		return '';
	}
	
	//2002 03 08 18 02 30
	
	$yr=strval(substr($date,0,4));
	$mo=strval(substr($date,4,2));
	$da=strval(substr($date,6,2));
	
	$hr=strval(substr($date,8,2));
	$mi=strval(substr($date,10,2));
	$se=strval(substr($date,12,2));
	
	if ($timeStampOrDate == 'timestamp')
	{
		return mktime($hr,$mi,$se,$mo,$da,$yr);
	}
	else
	{
		return date('F d, Y', mktime($hr,$mi,$se,$mo,$da,$yr));
	}
}


//-------------------------------------------------------------------
// FUNCTION: deleteArrayElement
//-------------------------------------------------------------------

/**
 * This method deletes an element from the given array at the given index.
 *
 * @access public
 * @param array $array The array from which we want to delete an element.
 * @param int $index The array index to delete. 
 * @return array An array with the deleted index.
 */

function &deleteArrayElement(&$array, $index)
{
	$newArray = array();
		
	for ($i = 0; $i < sizeof($array); $i++)
	{
		if ($i != $index)
		{
			$newArray[] = &$array[$i];
		}
	}
	
	return $newArray;
}


//-------------------------------------------------------------------
// FUNCTION: deleteAssociativeArrayElement
//-------------------------------------------------------------------

/**
 * This method deletes an element from the given array at the given 
 * associative index.
 *
 * @access public
 * @param array $array The array from which we want to delete an element.
 * @param mixed $index The array associative index to delete. 
 * @return array An array with the deleted index.
 */

function &deleteAssociativeArrayElement(&$array, $index)
{
	$newArray = array();
	
	$arraykeys = array_keys($array);
		
	for ($i = 0; $i < sizeof($array); $i++)
	{
		if ($i != $index)
		{
			$newArray[] = &$array[$i];
		}
	}
	
	return $newArray;
}


//-------------------------------------------------------------------
// FUNCTION: unsetKeyArray
//-------------------------------------------------------------------

/** 
  * This method takes an array, and unsets values from it at the given key(s). You must pass at least one key, but you can also pass as many additional keys as you desire.
  *
  * @access public
  * @param array $array The array from which we want to unset elements.
  * @param mixed $key,... The array key(s) to unset. 
  * @return array An array with the deleted index.
  * @todo Fix this method. It seems broken.
  */

function &unsetKeyArray(&$array, $key)
{
	$argumentCount = func_num_args();
	
	$arraySize = sizeof($array);
	$keys = array_keys($array);
	
	for ($i = 0; $i < $arraySize; $i++)
	{
		for ($j = 0; $j < $argumentCount - 1; $j++)
		{
			if ($keys[$i] == func_get_arg($j + 1))
			{
				unset($array[$key]);
			}
		}
	}
	
	unset($argumentCount, $arraySize, $keys, $i, $j);
	
	return $array;
}


//-------------------------------------------------------------------
// FUNCTION: shortenText()
//-------------------------------------------------------------------

/** 
  * This method shortens a string to a specified length and uses continuation characters to indicate it was shortened.
  *
  * @access public
  * @param string $textToShorten The text to shorten.
  * @param int $textLength The length of the shortened text.
  * @param string $moreString The string to use for the continuation characters (defaults to '...'.
  * @return string The shortened string.
  */

function shortenText($textToShorten, $textLength = 25, $moreString = '...')
{
	if (strlen($textToShorten) > $textLength)
	{
		$shortString = substr($textToShorten, 0, $textLength - 3);
		
		return substr($shortString, 0, strrpos($shortString, ' ')) . $moreString;
	}
	else
	{
		return $textToShorten;
	}
}


//-------------------------------------------------------------------
// FUNCTION: cc_mail()
//-------------------------------------------------------------------

/** 
  * This method sends an email. An error is written to the log file if an error occurs (no need to duplicate this code in every application) and the error object (of type PEAR_Error) is returned to the application so that it can handle it as it sees fit.
  *
  * @access public
  * @param string $to The email address to send to.
  * @param string $subject The email subject.
  * @param string $content The email message.
  * @param array $headers Additional headers to add to the e-mail.
  * @return mixed Boolean true if successful, or a PEAR_Error if false.
  * @see http://pear.php.net/manual/en/core.pear.pear-error.php
  */

function cc_mail($to, $subject, $content, $headers, $mailserver = null)
{
	require_once('Mail.php');
	global $sendmailFromEmailAddress, $sendmailHost;
		
	if (!isset($sendmailHost) && !isset($mailserver))
	{
		$sendmailHost = '127.0.0.1';
	}

	if (!isset($headers['From']))
	{
		if (!isset($sendmailFromEmailAddress))
		{
			$sendmailFromEmailAddress = 'webmaster@' . $_SERVER['SERVER_NAME'];
		}
	
		$headers['From'] = $sendmailFromEmailAddress;
	}

	if (!isset($headers['To']))
	{
		$headers['To'] = $to;
	}
	$headers['Subject'] = $subject;
	$headers['X-Mailer'] = 'N2O';
	
	$recipients = array();
	$recipients[] = $to;
	
	if (isset($headers['Cc']))
	{
		$recipients[] = $headers['Cc'];
	}

	if (isset($headers['Bcc']))
	{
		$recipients[] = $headers['Bcc'];
	}

	$mail = &Mail::factory('smtp', array('host' => $sendmailHost));
	
	$mailReturn = $mail->send($recipients, $headers, $content);
	
	if (PEAR::IsError($mailReturn))
	{
		global $application_name;
		
		//mailReturn will be a PEAR_Error object if there is an error and a line will be written to the error log.
		$mailError = 'CC_Utilities::cc_mail: An error occured trying to send email in application *' . $application_name . '* with the following details-> To: ' . $headers['To'] . ', From: ' . $headers['From'] . ', Subject: ' . $headers['Subject'] . ', Error: ' . $mailReturn->toString();
		trigger_error($mailError, E_USER_WARNING);
	}
	
	return $mailReturn;
}

//-------------------------------------------------------------------
// FUNCTION: removeCommas()
//-------------------------------------------------------------------

/** 
  * This method removed commas from a string. Useful for the integer number field.
  *
  * @access public
  * @param string $string The string to process.
  * @return string The string minus any commas.
  */
  
function removeCommas($string)
{
	return str_replace(',', '', $string);
}


//-------------------------------------------------------------------
// FUNCTION: quoteEmailMessage()
//-------------------------------------------------------------------

/** 
  * This method quotes e-mail messages (with levels of '>') to distinguish between levels of reply.
  *
  * @access public
  * @param string $message The email message to quote.
  * @return string The quoted e-mail message.
  */

function quoteEmailMessage($message)
{
	if (($positionOfNewline = strpos($message, "\n")) !== false)
	{
		if (($positionOfReturn = strpos($message, "\r")) !== false)
		{
			if ($positionOfReturn == ($positionOfNewline - 1))
			{
				$stringToQuote = substr($message, 0, $positionOfReturn);
				$stringRemainder = substr($message, $positionOfReturn + 2, strlen($message) - $positionOfReturn - 2);
			}
			else
			{
				$stringToQuote = substr($message, 0, $positionOfNewline);
				$stringRemainder = substr($message, $positionOfNewline + 1, strlen($message) - $positionOfNewline - 1);
			}
		}
		else
		{
			$stringToQuote = substr($message, 0, $positionOfNewline);
			$stringRemainder = substr($message, $positionOfNewline + 1, strlen($message) - $positionOfNewline - 1);
		}
				
		return quoteEmailMessage(wordwrap($stringToQuote, 75, "\r\n")) . quoteEmailMessage($stringRemainder);
	}
	else
	{
		return '> ' . $message;
	}	
}


//-------------------------------------------------------------------
// FUNCTION: pluralize
//-------------------------------------------------------------------


/** 
  * This function will return an "s" if the passed integer is not 1.
  *
  * @arg Integer representing a value.
  *
  */

function pluralize($i)
{
	if ($i != 1)
	{
		return 's';
	}
}


//-------------------------------------------------------------------
// FUNCTION: stripFieldListFromQuery
//-------------------------------------------------------------------

/**
  * This function will replace an SQL query's field list with '*'.
  *
  * eg. 
  *
  * The following:
  * --------------
  * stripFieldListFromQuery('select EMAIL, FIRST_NAME, LAST_NAME, ADDRESS from USERS') 
  *
  * Returns:
  * -------------
  * select * from USERS
  *
  * @arg strong $query The query to strip.
  * @return string The stripped query.
  *
  */

function stripFieldListFromQuery($query)
{
	return preg_replace('/select (.*) from (.*)/i', 'select * from \2', $query);
}


//-------------------------------------------------------------------
// FUNCTION: getApplication
//-------------------------------------------------------------------


/** 
  * This function return the current application object.
  *
  * @return CC_Application A reference to the current application.
  */

function &getApplication()
{
	return $_SESSION['application'];
}



//-------------------------------------------------------------------
// FUNCTION: parseOMatic
//-------------------------------------------------------------------

/** 
  * This function takes a file, opens it, and parses the krunk out of it.
  *
  * The $file must exist and must contain unique keys to be parsed.
  *
  * The $parseKeys array takes this format
  *     $parseKeys['__KEY_TO_PARSE__'] = 'replacementValue';
  *
  * @access public
  * @param string $file The file on the server we wish to parse (must include full path)
  * @param array $parseKeys The keys to search for and the values to replace them with. ($parseKeys['__KEY_TO_PARSE__'] = 'replacementValue';)
  * @param boolean $useFile The first $file string will be a path to a file, otherwise it will just be the string to parse.
  * @return string The parsed string.
  */

function parseOMatic($file, $parseKeys = null, $useFile = true)
{
	if ($parseKeys == null)
	{
		$parseKeys = array();
	}
	
	$keys = array_keys($parseKeys);
	
	$sizeOfKeys = sizeof($keys);
	
	for ($i = 0; $i < $sizeOfKeys; $i++)
	{
		$search[] = $keys[$i];
		$replace[] = $parseKeys[$keys[$i]];
	}
	

	if ($useFile)
	{
		$parsedString = file_get_contents($file);
	}
	else
	{
		$parsedString = $file;
	}

	
	if ($sizeOfKeys > 0)
	{
		$parsedString = str_replace($search, $replace, $parsedString);
	}
	
	return $parsedString;
}


//-------------------------------------------------------------------
// FUNCTION: getStackTrace
//-------------------------------------------------------------------

/** 
  * This function returns the function call stack trace. (It only works if the PHP function debug_backtrace() exists.)
  *
  * @access public
  * @param boolean $ignoreFirst In most cases with N2O, the first call is include(), so we don't normally need to see that.
  * @return string The stacktrace.
  */

function getStackTrace($ignoreFirst = true)
{
	if (function_exists('debug_backtrace'))
	{
		$backtrace = debug_backtrace();
		
		$size = sizeof($backtrace) - ($ignoreFirst ? 2 : 1);
		
		$stacktrace = '';
		
		for ($i = $size; $i > 0; $i--)
		{
			$stacktrace .= $backtrace[$i]['class'] . '->' . $backtrace[$i]['function'] . '()';
			
			if ($i - 1 > 0)
			{
				$stacktrace .= ', ';
			}
		}
		
		return $stacktrace;
	}
}

?>