<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [Gart] www.wskmphp.com 
 * $Id: index.php 193 2010-11-14 13:28:18Z ws99 $ 
 */

!defined('IN_ART') && exit('Access Denied');

class app_index extends art_page
{
	function page_load()
	{
		$this->isHTML();
	}
	
	function doIndex()
	{
		$this->isHome();
		assign_var('flinktype',WSKM::getConfig('friendLinkType'));
		assign_var('friendlink',readCacheSystem('friendlink'));
		template('index');
	}
	
}

?>