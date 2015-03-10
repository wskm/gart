<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [Gart] www.wskmphp.com 
 * $Id: article.php 135 2010-10-15 15:59:43Z ws99 $ 
 */

!defined('IN_ART') && exit('Access Denied');

class model_admin_article extends wskm_model_abstract
{
	public $adminPageCount=15;
	private $tagsMaxNum=5;

	function getAdminTitles($uid=0)
	{
		$keys=array(
		'title'=>requestGet('title'),
		'cid'=>(int)requestGet('cid'),
		'status'=>(int)requestGet('status'),		
		'digest'=>(int)requestGet('digest')
		);

		$sorttype='DESC';
		if(strtoupper(requestGet('sorttype'))=='ASC')$sorttype='ASC';

		$search='';
		foreach ($keys as $tempi=>$key){
			if(empty($key) )continue;
			if ($tempi != 'title' && $tempi != 'status' ) {
				$search .= " AND a.`{$tempi}` ='{$key}' ";
			}
			elseif ($tempi == 'title'){
				$search .= " AND a.title like '%{$key}%' ";
			}
		}
		$keys['sorttype']=$sorttype;
		if ($keys['status'] === 0) {
			$search.=" AND a.status =1 ";
		}
		elseif ($keys['status'] === -1) {
			$search .= " AND a.status =0 ";
		}
		else{
			$search .= " AND a.status ='{$keys['status']}' ";
		}
		
		if ($uid>0) {
			$search .=" AND a.uid='{$uid}' ";
		}

		$pagesum=$this->getAdminTitlesCount($search);
		$cpage=0;
		$start_page=multiPage_start($cpage,$this->adminPageCount,$pagesum);

		$sql="SELECT a.* FROM ".TABLE_PREFIX."articles a WHERE 1=1 {$search} ORDER BY a.aid {$sorttype} LIMIT {$start_page},{$this->adminPageCount}";
		$query=$this->db->query($sql);
		$article=array();
		while ($tempi=$this->db->fetch($query)) {
			$cate=getCategoryData($tempi['cid']);
			$tempi['cname']=$cate['name'];
			$tempi['mvcurl']=mvcUrl('',array('news','show',array('id'=>$tempi['aid'])),ART_URL.'index.php',WSKM::getConfig('urlMode'));
			$article[]=$tempi;
		}

		return array('list'=>$article,'count'=>$pagesum,'page'=>$cpage,'keys'=>$keys);
	}

	function getAdminTitlesCount($search){
		$sql="SELECT COUNT(*) FROM ".TABLE_PREFIX."articles a WHERE 1=1 {$search} ";
		return (int)$this->db->fetch_column($sql);
	}

	function getAdminArticleFullInfo($aid)
	{
		$where=" a.aid ='$aid' ";
		$sql="SELECT * FROM ".TABLE_PREFIX."articles a LEFT JOIN ".TABLE_PREFIX."articlemessages m ON a.aid=m.aid WHERE {$where}";
		$data=$this->db->fetch_first($sql);

		return $data;
	}

	function updateTitle($args,$where)
	{
		return $this->db->update(TABLE_PREFIX.'articles',$args,$where) !== false;
	}

	function updateTitleById($titles,$aid){
		return $this->db->update(TABLE_PREFIX.'articles',$titles," aid='$aid' ") !== false;
	}

	function updateMessage($article,$aid){
		return $this->db->update(TABLE_PREFIX.'articlemessages',$article," aid='{$aid}' ") !== false;
	}

	function insertMessage($article){
		return $this->db->insert(TABLE_PREFIX.'articlemessages',$article) !==false;
	}

	function insertTitle($titles){
		$id=0;
		if ($this->db->insert(TABLE_PREFIX.'articles',$titles) !==false) {
			$id= $this->db->insert_id();
		}
		return $id;
	}

	static function filterTag($txt){
		$txt = str_replace(array(chr(0xa3).chr(0xac), chr(0xa1).chr(0x41), chr(0xef).chr(0xbc).chr(0x8c)), ',', $txt);

		$list=array();
		if(strExists($txt, ',')) {
			$list = explode(',', $txt);
		} else {
			$txt = str_replace(array(chr(0xa1).chr(0xa1), chr(0xa1).chr(0x40), chr(0xe3).chr(0x80).chr(0x80)), ' ', $txt);
			$list = explode(' ', $txt);
		}

		return array_filter( array_unique($list),'checkTag');
	}

