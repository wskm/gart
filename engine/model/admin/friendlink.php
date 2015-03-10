<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [Gart] www.wskmphp.com 
 * $Id: friendlink.php 16 2010-07-11 14:06:18Z ws99 $ 
 */

!defined('IN_ART') && exit('Access Denied');

class model_admin_friendlink extends wskm_model_abstract 
{
	function getList()
	{
		return $this->db->fetch_all('SELECT * FROM '.TABLE_PREFIX.'friendlinks ORDER BY displaysort DESC ');
	}
	
	function getInfo($id){
		return $this->db->fetch_first('SELECT * FROM '.TABLE_PREFIX."friendlinks WHERE id='{$id}'");
	}
	
	function update($data,$nid)
	{
		return $this->db->update(TABLE_PREFIX.'friendlinks',$data," id='$nid' ") !== false;
	}
	
	function insert($data){
		return $this->db->insert(TABLE_PREFIX.'friendlinks',$data) !== false;
	}
	
	function del($fid){
		$info=$this->getInfo($fid);
		if ($info['logo']) {
			wskm_io::fDelete(ART_UPLOAD_PATH.'logo'.DS.$info['logo']);
		}
		return $this->db->delete(TABLE_PREFIX.'friendlinks'," id='{$fid}' ") !== false;
	}
	
	function updateCache(){
		writeCacheSystem('friendlink',$this->getList());
	}
}
?>