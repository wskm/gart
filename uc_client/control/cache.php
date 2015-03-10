<?php

/*
	[UCenter] (C)2001-2009 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: cache.php 16 2010-07-11 14:06:18Z ws99 $
*/

!defined('IN_UC') && exit('Access Denied');

class cachecontrol extends base {

	function __construct() {
		$this->cachecontrol();
	}

	function cachecontrol() {
		parent::__construct();
	}

	function onupdate($arr) {
		$this->load("cache");
		$_ENV['cache']->updatedata();
	}

}

?>