	function setTagsMaxNum($num){
		$this->tagallownum=$num;
	}

	function setTags($txt,$aid){
		if (empty($txt)) {
			$tagObject=usingAdminModel('tag');
			$tagObject->delete($aid);
			return ;	
		}
		$oldtags=array();
		$query=$this->db->query('SELECT name FROM '.TABLE_PREFIX."articletags WHERE aid='{$aid}' ");
		while ($tempi=$this->db->fetch($query)){
			$oldtags[]=$tempi['name'];
		}

		$tags=self::filterTag($txt);

		if ($tags == $oldtags) {
			$this->db->update(TABLE_PREFIX.'articles', array('tags'=>implode(' ',$tags)) ," aid='{$aid}'");
			return ;
		}

		$handi=1;
		foreach ($tags as $tag){
			if ($handi > $this->tagsMaxNum) break;

			if(!in_array($tag,$oldtags)){
				$temptag=$this->db->fetch_first('SELECT close,tagid FROM '.TABLE_PREFIX."tags WHERE tagname='{$tag}' ");
				if ($temptag === false) {
					$this->db->insert(TABLE_PREFIX.'tags',array('tagname'=>$tag,'close'=>0,'count'=>1));
				}elseif(!$temptag['close']){
					$this->db->exec('UPDATE '.TABLE_PREFIX."tags SET count=count+1 WHERE tagid='{$temptag['tagid']}'  ");
				}

				$this->db->insert(TABLE_PREFIX.'articletags',array('name'=>$tag,'aid'=>$aid));
			}
			$handi++;
		}

		foreach ($oldtags as $tag){
			if (!in_array($tag,$tags) && ($this->db->delete(TABLE_PREFIX.'articletags'," name='{$tag}' AND aid='{$aid}' ") !== false)) {
				if($this->db->fetch_column('SELECT count(*) FROM '.TABLE_PREFIX."articletags WHERE name='{$tag}' AND aid!='$aid'")) {
					$this->db->exec('UPDATE '.TABLE_PREFIX."tags SET count=count-1 WHERE tagname='{$tag}'");
				} else {
					$this->db->delete(TABLE_PREFIX.'tags',"tagname='{$tag}'");
				}

			}
		}

		$this->db->update(TABLE_PREFIX.'articles', array('tags'=>implode(' ',$tags)) ," aid='{$aid}'");

		unset($oldtags,$tags);
	}

	function deleteComplete($aids){
		if ($aids) {
			$attachObject=usingAdminModel('attachment');
			if(!$attachObject->delete('',$aids,1))return 'delete_error_attach';

			$tagObject=usingAdminModel('tag');
			if(!$tagObject->delete($aids,1))return 'delete_error_tag';

			foreach (array('comments','articlemessages','articles') as $table){
				if($this->db->delete(TABLE_PREFIX.$table," aid IN ($aids) ") === false){
					return 'delete_error_article';
				}
			}
			return true;
		}
		return 'edit_error';
	}

	function getCommentCount($wherestr){
		return $this->db->fetch_column('SELECT COUNT(*) FROM '.TABLE_PREFIX."comments WHERE 1=1 $wherestr");
	}

	function getCommentList(){

		$keys=array(
		'status'=>requestGet('status',TYPE_INT)
		);

		$wherestr='';
		foreach ($keys as $key =>$tempi){
			$wherestr.=" AND `$key`='$tempi' ";
		}

		$currpage=1;
		$totalcount=$this->getCommentCount($wherestr);
		$startpage=multiPage_start($currpage,$this->adminPageCount,$totalcount);

		$list=array();
		$query=$this->db->query('SELECT * FROM '.TABLE_PREFIX."comments WHERE 1=1 $wherestr ORDER BY dateline DESC LIMIT {$startpage},{$this->adminPageCount} ");
		while ($tempi=$this->db->fetch($query)){
			if ($tempi['uid'] && $tempi['anonym']=='') {
				$tempi['photo']=getUserPhoto($tempi['uid'],'s',true);
				$tempi['spaceurl']=mvcUrl('',array('user','space',array('uid'=>$tempi['uid'])),ART_URL,WSKM::getConfig('urlMode'));
			}else{
				$tempi['photo']=getUserPhoto(0,'s',true);
				$tempi['spaceurl']='javascript:void(0);';
				$tempi['uname']=$tempi['anonym'];
			}
			$tempi['message']=strCut( strip_tags($tempi['message']),50,'...');
			$tempi['articleurl']=mvcUrl('',array('comment','list',array('id'=>$tempi['aid'])),ART_URL,WSKM::getConfig('urlMode'));
			$list[]=$tempi;
		}

		return array('list'=>$list,'count'=>$totalcount,'page'=>$currpage,'keys'=>$keys);
	}

