<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [Gart] www.wskmphp.com 
 * $Id: category.php 120 2010-10-03 05:19:27Z ws99 $ 
 */

!defined('IN_ART') && exit('Access Denied');

class model_category extends wskm_model_abstract
{
	public $newsPageCount=15;
	function getNewsList($cid,$ischilds=true){

		$keys=array(
		'id'=>$cid,
		'page'=>0
		);
	
		if ($cid < 1 ) {
			return false;
		}

		if (requestGet('constpage') > 0) {
			$this->newsPageCount=(int)requestGet('constpage');
		}

		$wherestr=$cid.',';
		$cchilds=getCategoryChilds($cid);
		foreach ($cchilds as $key=>$tempi){
			$wherestr.=$key.',';
		}
		$wherestr=rtrim($wherestr,',');
		if ($ischilds) {
			$wherestr=" cid IN ($wherestr) ";
		}else{
			$wherestr=" cid = '$cid' ";
		}
		
		$currpage=1;
		$totalcount=$this->getNewsListCount($wherestr);
		$startpage=multiPage_start($currpage,$this->newsPageCount,$totalcount);
		
		$list=array();
		$query=$this->db->query('SELECT aid,cid,uid,uname,dateline,title,titlestyle,summary FROM '.TABLE_PREFIX."articles WHERE status=1 AND {$wherestr} ORDER BY dateline DESC LIMIT {$startpage},{$this->newsPageCount} ");
		while ($tempi=$this->db->fetch($query)){
			$tempi['mvcurl']=mvcUrl('',array('news','show',array('id'=>$tempi['aid'])));
			$list[]=$tempi;
		}
		$keys['page']=$currpage;
		return array('list'=>$list,'count'=>$totalcount,'page'=>$currpage,'keys'=>$keys);
	}

	function getNewsListCount($wherestr){		
		return $this->db->fetch_column('SELECT COUNT(*) FROM '.TABLE_PREFIX."articles WHERE status=1 AND {$wherestr} ");
	}

}
?>