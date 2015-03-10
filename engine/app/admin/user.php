<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [Gart] www.wskmphp.com 
 * $Id: user.php 103 2010-10-02 14:07:39Z ws99 $ 
 */

!defined('IN_ART') && exit('Access Denied');

class app_admin_user extends admin_common
{
	public $Auser=null;
	function load(){
		if (!$this->isManageAccess()) {
			adminMessage('access_not');	
		}
		
		$this->model=usingAdminModel('user');
		loadLang('admin_user');
	}

	function doIndex(){

		$data= $this->model->getUsers();
		$keys=$data['keys'];

		$url="index.php?wskm=user";
		$url .='&'.http_build_query($keys);
		$htmlpage=multiPage($data['count'],$data['page'],$url,$this->model->adminPageCount);

		if (!$keys['status'] && !$keys['top'] && !$keys['digest']) {
			assign_var('isallnews',1);
		}

		assign_var('skeys',$keys);
		assign_var('htmlpage',$htmlpage);
		assign_var('groups',$this->model->getUserGroups());
		assign_var('users',$data['list']);
		adminTemplate('user');
	}

	function doEdit(){
		if (checkToken()) {
			$uid=requestPost('uid',TYPE_INT);
			if ($uid<1) {
				adminMessage('request_error','index.php');
			}

			$newpw=requestPost('newpw',TYPE_STRING,32);
			if((strlen($newpw)>0 && strlen($newpw)<6) || strlen($newpw)>32){
				adminMessage('password_length',-1);
			}
			elseif($newpw && $newpw != addslashes($newpw)) {
				adminMessage('password_illegal',-1);
			}

			$groupid=requestPost('groupid',TYPE_INT);
			$group=$this->model->getGroupInfo($groupid);
			if ($group===false) {
				adminMessage('group_not',-1);
			}
			$adminid=$group['adminid'];
			$birthday=requestPost('birthday',TYPE_STRING);
			$update=array(
			'groupid'=>$groupid,
			'adminid'=>$adminid,
			'sex'=>requestPost('sex',TYPE_INT),
			'timeoffset'=>requestPost('timeoffset',TYPE_STRING,4),
			'timeformat'=>requestPost('timeformat',TYPE_INT),
			'showemail'=>requestPost('showemail',TYPE_INT),
			'sendemail'=>requestPost('sendemail',TYPE_INT),
			);
			if (preg_match("/[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}/",$birthday)) {
				$update['birthday']=$birthday;
			}

			$email=requestPost('email');
			if (!isEmail($email)) {
				$email='';
			}
			$this->Auser=tigerUser();
			if(requestPost('delphoto',TYPE_BOOL)){
				$this->Auser->deletePhoto($uid);
			}
			$info=$this->Auser->getUser($uid);

			if ($email && $info['email'] != $email) {
				$res=$this->Auser->checkEmail($email);
				if ($res==REGISTER_EMAIL_ERROR){
					adminMessage('email_format',-1);
				}elseif ($res==REGISTER_EMAIL_PROTECT){
					adminMessage('email_protect',-1);
				}elseif ($res==REGISTER_EMAIL_EXISTS){
					adminMessage('email_exists',-1);
				}
			}

			if(!$this->model->updateUser($update,$uid)){
				adminMessage('edit_error',-1);
			}

			if( ! $this->Auser->editUser($uid,'',$newpw,$email,array('isforce'=>true,'uname'=>$info['uname'])) ){
				adminMessage('edit_error',-1);
			}
			adminMessage('edit_ok',getUrlReferer());

		}else{
			$uid=requestGet('id',TYPE_INT);
			if ($uid<1) {
				adminMessage('request_error','index.php');
			}

			$this->Auser=tigerUser();
			$info=$this->Auser->getUser($uid);
			WSKM::using('wskm_version');
			WSKM::helper('html');
			$utz=$info['timeoffset'];
			if ($utz==99) {
				$utz=WSKM::getConfig('timeZone');
			}
			$timezone_option=html_select_option(wskm_version::$timezone,$utz);
			assign_var('timezone_option',$timezone_option);
			assign_var('info',$info);
			assign_var('uid',$uid);
			assign_var('timeFormats',WSKM::getConfig('timeFormats'));
			assign_var('groups',$this->model->getUserGroups());
			assign_var('action','edit');
			adminTemplate('user_info');
		}
	}

