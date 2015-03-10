<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [Gart] www.wskmphp.com 
 * $Id: fun_common.php 280 2010-12-26 06:05:21Z ws99 $ 
 */

!defined('IN_ART') && exit('Access Denied');

function loadLang($key,$iserr=false)
{
	return wskm_lang::lang($key,LANGUAGE,false,false,$iserr);
}

function readConfig($key,$isp='')
{
	$path=ART_DATA.$key.'config.php';
	return loadArray($path,$isp);
}

function artMessage($message='', $urlForward = '')
{
	showMessage($message,$urlForward);
}

function checkToken($type=URL_POST,$showerr=1) {
	if ($type==URL_POST && IS_POST && isMyReferer()) {
		if (ART_HASH !=  requestPost('arthash')) {
			if($showerr){
				if (!defined('IN_ADMIN')) {
					adminMessage('request_expired','index.php');
				}else{
					artMessage('request_expired','index.php');
				}
			}elseif($showerr===-1){
				xmlMessage('request_expired');
			}
			return false;
		}
		return 	true;
	}else{
		return ART_HASH ==  requestGet('arthash');
	}
}

function usingArtInc($name)
{
	return require_once(ART_INC_PATH.'inc_'.$name.'.php');
}

function usingArtFun($name)
{
	return require(ART_INC_PATH.'fun_'.$name.'.php');
}

function usingArtClass($name)
{
	if (class_exists('art_'.$name) || interface_exists('art_'.$name)) {
		return true;
	}
	return require(ART_INC_PATH.'class_'.$name.'.php');
}

function toUrlSeparator($str){
	return str_replace(DIRECTORY_SEPARATOR,'/',$str);
}

function usingAdminModel($model)
{
	$className =WSKM::getConfig('modelAdminPrefix').$model;
	$path = MVC_DIR.str_replace('_', DS, $className).'.php';
	if(WSKM::usingBase($className,$path))
	{
		return new $className();
	}
	throw new wskm_exception('Not admin model:'.$model);
}

function usingModel($name){
	return WSKM::usingModel($name);
}

function getWebStyle($styleid){
	$style=WSKM::$caches['style'][$styleid];
	if (empty($style)) {
		wskm_note::note('style','cache');
	}else{
		return $style;
	}

	return false;
}

function loadCacheSystem($name){
	$path=ART_CACHE_PATH.'data'.DS.'sys_'.$name.'.php';

	if (file_exists($path)) {
		if (strpos($name,'usergroup') !== false) {
			$name='usergroup';
		}
		WSKM::setValue($name,include($path));
	}else{
		wskm_note::note($name,'cache');
		return false;
	}

	return true;
}

function loadCacheConfig($name,$key=''){
	$path=ART_CACHE_PATH.'data'.DS.'sys_'.$name.'.php';

	if (file_exists($path)) {
		WSKM::loadConfig($path,$key);
	}else{
		wskm_note::note($name,'cache');
	}
}

function readCacheSystem($name){
	$path=ART_CACHE_PATH.'data'.DS.'sys_'.$name.'.php';

	if (file_exists($path)) {
		return include($path);
	}
	return false;
}

function writeCacheSystem($name,$arr)
{
	wskm_cache::writeArray(ART_CACHE_PATH.'data'.DS.'sys_'.$name.'.php',$arr);
	clearstatcache();
}

function readCacheStatic($name,$life=0){
	$path=ART_CACHE_PATH.'data'.DS.'static_'.$name.'.php';
	$life=$life==0?(int)WSKM::getConfig('cacheStaticTime'):$life;
	if (file_exists($path) && WSKM_TIME < $life +filemtime($path) ) {
		return include($path);
	}

	return false;
}

function writeCacheStatic($name,$arr){
	wskm_cache::writeArray(ART_CACHE_PATH.'data'.DS.'static_'.$name.'.php',$arr);
	clearstatcache();
}

function removeCacheStatic($name){
	if(wskm_io::fDelete(ART_CACHE_PATH.'data'.DS.'static_'.$name.'.php'))clearstatcache();
}

function getCategoryData($cid){
	return WSKM::$caches['category']['tree'][$cid];
}

