<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [WskmPHP] www.wskmphp.com 
 * $Id: wk_util.php 71 2010-09-30 12:26:28Z ws99 $ 
 */

!defined('IN_WSKM') && exit('Access Denied');

function toJs($content)
{
	return str_replace(array("\r", "\n"), array('', '\n'), addslashes($content));
}

function toText($str){
	$str=htmlspecialchars_decode($str);
	$str = preg_replace("/<sty(.*)\\/style>|<scr(.*)\\/script>|<!--(.*)-->/isU",'',$str);
	$str = str_replace(array('<br />','<br>','<br/>'), "\n", $str);
	$str = strip_tags($str);
	return $str;
}

function toHtml($text)
{
	return nl2br(str_replace(' ', '&nbsp;', htmlspecialchars($text)));
}

function fSize($filesize) {
	if($filesize >= 1073741824) {
		$filesize = round($filesize / 1073741824 * 100) / 100 . ' GB';
	} elseif($filesize >= 1048576) {
		$filesize = round($filesize / 1048576 * 100) / 100 . ' MB';
	} elseif($filesize >= 1024) {
		$filesize = round($filesize / 1024 * 100) / 100 . ' KB';
	} else {
		$filesize = $filesize . ' Bytes';
	}
	return $filesize;
}

function autoCharset($fContents,$from='',$to='utf-8'){
	$from   =  strtoupper($from)=='UTF8'? 'utf-8':$from;
	$to       =  strtoupper($to)=='UTF8'? 'utf-8':$to;
	if( strtoupper($from) === strtoupper($to) || empty($fContents) || (is_scalar($fContents) && !is_string($fContents)) ){
		return $fContents;
	}
	if(is_string($fContents) ) {
		if(function_exists('mb_convert_encoding')){
			return mb_convert_encoding ($fContents, $to, $from);
		}elseif(function_exists('iconv')){
			return iconv($from,$to,$fContents);
		}else{

			return $fContents;
		}
	}
	elseif(is_array($fContents)){
		foreach ( $fContents as $key => $val ) {
			$_key =     autoCharset($key,$from,$to);
			$fContents[$_key] = autoCharset($val,$from,$to);
			if($key != $_key ) {
				unset($fContents[$key]);
			}
		}
		return $fContents;
	}
	elseif(is_object($fContents)) {
		$vars = get_object_vars($fContents);
		foreach($vars as $key=>$val) {
			$fContents->$key = autoCharset($val,$from,$to);
		}
		return $fContents;
	}
	else{
		return $fContents;
	}
}

function getCrlf()
{
	if (stristr($_SERVER['HTTP_USER_AGENT'], 'Win'))
	{
		$the_crlf = '\r\n';
	}
	elseif (stristr($_SERVER['HTTP_USER_AGENT'], 'Mac'))
	{
		$the_crlf = '\r';
	}
	else
	{
		$the_crlf = '\n';
	}

	return $the_crlf;
}

function getGUID(){
	return WSKM::SQL()->fetch_column("select uuid() as GUID");
}

function array2jsarr($phparr,$jsarrname="")
{
	$str = "new Array(";
	$str = $jsarrname=="" ? $str : "$jsarrname = ".$str;
	$i = 0;
	while( list($a,$b)=each($phparr) )
	{
		$str .= $i++>0 ? "," : "";
		$str .= is_array($b) ? phparr_to_jsarr( $b ) : "\"".str_replace("\"","\\\"",str_replace("\\","\\\\",$b))."\"";
	}
	$str .=")";
	$str = $jsarrname=="" ? $str : $str.";";
	return $str;
}

