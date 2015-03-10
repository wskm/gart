<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [WskmPHP] www.wskmphp.com 
 * $Id: wk_js.php 16 2010-07-11 14:06:18Z ws99 $ 
 */

!defined('IN_WSKM') && exit('Access Denied');

function gotoUrlTime($url, $delay = 0,$js = true)
{
	if (!$js) {
		if (headers_sent() || $delay > 0) {
			echo <<<EOT
    <html>
    <head>
    <meta http-equiv="refresh" content="{$delay};URL={$url}" />
    </head>
    </html>
EOT;
			exit;
}
}
$out = '';
if ($delay > 0) {
	$out .= "window.setTimeout(function () { document.location='{$url}'; }, {$delay});";
} else {
	$out .= "document.location='{$url}';";
}

jsWriter($out);
exit;
}


function popAlert($msg,$type=0)
{
	if ($type) {
		self::jsWriter(sprintf("window.alert('%s');return false;",(toJs($msg))));
		return  false;
	}
	return  sprintf("window.alert('%s')",(toJs($msg)));
}

function popConfirm($msg)
{
	return sprintf("window.confirm('%s')",(toJs($msg)));
}

function popPrompt($msg)
{
	return sprintf("window.prompt('%s')",(toJs($msg)));
}

function selfClose()
{
	return 'window.close();';
}

function parentClose()
{
	return 'if(window.parent){window.parent.close();}';
}

function selfRefresh()
{
	return " window.location +='';";
}

function parentRefresh()
{
	return "if(window.parent){ window.parent.location +='';}";
}

function selfGoBack()
{
	return " window.history.back();";
}

function parentGoBack()
{
	return "if(window.parent){ window.parent.history.back();}";
}

function openWindow($url, $frameName='', $status=0,$location=0, $menubar=0,$resizable=0, $height=200, $width=300, $top=0, $left=0, $scrollbars=0, $toolbar=0)
{
	return sprintf(" window.open('%s', '%s', 'status=%d,location=%d,menubar=%d,resizable=%d,height=%dpx,width=%dpx,top=%d,left=%d,scrollbars=%d,toolbar=%d'); ",
	toJs($url), toJs($frameName), ($status ? 1 : 0), ($location ? 1 : 0), ($menubar ? 1 : 0), ($resizable ? 1 : 0), $height, $width, $top, $left, ($scrollbars ? 1 : 0), ($toolbar ? 1 : 0));
}

function showModalDialog($url, $status=0, $resizable=1, $height=250, $width=300, $center=1, $scroll=0)
{
	return sprintf(" window.showModalDialog('%s', window, 'status=%d,resizable=%d,dialogHeight=%dpx,dialogWidth=%dpx,center=%d,scroll=%d,unadorne=yes'); ",
	toJs($url), ($status ? 1 : 0), ($resizable ? 1 : 0), $height, $width, ($center ? 1 : 0), ($scroll ? 1 : 0));
}

function showModelessDialog($url, $status=0, $resizable=1, $height=250, $width=300, $center=1, $scroll=0)
{
	return sprintf(" window.showModelessDialog('%s', window, 'status=%d,resizable=%d,dialogHeight=%dpx,dialogWidth=%dpx,center=%d,scroll=%d,unadorne=yes'); ",
	toJs($url), ($status ? 1 : 0), ($resizable ? 1 : 0), $height, $width, ($center ? 1 : 0), ($scroll ? 1 : 0));
}




?>