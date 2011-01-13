<?php
// $Id: CC_User.php,v 1.8 2004/06/15 23:24:44 mike Exp $
//=======================================================================
// CLASS: CC_User
//=======================================================================

/**
 * This class holds information about an application's current user.
 *
 * @package N2O
 * @access public
 *
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */


class CC_User
{
	/**
	 * The user's id.
	 *
	 * @var int $userId
	 * @access private
	 */

	var $userId;


	/**
	 * The user's username.
	 *
	 * @var string $username
	 * @access private
	 */

	var $username;


	/**
	 * The user's name.
	 *
	 * @var string $name
	 * @access private
	 * @deprecated
	 * @see $firstName
	 * @see $lastName
	 */

	var $name;

	
	/**
	 * The user's first name.
	 *
	 * @var string $firstName
	 * @access private
	 */

	var $firstName;


	/**
	 * The user's last name.
	 *
	 * @var string $lastName
	 * @access private
	 */

	var $lastName;


	/**
	 * The user's email address.
	 *
	 * @var string $email
	 * @access private
	 */

	var $email;	  			


	/**
	 * The user's user type. The value of this will depend on specific application context.
	 *
	 * @var string $userType
	 * @access private
	 */

	var $userType;
	
	
	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_User
	//-------------------------------------------------------------------

	/**
	 * The constructor should be overidden to do any application-specific user handling.
	 *
	 * @access public
	 */
	 
	function CC_User($userId = null)
	{
		if ($userId != null)
		{
			$this->setUserId($userId);
		}
	}
	

	//-------------------------------------------------------------------
	// METHOD: getUserType
	//-------------------------------------------------------------------

	/** 
	 * This method gets the user's user type.
	 *
	 * @access public
	 * @return string The user type.
	 * @see setUserType()
	 */

	function getUserType()
	{
		return $this->userType;
	}


	//-------------------------------------------------------------------
	// METHOD: getUserId
	//-------------------------------------------------------------------

	/** 
	 * This method gets the user's id.
	 *
	 * @access public
	 * @return int The user's id.
	 * @see setUserId()
	 */

	function getUserId()
	{
		return $this->userId;
	}


	//-------------------------------------------------------------------
	// METHOD: getUserName
	//-------------------------------------------------------------------

	/** 
	 * This method gets the user's username.
	 *
	 * @access public
	 * @return string $username The user's username.
	 * @see setUserName()
	 */

	function getUserName()
	{
		return $this->username;
	}


	//-------------------------------------------------------------------
	// METHOD: getName
	//-------------------------------------------------------------------

	/** 
	 * This method gets the user's name.
	 *
	 * @access public
	 * @return string $username The user's name.
	 * @see setName()
	 * @deprecated
	 * @see getFirstName()
	 * @see getLastName()
	 */

	function getName()
	{
		return $this->name;
	}


	//-------------------------------------------------------------------
	// METHOD: getFirstName
	//-------------------------------------------------------------------

	/** 
	 * This method gets the user's first name.
	 *
	 * @access public
	 * @return string $firstName The user's first name.
	 * @see setFirstName()
	 */

	function getFirstName()
	{
		return $this->firstName;
	}


	//-------------------------------------------------------------------
	// METHOD: getLastName
	//-------------------------------------------------------------------

	/** 
	 * This method gets the user's last name.
	 *
	 * @access public
	 * @return string $lastName The user's last name.
	 * @see setLastName()
	 */

	function getLastName()
	{
		return $this->lastName;
	}


	//-------------------------------------------------------------------
	// METHOD: getEmail
	//-------------------------------------------------------------------

	/** 
	 * This method gets the user's email address.
	 *
	 * @access public
	 * @return string $email The user's email address.
	 * @see setEmail()
	 */

	function getEmail()
	{
		return $this->email;
	}


	//-------------------------------------------------------------------
	// METHOD: setUserType
	//-------------------------------------------------------------------

	/** 
	 * This method sets the user's user type.
	 *
	 * @access public
	 * @param string $userType The user's user type.
	 * @see getUserType()
	 */
	 
	function setUserType($userType)
	{
		$this->userType = $userType;
	}


	//-------------------------------------------------------------------
	// METHOD: setUserId
	//-------------------------------------------------------------------

	/** 
	 * This method sets the user's id.
	 *
	 * @access public
	 * @param string $userId The user's id.
	 * @see getUserId()
	 */

	function setUserId($userId)
	{
		$this->userId = $userId;
	}


	//-------------------------------------------------------------------
	// METHOD: setUserName
	//-------------------------------------------------------------------

	/** 
	 * This method sets the user's name.
	 *
	 * @access public
	 * @param string $userId The user's name.
	 * @see getUserName()
	 */

	function setUserName($username)
	{
		$this->username = $username;
	}


	//-------------------------------------------------------------------
	// METHOD: setName
	//-------------------------------------------------------------------

	/** 
	 * This method sets the user's name.
	 *
	 * @access public
	 * @param string $name The user's name.
	 * @see getName()
	 */

	function setName($name)
	{
		$this->name = $name;
	}


	//-------------------------------------------------------------------
	// METHOD: setFirstName
	//-------------------------------------------------------------------

	/** 
	 * This method sets the user's first name.
	 *
	 * @access public
	 * @param string $firstName The user's first name.
	 * @see getFirstName()
	 */

	function setFirstName($firstName)
	{
		$this->firstName = $firstName;
	}


	//-------------------------------------------------------------------
	// METHOD: setLastName
	//-------------------------------------------------------------------

	/** 
	 * This method sets the user's last name.
	 *
	 * @access public
	 * @param string $lastName The user's last name.
	 * @see getLastName()
	 */

	function setLastName($lastName)
	{
		$this->lastName = $lastName;
	}


	//-------------------------------------------------------------------
	// METHOD: setEmail
	//-------------------------------------------------------------------

	/** 
	 * This method sets the user's email address.
	 *
	 * @access public
	 * @param string $email The user's email address.
	 * @see getEmail()
	 */

	function setEmail($email)
	{
		$this->email = $email;
	}
}

?>