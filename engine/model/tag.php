<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [Gart] www.wskmphp.com 
 * $Id: tag.php 280 2010-12-26 06:05:21Z ws99 $ 
 */

!defined('IN_ART') && exit('Access Denied');

class model_tag extends wskm_model_abstract
{
	public $pageCount=20;
	function getTag($name){
		return $this->db->fetch_first('SELECT tagid,tagname,close,`count` FROM '.TABLE_PREFIX."tags WHERE tagname='{$name}' ");
	}

	function getRandomTagName($count=20){
		$query=$this->db->query('SELECT tagname FROM '.TABLE_PREFIX."tags WHERE close=0 ORDER BY count DESC LIMIT $count ");
		$list=array();
		while ($tempi=$this->db->fetch($query)){
			$list[]=$tempi['tagname'];
		}
		
		shuffle($list);
		return $list;
	}
	
	function getTagArticles($name)
	{
		$pagesum=$this->getTagArticlesCount($name);
		$cpage=0;
		$start_page=multiPage_start($cpage,$this->pageCount,$pagesum);

		$sql="SELECT a.* FROM ".TABLE_PREFIX."articletags tag LEFT JOIN ".TABLE_PREFIX."articles a ON a.aid=tag.aid  WHERE tag.name='$name' ORDER BY dateline DESC LIMIT {$start_page},{$this->pageCount}";	
		$list=array();

		$query = $this->db->query($sql);
		while ($tempi=$this->db->fetch($query)) {
			if ($tempi['aid']<1) {
				continue;
			}
			$tempi['mvcurl']=mvcUrl('',array('news','show',array('id'=>$tempi['aid'])));
			$list[]=$tempi;
		}

		return array('list'=>$list,'count'=>$pagesum,'page'=>$cpage);
	}

	function getTagArticlesCount($name){
		$sql="SELECT COUNT(*) FROM ".TABLE_PREFIX."articletags WHERE name='$name' ";
		return (int)$this->db->fetch_column($sql);
	}

	function delete($aid){
		$query=$this->db->query('SELECT name FROM '.TABLE_PREFIX."articletags WHERE aid='{$aid}' " );
		$titletag=array();
		while ($tempi=$this->db->fetch($query)) {
			$titletag[]=$tempi['name'];
		}

		if($titletag){
			foreach ($titletag as $tagname){
				if($this->db->fetch_column('SELECT count(*) FROM '.TABLE_PREFIX."articletags WHERE name = '{$tagname}' AND aid != '$aid'")) {
					$this->db->exec('UPDATE '.TABLE_PREFIX."tags SET count=count-1 WHERE tagname='{$tagname}'");
				} else {
					$this->db->delete(TABLE_PREFIX.'tags',"tagname='{$tagname}'");
				}
			}

			return $this->db->delete(TABLE_PREFIX.'articletags'," aid='{$aid}' ") !== false;
		}else{
			return true;
		}
	}

}

?>