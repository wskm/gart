<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [WskmPHP] www.wskmphp.com 
 * $Id: fileupload.php 133 2010-10-13 15:34:30Z ws99 $ 
 */

!defined('IN_WSKM') && exit('Access Denied');

define('IMAGE_JPG','jpg');
define('IMAGE_PNG','png');
define('IMAGE_GIF','gif');

class wskm_fileupload {

	var $dir;
	var $thumb_width;
	var $thumb_height;
	var $thumb_ext;
	//var $watermark_file;
	var $watermark_type;

	var $watermark_pos;
	var $watermark_alpha;
	var $jpgquality;
	var $time;

	var $ismustimg=false;
	var $isself=false;

	var $ismkdir=true;
	var $filename;
	var $formval;

	var $filetypedata = array();
	var $filetypeids = array();		
	var $filetypes = array();		

	function setMustImage($is){
		$this->ismustimg=(bool)$is;
	}

	function wskm_fileupload($formval='') {
		$this->formval;
		$this->filetypedata = array(
		'av' => array('av', 'wmv', 'wav'),
		'real' => array('rm', 'rmvb'),
		'binary' => array('dat'),
		'flash' => array('swf'),
		'html' => array('html', 'htm'),
		'image' => array('gif', 'jpg', 'jpeg', 'png', 'bmp'),
		'office' => array('doc', 'xls', 'ppt'),
		'pdf' => array('pdf'),
		'rar' => array('rar', 'zip'),
		'text' => array('txt'),
		'bt' => array('torrent','bt'),
		'zip' => array('tar', 'rar', 'zip', 'gz'),
		);
		$this->filetypeids = array_keys($this->filetypedata);
		foreach($this->filetypedata as $data) {
			$this->filetypes = array_merge($this->filetypes, $data);
		}
	}

	function set_isself($isor){
		$this->isself=(bool)$isor;
	}

	function set_filename($name)
	{
		$this->filename=$name;
	}

	function set_ismkdir($isc=true)
	{
		$this->ismkdir=$isc;
	}

	function set_formval($val)
	{
		$this->formval=$val;
	}

