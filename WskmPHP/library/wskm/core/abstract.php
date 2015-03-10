<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [WskmPHP] www.wskmphp.com 
 * $Id: abstract.php 173 2010-10-24 11:22:44Z ws99 $ 
 */

!defined('IN_WSKM') && exit('Access Denied');

class wskm_core_abstract extends wskm_core_base
{
	public $db=null;

	public function sql()
	{
		$this->db=WSKM::SQL();
	}

	public function __construct(){
		$this->sql();
	}


}

interface wskm_core_icache{
	public function getKey($txt);
	public function get($key);
	public function set($key,$value,$expire=0);
	public function add($key,$value,$expire=0);
	public function remove($key);
	public function clear();
	
}

class wskm_model_abstract extends wskm_core_abstract
{

}



?>