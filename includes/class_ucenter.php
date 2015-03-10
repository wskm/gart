<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [Gart] www.wskmphp.com 
 * $Id: class_ucenter.php 280 2010-12-26 06:05:21Z ws99 $ 
 */

!defined('IN_ART') && exit('Access Denied');

WSKM::using('wskm_user_abstract');
class art_ucenter extends wskm_user_abstract implements wskm_iuser
{
	function __construct(){
		parent::__construct();

		require_once ART_ROOT.'uc_client'.DS.'client.php';
	}

	function encrypt($password,$salt=''){
		return md5(md5($password).$salt);
	}

	function login($name,$pw,$option=''){
		$nametype=0;
		if (is_array($option) && $option['type']=='email') {
			$nametype=2;
		}
		$ucresult = uc_user_login($name, $pw, $nametype);
		list($tmp['uid'], $tmp['uname'], $tmp['password'], $tmp['email'], $duplicate) = wkAddslashes($ucresult, 1);
		$ucresult = $tmp;

		if (isset($option['check'])) {
			return (int)$ucresult['uid'];
		}

		if ($ucresult['uid'] ==-1 ) {
			return LOGIN_ERROR_NOTEXIST;
		}
		elseif($ucresult['uid'] ==-2) {
			return LOGIN_ERROR_PASSWORD;
		}
		elseif ($ucresult['uid'] >0 ){
			$user=$this->db->fetch_first('SELECT uid,uname,password,salt,email,groupid FROM '.TABLE_PREFIX."users WHERE uid='{$ucresult['uid']}' ");

			if (!$user) {
				$user=$ucresult;
				$user['salt']= WSKM::random(8,0);
				$user['password']=md5(sha1($user['password']).$user['salt']);
				$ip=USER_IP;
				$time=WSKM_TIME;
				$timeoffset=WSKM::getConfig('timeZone');
				$user['groupid']=(int)WSKM::getConfig('regGroupId');
				$adminid=0;
				if ($this->db->exec("INSERT INTO ".TABLE_PREFIX."users SET uid='{$user['uid']}', uname='{$user['uname']}', password='{$user['password']}', email='{$user['email']}', createip='$ip',lastip='$ip', createtime='".$time."', salt='{$user['salt']}',groupid='{$user['groupid']}',timeoffset='$timeoffset',adminid='{$adminid}' ") ===false) {
					return -4;
				}
			}

			if(addslashes($user['email']) != $ucresult['email']) {
				$this->db->exec('UPDATE '.TABLE_PREFIX."users SET email='{$ucresult['email']}' WHERE uid= '{$ucresult['uid']}'  ");
			}

			$this->user->initVal($user);
			$this->user->setPassword($user['password']);
			$this->user->setSalt($user['salt']);

			$this->user->loginOk((bool)$option['save']);
			$ucsynhtml=uc_user_synlogin($user['uid'] );
			WSKM::setValue('artmsg',$ucsynhtml);
			return LOGIN_ERROR_NONE;

		}
		return -4;
	}

	function logout(){
		$ucsynhtml=uc_user_synlogout($this->user->getUid());
		WSKM::setValue('artmsg',$ucsynhtml);
		wskm_cookie::clear();
		$this->user->clearVar();
	}

	function checkUserName($name){
		if(self::isIllegalName($name))
		{
			return REGISTER_NAME_ILLEGAL;
		}elseif (self::isUnameProtect($name)){
			return REGISTER_NAME_PROTECT;
		}

		$res=uc_user_checkname($name);
		if ($res==-1){
			return REGISTER_NAME_ILLEGAL;
		}elseif ($res==-2){
			return REGISTER_NAME_PROTECT;
		}elseif ($res==-3){
			return REGISTER_NAME_EXISTS;
		}

		return REGISTER_NAME_NONE;
	}

	function checkEmail($email){
		if(!isEmail($email)){
			return REGISTER_EMAIL_ERROR;
		}elseif( self::isEMailProtect($email) ){
			return REGISTER_EMAIL_PROTECT;
		}

		$res=uc_user_checkemail($email);
		if($res == -6){
			return REGISTER_EMAIL_EXISTS;
		}elseif($res == -5){
			return REGISTER_EMAIL_PROTECT;
		}elseif ($res != 1){
			return REGISTER_EMAIL_ERROR;
		}

		return REGISTER_EMAIL_NONE;
	}

