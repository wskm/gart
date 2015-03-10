<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [WskmPHP] www.wskmphp.com 
 * $Id: user.php 280 2010-12-26 06:05:21Z ws99 $ 
 */

!defined('IN_WSKM') && exit('Access Denied');

class wskm_user extends wskm_core_base
{
	protected $sid='';
	protected $uid = 0;
	protected $uname = '';
	protected $groupid = 5;
	protected $styleid = 0;
	protected $adminid = 0;
	protected $password='';
	protected $salt='';

	protected $ip='';
	protected $vcode='';
	protected $lastvisit=0;
	protected $isbanned=false;
	protected $cookieexpire=604800;

	protected $info=array();
	public $db;

	protected $isexistsession = false;
	protected $isupdated = false;

	public function __construct(){
		$this->db=WSKM::SQL();
		$this->init();
		$this->load();
		if(loadCacheSystem('usergroup'.$this->getGroupid())){
			if(!(bool)WSKM::getValues('usergroup','isvisit')){
				showMessage('not_accessweb');
			}
		}
	}

	function __destruct()
	{
		$this->session_update();
	}

	private function init()
	{
		$this->sid=wskm_cookie::getValue('sid');
		if (empty($this->sid)) {
			$this->session_new();
		}
		else{
			$hashstr=wskm_cookie::getValue('userhash');
			$this->isexistsession=true;
		}

		$this->ip=USER_IP;
		$this->styleid=(int)WSKM::getConfig('styleId');
		$this->groupid=(int)WSKM::getConfig('groupId');
		list($this->password,$this->uid) = empty($hashstr) ? array('', 0) : wkAddslashes(explode("\t", wskm_encrypt::UNDES($hashstr)), 1);

	}

	public function load()
	{
		if (!$this->isexistsession) {
			return ;
		}

		$this->isexistsession=false;
		if ($this->sid)
		{
			if($this->uid > 0) {
				if ( $this->info = $this->db->fetch_first("SELECT s.*,u.uid,u.password,u.salt,u.uname,u.email,u.adminid,u.groupid,u.sex,u.lastvisit,u.timeoffset,u.timeformat,u.emailverify FROM ".TABLE_PREFIX."sessions s, ".TABLE_PREFIX."users u WHERE u.uid=s.uid AND s.sid='{$this->sid}' AND s.ip='{$this->ip}' AND u.uid='{$this->uid}' AND u.password='{$this->password}' ") ) {
					$this->isexistsession=true;
				}elseif ($this->info=$this->db->fetch_first("SELECT u.uid,u.password,u.salt,u.uname,u.email,u.adminid,u.groupid,u.sex,u.lastvisit,u.timeoffset,u.timeformat,u.emailverify FROM ".TABLE_PREFIX."users u WHERE uid='{$this->uid}' AND password='{$this->password}'  ")){
					$this->session_new();
				}elseif($this->info = $this->db->fetch_first("SELECT * FROM ".TABLE_PREFIX."sessions WHERE sid='$this->sid' AND ip='$this->ip' "))
				{
					$this->isexistsession=true;
					wskm_cookie::clear();
				}
			}
			else
			{
				$this->info = $this->db->fetch_first("SELECT * FROM ".TABLE_PREFIX."sessions WHERE sid='{$this->sid}' AND ip='{$this->ip}'");
				if ($this->info ) {
					$this->isexistsession=true;
					if( $this->info['uid'] > 0){
						$this->uid=$this->info['uid'];
						$this->info = array_merge($this->info, $this->db->fetch_first("SELECT * FROM ".TABLE_PREFIX."users WHERE uid='".$this->info['uid']."'"));
					}
				}
			}
		}

		$temp=(int)wskm_cookie::getValue('styleid');
		$this->styleid=  $temp > 0 ? $temp:($this->info['styleid'] ? $this->info['styleid'] : $this->styleid);
		
		$this->uid=(int)$this->uid;
		$this->uname = (string)$this->info['uname'];
		$this->groupid = (int)$this->info['groupid'];
		$this->adminid = (int)$this->info['adminid'];
		$this->lastvisit=(int)$this->info['lastvisit'];
		$this->password=$this->info['password'];
		$this->salt=$this->info['salt'];

		$temp=WSKM::getConfig('timeFormats');
		WSKM::setConfig('userTimeFormat',$temp[(int)$this->getInfo('timeformat')]);

		if (isset($this->info['timeoffset']) && $this->info['timeoffset'] != 99) {
			WSKM::setConfig('userTimeZone',$this->info['timeoffset']);
		}else{
			WSKM::setConfig('userTimeZone',WSKM::getConfig('timeZone'));
		}

		$this->vcode_read();

		if (empty($this->uid) || empty($this->uname)) {
			$this->uid=0;
			$this->uname='';
			$this->groupid=WSKM::getConfig('groupId');
			$this->adminid=0;
		}

	}

