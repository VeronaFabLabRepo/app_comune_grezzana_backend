<?php
class FM_Utility
{
	public static function debug($x)
	{
		echo '<pre>';
		print_r($x);
		echo '</pre>';
	}

	public static function random_string($length)
	{
		$string = "";

		// genera una stringa casuale che ha lunghezza
		// uguale al multiplo di 32 successivo a $length
		for ($i = 0; $i <= ($length / 32); $i++) {
			$string .= md5(time() + rand(0, 99));
		}

		// indice di partenza limite
		$max_start_index = (32*$i)-$length;

		// seleziona la stringa, utilizzando come indice iniziale
		// un valore tra 0 e $max_start_point
		$random_string = substr($string, rand(0, $max_start_index), $length);

		return $random_string;
	}

// 	public static function permalink($str, $replace = array(), $delimiter = '-')
// 	{
// 		setlocale(LC_ALL, 'en_US.UTF8');
// 		if (!empty($replace)) {
// 			$str = str_replace((array)$replace, ' ', $str);
// 		}

// 		$clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
// 		$clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
// 		$clean = strtolower(trim($clean, '-'));
// 		$clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

// 		return $clean;
// 	}

	public static function prepareFieldName($string)
	{
		return self::cleanString($string);
	}

	public static function cleanString($string)
	{
		$chaine = trim($string);
		$chaine = strtr($string, "ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ", "aaaaaaaaaaaaooooooooooooeeeeeeeecciiiiiiiiuuuuuuuuynn");
		$chaine = strtr($string, "ABCDEFGHIJKLMNOPQRSTUVWXYZ", "abcdefghijklmnopqrstuvwxyz");
		$chaine = preg_replace('#([^.a-z0-9]+)#i', '_', $string);
		$chaine = preg_replace('#-{2,}#','_',$string);
		$string = str_replace(' ', '_', $string);
		$chaine = preg_replace('#-$#','',$string);
		$chaine = preg_replace('#^-#','',$string);
		$string = strtolower($string);
		return $string;
	}

	public static function getDaysWithZero()
	{
		$days = array();
		for ($i = 1; $i <= 31; $i++) {
			$days[str_pad($i , 2, "0", STR_PAD_LEFT)] = str_pad($i , 2, "0", STR_PAD_LEFT);
		}
		return $days;
	}

	public static function getMonthsWithZero()
	{
		$months = array();
		for ($i = 1; $i <= 12; $i++) {
			$months[str_pad( $i , 2, "0", STR_PAD_LEFT)] = str_pad( $i , 2, "0", STR_PAD_LEFT);
		}
		return $months;
	}

	public static function getCompactYears()
	{
		$years = array();
		$currentYear = date('Y');
		for ($i = $currentYear; $i > ($currentYear - 100); $i--) {
			//$years[$i] = substr($i, 2, 2);
			$years[$i] = $i;
		}
		return $years;
	}

