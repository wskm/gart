<?php

!defined('IN_ART') && exit('Access Denied');

function wskm_index(){
	$data=array();
	$classArticle=usingModel('article');
	$data['bests']=$classArticle->getArticleBests(10);
	$data['hots']=$classArticle->getArticleHots(10);

	$temp='';
	$pollnum=(int)WSKM::getConfig('indexPollCount');

	$db=WSKM::SQL();
	$query=$db->query('SELECT pollid FROM '.TABLE_PREFIX."polls WHERE expire NOT BETWEEN 1 AND UNIX_TIMESTAMP() ORDER BY pollid DESC  LIMIT {$pollnum} " );
	while ($tempi=$db->fetch($query)) {
		$temp .='<script type="text/javascript" language="javascript" src="'.ART_URL_FULL.'poll.php?id='.$tempi['pollid'].'" ></script>';
	}
	$data['poll']=$temp;

	$data['pics']=$classArticle->getArticlePics(8);
	$announceObj=usingModel('announce');
	$data['announce']=$announceObj->getNews(8);

	$temp=array();
	$bigid=(int)getDbCache('bignews');
	if ($bigid > 0) {
		$temp=(array)$classArticle->getNewsById("'$bigid'");
	}
	$data['bignews']=$temp[0];

	$smalls=(array)unserialize(getDbCache('smallnews'));
	$data['smallnews']=$classArticle->getNewsById(wkImplode($smalls,','));

	$temp=(array)unserialize(getDbCache('cyclepic'));
	$data['cycles']=$classArticle->getNewsById(wkImplode($temp,','));

	$smalls[]=$bigid;
	$data['news']=$classArticle->getArticleNews(16,'',wkImplode($smalls,','));
	return $data;
}

function wskm_right(){
	$data=array();
	$classArticle=usingModel('article');
	$data['news']=$classArticle->getArticleNews(10);
	$data['weekhots']=$classArticle->getArticleHots(15,'week');

	$model=usingModel('tag');
	$tags=$model->getRandomTagName(20);
	$taglist=array();
	foreach ($tags as $key=>$tag){
		$taglist[$key]['name']=$tag;
		$taglist[$key]['classkey']=mt_rand(1,10);
		$taglist[$key]['mvcurl']=mvcUrl('',array('tag','show',array('name'=>$tag)));
	}
	$data['tags']=$taglist;

	return $data;
}



?>