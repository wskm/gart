<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [Gart] www.wskmphp.com 
 * $Id: category.php 17 2010-08-26 11:37:04Z ws99 $ 
 */

!defined('IN_ART') && exit('Access Denied');

class model_admin_category extends wskm_model_abstract
{
	public $tree=null;
	public $newsPageCount=15;

	function getList($upid=-1,$isshow=true)
	{
		if ($upid != -1) {
			$addsql='1 = 1';
			$upid >= 0 && $addsql .=' AND parentid='.$upid;
			$addsql .= $isshow?' AND status=1 ':'';
			$sql='SELECT * FROM '.TABLE_PREFIX.'category WHERE '.$addsql.' ORDER BY displaysort,cid ASC  ';
		}
		else{
			$sql='SELECT * FROM '.TABLE_PREFIX."category ORDER BY parentid,displaysort  ASC  ";
		}

		$query= $this->db->query($sql);
		$res=array();
		while ($row=$this->db->fetch($query)){			
			$res[$row['cid']]=$row;
		}

		return $res;
	}

	function getSelectOption($catelist,$selectid=0,$except='')
	{
		if (!is_object($this->treeobj)) {
			WSKM::using('wskm_tree');
			WSKM::helper('html');
			$this->tree=new wskm_tree();
		}
		$this->tree->setTree($catelist, 'cid', 'parentid', 'name');

		$option=$this->tree->getOptions(0, 0, $except);
		return html_select_option($option,$selectid,false);
	}

	function getAids($cid){
		$list=array();
		$query=$this->db->query('SELECT aid FROM '.TABLE_PREFIX."articles WHERE cid='{$cid}' " );
		while ($tempi=$this->db->fetch($query)){
			$list[]=$tempi['aid'];			
		}
		
		return $list;
	}

	function getChildCount($cid)
	{
		return (int)$this->db->fetch_column('SELECT COUNT(*) FROM '.TABLE_PREFIX.'category WHERE parentid='.$cid);
	}

	function getAdminCategory($cid)
	{
		$data=$this->db->fetch_first('SELECT * FROM '.TABLE_PREFIX."category WHERE cid='{$cid}'");		
		return $data;
	}

	function update($args,$id)
	{
		if ($id < 1 ) {
			return false;
		}
		
		return $this->db->update(TABLE_PREFIX.'category',$args,"cid ='{$id}'") !== false;
	}

	function insert($args)
	{
		if (!$args['name']) {
			return false;
		}
		return $this->db->insert(TABLE_PREFIX.'category',$args)  !== false;
	}

	function delete($id)
	{
		return 	$this->db->delete(TABLE_PREFIX.'category',' cid='.$id)  !== false;
	}

	function isUnique($name, $parentid, $cid )
	{
		$conditions = "parentid =$parentid AND name = '$name'";
		$cid && $conditions .= " AND cid <> " . $cid ;

		$sql='SELECT COUNT(*) FROM '.TABLE_PREFIX.'category WHERE '.$conditions;
		return (int)$this->db->fetch_column($sql) == 0;
	}
	
	function updateCache()
	{
		$this->tree=new wskm_tree();
		$list=array();
		$query= $this->db->query('SELECT * FROM '.TABLE_PREFIX."category WHERE status=1 ORDER BY displaysort,cid ASC");
		while ($row=$this->db->fetch($query)){
			$list[$row['cid']]=$row;
		}
		$this->tree->setTree($list, 'cid', 'parentid', 'name');
		$treelist=$this->tree->getTreeList();

		foreach ($treelist as $key=>$cate){
			if ($treelist[$key]['domain']) {
				$treelist[$key]['mvcurl']='http://'.$treelist[$key]['domain'];
			}else{
				$treelist[$key]['mvcurl']=mvcUrl('',array('category','list',array('id'=>$cate['cid'])),ART_URL.'index.php',WSKM::getConfig('urlMode'));
			}

		}

		$cache=array('tree'=>$treelist,'childlist'=>$this->tree->getChildList(),'parentlist'=>$this->tree->getParentList());
		writeCacheSystem('category',$cache);
		
		usingArtClass('cache');
		if ($isupdatesetting) {			
			art_cache::update('settings');			
		}
		art_cache::update('nav');
		return $cache;
	}

}
?>