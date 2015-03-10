<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [WskmPHP] www.wskmphp.com 
 * $Id: type.php 16 2010-07-11 14:06:18Z ws99 $ 
 */

!defined('IN_WSKM') && exit('Access Denied');

class wskm_io_type{
	public static $filetype=array(
	'image' => array('gif', 'jpg', 'jpeg', 'png', 'bmp'),
	'video' => array('rm', 'rmvb', 'avi', 'mpg', 'mpeg','asf','mov','wmv','swf','mp4','3gp','3gpp','mkv','flv'),
	'music' => array('mp3', 'ogg', 'wav','wma','mid','ac3','dts'),
	'txt'=>array('txt','rtf','wri','chm','doc','ppt'),
	'php'=>array('php','jsp','asp','aspx','htm','html'),
	'rar' => array('tar', 'rar', 'zip', 'gz'),
	);
}

?>