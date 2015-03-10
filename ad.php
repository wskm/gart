<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [Gart] www.wskmphp.com 
 * $Id: wskm.php 235 2010-11-23 07:56:11Z ws99 $ 
 */

define('IN_ART',true);
define('OBGZIP',false);
require('./includes/inc_plugin.php');

$id=(int)$_GET['id'];
if ($id<1) {
	exit();
}

$temp='';
$ads=readCacheSystem('ad');
if (isset($ads[$id]) && ($ads[$id]['endtime'] ==0 || $ads[$id]['endtime'] >= WSKM_TIME)) {
	$temp=$ads[$id]['code'];
	if ($temp && !strExists($temp,'</div>')) {
		$temp='<div class="mt5 mb5" >'.$temp.'</div>';
	}
	echo jsSFormat($temp);
}

?>