<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [WskmPHP] www.wskmphp.com 
 * $Id: io.php 193 2010-11-14 13:28:18Z ws99 $ 
 */

!defined('IN_WSKM') && exit('Access Denied');

class wskm_io
{
	static function createEmptyIndex($todir)
	{
		wskm_io::dMake($todir);
		wskm_io::fMake($todir.DS.'index.htm');
	}

	static function fRead($file,$mod='rb') {
		if(!@$fp = @fopen($file, $mod)) {
			return false;
		} else {
			flock($fp, LOCK_SH);
			$data = fread($fp,filesize($file));
			flock($fp, LOCK_UN);
			fclose($fp);
			return $data;
		}
	}

	static function fWrite($file, $content='', $mod = 'wb') {
		if (!file_exists($file)) {
			self::fMake($file);
		}
		if(!@$fp = @fopen($file, $mod)) {
			return false;
		} else {
			flock($fp, LOCK_EX);
			fwrite($fp, $content);
			flock($fp, LOCK_UN);
			fclose($fp);
			return true;
		}
	}

	static function fDelete($folder) {
		if(!is_file($folder) || !file_exists($folder)) {
			return false;
		}
		return @unlink($folder);
	}

	static function fMake($file) {
		if (file_exists($file)) {
			return true;
		}

		if(!@touch($file))
		{
			return  self::dMake(dirname($file))?@touch($file):false;
		}
		return $isc;
	}

	static function dMake($dir, $mode = 0777)
	{
		if (!is_dir($dir)) {
			self::dMake(dirname($dir), $mode);
			return mkdir($dir, $mode);
		}
		return true;
	}

	static function dDelete($dir,$isclear=false)
	{
		if (!is_dir($dir)) {
			return false;
		}

		$dir = realpath($dir);
		if ($dir == '' || $dir == '/' ||
		(strlen($dir) == 3 && substr($dir, 1) == ':\\'))
		{
			return false;
		}

		if(false !== ($dh = opendir($dir))) {
			while(false !== ($file = readdir($dh))) {
				if($file == '.' || $file == '..') { continue; }
				$path = $dir .DS. $file;
				if (is_dir($path)) {
					if (!self::dDelete($path,$isclear)) { return false; }
				} else {
					unlink($path);
				}
			}
			closedir($dh);

			if (!$isclear) {
				rmdir($dir);
			}
			return true;
		} else {
			return false;
		}
	}

	static function dSize($dirName)
	{
		return disk_total_space($dirName);
	}

	static function dFree($dirName)
	{
		return disk_free_space($dirName);
	}

	static function dPath($dir)
	{
		$dir=pathSame($dir);
		$dir=trim($dir,DS).DS;
		return $dir;
	}

	static function dUseRatio($dirName)
	{
		return number_format(disk_free_space($dirName)/disk_total_space($dirName)*100,2).'%';
	}

	static function dList($path)
	{
		$dirhandle=dir($path);
		$dirs=array();
		while (($dirtemp=$dirhandle->read()) !== false) {
			$dirpath=realpath($path.$dirtemp);
			if (($dirtemp != '.' && $dirtemp != '..') && is_dir($dirpath)) {
				$dirs[]=$dirpath;
			}
		}

		return $dirs;
	}

	static function dListRegex($path,$regex='')
	{
		$files = glob($path.'*');

		$list=array();
		foreach($files as $file)
		{
			$fileext = fExt($file);
			if(is_dir($file) || empty($regex) ||  (is_file($file) && preg_match("/($regex)/i", $file)) )
			{
				$list[] = $file;
			}
		}
		return $list;
	}

}


?>