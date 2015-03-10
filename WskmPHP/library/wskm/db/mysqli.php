<?php !defined('IN_WSKM') && exit('Access Denied');
/*
*	WskmPHP Framework
*
*	Copyright (c) 2009 WSKM Inc.
*
*
*/


class wskm_db_mysqli extends wskm_db_base implements wskm_core_isql
{
	public function transaction()
	{
		return mysqli_autocommit($this->link,false);
	}

	public function commit()
	{
		return mysqli_commit($this->link);
	}

	public function rollback()
	{
		return mysqli_rollback($this->link);
	}

	function addDbServer($config)
	{		
		if(!$this->link = mysqli_connect($config['dbHost'], $config['dbUser'], $config['dbPassword'],$config['dbName'],$config['dbPort'])) {
			$this->halt('Can not connect to MySQL server:'.mysqli_connect_error());
		}

		$this->tablePre=$config['tablePre'];
		$this->dbType=$config['dbType'];
		
		$config['dbCharset']=str_replace('-','',$config['dbCharset']);
		if($this->version() > '4.1') {
			if($config['dbCharset']) {
				mysqli_query($this->link,"SET character_set_connection=".$config['dbCharset'].", character_set_results=".$config['dbCharset'].", character_set_client=binary");
			}

			if($this->version() > '5.0.1') {
				mysqli_query($this->link,"SET sql_mode=''");
			}
		}

	}

	function select_db($dbNmae)
	{
		return mysqli_select_db($this->link,$dbNmae);
	}

	function query($sql, $type = QUERY_EXCEPTION) {
		if(!($query = mysqli_query($this->link,$sql)) && $type != QUERY_NONE) {
			$this->halt('Query error', $sql);
		}

		$this->querynum++;
		$this->histories[] = $sql;
		return $query;
	}

	function exec($sql)
	{
		//return mysqli_query($this->link,$sql) !== false;
		return mysqli_real_query($this->link,$sql) !== false;
	}

	function affected_rows($link='') {
		return mysqli_affected_rows($this->link);
	}

	function fetch($query, $result_type = 'ASSOC') {
		return mysqli_fetch_array($query, $result_type=='ASSOC'?MYSQLI_ASSOC: MYSQLI_NUM);
	}

	function fetch_first($sql) {		
		return mysqli_fetch_assoc($this->query($sql));
	}

	function fetch_all($sql) {		
		$arr = array();
		$query = $this->query($sql);
		while($data = mysqli_fetch_assoc($query) ) {
			$arr[] = $data;
		}
		return $arr;
	}

	function escape($char) {
		return mysqli_real_escape_string($char);
	}

	function num_rows($query) {
		return mysqli_num_rows($query);
	}


	function insert_id() {
		return ($id = mysqli_insert_id($this->link)) >= 0 ? $id : $this->fetch_column("SELECT last_insert_id()");
	}

	function close() {
		return  mysqli_close($this->link);
	}

	function version() {
		return mysqli_get_server_info($this->link);
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
		$s .= '<br /><b>Error:</b>'.mysqli_error($this->link);
		$s .= '<br /><b>Errno:</b>'.mysqli_errno($this->link);
		
		$s .='<br><a href="http://www.wskms.com/help.php">Search Help</a></p>';
		echo $s;
		exit();

	}

	public function column_count($query)
	{
		return count(mysqli_fetch_lengths($query));
	}

	public function fetch_column($query,$index=0)
	{		
		$res= mysqli_fetch_row($this->query($query));
		return $res[$index];
	}

	public function exception_level($iserr){		
		$this->exceptionLevel=$iserr?QUERY_EXCEPTION:QUERY_NONE;
	}
}

?>