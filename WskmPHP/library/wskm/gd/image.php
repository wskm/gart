<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [WskmPHP] www.wskmphp.com 
 * $Id: image.php 133 2010-10-13 15:34:30Z ws99 $ 
 */

!defined('IN_WSKM') && exit('Access Denied');

class wskm_gd_image {
	private $srcfile = '';					
	public  $srcfext='';					
	private $targetdir = '';				
	private $thumbfile='';					
	private $imagecreatefromfunc = '';		
	private $imagefunc = '';	
	private $issetext=true;

	public $attach = array();             
	public $attachinfo = '';				
	public $animatedgif = 0;             
	public $error = 0;                   

	static $instance=null;
	static function getInstance($srcfile,$targetdir=''){
		if (!is_object(self::$instance)) {
			self::$instance=new wskm_gd_image($srcfile,$targetdir);
		}
		return self::$instance;
	}

	function __construct($srcfile, $targetdir, $attach = array()) {
		$this->srcfile = $srcfile;
		$this->targetdir = $targetdir;

		if ($targetdir && !is_dir($targetdir)) {
			wskm::using('wskm_io');
			wskm_io::dMake($targetdir);
		}

		$this->attach = $attach;
		$this->attachinfo = @getimagesize($srcfile);

		switch($this->attachinfo['mime']) {
			case 'image/jpeg':
				$this->imagecreatefromfunc = function_exists('imagecreatefromjpeg') ? 'imagecreatefromjpeg' : '';
				$this->imagefunc = function_exists('imagejpeg') ? 'imagejpeg' : '';
				$this->srcfext='jpg';
				break;
			case 'image/gif':
				$this->imagecreatefromfunc = function_exists('imagecreatefromgif') ? 'imagecreatefromgif' : '';
				$this->imagefunc = function_exists('imagegif') ? 'imagegif' : '';
				$this->srcfext='gif';
				break;
			case 'image/png':
				$this->imagecreatefromfunc = function_exists('imagecreatefrompng') ? 'imagecreatefrompng' : '';
				$this->imagefunc = function_exists('imagepng') ? 'imagepng' : '';
				$this->srcfext='png';
				break;
		}

		if($this->attachinfo['mime'] == 'image/gif') {
			if($this->imagecreatefromfunc && !@imagecreatefromgif($srcfile)) {
				$this->error = 1;
				$this->imagecreatefromfunc = $this->imagefunc = '';
				return FALSE;
			}
			$this->attach['size'] = empty($this->attach['size']) ? @filesize($srcfile) : $this->attach['size'];
			$fp = fopen($srcfile, 'rb');
			$targetfilecontent = fread($fp, $this->attach['size']);
			fclose($fp);
			
			$this->animatedgif = strpos($targetfilecontent, 'NETSCAPE2.0') === FALSE ? 0 : 1;
		}
	}

	static function isImage($t){
		return in_array($t,array('gif', 'jpg', 'jpeg', 'png', 'bmp'));
	}

	function isSetExt($or=false){
		$this->issetext=(bool)$or;
	}

	function Thumb($thumbwidth, $thumbheight,$filepath='',$thumbstatus=1,$thumbquality=70) {

		$filename=$thumbdir='';
		if ($filepath) {
			$filepath= $this->issetext ? str_replace( '.'.fExt($filepath) , '.'.$this->srcfext ,$filepath) : $filepath;
		}else{
			$filename=time().substr(md5($this->srcfile),0,16).'.'.$this->srcfext;
			$thumbdir=$this->targetdir.DIRECTORY_SEPARATOR.'thumb';
			if (!is_dir($thumbdir)) {
				wskm::using('wskm_io');
				wskm_io::dMake($thumbdir);
			}
		}

		$this->thumbfile=$filepath?$filepath:$thumbdir.DIRECTORY_SEPARATOR.'9'.$filename;
		$this->attach['thumb']=basename($this->thumbfile);

		$this->Thumb_GD($thumbwidth, $thumbheight, $thumbstatus,$thumbquality);
	}

	function Watermark($watermarktype=0,$watermarkstatus=9,$watermarktrans=50,$watermarkquality=50,$watermarktext=array()) {

		if ($this->animatedgif || ($watermarktype>1 && empty($watermarktext)) ) {
			return ;
		}
		$this->Watermark_GD($watermarktype,$watermarkstatus,$watermarktrans,$watermarkquality,$watermarktext);
	}

