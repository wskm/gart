<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [Gart] www.wskmphp.com 
 * $Id: class_cache.php 280 2010-12-26 06:05:21Z ws99 $ 
 */

!defined('IN_ART') && exit('Access Denied');

@set_time_limit(180);
class art_cache
{

	static function clearAllStaticFile(){
		$dir=ART_CACHE_PATH.'data'.DS;
		if($handle = opendir($dir)){
			while(($file = readdir($handle)) !== false) {
				if(substr($file,0,6) == 'static') {
					$path=$dir.$file;
					if(wskm_io::fDelete($path) ===false ){
						exit('Can not delete to cache files, please check directory  ./cache/data/ .');
					}
				}
			}
		}
	}

	static function deleteHtml($aid=0,$isall=false){
		$dir=ART_ROOT.'html'.DS;
		if ($isall) {
			if($handle = opendir($dir)){
				while(($file = readdir($handle)) !== false) {
					if ($file != "." && $file != ".." ){
						if (is_dir($dir.$file) && wskm_io::dDelete($dir.$file) !==false) {
							continue;
						}
						if(wskm_io::fDelete($dir.$file) !==false){
							continue;
						}
						exit('Can not delete to cache files, please check directory  ./html/'.$file);
					}
				}
			}
			return ;
		}

		if (empty($aid)) {
			return ;
		}

		ignore_user_abort(true);
		$aids=array();
		if (is_array($aid)) {
			$aids=$aid;
		}else{
			$aids[]=$aid;
		}

		foreach ($aids as $id){
			if ($id>0) {
				$key="news-show-{$id}-";
				$deldir=$dir.substr(md5($key.'1'),0,1).DS;
				if($handle = opendir($deldir)){
					while(($file = readdir($handle)) !== false) {
						if (strExists($file,$key)) {
							wskm_io::fDelete($deldir.$file);
						}
					}
				}
			}
		}

	}

	static function updateHtml($aid){
		$dir=ART_ROOT.'html'.DS;
		$updatehtml="<script type=\"text/javascript\" src=\"".ART_INC_URL.'js/util.js'."\" ></script>";
		if (file_exists($dir.'index.html')) {
			$url=mvcUrl('',array('index','index'),ART_URL_FULL,WSKM::getConfig('urlMode'),false);
			$url .= (strExists($url,'?')?'&updatehtml=1':'?updatehtml=1');
			$updatehtml .="<script type=\"text/javascript\" >ajaxCall('{$url}',function(){})</script>";
		}

		if (empty($aid)) {
			WSKM::setValue('artmsg',$updatehtml);
			return ;
		}

		$aids=array();
		if (is_array($aid)) {
			$aids=$aid;
		}else{
			$aids[]=$aid;
		}

		foreach ($aids as $id){
			if ($id>0) {
				$url=mvcUrl('',array('news','show',array('id'=>$id)),ART_URL_FULL,WSKM::getConfig('urlMode'),false);
				$url .= (strExists($url,'?')?'&updatehtml=1':'?updatehtml=1');
				$updatehtml .="<script type=\"text/javascript\" >ajaxCall('{$url}',function(){})</script>";
			}
		}

		WSKM::setValue('artmsg',$updatehtml);
	}

	static function clearAllTplFile(){
		$dir=ART_CACHE_PATH.'tpl'.DS;
		if($handle = opendir($dir)){
			while(($file = readdir($handle)) !== false) {
				if ($file != "." && $file != "..") {
					$path=$dir.$file;

					if(wskm_io::fDelete($path) ===false ){
						exit('Can not delete to cache files, please check directory  ./cache/data/ .');
					}
				}
			}
			wskm_io::fMake($dir.'index.html');
		}
	}

