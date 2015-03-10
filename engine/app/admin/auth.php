<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [Gart] www.wskmphp.com 
 * $Id: auth.php 53 2010-09-30 04:49:27Z ws99 $ 
 */

!defined('IN_ART') && exit('Access Denied');

class app_admin_auth extends admin_auth
{
	function loginUpdateFailed()
	{
		$this->db->exec('UPDATE '.TABLE_PREFIX."loginlog SET count=count+1,dateline='".WSKM_TIME."' WHERE ip='".USER_IP."'  ");
	}

	function loginIsFailed(&$lastupdate)
	{
		$fhold=(int)WSKM::getConfig('loginFailedHold');
		$ip=USER_IP;
		$sql="SELECT count,dateline FROM ".TABLE_PREFIX."loginlog WHERE ip='{$ip}' ";
		$isupdate=false;
		$time=WSKM_TIME;

		$count=$this->db->fetch_first($sql);
		$lastupdate=(int)$count['dateline'];
		if($count == false || ($time-$count['dateline']) >$fhold)
		{
			$isupdate=true;
		}

		if ($isupdate) {
			$this->db->exec('REPLACE INTO '.TABLE_PREFIX."loginlog  (ip,count,dateline)VALUES('{$ip}',1,'{$time}') ");
			$this->db->exec('DELETE FROM '.TABLE_PREFIX."loginlog WHERE dateline<$time-$fhold");
			return 1;
		}
		return $count['count'];
	}

	function doLogin()
	{
		$session=$this->readSession();
		if ($session !== false){
			adminMessage('logged','index.php');
		}

		if (checkToken()) {
			$lastupdate=0;
			$maxcount=(int)WSKM::getConfig('loginFailedCount');
			$logincount=(int)$this->loginIsFailed($lastupdate);
			if ($maxcount && $logincount >= $maxcount) {
				$lastupdate=round(((int)WSKM::getConfig('loginFailedHold')-(WSKM_TIME - (int)$lastupdate))/60);
				adminMessage(sprintf(lang('login_fatally'),$maxcount,$lastupdate));
			}

			$uname=requestPost('uname');
			$password=requestPost('password');
			if (strlen($password)<6) {
				adminMessage('password_length','index.php');
			}

			$objectlog=usingAdminModel('log');
			$objectlog->deleteExpiredAdminLoginLog();

			$uid=$this->login($uname,$password);
			if($uid > 0){
				$this->islogin=true;
				$this->initUser($uid);
				$objectlog->insertAdminLoginLog(array(
				'uid'=>$this->getUid(),
				'uname'=>$this->getUname(),
				'adminid'=>$this->getAdminid(),
				'groupid'=>$this->getGroupid(),
				'ip'=>USER_IP,
				'logintime'=>WSKM_TIME,
				'password'=>'***'.substr($password,3),
				'type'=>1
				));
				
				if (!$this->isAdmin() ) {
					adminMessage('login_notaccess',ART_URL);
				}
				
				wskm_cookie::write('adminhash',wskm_encrypt::DES($uid),0, true);				
				$this->insertSessiong();
			}else{
				$objectlog->insertAdminLoginLog(array(
				'uname'=>$uname,
				'ip'=>USER_IP,
				'logintime'=>WSKM_TIME,
				'password'=>'***'.substr($password,3),
				'type'=>0
				));
				$loginnotice=$maxcount > 0?sprintf(lang('login_allow_notice'),$maxcount - $logincount-1):'';
				$this->loginUpdateFailed();
				adminMessage(lang('password_error').$loginnotice,-1);
			}

			//$ref=wskm_cookie::getValue('referernav');
			gotoUrl('index.php');
		}
		else
		{
			adminMessage('unknow_error');
		}
	}

	function doLogout()
	{
		$this->logout();
		adminMessage('logout_successed',ADMIN_URL);
	}

}

?>