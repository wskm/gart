<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [Gart] www.wskmphp.com 
 * $Id: class_user.php 280 2010-12-26 06:05:21Z ws99 $ 
 */

!defined('IN_ART') && exit('Access Denied');

WSKM::using('wskm_user_abstract');
class art_user extends wskm_user_abstract implements wskm_iuser
{
	function encrypt($password,$salt=''){
		return md5(sha1($password).$salt);
	}

	function login($name, $password,$option=''){
		$wherestr='';
		if (is_array($option) && $option['type']=='email') {
			$wherestr =" email='{$name}' ";
		}else{
			$wherestr="  uname='$name' ";
		}

		$user=(array)$this->db->fetch_first('SELECT uid,uname,password,salt,email,groupid FROM '.TABLE_PREFIX."users WHERE $wherestr ");
		if ($user['uid'] <1 ) {
			return LOGIN_ERROR_NOTEXIST;
		}

		$this->user->setPassword($user['password']);
		$this->user->setSalt($user['salt']);
		if(!$this->samePassword($password)) {
			$this->user->setPassword('');
			$this->user->setSalt('');
			return LOGIN_ERROR_PASSWORD;
		}

		if (isset($option['check']) ) {
			return (int)$user['uid'];
		}

		$this->user->initVal($user);
		$this->user->loginOk((bool)$option['save']);
		return LOGIN_ERROR_NONE;
	}

	function logout()
	{
		wskm_cookie::clear();
		$this->user->clearVar();

	}

	public function addUser($username, $password, $email,$args='') {
		$salt = self::randomSalt();
		$password = $this->encrypt($password, $salt);
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
		if ($this->db->exec("INSERT INTO ".TABLE_PREFIX."users SET uname='$username', password='$password', email='$email', createip='$ip',lastip='$ip', createtime='".$time."', salt='$salt',groupid='$groupid',timeoffset='$timeoffset',adminid='{$adminid}' ") !==false) {
			$uid = $this->db->insert_id();
			$autologin=true;
			if (isset($args['autologin'])) {
				$autologin=(bool)$args['autologin'];
			}
			if($autologin){
				$this->user->setUid($uid);
				$this->user->setPassword($password);
				$this->user->setUname($username);
				$this->user->setEmail($email);
				$this->user->loginOk();
			}
			return $uid;
		}
		return false;
	}

	function checkUserName($name){
		if(self::isIllegalName($name))
		{
			return REGISTER_NAME_ILLEGAL;
		}elseif (self::isUnameProtect($name)){
			return REGISTER_NAME_PROTECT;
		}
		elseif ($this->db->fetch_column("SELECT uid FROM ".TABLE_PREFIX."users WHERE uname='$name'") !== false){
			return REGISTER_NAME_EXISTS;
		}

		return REGISTER_NAME_NONE;
	}

	function checkEmail($email){
		if(!isEmail($email)){
			return REGISTER_EMAIL_ERROR;
		}elseif( self::isEMailProtect($email) ){
			return REGISTER_EMAIL_PROTECT;
		}elseif($this->db->fetch_column("SELECT uid FROM ".TABLE_PREFIX."users WHERE email='$email'") !== false){
			return REGISTER_EMAIL_EXISTS;
		}

		return REGISTER_EMAIL_NONE;
	}

	function getUser($uid,$option=''){
		$wherestr=" uid='$uid' ";
		if (is_array($option) && isset($option['type']) ) {
			if($option['type'] == 'uname' ){
				$wherestr=" uname='$uid' ";
			}elseif($option['type'] == 'email' ){
				$wherestr=" email='$uid' ";
			}
		}

		return $this->db->fetch_first('SELECT * FROM '.TABLE_PREFIX."users WHERE $wherestr ");
	}

	function editUser($uid,$oldpw,$newpw,$email,$args=''){
		$wherestr=" uid='$uid' ";
		$username='';
		$isforgotpassword=false;

		if (is_array($args)) {
			if ($uid < 1) {
				if ($args['uname']) {
					$wherestr=" uname= '{$args['uname']}' ";
					$username =$args['uname'];
				}elseif ( (int)$args['uid'] >0 ) {
					$wherestr=" uid= '{$args['uid']}' ";
					$uid =(int)$args['uid'];
				}
			}

			if ((bool)$args['isforce'] == true) {
				$isforgotpassword=true;
			}
		}

		if (empty($username) && $uid==0) {
			return false;
		}

		$password=$salt='';
		if (!$isforgotpassword && $oldpw && $newpw) {
			$info=$this->getUser($uid > 0? $uid:$username,$uid > 0?'':array('type'=>'uname'));
			if(!$this->samePassword($oldpw,$info['password'],$info['salt'] )) {
				return -1;
			}

			$salt = self::randomSalt();
			$password = $this->encrypt($newpw, $salt);
		}
		elseif ($isforgotpassword && $newpw) {
			$salt = self::randomSalt();
			$password = $this->encrypt($newpw, $salt);
		}

		$update=array();
		//		if ($username) {
		//			$update['uname']=$username;
		//		}
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
		//edit
		$this->deletePhoto($uid);
		return $this->db->delete(TABLE_PREFIX.'users'," uid='{$uid}'") !== false;
	}

	function setPhotoHtml($uid=0){
		return '<form action="'.mvcUrl('',array('user','photo'),ART_URL.URL_HOME).'"  enctype="multipart/form-data" method="POST" ><input type="hidden" name="arthash" value="'.ART_HASH.'" ><div>'.lang('photo_update').'&nbsp;&nbsp;<input type="file" name="photo[]" >&nbsp;&nbsp;<input type="submit"  class="submit" value="'.lang('submit').'" name="pgo" ></div></form>';
	}

	function deletePhoto($uid){
		$photodir=getPhotoDir($uid);
		foreach (array('s','m','b') as $fname){
			wskm_io::fDelete($photodir.$uid.'_photo_'.$fname.'.jpg');
		}
	}

	public function samePassword($password){
		if ($this->user->getPassword() == $this->encrypt($password,$this->user->getSalt())) {
			return true;
		}
		return false;
	}

}

?>