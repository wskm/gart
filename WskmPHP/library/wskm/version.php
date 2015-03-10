<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [WskmPHP] www.wskmphp.com 
 * $Id: version.php 136 2010-10-16 07:49:23Z ws99 $ 
 */

!defined('IN_WSKM') && exit('Access Denied');

class wskm_version{
	static $timezone = array(
	'-12' => '(GMT -12:00) Eniwetok, Kwajalein',
	'-11' => '(GMT -11:00) Midway Island, Samoa',
	'-10' => '(GMT -10:00) Hawaii',
	'-9' => '(GMT -09:00) Alaska',
	'-8' => '(GMT -08:00) Pacific Time (US & Canada), Tijuana',
	'-7' => '(GMT -07:00) Mountain Time (US & Canada), Arizona',
	'-6' => '(GMT -06:00) Central Time (US & Canada), Mexico City',
	'-5' => '(GMT -05:00) Eastern Time (US & Canada), Bogota, Lima, Quito',
	'-4' => '(GMT -04:00) Atlantic Time (Canada), Caracas, La Paz',
	'-3.5' => '(GMT -03:30) Newfoundland',
	'-3' => '(GMT -03:00) Brassila, Buenos Aires, Georgetown, Falkland Is',
	'-2' => '(GMT -02:00) Mid-Atlantic, Ascension Is., St. Helena',
	'-1' => '(GMT -01:00) Azores, Cape Verde Islands',
	'0' => '(GMT) Casablanca, Dublin, Edinburgh, London, Lisbon, Monrovia',
	'1' => '(GMT +01:00) Amsterdam, Berlin, Brussels, Madrid, Paris, Rome',
	'2' => '(GMT +02:00) Cairo, Helsinki, Kaliningrad, South Africa',
	'3' => '(GMT +03:00) Baghdad, Riyadh, Moscow, Nairobi',
	'3.5' => '(GMT +03:30) Tehran',
	'4' => '(GMT +04:00) Abu Dhabi, Baku, Muscat, Tbilisi',
	'4.5' => '(GMT +04:30) Kabul',
	'5' => '(GMT +05:00) Ekaterinburg, Islamabad, Karachi, Tashkent',
	'5.5' => '(GMT +05:30) Bombay, Calcutta, Madras, New Delhi',
	'5.75' => '(GMT +05:45) Katmandu',
	'6' => '(GMT +06:00) Almaty, Colombo, Dhaka, Novosibirsk',
	'6.5' => '(GMT +06:30) Rangoon',
	'7' => '(GMT +07:00) Bangkok, Hanoi, Jakarta',
	'8' => '(GMT +08:00) Beijing, Hong Kong, Perth, Singapore, Taipei',
	'9' => '(GMT +09:00) Osaka, Sapporo, Seoul, Tokyo, Yakutsk',
	'9.5' => '(GMT +09:30) Adelaide, Darwin',
	'10' => '(GMT +10:00) Canberra, Guam, Melbourne, Sydney, Vladivostok',
	'11' => '(GMT +11:00) Magadan, New Caledonia, Solomon Islands',
	'12' => '(GMT +12:00) Auckland, Wellington, Fiji, Marshall Island'
	);

	static function php_version()
	{
		return PHP_VERSION;
	}

	static function php_os()
	{
		return PHP_OS;
	}

	static function mysql_version()
	{
		return WSKM::SQL()->version();
	}

	static function upload_maxsize(){
		$size='';
		if(@ini_get('file_uploads')) {
			$size = ini_get('upload_max_filesize');
		}
		return $size;
	}

	static function server_software()
	{
		return  $_SERVER['SERVER_SOFTWARE'];
	}

}

?>