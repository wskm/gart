<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [Gart] www.wskmphp.com 
 * $Id: fun_article.php 222 2010-11-22 09:20:03Z ws99 $ 
 */

!defined('IN_ART') && exit('Access Denied');

function emailVerifyKey(){
	$user=WSKM::user();
	return md5(substr(md5(ART_KEY), 8).$user->getUid().$user->getEmail().substr(WSKM_TIME,0,5));
}

function emailVerify(){
	if (!(bool)WSKM::getConfig('isEmailVerify')) {
		return false;
	}

	$user=WSKM::user();
	if (!$user->isLogin()) {
		return false;
	}
	
	$sendmail=$user->getEmail();
	if (!isEmail($sendmail)) {
		return false;
	}

	loadLang('user');
	$key=emailVerifyKey();
	$link=mvcUrl('',array('user','emailforverify',array('hash'=>$key)),ART_URL_FULL);
	$body=lang('emailverify_body');
	$body .= "<br/><a href=\"$link\" target='_blank' >{$link}</a>";

	WSKM::using('wskm_email');
	@set_time_limit(60);
	return wskm_email::sendmail(ART_WEB_NAME.'-'.lang('emailverify_title'),$body,$sendmail);
}

?>