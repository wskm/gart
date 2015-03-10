<?php

define('IN_DISCUZ', TRUE);

define('UC_CLIENT_VERSION', '1.5.0');	//note UCenter 版本标识
define('UC_CLIENT_RELEASE', '20081031');

define('API_DELETEUSER', 1);		//note 用户删除 API 接口开关
define('API_RENAMEUSER', 1);		//note 用户改名 API 接口开关
define('API_GETTAG', 1);		//note 获取标签 API 接口开关
define('API_SYNLOGIN', 1);		//note 同步登录 API 接口开关
define('API_SYNLOGOUT', 1);		//note 同步登出 API 接口开关
define('API_UPDATEPW', 1);		//note 更改用户密码 开关
define('API_UPDATEBADWORDS', 1);	//note 更新关键字列表 开关
define('API_UPDATEHOSTS', 1);		//note 更新域名解析缓存 开关
define('API_UPDATEAPPS', 1);		//note 更新应用列表 开关
define('API_UPDATECLIENT', 1);		//note 更新客户端缓存 开关
define('API_UPDATECREDIT', 1);		//note 更新用户积分 开关
define('API_GETCREDITSETTINGS', 1);	//note 向 UCenter 提供积分设置 开关
define('API_GETCREDIT', 1);		//note 获取用户的某项积分 开关
define('API_UPDATECREDITSETTINGS', 1);	//note 更新应用积分设置 开关

define('API_RETURN_SUCCEED', '1');
define('API_RETURN_FAILED', '-1');
define('API_RETURN_FORBIDDEN', '-2');

define('SITE_ROOT', substr(dirname(__FILE__), 0, -3));
define('IN_WSKM',true);
define('IN_ART',true);

//note 普通的 http 通知方式
if(!defined('IN_UC')) {

	error_reporting(0);
	set_magic_quotes_runtime(0);

	defined('MAGIC_QUOTES_GPC') || define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());
	$configsys=require_once SITE_ROOT.'config'.DIRECTORY_SEPARATOR.'config_sys.php';

	$_DCACHE = $get = $post = array();

	$code = @$_GET['code'];
	parse_str(_authcode($code, 'DECODE', UC_KEY), $get);
	if(MAGIC_QUOTES_GPC) {
		$get = _stripslashes($get);
	}

	require_once SITE_ROOT.'./uc_client/lib/db.class.php';


	$timestamp = time();
	if($timestamp - $get['time'] > 3600) {
		exit('Authracation has expiried');
	}
	if(empty($get)) {
		exit('Invalid Request');
	}
	$action = $get['action'];

	require_once SITE_ROOT.'./uc_client/lib/xml.class.php';
	$post = xml_unserialize(file_get_contents('php://input'));

	if(in_array($get['action'], array('test', 'deleteuser', 'renameuser', 'gettag', 'synlogin', 'synlogout', 'updatepw', 'updatebadwords', 'updatehosts', 'updateapps', 'updateclient', 'updatecredit', 'getcreditsettings', 'updatecreditsettings'))) {
		require_once SITE_ROOT.'./uc_client/lib/db.class.php';
		$GLOBALS['db'] = new ucclient_db();
		$GLOBALS['db']->connect($configsys['dbHost'], $configsys['dbUser'], $configsys['dbPassword'], $configsys['dbName'], $configsys['dbCharset'], $configsys['dbPconnect'], $configsys['tablePre']);

		$GLOBALS['tablepre'] =  $configsys['tablePre'];
		$GLOBALS['artkey'] =  $configsys['artkey'];
		$GLOBALS['cookiepre'] =  $configsys['cookiePre'];
		$GLOBALS['cookiedomain'] =  $configsys['cookieDomain'];
		$GLOBALS['cookiepath'] =  $configsys['cookiePath'];

		unset($configsys);
		$uc_note = new uc_note();
		exit($uc_note->$get['action']($get, $post));
	} else {
		exit(API_RETURN_FAILED);
	}

} else {
	$configsys=require_once SITE_ROOT.'config'.DIRECTORY_SEPARATOR.'config_sys.php';
	require_once SITE_ROOT.'./uc_client/lib/db.class.php';
	$GLOBALS['db'] = new ucclient_db();
	$GLOBALS['db']->connect($configsys['dbHost'], $configsys['dbUser'], $configsys['dbPassword'], $configsys['dbName'], $configsys['dbCharset'], $configsys['dbPconnect'], $configsys['tablePre']);

	$GLOBALS['tablepre'] =  $configsys['tablePre'];
	$GLOBALS['artkey'] =  $configsys['artkey'];
	$GLOBALS['cookiepre'] =  $configsys['cookiePre'];
	$GLOBALS['cookiedomain'] =  $configsys['cookieDomain'];
	$GLOBALS['cookiepath'] =  $configsys['cookiePath'];
	unset($configsys);
}

