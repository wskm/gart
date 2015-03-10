<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [WskmPHP] www.wskmphp.com 
 * $Id: wskm.php 280 2010-12-26 06:05:21Z ws99 $ 
 */

!defined('IN_WSKM') && exit('Access Denied');

class WSKM
{

	private static $user=null;
	public static $configs=array();
	public static $caches=array();

	static function hash(){
		$add='';
		if (is_object(self::$user)) {
			$add .=self::$user->getUid().self::$user->getUname().self::$user->getGroupid().self::$user->getAdminid();
		}
		return wkHash($add);
	}

	static function uniqid(){
		return uniqid(mt_rand(), true);
	}

	static function random($length, $isnumber = 0) {
		$hashstr=md5(print_r($_SERVER, 1).microtime(true)).uniqid(mt_rand(), true);

		if ($isnumber) {
			$hashstr=sprintf('%u',crc32($hashstr));
			$hashstr.='012340567890';
		}else{
			$hashstr=base_convert($hashstr,16,35);
			$hashstr.=strtoupper($hashstr);
		}

		$res = '';
		$max = strlen($hashstr) - 1;
		for($i = 0; $i < $length; $i++) {
			$res .= $hashstr[mt_rand(0, $max)];
		}
		return $res;
	}

	static function initCaches($cache){
		self::$caches=$cache;
	}

	static function getValue($key){
		return self::$caches[$key];
	}

	static function getValues($key,$key2){
		if (isset(self::$caches[$key][$key2])) {
			return self::$caches[$key][$key2];
		}
		return false;
	}

	static function setValue($key,$value,$showerr=true){
		if ($showerr && isset(self::$caches[$key]) && self::$caches[$key]) {
			throw new wskm_exception('Key already exists:'.$key);
		}

		self::$caches[$key]=$value;
	}

	static function loadConfig($path,$key='')
	{
		if (!is_array($path))
		{
			if (!file_exists($path))
			{
				throw new wskm_exception('loadConfig:'.$path);
			}
			else
			{
				$configsArray = loadArray($path,$key);
				self::$configs=array_merge(self::$configs,$configsArray);
			}
		}elseif($path){
			self::$configs=array_merge(self::$configs,$path);
		}
	}

	static function getConfig($key)
	{
		return self::$configs[$key];
	}

	static function isExistConfig($key){
		return isset(self::$configs[$key]);
	}
	
	static function getConfigs($key1,$key2)
	{
		if (isset(self::$configs[$key1][$key2]) ) {
			return self::$configs[$key1][$key2];
		}
		return false;
	}

	static function setConfig($key,$value)
	{
		return self::$configs[$key]=$value;
	}

	static function import($path)
	{
		return self::importBase($path);
	}

	static function helper($path)
	{
		$path=WSKM_COMPATH.'wk_'.$path.'.php';
		return self::importBase($path);
	}

	static function using($className)
	{
		$path = WSKM_LIBPATH.str_replace('_', DS, $className) . '.php';
		return  self::usingBase($className,$path);
	}

	static function usingBase($className, $path)
	{
		if(class_exists($className, false)|| interface_exists($className, false)){return true;}

		include($path);
		if (class_exists($className, false) || interface_exists($className, false))
		{
			return true;
		}

		return false;
	}

	static function loadClass($className)
	{
		static $classwrap=array();
		$cachekey='wskm_class_'.$className;
		if (isset($classwrap[$cachekey]) && is_object($classwrap[$cachekey])) {
			return $classwrap[$cachekey];
		}
		
		if (self::using($className))
		{
			$class=new $className();
			$classwrap[$cachekey]=$class;
			return  $class;
		} else{
			throw new wskm_exception("WSKM Load Class Error By CalssName:<b>$className</b>");
		}

	}

	private static function importBase($path)
	{
		static $isLoad=array();
		if (isset($isLoad[$path]))
		{
			return $isLoad[$path];
		}

		$isok = include ($path);
		$isLoad[$path]=$isok;

		return $isok;
	}

