<?php
// $Id: CC_ISO_Country_Field.php,v 1.14 2007/01/07 19:39:28 mike Exp $
//=======================================================================
// CLASS: CC_ISO_Country_Field
//=======================================================================

/**
 * The CC_ISO_Country_Field is a CC_SelectList_Field field with all the world's countries as its selection options in ISO format.
 *
 * @package CC_Fields
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

class CC_ISO_Country_Field extends CC_SelectList_Field
{	
	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_ISO_Country_Field
	//-------------------------------------------------------------------
	
	/** 
	 * The CC_ISO_Country_Field constructor sets its values here, yo, and defines the list of countries here as well in ISO format. 
	 *
	 * @access public
	 * @param string $name The unique name of the field. Names must be unique so that N2O knows which fields to update when users submit data.
	 * @param string $label A text label to accompany the field to describe which input is either expected or displayed.
	 * @param bool $required Whether or not this field must contain data from the user before proceeding. Not required by default.
	 * @param string $defaultValue The value the field should contain before user's submit input. The field is set to '-Select Country-' by default.
	 */
	 
	function CC_ISO_Country_Field($name, $label, $required = false, $defaultValue = '', $unselectedValue = ' - Select - ', $theOptions = null)
	{
		global $application;
		
		switch ($application->getLanguage())
		{	
			case 'French':
			{
				if ($unselectedValue == ' - Select - ')
				{
					$unselectedValue = '- S&eacute;l&eacute;ctionnez un Pays -';
				}
			}
		}

		$this->CC_SelectList_Field($name, $label, $required, $defaultValue, $unselectedValue);
		$this->setEscapeValues(false);
	}


	//-------------------------------------------------------------------
	// METHOD: getOptions
	//-------------------------------------------------------------------
	
	function getOptions()
	{
		global $application;
		
		$countries = array();

		switch ($application->getLanguage())
		{	
			case 'French':
			{				
				$countries[] = array('US', '&Eacute;tats-Unis');
				$countries[] = array('CA', 'Canada');
				$countries[] = array('AD', 'Andorra');
				$countries[] = array('AF', 'Afghanistan');
				$countries[] = array('AG', 'Antigua & Barbuda');
				$countries[] = array('AI', 'Anguilla');
				$countries[] = array('AL', 'Albania');
				$countries[] = array('AM', 'Armenia');
				$countries[] = array('AN', 'Netherlands Antilles');
				$countries[] = array('AO', 'Angola');
				$countries[] = array('AQ', 'Antarctica');
				$countries[] = array('AR', 'Argentina');
				$countries[] = array('AS', 'American Samoa');
				$countries[] = array('AT', 'Austria');
				$countries[] = array('AU', 'Australia');
				$countries[] = array('AW', 'Aruba');
				$countries[] = array('AZ', 'Azerbaijan');
				$countries[] = array('BA', 'Bosnia and Herzegovina');
				$countries[] = array('BB', 'Barbados');
				$countries[] = array('BD', 'Bangladesh');
				$countries[] = array('BE', 'Belgium');
				$countries[] = array('BF', 'Burkina Faso');
				$countries[] = array('BG', 'Bulgaria');
				$countries[] = array('BH', 'Bahrain');
				$countries[] = array('BI', 'Burundi');
				$countries[] = array('BJ', 'Benin');
				$countries[] = array('BM', 'Bermuda');
				$countries[] = array('BN', 'Brunei Darussalam');
				$countries[] = array('BO', 'Bolivia');
				$countries[] = array('BR', 'Brazil');
				$countries[] = array('BS', 'Bahama');
				$countries[] = array('BT', 'Bhutan');
				$countries[] = array('BU', 'Burma');
				$countries[] = array('BV', 'Bouvet Island');
				$countries[] = array('BW', 'Botswana');
				$countries[] = array('BY', 'Belarus');
				$countries[] = array('BZ', 'Belize');
				$countries[] = array('CC', 'Cocos (Keeling) Islands');
				$countries[] = array('CF', 'Central African Republic');
				$countries[] = array('CG', 'Congo');
				$countries[] = array('CH', 'Switzerland');
				$countries[] = array('CI', 'C&ocirc;te D\'ivoire (Ivory Coast)');
				$countries[] = array('CK', 'Cook Islands');
				$countries[] = array('CL', 'Chile');
				$countries[] = array('CM', 'Cameroon');
				$countries[] = array('CN', 'China');
				$countries[] = array('CO', 'Colombia');
				$countries[] = array('CR', 'Costa Rica');
				$countries[] = array('CU', 'Cuba');
				$countries[] = array('CV', 'Cape Verde');
				$countries[] = array('CX', 'Christmas Island');
				$countries[] = array('CY', 'Cyprus');
				$countries[] = array('CZ', 'Czech Republic');
				$countries[] = array('DE', 'Germany');
				$countries[] = array('DJ', 'Djibouti');
				$countries[] = array('DK', 'Denmark');
				$countries[] = array('DM', 'Dominica');
				$countries[] = array('DO', 'Dominican Republic');
				$countries[] = array('DZ', 'Algeria');
				$countries[] = array('EC', 'Ecuador');
				$countries[] = array('EE', 'Estonia');
				$countries[] = array('EG', 'Egypt');
				$countries[] = array('EH', 'Western Sahara');
				$countries[] = array('ER', 'Eritrea');
				$countries[] = array('ES', 'Spain');
				$countries[] = array('ET', 'Ethiopia');
				$countries[] = array('FI', 'Finland');
				$countries[] = array('FJ', 'Fiji');
				$countries[] = array('FK', 'Falkland Islands (Malvinas)');
				$countries[] = array('FM', 'Micronesia');
				$countries[] = array('FO', 'Faroe Islands');
				$countries[] = array('FR', 'France');
				$countries[] = array('FX', 'France, Metropolitan');
				$countries[] = array('GA', 'Gabon');
				$countries[] = array('GD', 'Grenada');
				$countries[] = array('GE', 'Georgia');
				$countries[] = array('GF', 'French Guiana');
				$countries[] = array('GH', 'Ghana');
				$countries[] = array('GI', 'Gibraltar');
				$countries[] = array('GL', 'Greenland');
				$countries[] = array('GM', 'Gambia');
				$countries[] = array('GN', 'Guinea');
				$countries[] = array('GP', 'Guadeloupe');
				$countries[] = array('GQ', 'Equatorial Guinea');
				$countries[] = array('GR', 'Greece');
				$countries[] = array('GT', 'Guatemala');
				$countries[] = array('GU', 'Guam');
				$countries[] = array('GW', 'Guinea-Bissau');
				$countries[] = array('GY', 'Guyana');
				$countries[] = array('HK', 'Hong Kong');
				$countries[] = array('HM', 'Heard & McDonald Islands');
				$countries[] = array('HN', 'Honduras');
				$countries[] = array('HR', 'Croatia');
				$countries[] = array('HT', 'Haiti');
				$countries[] = array('HU', 'Hungary');
				$countries[] = array('ID', 'Indonesia');
				$countries[] = array('IE', 'Ireland');
				$countries[] = array('IL', 'Israel');
				$countries[] = array('IN', 'India');
				$countries[] = array('IO', 'British Indian Ocean Territory');
				$countries[] = array('IQ', 'Iraq');
				$countries[] = array('IR', 'Islamic Republic of Iran');
				$countries[] = array('IS', 'Iceland');
				$countries[] = array('IT', 'Italy');
				$countries[] = array('JM', 'Jamaica');
				$countries[] = array('JO', 'Jordan');
				$countries[] = array('JP', 'Japan');
				$countries[] = array('KE', 'Kenya');
				$countries[] = array('KG', 'Kyrgyzstan');
				$countries[] = array('KH', 'Cambodia');
				$countries[] = array('KI', 'Kiribati');
				$countries[] = array('KM', 'Comoros');
				$countries[] = array('KN', 'St. Kitts and Nevis');
				$countries[] = array('KP', 'North Korea');
				$countries[] = array('KR', 'South Korea');
				$countries[] = array('KW', 'Kuwait');
				$countries[] = array('KY', 'Cayman Islands');
				$countries[] = array('KZ', 'Kazakhstan');
				$countries[] = array('LA', 'Laos');
				$countries[] = array('LB', 'Lebanon');
				$countries[] = array('LC', 'Saint Lucia');
				$countries[] = array('LI', 'Liechtenstein');
				$countries[] = array('LK', 'Sri Lanka');
				$countries[] = array('LR', 'Liberia');
				$countries[] = array('LS', 'Lesotho');
				$countries[] = array('LT', 'Lithuania');
				$countries[] = array('LU', 'Luxembourg');
				$countries[] = array('LV', 'Latvia');
				$countries[] = array('LY', 'Libyan Arab Jamahiriya');
				$countries[] = array('MA', 'Morocco');
				$countries[] = array('MC', 'Monaco');
				$countries[] = array('MD', 'Moldova, Republic of');
				$countries[] = array('MG', 'Madagascar');
				$countries[] = array('MH', 'Marshall Islands');
				$countries[] = array('ML', 'Mali');
				$countries[] = array('MN', 'Mongolia');
				$countries[] = array('MM', 'Myanmar');
				$countries[] = array('MO', 'Macau');
				$countries[] = array('MP', 'Northern Mariana Islands');
				$countries[] = array('MQ', 'Martinique');
				$countries[] = array('MR', 'Mauritania');
				$countries[] = array('MS', 'Monserrat');
				$countries[] = array('MT', 'Malta');
				$countries[] = array('MU', 'Mauritius');
				$countries[] = array('MV', 'Maldives');
				$countries[] = array('MW', 'Malawi');
				$countries[] = array('MX', 'Mexico');
				$countries[] = array('MY', 'Malaysia');
				$countries[] = array('MZ', 'Mozambique');
				$countries[] = array('NA', 'Nambia');
				$countries[] = array('NC', 'New Caledonia');
				$countries[] = array('NE', 'Niger');
				$countries[] = array('NF', 'Norfolk Island');
				$countries[] = array('NG', 'Nigeria');
				$countries[] = array('NI', 'Nicaragua');
				$countries[] = array('NL', 'Netherlands');
				$countries[] = array('NO', 'Norway');
				$countries[] = array('NP', 'Nepal');
				$countries[] = array('NR', 'Nauru');
				$countries[] = array('NU', 'Niue');
				$countries[] = array('NZ', 'New Zealand');
				$countries[] = array('OM', 'Oman');
				$countries[] = array('PA', 'Panama');
				$countries[] = array('PE', 'Peru');
				$countries[] = array('PF', 'French Polynesia');
				$countries[] = array('PG', 'Papua New Guinea');
				$countries[] = array('PH', 'Philippines');
				$countries[] = array('PK', 'Pakistan');
				$countries[] = array('PL', 'Poland');
				$countries[] = array('PM', 'St. Pierre & Miquelon');
				$countries[] = array('PN', 'Pitcairn');
				$countries[] = array('PR', 'Puerto Rico');
				$countries[] = array('PT', 'Portugal');
				$countries[] = array('PW', 'Palau');
				$countries[] = array('PY', 'Paraguay');
				$countries[] = array('QA', 'Qatar');
				$countries[] = array('RE', 'R&eacute;union');
				$countries[] = array('RO', 'Romania');
				$countries[] = array('RU', 'Russian Federation');
				$countries[] = array('RW', 'Rwanda');
				$countries[] = array('SA', 'Saudi Arabia');
				$countries[] = array('SB', 'Solomon Islands');
				$countries[] = array('SC', 'Seychelles');
				$countries[] = array('SD', 'Sudan');
				$countries[] = array('SE', 'Sweden');
				$countries[] = array('SG', 'Singapore');
				$countries[] = array('SH', 'St. Helena');
				$countries[] = array('SI', 'Slovenia');
				$countries[] = array('SJ', 'Svalbard & Jan Mayen Islands');
				$countries[] = array('SK', 'Slovakia');
				$countries[] = array('SL', 'Sierra Leone');
				$countries[] = array('SM', 'San Marino');
				$countries[] = array('SN', 'Senegal');
				$countries[] = array('SO', 'Somalia');
				$countries[] = array('SR', 'Suriname');
				$countries[] = array('ST', 'Sao Tome & Principe');
				$countries[] = array('SV', 'El Salvador');
				$countries[] = array('SY', 'Syrian Arab Republic');
				$countries[] = array('SZ', 'Swaziland');
				$countries[] = array('TC', 'Turks & Caicos Islands');
				$countries[] = array('TD', 'Chad');
				$countries[] = array('TF', 'French Southern Territories');
				$countries[] = array('TG', 'Togo');
				$countries[] = array('TH', 'Thailand');
				$countries[] = array('TJ', 'Tajikistan');
				$countries[] = array('TK', 'Tokelau');
				$countries[] = array('TM', 'Turkmenistan');
				$countries[] = array('TN', 'Tunisia');
				$countries[] = array('TO', 'Tonga');
				$countries[] = array('TP', 'East Timor');
				$countries[] = array('TR', 'Turkey');
				$countries[] = array('TT', 'Trinidad & Tobago');
				$countries[] = array('TV', 'Tuvalu');
				$countries[] = array('TW', 'Taiwan');
				$countries[] = array('TZ', 'Tanzania, United Republic of');
				$countries[] = array('UA', 'Ukraine');
				$countries[] = array('UG', 'Uganda');
				$countries[] = array('AE', 'United Arab Emirates');
				$countries[] = array('GB', 'United Kingdom (Great Britain)');
				$countries[] = array('UM', 'United States Minor Outlying Islands');
				$countries[] = array('UY', 'Uruguay');
				$countries[] = array('UZ', 'Uzbekistan');
				$countries[] = array('VA', 'Vatican City State');
				$countries[] = array('VC', 'St. Vincent & the Grenadines');
				$countries[] = array('VE', 'Venezuela');
				$countries[] = array('VG', 'Virgin Islands (British)');
				$countries[] = array('VI', 'Virgin Islands (United States)');
				$countries[] = array('VN', 'Viet Nam');
				$countries[] = array('VU', 'Vanuatu');
				$countries[] = array('WF', 'Wallis & Futuna Islands');
				$countries[] = array('WS', 'Samoa');
				$countries[] = array('YE', 'Yemen');
				$countries[] = array('YT', 'Mayotte');
				$countries[] = array('YU', 'Yugoslavia');
				$countries[] = array('ZA', 'South Africa');
				$countries[] = array('ZM', 'Zambia');
				$countries[] = array('ZR', 'Zaire');
				$countries[] = array('ZW', 'Zimbabwe');
			}
			break;
			
			default:
			{
				
				$countries[] = array('US', 'United States');
				$countries[] = array('CA', 'Canada');
				$countries[] = array('AF', 'Afghanistan');
				$countries[] = array('AL', 'Albania');
				$countries[] = array('DZ', 'Algeria');
				$countries[] = array('AS', 'American Samoa');
				$countries[] = array('AD', 'Andorra');
				$countries[] = array('AO', 'Angola');
				$countries[] = array('AI', 'Anguilla');
				$countries[] = array('AQ', 'Antarctica');
				$countries[] = array('AG', 'Antigua & Barbuda');
				$countries[] = array('AR', 'Argentina');
				$countries[] = array('AM', 'Armenia');
				$countries[] = array('AW', 'Aruba');
				$countries[] = array('AU', 'Australia');
				$countries[] = array('AT', 'Austria');
				$countries[] = array('AZ', 'Azerbaijan');
				$countries[] = array('BS', 'Bahama');
				$countries[] = array('BH', 'Bahrain');
				$countries[] = array('BD', 'Bangladesh');
				$countries[] = array('BB', 'Barbados');
				$countries[] = array('BY', 'Belarus');
				$countries[] = array('BE', 'Belgium');
				$countries[] = array('BZ', 'Belize');
				$countries[] = array('BJ', 'Benin');
				$countries[] = array('BM', 'Bermuda');
				$countries[] = array('BT', 'Bhutan');
				$countries[] = array('BO', 'Bolivia');
				$countries[] = array('BA', 'Bosnia and Herzegovina');
				$countries[] = array('BW', 'Botswana');
				$countries[] = array('BV', 'Bouvet Island');
				$countries[] = array('BR', 'Brazil');
				$countries[] = array('IO', 'British Indian Ocean Territory');
				$countries[] = array('BN', 'Brunei Darussalam');
				$countries[] = array('BG', 'Bulgaria');
				$countries[] = array('BF', 'Burkina Faso');
				$countries[] = array('BU', 'Burma');
				$countries[] = array('BI', 'Burundi');
				$countries[] = array('CI', 'C&ocirc;te D\'ivoire (Ivory Coast)');
				$countries[] = array('KH', 'Cambodia');
				$countries[] = array('CM', 'Cameroon');
				$countries[] = array('CV', 'Cape Verde');
				$countries[] = array('KY', 'Cayman Islands');
				$countries[] = array('CF', 'Central African Republic');
				$countries[] = array('TD', 'Chad');
				$countries[] = array('CL', 'Chile');
				$countries[] = array('CN', 'China');
				$countries[] = array('CX', 'Christmas Island');
				$countries[] = array('CC', 'Cocos (Keeling) Islands');
				$countries[] = array('CO', 'Colombia');
				$countries[] = array('KM', 'Comoros');
				$countries[] = array('CG', 'Congo');
				$countries[] = array('CK', 'Cook Islands');
				$countries[] = array('CR', 'Costa Rica');
				$countries[] = array('HR', 'Croatia');
				$countries[] = array('CU', 'Cuba');
				$countries[] = array('CY', 'Cyprus');
				$countries[] = array('CZ', 'Czech Republic');
				$countries[] = array('DK', 'Denmark');
				$countries[] = array('DJ', 'Djibouti');
				$countries[] = array('DM', 'Dominica');
				$countries[] = array('DO', 'Dominican Republic');
				$countries[] = array('TP', 'East Timor');
				$countries[] = array('EC', 'Ecuador');
				$countries[] = array('EG', 'Egypt');
				$countries[] = array('SV', 'El Salvador');
				$countries[] = array('GQ', 'Equatorial Guinea');
				$countries[] = array('ER', 'Eritrea');
				$countries[] = array('EE', 'Estonia');
				$countries[] = array('ET', 'Ethiopia');
				$countries[] = array('FK', 'Falkland Islands (Malvinas)');
				$countries[] = array('FO', 'Faroe Islands');
				$countries[] = array('FJ', 'Fiji');
				$countries[] = array('FI', 'Finland');
				$countries[] = array('FR', 'France');
				$countries[] = array('FX', 'France, Metropolitan');
				$countries[] = array('GF', 'French Guiana');
				$countries[] = array('PF', 'French Polynesia');
				$countries[] = array('TF', 'French Southern Territories');
				$countries[] = array('GA', 'Gabon');
				$countries[] = array('GM', 'Gambia');
				$countries[] = array('GE', 'Georgia');
				$countries[] = array('DE', 'Germany');
				$countries[] = array('GH', 'Ghana');
				$countries[] = array('GI', 'Gibraltar');
				$countries[] = array('GR', 'Greece');
				$countries[] = array('GL', 'Greenland');
				$countries[] = array('GD', 'Grenada');
				$countries[] = array('GP', 'Guadeloupe');
				$countries[] = array('GU', 'Guam');
				$countries[] = array('GT', 'Guatemala');
				$countries[] = array('GN', 'Guinea');
				$countries[] = array('GW', 'Guinea-Bissau');
				$countries[] = array('GY', 'Guyana');
				$countries[] = array('HT', 'Haiti');
				$countries[] = array('HM', 'Heard & McDonald Islands');
				$countries[] = array('HN', 'Honduras');
				$countries[] = array('HK', 'Hong Kong');
				$countries[] = array('HU', 'Hungary');
				$countries[] = array('IS', 'Iceland');
				$countries[] = array('IN', 'India');
				$countries[] = array('ID', 'Indonesia');
				$countries[] = array('IQ', 'Iraq');
				$countries[] = array('IE', 'Ireland');
				$countries[] = array('IR', 'Islamic Republic of Iran');
				$countries[] = array('IL', 'Israel');
				$countries[] = array('IT', 'Italy');
				$countries[] = array('JM', 'Jamaica');
				$countries[] = array('JP', 'Japan');
				$countries[] = array('JO', 'Jordan');
				$countries[] = array('KZ', 'Kazakhstan');
				$countries[] = array('KE', 'Kenya');
				$countries[] = array('KI', 'Kiribati');
				$countries[] = array('KP', 'North Korea');
				$countries[] = array('KR', 'South Korea');
				$countries[] = array('KW', 'Kuwait');
				$countries[] = array('KG', 'Kyrgyzstan');
				$countries[] = array('LA', 'Laos');
				$countries[] = array('LV', 'Latvia');
				$countries[] = array('LB', 'Lebanon');
				$countries[] = array('LS', 'Lesotho');
				$countries[] = array('LR', 'Liberia');
				$countries[] = array('LY', 'Libyan Arab Jamahiriya');
				$countries[] = array('LI', 'Liechtenstein');
				$countries[] = array('LT', 'Lithuania');
				$countries[] = array('LU', 'Luxembourg');
				$countries[] = array('MO', 'Macau');
				$countries[] = array('MG', 'Madagascar');
				$countries[] = array('MW', 'Malawi');
				$countries[] = array('MY', 'Malaysia');
				$countries[] = array('MV', 'Maldives');
				$countries[] = array('ML', 'Mali');
				$countries[] = array('MT', 'Malta');
				$countries[] = array('MH', 'Marshall Islands');
				$countries[] = array('MQ', 'Martinique');
				$countries[] = array('MR', 'Mauritania');
				$countries[] = array('MU', 'Mauritius');
				$countries[] = array('YT', 'Mayotte');
				$countries[] = array('MX', 'Mexico');
				$countries[] = array('FM', 'Micronesia');
				$countries[] = array('MD', 'Moldova, Republic of');
				$countries[] = array('MC', 'Monaco');
				$countries[] = array('MN', 'Mongolia');
				$countries[] = array('MS', 'Monserrat');
				$countries[] = array('MA', 'Morocco');
				$countries[] = array('MZ', 'Mozambique');
				$countries[] = array('MM', 'Myanmar');
				$countries[] = array('NA', 'Nambia');
				$countries[] = array('NR', 'Nauru');
				$countries[] = array('NP', 'Nepal');
				$countries[] = array('NL', 'Netherlands');
				$countries[] = array('AN', 'Netherlands Antilles');
				$countries[] = array('NC', 'New Caledonia');
				$countries[] = array('NZ', 'New Zealand');
				$countries[] = array('NI', 'Nicaragua');
				$countries[] = array('NE', 'Niger');
				$countries[] = array('NG', 'Nigeria');
				$countries[] = array('NU', 'Niue');
				$countries[] = array('NF', 'Norfolk Island');
				$countries[] = array('MP', 'Northern Mariana Islands');
				$countries[] = array('NO', 'Norway');
				$countries[] = array('OM', 'Oman');
				$countries[] = array('PK', 'Pakistan');
				$countries[] = array('PW', 'Palau');
				$countries[] = array('PA', 'Panama');
				$countries[] = array('PG', 'Papua New Guinea');
				$countries[] = array('PY', 'Paraguay');
				$countries[] = array('PE', 'Peru');
				$countries[] = array('PH', 'Philippines');
				$countries[] = array('PN', 'Pitcairn');
				$countries[] = array('PL', 'Poland');
				$countries[] = array('PT', 'Portugal');
				$countries[] = array('PR', 'Puerto Rico');
				$countries[] = array('QA', 'Qatar');
				$countries[] = array('RE', 'R&eacute;union');
				$countries[] = array('RO', 'Romania');
				$countries[] = array('RU', 'Russian Federation');
				$countries[] = array('RW', 'Rwanda');
				$countries[] = array('LC', 'Saint Lucia');
				$countries[] = array('WS', 'Samoa');
				$countries[] = array('SM', 'San Marino');
				$countries[] = array('ST', 'Sao Tome & Principe');
				$countries[] = array('SA', 'Saudi Arabia');
				$countries[] = array('SN', 'Senegal');
				$countries[] = array('SC', 'Seychelles');
				$countries[] = array('SL', 'Sierra Leone');
				$countries[] = array('SG', 'Singapore');
				$countries[] = array('SK', 'Slovakia');
				$countries[] = array('SI', 'Slovenia');
				$countries[] = array('SB', 'Solomon Islands');
				$countries[] = array('SO', 'Somalia');
				$countries[] = array('ZA', 'South Africa');
				$countries[] = array('ES', 'Spain');
				$countries[] = array('LK', 'Sri Lanka');
				$countries[] = array('SH', 'St. Helena');
				$countries[] = array('KN', 'St. Kitts and Nevis');
				$countries[] = array('PM', 'St. Pierre & Miquelon');
				$countries[] = array('VC', 'St. Vincent & the Grenadines');
				$countries[] = array('SD', 'Sudan');
				$countries[] = array('SR', 'Suriname');
				$countries[] = array('SJ', 'Svalbard & Jan Mayen Islands');
				$countries[] = array('SZ', 'Swaziland');
				$countries[] = array('SE', 'Sweden');
				$countries[] = array('CH', 'Switzerland');
				$countries[] = array('SY', 'Syrian Arab Republic');
				$countries[] = array('TW', 'Taiwan');
				$countries[] = array('TJ', 'Tajikistan');
				$countries[] = array('TZ', 'Tanzania, United Republic of');
				$countries[] = array('TH', 'Thailand');
				$countries[] = array('TG', 'Togo');
				$countries[] = array('TK', 'Tokelau');
				$countries[] = array('TO', 'Tonga');
				$countries[] = array('TT', 'Trinidad & Tobago');
				$countries[] = array('TN', 'Tunisia');
				$countries[] = array('TR', 'Turkey');
				$countries[] = array('TM', 'Turkmenistan');
				$countries[] = array('TC', 'Turks & Caicos Islands');
				$countries[] = array('TV', 'Tuvalu');
				$countries[] = array('UG', 'Uganda');
				$countries[] = array('UA', 'Ukraine');
				$countries[] = array('AE', 'United Arab Emirates');
				$countries[] = array('GB', 'United Kingdom (Great Britain)');
				$countries[] = array('UM', 'US Minor Outlying Islands');
				$countries[] = array('UY', 'Uruguay');
				$countries[] = array('UZ', 'Uzbekistan');
				$countries[] = array('VU', 'Vanuatu');
				$countries[] = array('VA', 'Vatican City State (Holy See)');
				$countries[] = array('VE', 'Venezuela');
				$countries[] = array('VN', 'Viet Nam');
				$countries[] = array('VG', 'Virgin Islands (British)');
				$countries[] = array('VI', 'Virgin Islands (United States)');
				$countries[] = array('WF', 'Wallis & Futuna Islands');
				$countries[] = array('EH', 'Western Sahara');
				$countries[] = array('YE', 'Yemen');
				$countries[] = array('YU', 'Yugoslavia');
				$countries[] = array('ZR', 'Zaire');
				$countries[] = array('ZM', 'Zambia');
				$countries[] = array('ZW', 'Zimbabwe');
			}
		}	
	
		return $countries;
	}

}
?>