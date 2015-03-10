<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [Gart] www.wskmphp.com 
 * $Id: vcode.php 260 2010-11-29 11:06:27Z ws99 $ 
 */

define('IN_ART',true);
define('IS_MVC',false);
define('IS_NOCACHE',true);
define('OBGZIP',false);
require('./includes/inc_common.php');

if(!isMyReferer())exit('error');
$vcodeType=(int)WSKM::getConfig('vcodeType');

if ($vcodeType==0) {
	$user=WSKM::user();
	$user->vcode_new();
	
	WSKM::using('wskm_vcode');
	$code=new wskm_vcode();
	$code->code=$user->getVcode();
	
	$code->codelength=(int)WSKM::getConfig('vcodeLength');
	$code->display();	
}
exit();


?>