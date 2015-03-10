<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [Gart] www.wskmphp.com 
 * $Id: search.php 260 2010-11-29 11:06:27Z ws99 $ 
 */

define('IN_ART',true);
define('IS_MVC',false);
require('./includes/inc_plugin.php');

$wk=requestGet('wk',TYPE_STRING,30);
$searchdata=array();
if ($wk) {
	$searchkey=array(
	'wk'=>rawurlencode($wk),
	);
	$searchtype='title';
	if ($searchtype=='title') {
		$search .= ' AND title LIKE \'%'.$wk.'%\' ';
	}

	$pageCount=15;
	$currpage=1;

	$db=WSKM::SQL();
	$totalcount=$db->fetch_column('SELECT COUNT(*) FROM '.TABLE_PREFIX."articles WHERE status=1  {$search} ");

	$startpage=multiPage_start($currpage,$pageCount,$totalcount);

	$cateCache=null;
	if ($totalcount) {
		$cateCache=WSKM::getValues('category','tree');
	}

	$list=array();
	$query=$db->query('SELECT aid,cid,uid,uname,dateline,title,titlestyle,summary FROM '.TABLE_PREFIX."articles WHERE status=1 {$search} ORDER BY dateline DESC LIMIT {$startpage},{$pageCount} ");
	while ($tempi=$db->fetch($query)){
		$tempi['cateurl']='[<a href="'.$cateCache[$tempi['cid']]['mvcurl'].'" target="_blank" >'.$cateCache[$tempi['cid']]['name'].'</a>]';
		$tempi['mvcurl']=mvcUrl('',array('news','show',array('id'=>$tempi['aid'])));
		$searchdata[]=$tempi;
	}

	assign_var('htmlpage',multiPage($totalcount,$currpage,'search.php?'.http_build_query($searchkey),$pageCount));
}

assign_var('page_title',lang('search').'_'.$searchkey['wk'].'_');
assign_var('searchdata',$searchdata);
assign_var('wk',$wk);
template('search');

?>