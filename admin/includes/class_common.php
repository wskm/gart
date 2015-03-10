<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [Gart] www.wskmphp.com 
 * $Id: class_common.php 280 2010-12-26 06:05:21Z ws99 $ 
 */

!defined('IN_ART') && exit('Access Denied');
class admin_common extends admin_auth
{
	public $islogin=false;

	public function __construct()
	{
		parent::__construct();

		$this->init();
		$this->load();
	}

	public function load(){}

	public function init()
	{
		$session=false;
		if ($this->getUid() < 1) {
			$this->islogin=false;
			adminTemplate('login');
			exit();
		}
		
		$this->initUser($this->getUid());
		if (!$this->isAdmin() ) {
			adminMessage('login_notaccess',ART_URL);
		}
		
		$session=$this->readSession();
		if ($session !== false) {

			$this->islogin=true;
			$this->updateSession();

			define('UNAME',$this->getUname());
			define('UID',$this->getUid());
			define('ADMINID',$this->getAdminid());
			define('GROUPID',$this->getGroupid());
		}else{
			$this->islogin=false;

			$navstr='';
			if ($_SERVER['QUERY_STRING']) {
				$navstr=str_replace('wskm=','nav=',$_SERVER['QUERY_STRING']);
				$navstr=str_replace('act=','menukey=',$navstr);
			}
			wskm_cookie::write('adminhash');
			//wskm_cookie::write('referernav', $navstr );
			adminTemplate('login');
			exit();
		}
	}
}

class admin_csync extends wskm_page_abstract
{
	static function sync($args=''){
		usingArtClass('cache');
		art_cache::update('static');
		if (IS_HTML) {
			art_cache::updateHtml($args);
		}
	}

}

?>