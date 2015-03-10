<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [Gart] www.wskmphp.com 
 * $Id: wskm.php 260 2010-11-29 11:06:27Z ws99 $ 
 */

define('IN_ART',true);
define('IS_MVC',false);
define('IS_NOCACHE',true);
define('OBGZIP',false);

$act=$_GET['act'];
if (!in_array($act,array('newskiss','upload','swfupload','loginstate','checkhtml','commentvote','updatevcode','validvcode','checkuname','checkemail'))) {
	exit();
}
require('./includes/inc_common.php');

if($act=='loginstate'){
	WSKM::using('wskm_http_mvc');
	define('URLMODE', WSKM::getConfig('urlMode'));
	$user=WSKM::user();
	$html='';
	if ($user->isLogin()) {
		$html="document.write('<b>{$user->getUname()}</b> | <a href=\"".mvcUrl('',array('user','logout'),ART_URL)."\" >".lang('logout')."</a> | <a href=\"".mvcUrl('',array('user'),ART_URL)."\" >".lang('user_center')."</a>');";
	}else{
		$html="document.write('<a href=\"".mvcUrl('',array('user','login'),ART_URL)."\" onclick=\"showBox(\'login\',this.href);return false;\"  >".lang('login')."</a> | <a  href=\"".mvcUrl('',array('user','reg'),ART_URL)."\" onclick=\"showBox(\'reg\',this.href);return false;\" >".lang('reg')."</a>');";
	}
	echo $html;
	exit();
}elseif($act=='checkhtml'){
	$url=requestGet('url');
	$key=preg_replace('/[^A-Z0-9_\-\\/]/i', '', requestGet('key'));
	$updateid=requestGet('update',TYPE_INT);
	if (!$key || !$url) {
		exit();
	}

	if ($updateid >0) {
		$newsObj=usingModel('article');
		$newsObj->updateClicks($updateid);
	}

	$htmlpath=ART_ROOT.'html'.DS.$key.'.html';
	if (file_exists($htmlpath) && WSKM_TIME > (int)WSKM::getConfig('cacheHtmlTime') + filemtime($htmlpath)) {
		//if (file_exists($htmlpath)) {
		$url .= (strExists($url,'?')?'&updatehtml=1':'?updatehtml=1');
		echo "ajaxCall('".$url."',function(){});";
	}

	exit();

}elseif ($act=='commentvote') {
	if (checkToken(URL_GET) && isMyReferer()) {
		$cmid=requestGet('id',TYPE_INT);
		if ($cmid <1) {
			xmlMessage('');
		}

		if (WSKM::getConfig('commentVote')) {
			$user=WSKM::user();
			if (!$user->isLogin()) {
				xmlMessage('-2');
			}
		}

		$objComment=usingModel('comment');
		$data=$objComment->vote($cmid);
		xmlMessage("$data");
	}
	xmlMessage('');
}elseif ($act=='updatevcode') {
	$rand=mt_rand(1,10000);
	$tabs=isset($_GET['tab'])?htmlspecialchars($_GET['tab']):'';
	xmlHeader();
	echo '<img onclick="updatevcode'.$tabs.'()" width="60" height="23" src="'.ART_URL.'vcode.php?random='.$rand.'"  alt="" />';
	xmlFooter();
	exit();
}elseif($act=='validvcode' && isset($_GET['vcode'])){
	$vaildcode=$_GET['vcode'];
	if (strlen($vaildcode) != (int)WSKM::getConfig('vcodelength')) {
		xmlMessage('vcode_err');
	}
	$user=WSKM::user();

	if($user->checkVcode($vaildcode)){
		xmlShow('okvcode');
	}
	else{
		xmlMessage('vcode_err');
	}
}elseif($act=='checkuname' && isset($_GET['uname'])){
	$uname=requestGet('uname',TYPE_STRING);

	$user=tigerUser();
	$res=$user->checkUserName($uname);
	if ($res==REGISTER_NAME_NONE) {
		xmlMessage('[ok]');
	}elseif ($res==REGISTER_NAME_ILLEGAL){
		xmlMessage('uname_illegal');
	}elseif ($res==REGISTER_NAME_PROTECT){
		xmlMessage('uname_protect');
	}elseif ($res==REGISTER_NAME_EXISTS){
		xmlMessage('uname_exists');
	}

	xmlMessage('unknow_error');

}elseif($act=='checkemail' && isset($_GET['email'])){
	$email=requestGet('email',TYPE_STRING);
	$user=tigerUser();
	$res=$user->checkEmail($email);
	if ($res==REGISTER_EMAIL_NONE) {
		xmlMessage('[ok]');
	}elseif ($res==REGISTER_EMAIL_ERROR){
		xmlMessage('email_format');
	}elseif ($res==REGISTER_EMAIL_PROTECT){
		xmlMessage('email_protect');
	}elseif ($res==REGISTER_EMAIL_EXISTS){
		xmlMessage('email_exists');
	}

	xmlMessage('unknow_error');
}elseif ($act=='swfupload'){
	$uid=requestPost('uid');
	$validhash=md5(substr(md5(ART_KEY), 8).$uid);
	if (!$_FILES['Filedata']['error'] && requestPost('hash') == $validhash) {

		$attach=array();
		foreach ($_FILES['Filedata'] as $key=>$value){
			$attach[$key][]=$value;
		}
		$_FILES['Filedata']=$attach;
		echo uploadArt('Filedata',0);
	}
	exit();
}elseif ($act=='upload'){
	$user=WSKM::user();
	if (!$user->islogin() && !checkToken()) {
		exit();
	}
	if (!isAccessFor('isupload')) {
		xmlMessage('no_access');
	}
	xmlMessage(uploadArt('postfile',1));
	exit();
}elseif($act == 'newskiss'){
	if (isMyReferer()) {
		$aid=requestGet('id',TYPE_INT);
		$type=requestGet('type',TYPE_INT);
		if ($aid <1) {
			xmlMessage('-1');
		}

		$kisscookie=base64_decode(wskm_cookie::getValue('newskiss'));
		if ($kisscookie && in_array($aid,explode(',',$kisscookie))) {
			xmlMessage('0');
		}
		$object=usingModel('article');
		$object->updateNewsKiss($aid,$type);
		
		$kisscookie=base64_encode(ltrim($kisscookie.','.$aid,','));
		wskm_cookie::write('newskiss',$kisscookie,2073600);
		xmlMessage('1');
	}
	xmlMessage('-1');
}

