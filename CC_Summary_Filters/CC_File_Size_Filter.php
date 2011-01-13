<?php
// $Id: CC_File_Size_Filter.php,v 1.1 2004/02/06 00:13:36 patrick Exp $
//=======================================================================
// CLASS: CC_File_Size_Filter
//=======================================================================

/**
 * This CC_Summary_Filter filters text values by shortening them to the passed length.
 * 
 * @package CC_Summary_Filters
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

class CC_File_Size_Filter extends CC_Summary_Filter
{
	/**
	 * The divisor to use when calculating KBs and MBs.
	 *
	 * @var int $divisor
	 * @access private
	 */

	var $divisor;

	
	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_File_Size_Filter()
	//-------------------------------------------------------------------
	
	/**
	 * A divisor value to use when calcuating KBs and MBs.
	 *
	 * @param int $divisor
	 * @access private
	 */

	function CC_File_Size_Filter($divisor = 1024)
	{
		$this->divisor = $divisor;
		$this->setCenterAlignment();
	}


	//-------------------------------------------------------------------
	// METHOD: processValue()
	//-------------------------------------------------------------------

	/**
	 * This method formats a passed file size.
	 * 
	 * @access public
	 * @param string $textToShorten The text to shorten. 
	 * @return string A shortened string suffixed with '...' to indicate that it has been edited.
	 */

	function processValue($filesize)
	{
		if ($filesize < $this->divisor)
		{
			$filesizeFormatted = $filesize . ' bytes';
		}
		else if ($filesize < ($this->divisor * $this->divisor))
		{
			$filesizeFormatted = number_format($filesize / $this->divisor) . ' KB';
		}
		else
		{
			$filesizeFormatted = number_format($filesize / ($this->divisor * $this->divisor), 2) . ' MB';
		}
		
		return $filesizeFormatted;
	}
}

?>