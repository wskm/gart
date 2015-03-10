<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [Gart] www.wskmphp.com 
 * $Id: article.php 256 2010-11-28 21:31:47Z ws99 $ 
 */

!defined('IN_ART') && exit('Access Denied');

class model_article extends wskm_model_abstract
{
	function getArticleNews($count=15,$cid='',$bigids=''){
		$count=(int)$count;

		$wherestr='';
		if ($cid) {
			$wherestr = " AND cid ='{$cid}' ";
		}
		if ($bigids) {
			$wherestr .= " AND aid NOT IN ($bigids) ";
		}

		$sql='SELECT aid,cid,uname,dateline,title,titlestyle,summary,digest FROM '.TABLE_PREFIX."articles WHERE status=1 {$wherestr} ORDER BY dateline DESC limit {$count}";
		return $this->getNewsBySql($sql);
	}

	function getArticleBests($count=15){
		$count=(int)$count;
		$sql='SELECT aid,cid,uname,dateline,title,titlestyle,summary,digest FROM '.TABLE_PREFIX."articles WHERE status=1 AND digest=1 ORDER BY aid DESC limit {$count}";
		return $this->getNewsBySql($sql);
	}

	function getArticleHots($count=15,$cycle=''){
		$count=(int)$count;
		$wherestr='';
		if ($cycle=='week') {
			$wherestr=' AND YEARWEEK(FROM_UNIXTIME(dateline),1)=YEARWEEK(NOW(),1) ';
		}

		$sql='SELECT aid,cid,uname,dateline,title,views FROM '.TABLE_PREFIX."articles WHERE status=1 {$wherestr} ORDER BY views DESC limit {$count}";
		return $this->getNewsBySql($sql);
	}

	function getArticlePics($count=8,$cid=0){
		$count=(int)$count;
		$cid=(int)$cid;
		$wherestr=$cid?"  cid='{$cid}' AND ":'';
		$sql='SELECT aid,cid,dateline,title,titlestyle,cover,summary,digest FROM '.TABLE_PREFIX."articles WHERE $wherestr status=1 AND cover !='' ORDER BY views DESC limit {$count}";

		return $this->getNewsBySql($sql);
	}

	function getArticleMessage($id){
		return $this->db->fetch_first('SELECT mid,aid,message,pagetype FROM '.TABLE_PREFIX."articlemessages WHERE aid= '{$id}' ");
	}

	function getNewsBySql($sql){
		if (!$sql) {
			throw new wskm_exception('Class article -> getTitlesBySql Error');
		}
		$list=array();
		$query=$this->db->query($sql);
		while ($row=$this->db->fetch($query)) {
			if ($row['cover'] && !strExists($row['cover'],'://')) {
				$row['cover'] = ART_URL.$row['cover'];
			}
			$row['mvcurl']=mvcUrl('',array('news','show',array('id'=>$row['aid'])));
			$list[]=$row;
		}
		return $list;
	}

	function getNewsById($aids,$orderstr=''){
		if ($orderstr=='') {
			$orderstr='ORDER BY dateline DESC';
		}
		$sql='SELECT aid,cid,uid,uname,dateline,title,titlestyle,cover,summary FROM '.TABLE_PREFIX."articles WHERE aid IN ({$aids}) AND status=1 {$orderstr} ";
		return $this->getNewsBySql($sql);
	}

	function getArticleFullInfo($aid){
		$data= $this->db->fetch_first('SELECT * FROM '.TABLE_PREFIX."articles a LEFT JOIN ".TABLE_PREFIX."articlemessages m ON a.aid = m.aid  WHERE a.aid='{$aid}' AND a.status=1 ");
		if ($data['fromname']) {
			$data['fromurl']=empty($data['fromurl'])?'#':$data['fromurl'];
			$data['comefrom']='<a href="http://'.$data['fromurl'].'" target="_blank" >'.$data['fromname'].'</a>';
		}
	
		if ($data['tags']) {
			$data['tags']= explode(' ',$data['tags']);
			$data['htmltags']=$data['htmlkeywords']='';
			foreach ($data['tags'] as $tag){
				$data['htmltags'] .='<a href="'. mvcUrl('',array('tag','show',array('name'=>$tag))) .'" target="_blank" >'.$tag.'</a>&nbsp;';
				$data['htmlkeywords'].=$tag.',';
			}
		}
		
		$data['mvcurl']=mvcUrl('',array('news','show',array('id'=>$data['aid'])));
		if ($data['cover'] && !strExists($data['cover'],'://')) {
			$data['cover'] = ART_URL.$data['cover'];
		}

		return $data;
	}

