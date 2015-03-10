<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [Gart] www.wskmphp.com 
 * $Id: ucenter.php 85 2010-10-01 14:56:41Z ws99 $ 
 */

$ucpw=trim(_POST('ucpw'));
$ucurl='http://'.str_replace('http://','',rtrim(_POST('ucurl'),'/')) ;
$ucip=trim(_POST('ucip'));

if(!$ucip) {
	$temp = @parse_url($ucurl);
	$ucip = gethostbyname($temp['host']);

	if(ip2long($ucip) == -1 || ip2long($ucip) === FALSE) {
		$err[]=lang('ucenter_dns_error');
	}
}
if (empty($ucpw) || empty($ucurl)) {
	$err[]=lang('ucenter_pwurl_empty');
	return ;
}

if(!defined('UC_API')) {
	define('UC_API', '');
}

include WEB_ROOT.'uc_client/client.php';
$ucinfo = openurl($ucurl.'/index.php?m=app&a=ucinfo&release='.UC_CLIENT_RELEASE, 500, '', '', 1, $ucip);
list($status, $ucversion, $ucrelease, $uccharset, $ucdbcharset, $apptypes) = explode('|', $ucinfo);

if($status != 'UC_STATUS_OK') {
	$err[]=lang('ucenter_url_unreachable');
} else {

	$dbcharset = strtolower(DB_CHARSET ? str_replace('-', '', DB_CHARSET) : DB_CHARSET);
	$ucdbcharset = strtolower($ucdbcharset ? str_replace('-', '', $ucdbcharset) : $ucdbcharset);
	if(UC_CLIENT_VERSION > $ucversion) {
		$err[]=lang('ucenter_version_incorrect');
	} elseif($dbcharset && $ucdbcharset != $dbcharset) {
		$err[]=lang('ucenter_dbcharset_incorrect');
	}

	$app_tagtemplates='';
	$app_name='Gart';
	$app_url=rtrim(str_replace('install/','',getBaseDir(true)),'/'); //rtrim('http://'.ART_WEB_URL.ART_URL,'/');
	$app_type='OTHER';
	$postdata = "m=app&a=add&ucfounder=&ucfounderpw=".urlencode($ucpw)."&apptype=".urlencode($app_type)."&appname=".urlencode($app_name)."&appurl=".urlencode($app_url)."&appip=&appcharset=".PAGE_CHARSET.'&appdbcharset='.DB_CHARSET.'&'.$app_tagtemplates.'&release='.UC_CLIENT_RELEASE;

	$ucconfig = openurl($ucurl.'/index.php', 500, $postdata, '', 1, $ucip);
	if(empty($ucconfig)) {
		$err[]=lang('ucenter_api_add_app_error');
	} elseif($ucconfig == '-1') {
		$err[]=lang('ucenter_admin_invalid');
	} else {
		list($appauthkey, $appid) = explode('|', $ucconfig);
		if(empty($appauthkey) || empty($appid)) {
			$err[]=lang('ucenter_data_invalid');
		} elseif($succeed = save_uc_config($ucconfig."|$ucurl|$ucip")) {
			//$err[]=lang('app_reg_success');
		} else {
			$err[]=lang('config_unwriteable');
		}
	}

}