	function Thumb_GD($thumbwidth, $thumbheight, $thumbstatus=1,$thumbquality=70) {
		if($thumbstatus && function_exists('imagecreatetruecolor') && function_exists('imagecopyresampled') && function_exists('imagejpeg')) {
			$imagecreatefromfunc = $this->imagecreatefromfunc;
			//$imagefunc = $thumbstatus == 1 ? 'imagejpeg' : $this->imagefunc;
			$imagefunc =  $this->imagefunc;
			list($img_w, $img_h) = $this->attachinfo;

			if(!$this->animatedgif && ($img_w >= $thumbwidth || $img_h >= $thumbheight)) {

				$thumbfile = $this->thumbfile;
				if($thumbstatus != 3) {
					$attach_photo = $imagecreatefromfunc($this->srcfile);

					$x_ratio = $thumbwidth / $img_w;
					$y_ratio = $thumbheight / $img_h;

					if(($x_ratio * $img_h) < $thumbheight) {
						$thumb['height'] = ceil($x_ratio * $img_h);
						$thumb['width'] = $thumbwidth;
					} else {
						$thumb['width'] = ceil($y_ratio * $img_w);
						$thumb['height'] = $thumbheight;
					}


					$cx = $img_w;
					$cy = $img_h;
				} else {
					$attach_photo = $imagecreatefromfunc($this->srcfile);

					$imgratio = $img_w / $img_h;
					$thumbratio = $thumbwidth / $thumbheight;

					if($imgratio >= 1 && $imgratio >= $thumbratio || $imgratio < 1 && $imgratio > $thumbratio) {
						$cuty = $img_h;
						$cutx = $cuty * $thumbratio;
					} elseif($imgratio >= 1 && $imgratio <= $thumbratio || $imgratio < 1 && $imgratio < $thumbratio) {
						$cutx = $img_w;
						$cuty = $cutx / $thumbratio;
					}

					$dst_photo = imagecreatetruecolor($cutx, $cuty);
					imageCopyMerge($dst_photo, $attach_photo, 0, 0, 0, 0, $cutx, $cuty, 100);

					$thumb['width'] = $thumbwidth;
					$thumb['height'] = $thumbheight;

					$cx = $cutx;
					$cy = $cuty;
				}

				$thumb_photo = imagecreatetruecolor($thumb['width'], $thumb['height']);
				imageCopyreSampled($thumb_photo, $attach_photo ,0, 0, 0, 0, $thumb['width'], $thumb['height'], $cx, $cy);
				clearstatcache();
				if($this->attachinfo['mime'] == 'image/jpeg') {
					$imagefunc($thumb_photo, $thumbfile, $thumbquality);
				} else {
					$imagefunc($thumb_photo, $thumbfile);
				}
				$this->attach['isthumb'] = 1;
			}
		}
	}

