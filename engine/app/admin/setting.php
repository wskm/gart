<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [Gart] www.wskmphp.com 
 * $Id: setting.php 103 2010-10-02 14:07:39Z ws99 $ 
 */

!defined('IN_ART') && exit('Access Denied');

class app_admin_setting extends admin_common
{
	function load()
	{
		loadLang('admin_setting');
	}

	function doIndex()
	{
		if (!$this->isManageAccess()) {
			adminMessage('access_not');
		}

		$this->model=usingAdminModel('setting');
		if (!IS_POST) {
			WSKM::helper('html');
			$type=requestGet('type',TYPE_ALNUM);
			if (empty($type)) {
				$type='base';
			}
			if (!in_array($type,array('style','base','sys','article','img','reg','login','attach','vcode','mail'))) {
				$type='base';
			}

			$setting=$this->model->getSetting($type);

			if ($type=='base') {
				WSKM::using('wskm_version');
				$timezone_option=html_select_option(wskm_version::$timezone,$setting['timeZone']);
				assign_var('timezone_option',$timezone_option);
			}elseif ($type=='img'){
				$positions=array();
				for ($i=1;$i<10;$i++){
					$positions[$i]=lang('position_'.$i);
				}
				assign_var('imgposition_option',html_select_option($positions,$setting['waterMarkPosition']) );
			}

			if ($type=='base' || $type=='reg') {
				if ($type=='base') {
					$path=ART_ROOT.'languages'.DS;
					$dirhandle=dir($path);
					$dirs=array();
					while (($dirtemp=$dirhandle->read()) !== false) {
						$dirpath=realpath($path.$dirtemp);
						if ( preg_match('/^[a-z0-9_\-]+$/i',$dirtemp ) && is_dir($dirpath) ) {
							$dirs[$dirtemp]=$dirtemp;
						}
					}
					assign_var('weblangs',$dirs);
				}
				assign_var('usergroup_option',html_select_option($this->model->getUserGroups(),$setting[$type=='base'?'groupId':'regGroupId']));
			}

			if ($type=='attach') {
				$setting['attachMaxSize']=round((int)$setting['attachMaxSize']/1048576);
			}

			assign_var('type',$type);
			assign_var('setting',$setting);

			adminTemplate('setting');
		}
		else if(checkToken()){
			$setting=requestPost('setting',TYPE_ARRAY);
			$settype=requestGet('type',TYPE_ALNUM);

			if (!is_array($setting)) {
				exit('err');
			}

			if ($settype=='base') {
				$setting['webUrl']=str_replace('http://','',rtrim($setting['webUrl'],'/'));
				$setting['webBaseUrl']=rtrim($setting['webBaseUrl'],'/');
				$setting['timeFormats']=rtrim(str_replace(array("\r"),'',$setting['timeFormats']),"\n");
			}elseif ($settype=='reg'){
				$setting['unameProtect']=rtrim(str_replace(array("\r",' '),'',$setting['unameProtect']),"\n");
				$setting['emailProtect']=rtrim(str_replace(array("\r",' '),'',$setting['emailProtect']),"\n");
			}elseif ($settype=='attach') {
				$setting['attachMaxSize']=(int)$setting['attachMaxSize']*1048576;
			}elseif ($settype=='style'){
				$setting['popBgColor']='#'.$setting['popBgColor'];
			}

			$this->model->readAll();
			$isupdateall=false;

			$res=$this->model->updateSettings($setting);
			$isupdateall=$this->model->isUpdateAll;
			if ($isupdateall) {
				usingArtClass('cache');
				art_cache::updateAll();

			}
			elseif ($res !== false) {
				$this->model->updateCache();
				adminMessage('edit_basesetting_successed',1);
			}
			adminMessage('edit_basesetting_failure',1);
		}

	}

