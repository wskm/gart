<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [WskmPHP] www.wskmphp.com 
 * $Id: abstract.php 280 2010-12-26 06:05:21Z ws99 $ 
 */

!defined('IN_WSKM') && exit('Access Denied');

define('LOGIN_ERROR_NOTEXIST','LOGIN_ERROR_NOTEXIST');
define('LOGIN_ERROR_PASSWORD','LOGIN_ERROR_PASSWORD');
define('LOGIN_ERROR_NONE',1);

define('REGISTER_NAME_ILLEGAL','REGISTER_NAME_ILLEGAL');
define('REGISTER_NAME_PROTECT', 'REGISTER_NAME_PROTECT');
define('REGISTER_NAME_EXISTS', 'REGISTER_NAME_EXISTS');
define('REGISTER_NAME_NONE',1);

define('REGISTER_EMAIL_ERROR', 'REGISTER_EMAIL_ERROR');
define('REGISTER_EMAIL_PROTECT', 'REGISTER_EMAIL_PROTECT');
define('REGISTER_EMAIL_EXISTS', 'REGISTER_EMAIL_EXISTS');
define('REGISTER_EMAIL_NONE', 1);

class wskm_user_abstract{
	public $user=null;
	public $db=null;
	public function __construct(){
		$this->user=WSKM::user();
		$this->db=$this->user->db;
	}

	public static function randomSalt(){
		return WSKM::random(8,0);
	}

	public static function randomPassword($length = 6)
	{
		$salt = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$saltlen = strlen($salt)-1;
		$makepass = '';
		for ($i = 0; $i < $length; $i ++) {
			$makepass .= $salt[mt_rand(0, $saltlen )];
		}
		return $makepass;
	}

	public static function isIllegalName($username) {

		$len = strlen($username);
		if($len > 15 || $len < 3 || preg_match("/\s+|[#%@,\:\*\'\;\"\s\<\>\&\?\!\`\~\^]|\xA1\xA1|^guest/is", $username)) {
			return true;
		} else {
			return false;
		}
	}

	public static function isUnameProtect($username)
	{
		$protect= WSKM::getConfig('unameProtect');
		if (!is_array($protect) ) {
			return false;
		}

		foreach ($protect as $v){
			if(trim($v) && strpos($username, $v) !== FALSE)
			{
				return true;
			}
		}
		
		return false;
	}

	public static function isEMailProtect($email)
	{
		$protect= (array)WSKM::getConfig('emailProtect');
		if (!$protect) {
			return false;
		}

		foreach ($protect as $v){
			if($v && strpos($email, $v) !== FALSE)
			{
				return true;
			}
		}
		return false;
	}

	public static function forgotPasswordKey($uid,$uname,$email){		
		return md5(substr(md5(ART_KEY),8).$uid.$uname.$email.substr(WSKM_TIME, 0, 6).USER_IP);
	}

}


interface wskm_iuser{
	function encrypt($password,$salt='');
	function login($name,$pw,$option='');
	function logout();
	function checkUserName($name);
	function checkEmail($email);
	function getUser($key,$option='');
	function addUser($username,$password,$email,$args='');
	function editUser($username,$oldpw,$newpw,$email,$args='');
	function deleteUser($uid,$args='');
	function setPhotoHtml($uid=0);
	function deletePhoto($uid);
	function samePassword($password);
}

?>