	function Watermark_GD($watermarktype=1,$watermarkstatus=9,$watermarktrans=50,$watermarkquality=50,$watermarktext=array()) {

		if($watermarkstatus && function_exists('imagecopy') && function_exists('imagealphablending') && function_exists('imagecopymerge')) {
			$imagecreatefromfunc = $this->imagecreatefromfunc;
			$imagefunc = $this->imagefunc;
			list($img_w, $img_h) = $this->attachinfo;
			if($watermarktype < 2) {
				$watermark_file = $watermarktype == 1 ? WSKM_ROOT.'config/images/watermark.png' : WSKM_ROOT.'config/images/watermark.gif';
				$watermarkinfo	= @getimagesize($watermark_file);
				$watermark_logo	= $watermarktype == 1 ? @imageCreateFromPNG($watermark_file) : @imageCreateFromGIF($watermark_file);
				if(!$watermark_logo) {
					return;
				}
				list($logo_w, $logo_h) = $watermarkinfo;
			} else {
				$watermarktextcvt = pack("H*", $watermarktext['text']);
				$box = imagettfbbox($watermarktext['size'], $watermarktext['angle'], $watermarktext['fontpath'], $watermarktextcvt);
				$logo_h = max($box[1], $box[3]) - min($box[5], $box[7]);
				$logo_w = max($box[2], $box[4]) - min($box[0], $box[6]);
				$ax = min($box[0], $box[6]) * -1;
				$ay = min($box[5], $box[7]) * -1;
			}
			$wmwidth = $img_w - $logo_w;
			$wmheight = $img_h - $logo_h;

			if(($watermarktype < 2 && is_readable($watermark_file) || $watermarktype == 2) && $wmwidth > 10 && $wmheight > 10 && !$this->animatedgif) {
				switch($watermarkstatus) {
					case 1:
						$x = +5;
						$y = +5;
						break;
					case 2:
						$x = ($img_w - $logo_w) / 2;
						$y = +5;
						break;
					case 3:
						$x = $img_w - $logo_w - 5;
						$y = +5;
						break;
					case 4:
						$x = +5;
						$y = ($img_h - $logo_h) / 2;
						break;
					case 5:
						$x = ($img_w - $logo_w) / 2;
						$y = ($img_h - $logo_h) / 2;
						break;
					case 6:
						$x = $img_w - $logo_w;
						$y = ($img_h - $logo_h) / 2;
						break;
					case 7:
						$x = +5;
						$y = $img_h - $logo_h - 5;
						break;
					case 8:
						$x = ($img_w - $logo_w) / 2;
						$y = $img_h - $logo_h - 5;
						break;
					case 9:
						$x = $img_w - $logo_w - 5;
						$y = $img_h - $logo_h - 5;
						break;
				}

				$dst_photo = imagecreatetruecolor($img_w, $img_h);
				$target_photo = @$imagecreatefromfunc($this->srcfile);
				imageCopy($dst_photo, $target_photo, 0, 0, 0, 0, $img_w, $img_h);

				if($watermarktype == 1) {
					imageCopy($dst_photo, $watermark_logo, $x, $y, 0, 0, $logo_w, $logo_h);
				} elseif($watermarktype == 2) {
					if(($watermarktext['shadowx'] || $watermarktext['shadowy']) && $watermarktext['shadowcolor']) {
						$shadowcolorrgb = explode(',', $watermarktext['shadowcolor']);
						$shadowcolor = imagecolorallocate($dst_photo, $shadowcolorrgb[0], $shadowcolorrgb[1], $shadowcolorrgb[2]);
						imagettftext($dst_photo, $watermarktext['size'], $watermarktext['angle'], $x + $ax + $watermarktext['shadowx'], $y + $ay + $watermarktext['shadowy'], $shadowcolor, $watermarktext['fontpath'], $watermarktextcvt);
					}
					$colorrgb = explode(',', $watermarktext['color']);
					$color = imagecolorallocate($dst_photo, $colorrgb[0], $colorrgb[1], $colorrgb[2]);
					imagettftext($dst_photo, $watermarktext['size'], $watermarktext['angle'], $x + $ax, $y + $ay, $color, $watermarktext['fontpath'], $watermarktextcvt);
				} else {
					imageAlphaBlending($watermark_logo, true);
					imageCopyMerge($dst_photo, $watermark_logo, $x, $y, 0, 0, $logo_w, $logo_h, $watermarktrans);
				}

				$targetflie =   $this->srcfile ;
				clearstatcache();
				if($this->attachinfo['mime'] == 'image/jpeg') {
					$imagefunc($dst_photo, $targetflie, $watermarkquality);
				} else {
					$imagefunc($dst_photo, $targetflie);
				}

				$this->attach['iswatermark'] =1; // filesize($targetflie);
			}
		}
	}

	function crop($toimg,$x,$y,$w,$h)
	{
		if ($this->animatedgif) {
			return ;
		}
		$x=(int)$x;
		$y=(int)$y;
		$w=(int)$w;
		$h=(int)$h;

		if ($w < 2 || $h < 2 ) {
			return ;
		}

		$fileext=$this->srcfext;
		$pictypes=null;
		switch ($fileext){
			case 'gif':
				$pictypes = array('imagecreatefromgif','imagegif');
				break;
			case 'jpg':
			case 'jpeg':
				$pictypes = array('imagecreatefromjpeg','imagejpeg');
				break;
			case 'png':
				$pictypes =array('imagecreatefrompng','imagepng');
				break;
		}
		if (!is_array($pictypes)) {
			return false;
		}

		$img_r = $pictypes[0]($this->srcfile);
		$dst_r = ImageCreateTrueColor( $w, $h);
		
		imagecopyresampled($dst_r,$img_r,0,0,$x,$y,	$w,$h,$w,$h);
		@$pictypes[1]($dst_r,$toimg);

		clearstatcache();
		return true;
	}
}

?>