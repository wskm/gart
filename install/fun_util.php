<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [Gart] www.wskmphp.com 
 * $Id: fun_util.php 36 2010-09-26 07:26:56Z ws99 $ 
 */

if(!defined('INSTALL_ART')) {
	exit('Access Denied');
}

function _GET($key){
	return $_GET[$key];
}

function _POST($key){
	return $_POST[$key];
}

function lang($key){
	return $GLOBALS['langs'][$key];
}

function function_check(&$funcs) {
	$isr=true;
	foreach($funcs as $key=>$item) {
		$funcs[$key]=function_exists($key);
		if (!$funcs[$key]) {
			$isr=false;
		}
	}
	return $isr;
}

function db_check($dbhost, $dbuser, $dbpw,$dbname) {

	$link=@mysql_connect($dbhost, $dbuser, $dbpw);
	if(!$link) {
		$error = mysql_error();
		return $error;
	}


	if(mysql_get_server_info() > '4.1') {
		mysql_query("CREATE DATABASE IF NOT EXISTS `$dbname` DEFAULT CHARACTER SET ".str_replace('-','',DB_CHARSET), $link);
	} else {
		mysql_query("CREATE DATABASE IF NOT EXISTS `$dbname`", $link);
	}
	if(mysql_errno()) {
		return mysql_error();
	}
	mysql_close($link);

	return true;
}

function random($length, $isnumber = 0) {
	$hashstr=md5(print_r($_SERVER, 1).microtime(true)).uniqid(mt_rand(), true);

	if ($isnumber) {
		$hashstr=sprintf('%u',crc32($hashstr));
		$hashstr.='012340567890';
	}else{
		$hashstr=base_convert($hashstr,16,35);
		$hashstr.=strtoupper($hashstr);
	}

	$res = '';
	$max = strlen($hashstr) - 1;
	for($i = 0; $i < $length; $i++) {
		$res .= $hashstr[mt_rand(0, $max)];
	}
	return $res;
}

function getUserIp() {
	$onlineip = '';
	if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
		$onlineip = getenv('HTTP_CLIENT_IP');
	} elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
		$onlineip = getenv('HTTP_X_FORWARDED_FOR');
	} elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
		$onlineip = getenv('REMOTE_ADDR');
	} elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
		$onlineip = $_SERVER['REMOTE_ADDR'];
	}
	return $onlineip;
}

function dir_access($dir) {
	$writeable = 0;
	if(!is_dir($dir)) {
		@mkdir($dir, 0777);
	}
	if(is_dir($dir)) {
		if($fp = @fopen("$dir/test.txt", 'w')) {
			@fclose($fp);
			@unlink("$dir/test.txt");
			$writeable = 1;
		} else {
			$writeable = 0;
		}
	}
	return $writeable;
}

function fsys_check(&$fsys) {
	$isr=true;
	foreach($fsys as $key => $item) {
		$fdpath = WEB_ROOT.$item['path'];
		if($item['type'] == 'dir') {
			if(!dir_access($fdpath)) {
				$fsys[$key]['err'] = 1;
				$isr=false;
			} else {
				$fsys[$key]['err'] = 0;
			}
		} else {
			if(file_exists($fdpath)) {
				if(is_writable($fdpath)) {
					$fsys[$key]['err'] = 0;
				} else {
					$fsys[$key]['err'] = 1;
					$isr=false;
				}
			} else {
				if(dir_access(dirname($fdpath))) {
					$fsys[$key]['err'] = 0;
				} else {
					$fsys[$key]['err'] = 1;
					$isr=false;
				}
			}
		}
	}
	return $isr;
}