	function doNav()
	{
		$this->model=usingAdminModel('nav');
		if (checkToken()) {
			$dels=requestPost('del',TYPE_ARRAY);
			if (count($dels) >0 ) {
				foreach ($dels as $id){
					if (!$this->model->del($id)) {
						adminMessage('del_bad','index.php?wskm=setting&act=nav');
					}
				}

				$this->model->updateCache();
				adminMessage('del_ok','index.php?wskm=setting&act=nav');
			}
		}

		$hand=requestGet('hand');
		if ($hand) {
			$nid=(int)requestGet('id');
			if ($nid <1 && $hand != 'add' && !IS_POST) {
				adminMessage('request_error','index.php');
			}
			if ($hand=='edit') {
				if (checkToken()) {
					$nav=array(
					'name'=>requestPost('name',TYPE_STRING,50),
					'target'=>requestPost('target',TYPE_INT),
					'displaysort'=>requestPost('displaysort',TYPE_INT),
					'color'=>'#'.requestPost('color',TYPE_STRING,20),
					'url'=>requestPost('url',TYPE_STRING,255),
					'status'=>requestPost('status',TYPE_INT),
					);
					if (empty($nav['name'])) {
						adminMessage('name_notempty',-1);
					}
					if ($nav['color']=='#FFFFFF') {
						$nav['color']='';
					}
					$nid=requestPost('nid',TYPE_INT);
					if (!$this->model->updateNav($nav,$nid)) {
						adminMessage('nav_edit_bad','index.php?wskm=setting&act=nav&hand=edit&id='.$nid);
					}
					$this->model->updateCache();
					adminMessage('nav_edit_ok','index.php?wskm=setting&act=nav&hand=edit&id='.$nid);

				}else{

					assign_var('info',$this->model->getAdminInfo($nid));
					assign_var('nid',$nid);
				}
			}elseif ($hand=='add'  && checkToken() ){
				$nav=array(
				'name'=>requestPost('name',TYPE_STRING,50),
				'target'=>requestPost('target',TYPE_INT),
				'color'=>'#'.requestPost('color',TYPE_STRING,20),
				'displaysort'=>requestPost('displaysort',TYPE_INT),
				'url'=>requestPost('url',TYPE_STRING,255),
				'status'=>requestPost('status',TYPE_INT),
				);
				if (empty($nav['name'])) {
					adminMessage('name_notempty',-1);
				}
				if ($nav['color']=='#FFFFFF') {
					$nav['color']='';
				}
				if (!$this->model->insertNav($nav)) {
					adminMessage('nav_add_bad','index.php?wskm=setting&act=nav');
				}
				$this->model->updateCache();
				adminMessage('nav_add_ok','index.php?wskm=setting&act=nav');

			}
			elseif ($hand=='del'){
				if (!$this->model->del($nid)) {
					adminMessage('del_bad','index.php?wskm=setting&act=nav');
				}
				$this->model->updateCache();
				adminMessage('del_ok','index.php?wskm=setting&act=nav');
			}

			if ($hand=='add') {
				assign_var('info',array('status'=>1));
			}
			assign_var('hand',$hand);
			adminTemplate('nav_info');
		}else{

			assign_var('navs',$this->model->getAdminList());
			adminTemplate('nav');
		}

	}

