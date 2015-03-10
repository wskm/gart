<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [Gart] www.wskmphp.com 
 * $Id: fun_common.php 250 2010-11-28 17:57:29Z ws99 $ 
 */

!defined('IN_ART') && exit('Access Denied');

function getThemeInc($key)
{
	return require(ADMIN_ROOT.'themes'.DS.ADMIN_STYLENAME.DS.'config'.DS.'inc_'.$key.'.php');
}

function usingAdminClass($key)
{	
	$className="admin_{$key}";
	$path=ADMIN_INC_DIR."class_{$key}.php";
	return WSKM::usingBase($className,$path);
}

function usingAdminInc($name)
{
	return require_once(ADMIN_INC_DIR.'inc_'. $name);
}

function usingAdminFun($name)
{
	return require_once(ADMIN_INC_DIR.'fun_'.$name);
}

function adminMessage($msg,$url='')
{
	showMessage($msg,$url);
}

function template_adminpath($basename,$styleid='',$stylename='')
{
	$basename=strtolower($basename);		
	$styleid=is_int($styleid) ? $styleid:'';
	if(($basename=='header' || $basename=='footer') && defined('IN_AJAX')){
		$basename .=IN_AJAX?'_ajax':'';
	}
	$sid=$styleid==''?ADMIN_STYLEID:$styleid;
	$sname=$stylename==''?ADMIN_STYLENAME:$stylename;
	$targetfile = ADMIN_CACHE_DIR.'tpl'.DS.$sid.'_'.$basename.'_tpl.php';	
	//$targetfile = CACHE_DIR.'tpl'.DS.$sid.'_'.$basename.'_tpl.php';
	$tplfile=ADMIN_TPL_DIR.$sname.DS.$basename.'_html.php';	

	if($styleid != 1 && !file_exists($tplfile)) {
		$tplfile = ADMIN_TPL_DIR.'default'.DS.$basename.'_html.php';
	}
	
	return array('tpl'=>$tplfile,'to'=>$targetfile);
}

function adminTemplate($name,$styleid='',$stylename=''){
	template($name,$styleid,$stylename);
}

function articleStatus($status)
{
	$str='';
	switch ($status)
	{
		case 2:
			$str=lang('verify');
			break;
		case 1:
			$str=lang('normal');
			break;
		default:
			$str=lang('close');
			break;
	}
	return $str;
}

function coverHtml($item,$key,$name,$classname=''){
	$inputhtml='';
	$id="{$name}{$key}";
	switch ($item['type']){
		case 'textarea':
			$inputhtml="<label for=\"$id\" ><b>{$item['name']}</b></label>&nbsp;<textarea  class=\"$classname\" name=\"{$item[$name]}[{$key}]\" id=\"{$id}\" >{$item['value']}</textarea>";
			break;
		case 'select':
			$data=array_split("\n",$item['value']);
			$inputhtml.="<label for=\"$id\" ><b>{$item['name']}</b></label>&nbsp;<select name=\"{$item['name']}[{$key}]\" class=\"$classname\" id=\"{$item[$name]}{$key}\">";
			$inputhtml.='<option value=""></option>';
			foreach ($data as $key=>$option){
				$inputhtml.="<option value=\"$option\" >$option</option>";
			}
			$inputhtml.="</select>";
			break;
		case 'checkbox':
			$data=array_split("\n",$item['value']);
			$ioption=0;
			$inputhtml="<label for=\"$id\" ><b>{$item['name']}</b></label>&nbsp;";
			foreach ($data as $key=>$option){
				$pid="{$item['name']}{$key}_{$ioption}";
				$inputhtml .="<input type=\"checkbox\" name=\"{$item[$name]}[{$key}]\" id=\"{$pid}\" class=\"$classname\" ><label for=\"$pid\" >{$option}</label>";
				$ioption++;
			}
			break;
		case 'radio':
			$ioption=0;
			$inputhtml="<label for=\"$id\" ><b>{$item['name']}</b></label>&nbsp;";
			foreach ($data as $key=>$option){
				$pid="{$item['name']}{$key}_{$ioption}";
				$inputhtml .="<input type=\"radio\" name=\"{$item[$name]}[{$key}]\" id=\"{$pid}\"  class=\"$classname\" ><label for=\"$pid\" >{$option}</label>";
				$ioption++;
			}
			break;
		case 'text':
		default:
			$inputhtml="<label for=\"$id\" ><b>{$item['name']}</b></label>&nbsp;<input type=\"text\" class=\"$classname\" name=\"{$item[$name]}[{$key}]\" id=\"$id\" value=\"{$item['value']}\" />";
			break;
	}

	return $inputhtml;
}

function toThumbPath($path)
{
	if (strpos($path,'attachments') === false) {
		$path='attachments'.DS.$path;
	}
	return toUrlSeparator(dirname($path)).'/thumb/9'.basename($path);
}
?>