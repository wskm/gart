<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [Gart] www.wskmphp.com 
 * $Id: inc_plugin.php 254 2010-11-28 20:59:16Z ws99 $ 
 */

define('IS_MVC',false);
define('CUR_ROOT', substr(dirname(__FILE__), 0, -8));
require(CUR_ROOT.'includes'.DIRECTORY_SEPARATOR.'inc_common.php');
define('URLMODE', WSKM::getConfig('urlMode'));

WSKM::using('wskm_page_abstract');
WSKM::using('wskm_template_easy');
WSKM::using('wskm_http_mvc');

art_hook::initUtil();
if (wskm_note::read('cache')) {
	gotoUrl(URL_HOME);
}

if (defined('PLUGIN_KEY')) {
	define('PLUGIN_URL',ART_URL.'plugins/'.PLUGIN_KEY.'/');
	define('PLUGIN_PATH',ART_ROOT.'plugins'.DS.PLUGIN_KEY.DS);
}

?>