function getCategoryParents($cid){
	$parents=array();
	foreach ( WSKM::$caches['category']['parentlist'][$cid] as $parent ){
		if ($parent == 0) {
			continue;
		}
		$parents[$parent]=WSKM::$caches['category']['tree'][$parent];
	}
	return $parents;
}

function getCategoryChilds($cid){
	$childs=array();
	foreach ( WSKM::$caches['category']['childlist'][$cid] as $child ){
		if ($child == 0) {
			continue;
		}
		$childs[$child]=WSKM::$caches['category']['tree'][$child];
	}
	return $childs;
}

function getPhotoDir($uid){
	return ART_ROOT.'photo'.DS.substr(md5( round($uid/100) )  ,0,6).DS;
}

function getUserPhoto($uid,$imgsize='m',$ishtml=true,$addurl=''){

	$sizes=array('s'=>'width="50"','m'=>'width="100"','b'=>'width="180"');
	if (WSKM::getConfig('userEngine') == 'ucenter') {
		$sizetype=array('s'=>'small','m'=>'middle','b'=>'big');
		if (isset($sizetype[$imgsize]) && $sizetype[$imgsize]) {
			$size=$sizetype[$imgsize];
		}else{
			$size='middle';
		}
		return $ishtml ? '<img src="'.UC_API.'/avatar.php?uid='.$uid.'&size='.$size.$addurl.'"  '.$sizes[$imgsize].' />':UC_API.'/avatar.php?uid='.$uid.'&size='.$size.$addurl ;
	}else{
		return $ishtml?'<img src="'.ART_URL.'photo.php?uid='.$uid.'&size='.$imgsize.$addurl.'" '.$sizes[$imgsize].' />':ART_URL.'photo.php?uid='.$uid.'&size='.$imgsize.$addurl;
	}
}

function defaultListImage(){
	return '/images/common/default.gif';
}

function tigerUser(){
	$name=WSKM::getConfig('userEngine');
	usingArtClass($name);

	$name='art_'.$name;
	return new $name();
}

function getWskm($key,$args='',$expire=''){
	$data=false;
	$iscache=true;
	
	if ($expire == 'none' || $expire=='wskm' ) {
		$iscache=false;
	}
	
	if ($iscache) {
		$cachetype=CACHETYPE_FILE;
		if ($key == 'newlist') {
			$cachetype=CACHETYPE_SQL;
		}

		$cachekey=$key;
		if ($cachetype == CACHETYPE_FILE) {
			$cachekey .= '_'.substr(md5(STYLEID.$args),0,6);
		}elseif ($cachetype == CACHETYPE_SQL){
			$cachekey='page'.substr(md5(STYLEID.$args),0,6);
			$cachekey.=requestGet($cachekey,TYPE_INT);
		}
		
		$data=getCacheBase($cachekey,$expire,$cachetype);
	}
	
	if (!$data) {
		usingArtFun('wskm');
		if ($expire != 'wskm') {
			usingThemeIncs();
		}
		
		$fun='wskm_'.$key;
		if(!function_exists($fun)){
			exit('wskm:'.$key);
		}
		$data = $fun($args);
		if ($iscache && $data) {
			setCacheBase($cachekey,$data,$expire,$cachetype);
		}
	}

	return $data;
}

function pluginLang($key){
	wskm_lang::loadPath(PLUGIN_PATH.'lang'.DS.$key.'_'.LANGUAGE.'_lang.php',LANGUAGE);
}

function isAccessFor($key){
	return (bool)WSKM::getValues('usergroup',$key);
}

function getUserAccess($key){
	return WSKM::getValues('usergroup',$key);
}

function usingAdminMVC($class)
{

	$path = MVC_DIR.str_replace('_', DS, $class).'.php';
	if (file_exists($path)) {
		return require_once($path);
	}
	adminMessage('mvc_noapp','index.php');
}

function setDbCache($key,$value,$expire=0){
	return WSKM::SQL()->exec('REPLACE INTO '.TABLE_PREFIX."caches (keyid,value,expire)VALUES('{$key}','".addslashes($value)."','{$expire}')") !== false;
}

