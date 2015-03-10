<?php !defined('IN_WSKM') && exit('Access Denied');

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [Gart] www.wskmphp.com 
 * $Id: class_mysql.php 67 2010-09-30 07:31:19Z ws99 $ 
 */

class wskm_db_mysql
{
	public  $link=null;
	protected $dbType='';
	protected $tablePre='';

	public function transaction()
	{
		return mysql_query('START TRANSACTION',$this->link);
	}

	public function commit()
	{
		return mysql_query('COMMIT',$this->link);
	}

	public function rollback()
	{
		return mysql_query('ROLLBACK ',$this->link);
	}

	function addDbServer($config)
	{
		$fun=$this->pconnect?'mysql_pconnect':'mysql_connect';
		if(!$this->link = $fun($config['dbHost'].':'.$config['dbPort'], $config['dbUser'], $config['dbPassword'])) {
			$this->halt('Can not connect to MySQL server');
		}
		$config['dbCharset']=str_replace('-','',$config['dbCharset']);
		if($this->version() > '4.1') {
			if($config['dbCharset']) {
				mysql_query("SET character_set_connection=".$config['dbCharset'].", character_set_results=".$config['dbCharset'].", character_set_client=binary", $this->link);
			}

			if($this->version() > '5.0.1') {
				mysql_query("SET sql_mode=''", $this->link);
			}
		}

		if($config['dbName']) {
			mysql_select_db($config['dbName'], $this->link);
		}
	}

	function select_db($dbNmae)
	{
		return mysql_select_db($dbNmae,$this->link);
	}

	function query($sql, $type = QUERY_EXCEPTION) {
		$func = $type == QUERY_NOCACHE && @function_exists('mysql_unbuffered_query') ? 'mysql_unbuffered_query' : 'mysql_query';
		if(!($query = $func($sql, $this->link)) && $type != QUERY_NONE) {
			$this->halt('Query error', $sql);
		}

		$this->querynum++;
		$this->histories[] = $sql;
		return $query;
	}

	function exec($sql)
	{
		return mysql_query($sql,$this->link) !== false;
	}

	function affected_rows($link='') {
		return mysql_affected_rows($this->link);
	}

	function fetch($query, $result_type = 'ASSOC') {
		return mysql_fetch_array($query, $result_type=='ASSOC'?MYSQL_ASSOC:MYSQL_NUM);
	}

	function fetch_first($sql) {
		return mysql_fetch_array($this->query($sql),MYSQL_ASSOC);
	}

	function fetch_all($sql) {
		$arr = array();
		$query = $this->query($sql);
		while($data = mysql_fetch_array($query,MYSQL_ASSOC) ) {
			$arr[] = $data;
		}
		return $arr;
	}

	function escape($char) {
		return mysql_escape_string($char);
	}

	function num_rows($query) {
		return mysql_num_rows($query);
	}


	function insert_id() {
		return ($id = mysql_insert_id($this->link)) >= 0 ? $id : $this->fetch_column("SELECT last_insert_id()");
	}

	function close() {
		return mysql_close($this->link);
	}

	function version() {
		return mysql_get_server_info($this->link);
	}

	function halt($message = '', $sql = '') {
		if ($this->exceptionLevel == QUERY_NONE) {
			return false;
		}
		if (function_exists('log_message')) {
			log_message($message . ' - ' . $sql, 'Sql');
		}

		$s ='<p style="font-family: Verdana, Tahoma; font-size: 12px">';
		if($sql) $s .="<br /><b>Sql</b>:".htmlspecialchars($sql);
		$s .='<br /><b>Message</b>:'.$message;
		$s .= '<br /><b>Error:</b>'.mysql_error();
		$s .= '<br /><b>Errno:</b>'.mysql_errno();

		$s .='<br><a href="http://www.wskmphp.com/help.php">Search Help</a></p>';
		echo $s;
		exit();

	}

	public function column_count($query)
	{
		return mysql_num_fields($query);
	}

	public function fetch_column($query,$index=0)
	{
		if (is_resource($query)) {
			return mysql_result($query,0,$index);
		}

		return mysql_result($this->query($query),0,$index);
	}

	public function exception_level($iserr){
		$this->exceptionLevel=$iserr?QUERY_EXCEPTION:QUERY_NONE;
	}
}

?>