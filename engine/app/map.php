<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [Gart] www.wskmphp.com 
 * $Id: map.php 16 2010-07-11 14:06:18Z ws99 $ 
 */

!defined('IN_ART') && exit('Access Denied');

class app_map extends art_page
{

	function doIndex()
	{		
		assign_var('page_title',lang('sitemap').'_');
		assign_var('page_keywords',lang('sitemap').',');
		assign_var('page_description',lang('sitemap'));
		template('map');
	}
}

?>