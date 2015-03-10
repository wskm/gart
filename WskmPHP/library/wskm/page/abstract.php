<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [WskmPHP] www.wskmphp.com 
 * $Id: abstract.php 17 2010-08-26 11:37:04Z ws99 $ 
 */

!defined('IN_WSKM') && exit('Access Denied');

class wskm_page_abstract extends wskm_core_abstract {

	public $model=null;
	public $user=null;
		
	private $uid=0;
	private $uname='';
	private $adminid=0;
	private $groupid;

	public function getUid()
	{
		return $this->uid;
	}

	public function getUname(){
		return $this->uname;
	}

	public function getAdminid(){
		return $this->adminid;
	}

	public function getGroupid()
	{
		return $this->groupid;
	}

	public function __construct()
	{
		$this->page_init();
	}
		
	public function page_init()	{
		$this->sql();
		$this->user=WSKM::user();
		$this->uid=$this->user->getUid();
		$this->uname=$this->user->getUname();
		$this->adminid=$this->user->getAdminid();
		$this->groupid=$this->user->getGroupid();
	}

	public function sql()
	{
		$this->db=WSKM::SQL();
	}

	
	public function isLogin()
	{
		return $this->user->isLogin();
	}

	public function isAdmin()
	{
		return $this->user->isAdmin();
	}


}

?>