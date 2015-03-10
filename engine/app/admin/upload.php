<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [Gart] www.wskmphp.com 
 * $Id: upload.php 103 2010-10-02 14:07:39Z ws99 $ 
 */

!defined('IN_ART') && exit('Access Denied');

class app_admin_upload extends admin_common
{

	function __construct()
	{
		parent::__construct();
		$this->model=usingAdminModel('attachment');
		usingArtFun('filesystem');
		WSKM::using('wskm_json');
	}

	function doCommon()
	{
		if (checkToken()) {
			if(empty($_FILES))return false;
			WSKM::using('wskm_fileupload');
			$iswater=(int)WSKM::getConfig('isWaterMark');
			$watertype=null;
			if ($iswater) {
				$watertype=(int)WSKM::getConfig('waterMarkType');
			}

			$imageimpath=ART_UPLOAD_PATH;
			$waterposition=(int)WSKM::getConfig('waterMarkPosition');
			$attachs=uploadAttachment('uploadattch',$imageimpath,0,$watertype,null,array('pos'=>$waterposition,'alpha'=>50,'jpg'=>70));

			$attachhtml=array();
			foreach ($attachs as $key=> $attach){
				if (isset($attach['err'])) {
					$attachhtml[$key]['err']=$attach['err'];
				}
				elseif($attach['name']){
					$attachid=$this->model->insert($this->getUid(),$attach['origin_name'],$attach['type'],$attach['size'],$attach['path'],$attach['width'],$attach['isimage'],$attach['isthumb']);
					$attachhtml[$key]['aid']=$attachid;
					$attachhtml[$key]['eid']=attachEncodeid($attachid);
					$attachhtml[$key]['width']=$attach['width'];
					$attachhtml[$key]['path']='attachments/'.htmlspecialchars($attach['path']);
					$attachhtml[$key]['icon'] = attachIcon($attach['ext'].' '.$attach['type']);
					$attachhtml[$key]['isimage'] = $attach['isimage'];
					$attachhtml[$key]['isthumb'] = $attach['isthumb'];
					$attachhtml[$key]['name'] = $attach['origin_name'];
				}
			}

			xmlMessage(jsonEncode($attachhtml));
		}
	}

	function doAjax_del()
	{
		$attachid=(int)requestGet('attachid');
		if($attachid && $this->model->delete($attachid))
		{
			echo 'ok';
			return ;
		}
		echo 'err';
	}

	function doArticleCover()
	{
		if (checkToken()) {
			if(empty($_FILES))return false;
			WSKM::using('wskm_fileupload');
			
			$aid=requestPost('aid',TYPE_INT);
			$cid=requestPost('cid',TYPE_INT);
			$iswater=(int)WSKM::getConfig('isWaterMark');
			$watertype=null;
			if ($iswater) {
				$watertype=(int)WSKM::getConfig('waterMarkType');
			}

			$imageimpath=ART_UPLOAD_PATH;
			$waterposition=(int)WSKM::getConfig('waterMarkPosition');
			$attachs=uploadAttachment('thumbupload',$imageimpath,1,$watertype,null,array('pos'=>$waterposition,'alpha'=>50,'jpg'=>70));

			$attachhtml=array();
			foreach ($attachs as $key=> $attach){
				if (isset($attach['err'])) {
					$attachhtml[$key]['err']=$attach['err'];
				}
				elseif($attach['name']){
					$attachid=$this->model->insert($this->getUid(),$attach['origin_name'],$attach['type'],$attach['size'],$attach['path'],$attach['width'],$attach['isimage'],$attach['isthumb'],$aid,$cid);
					$attachhtml[$key]['attachid']=$attachid;
					$atpath= htmlspecialchars('attachments/'.$attach['path']);
					$attachhtml[$key]['width']=$attach['width'];
					$attachhtml[$key]['path']=$atpath;
				}
			}

			WSKM::using('wskm_json');
			xmlMessage(jsonEncode($attachhtml));
		}
	}

	function doIsexist_thumb()
	{
		$id=requestGet('id',TYPE_INT);

		$isok=false;
		if ($id) {
			$isok=$this->model->IsExistThumb($id);
		}
		if( $isok !== false){
			echo toThumbPath($isok);
		}
	}

}

?>