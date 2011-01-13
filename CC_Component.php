<?php
// $Id: CC_Component.php,v 1.14 2005/06/01 19:28:47 patrick Exp $
//=======================================================================
// CLASS: CC_Component
//=======================================================================

/** This is the super-class of all components.
 *
 * @package N2O
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

class CC_Component
{
	
	/**
     * The component's name.
     *
     * @var string $name
     * @see setName()
     * @access private
     */
    
    var $name;


	/**
     * The component's optional CSS style.
     *
     * @var string $style
     * @see setStyle()
     * @access private
     */

	var $style;
	
	
	//-------------------------------------------------------------------
	// METHOD: setName()
	//-------------------------------------------------------------------

	/**
	 * This method sets the component's name. 
	 * A unique name must be set for each component so that it may be retrieved later, and so that one component is not overwritten by another. We cannot stress how important this is. Failing to do so may cause unexpected results, as well as a frustrating debugging experience.
	 * 
	 * @access public
	 * @param string $name The name to set. 
	 */

	function setName($name)
	{
		if (!$name)
		{
			trigger_error('A component was constructed with an empty name. This will cause big problems. Stack-trace to follow.', E_USER_WARNING);
			trigger_error(getStackTrace(), E_USER_WARNING);
		}
		else if (strpos($name, '.'))
		{
			trigger_error('You have constructed a component using a "." in the name. PHP doesn\'t like this, and your components won\'t be updated. Stack-trace to follow.', E_USER_WARNING);
			trigger_error(getStackTrace(), E_USER_WARNING);
		}
		$this->name = $name;
	}


	//-------------------------------------------------------------------
	// METHOD: getName()
	//-------------------------------------------------------------------

	/**
	 * This method gets the component's name.
	 * 
	 * @access public
	 * @return string The component's unique name. 
	 * @see setName()
	 */

	function getName()
	{
		return $this->name;
	}


	//-------------------------------------------------------------------
	// METHOD: getHTML()
	//-------------------------------------------------------------------

	/**
	 * This method generates HTML for displaying the button. Sublasses override this appropriately.
	 *
	 * @access public
	 */

	function getHTML()
	{
		return 'Hello Component!';
	}


	//-------------------------------------------------------------------
	// METHOD: setStyle
	//-------------------------------------------------------------------

	/**
	 * This method sets the component's CSS style. The styles are described in cc_styles.css but can overriden for each application provided the appropriate CSS file is referenced in the application's header file(s).
	 *
	 * @access public
	 * @param string $style The CSS style to set. 
	 */

	function setStyle($style)
	{
		$this->style = $style;
	}


	//-------------------------------------------------------------------
	// METHOD: getStyle
	//-------------------------------------------------------------------

	/**
	 * This method gets the component's CSS style.
	 *
	 * @access public
	 * @return string The component's CSS style. 
	 * @see setStyle()
	 */

	function getStyle()
	{
		return $this->style;
	}


	//-------------------------------------------------------------------
	// METHOD: register
	//-------------------------------------------------------------------

	/**
	 * This is a callback method that gets called by the window when the
	 * component is registered. It's up to the component to decide which
	 * registerXXX() method it should call on the window. Should your
	 * custom component consist of multiple components, you may need to
	 * make multiple calls.
	 *
	 * @access private
	 */

	function register(&$window)
	{
		$window->registerCustomComponent($this);
	}


	//-------------------------------------------------------------------
	// METHOD: get
	//-------------------------------------------------------------------

	/**
	 * This is a callback method that gets called by the window when the
	 * component is retrieved in the else block. It's up to the component
	 * to decide if it wishes to do anything special when this happens.
	 *
	 * @access private
	 */

	function get(&$window)
	{

	}
}

?>