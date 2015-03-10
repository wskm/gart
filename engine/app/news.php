<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [Gart] www.wskmphp.com 
 * $Id: news.php 252 2010-11-28 19:42:41Z ws99 $ 
 */

!defined('IN_ART') && exit('Access Denied');

class app_news  extends art_page
{
	function doIndex()
	{
		$aid=(int)requestGet('id');
		if ($aid) {
			$this->doShow();
		}else{
			artMessage('request_error','index.php');
		}
	}

	function doShow()
	{

		$aid=(int)requestGet('id');
		if ($aid<1) {
			artMessage('request_error','index.php');
		}

		$this->model=usingModel('article');
		$article=$this->model->getArticleInfo($aid);
		if ($article==false ) {
			artMessage('request_error','index.php');
		}

		$this->isHTML();
		loadLang('article');
		$articlemsg=$this->model->getArticleMessage($aid);
		$article['message']=$articlemsg['message'];
		$article['pagetype']=$articlemsg['pagetype'];

		$this->model->updateClicks($aid);

		$htmlpage='';
		$pagekey='##------[ART_PAGE]------##';
		if (strpos($article['message'],$pagekey) !== false ) {
			WSKM::using('wskm_page_tiger');
			$tpageclass=new wskm_page_tiger();
			$tpageclass->PAGE_KEY=$pagekey;
			$tpageclass->factory($article['message'],$article['pagetype']);
			$htmlpage=$tpageclass->outHtml($aid);
		}

		$category=getCategoryData($article['cid']);

		$cparents=array();
		if ($category['parentid'] > 0) {
			$cparents = getCategoryParents($article['cid']);
		}

		$cparents[]=$category;
		$navcate='';
		foreach ($cparents as $tempi){
			$navcate.="<a href='{$tempi['mvcurl']}' >".$tempi['name'].'</a>&nbsp;>&nbsp;';
		}

		if (!IS_HTML) {
			if ($article['replystate'] != 0) {
				assign_var('comments',$this->model->getCommentsSome($aid));
			}
			assign_var('needlogin',$article['replystate']==1 && !$this->isLogin() ? true:false);
		}

		assign_var('page_title',$article['title'].'_'.$category['name'].'_');
		assign_var('page_keywords',$article['htmlkeywords']);
		assign_var('page_description',$article['summary']);

		assign_var('commenturl',mvcUrl('',array('comment','list',array('id'=>$aid))).'#sendcomment');
		assign_var('artnav', $navcate.$article['title']);
		assign_var('htmlpage',$htmlpage);
		assign_var('aid',$aid);
		assign_var('article',$article);
		assign_var('switchurl',$this->model->switchUrl($aid,$article['cid']));
		assign_var('isNewsKiss',WSKM::getConfig('isNewsKiss'));
		assign_var('nav_current',$category['navkey']?$category['navkey']:'cate'.$article['cid']);
		template($category['tplshow']?$category['tplshow']:'news');
	}

}


?>