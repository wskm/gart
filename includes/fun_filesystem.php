<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [Gart] www.wskmphp.com 
 * $Id: fun_filesystem.php 111 2010-10-02 14:42:58Z ws99 $ 
 */

!defined('IN_ART') && exit('Access Denied');

function attachIcon($type,$size='') {
	static $attachicons = array(
	0 => 'common.gif',
	1 => 'image.gif',
	2 => 'binary.gif',
	3 => 'rar.gif',
	4 => 'msoffice.gif',
	5 => 'text.gif',
	6 => 'html.gif',
	7 => 'real.gif',
	8 => 'av.gif',
	9 => 'flash.gif',
	10 => 'pdf.gif',
	11 => 'torrent.gif'
	);

	if(preg_match("/image|^(jpg|gif|png|bmp)/", $type)) {
		$typeid = 1;
	} elseif(preg_match("/bittorrent|^torrent/", $type)) {
		$typeid = 11;
	} elseif(preg_match("/pdf|^pdf/", $type)) {
		$typeid = 10;
	} elseif(preg_match("/flash|^(swf|fla|swi)/", $type)) {
		$typeid = 9;
	} elseif(preg_match("/audio|video|^(wav|mid|mp3|m3u|wma|asf|asx|vqf|mpg|mpeg|avi|wmv)/", $type)) {
		$typeid = 8;
	} elseif(preg_match("/real|^(rm|rv)/", $type)) {
		$typeid = 7;
	} elseif(preg_match("/htm|^(php|js|pl|cgi|asp)/", $type)) {
		$typeid = 6;
	} elseif(preg_match("/text|^(txt|rtf|wri|chm)/", $type)) {
		$typeid = 5;
	} elseif(preg_match("/word|powerpoint|^(doc|ppt)/", $type)) {
		$typeid = 4;
	} elseif(preg_match("/compressed|^(zip|arj|rar|arc|cab|lzh|lha|tar|gz)/", $type)) {
		$typeid = 3;
	} elseif(preg_match("/octet-stream|^(exe|com|bat|dll)/", $type)) {
		$typeid = 2;
	} else {
		$typeid = 0;
	}

	return $size.$attachicons[$typeid];
}



?>