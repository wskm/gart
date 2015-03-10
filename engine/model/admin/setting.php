<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [Gart] www.wskmphp.com 
 * $Id: setting.php 28 2010-09-25 13:17:52Z ws99 $ 
 */

!defined('IN_ART') && exit('Access Denied');

class model_admin_setting extends wskm_model_abstract
{
	public $settings=array();
	public $isUpdateAll=false;

	function getSetting($type){
		$wherestr='';
		if(is_array($type)){
			$wherestr= " type IN ( '".implode("','",$type)."' ) ORDER BY type";
		}else{
			$wherestr= " type ='$type' ";
		}

		$query=$this->db->query('SELECT variable,value FROM '.TABLE_PREFIX."settings WHERE $wherestr ");
		while ($item=$this->db->fetch($query)) {
			$this->settings[$item['variable']]=$item['value'];
		}

		return $this->settings;
	}

	function readAll()
	{
		$query=$this->db->query('SELECT variable,value FROM '.TABLE_PREFIX."settings");
		while ($item=$this->db->fetch($query)) {
			$this->settings[$item['variable']]=$item['value'];
		}

	}

	function updateCache()
	{
		if (count($this->settings)>0) {
			writeCacheSystem('rules',nl2br($this->settings['regRulesText']));

			$this->settings['unameProtect']=explode("\n",$this->settings['unameProtect']);
			$this->settings['emailProtect']=explode("\n",$this->settings['emailProtect']);
			$this->settings['timeFormats']=explode("\n",$this->settings['timeFormats']);
			unset($this->settings['regRulesText']);

			WSKM::setConfig('urlMode',$this->settings['urlMode']);
			writeCacheSystem('settings',$this->settings);
		}
	}

	function updateSettings($newset)
	{
		foreach ($newset as $k=>$v){
			if (isset($this->settings[$k]) && $this->settings[$k] != $v) {
				if($this->db->exec("UPDATE ".TABLE_PREFIX."settings SET variable='$k', value='$v' WHERE variable='$k' ") ===false){
					return false;
				}

				if ($k=='urlMode' || $k=='language') {
					$this->isUpdateAll=true;
				}

				$this->settings[$k]=stripslashes($v);
			}
		}

		return true;
	}

	function getUserGroups(){
		$query=$this->db->query('SELECT groupid,groupname FROM '.TABLE_PREFIX."usergroups " );
		$list=array();
		while ($tempi=$this->db->fetch($query)) {
			$list[$tempi['groupid']]=$tempi['groupname'];
		}

		return $list;
	}

	function update($args,$where){
		$res=$this->db->update(TABLE_PREFIX.'settings',$args,$where) !==false;
		return $res;
	}

	function updateItem($variable,$value){
		return $this->update(array('value'=>$value)," variable='{$variable}'  ");
	}

	function getAnnounces(){
		$query=$this->db->query("SELECT * FROM ".TABLE_PREFIX."announce ORDER BY displaysort DESC ");
		$article=array();
		while ($tempi=$this->db->fetch($query)) {
			$tempi['mvcurl']=mvcUrl('',array('announce','show',array('id'=>$tempi['id'])),ART_URL,WSKM::getConfig('urlMode'));
			$article[]=$tempi;
		}

		return $article;
	}

	function getAnnounce($id){
		return $this->db->fetch_first("SELECT * FROM ".TABLE_PREFIX."announce WHERE id='$id' ");
	}

	function insertAnnounce($info){
		return $this->db->insert(TABLE_PREFIX.'announce',$info);
	}

	function updateAnnounce($info,$id){
		return $this->db->update(TABLE_PREFIX.'announce',$info," id='{$id}' ");
	}

	function deleteAnnounce($ids){
		return $this->db->delete(TABLE_PREFIX.'announce'," id IN ({$ids}) ");
	}

	function getAds(){
		$query=$this->db->query("SELECT * FROM ".TABLE_PREFIX."ad a LEFT JOIN ".TABLE_PREFIX."adtype t ON a.typeid=t.typeid  ORDER BY a.displaysort DESC ");
		$list=array();
		while ($tempi=$this->db->fetch($query)) {
			if ($tempi['args']) {
				$tempi['args']=unserialize($tempi['args']);
				$tempi['stylename']=lang('ad_'.$tempi['args']['style']);
			}
			$list[]=$tempi;
		}

		return $list;
	}

	function getAdTypes(){
		return $this->db->fetch_all("SELECT * FROM ".TABLE_PREFIX."adtype ");
	}

	function getAd($id){
		$data = $this->db->fetch_first("SELECT * FROM ".TABLE_PREFIX."ad WHERE id='$id' ");
		if ($data['args']) {
			$data['args']=unserialize($data['args']);
		}
		return $data;
	}

	function insertAd($info){
		return $this->db->insert(TABLE_PREFIX.'ad',$info);
	}

	function insertAdType($info){
		return $this->db->insert(TABLE_PREFIX.'adtype',$info);
	}

	function updateAd($info,$id){
		return $this->db->update(TABLE_PREFIX.'ad',$info," id='{$id}' ");
	}

	function updateAdType($info,$id){
		return $this->db->update(TABLE_PREFIX.'adtype',$info," typeid='{$id}' ");
	}

	function deleteAd($ids){
		return $this->db->delete(TABLE_PREFIX.'ad'," id IN ({$ids}) ");
	}

	function deleteAdType($ids){
		return $this->db->delete(TABLE_PREFIX.'adtype'," typeid IN ({$ids}) ");
	}
	
	function updateCacheAd(){
		$query=$this->db->query('SELECT id,typeid,code,begintime,endtime FROM '.TABLE_PREFIX."ad WHERE status=1 AND begintime <='".WSKM_TIME."' AND (endtime>='".WSKM_TIME."' OR endtime='0') ORDER BY displaysort DESC ");
		
		$list=array();
		while ($tempi=$this->db->fetch($query)){
			$list[ $tempi['id'] ]=$tempi;
		}
		
		writeCacheSystem('ad',$list);
	}
}

?>