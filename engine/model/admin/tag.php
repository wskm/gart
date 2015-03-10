<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [Gart] www.wskmphp.com 
 * $Id: tag.php 133 2010-10-13 15:34:30Z ws99 $ 
 */

!defined('IN_ART') && exit('Access Denied');

class model_admin_tag extends wskm_model_abstract
{

	function getTag($name){
		return $this->db->fetch_first('SELECT tagid,tagname,close,`count` FROM '.TABLE_PREFIX."tags WHERE tagname='{$name}' ");
	}


	function delete($aid,$isbatch=0){
		$wherestr='';
		if ($isbatch) {
			$wherestr=" aid IN ({$aid}) ";
		}else{
			$wherestr=" aid='{$aid}' ";
		}
		
		$query=$this->db->query('SELECT name FROM '.TABLE_PREFIX."articletags WHERE {$wherestr} ");
		$titletag=array();
		while ($tempi=$this->db->fetch($query)) {
			$titletag[]=$tempi['name'];
		}

		if($titletag){
			$notwhere= $isbatch ? " aid NOT IN ({$aid}) ":" aid != '{$aid}' ";
			foreach ($titletag as $tagname){
				if($this->db->fetch_column('SELECT count(*) FROM '.TABLE_PREFIX."articletags WHERE name = '{$tagname}' AND {$notwhere} ")) {
					$this->db->exec('UPDATE '.TABLE_PREFIX."tags SET count=count-1 WHERE tagname='{$tagname}'");
				} else {
					$this->db->delete(TABLE_PREFIX.'tags',"tagname='{$tagname}'");
				}
			}

			return $this->db->delete(TABLE_PREFIX.'articletags',$wherestr) !== false;
		}else{
			return true;
		}
	}

}

?>