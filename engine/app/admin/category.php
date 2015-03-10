<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [Gart] www.wskmphp.com 
 * $Id: category.php 67 2010-09-30 07:31:19Z ws99 $ 
 */

!defined('IN_ART') && exit('Access Denied');
class app_admin_category extends admin_common
{
	function load()
	{
		$this->model=usingAdminModel('category');
		loadLang('admin_category');
		WSKM::using('wskm_tree');
		usingArtClass('cache');
	}

	function doIndex()
	{
		if (checkToken()) {
			$selects=requestPost('selects',TYPE_ARRAY);
			if (count($selects)  > 0) {
				foreach ($selects as $cid){
					if ($cid > 0) {
						$this->deleteCategory($cid);
					}
				}
				$this->model->updateCache();
				admin_csync::sync();
				adminMessage('del_ok','index.php?wskm=category');
			}
		}
		assign_var('categorys_selectoption',$this->model->getSelectOption($this->model->getList(),0));
		assign_var('categorys',$this->model->tree->getTreeList());

		adminTemplate('category');
	}

	function doAdd()
	{
		$parentid=(int)requestGet('id');
		$cate=array(
		'cid'=>0,
		'parentid'=>$parentid,
		"displaysort"=>'99',
		"isnav"=>'',
		"navkey"=>'',
		"tpllist"=> "category",
		"tplshow"=>"news",
		"status"=>"1"
		);

		if ($parentid>0) {
			$parentcate=$this->model->getAdminCategory($parentid);
			if ($parentcate) {
				$cate['tpllist']=$parentcate['tpllist'];
				$cate['tplshow']=$parentcate['tplshow'];
				$cate['navkey']=$parentcate['navkey'];
			}
		}

		assign_var('html_selects',$this->model->getSelectOption($this->model->getList(),$parentid));
		assign_var('cate',$cate);
		assign_var('action','add');
		adminTemplate('category_info');

	}

	function doEdit()
	{
		if (checkToken()) {
			$isedit=false;
			$cid=requestPost('cid',TYPE_INT);
			$postact=requestPost('postact',TYPE_ALNUM);

			$navkey=requestPost('navkey',TYPE_ALNUM);
			$parentid=requestPost('parentid',TYPE_INT);

			$cateplus=array(
			'parentid'=>$parentid,
			'name'=>requestPost('catename',TYPE_STRING,50),
			'cover'=>requestPost('oldcover',TYPE_STRING,255),
			'url'=>requestPost('url',TYPE_STRING,255),
			'description'=>requestPost('description',TYPE_STRING),
			'keywords'=>requestPost('keywords',TYPE_STRING),
			'displaysort'=>requestPost('displaysort',TYPE_INT),
			'tpllist'=>requestPost('tpllist',TYPE_WORD,50),
			'tplshow'=>requestPost('tplshow',TYPE_WORD,50),
			'isnav'=>requestPost('isnav',TYPE_INT),
			'navkey'=>$navkey,
			);

			if ($postact=='edit') {
				$isedit=true;
				$cateplus['cid']=$cid;
				$cateplus['navkey']=$navkey?$navkey:'cate'.$cid;
			}

			if (!$cateplus['tpllist']) {
				$cateplus['tpllist']='category';
			}

			if (!$cateplus['tplshow']) {
				$cateplus['tplshow']='news';
			}

			if ($isedit) {
				if(!$this->model->update($cateplus,$cid)){
					adminMessage('cate_eidtbad',-1);
				}
			}else{
				if($this->model->insert($cateplus)){
					$cid=$this->db->insert_id();
				}else{
					adminMessage('add_error','index.php?wskm=category');
				}
			}

			if ($_FILES && $_FILES['cover']['name'][0]) {
				WSKM::using('wskm_fileupload');
				$watertype=null;
				if ((bool)WSKM::getConfig('isWaterMark')) {
					$watertype=(int)WSKM::getConfig('waterMarkType');
				}
				$imageimpath=ART_UPLOAD_PATH;
				$waterposition=(int)WSKM::getConfig('waterMarkPosition');
				$attachs=uploadAttachment('cover',$imageimpath,0,$watertype,'',array('pos'=>$waterposition,'alpha'=>50,'jpg'=>70));

				$attach=$attachs[0];
				if (isset($attach['err'])) {
					adminMessage($attach['err'],'index.php?wskm=category&act=edit&id='.$cid);
				}
				elseif($attach['name']){
					$attachObject=usingAdminModel('attachment');
					$attachObject->insert($this->getUid(),$attach['origin_name'],$attach['type'],$attach['size'],$attach['path'],$attach['width'],$attach['isimage'],$attach['isthumb'],0,$cid);
					$atpath= htmlspecialchars($attach['path']);

					if(!$this->model->update(array('cover'=>$atpath),$cid)){
						adminMessage('cate_eidtbad','index.php?wskm=category&act=edit&id='.$cid);
					}
				}
			}

			$this->model->updateCache();
			admin_csync::sync();

			if ($isedit) {
				adminMessage('cate_eidtok',getUrlReferer());
			}
			adminMessage('add_ok','index.php?wskm=category');

		}else{
			$cid=(int)requestGet('id');
			$cate=$this->model->getAdminCategory($cid);

			assign_var('html_selects',$this->model->getSelectOption($this->model->getList(),$cate['parentid']));
			assign_var('cate',$cate);
			assign_var('cid',$cid);
			assign_var('action','edit');
			adminTemplate('category_info');
		}
	}

	function doDrop()
	{
		$cid=(int)requestGet('id');
		if ($cid >0) {
			$this->deleteCategory($cid);
			$this->model->updateCache();
			admin_csync::sync();
			adminMessage('del_ok','index.php?wskm=category');
		}
	}


	private function deleteCategory($cid){
		if ($this->model->getChildCount($cid) > 0) {
			adminMessage('child_exist_nodel','index.php?wskm=category');
		}

		$aids = $this->model->getAids($cid);
		$aids2 = implode(',', $aids);

		if ($aids) {
			$objarticle=usingAdminModel('article');
			$msg=$objarticle->deleteComplete($aids2);
			if($msg !== true){
				adminMessage($msg,-1);
			}
			if (IS_HTML) {
				art_cache::deleteHtml($aids);
			}
		}

		if($this->model->delete($cid) ==false ){
			adminMessage('del_error','index.php?wskm=category');
		}
	}

	function doAjaxName(){
		$value=requestPost('value',TYPE_STRING);
		$id=substr(requestPost('id',TYPE_ALNUM),3);

		$column='name';
		if($id > 0 )
		{
			$res=$this->model->getAdminCategory($id);
			$isok=$this->model->isUnique($value,$res['parentid'],$id);
			if (!$isok) {
				return ;
			}

			if(!$this->model->update(array($column=>$value),$id) ){
				$value='error';
			}
		}

		echo $value;
	}

	function doAjaxSort(){
		$value=requestPost('value',TYPE_INT);
		$id=substr(requestPost('id',TYPE_ALNUM),3);

		$column='displaysort';
		if($id > 0 && !$this->model->update(array($column=>$value),$id))
		{
			$value='error';
		}

		echo $value;
	}

	function doCheck_category()
	{
		$name=requestGet('name');
		$parentid=requestGet('parentid');
		$cid=requestGet('cid');
		if ($this->model->isUnique($name,$parentid,$cid)) {
			echo 'true';
		}
		else{
			echo 'false';
		}
	}

	function doUpdateCache(){
		$this->model->updateCache();
		admin_csync::sync();
		adminMessage('update_cache_ok',getUrlReferer());
	}
}

?>