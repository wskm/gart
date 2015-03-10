<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [Gart] www.wskmphp.com 
 * $Id: class_util.php 254 2010-11-28 20:59:16Z ws99 $ 
 */

!defined('IN_ART') && exit('Access Denied');

function wskm_newList($args=''){
	$isargs=$cid=$pagecount=$wherestr=$wherestr2=false;
	if ($args && strExists($args,'|')) {
		$temp=explode('|', $args);
		if (count($temp) == 4) {
			$cid=(int)$temp[0];
			$pagecount=(int)$temp[1];
			$orderarr=array('dateline','views','replies');
			if (isset($orderarr[ $temp[2] ])) {
				$wherestr=' ORDER BY '.$orderarr[ $temp[2] ].' ';
			}else{
				$wherestr=' ORDER BY dateline ';
			}
			$wherestr .= ((bool)$temp[3])?'ASC':'DESC';
			$isargs=true;
		}else{
			$isargs=false;
		}
	}

	if(!$isargs){
		$cid=(int)$args;
		$pagecount=15;
		$wherestr=' ORDER BY dateline DESC ';
	}

	$db=WSKM::SQL();
	if ($cid > 0) {
		$wherestr2=$cid.',';
		$cchilds=getCategoryChilds($cid);
		foreach ($cchilds as $key=>$tempi){
			$wherestr2.=$key.',';
		}
		$wherestr2=rtrim($wherestr2,',');
		$wherestr2=" cid IN ($wherestr2) ";
	}else {
		$wherestr2=' 1=1 ';
	}

	$urlpagekey='page'.substr(md5($args),0,6);
	WSKM::setConfig('tplpagekey',$urlpagekey);

	$currpage=requestGet($urlpagekey,TYPE_INT);
	$totalcount=$db->fetch_column('SELECT COUNT(*) FROM '.TABLE_PREFIX."articles WHERE status=1 AND {$wherestr2} ");
	$startpage=multiPage_start($currpage,$pagecount,$totalcount,true);

	$cateCache=null;
	if ($totalcount) {
		$cateCache=WSKM::getValue('category');
		$cateCache=$cateCache['tree'];
	}

	$list=array();
	$query=$db->query('SELECT * FROM '.TABLE_PREFIX."articles WHERE status=1 AND {$wherestr2} {$wherestr} LIMIT {$startpage},{$pagecount} ");
	while ($tempi=$db->fetch($query)){
		if ($tempi['cover'] && !strExists($tempi['cover'],'://')) {
			$tempi['cover'] = ART_URL.$tempi['cover'];
		}
		$tempi['cateurl']='<a href="'.$cateCache[$tempi['cid']]['mvcurl'].'" target="_blank" >'.$cateCache[$tempi['cid']]['name'].'</a>';
		$tempi['mvcurl']=mvcUrl('',array('news','show',array('id'=>$tempi['aid'])));
		$list[]=$tempi;
	}

	return array('list'=>$list,'htmlpage'=>$htmlpage=multiPage($totalcount,$currpage,array(MVC_APP,MVC_ACT,array($urlpagekey=>$currpage)),$pagecount));
}

function wskm_new($args=''){
	$aid=(int)$args;
	if ($aid < 1) {
		exit('wskm:newinfo:'.$args);
	}
	$model=usingModel('article');
	return $model->getArticleFullInfo($aid);
}

function wskm_cate($args=''){
	if ($args > 0) {
		$list=getCategoryData($args);
		return array_merge(array($list),getCategoryChilds((int)$args));
	}
	return WSKM::getValues('category','tree');
}

function wskm_ad($args){
	$id=$typeid=$num=$temp=0;
	if ($args && strExists($args,'|')) {
		$temp=explode('|', $args);
		if (count($temp) == 2) {
			$typeid=(int)$temp[0];
			$num=(int)$temp[1];
		}else{
			$typeid=(int)$temp[0];
		}
	}else{
		$id=(int)$args;
	}

	$temp='';
	$ads=readCacheSystem('ad');
	if ($id > 0) {
		if (isset($ads[$id]) && ($ads[$id]['endtime'] ==0 || $ads[$id]['endtime'] >= WSKM_TIME)) {
			$temp=$ads[$id]['code'];
		}
	}elseif ($typeid > 0){
		$i=0;
		$isbreak=$num>0 ;
		$temp=array();
		foreach ($ads as $tempi){
			if ($isbreak && $i >= $num) {
				break;
			}

			if ($tempi['typeid'] == $typeid && ($tempi['endtime'] ==0 || $tempi['endtime'] >= WSKM_TIME) ) {
				$temp[$tempi['id']]=$tempi;
			}
			$i++;
		}
	}

	return $temp;
}



?>