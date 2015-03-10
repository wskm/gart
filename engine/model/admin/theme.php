<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [Gart] www.wskmphp.com 
 * $Id: theme.php 67 2010-09-30 07:31:19Z ws99 $ 
 */

!defined('IN_ART') && exit('Access Denied');

class model_admin_theme extends wskm_model_abstract
{
	function readAll()
	{
		$query=$this->db->query('SELECT * FROM '.TABLE_PREFIX."themes ");

		$themes=array();
		while ($item=$this->db->fetch($query)) {
			$themes[$item['styleid']]=$item;
		}
		return $themes;
	}

	function getThemes($isadmin=false){
		$where=$isadmin?' type=1 ':' type=0 ';
		$query=$this->db->query('SELECT * FROM '.TABLE_PREFIX."themes WHERE $where");
		$themes=$tnames=array();
		$styleid=$this->db->fetch_column('SELECT value FROM '.TABLE_PREFIX."settings WHERE variable='".($isadmin?'adminStyleId':'styleId')."'  ");
		$themescount=$this->db->fetch_column('SELECT count(*) FROM '.TABLE_PREFIX."themes WHERE ".$where);
		while ($item=$this->db->fetch($query)) {
			$localpic=$isadmin?ADMIN_ROOT.'themes'.DS.$item['name'].DS.'cover.jpg':ART_THEMES_PATH.$item['name'].DS.'cover.jpg';
			$pic=$isadmin?ADMIN_URL.'themes/'.$item['name'].'/cover.jpg':ART_URL.'themes/'.$item['name'].'/cover.jpg';
			$item['pic']=file_exists($localpic)?$pic:ART_URL.'images/common/nocover.jpg';
			$item['isdefault']=$themescount==1 ? true:$item['styleid']== $styleid ;
			$item['notdisable']=$item['isdefault'] ? true: $item['issys']==1;
			if ($isadmin) {
				$item['previewurl']=mvcUrl('',array('admin','index',array('adminstyleid'=>$item['styleid'])));
			}else{
				$item['previewurl']=mvcUrl('',array('index','index',array('styleid'=>$item['styleid'])),ART_URL,WSKM::getConfig('urlMode'));
			}
			$themes[]=$item;
			$tnames[]=$item['name'];
		}

		$installs = array();
		$dirpath =$isadmin? ADMIN_ROOT.'themes'.DS :ART_THEMES_PATH;
		$dirhand = dir($dirpath);
		while($dirname = $dirhand->read()) {
			$themepath = realpath($dirpath.$dirname);
			
			$subname=substr($dirname,0,1);
			if(  $subname!= '.' &&  $subname!= '_' && !in_array($dirname, $tnames) && is_dir($themepath)) {
				
				$localpic=$isadmin?ADMIN_ROOT.'themes/'.$dirname.DS.'cover.jpg':ART_THEMES_PATH.$dirname.DS.'cover.jpg';
				$pic=$isadmin?ADMIN_URL.'themes/'.$dirname.'/cover.jpg':ART_URL.'themes/'.$dirname.'/cover.jpg';
				$installs[]=array('name'=>strEncodeIn($dirname),'pic'=>file_exists($localpic)?$pic:ART_URL.'images/common/nocover.jpg');
			}
			//$index--;
		}

		return array('themes'=>$themes,'installs'=>$installs);
	}

	function install($name,$type){
		$type=$type>0?1:0;
				
		$res=$this->db->insert(TABLE_PREFIX.'themes',array('title'=>$name,'name'=>$name,'type'=>$type,'color'=>'#000000'))  !== false;
		$this->updateCache();
		return $res;
	}

	function uninstall($id){
		$res = $this->db->delete(TABLE_PREFIX.'themes',' styleid= '.$id)  !== false;
		$this->updateCache();
		return $res;
	}
	
	function update($args,$id){
		return $this->db->update(TABLE_PREFIX.'themes',$args," styleid ='{$id}' ") !== false;
	}
	
	function getInfo($styleid){
		return $this->db->fetch_first('SELECT * FROM '.TABLE_PREFIX."themes WHERE styleid ='{$styleid}' ");
	}
	
	function updateCache(){
		$query=$this->db->query('SELECT * FROM '.TABLE_PREFIX."themes ");

		$themes=array();
		while ($item=$this->db->fetch($query)) {
			$item['version']=WSKM::random(4);
			$themes[$item['styleid']]=$item;
		}
		
		writeCacheSystem('style',$themes);
	}
}

?>