<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [Gart] www.wskmphp.com 
 * $Id: wskm.php 235 2010-11-23 07:56:11Z ws99 $ 
 */

define('IN_ART',true);
define('IS_NOCACHE',true);
define('OBGZIP',false);

require('./includes/inc_plugin.php');
if (IS_POST ) {
	$id=requestPost('pollid',TYPE_INT);
	if ($id<1) {
		artMessage('request_error',ART_URL_FULL);
	}
	if ((bool)WSKM::getConfig('pollLevel') && !WSKM::user()->isLogin()) {
		artMessage('login_must',ART_URL_FULL.'poll.php?showid='.$id);
	}
	loadLang('article');
	$options=requestPost('polloptions',TYPE_ARRAY);
	if (count($options) > 0) {
		$pollObject=usingModel('poll');
		$str=$pollObject->putPoll($id,$options);
		artMessage($str,ART_URL_FULL.'poll.php?showid='.$id);
	}else{
		artMessage('poll_err_selected',ART_URL_FULL.'poll.php?showid='.$id);
	}
}

$id=(int)$_GET['id'];
$showid=(int)$_GET['showid'];
$isall=(bool)$_GET['isall'];
$type=(int)$_GET['type'];

if ($id < 1 && $showid < 1 && !$isall) {
	exit();
}

$pollObject=usingModel('poll');
loadLang('article');
if ($showid > 0) {
	$info=$pollObject->getPoll($showid);
	if ($info==false) {
		exit();
	}
	
	assign_var('others',$info);
	assign_var('pollid',$showid);
	template('poll_info');
	exit();
}elseif ($isall){
	$data=$pollObject->getPolls();
	$htmlpage=multiPage($data['count'],$data['page'],ART_URL.'poll.php?isall=1',$pollObject->pageCount);
	assign_var('list',$data['list']);
	assign_var('htmlpage',$htmlpage);
	template('poll');
	exit();
}

if ($type==0) {
	$info=$pollObject->getPollFor($id);
	if ($info==false) {
		exit();
	}
	$optionhtml='';
	foreach ($info['options'] as $tempi){
		$optionhtml.='<div class="artpoll_option" ><label><input type=\"'.$info['inputtype'].'" value="'.$tempi['optionid'].'" name="polloptions[]"/>'.$tempi['name'].'</label></div>';
	}
	echo 'document.write(\'<style type="text/css" >.artpollbtn{margin:8px 0 8px 0;} .artpoll_option{padding:2px 0} .artpollwrap h2{font-size:14px;font-weight:normal;border-bottom:dashed 1px #DBDBDB;padding:3px 12px} .artpolllist{padding:3px 8px} .artpollwrap input{margin-top:-2px;}</style><div class="artpollwrap"><form action="'.ART_URL_FULL.'poll.php" method="post" ><input type="hidden" name="pollid" value="'.$id.'" /><h2><span>'.$info['title'].'</span></h2><div class="artpolllist">'.$optionhtml.'<div class="artpollbtn" ><div class="abtnrb abtn fl"><button name="go" class="abtnlb" type="submit">'.lang('submit').'</button></div>&nbsp;<a href="'.ART_URL_FULL.'poll.php?showid='.$id.'" target="_blank" class="blue">'.lang('poll_data').'</a></div></div></form></div>\')';
	exit();
}else{
	$info=$pollObject->getPoll($id);
	if ($info==false) {
		exit();
	}
	
	assign_var('others',$info);
	assign_var('pollid',$id);
	ob_start();
	template('poll_block');
	$html=ob_get_contents();
	ob_clean();
	echo jsSFormat($html);
	exit();
}

?>