	function set_dir($dir) {
		$this->dir = rtrim($dir,DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
	}

	function set_thumb($width, $height, $ext = '') {
		$this->thumb_width = $width;
		$this->thumb_height = $height;
		$this->thumb_ext = $ext;
	}

	function set_watermark($type=0, $pos = 9, $alpha = 50 ,$jpgquality=70) {
		$this->watermark_type = $type;
		$this->watermark_pos = $pos;
		$this->watermark_alpha = $alpha;
		$this->jpgquality=$jpgquality;
	}


	function mkdir_by_date() {
		$dir =date('Y_m').DIRECTORY_SEPARATOR;
		return $dir;
	}

	function isUpload($file) {
		return (is_uploaded_file($file) || is_uploaded_file(str_replace('\\\\', '\\', $file)));
	}

	function execute() {
		$this->time = time();
		static $imgext  = array('jpg', 'jpeg', 'gif', 'png', 'bmp');

		$arr = array();
		$keys = array_keys($_FILES[$this->formval]['name']);  //attach[]
		$timedir='';
		foreach($keys as $key) {
			if(empty($_FILES[$this->formval]['name'][$key]))continue;
			$attach = array(
			'origin_name' => $_FILES[$this->formval]['name'][$key],
			'name' => '',
			'tmp_name' => $_FILES[$this->formval]['tmp_name'][$key],
			'size' => $_FILES[$this->formval]['size'][$key],
			'type' => $_FILES[$this->formval]['type'][$key],
			'path'=>'',
			'isthumb'=>0,
			'isimage'=>0,
			'width'=>0
			);

			$attach['name']=$attach['origin_name'];
			$attach['type']=str_replace('image/pjpeg','image/jpeg',$attach['type']);
			if(!$this->isUpload($attach['tmp_name']) || !($attach['tmp_name'] != 'none' && $attach['tmp_name'] && (int)$attach['size'])) {
				@unlink($attach['tmp_name']);
				continue;
			}

			$attach['ext'] = strtolower($this->fileext($attach['name']));
			if(!in_array($attach['ext'], $this->filetypes)) {
				@unlink($attach['tmp_name']);
				$arr[$key]['err']=lang('attch_notype');
				continue;
			}

			if ($attach['size'] > (int)WSKM::getConfig('attachMaxSize')) {
				@unlink($attach['tmp_name']);
				$arr[$key]['err']=lang('attch_limitsize');
				continue;
			}

			$imageexists=0;
			if(in_array($attach['ext'], $imgext)) {
				$attach['isimage'] = 1;
				$imageexists = 1;
			}

			if ($this->ismustimg && !$attach['isimage']) {
				@unlink($attach['tmp_name']);
				$arr[$key]['err']=lang('attch_noimg');
				continue;
			}

			if ($this->ismkdir) {
				$tfilename = $this->time.substr(md5($attach['origin_name'].uniqid(rand(), true) ), 8, 16);
				$attach['name'] = $tfilename.'.'.$attach['ext'];
				$timedir=$this->mkdir_by_date();
				if (strlen($attach['origin_name']) >30) {
					$attach['origin_name']=$attach['name'];
				}

			}else{
				$attach['name']=$this->filename?$this->filename.'.'.$attach['ext']:$attach['origin_name'];
			}

			$attach['path']=$timedir.$attach['name'];
			wskm::using('wskm_io');
			
			if (!is_dir($this->dir.$timedir)) {
				wskm_io::dMake($this->dir.$timedir);
				wskm_io::fMake($this->dir.$timedir.'index.htm');
			}

			$targetfile=$this->dir.$attach['path'];
			$issave=$this->copy($attach['tmp_name'], $targetfile);

			$objectImg=null;
			if ($issave) {
				@chmod($targetfile, 0644);
				if($attach['isimage'] || $attach['ext'] == 'swf') {
					WSKM::using('wskm_gd_image');
					
					$objectImg=wskm_gd_image::getInstance($targetfile,$this->dir.$timedir);
					list($width, $height, $type) = $objectImg->attachinfo;

					$attach['width']=$width;
					$size = $width * $height;
					if($size > 16777216 || $size < 4 || empty($type) || ($attach['isimage'] && !in_array($type, array(1,2,3,6,13)))) {
						@unlink($targetfile);
						$arr[$key]['err']=lang('attch_noimg');
						continue;
					}
				}

				if(in_array($attach['ext'], array('jpg', 'gif', 'png'))) {
					if($attach['width'] > 200) {
						$objectImg->Watermark($this->watermark_type,$this->watermark_pos,$this->watermark_alpha,$this->jpgquality);
					}

					if($this->thumb_width > 50) {
						$temptargetfile='';
						if ($this->isself) {
							$temptargetfile=$targetfile;
						}else{
							$filethumb = '9'.$tfilename.'.'.($this->thumb_ext ? $this->thumb_ext : $attach['ext']);
							$dirtThumb=$this->dir.$timedir.'thumb'.DIRECTORY_SEPARATOR;
							if (!is_dir($dirtThumb)) {
								wskm_io::dMake($dirtThumb);
								wskm_io::fMake($dirtThumb.'index.htm');
							}
							$temptargetfile=$dirtThumb.$filethumb;
						}
						$objectImg->Thumb($this->thumb_width, $this->thumb_height,$temptargetfile);

						$attach['isthumb']=1;
					}
				}
				$attach['path']=str_replace(DS,'/',$attach['path']);
				$arr[$key]=$attach;
			}

		}

		return $arr;
	}


	function copy($sourcefile, $destfile) {
		if(@copy($sourcefile, $destfile) || (function_exists('move_uploaded_file') && @move_uploaded_file($sourcefile, $destfile))) {
			@unlink($sourcefile);
			return true;
		}
		return false;
	}

	function fileext($filename) {
		return substr(strrchr($filename, '.'), 1, 10);
	}

}


function uploadAttachment($formname,$imgbasedir,$isimage=0,$watermarktype=null,$thumbwh=array(),$waterarr=array('pos'=>9,'alpha'=>50,'jpg'=>70),$filename='')
{
	$fload=WSKM::loadClass('wskm_fileupload');
	$fload->set_formval($formname);
	$fload->set_dir($imgbasedir);

	if (!empty($thumbwh)) {
		$fload->set_thumb($thumbwh[0],$thumbwh[1]);  //w  h
		$fload->set_isself(true);
	}

	if (!is_null($watermarktype)) {
		if ( !empty($waterarr) ) {
			$fload->set_watermark($watermarktype,$waterarr['pos'],$waterarr['alpha'],$waterarr['jpg']);
		}else{
			$fload->set_watermark($watermarktype);
		}
	}

	if ($filename) {
		$fload->set_filename($filename);
	}

	$fload->setMustImage($isimage);
	return $fload->execute();
}

function uploadEasy($formname,$todir,$filename='',$isimg=0)
{
	$fload=WSKM::loadClass('wskm_fileupload');
	$fload->set_formval($formname);
	$fload->set_dir($todir);
	$fload->set_ismkdir(false);
	$fload->set_filename($filename);
	$fload->setMustImage($isimg);
	return $fload->execute();
}

?>