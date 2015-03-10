<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [Gart] www.wskmphp.com 
 * $Id: attachment.php 104 2010-10-02 14:09:26Z ws99 $ 
 */

!defined('IN_ART') && exit('Access Denied');

class model_admin_attachment extends wskm_model_abstract
{
	public function insert($uid,$filename,$filetype,$filesize,$filepath,$width,$isimage,$isthumb,$aid=0,$cid=0)
	{
		$filepath=str_replace(DS,'/',$filepath);
		$argv=array('uid'=>$uid,'uploadtime'=>WSKM_TIME,'filename'=>$filename,'filetype'=>$filetype,
		'width'=>$width,'filesize'=>$filesize,'filepath'=>$filepath,'isimage'=>$isimage,'isthumb'=>$isthumb,'aid'=>$aid,'cid'=>$cid
		);

		if($this->db->insert(TABLE_PREFIX.'attachments',$argv,true)  !== false){
			return $this->db->insert_id();
		}
		return false;
	}

	public function delete($attachid,$aid=0,$isbatch=0)
	{
		$wherestr='';
		
		if (!$attachid && !$aid) {
			return false;
		}
		
		if ($isbatch) {
			if ($attachid) {
				$wherestr = " id in ({$attachid}) ";
			}elseif($aid){
				$wherestr = " aid in ({$aid}) ";
			}
		}else{
			if ($attachid >0) {
				$wherestr = " id='{$attachid}' ";
			}elseif($aid>0){
				$wherestr = " aid='{$aid}' ";
			}
			
		}

		$query=$this->db->query('SELECT filepath,isthumb FROM '.TABLE_PREFIX.'attachments  WHERE '.$wherestr);
		
		while ( $attch=$this->db->fetch($query) ) {
			$path=ART_ROOT.'attachments'.DS.$attch['filepath'];
			@unlink($path);
			if ((int)$attch['isthumb'] >0 ) {
				@unlink(dirname($path).DS.'thumb'.DS.'9'.basename($path));
			}
		}
		return $this->db->delete(TABLE_PREFIX.'attachments',$wherestr)  !== false;
	}

	public function update($attachid,$aid,$uid=0,$cid=0,$ismut=false){
		return $this->db->update(TABLE_PREFIX.'attachments',array('aid'=>$aid,'uid'=>$uid,'cid'=>$cid),$ismut?"id IN ({$attachid})":" id='{$attachid}'")  !== false;
	}

	public function getAttachment($aid)
	{
		$query=$this->db->query('SELECT id,uid,uploadtime,downloads,filename,filetype,filepath,width,isimage,isthumb FROM '.TABLE_PREFIX."attachments  WHERE aid='{$aid}'");
		$data=array();
		while ($row=$this->db->fetch($query)) {
			if ($row['isimage']) {
				$row['thumbpath']=toThumbPath($row['filepath']);
			}
			$row['encodeid']=attachEncodeid($row['id']);
			$row['ext'] = strtolower(fExt($row['filename']));
			$row['icon'] = attachIcon($row['ext'].' '.$row['filetype']);
			$data[]=$row;
		}
		return $data;
	}

	public function getAttachmentByAid($attachid){
		return $this->db->fetch_first('SELECT id,aid,cid,uid,uploadtime as `time`,downloads,filename,filetype,filepath,filesize,width,isimage FROM '.TABLE_PREFIX."attachments  WHERE id='{$attachid}'");
	}

	public function getUseAttachment(){
		$query=$this->db->query('SELECT id,filename,filetype,filepath,width,isimage FROM '.TABLE_PREFIX."attachments  WHERE aid=0 AND cid=0 ");
		$data=array();
		while ($row=$this->db->fetch($query)) {
			if ($row['isimage']) {
				$row['thumbpath']=toThumbPath($row['filepath']);
			}
			$row['encodeid']=attachEncodeid($row['id']);
			$row['ext'] = strtolower(fExt($row['filename']));
			$row['icon'] = attachIcon($row['ext'].' '.$row['filetype']);			
			$data[]=$row;
		}

		return $data;

	}

	public function updateDownloadCount($attachid){
		return $this->db->exec('UPDATE '.TABLE_PREFIX."attachments SET downloads= downloads+1 WHERE id='$attachid' ") !== false;
	}

	public function isExistThumb($id)
	{
		$data=$this->db->fetch_first("SELECT isthumb,filepath FROM ".TABLE_PREFIX."attachments WHERE id='$id' ");
		if ((int)$data['isthumb'] == 0) {
			return false;
		}
	
		return $data['filepath'];
	}

}

function attachEncodeid($id){
	return $id;
}

function attachDecodeid($id){
	return $id;
}



?>