	static function init()
	{
		static $firstTime = true;
		if (!$firstTime) { return; }
		$firstTime = false;

		if (!MIN_USING) {

			//busy
			define('PAGE_CHARSET',strtolower(self::getConfig('pageCharset')));
			define('LANGUAGE',strtolower(self::getConfig('language')));

			define('URL_NAME',getUrlName());
			$cacheDir=self::getConfig('userCacheDir');
			!defined('CACHE_DIR') && define('CACHE_DIR', empty($cacheDir)?WSKM_ROOT.'../cache/':$cacheDir.DS);
			!defined('TPL_DIR') && define('TPL_DIR', self::getConfig('userTplDir').DS);
			!defined('MOD_DIR') && define('MOD_DIR', self::getConfig('userModDir').DS);
			!defined('APP_DIR') && define('APP_DIR', self::getConfig('userAppDir').DS);

			if (self::getConfig('langdir')) {
				wskm_lang::setOutDir(self::getConfig('langdir').DS);
			}

			!defined('MVC_DIR') && define('MVC_DIR',self::getConfig('userMvcDir').DS);

			wskm_lang::lang('message',LANGUAGE);
			START_USER && self::startUser();
			self::initEngine();

		}
		
	}

	static function run()
	{		
		!defined('URLMODE') && define('URLMODE', self::getConfig('urlMode'));
		if(IS_MVC && self::getConfig('mvcEnabled'))
		{
			self::mvcBegin();
		}

	}

	private static function mvcBegin()
	{
		!defined('MVC_FUN') && define('MVC_FUN','usingMVC');
		self::using('wskm_http_mvc');
		wskm_http_mvc::mvc();
	}

	private static function initEngine()
	{
		$timezone =(int)self::getConfig('userTimeZone');

		if (empty($timezone)) {
			@date_default_timezone_set('Etc/GMT+8');
		} else {
			@date_default_timezone_set('Etc/GMT'.($timezone > 0 ? '-' : '+').(abs($timezone)));
		}

		set_exception_handler(self::getConfig('exceptionHandler'));
		header('content-type:text/html; charset='.PAGE_CHARSET);

	}

	private static function startUser()
	{
		wskm_cookie::load();
		self::$user=new wskm_user();
	}

	static function user()
	{
		if(is_object(self::$user)){
			return self::$user;
		}

		throw new wskm_exception('Please instantiated WSKM::startUser() !');
	}

	private static $_sql;
	static function SQL()
	{
		if(is_object(self::$_sql)) return self::$_sql;

		define('TABLE_PREFIX',self::getConfig('tablePre'));
		define('DB_TYPE',strtolower(self::getConfig('dbType')));
		define('DB_CHARSET',strtolower(self::getConfig('dbCharset')));

		$class='wskm_db_'.self::getConfig('dbDriver');
		self::using($class);
		self::$_sql= new $class();

		if (SQL_DEBUG) {
			self::$_sql->exception_level(1);
		}

		self::$_sql->addDbServer(array(
		'dbHost'=>self::getConfig('dbHost'),
		'dbUser'=>self::getConfig('dbUser'),
		'dbPassword'=>self::getConfig('dbPassword'),
		'dbName'=>self::getConfig('dbName'),
		'dbPconnect'=>self::getConfig('dbPconnect'),
		'dbPort'=>self::getConfig('dbPort'),
		'dbCharset'=>DB_CHARSET,
		'tablePre'=>TABLE_PREFIX,'dbType'=>DB_TYPE
		));

		return self::$_sql;
	}

	static function sqlHelper()
	{
		return self::loadClass('wskm_db_help_'.DB_TYPE);
	}

	static function usingMVC($className)
	{
		$path = MVC_DIR.str_replace('_', DS, $className).'.php';
		if (!file_exists($path)) {
			return false;
		}
		return self::usingBase($className,$path);
	}

