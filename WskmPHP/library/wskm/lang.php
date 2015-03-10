<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [WskmPHP] www.wskmphp.com 
 * $Id: lang.php 263 2010-11-29 21:52:44Z ws99 $ 
 */

!defined('IN_WSKM') && exit('Access Denied');

class wskm_lang
{
	private static $outDir='';
	public static $languages=array();

	static function setOutDir($path)
	{
		self::$outDir=$path;
	}

	static function langPath($key,$language,$isin=true)
	{
		if (strpos($key,'_') !== false) {
			$key=str_replace('_', DS, $key);
		}

		if ($isin) {
			$file=WSKM_ROOT.'language'.DS.$language.DS.$key.'_lang.php';
		}
		else {
			$file = self::$outDir.$language.DS.$key.'_lang.php';
		}

		return $file;
	}

	static function loadPath($file,$language='zh',$isexit=true){
		$keylog=$file.$language;
		if (isset(self::$languages['keylog'][$keylog])) {
			return;
		}
		else
		{
			self::$languages['keylog'][$keylog]=1;
		}

		if (strpos($key,'_') !== false) {
			$key=str_replace('_', DS, $key);
		}

		$arr=array();
		if (file_exists($file)) {
			$arr=loadArray($file);
		}
		else if ($isexit) {
			throw new wskm_exception('NO Language: '.$file);
		}
		else {
			return false;
		}

		if (!is_array(self::$languages[$language])) {
			self::$languages[$language]=array();
		}

		self::$languages[$language]=array_merge(self::$languages[$language],$arr);
	}

	static function lang($key,$language='zh',$isin=true,$rkey=false,$isexit=true)
	{
		//!defined('LANGUAGE') && define('LANGUAGE','zh');
		$keylog=$key.$language.$isin.$rkey;
		if (isset(self::$languages['keylog'][$keylog])) {
			return;
		}
		else
		{
			self::$languages['keylog'][$keylog]=1;
		}

		if (strpos($key,'_') !== false) {
			$key=str_replace('_', DS, $key);
		}

		$file='';
		if ($isin) {
			$file=WSKM_ROOT.'language'.DS.$language.DS.$key.'_lang.php';
		}
		else
		{
			$file=trim(self::$outDir);
			if(empty($file)){
				if ($isexit) {
					throw new wskm_exception('This lang dir not exist: '.$key);
				}
				else {
					return false;
				}
			}

			$file .= DS.$language.DS.$key.'_lang.php';
		}

		$arr=array();
		if (file_exists($file)) {
			if($rkey){
				$arr= loadArray($file,$key);
			}else{
				$arr=loadArray($file);
			}
		}
		else if ($isexit) {
			throw new wskm_exception('NO Language: '.$file);
		}
		else {
			return false;
		}

		if (!isset(self::$languages[$language]) || !is_array(self::$languages[$language])) {
			self::$languages[$language]=array();
		}

		self::$languages[$language]=array_merge(self::$languages[$language],$arr);

	}

	static function get($var,$language='zh')
	{
		if (is_string($var)) {
			return self::$languages[$language][$var];
		}
		return '';
	}
}

?>