	public static function getCountries()
	{
		$countries = array(
			  'AD' => 'Andorra'
			, 'AE' => 'United Arab Emirates'
			, 'AF' => 'Afghanistan'
			, 'AG' => 'Antigua and Barbuda'
			, 'AI' => 'Anguilla'
			, 'AL' => 'Albania'
			, 'AM' => 'Armenia'
			, 'AO' => 'Angola'
			, 'AQ' => 'Antarctica'
			, 'AR' => 'Argentina'
			, 'AS' => 'American Samoa'
			, 'AT' => 'Austria'
			, 'AU' => 'Australia'
			, 'AW' => 'Aruba'
			, 'AX' => 'Åland Islands'
			, 'AZ' => 'Azerbaijan'
			, 'BA' => 'Bosnia and Herzegovina'
			, 'BB' => 'Barbados'
			, 'BD' => 'Bangladesh'
			, 'BE' => 'Belgium'
			, 'BF' => 'Burkina Faso'
			, 'BG' => 'Bulgaria'
			, 'BH' => 'Bahrain'
			, 'BI' => 'Burundi'
			, 'BJ' => 'Benin'
			, 'BL' => 'Saint Barthélemy'
			, 'BM' => 'Bermuda'
			, 'BN' => 'Brunei Darussalam'
			, 'BO' => 'Bolivia'
			, 'BQ' => 'Caribbean Netherlands '
			, 'BR' => 'Brazil'
			, 'BS' => 'Bahamas'
			, 'BT' => 'Bhutan'
			, 'BV' => 'Bouvet Island'
			, 'BW' => 'Botswana'
			, 'BY' => 'Belarus'
			, 'BZ' => 'Belize'
			, 'CA' => 'Canada'
			, 'CC' => 'Cocos (Keeling) Islands'
			, 'CD' => 'Congo, Democratic Republic of'
			, 'CF' => 'Central African Republic'
			, 'CG' => 'Congo'
			, 'CH' => 'Switzerland'
			, 'CI' => 'Côte d\'Ivoire'
			, 'CK' => 'Cook Islands'
			, 'CL' => 'Chile'
			, 'CM' => 'Cameroon'
			, 'CN' => 'China'
			, 'CO' => 'Colombia'
			, 'CR' => 'Costa Rica'
			, 'CU' => 'Cuba'
			, 'CV' => 'Cape Verde'
			, 'CW' => 'Curaçao'
			, 'CX' => 'Christmas Island'
			, 'CY' => 'Cyprus'
			, 'CZ' => 'Czech Republic'
			, 'DE' => 'Germany'
			, 'DJ' => 'Djibouti'
			, 'DK' => 'Denmark'
			, 'DM' => 'Dominica'
			, 'DO' => 'Dominican Republic'
			, 'DZ' => 'Algeria'
			, 'EC' => 'Ecuador'
			, 'EE' => 'Estonia'
			, 'EG' => 'Egypt'
			, 'EH' => 'Western Sahara'
			, 'ER' => 'Eritrea'
			, 'ES' => 'Spain'
			, 'ET' => 'Ethiopia'
			, 'FI' => 'Finland'
			, 'FJ' => 'Fiji'
			, 'FK' => 'Falkland Islands'
			, 'FM' => 'Micronesia, Federated States of'
			, 'FO' => 'Faroe Islands'
			, 'FR' => 'France'
			, 'GA' => 'Gabon'
			, 'GB' => 'United Kingdom'
			, 'GD' => 'Grenada'
			, 'GE' => 'Georgia'
			, 'GF' => 'French Guiana'
			, 'GG' => 'Guernsey'
			, 'GH' => 'Ghana'
			, 'GI' => 'Gibraltar'
			, 'GL' => 'Greenland'
			, 'GM' => 'Gambia'
			, 'GN' => 'Guinea'
			, 'GP' => 'Guadeloupe'
			, 'GQ' => 'Equatorial Guinea'
			, 'GR' => 'Greece'
			, 'GS' => 'South Georgia and the South Sandwich Islands'
			, 'GT' => 'Guatemala'
			, 'GU' => 'Guam'
			, 'GW' => 'Guinea-Bissau'
			, 'GY' => 'Guyana'
			, 'HK' => 'Hong Kong'
			, 'HM' => 'Heard and McDonald Islands'
			, 'HN' => 'Honduras'
			, 'HR' => 'Croatia'
			, 'HT' => 'Haiti'
			, 'HU' => 'Hungary'
			, 'ID' => 'Indonesia'
			, 'IE' => 'Ireland'
			, 'IL' => 'Israel'
			, 'IM' => 'Isle of Man'
			, 'IN' => 'India'
			, 'IO' => 'British Indian Ocean Territory'
			, 'IQ' => 'Iraq'
			, 'IR' => 'Iran'
			, 'IS' => 'Iceland'
			, 'IT' => 'Italy'
			, 'JE' => 'Jersey'
			, 'JM' => 'Jamaica'
			, 'JO' => 'Jordan'
			, 'JP' => 'Japan'
			, 'KE' => 'Kenya'
			, 'KG' => 'Kyrgyzstan'
			, 'KH' => 'Cambodia'
			, 'KI' => 'Kiribati'
			, 'KM' => 'Comoros'
			, 'KN' => 'Saint Kitts and Nevis'
			, 'KP' => 'North Korea'
			, 'KR' => 'South Korea'
			, 'KW' => 'Kuwait'
			, 'KY' => 'Cayman Islands'
			, 'KZ' => 'Kazakhstan'
			, 'LA' => 'Lao People\'s Democratic Republic'
			, 'LB' => 'Lebanon'
			, 'LC' => 'Saint Lucia'
			, 'LI' => 'Liechtenstein'
			, 'LK' => 'Sri Lanka'
			, 'LR' => 'Liberia'
			, 'LS' => 'Lesotho'
			, 'LT' => 'Lithuania'
			, 'LU' => 'Luxembourg'
			, 'LV' => 'Latvia'
			, 'LY' => 'Libya'
			, 'MA' => 'Morocco'
			, 'MC' => 'Monaco'
			, 'MD' => 'Moldova'
			, 'ME' => 'Montenegro'
			, 'MF' => 'Saint-Martin (France)'
			, 'MG' => 'Madagascar'
			, 'MH' => 'Marshall Islands'
			, 'MK' => 'Macedonia'
			, 'ML' => 'Mali'
			, 'MM' => 'Myanmar'
			, 'MN' => 'Mongolia'
			, 'MO' => 'Macau'
			, 'MP' => 'Northern Mariana Islands'
			, 'MQ' => 'Martinique'
			, 'MR' => 'Mauritania'
			, 'MS' => 'Montserrat'
			, 'MT' => 'Malta'
			, 'MU' => 'Mauritius'
			, 'MV' => 'Maldives'
			, 'MW' => 'Malawi'
			, 'MX' => 'Mexico'
			, 'MY' => 'Malaysia'
			, 'MZ' => 'Mozambique'
			, 'NA' => 'Namibia'
			, 'NC' => 'New Caledonia'
			, 'NE' => 'Niger'
			, 'NF' => 'Norfolk Island'
			, 'NG' => 'Nigeria'
			, 'NI' => 'Nicaragua'
			, 'NL' => 'The Netherlands'
			, 'NO' => 'Norway'
			, 'NP' => 'Nepal'
			, 'NR' => 'Nauru'
			, 'NU' => 'Niue'
			, 'NZ' => 'New Zealand'
			, 'OM' => 'Oman'
			, 'PA' => 'Panama'
			, 'PE' => 'Peru'
			, 'PF' => 'French Polynesia'
			, 'PG' => 'Papua New Guinea'
			, 'PH' => 'Philippines'
			, 'PK' => 'Pakistan'
			, 'PL' => 'Poland'
			, 'PM' => 'St. Pierre and Miquelon'
			, 'PN' => 'Pitcairn'
			, 'PR' => 'Puerto Rico'
			, 'PS' => 'Palestinian Territory, Occupied'
			, 'PT' => 'Portugal'
			, 'PW' => 'Palau'
			, 'PY' => 'Paraguay'
			, 'QA' => 'Qatar'
			, 'RE' => 'Reunion'
			, 'RO' => 'Romania'
			, 'RS' => 'Serbia'
			, 'RU' => 'Russian Federation'
			, 'RW' => 'Rwanda'
			, 'SA' => 'Saudi Arabia'
			, 'SB' => 'Solomon Islands'
			, 'SC' => 'Seychelles'
			, 'SD' => 'Sudan'
			, 'SE' => 'Sweden'
			, 'SG' => 'Singapore'
			, 'SH' => 'Saint Helena'
			, 'SI' => 'Slovenia'
			, 'SJ' => 'Svalbard and Jan Mayen Islands'
			, 'SK' => 'Slovakia (Slovak Republic)'
			, 'SL' => 'Sierra Leone'
			, 'SM' => 'San Marino'
			, 'SN' => 'Senegal'
			, 'SO' => 'Somalia'
			, 'SR' => 'Suriname'
			, 'SS' => 'South Sudan'
			, 'ST' => 'Sao Tome and Principe'
			, 'SV' => 'El Salvador'
			, 'SX' => 'Saint-Martin (Pays-Bas)'
			, 'SY' => 'Syria'
			, 'SZ' => 'Swaziland'
			, 'TC' => 'Turks and Caicos Islands'
			, 'TD' => 'Chad'
			, 'TF' => 'French Southern Territories'
			, 'TG' => 'Togo'
			, 'TH' => 'Thailand'
			, 'TJ' => 'Tajikistan'
			, 'TK' => 'Tokelau'
			, 'TL' => 'Timor-Leste'
			, 'TM' => 'Turkmenistan'
			, 'TN' => 'Tunisia'
			, 'TO' => 'Tonga'
			, 'TR' => 'Turkey'
			, 'TT' => 'Trinidad and Tobago'
			, 'TV' => 'Tuvalu'
			, 'TW' => 'Taiwan'
			, 'TZ' => 'Tanzania'
			, 'UA' => 'Ukraine'
			, 'UG' => 'Uganda'
			, 'UM' => 'United States Minor Outlying Islands'
			, 'US' => 'United States'
			, 'UY' => 'Uruguay'
			, 'UZ' => 'Uzbekistan'
			, 'VA' => 'Vatican'
			, 'VC' => 'Saint Vincent and the Grenadines'
			, 'VE' => 'Venezuela'
			, 'VG' => 'Virgin Islands (British)'
			, 'VI' => 'Virgin Islands (U.S.)'
			, 'VN' => 'Vietnam'
			, 'VU' => 'Vanuatu'
			, 'WF' => 'Wallis and Futuna Islands'
			, 'WS' => 'Samoa'
			, 'YE' => 'Yemen'
			, 'YT' => 'Mayotte'
			, 'ZA' => 'South Africa'
			, 'ZM' => 'Zambia'
			, 'ZW' => 'Zimbabwe'
		);
	}