	function getComment($commentid){
		$temp= $this->db->fetch_first('SELECT * FROM '.TABLE_PREFIX."comments WHERE id='$commentid' ");
		if ($temp['uid'] && $temp['anonym']=='') {
			$temp['photo']=getUserPhoto($temp['uid'],'s',true);
			$temp['spaceurl']=mvcUrl('',array('user','space',array('uid'=>$temp['uid'])),ART_URL,WSKM::getConfig('urlMode'));
		}else{
			$temp['photo']=getUserPhoto(0,'s',true);
			$temp['spaceurl']='javascript:void(0);';
			$temp['uname']=$temp['anonym'];
		}
		$temp['articleurl']=mvcUrl('',array('comment','list',array('id'=>$temp['aid'])),ART_URL,WSKM::getConfig('urlMode'));
		return $temp;
	}

	function updateArticleReplies($aid){
		$count = $this->db->fetch_column('SELECT COUNT(*) FROM '.TABLE_PREFIX."comments WHERE aid='{$aid}' ");
		return $this->db->update(TABLE_PREFIX.'articles',array('replies'=>$count)," aid='{$aid}' ") !==false;
	}
	
	function deleteComment($commentid,$aid=0){
		
		if (is_array($commentid)) {
			
			$cmids=array();
			foreach ($commentid as $value){
				$cmids=array_merge($cmids,$value);
			} 
			if($this->db->delete(TABLE_PREFIX.'comments','id',$cmids)===false){
				return false;
			}
			
			$aids=array_keys($commentid);
			foreach ($aids as $value){
				if($this->updateArticleReplies($value)===false){
					return false;
				}
			}
			return true;
		}else{
			if($this->db->delete(TABLE_PREFIX.'comments'," id = '$commentid' ") ===false){
				return false;
			}
			
			if($aid>0 && $this->updateArticleReplies($aid)===false){
				return false;
			}
			return true;
		}

	}

	function updateComment($info,$commentid){
		return $this->db->update(TABLE_PREFIX.'comments',$info," id='$commentid' ") !== false;
	}

	function urlAbsolute($txt){
		$txt=stripslashes ($txt);

		$indexurl=ART_URL_FULL;
		$indexurl='href="'.$indexurl.'index.php';
		$txt=str_replace('href="index.php',$indexurl, $txt);

		$txt=preg_replace_callback("/src\=\"(?!http\:\/\/)(?:\/)([^\>\s]{10,100})\.(jpg|gif|png)\"/i", 'addImageUrlAbsolute',$txt);
		$txt=htmlspecialchars_decode($txt);
		return addslashes($txt);

	}

	public function sync($args=''){
		usingArtClass('cache');
		art_cache::update('static');
		if (IS_HTML) {
			art_cache::updateHtml($args);
		}
	}

	function getArticleKinds($isshow=false){
		$where='';
		if ($isshow) {
			$where=' WHERE status=1 ';
		}

		return $this->db->fetch_all('SELECT * FROM '.TABLE_PREFIX.'articlekinds '.$where.' ORDER BY displaysort DESC ');
	}


	function insertArticleKinds($name,$sortnum=0,$status=1)
	{
		return $this->db->insert(TABLE_PREFIX.'articlekinds',array('name'=>$name,'displaysort'=>$sortnum,'status'=>$status)) !== false;
	}

	function updateArticleKinds($kid,$name,$sortnum,$status){
		return $this->db->update(TABLE_PREFIX.'articlekinds',array('name'=>$name,'displaysort'=>$sortnum,'status'=>$status)," kindid ='{$kid}'");
	}

