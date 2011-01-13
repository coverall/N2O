<?php
// $Id: CC_Text_Shortening_Filter.php,v 1.5 2003/07/06 01:31:34 jamie Exp $
//=======================================================================
// CLASS: CC_Text_Shortening_Filter
//=======================================================================

/**
 * This CC_Summary_Filter filters text values by shortening them to the passed length.
 * 
 * @package CC_Summary_Filters
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

class CC_Text_Shortening_Filter extends CC_Summary_Filter
{
	/**
	 * The length the text should be shortened to.
	 *
	 * @var int $textLength
	 * @access private
	 */

	var $textLength;
	
	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Text_Shortening_Filter()
	//-------------------------------------------------------------------
	
	/**
	 * An integer value is passed indicating at what length to shorten the text.
	 *
	 * @param int $textLength
	 * @access private
	 */

	function CC_Text_Shortening_Filter($textLength = 34)
	{
		$this->textLength = $textLength;
	}
	
	//-------------------------------------------------------------------
	// METHOD: processValue()
	//-------------------------------------------------------------------

	/**
	 * This method filters the passed text to the length indicated by $textLength.
	 * 
	 * @access public
	 * @param string $textToShorten The text to shorten. 
	 * @return string A shortened string suffixed with '...' to indicate that it has been edited.
	 */

	function processValue($textToShorten)
	{
		if (strlen($textToShorten) > $this->textLength)
		{
			$shortString = substr($textToShorten, 0, $this->textLength - 3);
			
			return substr($shortString, 0, strrpos($shortString, ' ')) . "...";
		}
		else
		{
			return $textToShorten;
		}
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: textFriendlyProcessValue()
	//-------------------------------------------------------------------

	/**
	 * This method doesn't seem to filter anything.
	 * 
	 * @access public
	 * @param string $textToShorten The text to shorten. 
	 * @return string A shortened string suffixed with '...' to indicate that it has been edited.
	 * @todo What up with this method? It doesn't do nuttin'.
	 */

	function textFriendlyProcessValue($textToShorten)
	{
		return $textToShorten;
	}
}

?>