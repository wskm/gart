<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [Gart] www.wskmphp.com 
 * $Id: tool.php 103 2010-10-02 14:07:39Z ws99 $ 
 */

!defined('IN_ART') && exit('Access Denied');

class app_admin_tool extends admin_common
{
	function load(){
		loadLang('admin_tool');
	}
	
	function doCacheHtml(){
		$index=mvcUrl('',array('index','index'),ART_URL_FULL,WSKM::getConfig('urlMode'),false);
		$index .= (strExists($index,'?')?'&updatehtml=1':'?updatehtml=1');
		$curl=mvcUrl('',array('category','list',array('id'=>'thetplcid')),ART_URL_FULL,WSKM::getConfig('urlMode'),false);
		$curl .= (strExists($curl,'?')?'&updatehtml=1':'?updatehtml=1');
		$url=mvcUrl('',array('news','show',array('id'=>'thetplid')),ART_URL_FULL,WSKM::getConfig('urlMode'),false);
		$url .= (strExists($url,'?')?'&updatehtml=1':'?updatehtml=1');
		
		assign_var('indext',$index);
		assign_var('catet',$curl);
		assign_var('newst',$url);
		adminTemplate('updatecachehtml');
	}

	function doAjax_HtmlClear(){
		usingArtClass('cache');
		art_cache::deleteHtml(0,true);
		art_cache::update('category');
		art_cache::update('static');
	}

	function doUpdateCache(){

		adminTemplate('updatecache');
	}

	function doAjax_Updatecache(){
		$isupdatetpl=requestGet('istpl',TYPE_BOOL);
		$isupdatedata=requestGet('isdata',TYPE_BOOL);
		$types=array();

		if ($isupdatedata) {
			$types[]='sys';
			$types[]='static';
		}
		
		if ($isupdatetpl) {
			$types[]='tpl';
		}
		
		if ($types) {
			usingArtClass('cache');
			foreach ($types as $type){
				art_cache::updateAll($type);
			}
		}

		echo 1;
	}