	static function usingModel($actionName)
	{
		$className =WSKM::getConfig('modelPrefix').$actionName;
		$path = MOD_DIR.$actionName.'.php';
		if (!file_exists($path)) {
			throw new wskm_exception('The class path does not exist:'.$actionName);
		}
		if(self::usingBase($className,$path))
		{
			if (class_exists($className)) {
				return new $className();
			}
		}
		throw new wskm_exception('Without this model class:'.$actionName);

	}

	static function usingApp($actionName)
	{
		$className =WSKM::getConfig('appPrefix').$actionName;
		$path = APP_DIR.$actionName.'.php';
		if (!file_exists($path)) {
			return false;
		}
		if(self::usingBase($className,$path))
		{
			return new $className();
		}
		return false;
	}

}


class wskm_core_base{

	public function initVal($args)
	{
		
		if (is_array($args)) {
			foreach ($args as $k=>$v){
				//!null
				if (isset($this->$k)) {
					$this->$k=$v;
				}
			}
		}
		
	}
}


class wskm_note
{
	static private $notes=array();
	static function note($value,$key){
		if (!isset(self::$notes[$key])) {
			self::$notes[$key]=array();
		}
		self::$notes[$key][]=$value;
	}

	static function read($key){
		return self::$notes[$key];
	}

}
class wskm_cache{

	public static function write($path,$content)
	{
		return wskm_io::fWrite($path,"<?php !defined('IN_WSKM') && exit('Access Denied');".PHP_EOL.$content.PHP_EOL."?>");
	}

	public static function writeArray($path,$arr){
		return self::write($path,'return '.arrayEval($arr));
	}

}


class wskm_filter{

	static function getValue($val,$type,$length=0)
	{
		switch ($type){
			case TYPE_INT:
				$val=(int)$val;
				break;
			case TYPE_FLOAT:
			case TYPE_DOUBLE:
				$val=(float)$val;
				break;
			case TYPE_STRING:
				$val=trim(strip_tags((string)$val));
				if ($length) {
					$val=strCut($val,$length,'');
				}
				break;
			case TYPE_URL:
				$val=filter_var($val,FILTER_VALIDATE_URL);
				if ($val==false) {
					$val='';
				}
				break;
			case TYPE_HTMLTEXT:
				$val=trim((string)$val);
				if ($length) {
					$val=strCut($val,$length,'');
				}
				break;
			case TYPE_WORD:
				$val = (string) preg_replace('/[\W]/', '', $val);
				break;
			case TYPE_ALNUM:
				$val = (string) preg_replace('/[^A-Z0-9_\-]/i', '', $val);
				break;
			case TYPE_BOOL:
				$val=(bool)$val;
				break;
			case TYPE_ARRAY:
				$val=(array)$val;
				break;
			case TYPE_CMD:
				$val = (string) preg_replace('/[^A-Z0-9_\.-\s]/i', '', $val);
				$val = ltrim($val, '.');
				break;
		}

		return $val;
	}

}

class wskm_request{
	public static $gets=array();
	public static $posts=array();

	static function initGET($args){
		self::$gets=$args;
	}

	static function initPOST($args){
		self::$posts=$args;
	}

	static function addGET($args){
		self::$gets=array_merge(self::$gets,$args);
	}
	
	static function setGET($key,$value){
		self::$gets[$key]=$value;
	}

	static function setPOST($key,$value){
		self::$posts[$key]=$value;
	}

	static function GET($key,$type='',$len=0)
	{
		if ($type != '') {
			return wskm_filter::getValue(self::$gets[$key],$type,$len);
		}
		return self::$gets[$key];
	}

	static function POST($key,$type='',$len=0)
	{
		if ($type != '') {
			return wskm_filter::getValue(self::$posts[$key],$type,$len);
		}
		return self::$posts[$key];
	}

}

class wskm_cookie{
	static private $cookie=array();