	public static function convertOptionsTextToArray($options)
	{
		$result = array();

		$options = explode("\n", $options);
		foreach ($options as $opt) {

			if ($opt) {

				list($value, $label) = explode(':', $opt);
				$result[$value] = $label;

			}

		}
		return $result;
	}

	public static function convertOptionsArrayToText($options)
	{
		$result = '';

		//foreach ($options)
		$options = explode("\n", $options);
		foreach ($options as $opt) {

			list($value, $label) = explode(':', $opt);
			$result[$value] = $label;

		}
		return $result;
	}

	public static function convertMultiOptionsArrayToText($options)
	{
		return (is_array($options) && count($options) > 0 ? join(',', $options) : '');
	}

	public static function convertMultiOptionsTextToArray($options)
	{
		return explode(',', $options);
	}

	public static function convertHumanDateToIso($date)
	{
		return implode('-', array_reverse(explode('/', $date)));
	}

	public static function convertIsoDateToHuman($date)
	{
		return implode('/', array_reverse(explode('-', $date)));
	}

	public static function getSQLOrderFromOption($option)
	{
		$order = '';

		switch ($option) {

			case 'id_asc':

				$order = 'id ASC';

			break;
			case 'id_desc':

				$order = 'id DESC';

			break;
			case 'data_insert_asc':

				$order = 'data_insert ASC';

			break;
			case 'data_insert_desc':

				$order = 'data_insert DESC';

			break;
			case 'order_no_asc':

				$order = 'order_no ASC';

			break;
			case 'order_no_desc':

				$order = 'order_no DESC';

			break;
			case 'title_asc':

				$order = 'title ASC';

			break;
			case 'title_desc':

				$order = 'title DESC';

			break;
			case 'order_no_asc_title_asc':

				$order = 'order_no ASC, title ASC';

			break;
			case 'order_no_desc_title_desc':

				$order = 'order_no DESC, title DESC';

			break;
			default:
				$order = $option;

		}
		return $order;
	}
}
?>