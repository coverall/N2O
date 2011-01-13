<?php

//=======================================================================
// CLASS: CC_Error
//=======================================================================

/**
 * This class defines an error in N2O.
 *
 * @package N2O
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 * @see CC_ErrorManager
 */

class CC_Error
{
	/**
     * The error code.
     *
     * @var int $_code
     * @access private
     * @see getCode()
     * @see setCode()
     */
    
    var $_code;
	
	
	/**
     * The concise error message.
     *
     * @var string $_message
     * @access private
     * @see getMessage()
     * @see setMessage()
     */
     
    var $_message;				// the associated simple error messages
	
	
	/**
     * The more detailed error message.
     *
     * @var string $_verboseMessage
     * @access private
     * @see getVerboseMessage()
     * @see setVerboseMessage()
     */
     
	var $_verboseMessage;		// the associated verbose error messages
	
	
	/**
     * Whether or not the error stops application execution.
     *
     * @var bool $_fatal
     * @access private
     * @see getFatal()
     * @see setFatal()
     */
     
	var $_fatal;
	
	
	/**
     * Whether or not the error should be displayed on the screen.
     *
     * @var bool $_display
     * @access private
     * @see getDisplay()
     * @see setDisplay()
     */
     
	var $_display;							
	
	/**
     * The more detailed error message.
     *
     * @var bool $_time
     * @access private
     * @see getTime()
     * @see setTime()
     */
     
	var $_time;					// the time the error was constructed
	
	
	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Error
	//-------------------------------------------------------------------
	
	/**
	 * This is the CC_Error object which defines a single error. 
	 
	 * @access private
	 * @param string $code The error code defined at the top of this file.
	 * @param string $message the associated error description
	 * @param bool $fatal
	 * @param bool $display (unused)
	 */
	 
	function CC_Error($code, $message, $verboseMessage, $fatal, $display)
	{
		$this->_code = $code;
		$this->_message = $message;
		$this->_verboseMessage = $verboseMessage;
		$this->_fatal = $fatal;
		$this->_display = $display;
		$this->_time = time();
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getCode()
	//-------------------------------------------------------------------

	/**
	 * This method returns the error code.
	 *
	 * @access public
	 * @return mixed
	 */

	function getCode()
	{
		return $this->_code;
	}

	
	//-------------------------------------------------------------------
	// METHOD: setCode()
	//-------------------------------------------------------------------

	/**
	 * This method sets the error code.
	 *
	 * @access public
	 * @param $code the code to set
	 */

	function setCode($code)
	{
		$this->_code = $code;
	}


	//-------------------------------------------------------------------
	// METHOD: getMessage()
	//-------------------------------------------------------------------

	/**
	 * This method returns the (concise) error message.
	 *
	 * @access public
	 * @return string
	 */

	function getMessage()
	{
		return $this->_message;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setMessage()
	//-------------------------------------------------------------------

	/**
	 * This method sets the (concise) error message.
	 *
	 * @access public
	 * @param $message the error message to set
	 */

	function setMessage($message)
	{
		$this->_message = $message;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getVerboseMessage()
	//-------------------------------------------------------------------

	/**
	 * This method returns the (verbose) error message.
	 *
	 * @access public
	 * @return string
	 */

	function getVerboseMessage()
	{
		return $this->_verboseMessage;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setVerboseMessage()
	//-------------------------------------------------------------------

	/**
	 * This method sets the (verbose) error message.
	 *
	 * @access public
	 * @param $verboseMessage the error message to set
	 */

	function setVerboseMessage($verboseMessage)
	{
		$this->_verboseMessage = $verboseMessage;
	}


	//-------------------------------------------------------------------
	// METHOD: getTime()
	//-------------------------------------------------------------------

	/**
	 * This method returns the time the error was set
	 *
	 * @access public
	 * @return string
	 */

	function getTime()
	{
		return $this->_time;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setTime()
	//-------------------------------------------------------------------

	/**
	 * This method sets the time the error occurred.
	 *
	 * @access public
	 * @param date $time The time the error was set.
	 */

	function setTime($time)
	{
		$this->_time = $time;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getFatal()
	//-------------------------------------------------------------------

	/**
	 * This method returns whether or not the error is fatal.
	 *
	 * @access public
	 * @return bool The fatality of the error.
	 */

	function getFatal()
	{
		return $this->_fatal;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setFatal()
	//-------------------------------------------------------------------

	/**
	 * This method sets whe the the error is fatal.
	 *
	 * @access public
	 * @param bool $fatal Whether the error is fatal.
	 */

	function setFatal($fatal)
	{
		$this->_fatal = $fatal;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getDisplay()
	//-------------------------------------------------------------------

	/**
	 * This method returns if the error is to be displayed on the screen.
	 *
	 * @access public
	 * @return bool Whether the error should be displayed.
	 */

	function getDisplay()
	{
		return $this->_display;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setDisplay()
	//-------------------------------------------------------------------

	/**
	 * This method sets whether the error is to be displayed or not (unused)
	 *
	 * @access public
	 * @param bool $display
	 */

	function setDisplay($display)
	{
		$this->_display = $display;
	}
}

?>