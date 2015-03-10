<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [Gart] www.wskmphp.com 
 * $Id: image.php 159 2010-10-20 08:35:28Z ws99 $ 
 */

!defined('IN_ART') && exit('Access Denied');

class app_admin_image extends admin_common
{
	function load(){
		loadLang('admin_image');
	}

	function doImagethumb(){
		
		if (checkToken()) {
			$attachid=(int)requestPost('attachid');
			$isself=requestPost('isself',TYPE_BOOL);
			$width=(int)requestPost('width');
			$height=(int)requestPost('height');			

			if ($width < 50 || $height <50) {
				adminMessage('thumb_input_err',-1);
			}

			WSKM::using('wskm_gd_image');
			$originpath=requestPost('imagepath');

			$imagepath=pathSame(ART_ROOT.$originpath);
			$todir='';
			if (!$isself && $attachid >0) {
				$todir=dirname($imagepath).DS.'thumb';
				wskm::using('wskm_io');

				wskm_io::createEmptyIndex($todir);
				$topath=$todir.DS.'9'.basename($imagepath);

			}else{
				$topath=$imagepath;
				$todir=dirname($topath).DS;
			}

			$objimg=wskm_gd_image::getInstance($imagepath,$todir);
			$objimg->Thumb($width,$height,$topath);
			$path=ART_URL.$originpath;

			list($width)=getimagesize($topath);
			$dbpath=str_replace(DS,'/',str_replace(ART_UPLOAD_PATH,'',$topath));
			$where =$attachid >0? " id='$attachid' ":" filepath='$dbpath' ";

			if (!$isself && $attachid >0) {
				$attchinfo=array('isthumb'=>1);
			}
			else{
				$attchinfo=array('width'=>$width);
				$where=str_replace('thumb'.DS.'9','',$where);
			}

			$res=$this->db->update(TABLE_PREFIX.'attachments',$attchinfo,$where);
			if ($path && $res) {
				jsWriter("alert('".lang('thumb_ok')."');setTimeout(function(){window.close();},200);");
			}

		}
	}

	function doImagecrop()
	{
		if (checkToken()) {
			$attachid=(int)requestPost('attachid');
			$isself=requestPost('isself',TYPE_BOOL);
			$isthumb=(int)requestPost('isthumb');
			$originpath=requestPost('imagepath');
			$imagepath=pathSame(ART_ROOT.$originpath);
			if (!$isself && $isthumb >0 && $attachid >0) {
				$todir=dirname($imagepath).DS.'thumb';
				wskm::using('wskm_io');
				if (!is_dir($todir)) {
					wskm_io::dMake($todir);
					wskm_io::fMake($todir.DS.'index.htm');
				}
				$topath=$todir.DS.'9'.basename($imagepath);

			}else{
				$topath=$imagepath;
			}

			WSKM::using('wskm_gd_image');
			$objimg=wskm_gd_image::getInstance($imagepath,$todir);

			$isok=$objimg->crop($topath,requestPost('x',TYPE_INT),requestPost('y',TYPE_INT),requestPost('w',TYPE_INT),requestPost('h',TYPE_INT));
			$path=ART_URL.$originpath;

			list($width, $height) = $objimg->attachinfo;
			
			$dbpath=str_replace(DS,'/',str_replace(ART_UPLOAD_PATH,'',$topath));
			$where =$attachid >0? " id='$attachid' ":" filepath='$dbpath' ";

			if (!$isself && $isthumb >0 && $attachid >0) {
				$attchinfo=array('isthumb'=>1);
			}
			else{
				$attchinfo=array('width'=>$width);
				$where=str_replace('thumb'.DS.'9','',$where);
			}

			$res=$this->db->update(TABLE_PREFIX.'attachments',$attchinfo,$where);
			if ($isok && $path && $res) {
				jsWriter("alert('".lang('crop_ok')."');setTimeout(function(){window.close();},200);");
			}

		}
		else{
			$path=rawurldecode(requestGet('path'));
			assign_var('thumbwidth',WSKM::getConfig('imagethumbwidth'));
			assign_var('thumbheight',WSKM::getConfig('imagethumbheight'));

			$hdpath=ART_ROOT.$path;
			$imgurl=ART_URL.$path;

			if (file_exists($hdpath)) {

				WSKM::using('wskm_gd_image');
				$objimg=wskm_gd_image::getInstance($hdpath);
				if($objimg->attachinfo == false){
					echo lang('image_not');
					exit();
				}
				elseif( $objimg->animatedgif)
				{
					echo lang('crop_giferr');
					exit();
				}

				list($width, $height) = $objimg->attachinfo;
				if ($width <200) {
					echo lang('image_width_minerr');
					exit();
				}

				assign_var('isself',(int)requestGet('isself'));
				assign_var('isthumb',(int)requestGet('isthumb'));
				assign_var('attachid',(int)requestGet('attachid'));
				assign_var('imagepath',$imgurl);
				assign_var('originpath',$path);
				assign_var('width',$width);
				assign_var('height',$height);
				adminTemplate('image');
			}
			else {
				echo lang('file_notfound');
				exit();
			}

		}
	}




}


?>