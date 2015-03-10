<?php !defined('IN_WSKM') && exit('Access Denied');

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [WskmPHP] www.wskmphp.com 
 * $Id: sql.php 173 2010-10-24 11:22:44Z ws99 $ 
 */

class wskm_cache_sql implements wskm_core_icache {

	private $tnameGrade=0;
	private $tableName='caches';
	private $isSetTName=false;
	private $cacheExpires=0;
	private $cacheKey='';
	
	public $db=null;	

	public function __construct(){
		$this->db=WSKM::SQL();
		$this->db->exception_level(0);
		$this->setTNameGrade(WSKM::getConfig('cachePlusTableNameGrade'));
		$this->cacheExpires=(int)WSKM::getConfig('cachePageTime');
	}

	public function setTNameGrade($length){
		$this->tnameGrade=(int)$length;
	}

	public function setBaseTName($name){
		$this->tableName=TABLE_PREFIX.$name;
	}

	function initTName($key){
		if (!$this->isSetTName) {			
			$this->tableName .= $this->tnameGrade >0?'_'.substr($key,0,$this->tnameGrade):'';
			$this->isSetTName=true;
		}
	}

	public function createTable(){
		
		$type = $this->db->version() > '4.1' ? " ENGINE=MYISAM ".(DB_CHARSET == 'DB_CHARSET' ?'':' DEFAULT CHARSET='. str_replace('-','', DB_CHARSET) ): " TYPE=MYISAM";
		$sql = "CREATE TABLE IF NOT EXISTS `{$this->tableName}` (
						keyid char(16) NOT NULL default '',
						`value` mediumtext NOT NULL,
						`expire` int(10) unsigned NOT NULL default '0',
						PRIMARY KEY  (keyid)
					) $type";
		return $this->db->exec($sql) !== false;
	}

	public function getKey($txt){
		return substr(md5($txt), 8, 16);
	}
	
	public function get($key){
		$this->cacheKey=$key;
		$this->initTName($key);
		
		$query=$this->db->query('SELECT keyid as `key`,value,expire FROM '.$this->tableName." WHERE keyid='$key' ");
		$data=@$this->db->fetch($query);
		if ($data === false) {
			if($this->createTable()==false){
				$this->db->halt('No permission to create tables');
			}
			return false;
		}
		if ($data['expire'] < WSKM_TIME ) {
			$this->db->delete($this->tableName,' keyid=\''.$key.'\' ');
			return false;
		}
		
		return unserialize($data['value']);
	}

	public function set($key,$value,$expire=0){				
		$this->cacheKey=$key;
		$this->initTName($key);
		$value=serialize($value);
		$life=empty($expire) ? (WSKM_TIME+$this->cacheExpires):(WSKM_TIME+$expire);
		
		return $this->db->exec('REPLACE INTO '.$this->tableName.' (keyid,value,expire)VALUES(\''.$key.'\',\''.$value.'\',\''.$life.'\')') !== false;
	}

	public function add($key,$value,$expire=0){
		return $this->set($key,$value);
	}

	public function remove($key){
		$key=$key==''?$this->cacheKey:$key;
		if (empty($key)) {
			throw new wskm_exception('error:remove:'.$key);
		}
		$this->initTName($key);
		return $this->db->delete($this->tableName,' keyid=\''.$key.'\' ') !== false;
	}

	public function clear(){
		if (!$this->isSetTName) {
			throw new wskm_exception('error:isSetTName');
		}
		return $this->db->exec(' TRUNCATE TABLE '.$this->tableName) !== false;
	}


}



?>