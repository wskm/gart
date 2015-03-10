<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [Gart] www.wskmphp.com 
 * $Id: user.php 104 2010-10-02 14:09:26Z ws99 $ 
 */

!defined('IN_ART') && exit('Access Denied');

class model_admin_user extends wskm_model_abstract
{
	public $adminPageCount=15;

	function isIp($str) {
		$exp = array();
		if($exp = explode('.', $str)) {
			foreach($exp as $val) {
				if($val > 255) {
					return FALSE;
				}
			}
		}
		return preg_match("/^[\d]{1,3}\.[\d]{1,3}\.[\d]{1,3}\.[\d]{1,3}$/", $str);
	}

	function getUsers()
	{
		$keys=array(
		'uname'=>requestGet('uname',TYPE_STRING),
		'email'=>requestGet('email',TYPE_STRING),
		'lastip'=>requestGet('lastip',TYPE_STRING),
		'groupid'=>(int)requestGet('groupid'),
		);

		if ($keys['email'] && !isEmail($keys['email'])) {
			$keys['email']='';
		}

		if ($keys['lastip'] && !$this->isIp($keys['lastip'])) {
			$keys['lastip']='';
		}
		
		$sorttype='DESC';
		if(strtoupper(requestGet('sorttype'))=='ASC')$sorttype='ASC';

		$search='';
	
		foreach ($keys as $tempi=>$key){
			if(empty($key) )continue;
			if ($tempi == 'uname'){
				$search .= " AND u.uname like '%{$key}%' ";
			}
			else{
				$search .= " AND u.`{$tempi}` ='{$key}' ";
			}
		}
		$keys['sorttype']=$sorttype;
		
		$pagesum=$this->getUsersCount($search);
		$cpage=0;
		$start_page=multiPage_start($cpage,$this->adminPageCount,$pagesum);

		$sql="SELECT u.*,g.groupname FROM ".TABLE_PREFIX."users u LEFT JOIN ".TABLE_PREFIX."usergroups g ON u.groupid = g.groupid WHERE 1=1 {$search} ORDER BY u.uid {$sorttype} LIMIT {$start_page},{$this->adminPageCount}";
		$query=$this->db->query($sql);
		$article=array();
		while ($tempi=$this->db->fetch($query)) {
			$tempi['mvcurl']=mvcUrl('',array('user','space',array('uid'=>$tempi['uid'])),ART_URL,WSKM::getConfig('urlMode'));
			if ($tempi['adminid'] < 1 || UID == $tempi['uid'] || ADMINID < $tempi['adminid'] ) {
				$tempi['allowedit']=true;
			}else{
				$tempi['allowedit']=false;
			}
			$article[]=$tempi;
		}

		return array('list'=>$article,'count'=>$pagesum,'page'=>$cpage,'keys'=>$keys);
	}

	function getUsersCount($search){
		$sql="SELECT COUNT(*) FROM ".TABLE_PREFIX."users u WHERE 1=1 {$search} ";
		return (int)$this->db->fetch_column($sql);
	}
	
	function getUserGroups(){
		return $this->db->fetch_all('SELECT * FROM '.TABLE_PREFIX."usergroups ");
	}
	
	function updateUserGroup($info,$groupid){
		return $this->db->update(TABLE_PREFIX.'usergroups',$info," groupid='$groupid' ") !==false;
	}
	
	function insertUserGroup($info){
		return $this->db->insert(TABLE_PREFIX.'usergroups',$info);
	}
	
	function deleteUserGroup($gid){
		return $this->db->delete(TABLE_PREFIX.'usergroups'," groupid='$gid' ") !==false;
	}
	
	function updateCacheUserGroups(){
		$groups=$this->getUserGroups();
		foreach ($groups as $group){
			writeCacheSystem('usergroup'.$group['groupid'],$group);
		}
	}
		
	function getGroupInfo($gid){
		return $this->db->fetch_first('SELECT * FROM '.TABLE_PREFIX."usergroups WHERE groupid='$gid' ");
	}
	
	function updateUser($args,$uid){
		return $this->db->update(TABLE_PREFIX.'users',$args,"uid='$uid'") !== false;
	}
}
?>