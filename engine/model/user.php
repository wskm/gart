<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [Gart] www.wskmphp.com 
 * $Id: user.php 280 2010-12-26 06:05:21Z ws99 $ 
 */

!defined('IN_ART') && exit('Access Denied');

class model_user extends wskm_model_abstract
{
	function editProfile($args,$uid){
		return $this->db->update(TABLE_PREFIX.'users',$args,"  uid='$uid' ") !== false;
	}

}

?>