	function doFriendLink(){
		$this->model=usingAdminModel('friendlink');

		if (checkToken()) {
			$dels=requestPost('del',TYPE_ARRAY);
			if (count($dels) >0 ) {
				foreach ($dels as $id){
					if (!$this->model->del($id)) {
						adminMessage('del_bad','index.php?wskm=setting&act=friendlink');
					}
				}

				$this->model->updateCache();
				adminMessage('del_ok','index.php?wskm=setting&act=friendlink');
			}
		}

		$hand=requestGet('hand');
		if ($hand) {
			$fid=(int)requestGet('id');
			if ($fid <1 && $hand != 'add' && !IS_POST) {
				adminMessage('request_error','index.php');
			}
			if ($hand=='edit') {
				if (checkToken()) {
					$flink=array(
					'name'=>requestPost('name',TYPE_STRING,50),
					'displaysort'=>requestPost('displaysort',TYPE_INT),
					'url'=> str_replace('http://','', requestPost('url',TYPE_STRING,255)),
					'logo'=>requestPost('logo',TYPE_STRING,255),
					);
					if (empty($flink['name'])) {
						adminMessage('name_notempty',-1);
					}
					if ($_FILES['uploadlogo']['name'][0]) {

						WSKM::using('wskm_fileupload');
						$attch=uploadEasy('uploadlogo',ART_UPLOAD_PATH.'logo'.DIRECTORY_SEPARATOR,'',1);
						$attch=$attch[0];
						if ($attch['err']) {
							adminMessage($attch['err'],-1);
						}
						if ($attch['path']) {
							$flink['logo']=$attch['path'];
						}

					}

					$fid=requestPost('fid',TYPE_INT);
					if (!$this->model->update($flink,$fid)) {
						adminMessage('flink_edit_bad','index.php?wskm=setting&act=friendlink&hand=edit&id='.$fid);
					}
					$this->model->updateCache();
					adminMessage('flink_edit_ok','index.php?wskm=setting&act=friendlink&hand=edit&id='.$fid);

				}else{
					assign_var('info',$this->model->getInfo($fid));
					assign_var('fid',$fid);
				}
			}elseif ($hand=='add'  && checkToken() ){
				$flink=array(
				'name'=>requestPost('name',TYPE_STRING,50),
				'displaysort'=>requestPost('displaysort',TYPE_INT),
				'url'=> str_replace('http://','',requestPost('url',TYPE_STRING,255)),
				'logo'=>requestPost('logo',TYPE_STRING,255),
				);
				if (empty($flink['name'])) {
					adminMessage('name_notempty',-1);
				}


				if ($_FILES) {
					WSKM::using('wskm_fileupload');
					$attch=uploadEasy('uploadlogo',ART_UPLOAD_PATH.'logo'.DIRECTORY_SEPARATOR,'',1);
					$attch=$attch[0];
					if ($attch['err']) {
						adminMessage($attch['err'],-1);
					}
					if ($attch['path']) {
						$flink['logo']=$attch['path'];
					}
				}
				if (!$this->model->insert($flink)) {
					adminMessage('flink_add_bad','index.php?wskm=setting&act=friendlink');
				}
				$this->model->updateCache();
				adminMessage('flink_add_ok','index.php?wskm=setting&act=friendlink');

			}
			elseif ($hand=='del'){
				if (!$this->model->del($fid)) {
					adminMessage('del_bad','index.php?wskm=setting&act=friendlink');
				}
				$this->model->updateCache();
				adminMessage('del_ok','index.php?wskm=setting&act=friendlink');
			}

			assign_var('hand',$hand);
			adminTemplate('friendlink_info');
		}else{
			assign_var('flinks',$this->model->getList());
			adminTemplate('friendlink');
		}
	}

	function doAnnounce(){
		$this->model=usingAdminModel('setting');
		if (checkToken()) {
			$selects=requestPost('selects',TYPE_ARRAY);
			if (count($selects)  > 0) {
				if ($this->model->deleteAnnounce(wkImplode($selects,',')) == false) {
					adminMessage('del_error',-1);
				}
				art_cache::update('static');
				adminMessage('del_ok','index.php?wskm=setting&act=announce');
			}

		}
		assign_var('list',$this->model->getAnnounces());
		adminTemplate('announce');
	}

	function doAnnounceHandle(){

		if (checkToken()) {
			$id=requestPost('id',TYPE_INT);
			$isadd=$id > 0 ? false:true;
			$title=requestPost('title',TYPE_STRING,80);
			$displaysort=requestPost('displaysort',TYPE_INT);
			$message=requestPost('message',TYPE_HTMLTEXT);

			if (!$title || !$message) {
				adminMessage('input_notice',-1);
			}

			$this->model=usingAdminModel('setting');
			if ($isadd) {
				if($this->model->insertAnnounce(array('title'=>$title,'message'=>$message,'displaysort'=>$displaysort,'author'=>$this->getUname(),'dateline'=>WSKM_TIME) ) ==false){
					adminMessage('insert_error',-1);
				}
				art_cache::update('static');
				adminMessage('insert_ok','index.php?wskm=setting&act=announce');
			}else{
				if($this->model->updateAnnounce(array('title'=>$title,'message'=>$message,'displaysort'=>$displaysort,'author'=>$this->getUname()) , $id) ==false){
					adminMessage('add_err',-1);
				}
				art_cache::update('static');
				adminMessage('edit_ok',getUrlReferer());
			}
			exit();
		}
		$id=requestGet('id',TYPE_INT);
		$this->model=usingAdminModel('setting');
		$info=array('message'=>'','displaysort'=>0);
		if ($id > 0) {
			$info=$this->model->getAnnounce($id);
		}

		usingArtFun('article');
		assign_var('id',$id);
		assign_var('info',$info);
		assign_var('editor',getHtmlEditor('message',$info['message']));
		adminTemplate('announce_info');
	}

