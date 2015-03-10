<?php

/*
	[UCenter] (C)2001-2009 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: friend.php 16 2010-07-11 14:06:18Z ws99 $
*/

!defined('IN_UC') && exit('Access Denied');

class friendcontrol extends base {

	function __construct() {
		$this->friendcontrol();
	}

	function friendcontrol() {
		parent::__construct();
		$this->init_input();
		$this->load('friend');
	}

	function ondelete() {
		$uid = intval($this->input('uid'));
		$friendids = $this->input('friendids');
		$id = $_ENV['friend']->delete($uid, $friendids);
		return $id;
	}

	function onadd() {
		$uid = intval($this->input('uid'));
		$friendid = $this->input('friendid');
		$comment = $this->input('comment');
		$id = $_ENV['friend']->add($uid, $friendid, $comment);
		return $id;
	}

	function ontotalnum() {
		$uid = intval($this->input('uid'));
		$direction = intval($this->input('direction'));
		$totalnum = $_ENV['friend']->get_totalnum_by_uid($uid, $direction);
		return $totalnum;
	}

	function onls() {
		$uid = intval($this->input('uid'));
		$page = intval($this->input('page'));
		$pagesize = intval($this->input('pagesize'));
		$totalnum = intval($this->input('totalnum'));
		$direction = intval($this->input('direction'));
		$pagesize = $pagesize ? $pagesize : UC_PPP;
		$totalnum = $totalnum ? $totalnum : $_ENV['friend']->get_totalnum_by_uid($uid);
		$data = $_ENV['friend']->get_list($uid, $page, $pagesize, $totalnum, $direction);
		return $data;
	}
}

?>