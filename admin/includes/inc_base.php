<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [Gart] www.wskmphp.com 
 * $Id: inc_base.php 250 2010-11-28 17:57:29Z ws99 $ 
 */

!defined('IN_ART') && exit('Access Denied');
@set_time_limit(0);

define('IN_ADMIN',true);
define('IS_NOCACHE',true);
define('ADMIN_ROOT', substr(dirname(__FILE__),0,-8));
define('ADMIN_DIRNAME', basename(ADMIN_ROOT));
define('ADMIN_INC_DIR', ADMIN_ROOT.'includes'.DIRECTORY_SEPARATOR);
define('OBGZIP',false);
require_once(dirname(ADMIN_ROOT).DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'inc_common.php');
define('ADMIN_URL', ART_URL.ADMIN_DIRNAME.'/');

wskm::helper('util');
loadLang('admin_common');
include('fun_common.php');

$adminstyleid=$adminstylename='';
$adminstyleid=(int)(!empty($_GET['adminstyleid']) ? $_GET['adminstyleid'] :
		(!empty($_POST['adminstyleid']) ? $_POST['adminstyleid'] :0));

$db=WSKM::SQL();
if ($adminstyleid < 1) {
	$adminstyleid=$db->fetch_column('SELECT value FROM '.TABLE_PREFIX."settings WHERE variable='adminStyleId'  ");
}
if ($adminstyleid > 0) {
	$adminstylename=$db->fetch_column('SELECT name FROM '.TABLE_PREFIX."themes WHERE styleid='{$adminstyleid}'  ");
}

define('ADMIN_STYLEID', $adminstyleid);
define('ADMIN_STYLENAME',$adminstylename);

define('ADMIN_CACHE_DIR',ART_CACHE_PATH);
define('ADMIN_TPL_DIR',ADMIN_ROOT.'themes'.DS);

define('ATHEME_URL', ART_URL.ADMIN_DIRNAME.'/themes/'.ADMIN_STYLENAME.'/');
define('ATHEME_IMG', ATHEME_URL.'images/');
define('ATHEME_JS',  ATHEME_URL.'js/');
define('ATHEME_CSS', ATHEME_URL.'css/');

define('MVC_FUN','usingAdminMVC');
define('URLMODE', URLMODE_NONE);

WSKM::setConfig('appDefault','admin');
WSKM::setConfig('appPrefix','app_admin_');
WSKM::setConfig('modelPrefix','model_admin_');

usingAdminClass('auth');
usingAdminClass('common');

?>