function save_uc_config($config ) {

	$success = false;
	$file=WEB_ROOT.'config'.DIRECTORY_SEPARATOR.'config_sys.php';

	list($appauthkey, $appid, $ucdbhost, $ucdbname, $ucdbuser, $ucdbpw, $ucdbcharset, $uctablepre, $uccharset, $ucapi, $ucip) = explode('|', $config);

	if($cfcontent = file_get_contents($file)) {
		$cfcontent = trim($cfcontent);

		$content='';
		if (strExists($cfcontent,'##USER##')) {
			if(preg_match("/##USER##[\n\r\t]*(.*?)[\n\r\t]*##ENDUSER##/s",$cfcontent,$match)){
				$content=$match[1];
			}
		}
		$link = mysql_connect($ucdbhost, $ucdbuser, $ucdbpw, 1);
		$uc_connnect = $link && mysql_select_db($ucdbname, $link) ? 'mysql' : '';

		$content = insertconfig($content, "/define\('UC_CONNECT',\s*'.*?'\);/i", "define('UC_CONNECT', '$uc_connnect');");
		$content = insertconfig($content, "/define\('UC_DBHOST',\s*'.*?'\);/i", "define('UC_DBHOST', '$ucdbhost');");
		$content = insertconfig($content, "/define\('UC_DBUSER',\s*'.*?'\);/i", "define('UC_DBUSER', '$ucdbuser');");
		$content = insertconfig($content, "/define\('UC_DBPW',\s*'.*?'\);/i", "define('UC_DBPW', '$ucdbpw');");
		$content = insertconfig($content, "/define\('UC_DBNAME',\s*'.*?'\);/i", "define('UC_DBNAME', '$ucdbname');");
		$content = insertconfig($content, "/define\('UC_DBCHARSET',\s*'.*?'\);/i", "define('UC_DBCHARSET', '$ucdbcharset');");
		$content = insertconfig($content, "/define\('UC_DBTABLEPRE',\s*'.*?'\);/i", "define('UC_DBTABLEPRE', '`$ucdbname`.$uctablepre');");
		$content = insertconfig($content, "/define\('UC_DBCONNECT',\s*'.*?'\);/i", "define('UC_DBCONNECT', '0');");
		$content = insertconfig($content, "/define\('UC_KEY',\s*'.*?'\);/i", "define('UC_KEY', '$appauthkey');");
		$content = insertconfig($content, "/define\('UC_API',\s*'.*?'\);/i", "define('UC_API', '$ucapi');");
		$content = insertconfig($content, "/define\('UC_CHARSET',\s*'.*?'\);/i", "define('UC_CHARSET', '$uccharset');");
		$content = insertconfig($content, "/define\('UC_IP',\s*'.*?'\);/i", "define('UC_IP', '$ucip');");
		$content = insertconfig($content, "/define\('UC_APPID',\s*'?.*?'?\);/i", "define('UC_APPID', '$appid');");
		$content = insertconfig($content, "/define\('UC_PPP',\s*'?.*?'?\);/i", "define('UC_PPP', '20');");

		if (strExists($cfcontent,'##USER##')) {
			$cfcontent=preg_replace("/##USER##[\n\r\t]*(.*?)[\n\r\t]*##ENDUSER##/s","\n##USER##\n".$content."\n##ENDUSER##\n",$cfcontent);
		}else{
			$cfcontent=preg_replace("/([\n\r\t]+)return\s+array\(/s","\n\n##USER##\n".$content."\n##ENDUSER##\n\nreturn array(",$cfcontent);
		}
		if(@file_put_contents($file, $cfcontent)) {
			$success = true;
		}
	}

	return $success;
}

function insertconfig($s, $find, $replace) {
	if(preg_match($find, $s)) {
		$s = preg_replace($find, $replace, $s);
	} else {
		$s .= "\r\n".$replace;
	}
	return $s;
}

function openurl($url, $limit = 0, $post = '', $cookie = '', $bysocket = FALSE, $ip = '', $timeout = 15, $block = TRUE) {
	$return = '';
	$matches = parse_url($url);
	$host = $matches['host'];
	$path = $matches['path'] ? $matches['path'].($matches['query'] ? '?'.$matches['query'] : '') : '/';
	$port = !empty($matches['port']) ? $matches['port'] : 80;

	if($post) {
		$out = "POST $path HTTP/1.0\r\n";
		$out .= "Accept: */*\r\n";
		//$out .= "Referer: $boardurl\r\n";
		$out .= "Accept-Language: zh-cn\r\n";
		$out .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
		$out .= "Host: $host\r\n";
		$out .= 'Content-Length: '.strlen($post)."\r\n";
		$out .= "Connection: Close\r\n";
		$out .= "Cache-Control: no-cache\r\n";
		$out .= "Cookie: $cookie\r\n\r\n";
		$out .= $post;
	} else {
		$out = "GET $path HTTP/1.0\r\n";
		$out .= "Accept: */*\r\n";
		//$out .= "Referer: $boardurl\r\n";
		$out .= "Accept-Language: zh-cn\r\n";
		$out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
		$out .= "Host: $host\r\n";
		$out .= "Connection: Close\r\n";
		$out .= "Cookie: $cookie\r\n\r\n";
	}
	$fp = @fsockopen(($ip ? $ip : $host), $port, $errno, $errstr, $timeout);
	if(!$fp) {
		return '';
	} else {
		stream_set_blocking($fp, $block);
		stream_set_timeout($fp, $timeout);
		@fwrite($fp, $out);
		$status = stream_get_meta_data($fp);
		if(!$status['timed_out']) {
			while (!feof($fp)) {
				if(($header = @fgets($fp)) && ($header == "\r\n" ||  $header == "\n")) {
					break;
				}
			}

			$stop = false;
			while(!feof($fp) && !$stop) {
				$data = fread($fp, ($limit == 0 || $limit > 8192 ? 8192 : $limit));
				$return .= $data;
				if($limit) {
					$limit -= strlen($data);
					$stop = $limit <= 0;
				}
			}
		}
		@fclose($fp);
		return $return;
	}
}

?>