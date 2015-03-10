<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [Gart] www.wskmphp.com 
 * $Id: log.php 20 2010-08-31 06:35:04Z ws99 $ 
 */

!defined('IN_ART') && exit('Access Denied');

class model_admin_log extends wskm_model_abstract
{
	public $adminPageCount=15;
	function insertAdminLoginLog($args){
		return $this->db->insert(TABLE_PREFIX.'adminloginlog',$args) !== false;
	}

	function getLoginList(){

		$sorttype='DESC';
		if(strtoupper(requestGet('sorttype'))=='ASC')$sorttype='ASC';

		$keys=array();
		$keys['sorttype']=$sorttype;
		$pagesum=$this->getLoginListCount('');
		$cpage=0;
		$start_page=multiPage_start($cpage,$this->adminPageCount,$pagesum);

		$sql="SELECT *,l.type as logintype FROM ".TABLE_PREFIX."adminloginlog l ORDER BY l.logid {$sorttype} LIMIT {$start_page},{$this->adminPageCount}";
		$query=$this->db->query($sql);
		$article=array();
		while ($tempi=$this->db->fetch($query)) {			
			$article[]=$tempi;
		}
		return array('list'=>$article,'count'=>$pagesum,'page'=>$cpage,'keys'=>$keys);
	}

	function getLoginListCount($search){
		$sql="SELECT COUNT(*) FROM ".TABLE_PREFIX."adminloginlog l WHERE 1=1 {$search} ";
		return (int)$this->db->fetch_column($sql);
	}
	
	function delLoginLog($logid){
		return $this->db->delete(TABLE_PREFIX.'adminloginlog',"logid='{$logid}' ");
	}

	function getAdminLastLoginLog($uid){
		$logs=$this->db->fetch_all('SELECT ip,logintime FROM '.TABLE_PREFIX."adminloginlog WHERE uid='{$uid}' AND type=1 ORDER BY logintime DESC limit 0,2  ");
		if (count($logs) == 2) {
			array_shift($logs);
			return $logs[0];
		}else{
			return false;
		}
	}

	function deleteExpiredAdminLoginLog(){
		$this->db->delete(TABLE_PREFIX.'adminloginlog',' logintime < UNIX_TIMESTAMP()-1209600 '); //1209600
	}

}

?>