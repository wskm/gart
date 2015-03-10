<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [Gart] www.wskmphp.com 
 * $Id: class_auth.php 280 2010-12-26 06:05:21Z ws99 $ 
 */

!defined('IN_ART') && exit('Access Denied');
class admin_auth
{	
	public $adminaccess='';
	public $userinfo='';
	public $model=null;
	public $db=null;
		
	private $uid=0;
	private $uname='';
	private $adminid=0;
	private $groupid;
	private $email;
	
	public function __construct()
	{
		$this->db=WSKM::SQL();
		$this->uid=wskm_encrypt::UNDES(wskm_cookie::getValue('adminhash'));
	}
	
	public function login($uname,$password)
	{
		$user=tigerUser();
		$uid=$user->login($uname,$password,array('check'=>1));
		if (!is_numeric($uid)) {
			return 0;
		}
		return $uid;
	}
	
	public function logout()
	{
		$this->db->exec('DELETE FROM '.TABLE_PREFIX.'adminsessions WHERE uid = \''.$this->getUid().'\' ');
		wskm_cookie::write('adminhash');
		$this->islogin=false;
	}
	
	public function readSession($life=1200)
	{
		$this->db->exec('DELETE FROM '.TABLE_PREFIX."adminsessions WHERE gotime < '".(WSKM_TIME-$life)."' ");		
		return $this->db->fetch_first('SELECT uid,logintime,gotime,ip FROM '.TABLE_PREFIX."adminsessions WHERE uid='{$this->getUid()}' AND uname='{$this->getUname()}' AND adminid='{$this->getAdminid()}'  ");
	}

	public function getAdminList(){
		return $this->db->fetch_all('SELECT  * FROM '.TABLE_PREFIX.'adminsessions');
	}
	
	public function insertSessiong()
	{
		$time=WSKM_TIME;
		$ip=USER_IP;
		return $this->db->exec('INSERT INTO '.TABLE_PREFIX."adminsessions (uid,uname,adminid,gotime,logintime,ip)VALUES('{$this->getUid()}','{$this->getUname()}','{$this->getAdminid()}','$time','$time','{$ip}')  ")  !== false;
	}

	public function updateSession()
	{		
		$this->db->exec('UPDATE '.TABLE_PREFIX."adminsessions SET  gotime='".WSKM_TIME."' WHERE uid='{$this->getUid()}' ") ;
	}

	public function isManageAccess(){
		if (ADMINID != 1) {
			return false;
		}
		return true;
	}
	
	public function initUser($uid){
		$user=tigerUser();
		$this->userinfo=$user->getUser($uid);
		
		$this->uid=$this->userinfo['uid'];
		$this->uname=$this->userinfo['uname'];
		$this->adminid=$this->userinfo['adminid'];
		$this->groupid=$this->userinfo['groupid'];
		$this->email=$this->userinfo['email'];
	}
	
	public function getUserInfo($key){
		return $this->userinfo[$key];
	}
	
	public function getUid()
	{
		return $this->uid;
	}

	public function getUname(){
		return $this->uname;
	}
	
	public function getEmail(){
		return $this->email;
	}

	public function getAdminid(){
		return $this->adminid;
	}
	
	public function isAdmin(){
		return $this->adminid > 0;
	}

	public function getGroupid()
	{
		return $this->groupid;
	}
		
}

?>