	static public function write($var,$value='',$life = 0, $httponly = true, $prefix = true)
	{
		$cookiepre =WSKM::getConfig('cookiePre');
		$cookiedomain=WSKM::getConfig('cookieDomain');
		$cookiepath=WSKM::getConfig('cookiePath');
		$var = ($prefix ? $cookiepre : '').$var;
		if($value == '' || $life < 0) {
			$value = '';
			$life = -1;
		}

		$life = $life > 0 ? WSKM_TIME + $life : ($life < 0 ? WSKM_TIME - 31536000 : 0);	//31536000 = 1year
		$cookiepath = $httponly && PHP_VERSION < '5.2.0' ? "$cookiepath; HttpOnly" : $cookiepath;		
		$secure = $_SERVER['SERVER_PORT'] == 443 ? 1 : 0;
		if(PHP_VERSION < '5.2.0') {
			setcookie($var, $value, $life, $cookiepath, $cookiedomain, $secure);
		} else {
			setcookie($var, $value, $life, $cookiepath, $cookiedomain, $secure, $httponly);
		}
	}

	static public function getValue($key){
		return self::$cookie[$key];
	}
	
	static public function isExist($key){
		return isset(self::$cookie[$key]);
	}

	static public function remove($key){
		unset(self::$cookie[$key]);
		self::write($key);
	}

	static public function clear()
	{
		if (isset(self::$cookie['adminhash'])) {
			unset(self::$cookie['adminhash']);
		}
		$keys=array_keys(self::$cookie);
		foreach ($keys as $k)
		{
			self::write($k);
		}

		self::$cookie=array();
	}

	static public function load()
	{
		$prefix=WSKM::getConfig('cookiePre');
		$prelength = strlen($prefix);
		foreach($_COOKIE as $key => $val) {
			if(substr($key, 0, $prelength) == $prefix) {
				self::$cookie[(substr($key, $prelength))] = MAGIC_QUOTES_GPC ? $val : wkAddslashes($val);
			}
		}

		unset($prelength,$key,$value);
	}
}

class wskm_encrypt{

	public static function md5($str)
	{
		return  md5(md5($str));
	}

	public static function crc32($str){
		return sprintf("%u", crc32($str));
	}

	public static function base64($str)
	{
		return base64_encode(serialize($data));
	}

	public static function unbase64($str)
	{
		return unserialize(base64_decode($data));
	}

	public static function DES($str,$key=AUTH_KEY) {
		if (function_exists('authCode')) {
			return authCode($str,'ENCODE',$key);
		}
		elseif (!function_exists('mcrypt_module_open')){
			WSKM::using('wskm_crypt_des');
			return wskm_crypt_des::DES_WEB($key,$str);
		}

		$td = mcrypt_module_open(MCRYPT_DES , '',  MCRYPT_MODE_ECB, '');
		$iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
		$ks = mcrypt_enc_get_key_size($td);
		$keystr = substr(md5($key), 0, $ks);
		mcrypt_generic_init($td, $keystr, $iv);
		$encrypted = mcrypt_generic($td, $str);
		mcrypt_module_close($td);
		$hexdata = bin2hex($encrypted);
		return $hexdata;
	}

	public static function UNDES($str,$key=AUTH_KEY) {
		if (function_exists('authCode')) {
			return authCode($str,'DECODE',$key);
		}elseif (!function_exists('mcrypt_module_open')){
			WSKM::using('wskm_crypt_des');
			return wskm_crypt_des::UNDES_WEB($key,$str);
		}

		$td = mcrypt_module_open(MCRYPT_DES , '',  MCRYPT_MODE_ECB, '');
		$iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
		$ks = mcrypt_enc_get_key_size($td);
		$keystr = substr(md5($key), 0, $ks);
		mcrypt_generic_init($td, $keystr, $iv);
		$encrypted = @pack( "H*", $str);
		$decrypted = @mdecrypt_generic($td, $encrypted);
		@mcrypt_generic_deinit($td);
		@mcrypt_module_close($td);
		return $decrypted;
	}
}

?>