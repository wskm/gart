<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [Gart] www.wskmphp.com 
 * $Id: tag.php 250 2010-11-28 17:57:29Z ws99 $ 
 */

!defined('IN_ART') && exit('Access Denied');

class app_tag  extends art_page
{
	function doIndex()
	{
		$name=requestGet('name');
		if ($name) {
			$this->doShow();
			exit();
		}
		artMessage('request_error','index.php');
	}

	function doShow()
	{
		$name=requestGet('name');
		if (!checkTag($name)) {
			artMessage('tag_nullity','index.php');
		}
		
		$this->model=usingModel('tag');	
		$taginfo=$this->model->getTag($name);
		if ($taginfo['close']) {
			artMessage('tag_close','index.php');
		}
		
		$articles= $this->model->getTagArticles($name);
				
		assign_var('htmlpage',multiPage($articles['count'],$articles['page'],array('tag','show',array('name'=>$name)),$this->model->pageCount));		
		assign_var('tagname',$name);
		assign_var('articles',$articles['list']);
		
		assign_var('page_title','TAG:'.$name.'_');
		assign_var('page_keywords',$name.',');
		assign_var('page_description',$name);
		template('tag');
	}

}

function checkTag($txt){
	$txt=trim($txt);
	if (empty($txt)) {
		return false;
	}
	return preg_match('/^([\x7f-\xff_-]|\w){2,20}$/', $txt);
}

?>