<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [WskmPHP] www.wskmphp.com 
 * $Id: file.php 16 2010-07-11 14:06:18Z ws99 $ 
 */

!defined('IN_WSKM') && exit('Access Denied');

define('DOWNLOAD_NONE',0);
define('DOWNLOAD_CONTINUE',1);

class wskm_http_file
{

	static function out($attach,$downtype=0){
		if (!is_array($attach) && $attach['time'] && $attach['filepath'] && $attach['filetype']) {
			throw new wskm_exception('The first parameter is not an array or an error!');
		}
		$downtype=DOWNLOAD_NONE;

		ob_end_clean();
		$time=gmdate('D, d M Y H:i:s', $attach['time']);

		$attach['filename']='"'.(strtolower(PAGE_CHARSET) == 'utf-8' && strExists($_SERVER['HTTP_USER_AGENT'], 'MSIE') ? urlencode($attach['filename']) : $attach['filename']).'"';
		$range = 0;
		$filesize=filesize($attach['filepath']);

		header('Date: '.$time.' GMT');
		header('Last-Modified: '.$time.' GMT');
		header('Content-Encoding: none');
		header('Content-Type: '.$attach['filetype']);
		header('Content-Length: '.$filesize);
		if ($attach['isimage']) {
			header('Content-Disposition: inline; filename='.$attach['filename']);
		} else {
			header('Content-Disposition: attachment; filename='.$attach['filename']);
		}

		self::localFile($attach['filepath'],$downtype,$range);
	}


	static function localFile($file,$downtype=0,$range=0){
		@readfile($file);
		@flush();
		@ob_flush();
	}

}

?>