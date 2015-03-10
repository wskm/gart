<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [WskmPHP] www.wskmphp.com 
 * $Id: wk_common.php 250 2010-11-28 17:57:29Z ws99 $ 
 */

!defined('IN_WSKM') && exit('Access Denied');

function clearPest($str){
	return preg_replace("/<scr(.*)\\/script>/is",'',$str);
}

function requestGet($key,$type='',$len=0)
{
	return wskm_request::GET($key,$type,$len);
}

function requestPost($key,$type='',$len=0)
{
	return wskm_request::POST($key,$type,$len);
}

function pathSame($path)
{
	$path = str_replace('./', '', $path);
	if(DIRECTORY_SEPARATOR == '\\') {
		$path = str_replace('/', '\\', $path);
	} elseif(DIRECTORY_SEPARATOR == '/') {
		$path = str_replace('\\', '/', $path);
	}
	return $path;
}

function getUrlName($isSuffix=true)
{
	return $isSuffix ? basename($_SERVER['SCRIPT_NAME']) :basename($_SERVER['SCRIPT_NAME'],'.php') ;
}

function getUrlFull()
{
	return strtolower(substr($_SERVER['SERVER_PROTOCOL'], 0, strpos($_SERVER['SERVER_PROTOCOL'], '/'))).'://'.$_SERVER['HTTP_HOST'].$_SERVER["REQUEST_URI"];
}

function getBaseUrl()
{
	$baseuri = null;
	$filename = basename($_SERVER['SCRIPT_FILENAME']);

	if (basename($_SERVER['SCRIPT_NAME']) === $filename) {
		$url = $_SERVER['SCRIPT_NAME'];
	} elseif (basename($_SERVER['PHP_SELF']) === $filename) {
		$url = $_SERVER['PHP_SELF'];
	} elseif (isset($_SERVER['ORIG_SCRIPT_NAME']) && basename($_SERVER['ORIG_SCRIPT_NAME']) === $filename) {
		$url = $_SERVER['ORIG_SCRIPT_NAME'];
	} else {
		$path    = $_SERVER['PHP_SELF'];
		$segs    = explode('/', trim($_SERVER['SCRIPT_FILENAME'], '/'));
		$segs    = array_reverse($segs);
		$index   = 0;
		$last    = count($segs);
		$url = '';
		do {
			$seg     = $segs[$index];
			$url = '/' . $seg . $url;
			++$index;
		} while (($last > $index) && (false !== ($pos = strpos($path, $url))) && (0 != $pos));
	}

	if (isset($_SERVER['HTTP_X_REWRITE_URL'])) {
		$request_uri = $_SERVER['HTTP_X_REWRITE_URL'];
	} elseif (isset($_SERVER['REQUEST_URI'])) {
		$request_uri = $_SERVER['REQUEST_URI'];
	} elseif (isset($_SERVER['ORIG_PATH_INFO'])) {
		$request_uri = $_SERVER['ORIG_PATH_INFO'];
		if (!empty($_SERVER['QUERY_STRING'])) {
			$request_uri .= '?' . $_SERVER['QUERY_STRING'];
		}
	} else {
		$request_uri = '';
	}

	if (0 === strpos($request_uri, $url)) {
		$baseuri = $url;
		return $baseuri;
	}

	if (0 === strpos($request_uri, dirname($url))) {
		$baseuri = rtrim(dirname($url), '/') . '/';
		return $baseuri;
	}

	if (!strpos($request_uri, basename($url))) {
		return '';
	}

	if ((strlen($request_uri) >= strlen($url))
	&& ((false !== ($pos = strpos($request_uri, $url))) && ($pos !== 0)))
	{
		$url = substr($request_uri, 0, $pos + strlen($url));
	}

	$baseuri = rtrim($url, '/') . '/';
	return $baseuri;
}

function getBaseDir()
{
	static $urlbase='';
	if ($urlbase=='') {
		$urlbase=getBaseUrl();
		$p=strrpos($urlbase,'/');
		if ($p !== false) {
			$urlbase=substr($urlbase,0,$p);
		}
		$urlbase = rtrim($urlbase, '/') . '/';
	}

	return $urlbase;
}

function getUrlPathInfo()
{
	return (isset($_SERVER['PATH_INFO'])) ? $_SERVER['PATH_INFO'] : @getenv('PATH_INFO');
}