	function doAdd(){
		if (checkToken()) {
			$uname=requestPost('uname',TYPE_STRING);
			$newpw=requestPost('newpw',TYPE_STRING);
			$email=requestPost('email',TYPE_STRING);

			if(strlen($newpw)<6){
				adminMessage('password_length',-1);
			}
			elseif(!$newpw || $newpw != addslashes($newpw)) {
				adminMessage('password_illegal',-1);
			}

			$this->Auser=tigerUser();
			$res=$this->Auser->checkUserName($uname);
			if ($res==REGISTER_NAME_ILLEGAL){
				adminMessage('uname_illegal',-1);
			}elseif ($res==REGISTER_NAME_PROTECT){
				adminMessage('uname_protect',-1);
			}elseif ($res==REGISTER_NAME_EXISTS){
				adminMessage('uname_exists',-1);
			}
			
			if ($email) {
				$res=$this->Auser->checkEmail($email);
				if ($res==REGISTER_EMAIL_ERROR){
					adminMessage('email_format',-1);
				}elseif ($res==REGISTER_EMAIL_PROTECT){
					adminMessage('email_protect',-1);
				}elseif ($res==REGISTER_EMAIL_EXISTS){
					adminMessage('email_exists',-1);
				}
			}

			$uid=$this->Auser->addUser($uname,$newpw,$email,array('autologin'=>false));
			if($uid==false){
				adminMessage('user_adderr');
			}

			$groupid=requestPost('groupid',TYPE_INT);
			$group=$this->model->getGroupInfo($groupid);
			if ($group===false) {
				adminMessage('group_not',-1);
			}
			$adminid=$group['adminid'];
			$birthday=requestPost('birthday',TYPE_STRING);
			$update=array(
			'groupid'=>$groupid,
			'adminid'=>$adminid,
			'sex'=>requestPost('sex',TYPE_INT),
			'timeoffset'=>requestPost('timeoffset',TYPE_STRING,4),
			'timeformat'=>requestPost('timeformat',TYPE_INT),
			'showemail'=>requestPost('showemail',TYPE_INT),
			'sendemail'=>requestPost('sendemail',TYPE_INT),
			);
			if (preg_match("/[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}/",$birthday)) {
				$update['birthday']=$birthday;
			}
			if(!$this->model->updateUser($update,$uid)){
				adminMessage('user_adderr',-1);
			}

			adminMessage('user_addok','index.php?wskm=user');
		}else{
			WSKM::using('wskm_version');
			WSKM::helper('html');
			assign_var('action','add');
			$timezone_option=html_select_option(wskm_version::$timezone,WSKM::getConfig('timeZone'));
			assign_var('timezone_option',$timezone_option);
			assign_var('timeFormats',WSKM::getConfig('timeFormats'));
			assign_var('groups',$this->model->getUserGroups());
			assign_var('info',array('sendemail'=>1,'groupid'=>7));
			adminTemplate('user_info');
		}
	}

	function doDel(){
		$uid=requestGet('id',TYPE_INT);
		if ($uid>0) {
			$this->Auser=tigerUser();
			if(!$this->Auser->deleteUser($uid)){
				adminMessage('del_error',-1);
			}
			adminMessage('del_ok','index.php?wskm=user');
		}
		adminMessage('request_error','index.php');
	}

	function doBatch()
	{
		if (checkToken()) {
			$acttype=requestPost('acttype');
			$selects=requestPost('selectid',TYPE_ARRAY);
			$movegid=requestPost('movegid',TYPE_INT);
			if (count($selects) <1) {
				adminMessage('select_oneuser',-1);
			}

			if ($acttype=='del') {
				$user=tigerUser();
				foreach ($selects as $id){
					if(!$user->deleteUser($id)){
						adminMessage('del_error',-1);
					}
				}
				adminMessage('del_ok','index.php?wskm=user');
			}
			elseif($acttype=='movegroup'){
				if ($movegid<1) {
					adminMessage('select_onegroup',-1);
				}
				$group=$this->model->getGroupInfo($movegid);
				if ($group===false) {
					adminMessage('group_not',-1);
				}
				$adminid=$group['adminid'];
				foreach ($selects as $id){
					if(!$this->model->updateUser(array('groupid'=>$movegid,'adminid'=>$adminid),$id)){
						adminMessage('edit_error',-1);
					}
				}
				adminMessage('edit_ok',getUrlReferer());
			}

			adminMessage('request_error','index.php');
		}
	}

	function doUserGroup(){
		if (checkToken()) {
			$data=requestPost('data');
			$dels=requestGet('del',TYPE_ARRAY);
			foreach ($dels as $key ){
				if (!$this->model->deleteUserGroup((int)$key)) {
					adminMessage('del_error',-1);
				}
			}
			
			if ($data[1]) {
				$data[1]['isvisit']=1;
			}
			
			$names=array();
			foreach ($data as $key=>$tempi){
				$tempi['groupname']=trim($tempi['groupname']);
				if (in_array($key,$dels) || !$tempi['groupname']) {
					continue;
				}

				$names[]=$tempi['groupname'];

				if (!isset($tempi['isvisit'])) {
					$tempi['isvisit']=0;
				}			
				if (!isset($tempi['isarticle'])) {
					$tempi['isarticle']=0;
				}				
				if (!isset($tempi['isarticlefree'])) {
					$tempi['isarticlefree']=0;
				}				
				if (!isset($tempi['isupload'])) {
					$tempi['isupload']=0;
				}
				
				if(!$this->model->updateUserGroup($tempi,$key)){
					adminMessage('edit_error',-1);
				}
			}

			$news=requestPost('new',TYPE_ARRAY);
			foreach ($news as $key=>$tempi){
				if (empty($tempi['groupname']) || in_array($tempi['groupname'],$names)) {
					continue;
				}

				$tempi['adminid']=(int)$tempi['adminid'];
				$tempi['accesslevel']=(int)$tempi['accesslevel'];
				if (!isset($tempi['isvisit'])) {
					$tempi['isvisit']=0;
				}			
				if (!isset($tempi['isarticle'])) {
					$tempi['isarticle']=0;
				}
				if (!isset($tempi['isarticlefree'])) {
					$tempi['isarticlefree']=0;
				}
				if (!isset($tempi['isupload'])) {
					$tempi['isupload']=0;
				}
				if(!$this->model->insertUserGroup($tempi) ){
					adminMessage('edit_error',-1);
				}
			}

			$this->model->updateCacheUserGroups();
			adminMessage('edit_ok','index.php?wskm=user&act=usergroup');
		}else{
			assign_var('list',$this->model->getUserGroups());
			adminTemplate('usergroup');
		}

	}


}


?>