class uc_note {

	var $dbconfig = '';
	var $db = '';
	var $tablepre = '';
	var $appdir = '';

	function _serialize($arr, $htmlon = 0) {
		if(!function_exists('xml_serialize')) {
			include_once SITE_ROOT.'./uc_client/lib/xml.class.php';
		}
		return xml_serialize($arr, $htmlon);
	}

	function uc_note() {
		$this->appdir = substr(dirname(__FILE__), 0, -3);
		$this->dbconfig = $this->appdir.'./config.inc.php';
		$this->db = $GLOBALS['db'];
		$this->tablepre = $GLOBALS['tablepre'];
	}

	function test($get, $post) {
		return API_RETURN_SUCCEED;
	}

	function deleteuser($get, $post) {
		$uids = $get['ids'];
		!API_DELETEUSER && exit(API_RETURN_FORBIDDEN);
		$articles = array();

		$query = $this->db->query("SELECT cid, aid FROM ".$this->tablepre."articles WHERE uid IN ($uids) ORDER BY cid");
		while($tempi = $this->db->fetch_array($query)) {
			$articles[$tempi['cid']] .= ($articles[$tempi['cid']] ? ',' : '').$tempi['aid'];
		}

		if($articles) {
			foreach($articles as $cid => $aids) {
				$query = $this->db->query("SELECT filepath, isthumb FROM ".$this->tablepre."attachments WHERE aid IN ($aids)");
				while($attach = $this->db->fetch_array($query)) {
					$path=ART_UPLOAD_PATH.'/'.$attach['filepath'];
					@unlink($path);
					if ((int)$attch['isthumb'] >0 ) {
						@unlink(dirname($path).DS.'thumb'.DS.'9'.basename($path));
					}
				}

				foreach(array('articles', 'articlemessages','articletags', 'comments', 'attachements') as $value) {
					$this->db->query("DELETE FROM ".$this->tablepre."$value WHERE aid IN ($aids)", 'UNBUFFERED');
				}

			}

		}

		$this->db->query("DELETE FROM ".$this->tablepre."users WHERE uid IN ($uids)");
		$this->db->query("DELETE FROM ".$this->tablepre."userprotected WHERE uid IN ($uids)", 'UNBUFFERED');

		$query = $this->db->query("SELECT uid,filepath, isthumb FROM ".$this->tablepre."attachments WHERE uid IN ($uids)");
		while($attach = $this->db->fetch_array($query)) {
			$path=ART_UPLOAD_PATH.'/'.$attach['filepath'];
			@unlink($path);
			if ((int)$attch['isthumb'] >0 ) {
				@unlink(dirname($path).DS.'thumb'.DS.'9'.basename($path));
			}
		}
		$this->db->query("DELETE FROM ".$this->tablepre."attachments WHERE uid IN ($uids)");
		$this->db->query("DELETE FROM ".$this->tablepre."comments WHERE uid IN ($uids)", 'UNBUFFERED');

		return API_RETURN_SUCCEED;
	}

