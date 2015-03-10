<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [Gart] www.wskmphp.com 
 * $Id: inc_common.php 281 2010-12-26 07:57:48Z ws99 $ 
 */

!defined('IN_ART') && exit('Access Denied');

define('ART_ROOT', substr(dirname(__FILE__),0,-8));
define('ART_INC_PATH',  ART_ROOT.'includes'.DIRECTORY_SEPARATOR);
define('ART_CONFIG_PATH',  ART_ROOT.'config'.DIRECTORY_SEPARATOR);
define('ART_UPLOAD_PATH',  ART_ROOT.'attachments'.DIRECTORY_SEPARATOR);
define('ART_CACHE_PATH',  ART_ROOT.'cache'.DIRECTORY_SEPARATOR);
define('ART_IMG_PATH',  ART_ROOT.'images'.DIRECTORY_SEPARATOR);
define('ART_THEMES_PATH',  ART_ROOT.'themes'.DIRECTORY_SEPARATOR);

define('ART_RELEASE', '20101226');
define('ART_VER', '1.6');
!defined('IN_ADMIN') && define('IN_ADMIN',false);

require_once(ART_ROOT.'WskmPHP'.DIRECTORY_SEPARATOR.'wskmphp.php');

WSKM::loadConfig(ART_CONFIG_PATH.'config_sys.php');
define('ART_KEY',md5(WSKM::getConfig('artkey') ) );
define("AUTH_KEY", md5(WSKM::getConfig('artkey').$_SERVER['HTTP_USER_AGENT']));
define("URL_AUTH_KEY", md5(WSKM::getConfig('urlartkey').WSKM::getConfig('artkey')));

require(ART_INC_PATH.'fun_common.php');

loadCacheConfig('settings');
usingArtClass('cache');
!defined('OBGZIP') && define('OBGZIP', (bool)WSKM::getConfig('isGzip'));
define('IS_HTML',(bool)WSKM::getConfig('isHtml'));
WSKM::init();

define('ART_WEB_URL',WSKM::getConfig('webUrl'));
define('ART_URL',WSKM::isExistConfig('webBaseUrl')?WSKM::getConfig('webBaseUrl').'/':getBaseDir() );
if(strpos(ART_URL, '://') === false) {
	define('ART_URL_FULL', ($_SERVER['HTTPS'] == 'on' ? 'https' : 'http').'://'.(empty($_SERVER['HTTP_HOST'])?$_SERVER['SERVER_NAME']:$_SERVER['HTTP_HOST']).ART_URL);
}else{
	define('ART_URL_FULL',ART_URL);
}

define('ART_UPLOAD_URL',ART_URL.'attachments/');
define('ART_INC_URL',ART_URL.'includes/');
define('ART_WEB_NAME',WSKM::getConfig('webName'));
define('ART_HASH',WSKM::hash());
loadLang('common');

if (IS_MVC){
	usingArtClass('page');
}

?>