	static function update($key){
		$key=strtolower($key);
		$cachetype=array('category','usergroup','friendlink','nav','settings','style','static','tpl','filterword','ad');
		if (strpos($key,'usergroup') !== false) {
			$key='usergroup';
		}
		if (!in_array($key,$cachetype)) {
			throw new wskm_exception('update cache for key error:'.$key);
		}

		switch ($key){
			case 'category':
				WSKM::using('wskm_tree');
				$mod=usingAdminModel('category');
				$mod->updateCache();
				break;
			case 'usergroup':
				$mod=usingAdminModel('user');
				$mod->updateCacheUserGroups();
				break;
			case 'friendlink':
				$mod=usingAdminModel('friendlink');
				$mod->updateCache();
				break;
			case 'nav':
				$mod=usingAdminModel('nav');
				$mod->updateCache();
				break;
			case 'settings':
				$mod=usingAdminModel('setting');
				$mod->readAll();
				$mod->updateCache();
				break;
			case 'style':
				$mod=usingAdminModel('theme');
				$mod->updateCache();
				break;
			case 'static':
				self::clearAllStaticFile();
				break;
			case 'tpl':
				self::clearAllTplFile();
				break;
			case 'filterword':
				$mod=usingAdminModel('word');
				$mod->updateCache();
				break;
			case 'ad':
				$mod=usingAdminModel('setting');
				$mod->updateCacheAd();
				break;
		}

	}

	static function updateAll($type=''){
		$toptype=array('sys'=>array('settings','style','category','filterword','usergroup','friendlink','nav','ad'),'static'=>array('static'),'tpl'=>array('tpl'));

		$alltype=array();
		if ($type=='') {
			foreach ($toptype as $key=>$tempi){
				$alltype=array_merge($alltype,$tempi);
			}
		}elseif (in_array($type,array_keys($toptype))){
			$alltype=$toptype[$type];
		}else{
			throw new wskm_exception('updateAll for type error:'.$type);
		}
		foreach ($alltype as $tempi){
			self::update($tempi);
		}

	}

}

class art_hook{
	static $isinit=false;

	static function initUtil(){
		if (self::$isinit) {
			return ;
		}
		self::$isinit=true;

		loadCacheSystem('style');
		loadCacheSystem('category');
		loadCacheSystem('nav');

		$userObject=WSKM::user();
		$styleid=requestGet('styleid',TYPE_INT);
		if ($styleid < 1) {
			$styleid=$userObject->getStyleid();
		}else{
			wskm_cookie::write('styleid',$styleid,31536000);
		}

		if (!in_array($styleid,array_keys(WSKM::getValue('style')))) {
			$styleid=(int)WSKM::getConfig('styleId');
		}

		$userObject->setStyleid($styleid);
		$temp=getWebStyle($styleid);
		define('STYLEID', $styleid);
		define('STYLENAME',$temp['name']);
		define('STYLEVERSION',$temp['version']);
		define('COOKIEPRE', WSKM::getConfig('cookiePre'));
		define('COOKIEDOMAIN', WSKM::getConfig('cookieDomain'));
		define('COOKIEPATH', WSKM::getConfig('cookiePath'));

		define('UNAME',$userObject->getUname());
		define('UID',$userObject->getUid());
		define('ADMINID',$userObject->getAdminid());
		define('GROUPID',$userObject->getGroupid());

		assign_var('page_seotitle',WSKM::getConfig('webTitle')?WSKM::getConfig('webTitle').'_':'');
		assign_var('page_seokeywords',WSKM::getConfig('webKeywords'));
		assign_var('page_seodescription',WSKM::getConfig('webDescription'));
		assign_var('page_footer',WSKM::getConfig('pageFooter'));
		assign_var('topnav',WSKM::getValue('nav'));
		assign_var('nav_current','index');

		$temp=WSKM::getValue('category');
		assign_var('categorylist',$temp['tree']);

		assign_var('popBgShow',(int)WSKM::getConfig('popBgShow'));
		assign_var('popBgColor',WSKM::getConfig('popBgColor'));
		assign_var('isVcode',(bool)WSKM::getConfig('isVcode'));
		if (!IS_HTML && (bool)WSKM::getConfig('isSwitchTheme')) {
			assign_var('themelist',WSKM::getValue('style'));
		}
		unset($temp);
	}
}

?>