function getDbCache($key){
	return WSKM::SQL()->fetch_column('SELECT value FROM '.TABLE_PREFIX."caches WHERE keyid='{$key}' ");
}

function getCacheBase($key,$expire='',$cachetype=''){
	$expire=(int)$expire;
	if ($expire < 0) {
		return false;
	}

	if ($cachetype=='') {
		$cachetype=CACHETYPE_FILE;
	}

	$cacheKey=$data='';
	if ($cachetype==CACHETYPE_SQL) {
		$pageCache=WSKM::loadClass('wskm_cache_sql');
		$pageCache->setBaseTName('caches');
		$cacheKey=$pageCache->getKey($key);
		$data=$pageCache->get($cacheKey);
	}elseif ($cachetype==CACHETYPE_FILE) {
		$data=readCacheStatic($key,$expire);
	}

	return $data;
}

function setCacheBase($key,$value,$expire='',$cachetype=''){
	$expire=(int)$expire;
	if ($cachetype=='') {
		$cachetype=CACHETYPE_FILE;
	}

	$cacheKey=$key;
	if ($cachetype==CACHETYPE_SQL) {
		$pageCache=WSKM::loadClass('wskm_cache_sql');
		$pageCache->setBaseTName('caches');
		$cacheKey=$pageCache->getKey($key);
		$pageCache->set($cacheKey,$value,$expire <0?3:$expire);
	}elseif ($cachetype==CACHETYPE_FILE) {
		writeCacheStatic($cacheKey,$value);
	}
}

function sqlData($sql,$expire='',$keyvar='',$args='',$cachetype=''){
	if (!$sql && !$keyvar) {
		return '';
	}

	$expire=(int)$expire;
	$cachetype=$cachetype==''?CACHETYPE_SQL:$cachetype;
	$sql=str_replace('@@',TABLE_PREFIX,$sql);

	$pageCache=null;
	$data=getCacheBase($sql,$expire,$cachetype);
	if ($expire <0 || !$data) {
		$data=array();
		$tofun='sqldata_'.$keyvar;
		if ($keyvar && function_exists($tofun)) {
			$data=$tofun($sql,$args);
		}else{
			$db=WSKM::SQL();
			$query = $db->query($db->escape($sql));
			$columnCount=$db->column_count($query);
			if ($columnCount==1) {
				while ($tempi=$db->fetch($query,'NUM')) {
					$data[]=$tempi[0];
				}
			}else{
				while ($tempi=$db->fetch($query,'ASSOC')) {
					$data[]=$tempi;
				}
			}
		}

		if ($data) {
			setCacheBase($sql,$data,$expire <0?3:$expire,$cachetype);
		}
	}

	return $data;
}

function usingThemeInc($file){
	$themepath=ART_THEMES_PATH.STYLENAME.DS;
	$usingfile=$themepath.'inc'.DS.$file.'.php';
	if (!file_exists($usingfile)) {
		if ($stylename == 'default') {
			return ;
		}
		$themepath=ART_THEMES_PATH.'default'.DS;
		$usingfile=$themepath.'inc'.DS.$file.'.php';
		if (file_exists($usingfile)) {
			include($usingfile);
		}
		return ;
	}
	include($usingfile);
}

function usingThemeIncs($stylename=''){
	if ($stylename=='') {
		$stylename=STYLENAME;
	}

	$isusing=true;
	$themepath=ART_THEMES_PATH.$stylename.DS;
	$usingfile=$themepath.'inc'.DS.'using.php';
	if (!file_exists($usingfile)) {
		if ($stylename == 'default') {
			return ;
		}
		$themepath=ART_THEMES_PATH.'default'.DS;
		$usingfile=$themepath.'inc'.DS.'using.php';
		if (!file_exists($usingfile)) {
			$isusing=false;
		}
	}

	if (!$isusing) {
		return ;
	}

	$files=(array)include($usingfile);
	foreach ($files as $file){
		$usingfile=$themepath.'inc'.DS.$file;
		if (file_exists($usingfile)) {
			include($usingfile);
		}
	}

}

?>