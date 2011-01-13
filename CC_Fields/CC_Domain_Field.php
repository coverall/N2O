<?php
// $Id: CC_Domain_Field.php,v 1.5 2004/09/20 21:02:50 mike Exp $
//=======================================================================
// CLASS: CC_Domain_Field
//=======================================================================

/**
 * The CC_Domain_Field field represents a domain name.
 *
 * @package CC_Fields
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

class CC_Domain_Field extends CC_Text_Field
{
	//-------------------------------------------------------------------
	// FUNCTION: setValue
	//-------------------------------------------------------------------

	/** 
	 * This method strips the 'www.' from the value and sets the field value with it.
	 *
	 * @access public
	 * @param string $value The domain to set.
	 */

	function setValue($value)
	{
		// if they have a 'www.' in there, nuke it...
		if (ereg('^www\.', $value))
		{
			parent::setValue(substr($value, 4));
		}
		else
		{
			parent::setValue($value);
		}
	}


	//-------------------------------------------------------------------
	// FUNCTION: validate
	//-------------------------------------------------------------------

	/** 
	 * This method checks if the domain name is in a valid format.
	 *
	 * @access public
	 * @return bool Whether or not the field has a valid domain name.
	 */

	function validate()
	{
		// make sure it is a valid domain: domainname.ext
		if (ereg('^[a-zA-Z0-9-]+\.[a-zA-Z]{2,4}$', $this->getValue()))
		{
			return true;
		}

		return false;		
	}
}

?>