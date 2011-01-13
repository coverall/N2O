<?php
// $Id: CC_Summary_Content_Provider.php,v 1.9 2003/08/21 19:41:36 patrick Exp $
//=======================================================================
// CLASS: CC_Summary_Content_Provider
//=======================================================================

/**
 * The CC_Summary class allows one to add additional columns to a summary that are not simply fields taken from the database. The actual content of these colums are provided by subclasses of this class. Classes which extend this, can provide any content they so desire. The getHTML() method returns the contents of the cell to use in place of the N2O field's getValue() method.
 * 
 * @package CC_Summary_Content_Providers
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 * @see CC_Summary::addColumn()
 */

class CC_Summary_Content_Provider
{	

	/**
     * The alignment of the column. should be "left", "center", or "right".
     *
     * @var string $alignment
     * @access private
     */	
     
	var $alignment;
	
	
	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Summary_Content_Provider
	//-------------------------------------------------------------------

	/**
	 * The constructor passes the columns alignment. Default alignment is left.
	 * 
	 * @access public
	 * @author The Crew <N2O@coverallcrew.com>
	 * @copyright Copyright &copy; 2003, Coverall Crew
	 */

	function CC_Summary_Content_Provider($alignment = 'left')
	{
		$this->alignment = $alignment;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getHTML()
	//-------------------------------------------------------------------

	/**
	 * This method is called when the summary object is rendered. Subclasses override this method to do their custom content providing.
	 * 
	 * @access public
	 * @param int $recordId Crew <N2O@coverallcrew.com>
	 * @param string $table The name of the table we are talking about.
	 * @param array $row The associative array for the current row of the summary.
	 */

	function getHTML($recordId, $table, $row)
	{
		return '';
	}


	//-------------------------------------------------------------------
	// METHOD: getHeading()
	//-------------------------------------------------------------------

	/**
	 * This method is called by the summary when it is rendering the header for the content provider. Override this if you need to do something special.
	 * 
	 * @access public
	 * @param string $heading The raw heading.
	 */

	function getHeading($heading)
	{
		return $heading;
	}
}

?>