function uploadArt($varname,$isimage=1){
	usingArtFun('filesystem');
	WSKM::using('wskm_fileupload');
	$iswater=(int)WSKM::getConfig('isWaterMark');
	$watertype=null;
	if ($iswater) {
		$watertype=(int)WSKM::getConfig('waterMarkType');
	}

	$imageimpath=ART_UPLOAD_PATH;
	$waterposition=(int)WSKM::getConfig('waterMarkPosition');
	$attachs=uploadAttachment($varname,$imageimpath,$isimage,$watertype,null,array('pos'=>$waterposition,'alpha'=>50,'jpg'=>70));

	$attachhtml=array();
	foreach ($attachs as $key=> $attach){
		if (isset($attach['err'])) {
			$attachhtml[$key]['err']=$attach['err'];
		}elseif($attach['name']){
			$attachObj=usingAdminModel('attachment');
			$attachid=$attachObj->insert($uid,$attach['origin_name'],$attach['type'],$attach['size'],$attach['path'],$attach['width'],$attach['isimage'],$attach['isthumb']);
			$attachhtml[$key]['aid']=$attachid;
			$attachhtml[$key]['eid']=attachEncodeid($attachid);
			$attachhtml[$key]['width']=$attach['width'];
			$attachhtml[$key]['path']='attachments/'.htmlspecialchars($attach['path']);
			$attachhtml[$key]['icon'] = attachIcon($attach['ext'].' '.$attach['type']);
			$attachhtml[$key]['isimage'] = $attach['isimage'];
			$attachhtml[$key]['isthumb'] = $attach['isthumb'];
			$attachhtml[$key]['name'] = $attach['origin_name'];
		}
	}

	if (count($attachhtml) > 0) {
		WSKM::using('wskm_json');
		return jsonEncode($attachhtml);
	}
}

exit();
?>