function wkAddslashes($string, $force = 0) {
	!defined('MAGIC_QUOTES_GPC') && define('MAGIC_QUOTES_GPC', function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc());
	if(!MAGIC_QUOTES_GPC || $force) {
		if(is_array($string)) {
			foreach($string as $key => $val) {
				unset($string[$key]);
				$string[addslashes($key)] = wkAddslashes($val, $force);				
			}
		} else {
			$string = addslashes($string);
		}
	}
	return $string;
}

function wkImplode($arr, $comma=',') {
    return '\''.implode('\''.$comma.'\'', $arr).'\'';
}

function wkHtmlspecialchars($string) {
	if(is_array($string)) {
		foreach($string as $key => $val) {
			$string[$key] = wkHtmlspecialchars($val);
		}
		return $string; //array_map('wkHtmlspecialchars',$string);
	} else {
		return  htmlspecialchars($string);
	}
}

function wkHeader($string, $replace = true, $http_response_code = 0) {
	$string = str_replace(array("\r", "\n"), array('', ''), $string);
	if(empty($http_response_code) || PHP_VERSION < '4.3' ) {
		@header($string, $replace);
	} else {
		@header($string, $replace, $http_response_code);
	}
	if(preg_match('/^\s*location:/is', $string)) {
		exit();
	}
}

function wkHash($plus='') {
	return substr(md5(substr(WSKM_TIME, 0, 4).AUTH_KEY.USER_IP.$plus), 9, 9);
}

function authCode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
	$ckey_length = 4;
	$key = md5($key ? $key : AUTH_KEY);
	$keya = md5(substr($key, 0, 16));
	$keyb = md5(substr($key, 16, 16));
	$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

	$cryptkey = $keya.md5($keya.$keyc);
	$key_length = strlen($cryptkey);

	$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
	$string_length = strlen($string);

	$result = '';
	$box = range(0, 255);

	$rndkey = array();
	for($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
	}

	for($j = $i = 0; $i < 256; $i++) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}

	for($a = $j = $i = 0; $i < $string_length; $i++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}

	if($operation == 'DECODE') {
		if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
			return substr($result, 26);
		} else {
			return '';
		}
	} else {
		return $keyc.str_replace('=', '', base64_encode($result));
	}

}

function strExists($haystack, $needle) {
	return !(strpos($haystack, $needle) === FALSE);
}

function strCut($string, $length, $dot = '') {
	if(strlen($string) <= $length) {
		return $string;
	}

	$string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array('&', '"', '<', '>'), $string);

	$strcut = '';
	if(PAGE_CHARSET == 'utf-8') {

		$n = $tn = $noc = 0;
		while($n < strlen($string)) {

			$t = ord($string[$n]);
			if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
				$tn = 1; $n++; $noc++;
			} elseif(194 <= $t && $t <= 223) {
				$tn = 2; $n += 2; $noc += 2;
			} elseif(224 <= $t && $t <= 239) {
				$tn = 3; $n += 3; $noc += 2;
			} elseif(240 <= $t && $t <= 247) {
				$tn = 4; $n += 4; $noc += 2;
			} elseif(248 <= $t && $t <= 251) {
				$tn = 5; $n += 5; $noc += 2;
			} elseif($t == 252 || $t == 253) {
				$tn = 6; $n += 6; $noc += 2;
			} else {
				$n++;
			}

			if($noc >= $length) {
				break;
			}

		}
		if($noc > $length) {
			$n -= $tn;
		}

		$strcut = substr($string, 0, $n);

	} else {
		for($i = 0; $i < $length; $i++) {
			$strcut .= ord($string[$i]) > 127 ? $string[$i].$string[++$i] : $string[$i];
		}
	}

	$strcut = str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $strcut);

	return $strcut.$dot;
}

function getRobot() {
	if(!defined('IS_ROBOT')) {
		$spiders = 'Bot|Crawl|Spider|slurp|sohu-search|lycos|robozilla';
		$browsers = 'MSIE|Netscape|Opera|Konqueror|Mozilla';
		if(preg_match("/($browsers)/i", $_SERVER['HTTP_USER_AGENT'])) {
			define('IS_ROBOT', FALSE);
		} elseif(preg_match("/($spiders)/i", $_SERVER['HTTP_USER_AGENT'])) {
			define('IS_ROBOT', TRUE);
		} else {
			define('IS_ROBOT', FALSE);
		}
	}
	return IS_ROBOT;
}

