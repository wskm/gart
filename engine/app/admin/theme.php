<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [Gart] www.wskmphp.com 
 * $Id: theme.php 17 2010-08-26 11:37:04Z ws99 $ 
 */

!defined('IN_ART') && exit('Access Denied');

class app_admin_theme extends admin_common
{
	function load()
	{
		loadLang('admin_theme');
		$this->model=usingAdminModel('theme');
	}

	function doIndex()
	{
		if (checkToken()) {

			$defaultsid = requestPost('defaultsid',TYPE_INT);
			$defaultadminsid = requestPost('defaultadminsid',TYPE_INT);
			$objsetting=null;
			if ($defaultsid >0) {
				$objsetting=usingAdminModel('setting');
				if($objsetting->updateItem('styleId',$defaultsid)==false){
					adminMessage('edit_error',-1);
				}
			}

			if ($defaultadminsid >0) {
				if (!is_object($objsetting)) {
					$objsetting=usingAdminModel('setting');
				}

				if($objsetting->updateItem('adminStyleId',$defaultadminsid)==false){
					adminMessage('edit_error',-1);
				}
			}

			if (is_object($objsetting)) {
				$objsetting->readAll();
				$objsetting->updateCache();
			}

			adminMessage('edit_ok','index.php?wskm=theme');
		}else{
			assign_var('themes',$this->model->getThemes(false));
			assign_var('adminthemes',$this->model->getThemes(true));
			adminTemplate('theme');
		}
	}

	function doEdit(){
		if (checkToken()) {
			$styleid=requestPost('styleid',TYPE_INT);
			$title=requestPost('title',TYPE_STRING);
			$color=requestPost('color',TYPE_STRING);
			
			if ($this->model->update(array('title'=>$title,'color'=>'#'.$color),$styleid) ==false) {
				adminMessage('edit_error',-1);
			}
			$this->model->updateCache();
			adminMessage('edit_ok','index.php?wskm=theme');
		}else{
			$styleid=requestGet('styleid',TYPE_INT);
			if ($styleid<1) {
				adminMessage('request_error');
			}

			assign_var('styleid',$styleid);
			assign_var('info',$this->model->getInfo($styleid));
			adminTemplate('theme_info');
		}
	}

	function doInstall(){
		$stylename=requestGet('style',TYPE_ALNUM);
		if (!$stylename) {
			adminMessage('valid_alphanum',-1);
		}

		$styletype=(int)requestGet('styletype');

		if($this->model->install($stylename,$styletype)){
			adminMessage('install_ok','index.php?wskm=theme');
		}

		adminMessage('install_bad',-1);
	}

	function doUninstall()
	{
		$id=(int)requestGet('styleid');
		if ($id>0 && $this->model->uninstall($id) ) {
			adminMessage('uninstall_ok','index.php?wskm=theme');
		}

		adminMessage('uninstall_bad',-1);
	}


}

?>