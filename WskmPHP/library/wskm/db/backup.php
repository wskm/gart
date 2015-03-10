<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [WskmPHP] www.wskmphp.com 
 * $Id: backup.php 139 2010-10-16 16:14:43Z ws99 $ 
 */

!defined('IN_WSKM') && exit('Access Denied');

class wskm_db_backup{

	static function export_mysqldump($tofile,$tablelist='',$args=''){
		if(!$tofile)return ;

		$db=WSKM::SQL();
		$version=$db->version();


		$tablestr='';
		if (is_array($tablelist)) {
			foreach($tablelist as $table) {
				$tablestr .= '"'.$table.'" ';
			}
		}

		$basedir=$db->fetch_first("SHOW VARIABLES LIKE 'basedir'");
		$basedir=$basedir['Value'];
		$mysqlbin = $basedir == DS ? '' : addslashes($basedir).'bin'.DS;

		$dbhost=WSKM::getConfig('dbHost');
		$dbport=WSKM::getConfig('dbPort');
		$dbuser=WSKM::getConfig('dbUser');
		$dbpw=WSKM::getConfig('dbPassword');
		$dbname=WSKM::getConfig('dbName');

		$compatible='';
		$extendi=0;
		if (is_array($args)) {
			if (isset($args['extinsert'])) {
				$extendi=$args['extinsert'];
			}
			if (isset($args['compatible'])) {
				$compatible=$args['compatible'];
			}
		}

		$db->exec('SET SQL_QUOTE_SHOW_CREATE=0');
		@set_time_limit(0);
		shell_exec($mysqlbin.'mysqldump --force --quick '.($version > '4.1' ? '--skip-opt --create-options' : '-all').' --add-drop-table'.($extendi == 1 ? ' --extended-insert' : '').''.($version > '4.1' && $compatible == 'MYSQL40' ? ' --compatible=mysql40' : '').' --host="'.$dbhost.'" '.($dbport ? (is_numeric($dbport) ? ' --port="'.$dbport.'"' : ' --socket="'.$dbport.'"') : '').' --user="'.$dbuser.'" --password="'.$dbpw.'" "'.$dbname.'" '.$tablestr.' > '.$tofile);
		wskm_io::fWrite($tofile,'/*!40101 SET NAMES '.str_replace('-','',DB_CHARSET).' */;'.PHP_EOL.wskm_io::fRead($tofile));
	}

	static function import_mysqldump($fromfile){
		if (!file_exists($fromfile)) {
			return ;
		}

		$db=WSKM::SQL();
		$basedir=$db->fetch_first("SHOW VARIABLES LIKE 'basedir'");
		$basedir=$basedir['Value'];
		$mysqlbin = $basedir == DS ? '' : addslashes($basedir).'bin'.DS;

		$dbhost=WSKM::getConfig('dbHost');
		$dbport=WSKM::getConfig('dbPort');
		$dbuser=WSKM::getConfig('dbUser');
		$dbpw=WSKM::getConfig('dbPassword');
		$dbname=WSKM::getConfig('dbName');
		@set_time_limit(0);

		shell_exec($mysqlbin.'mysql -h"'.$dbhost.'"'.($dbport ? (is_numeric($dbport) ? ' -P"'.$dbport.'"' : ' -S"'.$dbport.'"') : '').' -u"'.$dbuser.'" -p"'.$dbpw.'" "'.$dbname.'" < '.$fromfile);

	}

}


?>