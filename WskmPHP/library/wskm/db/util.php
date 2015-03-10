<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [WskmPHP] www.wskmphp.com 
 * $Id: util.php 16 2010-07-11 14:06:18Z ws99 $ 
 */

!defined('IN_WSKM') && exit('Access Denied');

class wskm_db_util{

	static function showTables($tablepre='',$databse=''){
		$db=WSKM::SQL();
		if ($databse) {
			$db->exec("USE $databse ");
		}

		$sql='';
		if ($tablepre) {
			$sql="SHOW TABLES LIKE '$tablepre%'";
		}else{
			$sql="SHOW TABLES";
		}
		$query=$db->query($sql);
		$list=array();
		while ($tempi=$db->fetch($query)) {
			if (is_array($tempi)) {
				foreach ($tempi as $tempii){
					$list[]=$tempii;
				}
			}
		}

		return $list;
	}

	static function showTableStatus($tablepre='',$databse='',$isfree=0){
		$db=WSKM::SQL();
		if ($databse) {
			$db->exec("USE $databse ");
		}

		$sql='';
		if ($tablepre) {
			$sql="SHOW TABLE STATUS LIKE '$tablepre%'";
		}else{
			$sql="SHOW TABLE STATUS";
		}

		if (!$isfree) {
			return $db->fetch_all($sql);
		}

		$list=array();
		$query=$db->query($sql);
		while ($tempi=$db->fetch($query)) {
			if ((int)$tempi['Data_free'] > 0) {
				$list[]=$tempi;
			}
		}

		return $list;
	}

	static function optimizeTable($table){
		$db=WSKM::SQL();
		return $db->exec("OPTIMIZE TABLE $table ");
	}

	static function dbSize($tablepre){
		$db=WSKM::SQL();
		$query = $db->query("SHOW TABLE STATUS LIKE '$tablepre%'", 'SILENT');
		$dbsize=0;
		while($table = $db->fetch($query)) {
			$dbsize += $table['Data_length'] + $table['Index_length'] + $table['Data_free'];
		}
		return $dbsize;
	}

}

?>