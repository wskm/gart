<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [Gart] www.wskmphp.com 
 * $Id: admin.php 182 2010-10-28 08:09:59Z ws99 $ 
 */

!defined('IN_ART') && exit('Access Denied');

class app_admin_admin extends admin_common
{
	function doIndex()
	{
		$menus=getThemeInc('menu');
		$galmenus=array_shift($menus);
		$tops=array_keys($menus);
		$tops="\"".implode('","',$tops)."\"";

		assign_var('navkey',requestGet('nav'));
		assign_var('menukey',requestGet('menukey'));

		if (isset($menus['plugin']['children'])) {
			$menus['plugin']['children']=array_merge($menus['plugin']['children'],$this->getPluginNav());
		}

		if (defined('UC_API') && isset($menus['user']['children']) ) {
			$menus['user']['children']['ucenter']=array('name'=>'UCenter','url'=>UC_API.'/admin.php?m=frame&a=main&iframe=1');
		}

		assign_var('navs',$galmenus['children']);
		assign_var('tops',$tops);
		assign_var('menus',$menus);
		adminTemplate('admin');
	}

	function getPluginNav(){
		$query=$this->db->query('SELECT plugintitle,pluginname FROM '.TABLE_PREFIX."plugins WHERE status=1 AND ismanage=1 ");
		$pluginnav=array();
		while ($tempi=$this->db->fetch($query)){
			$pluginnav[$tempi['pluginname']]=array(
			'name'=>$tempi['plugintitle'],
			'url'=>'index.php?wskm=plugin&act=manage&key='.$tempi['pluginname']
			);
		}
		return $pluginnav;
	}

	function doWelcome()
	{
		loadLang('admin_welcome');
		$objectlog=usingAdminModel('log');
		$loginlogs=$objectlog->getAdminLastLoginLog($this->getUid());
		assign_var('lastlogintime',$loginlogs['logintime']);
		assign_var('lastip',$loginlogs['ip']);

		WSKM::using('wskm_version');
		assign_var('server_software',wskm_version::server_software());
		assign_var('mysql_version',wskm_version::mysql_version());
		assign_var('upload_maxsize',wskm_version::upload_maxsize());

		WSKM::using('wskm_db_util');
		assign_var('dbSize', fSize( wskm_db_util::dbSize(TABLE_PREFIX)));
		assign_var('adminlist', $this->getAdminList());
		adminTemplate('welcome');
		$this->getUpdateMsg();
	}
	
	function getUpdateMsg(){
		$tourl = 'http://www.wskms.com/update.php?'.$this->webInfo();
		//$tourl = 'http://127.0.0.1/update.php?'.$this->webInfo();

		echo <<< EOT
<script type="text/javascript">
var updatehtml = '<table><tr><td><a href="http://www.wskms.com/update.php" target="_blank"><img src="{$tourl}" onload="showupdate()" /></a></td></tr></table>';
dom('updatemsg').style.display = 'none';
dom('updatemsg').innerHTML = updatehtml;
function showupdate() {
	dom('updatemsg').style.display = '';
}
</script>
EOT;
	}

	function webInfo(){
		$info=array(
		'version' => ART_VER,
		'release' =>ART_RELEASE,
		'php' => PHP_VERSION,
		'mysql' => WSKM::SQL()->version(),
		'charset' => PAGE_CHARSET,
		'webname' => ART_WEB_NAME,
		'weburl' => ART_WEB_URL,
		'host'=>host()
		);

		$lasttime = @filemtime(ART_CACHE_PATH.'lastupdate.lock');
		if(empty($lasttime) || (WSKM_TIME - $lasttime > 21600)) {
			@touch(ART_CACHE_PATH.'lastupdate.lock');
			$info['users'] = WSKM::SQL()->fetch_column('SELECT COUNT(*) FROM '.TABLE_PREFIX."users");
			$info['articles'] =  WSKM::SQL()->fetch_column('SELECT COUNT(*) FROM '.TABLE_PREFIX."articles");			
		}

		$tourl = '';
		foreach($info as $key => $value) {
			$tourl .= $key.'='.rawurlencode($value).'&';
		}
		$tourl=rtrim($tourl,'&');
		return 'info='.rawurlencode(base64_encode($tourl)).'&lastupdate='.WSKM_TIME;
	}
	
	function doTestMail(){
		$mailto = requestGet('mailto');
		if (!isEmail($mailto)) {
			echo 0;
			return ;
		}
		loadLang('admin_test');
		WSKM::using('wskm_email');
		$data=wskm_email::sendMail(lang('mail_subject'),lang('mail_body_html'),$mailto);
		echo $data?0:1;
	}
	
	function doSaveAData(){
		$data=requestPost('data',TYPE_HTMLTEXT);
		if (!$data) {
			return ;
		}

		if (!checkToken()) {			
			return ;
		}
		
		$filename='savea'.substr(md5($this->getUid().ART_KEY),-6).'.txt';
		echo wskm_io::fWrite(ART_CACHE_PATH.'data'.DS.$filename,stripslashes($data)) ? '1' : '0';
	}
	
	function doGetAData(){
		if (!checkToken()) {
			return ;
		}
		
		$filename='savea'.substr(md5($this->getUid().ART_KEY),-6).'.txt';
		echo wskm_io::fRead(ART_CACHE_PATH.'data'.DS.$filename);
	}
	
}

?>