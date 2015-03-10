<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [Gart] www.wskmphp.com 
 * $Id: comment.php 198 2010-11-15 04:34:14Z ws99 $ 
 */

!defined('IN_ART') && exit('Access Denied');

class model_comment extends wskm_model_abstract
{
	public $commentPageCount=15;
	function insert($comment){
		if($this->db->exec('UPDATE '.TABLE_PREFIX."articles SET replies=replies+1 WHERE aid={$comment['aid']} ") !== false){
			if ($comment['uid'] > 0) {
				$this->db->exec('UPDATE '.TABLE_PREFIX."users SET replycount=replycount+1,lastreplytime='".WSKM_TIME."'  WHERE uid='{$comment['uid']}'");
			}
			
			if($this->db->insert(TABLE_PREFIX.'comments',$comment) !== false){
				return $this->db->insert_id();
			}
		}
		return false;
	}
	
	function getListCount($aid){
		return $this->db->fetch_column('SELECT COUNT(*) FROM '.TABLE_PREFIX."comments WHERE aid='{$aid}' AND status=1 ");
	}
	
	function getList($aid){

		$currpage=1;
		$totalcount=$this->getListCount($aid);
		$startpage=multiPage_start($currpage,$this->commentPageCount,$totalcount);

		$list=array();
		$query=$this->db->query('SELECT id,aid,uid,uname,anonym,ip,dateline,message,kiss,`status` FROM '.TABLE_PREFIX."comments WHERE aid='{$aid}' AND status=1  ORDER BY dateline DESC LIMIT {$startpage},{$this->commentPageCount} ");
		while ($tempi=$this->db->fetch($query)){
			if ($tempi['uid'] && $tempi['anonym']=='') {
				$tempi['photo']=getUserPhoto($tempi['uid'],'s',true);
				$tempi['spaceurl']=mvcUrl('',array('user','space',array('uid'=>$tempi['uid'])));
			}else{
				$tempi['photo']=getUserPhoto(0,'s',true);
				$tempi['spaceurl']='javascript:void(0);';
				$tempi['uname']=$tempi['anonym'];
			}
			$list[]=$tempi;
		}

		return array('list'=>$list,'count'=>$totalcount,'page'=>$currpage);
	}
	
	function getArticleInfo($aid){
		return $this->db->fetch_first('SELECT aid,title,replystate,status FROM '.TABLE_PREFIX."articles WHERE aid='$aid'  " );
	}
	
	function vote($id){
		$user=WSKM::user();
		$vaildkey=is_object($user)?($user->getUid()?$user->getUid():USER_IP):USER_IP;				
		$temp=$this->db->fetch_first('SELECT uid,ip,detail FROM '.TABLE_PREFIX."comments WHERE id='{$id}'  ");
		if ($temp['uid'] == $user->getUid() || $temp['ip'] == USER_IP ) {
			return -1;
		}
		
		if (strExists("\r".$temp['detail']."\r","\r".$vaildkey."\r") || $this->db->exec('UPDATE '.TABLE_PREFIX."comments SET  kiss=kiss+1,detail=CONCAT(detail,'{$vaildkey}\r') WHERE id='{$id}' ") === false){
			return 0;
		}
		
		return 1;
	}
}


?>