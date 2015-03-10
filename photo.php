<?php
/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [Gart] www.wskmphp.com 
 * $Id: photo.php 170 2010-10-20 10:12:48Z ws99 $ 
 */

error_reporting(0);
define('WEB_URL', ($_SERVER['HTTPS'] == 'on' ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST'].substr($_SERVER['PHP_SELF'], 0, strrpos($_SERVER['PHP_SELF'], '/')).'/');

$uid = isset($_GET['uid']) ? (int)$_GET['uid'] : 0;
$sizetype = isset($_GET['size']) ? $_GET['size'] : '';
$random = isset($_GET['random']) ? $_GET['random'] : '';
$isexists = isset($_GET['isexists']) ? (bool)$_GET['isexists'] : false;

if (!in_array($sizetype,array('s','m','b'))) {
	$sizetype='m';
}

$photourl='';
if ($uid <1) {
	if ($isexists) {
		echo 0;
		exit();
	}
	$photourl='images/common/default_photo_'.$sizetype.'.jpg';

}else{
	$photobase='./photo/'.substr( md5( round($uid/100) ) ,0,6).'/'.$uid.'_photo_'.$sizetype.'.jpg';
	
	if(file_exists(dirname(__FILE__).DIRECTORY_SEPARATOR.$photobase)) {
		if($isexists) {
			echo 1;
			exit;
		}
		$random = $random ? mt_rand() : '';
		$photourl = $random=='' ? $photobase : $photobase.'?random='.$random;
	} else {
		if($isexists) {
			echo 0;
			exit;
		}
		$photourl='images/common/default_photo_'.$sizetype.'.jpg';
	}
}

if($random=='') {
	header("HTTP/1.1 301 Moved Permanently");
	header("Last-Modified:".date('r'));
	header("Expires: ".date('r', time() + 86400));
}

header('Location: '.WEB_URL.$photourl); exit();

?>