function EXCEPTION_HANDLER($ex)
{
	if (!WSKM::getConfig('displayException')) { exit('Error exception! '); }

	$out = "<br /><b>My Exception</b> '" . get_class($ex) . "'<BR>";
	if ($ex->getMessage() != '') {
		$out .= " Message -> '" . $ex->getMessage() . "'<br>";
	}
	if (defined('IN_DEBUG') && IN_DEBUG ) {
		$out .= ' File -> ' . $ex->getFile() . ':' . $ex->getLine() . "<br>";
		$out .= ' Trace -> '.$ex->getTraceAsString();
	}

	echo $out;
	exit();
}

function throwException($className,$type='Exception')
{
	EXCEPTION_HANDLER($className);
}

function host() {
	return $_SERVER['HTTP_HOST'];
}

function multiPage_start(&$currentpage, $constpage, $totalnum,$isother=false) {
	if (!$isother) {
		$currentpage=(int)requestGet('page');
	}
	$totalpage = ceil($totalnum / $constpage);
	$currentpage =  max(1, min($totalpage, $currentpage));
	return ($currentpage - 1) * $constpage;

}

function multiPage($num, $curpage, $mpurl,$perpage=15, $maxpages = 0, $page = 6, $autogoto = true, $simple = false) {
	$ajaxtarget = !empty($_GET['ajaxtarget']) ? " ajaxtarget=\"".htmlspecialchars($_GET['ajaxtarget'])."\" " : '';
	if(defined('IN_ADMIN') && IN_ADMIN) {
		$showkbd = TRUE;
		$lang['prev'] = '&lsaquo;&lsaquo;';
		$lang['next'] = '&rsaquo;&rsaquo;';

	} else {
		$showkbd = FALSE;
		$lang['prev'] ='&lt;'; //'&lsaquo;&lsaquo;';
		$lang['next'] ='&gt;';//'&rsaquo;&rsaquo;';	// $GLOBALS['dlang']['nextpage'];
	}

	$multipage = '';
	$isuargv=false;
	if (is_array($mpurl)) {
		$isuargv=true;
	}
	else{
		$mpurl .= strpos($mpurl, '?') ? '&amp;' : '?';
	}

	$realpages = 1;
	if($num > $perpage) {
		$offset = 2;

		$realpages = @ceil($num / $perpage);
		$pages = $maxpages && $maxpages < $realpages ? $maxpages : $realpages;

		if($page > $pages) {
			$from = 1;
			$to = $pages;
		} else {
			$from = $curpage - $offset;
			$to = $from + $page - 1;
			if($from < 1) {
				$to = $curpage + 1 - $from;
				$from = 1;
				if($to - $from < $page) {
					$to = $page;
				}
			} elseif($to > $pages) {
				$from = $pages - $page + 1;
				$to = $pages;
			}
		}

		$lang['first']='1...';
		$multipage = ($curpage - $offset > 1 && $pages > $page ? '<a href="'.($isuargv ?(mvcUrl_page($mpurl,1)):$mpurl.'page=1' ).'" class="first"'.$ajaxtarget.'>'.$lang['first'].'</a>' : '').
		($curpage > 1 && !$simple ? '<a href="'.($isuargv ?(mvcUrl_page($mpurl,$curpage - 1)):$mpurl.'page='.($curpage - 1) ).'" class="prev"'.$ajaxtarget.'>'.$lang['prev'].'</a>' : '');
		for($i = $from; $i <= $to; $i++) {
			$multipage .= $i == $curpage ? '<span class="current">'.$i.'</span>' :
			'<a href="'.($isuargv ?(mvcUrl_page($mpurl,$i)):$mpurl.'page='.$i ).($ajaxtarget && $i == $pages && $autogoto ? '#' : '').'"'.$ajaxtarget.'>'.$i.'</a>';
		}

		$lang['last']='...'.$realpages;

		$multipage .= ($curpage < $pages && !$simple ? '<a href="'.($isuargv ?(mvcUrl_page($mpurl,$curpage + 1)):$mpurl.'page='.($curpage + 1) ).'" class="next"'.$ajaxtarget.'>'.$lang['next'].'</a>' : '').
		($to < $pages ? '<a href="'.($isuargv ?(mvcUrl_page($mpurl,$pages)):$mpurl.'page='.$pages ).'" class="last"'.$ajaxtarget.'>'.$lang['last'].'</a>' : '').
		($showkbd && !$simple && $pages > $page && !$ajaxtarget ? '<kbd><input type="text" name="custompage" size="3" onkeydown="if(event.keyCode==13) {window.location=\''.$mpurl.'page=\'+this.value; return false;}" /></kbd>' : '');

		$multipage = $multipage ? '<div class="pages">'.$multipage.'</div>' : '';
	}
	//$maxpage = $realpages;
	return $multipage;
}