	function getUser($name,$option=''){
		$wherestr=" uid='$name' ";
		if (is_array($option) && isset($option['type']) ) {
			if($option['type'] == 'uname' ){
				$wherestr=" uname='$name' ";
			}elseif($option['type'] == 'email' ){
				$wherestr=" email='$name' ";
			}
		}

		return $this->db->fetch_first('SELECT * FROM '.TABLE_PREFIX."users WHERE $wherestr ");
	}

	function addUser($username,$password,$email,$args=''){
		$ucuid=uc_user_register($username,$password,$email);
		if ($ucuid<0) {
			return false;
		}

		$salt = self::randomSalt();
		$password_md = md5(sha1($password).$salt);
		$time=WSKM_TIME;
		$ip=USER_IP;
		$timeoffset=(int)WSKM::getConfig('timeZone');
		$groupid=(int)WSKM::getConfig('regGroupId');
		$adminid=0;

		if (is_array($args)) {
			if (isset($args['groupid']) && $args['groupid'] > 0 ) {
				$groupid=(int)$args['groupid'];
			}
			if (isset($args['adminid']) &&  $args['adminid'] > 0 ) {
				$adminid=(int)$args['adminid'];
			}

			if (isset($args['timezone']) && $args['timezone']) {
				$timeoffset=(int)$args['timezone'];
			}

		}

		if ($this->db->exec("INSERT INTO ".TABLE_PREFIX."users SET uid='$ucuid',uname='$username', password='$password_md', email='$email', createip='$ip',lastip='$ip', createtime='".$time."', salt='$salt',groupid='$groupid',timeoffset='$timeoffset',adminid='{$adminid}' ") !==false) {
			$autologin=true;
			if (isset($args['autologin'])) {
				$autologin=(bool)$args['autologin'];
			}
			if($autologin){
				$this->user->setUid($ucuid);
				$this->user->setPassword($password_md);
				$this->user->setUname($username);
				$this->user->setEmail($email);
				$this->user->loginOk();
			}
			return $ucuid;
		}
		return false;
	}

	function editUser($uid,$oldpw,$newpw,$email,$args=''){
		$wherestr=" uid='$uid' ";
		$username='';
		$isforgotpassword=false;

		if (is_array($args)) {
			if ($args['uname']) {
				$wherestr=" uname= '{$args['uname']}' ";
				$username =$args['uname'];
			}elseif ( (int)$args['uid'] >0 ) {
				$wherestr=" uid= '{$args['uid']}' ";
				$uid =(int)$args['uid'];
			}

			if ((bool)$args['isforce'] == true) {
				$isforgotpassword=true;
			}
		}

		if (empty($username) && $uid==0) {
			return false;
		}
		$ucres=uc_user_edit($username,$oldpw,$newpw,$email,$isforgotpassword);
		if ($ucres==-1) {
			return -1;
		}elseif ($ucres==-8) {
			return -8;
		}elseif ($ucres==-7){
			return true;
		}elseif ($ucres < 0) {
			return false;
		}

		$password=$salt='';
		if ($newpw) {
			$salt = self::randomSalt();
			$password = md5(sha1($newpw).$salt);
		}

		$update=array();
		if ($email) {
			$update['email']=$email;
		}
		if ($password && $salt) {
			$update['password']=$password;
			$update['salt']=$salt;
		}
		if (count($update) > 0 ) {
			return $this->db->update( TABLE_PREFIX.'users',$update,$wherestr ) !== false;
		}
		return true;
	}

	function deleteUser($uid,$args=''){
		$this->deletePhoto($uid);
		if (!$this->db->delete(TABLE_PREFIX.'users',"uid='$uid' ")) {
			return false;
		}
		return uc_user_delete($uid);
	}

	function setPhotoHtml($uid=0){
		return uc_avatar($uid);
	}

	function deletePhoto($uid){
		return uc_user_deleteavatar($uid);
	}

	public function samePassword($password){

		$ucinfo=uc_user_login($this->user->getUid(),$password,1);
		if((int)$ucinfo[0] > 0){
			return true;
		}
		return false;
	}
}

?>