	function doDb(){
		$hand=requestGet('hand');
		$hand=$hand?$hand:'backup';
		$ishell=function_exists('shell_exec');
		$basedir=ART_CACHE_PATH.'backup'.DS;
		usingArtClass('cache');

		if ($hand=='backup') {
			if (checkToken()) {
				$fname=requestPost('filename',TYPE_ALNUM);

				if (empty($fname) || !str_replace(array('-','_'),'',$fname)) {
					$fname=date('YmdHs',WSKM_TIME).'_'.WSKM::random(9);
				}

				$backuptype=requestPost('backuptype',TYPE_ALNUM);
				$iszip=requestPost('iszip',TYPE_BOOL);
				$isextend=requestPost('isextend',TYPE_BOOL);
				$isall=requestPost('isall',TYPE_BOOL);
				$backuptable=requestPost('backuptable',TYPE_ARRAY);

				$tablelist='';
				if (!$isall && $backuptable) {
					$tablelist=$backuptable;
				}

				$todir=ART_CACHE_PATH.'backup'.DS.'db_'.date('Ym',WSKM_TIME).DS;
				if (!is_dir($todir)) {
					wskm_io::dMake($todir);
					wskm_io::fMake($todir.'index.html');
				}
				$sqlfname=$todir.$fname.'.sql';
				if ($backuptype=='mysqldump') {
					WSKM::using('wskm_db_backup');
					wskm_db_backup::export_mysqldump($sqlfname,$tablelist);
				}

				if ($iszip && file_exists($sqlfname)) {
					WSKM::using('wskm_zip');
					wskm_zip::zip($sqlfname,$todir.$fname.'.zip');
					wskm_io::fDelete($sqlfname);
				}
				adminMessage('backup_ok',getUrlReferer());

			}
			assign_var('filename',date('YmdH',WSKM_TIME).'_'.WSKM::random(6));
		}elseif ($hand=='import'){
			if (checkToken()) {
				$importfile=$_FILES['importfile'];
				if (fExt($importfile['name'])=='sql' && $importfile['type']=='application/octet-stream') {
					WSKM::using('wskm_db_backup');
					wskm_db_backup::import_mysqldump($importfile['tmp_name']);
					wskm_io::fDelete($importfile['tmp_name']);
					art_cache::updateAll();
					adminMessage('backup_importok','index.php?wskm=tool&act=db&hand=import');
				}

			}
			$allbkfile=array();
			if($handle = opendir($basedir)){
				while(($filesys = readdir($handle)) !== false) {
					if ( (substr($filesys,0,3)=='db_') && is_dir($basedir.$filesys)) {

						if ($fhandle = opendir($basedir.$filesys)) {
							while(($bkfile = readdir($fhandle)) !== false) {
								$filelist=array();
								$isneed=false;

								$filelist['dirname']=$filesys;																	if (fExt($bkfile) =='sql') {
									$isneed=true;
									$filelist['type']='sql';
								}elseif(fExt($bkfile)=='zip'){
									$isneed=true;
									$filelist['type']='zip';
								}
								if ($isneed) {
									$filelist['filename']=$bkfile;
									$filelist['path']=($filesys.'/'.$bkfile);
									$filelist['size']=filesize($basedir.$filesys.DS.$bkfile);
									$filelist['mtime']=filemtime($basedir.$filesys.DS.$bkfile);
									$allbkfile[]=$filelist;
								}
							}
						}

					}
				}

				assign_var('bkfiles',$allbkfile);
			}
		}elseif ($hand=='delbk'){
			$file=str_replace('/',DS,requestGet('path',TYPE_STRING));

			if (!file_exists($basedir.$file)) {
				adminMessage('backup_notexist',-1);
			}
			if (wskm_io::fDelete($basedir.$file)) {
				adminMessage('backup_delok','index.php?wskm=tool&act=db&hand=import');
			}else{
				adminMessage('backup_delerr','index.php?wskm=tool&act=db&hand=import');
			}

		}elseif ($hand=='unzip'){
			$file=str_replace('/',DS,requestGet('path',TYPE_STRING));

			if (!file_exists($basedir.$file) && fExt($basedir.$file)=='zip') {
				adminMessage('backup_notexist',-1);
			}
			WSKM::using('wskm_zip');
			if (!wskm_zip::unzip($basedir.$file)) {
				adminMessage('backup_unziperr',-1);
			}
			adminMessage('backup_unzipok','index.php?wskm=tool&act=db&hand=import');
		}elseif ($hand=='zip'){
			$file=str_replace('/',DS,requestGet('path',TYPE_STRING));

			if (!file_exists($basedir.$file) && fExt($basedir.$file)=='sql') {
				adminMessage('backup_notexist',-1);
			}
			WSKM::using('wskm_zip');
			if (!wskm_zip::zip($basedir.$file,$basedir.str_replace('.sql','.zip',$file))) {
				adminMessage('backup_ziperr',-1);
			}
			adminMessage('backup_zipok','index.php?wskm=tool&act=db&hand=import');
		}elseif ($hand=='importfile'){
			$file=str_replace('/',DS,requestGet('path',TYPE_STRING));

			if (!file_exists($basedir.$file) && fExt($basedir.$file)=='sql') {
				adminMessage('backup_notexist',-1);
			}
			WSKM::using('wskm_db_backup');
			wskm_db_backup::import_mysqldump($basedir.$file);
			art_cache::updateAll();
			adminMessage('backup_importok','index.php?wskm=tool&act=db&hand=import');

		}elseif ($hand=='optimize'){
			WSKM::using('wskm_db_util');
			if (checkToken()) {
				$tables=requestPost('optselects',TYPE_ARRAY);
				foreach ($tables as $table){
					if(wskm_db_util::showTableStatus($table,'',1)){
						if(!wskm_db_util::optimizeTable($table)){
							adminMessage('table_optimizeerr',-1);
						}
					}
				}

				adminMessage('table_optimizeok','index.php?wskm=tool&act=db&hand=optimize');
			}

			$tableslist=wskm_db_util::showTableStatus('','',1);
			assign_var('frees',$tableslist);
		}elseif($hand=='batchdel'){
			$selectfiles=requestPost('selectfile',TYPE_ARRAY);
			if (count($selectfiles) > 0) {

				foreach ($selectfiles as $file){
					if (!file_exists($basedir.$file)) {
						adminMessage('backup_notexist',-1);
					}
					if (!wskm_io::fDelete($basedir.$file)) {
						adminMessage('backup_delerr','index.php?wskm=tool&act=db&hand=import');
					}
				}
				adminMessage('backup_delok','index.php?wskm=tool&act=db&hand=import');

			}
		}

		assign_var('isshell',$ishell);
		assign_var('hand',$hand);
		adminTemplate('db');
	}

	function doShowTables(){
		WSKM::using('wskm_db_util');
		WSKM::using('wskm_json');
		echo jsonEncode(wskm_db_util::showTables(TABLE_PREFIX));
	}

	function doLoginLog(){
		if (!$this->isManageAccess()) {
			adminMessage('access_not');	
		}
		$hand=requestGet('hand',TYPE_ALNUM);
		$this->model=usingAdminModel('log');
		$logid=requestGet('id',TYPE_INT);
		if (checkToken()) {
			$logs=requestPost('dellog',TYPE_ARRAY);
			foreach ($logs as $logid){
				if (!$this->model->delLoginLog($logid)) {
					adminMessage('log_delerr',-1);
				}
			}
			adminMessage('log_delok',getUrlReferer());
		}elseif ($hand=='del' && $logid > 0) {
			if (!$this->model->delLoginLog($logid)) {
				adminMessage('log_delerr',-1);
			}

			adminMessage('log_delok',getUrlReferer());
		}else{

			$data=$this->model->getLoginList();
			$keys=$data['keys'];

			$url="index.php?wskm=tool&act=loginlog";
			$url .='&'.http_build_query($keys);
			$htmlpage=multiPage($data['count'],$data['page'],$url,$this->model->adminPageCount);

			assign_var('skeys',$keys);
			assign_var('list',$data['list']);
			assign_var('htmlpage',$htmlpage);
			adminTemplate('loginlog');
		}
	}

}

?>