function makeSemiangle($str)
{
	$arr = array('０' => '0', '１' => '1', '２' => '2', '３' => '3', '４' => '4',
	'５' => '5', '６' => '6', '７' => '7', '８' => '8', '９' => '9',
	'Ａ' => 'A', 'Ｂ' => 'B', 'Ｃ' => 'C', 'Ｄ' => 'D', 'Ｅ' => 'E',
	'Ｆ' => 'F', 'Ｇ' => 'G', 'Ｈ' => 'H', 'Ｉ' => 'I', 'Ｊ' => 'J',
	'Ｋ' => 'K', 'Ｌ' => 'L', 'Ｍ' => 'M', 'Ｎ' => 'N', 'Ｏ' => 'O',
	'Ｐ' => 'P', 'Ｑ' => 'Q', 'Ｒ' => 'R', 'Ｓ' => 'S', 'Ｔ' => 'T',
	'Ｕ' => 'U', 'Ｖ' => 'V', 'Ｗ' => 'W', 'Ｘ' => 'X', 'Ｙ' => 'Y',
	'Ｚ' => 'Z', 'ａ' => 'a', 'ｂ' => 'b', 'ｃ' => 'c', 'ｄ' => 'd',
	'ｅ' => 'e', 'ｆ' => 'f', 'ｇ' => 'g', 'ｈ' => 'h', 'ｉ' => 'i',
	'ｊ' => 'j', 'ｋ' => 'k', 'ｌ' => 'l', 'ｍ' => 'm', 'ｎ' => 'n',
	'ｏ' => 'o', 'ｐ' => 'p', 'ｑ' => 'q', 'ｒ' => 'r', 'ｓ' => 's',
	'ｔ' => 't', 'ｕ' => 'u', 'ｖ' => 'v', 'ｗ' => 'w', 'ｘ' => 'x',
	'ｙ' => 'y', 'ｚ' => 'z',
	'（' => '(', '）' => ')', '〔' => '[', '〕' => ']', '【' => '[',
	'】' => ']', '〖' => '[', '〗' => ']', '“' => '[', '”' => ']',
	'‘' => '[', '’' => ']', '｛' => '{', '｝' => '}', '《' => '<',
	'》' => '>',
	'％' => '%', '＋' => '+', '—' => '-', '－' => '-', '～' => '-',
	'：' => ':', '。' => '.', '、' => ',', '，' => '.', '、' => '.',
	'；' => ',', '？' => '?', '！' => '!', '…' => '-', '‖' => '|',
	'”' => '"', '’' => '`', '‘' => '`', '｜' => '|', '〃' => '"',
	'　' => ' ');

	return strtr($str, $arr);
}

function get_object_vars_deep($obj)
{
	if(is_object($obj))
	{
		$obj = get_object_vars($obj);
	}
	elseif(is_array($obj))
	{
		foreach ($obj as $key => $value)
		{
			$obj[$key] = get_object_vars_deep($value);
		}
	}
	return $obj;
}

function strEncodeIn($str)
{
	if (LANGUAGE=='zh'&&PAGE_CHARSET=='utf-8') {
		$str=iconv('gbk','utf-8',$str);
	}
	return $str;
}

function strEncodeOut($str)
{
	if (LANGUAGE=='zh'&&PAGE_CHARSET=='utf-8') {
		$str=iconv('utf-8','gbk',$str);
	}
	return $str;
}




function wkGetEnv($var_name) {
	if (isset($_SERVER[$var_name])) {
		return $_SERVER[$var_name];
	} elseif (isset($_ENV[$var_name])) {
		return $_ENV[$var_name];
	} elseif (getenv($var_name)) {
		return getenv($var_name);
	} elseif (function_exists('apache_getenv')
	&& apache_getenv($var_name, true)) {
		return apache_getenv($var_name, true);
	}

	return '';
}

function imgThumb($size, $smthumb = 50) {
	if($size[0] <= $smthumb && $size[1] <= $smthumb) {
		return array('w' => $size[0], 'h' => $size[1]);
	}
	$sm = array();
	$x_ratio = $smthumb / $size[0];
	$y_ratio = $smthumb / $size[1];
	if(($x_ratio * $size[1]) < $smthumb) {
		$sm['h'] = ceil($x_ratio * $size[1]);
		$sm['w'] = $smthumb;
	} else {
		$sm['w'] = ceil($y_ratio * $size[0]);	//$smthumb>= w or h
		$sm['h'] = $smthumb;
	}
	return $sm;
}

?>