<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [Gart] www.wskmphp.com 
 * $Id: category.php 250 2010-11-28 17:57:29Z ws99 $ 
 */

!defined('IN_ART') && exit('Access Denied');

class app_category  extends art_page
{
	function doIndex()
	{
		$cid=(int)requestGet('id');
		if ($cid > 0) {
			$this->doList();
		}else {
			artMessage('request_error','index.php');
		}
	}

	function doList(){
		$cid=(int)requestGet('id');
		if ($cid <1) {
			artMessage('request_error','index.php');
		}
		
		$category=getCategoryData($cid);		
		if (!$category) {
			artMessage('request_error','index.php');
		}
		
		if ($category['url']) {
			gotoUrl($category['url']);
			exit();
		}
		
		$this->isHTML();
		$cparents=array();
		if ($category['parentid'] > 0) {
			$cparents = getCategoryParents($cid);
		}
		
		$navcate='';
		foreach ($cparents as $tempi){
			$navcate.="<a href='{$tempi['mvcurl']}' >".$tempi['name'].'</a>&nbsp;>&nbsp;';
		}
		$this->model=usingModel('category');
		$newsdata=$this->model->getNewsList($cid);
	
		$htmlpage=multiPage($newsdata['count'],$newsdata['page'],array('category','list',$newsdata['keys']),$this->model->newsPageCount);
		assign_var('news',$newsdata['list']);
		assign_var('htmlpage',$htmlpage);
		
		assign_var('page_title',$category['name'].'_');
		assign_var('page_keywords',$category['name'].','.$category['keywords']);
		assign_var('page_description',$category['name'].' '.$category['description']);
		
		assign_var('artnav', $navcate.$category['name']);
		assign_var('category',$category);
		assign_var('category_chlids',getCategoryChilds($cid));
		assign_var('nav_current',$category['navkey']?$category['navkey']:'cate'.$cid);
		template($category['tpllist']?$category['tpllist']:'category');
	}
}


?>