function getBaseUrl()
{
	$baseuri = null;
	//if ($baseuri) { return $baseuri; }
	$filename = basename($_SERVER['SCRIPT_FILENAME']);

	if (basename($_SERVER['SCRIPT_NAME']) === $filename) {
		$url = $_SERVER['SCRIPT_NAME'];
	} elseif (basename($_SERVER['PHP_SELF']) === $filename) {
		$url = $_SERVER['PHP_SELF'];
	} elseif (isset($_SERVER['ORIG_SCRIPT_NAME']) && basename($_SERVER['ORIG_SCRIPT_NAME']) === $filename) {
		$url = $_SERVER['ORIG_SCRIPT_NAME']; // 1and1 shared hosting compatibility
	} else {
		// Backtrack up the script_filename to find the portion matching
		// php_self
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

	// Does the baseUrl have anything in common with the request_uri?
	if (isset($_SERVER['HTTP_X_REWRITE_URL'])) { // check this first so IIS will catch
		$request_uri = $_SERVER['HTTP_X_REWRITE_URL'];
	} elseif (isset($_SERVER['REQUEST_URI'])) {
		$request_uri = $_SERVER['REQUEST_URI'];
	} elseif (isset($_SERVER['ORIG_PATH_INFO'])) { // IIS 5.0, PHP as CGI
		$request_uri = $_SERVER['ORIG_PATH_INFO'];
		if (!empty($_SERVER['QUERY_STRING'])) {
			$request_uri .= '?' . $_SERVER['QUERY_STRING'];
		}
	} else {
		$request_uri = '';
	}

	if (0 === strpos($request_uri, $url)) {
		// full $url matches
		$baseuri = $url;
		return $baseuri;
	}

	if (0 === strpos($request_uri, dirname($url))) {
		// directory portion of $url matches
		$baseuri = rtrim(dirname($url), '/') . '/';
		return $baseuri;
	}

	if (!strpos($request_uri, basename($url))) {
		// no match whatsoever; set it blank
		return '';
	}

	// If using mod_rewrite or ISAPI_Rewrite strip the script filename
	// out of baseUrl. $pos !== 0 makes sure it is not matching a value
	// from PATH_INFO or QUERY_STRING
	if ((strlen($request_uri) >= strlen($url))
	&& ((false !== ($pos = strpos($request_uri, $url))) && ($pos !== 0)))
	{
		$url = substr($request_uri, 0, $pos + strlen($url));
	}

	$baseuri = rtrim($url, '/') . '/';
	return $baseuri;
}

function getBaseDir($isfull=false)
{
	$urlbase=getBaseUrl();	
	
	$p=strrpos($urlbase,'/');
	if ($p !== false) {
		$urlbase=substr($urlbase,0,$p);
	}
	
	$urlbase = rtrim($urlbase, '/') . '/';
	return $isfull?strtolower(substr($_SERVER['SERVER_PROTOCOL'], 0, strpos($_SERVER['SERVER_PROTOCOL'], '/'))).'://'.$_SERVER['HTTP_HOST'].$urlbase:$urlbase;
}

function strExists($haystack, $needle) {
	return !(strpos($haystack, $needle) === FALSE);
}

function shownotice($msg){
	if (is_array($msg) && $msg) {
		$s='<br/><ul class="err" >';
		$i=1;
		foreach ($msg as $m){
			$s.="<li>$i.$m</li>";
			$i++;
		}
		$s.='</ul>';
		echo $s;
	}else{
		echo $msg;
	}
}

function goback(){
	$s='<div class="center  mt10">
				<input type="button" value="'.lang('step_up').'" onclick="history.go(-1);" >
		</div>';
	echo $s;
}

function isEmail($str) {
	if(strlen($str)<3 || !preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/i", $str)){return false;}else {return true;}
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

function jsMessage($message){
	echo '<script type="text/javascript">putmsg(\''.addslashes($message).' \');</script>'."\n";
	flush();
	ob_flush();
}

function installDone(){	
	global $lang;
	
	@touch(WEB_ROOT.'cache/install.lock');
	if (file_exists(WEB_ROOT.'cache/install.lock')) {
		file_put_contents(WEB_ROOT.'cache/install.lock','Gart - '.date('r',time()));
	}
	echo '<script type="text/javascript">putmsg(\'-> over\');$(\'nextstep\').disabled=false;setTimeout(function(){window.location=\'index.php?step=5&lang='.$lang.'\';},3800);</script><iframe src="../" style="display:none"></iframe>'."\n";
}


function runSql($sql) {	 
	if(empty($sql)) return;

	global $db;
	$sql = str_replace("\r\n", "\n", $sql);
	$sql = str_replace(' `art_', ' `'.TABLE_PREFIX, $sql);
	$sql = str_replace("\r", "\n", $sql);
	$ret = array();
	$num = 0;
	
	foreach(explode(";\n", trim($sql)) as $query) {
		//$ret[$num] = '';
		$queries = explode("\n", trim($query));
		foreach($queries as $query) {
			$query=trim($query);
			if ($query && ($query[0] != '#') && ($query[0].$query[1] != '--') && ($query[0].$query[1] != '/*' )) {
				$ret[$num] .= $query;
			}
			
		}
		$num++;
	}
	unset($sql);


	foreach($ret as $query) {
		if($query) {
			if(substr($query, 0, 12) == 'CREATE TABLE') {
				$name = preg_replace("/CREATE TABLE [`]?([a-z0-9_]+)[`]? .*/is", "\\1", $query);

				jsMessage('Create table -> '.$name);
				//$db->query(createtable($query));
				$db->query($query);
			} else {
				$db->query($query);
			}

		}
	}

}

function createtable($sql) {
	$type = strtoupper(preg_replace("/^\s*CREATE TABLE\s+.+\s+\(.+?\).*(ENGINE|TYPE)\s*=\s*([a-z]+).*$/isU", "\\2", $sql));
	$type = in_array($type, array('MYISAM', 'HEAP')) ? $type : 'MYISAM';
	return preg_replace("/^\s*(CREATE TABLE\s+.+\s+\(.+?\)).*$/isU", "\\1", $sql).
	(mysql_get_server_info() > '4.1' ? " ENGINE=$type DEFAULT CHARSET=".str_replace('-','',DB_CHARSET ): " TYPE=$type");
}

function table_initdata(){
	global $db;
	jsMessage('-> Table init');
	$db->query('TRUNCATE TABLE '.TABLE_PREFIX.'users ');	
	$db->query('TRUNCATE TABLE '.TABLE_PREFIX.'articlemessages');
	$db->query('TRUNCATE TABLE '.TABLE_PREFIX.'articles');
	$db->query('TRUNCATE TABLE '.TABLE_PREFIX.'comments');
	$db->query('TRUNCATE TABLE '.TABLE_PREFIX.'category');	
	$db->query('TRUNCATE TABLE '.TABLE_PREFIX.'attachments');
	$db->query('TRUNCATE TABLE '.TABLE_PREFIX.'nav ');
	$db->query('TRUNCATE TABLE '.TABLE_PREFIX.'adminloginlog');
	
}

function dir_initdata($dir) {
	jsMessage('Clear cache -> '.$dir);
	$directory = dir($dir);
	while($entry = $directory->read()) {
		$filename = $dir.'/'.$entry;
		if(is_file($filename)) {
			@unlink($filename);
		}
	}
	$directory->close();
	@touch($dir.'/index.htm');
}



function uc_add_admin($username, $password, $useremail){
	global $err;
	
	$error = '';	
	include WEB_ROOT.'config'.DS.'config_sys.php';
	include WEB_ROOT.'uc_client'.DS.'client.php';
	$uid = uc_user_register($username, $password, $useremail);

	if($uid == -1 || $uid == -2) {
		$error = 'admin_username_invalid';
	} elseif($uid == -4 || $uid == -5 || $uid == -6) {
		$error = 'admin_email_invalid';
	} elseif($uid == -3) {
		$ucresult = uc_user_login($username, $password);		
		list($tmp['uid'], $tmp['username'], $tmp['password'], $tmp['email']) = uc_addslashes($ucresult);
		$ucresult = $tmp;
		if($ucresult['uid'] <= 0) {
			$error = 'admin_exist_password_error';
		} else {
			$uid = $ucresult['uid'];
			$email = $ucresult['email'];
			$password = $ucresult['password'];
		}
	}

	if(!$error && $uid > 0) {
		uc_user_addprotected($username,'Gart Admin');
	} else {
		$uid = 0;
		$error = empty($error) ? 'admin_add_err' : $error;
		$err[]=lang($error);
	}

	return $uid;
}

?>