	function doAd(){
		$this->model=usingAdminModel('setting');
		if (checkToken()) {
			$selects=requestPost('selects',TYPE_ARRAY);
			if (count($selects)  > 0) {
				if ($this->model->deleteAd(wkImplode($selects,',')) == false) {
					adminMessage('del_error',-1);
				}

				$this->model->updateCacheAd();
				adminMessage('del_ok','index.php?wskm=setting&act=ad');
			}

		}
		assign_var('list',$this->model->getAds());
		adminTemplate('ad');
	}

	function doAdType(){
		$model=usingAdminModel('setting');
		if (checkToken()) {
			//del
			$list=requestPost('list',TYPE_ARRAY);
			$dels=requestPost('del',TYPE_ARRAY);
			if($dels && !$model->deleteAdType(wkImplode($dels,','))){
				adminMessage('del_error',-1);
			}

			//edit
			$names=array();
			foreach ($list as $wid=>$tempi){
				if (in_array($wid,$dels)) {
					continue;
				}

				$name=trim($tempi['name']);
				if (!$name) {
					adminMessage('empty_err',-1);
				}
				$names[]=$name;
				if(!$model->updateAdType(array('name'=>$name),(int)$wid)){
					adminMessage('edit_error',-1);
				}
			}

			$newnames=requestPost('newnames',TYPE_ARRAY);
			foreach ($newnames as $index=>$name){
				if (in_array($name,$names)) {
					continue;
				}
				$name=trim($name);
				if (!$name) {
					continue;
				}

				if ($name) {
					if(!$model->insertAdType(array('name'=>$name))){
						adminMessage('insert_error',-1);
					}
				}
			}
			adminMessage('edit_ok',getUrlReferer());
		}

		assign_var('list',$model->getAdTypes());
		adminTemplate('adtype');
	}