function xmlHeader() {
	ob_end_clean();
	@header("Expires: -1");
	@header("Cache-Control: no-store, private, post-check=0, pre-check=0, max-age=0", FALSE);
	@header("Pragma: no-cache");
	header("Content-type: application/xml");
	echo "<?xml version=\"1.0\" encoding=\"".PAGE_CHARSET."\"?>\n<root><![CDATA[";
}

function xmlFooter() {
	echo ']]></root>';
}

function xmlMessage($text)
{
	$msg=WSKM::getValue('artmsg');
	xmlHeader();
	echo lang($text)?lang($text):$text.$msg;
	xmlFooter();
	exit();
}

function loadArray($path,$key='')
{
	static $configs=array();

	$ckey=$path;
	if (isset($configs[$ckey])) {
		if (!empty($key)) {
			return array($key=>$configs[$ckey]);
		}
		return $configs[$ckey];
	}

	$arr=array();

	$data=require($path);
	if (is_array($data)) {
		if ($key && is_string($key)) {
			$arr[$key]=$data;
		}
		else{
			$arr=$data;
		}

		$configs[$ckey]=$arr;
		unset($data,$arr);
		return $configs[$ckey];
	}

	throw new wskm_exception('load Array error! by :'.$path.' key:'.$key);
}

function debug()
{
	$args=func_get_args();
	foreach ($args as $arg){
		var_dump($arg);
	}
	exit();
}

function ipHide($str,$repace='*')
{
	$reg = '/((?:\d+\.){3})\d+/';
	return preg_replace($reg, "\\1$repace", $str);
}

function getUserIP()
{
	if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
		$onlineip = getenv('HTTP_CLIENT_IP');
	} elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
		$onlineip = getenv('HTTP_X_FORWARDED_FOR');
	} elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
		$onlineip = getenv('REMOTE_ADDR');
	} elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
		$onlineip = $_SERVER['REMOTE_ADDR'];
	}
	$onlineipmatches=array();
	preg_match("/[\d\.]{7,15}/", $onlineip, $onlineipmatches);
	$onlineip = $onlineipmatches[0] ? $onlineipmatches[0] : NULL;
	return $onlineip;
}

function arrayEval($array, $level = 0) {

	if(!is_array($array)) {
		return "'".$array."'";
	}
	if(is_array($array) && function_exists('var_export')) {
		return var_export($array, true);
	}

	$space = '';
	for($i = 0; $i <= $level; $i++) {
		$space .= "\t";
	}
	$evaluate = "Array\n$space(\n";
	$comma = $space;
	if(is_array($array)) {
		foreach($array as $key => $val) {
			$key = is_string($key) ? '\''.addcslashes($key, '\'\\').'\'' : $key;
			$val = !is_array($val) && (!preg_match("/^\-?[1-9]\d*$/", $val) || strlen($val) > 12) ? '\''.addcslashes($val, '\'\\').'\'' : $val;
			if(is_array($val)) {
				$evaluate .= "$comma$key => ".arrayEval($val, $level + 1);
			} else {
				$evaluate .= "$comma$key => $val";
			}
			$comma = ",\n$space";
		}
	}
	$evaluate .= "\n$space)";
	return $evaluate;
}

function now($time='',$format='')
{
	$time=$time?$time:time();
	if ($format=='') {
		$format=WSKM::getConfig('userTimeFormat');
	}elseif($format=='HOST'){
		$format=WSKM::getConfigs('timeFormats',0);
	}

	return date($format,$time);
}



function gotoUrl($url,$moved=false)
{
	if ($moved) {
		header("HTTP/1.1 301 Moved Permanently");
	}
	header('location:'.$url);	
	exit();
}

