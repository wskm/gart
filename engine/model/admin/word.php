<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [Gart] www.wskmphp.com 
 * $Id: word.php 16 2010-07-11 14:06:18Z ws99 $ 
 */

!defined('IN_ART') && exit('Access Denied');

class model_admin_word extends wskm_model_abstract 
{
	function getFilterWords(){
		$query=$this->db->query('SELECT * FROM '.TABLE_PREFIX.'filterword ORDER BY id DESC');
		$list=array();
		
		while ($tempi=$this->db->fetch($query)){
			$list[$tempi['id']]=$tempi;
		}
		return $list;
	}
	
	function updateFilterWord($info,$id){
		return $this->db->update(TABLE_PREFIX.'filterword',$info," id='{$id}' ") !==false;
	}
	
	function insertFilterWord($info){
		return $this->db->insert(TABLE_PREFIX.'filterword',$info) !==false;
	}
	
	function deleteFilterWord($id){
		return $this->db->delete(TABLE_PREFIX.'filterword',"id='{$id}' ") !== false;
	}
	
	function updateCache(){
		$query=$this->db->query('SELECT * FROM '.TABLE_PREFIX.'filterword ORDER BY id DESC');
		$word['replace']=$word['word']=$word=array();
		
		while ($tempi=$this->db->fetch($query)){
			$word['word'][]=$tempi['word'];
			$word['replace'][]=$tempi['replace'];
		}
		
		writeCacheSystem('filterword',$word);
		return $word;
	}

}

?>