<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [WskmPHP] www.wskmphp.com 
 * $Id: vcode.php 16 2010-07-11 14:06:18Z ws99 $ 
 */

!defined('IN_WSKM') && exit('Access Denied');

class wskm_vcode {
	public $width=0;
	public $height=0;
	public $code='';
	public $type=0;
	public $codelength=4;
	public $imagetype = 'jpeg';

	function display()
	{
		codeConvert($this->code,$this->type,$this->codelength);
		$paddingLeft =  5;
		$paddingRight =  5;
		$paddingTop =  3;
		$paddingBottom =  3;
		$fspace=3;

		$font = 5;
		$border = 1;
		$borderColor =  '0x000000';

		$fontWidth = imagefontwidth($font);
		$fontHeight = imagefontheight($font);

		$width = $fontWidth * $this->codelength+$this->codelength*$fspace + $paddingLeft + $paddingRight +
		$border * 2 ;
		$height = $fontHeight + $paddingTop + $paddingBottom + $border * 2 ;

		$width=max($this->width,$width);
		$height=max($this->height,$height);
		
		$img = imagecreate($width, $height);

		$textColor=array();
		for ($i=0;$i<$this->codelength;$i++)
		{
			$textColor[] =imagecolorallocate($img, rand(128, 255), rand(126, 255), rand(128, 255)); 
		}
		$bgcolor = imagecolorallocate ($img, rand(0, 128), rand(0, 128), rand(0, 128));

		if ($border) {
			list($r, $g, $b) = $this->_hex2rgb($borderColor);
			$borderColor = imagecolorallocate($img, $r, $g, $b);
			imagefilledrectangle($img, 0, 0, $width, $height, $borderColor);
		}

		imagefilledrectangle($img, $border, $border,
		$width - $border - 1, $height - $border - 1, $bgcolor);

		$this->setDisturbColor($img);

		for ($i=0;$i<$this->codelength;$i++)
		{
			imagestring($img, rand(4,5), $paddingLeft + $border +$i*$fontWidth+$i*$fspace+rand(0,3), $border+rand(0,$height-$border-$fontHeight),
			$this->code[$i], $textColor[$i]);
			
		}

		switch (strtolower($this->imagetype)) {
			case 'png':
				header("Content-type: " . image_type_to_mime_type(IMAGETYPE_PNG));
				imagepng($img);
				break;
			case 'gif':
				header("Content-type: " . image_type_to_mime_type(IMAGETYPE_GIF));
				imagegif($img);
				break;
			case 'jpg':
			default:
				header("Content-type: " . image_type_to_mime_type(IMAGETYPE_JPEG));
				imagejpeg($img);
		}

		imagedestroy($img);
		unset($img);

		return $code;
	}

	function setDisturbColor(&$img) {
		$disturColor=null;
		for ($i = 0; $i <= 128; $i++) {
			$disturColor = imagecolorallocate($img, rand(0, 255), rand(0, 255), rand(0, 255));
			imagesetpixel($img, rand(2, 128),rand(2, 38), $disturColor);
		}
		unset($disturColor);
	}

	function _hex2rgb($color, $defualt = 'ffffff')
	{
		$color = strtolower($color);
		if (substr($color, 0, 2) == '0x') {
			$color = substr($color, 2);
		} elseif (substr($color, 0, 1) == '#') {
			$color = substr($color, 1);
		}
		$l = strlen($color);
		if ($l == 3) {
			$r = hexdec(substr($color, 0, 1));
			$g = hexdec(substr($color, 1, 1));
			$b = hexdec(substr($color, 2, 1));
			return array($r, $g, $b);
		} elseif ($l != 6) {
			$color = $defualt;
		}

		$r = hexdec(substr($color, 0, 2));
		$g = hexdec(substr($color, 2, 2));
		$b = hexdec(substr($color, 4, 2));
		return array($r, $g, $b);
	}

}



?>