<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [Gart] www.wskmphp.com 
 * $Id: download.php 260 2010-11-29 11:06:27Z ws99 $ 
 */

define('IN_ART',true);
define('IS_MVC',false);
define('OBGZIP',false);
require('./includes/inc_common.php');

if ((bool)WSKM::getConfig('attachValidReferer') && !isMyReferer()) {	
	artMessage('referer_error');
}

$userClass=null;
if ((bool)WSKM::getConfig('attachValidUser')) {
	$userClass=WSKM::user();
	if(!$userClass->isLogin()){
		artMessage('login_not','index.php');
	}

}

$attachid=requestGet('id');

if ($attachid  > 0) {
	$db=WSKM::SQL();
	$attachInfo=$db->fetch_first('SELECT id,aid,cid,uid,uploadtime as `time`,downloads,filename,filetype,filepath,filesize,width,isimage FROM '.TABLE_PREFIX."attachments  WHERE id='{$attachid}'");
	if ($attachInfo == false) {
		artMessage('not_found','index.php');
	}
	
	$attachInfo['filepath']=pathSame( ART_ROOT.'attachments'.DS.$attachInfo['filepath']);
	if (!is_readable($attachInfo['filepath'])) {
		artMessage('attachment_notfound');
	}
	
	$db->exec('UPDATE '.TABLE_PREFIX."attachments SET downloads= downloads+1 WHERE id='$attachid' ");
	
	WSKM::using('wskm_http_file');
	wskm_http_file::out($attachInfo);
}

exit('Access Denied');

?>