	function insertPoll($title,$ismore,$dateline,$expire,$options){

		if( $this->db->insert(TABLE_PREFIX.'polls',array('title'=>$title,'ismore'=>$ismore,'dateline'=>$dateline,'expire'=>$expire)) !== false){
			$pollid=$this->db->insert_id();
			if (is_array($options) && count($options) >0) {
				foreach ($options as $option){
					$this->insertPollOption($pollid,$option);
				}
			}

			return $pollid;
		}
		return false;
	}

	function updatePoll($pollid,$title,$ismore,$dateline,$expire){
		return $this->db->update(TABLE_PREFIX.'polls',array('title'=>$title,'ismore'=>$ismore,'dateline'=>$dateline,'expire'=>$expire)," pollid = '{$pollid}'") !== false;
	}

	function updatePollOption($optionid,$name){
		if (empty($name)) {
			return $this->db->delete(TABLE_PREFIX.'pollsoptions',"optionid='{$optionid}'") !== false;
		}else{
			return $this->db->update(TABLE_PREFIX.'pollsoptions',array('name'=>$name),"optionid = '{$optionid}'") !== false;
		}
	}

	function insertPollOption($id,$name){
		if ($id && $name) {
			return $this->db->insert(TABLE_PREFIX.'pollsoptions',array('pollid'=>$id,'name'=>$name));
		}
		return false;
	}
	
	function getPolls(){
		$pagesum=$this->getPollCount();
		$cpage=0;
		$start_page=multiPage_start($cpage,$this->adminPageCount,$pagesum);

		$sql="SELECT * FROM ".TABLE_PREFIX."polls ORDER BY pollid DESC LIMIT {$start_page},{$this->adminPageCount}";
		$query=$this->db->query($sql);
		$article=array();
		while ($tempi=$this->db->fetch($query)) {
			$article[]=$tempi;
		}

		return array('list'=>$article,'count'=>$pagesum,'page'=>$cpage);
	}

	function getPollCount(){
		$sql="SELECT COUNT(*) FROM ".TABLE_PREFIX."polls  ";
		return (int)$this->db->fetch_column($sql);
	}
	
	function getPoll($pollid){
		$poll=$this->db->fetch_first('SELECT title,ismore,hits,dateline,expire FROM '.TABLE_PREFIX."polls WHERE pollid='{$pollid}' " );

		$polloption=$noteid=array();
		$details='';
		if ($poll !== false) {
			$sumpoll=$this->db->fetch_column('SELECT SUM(total) FROM '.TABLE_PREFIX."pollsoptions WHERE pollid='{$pollid}' ");
			$query=$this->db->query('SELECT optionid,total,name,detail FROM '.TABLE_PREFIX."pollsoptions WHERE pollid='{$pollid}'");
			while ($tempi=$this->db->fetch($query)){
				$details.="\r".$tempi['detail'];
				$tempi['ratio']=@sprintf('%01.2f',$tempi['total']*100/$sumpoll) ;
				$tempi['width']=(int)$tempi['ratio']?$tempi['ratio'].'%':'2px';
				$polloption[]=$tempi;
			}

			if(count($polloption)<1)return false;
		}else{
			return false;
		}

		if ($details) {
			$noteid=explode("\r",$details);
		}

		$user=WSKM::user();
		$vaildkey=is_object($user)?($user->getUid()?$user->getUid():USER_IP):USER_IP;
		$poll['inputtype']=$poll['ismore']?'checkbox':'radio';
		$poll['isput']=in_array($vaildkey,$noteid);
		$poll['isexpire']=$poll['expire']?(WSKM_TIME >= $poll['expire']):false;

		return array('poll'=>$poll,'options'=>$polloption);
	}

	function deletePoll($pollid,$isbatch=0){
		$wherestr='';
		if ($isbatch) {
			$wherestr=" pollid IN ($pollid) ";
		}else{
			$wherestr=" pollid='$pollid' ";
		}

		if($this->db->delete(TABLE_PREFIX.'pollsoptions',$wherestr) === false){
			return false;
		}

		if($this->db->delete(TABLE_PREFIX.'polls',$wherestr) === false){
			return false;
		}
		return true;
	}

}

function addImageUrlAbsolute($m) {
	return ' src="'.ART_URL_FULL.$m[1].'.'.$m[2].'" ';
}

function checkTag($txt){
	$txt=trim($txt);
	if (empty($txt)) {
		return false;
	}
	return preg_match('/^([\x7f-\xff_-]|\w){2,20}$/', $txt);
}

?>