	function getArticleInfo($aid){
		$data= $this->db->fetch_first('SELECT cid,kindid,title,uid,uname,dateline,title,views,replies,replystate,tags,author,fromname,fromurl,summary,kiss,bury FROM '.TABLE_PREFIX."articles WHERE aid='{$aid}' AND status=1 ");
		if ($data['fromname']) {
			$data['fromurl']=empty($data['fromurl'])?'#':$data['fromurl'];
			$data['comefrom']='<a href="http://'.$data['fromurl'].'" target="_blank" >'.$data['fromname'].'</a>';
		}

		if ($data['tags']) {
			$data['tags']= explode(' ',$data['tags']);
			$data['htmltags']=$data['htmlkeywords']='';
			foreach ($data['tags'] as $tag){
				$data['htmltags'] .='<a href="'. mvcUrl('',array('tag','show',array('name'=>$tag))) .'" target="_blank" >'.$tag.'</a>&nbsp;';
				$data['htmlkeywords'].=$tag.',';
			}

		}

		return $data;
	}

	function updateClicks($aid){
		$this->db->exec('UPDATE '.TABLE_PREFIX.'articles SET views = views+1  WHERE aid= '.$aid);
	}

	function updateNewsKiss($aid,$type=1){
		$wherestr=' kiss=kiss+1 ';
		if ($type != 1) {
			$wherestr=' bury=bury+1 ';
		}
		$this->db->exec('UPDATE '.TABLE_PREFIX."articles SET {$wherestr} WHERE aid= '{$aid}' ");
	}

	function getCommentsSome($aid,$ishot=0){
		$wherestr='';
		if ($ishot) {
			$wherestr=' AND kiss > 10 ORDER BY kiss DESC LIMIT 0,5 ';
		}else{
			$wherestr=' ORDER BY dateline DESC LIMIT 0,10 ';
		}

		$query=$this->db->query('SELECT * FROM '.TABLE_PREFIX."comments WHERE aid ='{$aid}' AND status=1 {$wherestr} ");
		$comments=array();
		while ($tempi=$this->db->fetch($query)){

			if ($tempi['uid'] && $tempi['anonym']=='') {
				$tempi['photo']=getUserPhoto($tempi['uid'],'s',true);
				$tempi['spaceurl']=mvcUrl('',array('user','space',array('uid'=>$tempi['uid'])));
			}else{
				$tempi['photo']=getUserPhoto(0,'s',true);
				$tempi['spaceurl']='javascript:void(0);';
				$tempi['uname']=$tempi['anonym'];
			}

			$comments[]=$tempi;
		}
		return $comments;
	}

	function switchArticleID($type,$aid,$cid){
		$wherestr='';
		if ($type=='next') {
			$wherestr=" aid < '$aid' AND cid='$cid' AND status=1 ORDER BY aid DESC ";
		}else{
			$wherestr=" aid > '$aid' AND cid='$cid' AND status=1 ORDER BY aid ";
		}

		return $this->db->fetch_column('SELECT aid FROM '.TABLE_PREFIX."articles WHERE $wherestr LIMIT 1");
	}

	function switchUrl($aid,$cid){

		$list=array();
		$temp=$this->db->fetch_first('SELECT aid,title FROM '.TABLE_PREFIX."articles WHERE aid < '$aid' AND cid='$cid' AND status=1 ORDER BY aid DESC LIMIT 1");

		if ($temp) {
			$list['last']='<li>'.lang('last_url').'<a href="'.mvcUrl('',array('news','show',array('id'=>$temp['aid']))).'" >'.$temp['title'].'</a></li>';
		}

		$temp=$this->db->fetch_first('SELECT aid,title FROM '.TABLE_PREFIX."articles WHERE aid > '$aid' AND cid='$cid' AND status=1 ORDER BY aid LIMIT 1");
		if ($temp) {
			$list['next']='<li>'.lang('next_url').'<a href="'.mvcUrl('',array('news','show',array('id'=>$temp['aid']))).'" >'.$temp['title'].'</a></li>';
		}

		return $list;
	}

}




?>