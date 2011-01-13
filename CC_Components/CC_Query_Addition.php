<?php
// $Id: CC_Query_Addition.php,v 1.3 2003/08/29 04:03:23 patrick Exp $
//=======================================================================
// CLASS: CC_Query_Addition
//=======================================================================

/** This class represents a query addition for the CC_Summary_Search_Component. This class is meant to be extended, and its getQueryAddition() method implemented. It should return a fragment of a where clause that will be placed inside parentheses.
  *
  * @package CC_Components
  * @access public
  * @author The Crew <N2O@coverallcrew.com>
  * @copyright Copyright &copy; 2003, Coverall Crew
  */

class CC_Query_Addition extends CC_Component
{
	//-------------------------------------------------------------------
	// METHOD: getQueryAddition()
	//-------------------------------------------------------------------

	/**
	 * This method generates HTML for displaying the button. Sublasses override this appropriately.
	 *
	 * @access public
	 */

	function getQueryAddition()
	{
		return '';
	}


	//-------------------------------------------------------------------
	// METHOD: register
	//-------------------------------------------------------------------

	/**
	 * This is a callback method that gets called by the window when the
	 * component is registered.
	 *
	 * @access public
	 */

	function register(&$window)
	{

	}

}

?>