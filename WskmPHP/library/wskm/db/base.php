<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [WskmPHP] www.wskmphp.com 
 * $Id: base.php 16 2010-07-11 14:06:18Z ws99 $ 
 */

!defined('IN_WSKM') && exit('Access Denied');

define('QUERY_NONE',0);
define('QUERY_EXCEPTION',1);
define('QUERY_NOCACHE',2);

abstract class wskm_db_base{

	protected $querynum = 0;
	protected $histories=array();
	protected $goneaway = 5;

	public  $link=null;
	
	protected $dbType='';
	protected $tablePre='';
	protected $fetch_mode='ASSOC';
	protected $exceptionLevel=QUERY_NONE;

	function getSqlHistory()
	{
		return $this->histories;
	}

	function mysql_insert_str($tablename,$argv,$isp=false)
	{

		if ($isp) {
			$keys = "`" . implode("`,`", $argv) . "`";
			$values = str_repeat(',?',count($argv)) ;
			$values=substr($values,1);
		}
		else
		{
			$keys = "`" . implode("`,`", array_keys($argv)) . "`";
			$values = "'" . implode("','", array_values($argv)) . "'";
		}

		$sql = "INSERT INTO $tablename ($keys) VALUES ($values)";
		return $sql;
	}

	function mysql_update_str($tablename,$argv,$where,$isp=false)
	{
		$updatestr=$wherestr='';

		if ($isp) {
			foreach ($argv as $v){
				$updatestr .= "`$v` =?,";
			}
		}
		else
		{
			foreach ($argv as $k => $v){
				$updatestr .= "`$k` ='$v',";
			}

		}
		$updatestr=rtrim($updatestr,',');

		if ($where) {
			$wherestr=' WHERE '.$where;
		}
		$sql="UPDATE $tablename SET $updatestr $wherestr ";
		return $sql;
	}

	function mysql_delete_str($field)
	{
		$values = "'" . implode("','", $field) . "'";
		return $values;
	}

	function insert($tablename, $array, $insert_id = false) {
		if (!is_array($array))return false;

		$fun=$this->dbType.'_insert_str';
		$sql=$this->$fun($tablename,$array);

		$isok = $this->exec($sql);
		if($insert_id) return $this->insert_id();
		return $isok;
	}

	function update($tablename, $arr, $where ) {
		if (!is_array($arr))
		return false;
		$fun=$this->dbType.'_update_str';
		$sql=$this->$fun($tablename,$arr,$where);

		return $this->exec($sql);
	}

	function delete($tablename, $where = '',$fieldarray='') {
		if(empty($where)) {
			$query=$this->exec('TRUNCATE TABLE '.$tablename);
		}elseif ($where && is_array($fieldarray)) {
			$fun=$this->dbType.'_delete_str';
			$field=$this->$fun($fieldarray);
			$query=$this->exec("DELETE FROM $tablename WHERE $where IN ({$field}) " );
		}else {
			$query=$this->exec('DELETE FROM '.$tablename.' WHERE '.$where);
		}

		return $query;
	}


}

interface wskm_core_isql{
	public function version();
	public function addDbServer($config);

	public function transaction();
	public function commit();
	public function rollback();

	public function query($sql, $type='' );
	public function exec($sql);

	public function fetch($sql,$result_type='');
	public function fetch_first($sql);
	public function fetch_all($sql);

	public function escape($char);
	public function select_db($dbNmae);
	public function affected_rows($query='');
	public function insert_id();
	public function num_rows($query);

	public function close();
	public function halt($message='', $sql='');

	public function column_count($res);
	public function fetch_column($res,$index=0);

	public function exception_level($level);

}

?>