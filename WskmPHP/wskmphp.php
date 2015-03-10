<?php
/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [WskmPHP] www.wskmphp.com 
 * $Id: wskmphp.php 263 2010-11-29 21:52:44Z ws99 $ 
 */

error_reporting(0);
if(PHP_VERSION < '5.3.0') {
	set_magic_quotes_runtime(0);
}

if(!empty( $_SERVER['QUERY_STRING']))
{
	$temp = rawurldecode($_SERVER['QUERY_STRING']);
	if(strpos($temp, '<')!== false || strpos($temp, '"') !== false)
	exit( 'Request bad url');
}

define('IN_WSKM', true);
define('IN_DEBUG', false);
define('SQL_DEBUG', true);
define('WSKM_RUNTIME',microtime(true));
define('WSKM_TIME',(int)WSKM_RUNTIME);

!defined('MIN_USING') && define('MIN_USING',false);
!defined('IS_MVC') && define('IS_MVC',true);			
!defined('IS_SAFE') && define('IS_SAFE',true);			
!defined('IS_SESSION') && define('IS_SESSION',true);	
!defined('IS_NOCACHE') && define('IS_NOCACHE',false);
!defined('IS_NOROBOT') && define('IS_NOROBOT',false);
!defined('START_USER') && define('START_USER',true);

define('WSKM_VERSION', '0.1');
define('DS', DIRECTORY_SEPARATOR);
define('WSKM_ROOT', dirname(__FILE__). DS);
define('WSKM_LIBPATH', WSKM_ROOT . 'library'.DS);
define('WSKM_COMPATH', WSKM_ROOT . 'common'.DS);
define('WSKM_INFPATH', WSKM_ROOT . 'config'.DS);
define('WSKM_PLUGPATH', WSKM_ROOT . 'plugins'.DS);

define('IS_WIN',PHP_OS=='WINNT');
define('IS_POST',strtoupper($_SERVER['REQUEST_METHOD']) == 'POST');
define('MAGIC_QUOTES_GPC', function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc());

define('URLMODE_NONE', 'URLMODE_NONE');
define('URLMODE_SIGN', 'URLMODE_SIGN');
define('URLMODE_PATH', 'URLMODE_PATH');
define('URLMODE_REWR', 'URLMODE_REWR');

//type
define('TYPE_STRING','string');
define('TYPE_HTMLTEXT','htmltext');
define('TYPE_INT','integer');
define('TYPE_FLOAT','float');
define('TYPE_DOUBLE','double');
define('TYPE_BOOL','bool');
define('TYPE_ARRAY','array');
define('TYPE_OBJECT','object');
define('TYPE_RESOURCE','resource');
define('TYPE_CMD','cmd');
define('TYPE_WORD','word');
define('TYPE_ALNUM','alnum');
define('TYPE_URL','url');

define('URL_POST','post');
define('URL_GET','get');
define('URL_HOME','index.php');

//cache type
define('CACHETYPE_SQL','sql');
define('CACHETYPE_FILE','file');
define('CACHETYPE_HTML','html');
define('CACHETYPE_MEMORY','memory');

if(IS_NOCACHE)
{
	header("Expires: -1");
	header("Cache-Control: private, post-check=0, pre-check=0, max-age=0", FALSE);
	header("Pragma: no-cache");
}

require(WSKM_COMPATH.'wk_common.php');

getRobot();
if(IS_NOROBOT && IS_ROBOT) exit(header("HTTP/1.1 403 Forbidden"));
if(!MAGIC_QUOTES_GPC){
	$_GET = wkAddslashes($_GET);
	$_POST = wkAddslashes($_POST);
	$_COOKIE = wkAddslashes($_COOKIE);
	$_FILES = wkAddslashes($_FILES);
}

if(function_exists('ini_get')) {
	$temp = @ini_get('memory_limit');
	if($temp && getBytes($temp) < 33554432 && function_exists('ini_set')) {
		ini_set('memory_limit', '128m');
	}
}

define('USER_IP',getUserIP());
define('PAGE_SELF',getUrlFull());

require_once(WSKM_LIBPATH.'wskm.php');
if(!IS_MVC){
	wskm_request::initGET($_GET);
	wskm_request::initPOST($_POST);
}

if (!MIN_USING) {
	WSKM::loadConfig(WSKM_INFPATH.'config_wskm.php');

	wskm::using('wskm_lang');
	WSKM::using('wskm_user');

	WSKM::using('wskm_db_base');
	WSKM::using('wskm_exception');

	WSKM::using('wskm_io');
	WSKM::using('wskm_core_abstract');

	if (IS_MVC){
		WSKM::using('wskm_page_abstract');
		WSKM::using('wskm_template_easy');
	}

}

?>