	public function vcode_read(){
		list($this->vcode,$time,$uid)=explode("\t",wskm_encrypt::UNDES(wskm_cookie::getValue('vcode')));
		if ($this->vcode && ($this->uid != $uid || WSKM_TIME > $time+3600)) {
			$this->vcode_new();
		}

		if (empty($this->vcode)) {
			$this->vcode_new();
		}

	}

	public function vcode_new(){
		$this->vcode=WSKM::random(8,0);
		wskm_cookie::write('vcode', wskm_encrypt::DES($this->vcode."\t".WSKM_TIME."\t".$this->uid), 3600);
	}

	protected function session_new(){
		$this->sid=$this->session_id();
		wskm_cookie::write('sid', $this->sid, $this->cookieexpire, true);

		$this->vcode_new();
		$this->isexistsession=false;
	}

	public function session_update(){
		if($this->isupdated)return true;
		$timestamp=WSKM_TIME;

		if($this->isexistsession) {
			$this->db->query("UPDATE ".TABLE_PREFIX."sessions SET uid='$this->uid', uname='$this->uname', groupid='$this->groupid',styleid='{$this->styleid}',viewtime='$timestamp' WHERE sid='$this->sid'");
		} else {
			$onlinehold=WSKM::getConfig('onlineHold');

			$this->db->exec("DELETE FROM ".TABLE_PREFIX."sessions WHERE sid='$this->sid' OR viewtime<($timestamp-$onlinehold) OR ('$this->uid'<>'0' AND uid='$this->uid') OR (uid='0' AND ip='$this->ip')");
			$this->db->exec("INSERT INTO ".TABLE_PREFIX."sessions (sid, ip, uid, uname, groupid,styleid, viewtime) VALUES ('{$this->sid}', '{$this->ip}', '{$this->uid}', '{$this->uname}', '{$this->groupid}','{$this->styleid}','$timestamp')");

			if($this->uid > 0 && $timestamp - $this->lastvisit > 21600) {
				$this->db->exec("UPDATE ".TABLE_PREFIX."users SET lastip='$this->ip', lastvisit='$timestamp' WHERE uid='$this->uid'");
			}
		}

		$this->isupdated=true;
	}

	public function getInfo($key)
	{
		return $this->info[$key];
	}

	public function getProfile(){
		return $this->info;
	}

	public function getIp()
	{
		return $this->ip;
	}

	public function getUid()
	{
		return $this->uid;
	}

	public function setUid($id){
		$this->uid=$id;
	}

	public function isLogin()
	{
		return $this->uid >0;
	}

	public function isAdmin()
	{
		return $this->adminid >0 ;
	}

	public function getSid()
	{
		return $this->Sid;
	}

	public function getStyleid()
	{
		return $this->styleid;
	}

	public function setStyleid($styleid)
	{
		return $this->styleid=$styleid;
	}

	public function getUname()
	{
		return $this->uname;
	}
	
	public function setUname($name)
	{
		$this->uname=$name;
	}

	public function getPassword()
	{
		return $this->password;
	}

	public function setPassword($pw)
	{
		$this->password=$pw;
	}

	public function getSalt(){
		return $this->salt;
	}

	public function setSalt($salt){
		$this->salt=$salt;
	}

	public function getGroupid()
	{
		return $this->groupid;
	}

	public function getEmail()
	{
		return $this->info['email'];
	}
	
	public function setEmail($mail)
	{
		$this->info['email']=$mail;
	}

	public function isEmailVerify()
	{
		return (bool)$this->info['emailverify'];
	}
	
	public function getAdminid()
	{
		return $this->adminid;
	}

	public function getVcode()
	{
		return $this->vcode;
	}

	public static function session_id(){
		return WSKM::random(8,0);
	}

	public function loginOk($issave=1)
	{
		$this->isexistsession=false;
		wskm_cookie::write('userhash',  wskm_encrypt::DES("$this->password\t$this->uid"), $issave?2592000:0, true);
	}

	public function clearVar(){
		$this->sid='';
		$this->uid=0;
		$this->uname='';
		$this->password='';
		$this->groupid=5;
		$this->adminid=0;
	}

	public function checkVcode($vaildcode) {
		if (!(bool)WSKM::getConfig('isVcode')) {
			return true;
		}
		$sourcecode=$this->getVcode();
		codeConvert($sourcecode,(int)WSKM::getConfig('vcodeType'));
		if (strtoupper($vaildcode) == $sourcecode) {
			return true;
		}

		return false;
	}

}

?>