	function renameuser($get, $post) {
		$uid = $get['uid'];
		$usernameold = $get['oldusername'];
		$usernamenew = $get['newusername'];
		if(!API_RENAMEUSER) {
			return API_RETURN_FORBIDDEN;
		}

		$this->db->query("UPDATE ".$this->tablepre."articles SET uname='$usernamenew' WHERE uname='$uid'");
		$this->db->query("UPDATE ".$this->tablepre."users SET uname='$usernamenew' WHERE uid='$uid'");
		$this->db->query("UPDATE ".$this->tablepre."userprotected SET uname='$usernamenew' WHERE uid='$uid'");
		$this->db->query("UPDATE ".$this->tablepre."comments SET uname='$usernamenew' WHERE uid='$uid'");
		return API_RETURN_SUCCEED;
	}

	function gettag($get, $post) {
		$name = $get['id'];
		if(!API_GETTAG) {
			return API_RETURN_FORBIDDEN;
		}

		$return = array();
		return $this->_serialize($return, 1);
	}

	function synlogin($get, $post) {
		$uid = (int)$get['uid'];
		$username = $get['username'];
		$password= $get['password'];
		if(!API_SYNLOGIN) {
			return API_RETURN_FORBIDDEN;
		}
		$authkey=md5($GLOBALS['artkey'].$_SERVER['HTTP_USER_AGENT']);

		header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
		$uid = intval($uid);
		$query = $this->db->query("SELECT uname, uid, password FROM ".$this->tablepre."users WHERE uid='$uid'");
		if($member = $this->db->fetch_array($query)) {
			_setcookie('userhash', _authcode("{$member['password']}\t{$member['uid']}", 'ENCODE', $authkey), 2592000);
		}else{

			$user=array();
			$user['salt']= rand(10000000, 99999999);
			$user['password']=md5(uniqid().$user['salt']);//$password;
			$user['uid']=$uid;
			$user['email']='';
			$user['username']=$username;
			$ip=$_SERVER['REMOTE_ADDR'];
			$time=time();

			$sysconfig=require_once SITE_ROOT.'cache'.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'sys_settings.php';

			$timeoffset=$sysconfig['timeZone'];
			$groupid=(int)$sysconfig['regGroupId'];
			$styleid=(int)$sysconfig['styleId'];
			$adminid=0;

			if ($this->db->query("INSERT INTO ".$this->tablepre."users SET uid='{$user['uid']}', uname='{$user['username']}', password='{$user['password']}', email='{$user['email']}', createip='$ip',lastip='$ip', createtime='".$time."', salt='{$user['salt']}',groupid='{$groupid}',styleid='$styleid',timeoffset='$timeoffset',adminid='{$adminid}' ") !==false) {
				_setcookie('userhash', _authcode("{$user['password']}\t{$user['uid']}", 'ENCODE', $authkey), 2592000);
			}
		}
	}

	function synlogout($get, $post) {
		if(!API_SYNLOGOUT) {
			return API_RETURN_FORBIDDEN;
		}

		header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
		_setcookie('userhash', '', -86400 * 365);
		_setcookie('sid', '', -86400 * 365);
	}

	function updatepw($get, $post) {
		if(!API_UPDATEPW) {
			return API_RETURN_FORBIDDEN;
		}
		$username = $get['username'];
		$password = $get['password'];

		$salt=rand(10000000, 99999999);
		$newpw = md5(sha1($password).$salt);

		$this->db->query("UPDATE ".$this->tablepre."users SET password='$newpw',salt='$salt' WHERE username='$username'");
		return API_RETURN_SUCCEED;
	}

	function updatebadwords($get, $post) {
		if(!API_UPDATEBADWORDS) {
			return API_RETURN_FORBIDDEN;
		}
		$cachefile = $this->appdir.'./uc_client/data/cache/badwords.php';
		$fp = fopen($cachefile, 'w');
		$data = array();
		if(is_array($post)) {
			foreach($post as $k => $v) {
				$data['findpattern'][$k] = $v['findpattern'];
				$data['replace'][$k] = $v['replacement'];
			}
		}
		$s = "<?php\r\n";
		$s .= '$_CACHE[\'badwords\'] = '.var_export($data, TRUE).";\r\n";
		fwrite($fp, $s);
		fclose($fp);
		return API_RETURN_SUCCEED;
	}

