<?php
// $Id: CC_ProvinceState_Field.php,v 1.21 2010/11/11 04:28:32 patrick Exp $
//=======================================================================
// CLASS: CC_ProvinceState_Field
//=======================================================================

/**
 * The CC_ProvinceState_Field is a CC_SelectList_Field field with all Canada's provinces and territories as well as all the U.S. states as its selection options.
 *
 * In addition to what's supported globally by all CC_Fields (see documentation), this field supports the following arguments for the fourth argument of CC_FieldManager's addField() method:
 *
 * type=[province|state|stateprovince|provincestate] - this argument contols what is displayed in this field. The default is "provincestate" which shows both provinces and states with provinces first.
 * includeNA=[0|1] - whether or not we should include "N/A" in the list
 * NAFirst=[0|1] - whether or not the "N/A" should be at the top of the list
 * abbreviated=[0|1] - whether or not the values of the provinces/states will be saved using the two-letter abbreviations.
 *
 * @package CC_Fields
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

class CC_ProvinceState_Field extends CC_SelectList_Field
{	
	/**
     * Whether or not to include the US states.
     *
     * @var bool $_includeStates
     * @access private
     */	
	var $_includeStates = true;


	/**
     * Whether or not to include the Canadian provinces.
     *
     * @var bool $_includeProvinces
     * @access private
     */	
	var $_includeProvinces = true;


	/**
     * Whether or not to show provinces first.
     *
     * @var string $_provincesFirst
     * @access private
     */		
	var $_provincesFirst = true;


	/**
     * Whether or not to show abbreviated names representing the field's value.
     *
     * @var bool $_abbreviated
     * @access private
     */	
	var $_abbreviated = false;


	/**
     * Whether or not to include 'Not Applicable' in the list.
     *
     * @var bool $_includeNA
     * @access private
     */		
	var $_includeNA = false;


	/**
     * Whether or not to show 'Not Applicable' at the end or the beginning of the list.
     *
     * @var bool $_NAFirst
     * @access private
     */		
	var $_NAFirst = false;
	

	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_ProvinceState_Field
	//-------------------------------------------------------------------
	
	/** 
	 * The CC_ProvinceState_Field constructor sets its values here, yo. 
	 *
	 * @access public
	 * @param string $name The unique name of the field. Names must be unique so that N2O knows which fields to update when users submit data.
	 * @param string $label A text label to accompany the field to describe which input is either expected or displayed.
	 * @param bool $required Whether or not this field must contain data from the user before proceeding. Not required by default.
	 * @param string $defaultValue The value the field should contain before user's submit input. The field is set to '- Select Province/State -' by default.
	 */

	function CC_ProvinceState_Field($name, $label, $required = false, $defaultValue = '')
	{
		global $application;
		
		$this->CC_SelectList_Field($name, $label, $required, $defaultValue);
		$this->setEscapeValues(false);
	}
	
	
	//-------------------------------------------------------------------
	// FUNCTION: getProvinceArray
	//-------------------------------------------------------------------
	
	/** 
	 * The province array returner. 
	 *
	 * @access private
	 * @param array An array of provinces, if the field is set to include them.
	 */
	
	function getProvinceArray()
	{
		global $application;
		
		if ($this->_includeProvinces)
		{
			if ($this->_abbreviated)
			{
				switch ($application->getLanguage())
				{
					case 'French':
					{
						return array(array('AB', 'Alberta'), array('BC', 'Colombie-Britannique'), array('PE', '&Icirc;le-du-Prince-&Eacute;douard'), array('MB', 'Manitoba'), array('NB', 'Nouveau-Brunswick'), array('NS', 'Nouvelle-&Eacute;cosse'), array('NU', 'Nunavut'), array('ON', 'Ontario'), array('QC', 'Qu&eacute;bec'), array('SK', 'Saskatchewan'), array('NF', 'Terre-Neuve-et-Labrador'), array('NT', 'Territoires du Nord-Ouest'), array('YT', 'Yukon'));
					}
					break;
					
					default:
					{
						return array(array('AB', 'Alberta'), array('BC', 'British Columbia'), array('MB', 'Manitoba'), array('NB', 'New Brunswick'), array('NF', 'Newfoundland'), array('NT', 'Northwest Territories'), array('NS', 'Nova Scotia'), array('NU', 'Nunavut'), array('ON', 'Ontario'), array('PE', 'Prince Edward Island'), array('QC', 'Quebec'), array('SK', 'Saskatchewan'), array('YT', 'Yukon'));
					}
				}
			}
			else
			{
				switch ($application->getLanguage())
				{
					case 'French':
					{
						return array('Alberta', 'Colombie-Britannique', '&Icirc;le-du-Prince-&Eacute;douard', 'Manitoba', 'Nouveau-Brunswick', 'Nouvelle-&Eacute;cosse', 'Nunavut', 'Ontario', 'Qu&eacute;bec', 'Saskatchewan', 'Terre-Neuve-et-Labrador', 'Territoires du Nord-Ouest', 'Yukon');
					}
					break;
	
					default:
					{
						return array('Alberta', 'British Columbia', 'Manitoba', 'New Brunswick', 'Newfoundland', 'Northwest Territories', 'Nova Scotia', 'Nunavut', 'Ontario', 'Prince Edward Island', 'Quebec', 'Saskatchewan', 'Yukon');
					}
				}
			}
		}
		else
		{
			return array();
		}
	}
	
	//-------------------------------------------------------------------
	// FUNCTION: getStateArray
	//-------------------------------------------------------------------
		
	/** 
	 * The state array returner. 
	 *
	 * @access private
	 * @param array An array of states, if the field is set to include them.
	 */

	function getStateArray()
	{
		if ($this->_includeStates)
		{
			if ($this->_abbreviated)
			{
				return array(array('AL', 'Alabama'), array('AK', 'Alaska'), array('AZ', 'Arizona'), array('AR', 'Arkansas'), array('CA', 'California'), array('CO', 'Colorado'), array('CT', 'Connecticut'), array('DE', 'Delaware'), array('DC', 'District of Columbia'), array('FL', 'Florida'), array('GA', 'Georgia'), array('HI', 'Hawaii'), array('ID', 'Idaho'), array('IL', 'Illinois'), array('IN', 'Indiana'), array('IA', 'Iowa'), array('KS', 'Kansas'), array('KY', 'Kentucky'), array('LA', 'Louisiana'), array('ME', 'Maine'), array('MD', 'Maryland'), array('MA', 'Massachusetts'), array('MI', 'Michigan'), array('MN', 'Minnesota'), array('MS', 'Mississippi'), array('MO', 'Missouri'), array('MT', 'Montana'), array('NE', 'Nebraska'), array('NV', 'Nevada'), array('NH', 'New Hampshire'), array('NJ', 'New Jersey'), array('NM', 'New Mexico'), array('NY', 'New York'), array('NC', 'North Carolina'), array('ND', 'North Dakota'), array('OH', 'Ohio'), array('OK', 'Oklahoma'), array('OR', 'Oregon'), array('PA', 'Pennsylvania'), array('RI', 'Rhode Island'), array('SC', 'South Carolina'), array('SD', 'South Dakota'), array('TN', 'Tennessee'), array('TX', 'Texas'), array('UT', 'Utah'), array('VT', 'Vermont'), array('VA', 'Virginia'), array('WA', 'Washington'), array('WV', 'West Virginia'), array('WI', 'Wisconsin'), array('WY', 'Wyoming'));
			}
			else
			{
				return array('Alabama', 'Alaska', 'Arizona', 'Arkansas', 'California', 'Colorado', 'Connecticut', 'Delaware', 'District of Columbia', 'Florida', 'Georgia', 'Hawaii', 'Idaho', 'Illinois', 'Indiana', 'Iowa', 'Kansas', 'Kentucky', 'Louisiana', 'Maine', 'Maryland', 'Massachusetts', 'Michigan', 'Minnesota', 'Mississippi', 'Missouri', 'Montana', 'Nebraska', 'Nevada', 'New Hampshire', 'New Jersey', 'New Mexico', 'New York', 'North Carolina', 'North Dakota', 'Ohio', 'Oklahoma', 'Oregon', 'Pennsylvania', 'Rhode Island', 'South Carolina', 'South Dakota', 'Tennessee', 'Texas', 'Utah', 'Vermont', 'Virginia', 'Washington', 'West Virginia', 'Wisconsin', 'Wyoming');
			}
		}
		else
		{
			return array();
		}
	}
	//-------------------------------------------------------------------
	// METHOD: setIncludeStates()
	//-------------------------------------------------------------------
	
	/** 
	 * Whether or not to include US states in the select list. 
	 *
	 * @access public
	 * @param bool $includeStates Whether or not to include the state.
	 */
	 
	function setIncludeStates($includeStates, $statesFirst = false)
	{
	 	$this->_includeStates = $includeStates;
		
		if ($statesFirst)
		{
			$this->_provincesFirst = !$statesFirst;
		}
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setIncludeProvinces()
	//-------------------------------------------------------------------
	
	/** 
	 * Whether or not to include Canadian provinces in the select list. 
	 *
	 * @access public
	 * @param bool $includeStates Whether or not to include the state.
	 */
	 
	function setIncludeProvinces($includeProvinces, $provincesFirst = false)
	{
		$this->_includeProvinces = $includeProvinces;
		
		if ($provincesFirst)
		{
			$this->_provincesFirst = $provincesFirst;
		}
	} 
	
	
	//-------------------------------------------------------------------
	// METHOD: setAbbreviated()
	//-------------------------------------------------------------------
	
	/** 
	 * Whether or not to abbreviate values for states and provinces. 
	 *
	 * @access public
	 * @param bool $abbreviated Whether or not to abbreviated state/province values.
	 */
	 
	function setAbbreviated($abbreviated)
	{
		$this->_abbreviated = $abbreviated;
	} 
	 
	 
	//-------------------------------------------------------------------
	// METHOD: setIncludeNA()
	//-------------------------------------------------------------------
	
	/** 
	 * Whether or not to include US states in the select list. 
 	 *
	 * @access public
	 * @param bool $includeStates Whether or not to include the state.
	 */
	 
	function setIncludeNA($includeNA, $NAFirst = false)
	{
		$this->_includeNA = $includeNA;
		$this->_NAFirst = $NAFirst;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getOptions()
	//-------------------------------------------------------------------
	
	/** 
	 * update the select list options based on the class' parameters.
 	 *
	 * @access public
	 */
	 
	function getOptions()
	{
		if ($this->_provincesFirst)
		{
			$options = array_merge($this->getProvinceArray(), $this->getStateArray());
		}
		else
		{
			$options = array_merge($this->getStateArray(), $this->getProvinceArray());
		}

		if ($this->_includeNA)
		{
			if ($this->_abbreviated)
			{
				$naArray = array(array('XX', 'Not Applicable'));
			}
			else
			{
				$naArray = array('Not Applicable');
			}
			
			if ($this->_NAFirst)
			{
				$options = array_merge($naArray, $options);
			}
			else
			{
				$options = array_merge($options, $naArray);
			}
		}

		return $options;
	}


	//-------------------------------------------------------------------
	// METHOD: register()
	//-------------------------------------------------------------------
	 
	/**
	 * This is a callback method that gets called by the window when the
	 * component is registered. It's up to the component to dece which
	 * registerXXX() method it should call on the window. Should your
	 * custom component consist of multiple components, you may need to
	 * make multiple calls.
	 *
	 * @access public
	 */

	function register(&$window)
	{
		global $application;

		parent::register($window);
		
		switch ($application->getLanguage())
		{
			case 'French':
			{
				if ($this->_includeProvinces && $this->_includeStates)
				{
					$this->setUnselectedValue('- S&eacute;l&eacute;ctionnez un &Eacute;tat/Province -');
				}
				else if ($this->_includeProvinces && !$this->_includeStates)
				{
					$this->setUnselectedValue('- S&eacute;l&eacute;ctionnez un Province -');
				}
				else if (!$this->_includeProvinces && $this->_includeStates)
				{
					$this->setUnselectedValue('- S&eacute;l&eacute;ctionnez un &Eacute;tat -');
				}
			}
			break;
			
			default:
			{
				if ($this->_includeProvinces && $this->_includeStates)
				{
					$this->setUnselectedValue('- Select Province/State -');
				}
				else if ($this->_includeProvinces && !$this->_includeStates)
				{
					$this->setUnselectedValue('- Select Province -');
				}
				else if (!$this->_includeProvinces && $this->_includeStates)
				{
					$this->setUnselectedValue('- Select State -');
				}
			}
		}
	} 


	//-------------------------------------------------------------------
	// STATIC METHOD: getInstance
	//-------------------------------------------------------------------

	/**
	 * This is a static method called by CC_Record when it needs an instance
	 * of a field. The implementing field needs to return a constructed
	 * instance of itself.
	 *
	 * @access public
	 */

	static function &getInstance($className, $name, $label, $value, $args, $required)
	{
		$field = new $className($name, $label, $required, $value);

		$type = (isset($args->type) ? $args->type : false);
		$includeNA = (isset($args->includeNA) ? $args->includeNA : false);
		$NAFirst = (isset($args->NAFirst) ? $args->NAFirst : false);
		$abbreviated = (isset($args->abbreviated) ? $args->abbreviated : false);
		
		switch ($type)
		{
			case 'province':
			{
				$field->setIncludeProvinces(true);
				$field->setIncludeStates(false);
			}
			break;
			
			case 'state':
			{
				$field->setIncludeProvinces(false);
				$field->setIncludeStates(true);
			}
			break;
			
			case 'stateprovince':
			{
				$field->setIncludeProvinces(true, false);
				$field->setIncludeStates(true, true);
			}
			break;
			
			default:
			{
				$field->setIncludeProvinces(true, true);
				$field->setIncludeStates(true, false);
			}
		}
		
		$field->setIncludeNA($includeNA, $NAFirst);
		$field->setAbbreviated($abbreviated);
		
		unset($type, $includeNA, $NAFirst, $abbreviated);

		return $field;
	}

}

?>