function showMessage($message='', $tourl = '', $time = 3) {
	$lang=lang($message);
	$message=empty($lang)?$message:$lang;
	$time = $time * 1000;

	$urlstr=$urlmsg='';
	if( $tourl === -1){$tourl= 'javascript:history.go(-1);';$urlmsg=lang('go_back');}
	elseif($tourl === 1){$tourl= 'javascript:location.href+=\'\';';}

	$urlmsg=empty($urlmsg)?lang('auto_redirect'):$urlmsg;
	if ($tourl==URL_HOME) {
		$tourl=getBaseDir();
	}
	if($tourl) $urlstr= "<p style='margin-top:10px;'><a href=\"$tourl\" id='notice_a' >$urlmsg</a></p>";
	if($time && $urlstr ) {
		$message .= "<script type='text/javascript'>function redirector() { window.location.replace(\"{$tourl}\"); }setTimeout('redirector();', $time);</script>";
	}

	$artmsg=WSKM::getValue('artmsg');
	echo '<html><head><meta http-equiv="Content-Type" content="text/html; charset='.PAGE_CHARSET.'" />'.
	'<title>WskmPHP Notice</title><style type="text/css">'.
	'body { margin:0 auto;padding:0 atuo;text-align:center;font-size:12px;background-color:#fff; font-family:verdana,Helvetica,sans-serif} #notice_msg{font-weight:bold;} '.
	'.notice{margin:150px auto; border: 1px dashed #E4E9F1;} .notice td{ padding:10px 10px; }'.
	'.notice a{ color:gray; text-decoration: underline ;font-size:12px;font-weight:none;}'.
	'.notice p{text-align:center;color:#C00000;font-size:14px;padding:0;margin:0; }'.
	'</style></head><body>'.
	'<div><table class="notice" id="notice"><tr><td align="center" ><p id="notice_msg" >'.$message.'</p>'.$urlstr.'</td></tr></table><div>'.
	"<script type='text/javascript'>function rand(min,max){	return parseInt(Math.random()*(max-min+1)+min); }function randColor(){	return 'rgb('+rand(0,200)+','+rand(0,200)+','+rand(0,200)+')';}".
	'document.getElementById("notice").style.borderColor=randColor();</script>'.$artmsg.
	'</body></html>';

	exit();
}

function lang($key)
{
	return wskm_lang::get($key,LANGUAGE);
}

function usingMVC($class)
{
	return WSKM::usingMVC($class);
}

function isMyReferer()
{
	$uphttp = parse_url($_SERVER['HTTP_REFERER']);
	$uphttp['host'] .= !empty($uphttp['port']) ? (':'.$uphttp['port']) : '';
	if($uphttp['host'] != $_SERVER["HTTP_HOST"] ) {
		return false;
	}
	return true;
}

function getUrlReferer($durl='index.php'){
	if ($_SERVER['HTTP_REFERER']) {
		$uphttp = parse_url($_SERVER['HTTP_REFERER']);
		$uphttp['host'] .= !empty($uphttp['port']) ? (':'.$uphttp['port']) : '';
		if($uphttp['host'] != $_SERVER["HTTP_HOST"] ) {
			return $durl;
		}

		return $_SERVER['HTTP_REFERER'];
	}

	return $durl;
}

function isEmail($str) {
	if(strlen($str)<3 || !preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/i", $str)){return false;}else {return true;}
}

function jsInclude($path,$ishtml=false)
{
	if (!empty($path)) {
		$html= "\n<script src=\"{$path}\" type=\"text/javascript\"></script>\n";
		if($ishtml) return $html;
		echo $html;
	}
	return '';
}

function jsWriter($content,$ishtml=false)
{
	$html = "\n<script type=\"text/javascript\">\n//<![CDATA[\n{$content}\n//]]>\n</script>\n";
	if($ishtml) return $html;
	echo $html;
}

function jsSFormat($html, $iswrite = 1)
{
	$html = addslashes(str_replace(array("\r", "\n"), '', $html));
	return $iswrite ? 'document.write("'.$html.'");' : $html;
}

function fExt($filename) {
	return trim(substr(strrchr($filename, '.'), 1, 10));
}

function codeConvert(& $code,$type=0,$length=4)
{
	if($type==1){
		$code= substr(base_convert($code,16,10),0,$length);
		return ;
	}
	$code=substr($code,-8);
	$format='';
	if ($type==0) {
		$format = sprintf('%04s', base_convert($code, 16, 22));
		$units = 'ABCDEFGHJKMPQRTVWXY12346789';
	}
	$code='';
	for($i = 0; $i < $length; $i++) {
		$unit = ord($format[$i]);
		$code .= ($unit >= 0x30 && $unit <= 0x39) ? $units[$unit - 0x30] : $units[$unit - 0x57];
	}
}

function getBytes($val) {
    $val = trim($val);
    $last = strtolower($val{strlen($val)-1});
    switch($last) {
        case 'g': 
        	$val *= 1024;
        case 'm': 
        	$val *= 1024;
        case 'k': 
        	$val *= 1024;
    }
    return $val;
}

?>