	function updatehosts($get, $post) {
		if(!API_UPDATEHOSTS) {
			return API_RETURN_FORBIDDEN;
		}
		$cachefile = $this->appdir.'./uc_client/data/cache/hosts.php';
		$fp = fopen($cachefile, 'w');
		$s = "<?php\r\n";
		$s .= '$_CACHE[\'hosts\'] = '.var_export($post, TRUE).";\r\n";
		fwrite($fp, $s);
		fclose($fp);
		return API_RETURN_SUCCEED;
	}

	function updateapps($get, $post) {
		if(!API_UPDATEAPPS) {
			return API_RETURN_FORBIDDEN;
		}
		$UC_API = $post['UC_API'];

		$cachefile = $this->appdir.'./uc_client/data/cache/apps.php';
		$fp = fopen($cachefile, 'w');
		$s = "<?php\r\n";
		$s .= '$_CACHE[\'apps\'] = '.var_export($post, TRUE).";\r\n";
		fwrite($fp, $s);
		fclose($fp);

		if(is_writeable($this->appdir.'./config/config_sys.php')) {
			$configfile = trim(file_get_contents($this->appdir.'./config/config_sys.php'));
			//$configfile = substr($configfile, -2) == '? >' ? substr($configfile, 0, -2) : $configfile;
			$configfile = preg_replace("/define\('UC_API',\s*'.*?'\);/i", "define('UC_API', '$UC_API');", $configfile);
			if($fp = @fopen($this->appdir.'./config/config_sys.php', 'w')) {
				@fwrite($fp, trim($configfile));
				@fclose($fp);
			}
		}

		return API_RETURN_SUCCEED;
	}

	function updateclient($get, $post) {
		if(!API_UPDATECLIENT) {
			return API_RETURN_FORBIDDEN;
		}
		$cachefile = $this->appdir.'./uc_client/data/cache/settings.php';
		$fp = fopen($cachefile, 'w');
		$s = "<?php\r\n";
		$s .= '$_CACHE[\'settings\'] = '.var_export($post, TRUE).";\r\n";
		fwrite($fp, $s);
		fclose($fp);
		return API_RETURN_SUCCEED;
	}

	function updatecredit($get, $post) {
		if(!API_UPDATECREDIT) {
			return API_RETURN_FORBIDDEN;
		}
		$credit = $get['credit'];
		$amount = $get['amount'];
		$uid = $get['uid'];
		return API_RETURN_SUCCEED;
	}

	function getcredit($get, $post) {
		if(!API_GETCREDIT) {
			return API_RETURN_FORBIDDEN;
		}
	}

	function getcreditsettings($get, $post) {
		if(!API_GETCREDITSETTINGS) {
			return API_RETURN_FORBIDDEN;
		}
		$credits = array();
		return $this->_serialize($credits);
	}

	function updatecreditsettings($get, $post) {
		if(!API_UPDATECREDITSETTINGS) {
			return API_RETURN_FORBIDDEN;
		}
		return API_RETURN_SUCCEED;
	}
}

function _setcookie($var, $value, $life = 0, $prefix = 1) {
	global $cookiepre, $cookiedomain, $cookiepath, $timestamp, $_SERVER;
	setcookie(($prefix ? $cookiepre : '').$var, $value,
	$life ? $timestamp + $life : 0, $cookiepath,
	$cookiedomain, $_SERVER['SERVER_PORT'] == 443 ? 1 : 0);
}

function _authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
	$ckey_length = 4;

	$key = md5($key ? $key : UC_KEY);
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

function _stripslashes($string) {
	if(is_array($string)) {
		foreach($string as $key => $val) {
			$string[$key] = _stripslashes($val);
		}
	} else {
		$string = stripslashes($string);
	}
	return $string;
}