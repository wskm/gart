<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [Gart] www.wskmphp.com 
 * $Id: nav.php 17 2010-08-26 11:37:04Z ws99 $ 
 */

!defined('IN_ART') && exit('Access Denied');

class model_admin_nav extends wskm_model_abstract
{
	function getAdminList()
	{
		return $this->db->fetch_all('SELECT * FROM '.TABLE_PREFIX.'nav ORDER BY displaysort DESC ');
	}

	function getList(){
		return $this->db->fetch_all('SELECT * FROM '.TABLE_PREFIX.'nav WHERE status=1 ORDER BY displaysort DESC ');
	}

	function getAdminInfo($id){
		return $this->db->fetch_first('SELECT * FROM '.TABLE_PREFIX."nav WHERE id='{$id}'");
	}

	function updateNav($data,$nid)
	{
		return $this->db->update(TABLE_PREFIX.'nav',$data," id='$nid' ") !== false;
	}

	function insertNav($data){
		return $this->db->insert(TABLE_PREFIX.'nav',$data) !== false;
	}

	function del($nid){
		return $this->db->delete(TABLE_PREFIX.'nav'," id='{$nid}' ") !== false;
	}

	function updateCache(){
		$query=$this->db->query('SELECT plugintitle,pluginname,hook FROM '.TABLE_PREFIX."plugins WHERE status=1 AND isnav=1 ");
		$pluginnav=array();
		while ($tempi=$this->db->fetch($query)){
			if ($tempi['hook']) {
				$tempi['hook']=unserialize($tempi['hook']);
			}

			if ($tempi['hook']['navurl'] && $tempi['hook']['navname']) {
				$tempi['name']=$tempi['plugintitle'];
				$tempi['url']=(strExists($tempi['hook']['navurl'],'://') ? '':ART_URL).$tempi['hook']['navurl'];
				$pluginnav[]=array('key'=>$tempi['pluginname'],'name'=>$tempi['hook']['navname'],'url'=>$tempi['url']);
			}

		}
		$query=$this->db->query('SELECT * FROM '.TABLE_PREFIX.'nav WHERE status=1 ORDER BY displaysort DESC ');
		$navs=array();
		
		while ($tempi=$this->db->fetch($query)){
			$tempi['key']=basename($tempi['url']);
			$tempi['key']=substr($tempi['key'],0,strpos($tempi['key'],'.'));
			$navs[]=$tempi;
		}

		$catelist=array();
		$query= $this->db->query('SELECT cid,name,navkey FROM '.TABLE_PREFIX."category WHERE status=1 AND isnav=1 ORDER BY displaysort,cid ASC");
		while ($tempi=$this->db->fetch($query)){
			$catelist[]=array('key'=>$tempi['navkey'],'name'=>$tempi['name'],'url'=>mvcUrl('',array('category','list',array('id'=>$tempi['cid'])),ART_URL.'index.php',WSKM::getConfig('urlMode')));
		}

		writeCacheSystem('nav',array_merge($catelist,$pluginnav,$navs));
	}
}
?>