	function doAdHandle(){
		if (checkToken()) {
			$id=requestPost('id',TYPE_INT);
			$isadd=$id > 0 ? false:true;
			$title=requestPost('title',TYPE_STRING,80);
			$displaysort=requestPost('displaysort',TYPE_INT);
			$begintime = requestPost('begintime') ? strtotime(requestPost('begintime')) : 0;
			$endtime = requestPost('endtime') ? strtotime(requestPost('endtime')) : 0;
			$addnew=requestPost('addnew',TYPE_ARRAY);
			$typeid=requestPost('typeid',TYPE_INT);
			$status=requestPost('status',TYPE_INT);

			if (!$title) {
				adminMessage('input_notice',-1);
			}elseif ($typeid < 1) {
				adminMessage('ad_typeidnotice',-1);
			}elseif($endtime && ($endtime <= WSKM_TIME || $endtime <= $begintime)) {
				adminMessage('ad_endtimenotice',-1);
			} elseif(($addnew['style'] == 'code' && !$addnew['code']['html']) || ($addnew['style'] == 'text' && (!$addnew['text']['title'] || !$addnew['text']['link'])) || ($addnew['style'] == 'image' && ( !$addnew['image']['link']) ) || ($addnew['style'] == 'flash' && (!$addnew['flash']['width'] || !$addnew['flash']['height']))) {
				adminMessage('ad_argsnotice',-1);
			}

			foreach($addnew[$addnew['style']] as $key => $val) {
				$addnew[$addnew['style']][$key] = stripslashes($val);
			}

			$addnew['displaysort'] = isset($addnew['displaysort']) ? implode("\t", $addnew['displaysort']) : '';

			if ($addnew['style'] == 'image' || $addnew['style'] == 'flash') {
				$varname=$addnew['style'] == 'image'?'imagefile':'flashfile';
				usingArtFun('filesystem');
				WSKM::using('wskm_fileupload');
				$iswater=(int)WSKM::getConfig('isWaterMark');
				$watertype=null;
				if ($iswater) {
					$watertype=(int)WSKM::getConfig('waterMarkType');
				}

				$imageimpath=ART_UPLOAD_PATH;
				$waterposition=(int)WSKM::getConfig('waterMarkPosition');
				$attachs=uploadAttachment($varname,$imageimpath,0,$watertype,null,array('pos'=>$waterposition,'alpha'=>50,'jpg'=>70));
				$attach=current($attachs);
				if (isset($attach['err'])) {
					adminMessage($attach['err'],-1);
				}

				if ($attach) {
					if ($varname=='imagefile') {
						$addnew['image']['url']=ART_URL_FULL.'attachments/'.htmlspecialchars($attach['path']);
					}else{
						$addnew['flash']['url']=ART_URL_FULL.'attachments/'.htmlspecialchars($attach['path']);
					}
				}

			}
			
			if(($addnew['style'] == 'image' &&  !$addnew['image']['url'] ) || ($addnew['style'] == 'flash' && !$addnew['flash']['url'] )) {
				adminMessage('ad_argsnotice',-1);
			}

			switch($addnew['style']) {
				case 'code':
					$addnew['code'] = $addnew['code']['html'];
					break;
				case 'text':
					$addnew['code'] = '<a href="'.$addnew['text']['link'].'" target="_blank" '.($addnew['text']['size'] ? 'style="font-size:'.$addnew['text']['size'].($addnew['text']['color'] ? ';color:'.$addnew['text']['color'] : '').'"' : '').' >'.$addnew['text']['title'].'</a>';
					break;
				case 'image':
					$addnew['code'] = '<a href="'.$addnew['image']['link'].'" target="_blank"><img src="'.$addnew['image']['url'].'"'.($addnew['image']['height'] ? ' height="'.$addnew['image']['height'].'"' : '').($addnew['image']['width'] ? ' width="'.$addnew['image']['width'].'"' : '').($addnew['image']['alt'] ? ' alt="'.$addnew['image']['alt'].'"' : '').' border="0"></a>';
					break;
				case 'flash':
					$addnew['code'] = '<embed width="'.$addnew['flash']['width'].'" height="'.$addnew['flash']['height'].'" src="'.$addnew['flash']['url'].'" type="application/x-shockwave-flash" wmode="transparent"></embed>';
					break;
			}

			$addnew['code'] = addslashes($addnew['code']);
			$addnew['args'] = addslashes(serialize(array_merge(array('style' => $addnew['style']), $addnew['style'] == 'code' ? array() : $addnew[$addnew['style']] )));

			$this->model=usingAdminModel('setting');
			if ($isadd) {
				if($this->model->insertAd(array('title'=>$title,'typeid'=>$typeid,'displaysort'=>$displaysort,'status'=>$status,'args'=>$addnew['args'],'code'=>$addnew['code'],'begintime'=>$begintime,'endtime'=>$endtime) ) ==false){
					adminMessage('insert_error',-1);
				}
				$this->model->updateCacheAd();
				adminMessage('insert_ok','index.php?wskm=setting&act=ad');
			}else{
				if($this->model->updateAd(array('title'=>$title,'typeid'=>$typeid,'displaysort'=>$displaysort,'status'=>$status,'args'=>$addnew['args'],'code'=>$addnew['code'],'begintime'=>$begintime,'endtime'=>$endtime) , $id) ==false){
					adminMessage('add_err',-1);
				}
				$this->model->updateCacheAd();
				adminMessage('edit_ok',getUrlReferer());
			}
		}
		$id=requestGet('id',TYPE_INT);
		$this->model=usingAdminModel('setting');
		$info=array('begintime'=>WSKM_TIME,'displaysort'=>0,'status'=>1,'typeid'=>1,'args'=>array('style'=>'code'));
		if ($id > 0) {
			$info=$this->model->getAd($id);
		}

		usingArtFun('article');
		assign_var('adtype',$this->model->getAdTypes());
		assign_var('id',$id);
		assign_var('info',$info);
		adminTemplate('ad_info');
	}

}

?>