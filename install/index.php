<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [Gart] www.wskmphp.com 
 * $Id: index.php 281 2010-12-26 07:57:48Z ws99 $ 
 */

error_reporting(E_ERROR | E_WARNING | E_PARSE);
@set_time_limit(800);
if (PHP_VERSION < '5.0')exit(PHP_VERSION.' Must be greater than php 5.0');
if(phpversion() < '5.3.0') {
	set_magic_quotes_runtime(0);
}

define('INSTALL_ART',true);
define('IN_WSKM',true);
define('DS',DIRECTORY_SEPARATOR);
define('INSTALL_PATH',dirname(__FILE__).DS);
define('WEB_ROOT',dirname(INSTALL_PATH).DS);
define('PAGE_CHARSET','utf-8');
define('DB_CHARSET','utf-8');
define('VERSION','1.6');

header('content-type:text/html; charset='.PAGE_CHARSET);
require INSTALL_PATH.'fun_util.php';

$lang='zh';
$step=0;
$langs=array();

if (isset($_GET['lang'])) {
	$lang=_GET('lang');
}
$langlist= require INSTALL_PATH.'inc_lang.php';
if (!in_array($lang,array_keys($langlist))) {
	$lang='zh';
}

if (isset($_GET['step'])) {
	$step=max(min((int)$_GET['step'],5),0);
}

$langs = require(INSTALL_PATH.'languages'.DS.$lang.'_lang.php');
ob_start();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=PAGE_CHARSET?>" />
<title><?=lang('page_title')?></title>
<link rel="stylesheet" href="style.css" type="text/css" media="all" />
<script type="text/javascript">
function $(id) {
	return document.getElementById(id);
}
</script>
</head>
<div class="wrap">
	<div class="header">
		<h1><?=lang('step_title_'.$step)?></h1>
		
		<span>Gart&nbsp;<?=VERSION?>&nbsp;<?=lang('lang')?>&nbsp;[<?=PAGE_CHARSET?>]
		<?php if($step==0) { ?>
		<select name="lang" onchange="location.href ='index.php?lang='+this.value " >
		<?php foreach ($langlist as $key=>$tempi) { ?>
		<option value="<?=$key?>" <?php if($lang==$key){?> selected="true"<?php } ?> ><?=$tempi?></option>
		<?php } ?>
		</select>
		<?php } ?>
		</span>
		
	</div>
	<div class="line"></div>
	<div class="main" >
<?php
if ($step==0) {
			?>
		<div class="licensewrap">
			<div class="license"><h1><?=lang('license_title')?></h1>
			<?=nl2br(lang('license'))?>
			</div>
			
		</div>

		<div class="center">
				<form action="index.php" method="get">
				<input type="hidden" value="1" name="step">
				<input type="hidden" value="<?=$lang?>" name="lang">
				<input type="button" onclick="window.close(); " value="<?=lang('agreement_no'); ?>" name="close" >&nbsp;<input type="submit" value="<?=lang('agreement_yes'); ?>" name="submit">
				
				</form>
		</div>

<?php
}elseif($step==1){

	if (file_exists(WEB_ROOT.'cache'.DS.'install.lock')) {
		ob_clean();
		echo lang('installed');
		exit();
	}
	
	$isnext=$uploadmax=true;
	//$funs=array('fsockopen'=>0,'xml_parser_create'=>0,'mysql_connect'=>0,'imagejpeg'=>0);
	$funs=array('mysql_connect'=>0,'imagejpeg'=>0);
	$isnext=function_check($funs);

	$phpe=phpversion() < '5.0';
	if ($phpe) {
		$isnext=false;
	}
	if (@ini_get('file_uploads') && (int)ini_get('upload_max_filesize')<2) {
		$uploadmax=false;
		$isnext=false;
	}

	$fsys=array(
	'at'=>array('type'=>'dir','path'=>'attachments','err'=>0),
	'ph'=>array('type'=>'dir','path'=>'photo','err'=>0),
	'ca'=>array('type'=>'dir','path'=>'cache','err'=>0),
	'cab'=>array('type'=>'dir','path'=>'cache/backup','err'=>0),
	'cad'=>array('type'=>'dir','path'=>'cache/data','err'=>0),
	'cat'=>array('type'=>'dir','path'=>'cache/tpl','err'=>0),
	'cs'=>array('type'=>'file','path'=>'config/config_sys.php','err'=>0),
	'uc'=>array('type'=>'dir','path'=>'uc_client/data/cache','err'=>0),
	'ht'=>array('type'=>'dir','path'=>'html','err'=>0),
	);

	if(!fsys_check($fsys)){
		$isnext=false;
	}

?>
		<h2 class="title">·&nbsp;<?=lang('env_sys')?></h2>
		<table class="marginbox box">
			<tbody>
				<tr>
					<td width="295"><?=lang('php_version')?></td>
					<td>> 5.0</td>
					<td class="<?php if(!$phpe){?>right<?php }else{ ?>error<?php }?>" ></td>
				</tr>
				<tr>
					<td><?=lang('upload_file')?></td>
					<td>2M</td>
					<td class="<?php if($uploadmax){?>right<?php }else{ ?>error<?php }?>" ></td>
				</tr>
				<tr>
					<td><?=lang('gd_support')?></td>
					<td></td>
					<td class="<?php if($funs['imagejpeg']){?>right<?php }else{ ?>error<?php }?>" ></td>
				</tr>
				<tr>
					<td><?=lang('mysql_support')?></td>
					<td></td>
					<td class="<?php if($funs['mysql_connect']){?>right<?php }else{ ?>error<?php }?>" ></td>
				</tr>
			</tbody>
		</table>

		<h2 class="title">·&nbsp;<?=lang('fsys_check')?></h2>
		<table class="marginbox box">
			<tbody>
				<tr>
					<td width="390" >./attachments/</td>
					<td class="<?php if(!$fsys['at']['err']){?>right<?php }else{ ?>error<?php }?>" ></td>
				</tr>
				<tr>
					<td  >./photo/</td>
					<td class="<?php if(!$fsys['ph']['err']){?>right<?php }else{ ?>error<?php }?>" ></td>
				</tr>
				<tr>
					<td  >./cache/</td>
					<td class="<?php if(!$fsys['ca']['err']){?>right<?php }else{ ?>error<?php }?>" ></td>
				</tr>
				<tr>
					<td  >./cache/backup/</td>
					<td class="<?php if(!$fsys['cab']['err']){?>right<?php }else{ ?>error<?php }?>" ></td>
				</tr>
				<tr>
					<td  >./cache/data/</td>
					<td class="<?php if(!$fsys['cad']['err']){?>right<?php }else{ ?>error<?php }?>" ></td>
				</tr>
				<tr>
					<td  >./cache/tpl/</td>
					<td class="<?php if(!$fsys['cat']['err']){?>right<?php }else{ ?>error<?php }?>" ></td>
				</tr>
				<tr>
					<td  >./config/config_sys.php</td>
					<td class="<?php if(!$fsys['cs']['err']){?>right<?php }else{ ?>error<?php }?>" ></td>
				</tr>
				<tr>
					<td  >./uc_client/data/cache/</td>
					<td class="<?php if(!$fsys['uc']['err']){?>right<?php }else{ ?>error<?php }?>" ></td>
				</tr>
				<tr>
					<td  >./html/</td>
					<td class="<?php if(!$fsys['ht']['err']){?>right<?php }else{ ?>error<?php }?>" ></td>
				</tr>
			</tbody>
		</table>
		

		
		<div class="center mt10">
		<?php if($isnext){ ?>
				<form action="index.php" method="get">
				<input type="hidden" value="2" name="step">
				<input type="hidden" value="<?=$lang?>" name="lang">
				<input type="button" onclick="history.go(-1);" value="<?=lang('step_up'); ?>" name="submit">&nbsp;
				<input type="submit" value="<?=lang('step_next'); ?>" name="nexts" >
				</form>
		<?php }else{?>
		<span class="red"><?=lang('step_error')?></span>
		<?php } ?>
		</div>
<?php
}elseif($step==2){ ?>
		<form action="index.php?step=3&lang=<?=$lang?>" method="POST">
		<h2 class="title">·&nbsp;<?=lang('userengine')?></h2>
		 <table class="marginbox ebox">
			<tr>
				<td width="80" ><?=lang('uengine_type')?>:</td>
				<td ><input type="radio"  name="userengine" value="user" checked="true" onclick="$('ucenterwrap').style.display='none'" >Gart User&nbsp;&nbsp;<input type="radio"  name="userengine" onclick="$('ucenterwrap').style.display=''" value="ucenter" >Ucenter</td>
			</tr>
			<tr>
				<td colspan="2"><span style="color:gray"><?=lang('uengine_selectnotice')?></span></td>
			</tr>
		</table>
		<table id="ucenterwrap" class="marginbox ebox" width="100%" style="display:none;" >
						<tr>
							<td width="90" align="left"><span class="editatitle">UCenter Url:</span></td>
							<td><input type="text"  class="txt" name="ucurl" ></td>
						</tr>
						<tr>
							<td ><span class="editatitle">UCenter IP:</span></td>
							<td>
							<input type="text"  class="txt"  name="ucip" >&nbsp;(<?=lang('uc_ipnotice')?>)
							</td>
						</tr>
						<tr>
							<td ><span class="editatitle">UCenter <?=lang('password')?>:</span></td>
							<td><input type="text"  class="txt"   name="ucpw" ></td>
						</tr>
		 </table>
		 <div class="center">
				<input type="hidden" value="<?=$lang?>" name="lang">
				<input type="submit" value="<?=lang('step_next'); ?>" name="nexts" >
				
		</div>
		</form>
<?php
}elseif($step==3){
	$stepshow=true;
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$err=array();

		$userengine=trim(_POST('userengine'));
		$ucip=$ucurl=$ucpw='';

		if (!in_array($userengine,array('user','ucenter'))) {
			$userengine='user';
		}

		if ($userengine=='ucenter') {
			require_once(INSTALL_PATH.'user'.DIRECTORY_SEPARATOR.'ucenter.php');
		}

		if ($err) {
			shownotice($err);
			goback();
			$stepshow=false;
		}

	}

	if ($stepshow) {
		$config=require(WEB_ROOT.'config'.DS.'config_sys.php');
?>
		<form action="index.php?step=4&lang=<?=$lang?>" method="POST">
		<input type="hidden" value="<?=$userengine?>" name="userengine">
		  <h2 class="title">·&nbsp;<?=lang('web_setting')?></h2>
		  <table class="marginbox ebox">
				<tr>
					<td width="80" ><?=lang('web_name')?>:</td>
					<td ><input type="text" class="txt" name="site[name]"  value="Gart Web" ></td>
				</tr>
				<tr>
					<td ><?=lang('web_url')?>:</td>
					<td >
					<input type="text" class="txt" name="site[url]"  value="<?=$_SERVER["SERVER_NAME"]?>">&nbsp;(<?=lang('web_url_notice')?>)
					</td>
				</tr>
				<tr>
					<td ><?=lang('web_basedir')?>:</td>
					<td >
					<input type="text" class="txt" name="site[basedir]"  value="<?=rtrim(str_replace('install/','',getBaseDir()),'/')?>">&nbsp;(<?=lang('web_basedir_notice')?>)
					</td>
				</tr>
				<tr>
					<td ><?=lang('timezone')?>:</td>
					<td >
								<select name="site[timeoffset]" id="timeoffset" style="width:400px" >
									  	<option value="-12">(GMT -12:00) Eniwetok, Kwajalein&nbsp;&nbsp;</option>
										<option value="-11">(GMT -11:00) Midway Island, Samoa&nbsp;&nbsp;</option>
										<option value="-10">(GMT -10:00) Hawaii&nbsp;&nbsp;</option>
										<option value="-9">(GMT -09:00) Alaska&nbsp;&nbsp;</option>
										<option value="-8">(GMT -08:00) Pacific Time (US &amp; Canada), Tijuana&nbsp;&nbsp;</option>
										<option value="-7">(GMT -07:00) Mountain Time (US &amp; Canada), Arizona&nbsp;&nbsp;</option>
										<option value="-6">(GMT -06:00) Central Time (US &amp; Canada), Mexico City&nbsp;&nbsp;</option>
										<option value="-5">(GMT -05:00) Eastern Time (US &amp; Canada), Bogota, Lima, Quito&nbsp;&nbsp;</option>
										<option value="-4">(GMT -04:00) Atlantic Time (Canada), Caracas, La Paz&nbsp;&nbsp;</option>
										<option value="-3.5">(GMT -03:30) Newfoundland&nbsp;&nbsp;</option>
										<option value="-3">(GMT -03:00) Brassila, Buenos Aires, Georgetown, Falkland Is&nbsp;&nbsp;</option>
										<option value="-2">(GMT -02:00) Mid-Atlantic, Ascension Is., St. Helena&nbsp;&nbsp;</option>
										<option value="-1">(GMT -01:00) Azores, Cape Verde Islands&nbsp;&nbsp;</option>
										<option value="0">(GMT) Casablanca, Dublin, Edinburgh, London, Lisbon, Monrovia&nbsp;&nbsp;</option>
										<option value="1">(GMT +01:00) Amsterdam, Berlin, Brussels, Madrid, Paris, Rome&nbsp;&nbsp;</option>
										<option value="2">(GMT +02:00) Cairo, Helsinki, Kaliningrad, South Africa&nbsp;&nbsp;</option>
										<option value="3">(GMT +03:00) Baghdad, Riyadh, Moscow, Nairobi&nbsp;&nbsp;</option>
										<option value="3.5">(GMT +03:30) Tehran&nbsp;&nbsp;</option>
										<option value="4">(GMT +04:00) Abu Dhabi, Baku, Muscat, Tbilisi&nbsp;&nbsp;</option>
										<option value="4.5">(GMT +04:30) Kabul&nbsp;&nbsp;</option>
										<option value="5">(GMT +05:00) Ekaterinburg, Islamabad, Karachi, Tashkent&nbsp;&nbsp;</option>
										<option value="5.5">(GMT +05:30) Bombay, Calcutta, Madras, New Delhi&nbsp;&nbsp;</option>
										<option value="5.75">(GMT +05:45) Katmandu&nbsp;&nbsp;</option>
										<option value="6">(GMT +06:00) Almaty, Colombo, Dhaka, Novosibirsk&nbsp;&nbsp;</option>
										<option value="6.5">(GMT +06:30) Rangoon&nbsp;&nbsp;</option>
										<option value="7">(GMT +07:00) Bangkok, Hanoi, Jakarta&nbsp;&nbsp;</option>
										<option value="8" selected >(GMT +08:00) Beijing, Hong Kong, Perth, Singapore, Taipei&nbsp;&nbsp;</option>
										<option value="9">(GMT +09:00) Osaka, Sapporo, Seoul, Tokyo, Yakutsk&nbsp;&nbsp;</option>
										<option value="9.5">(GMT +09:30) Adelaide, Darwin&nbsp;&nbsp;</option>
										<option value="10">(GMT +10:00) Canberra, Guam, Melbourne, Sydney, Vladivostok&nbsp;&nbsp;</option>
										<option value="11">(GMT +11:00) Magadan, New Caledonia, Solomon Islands&nbsp;&nbsp;</option>
										<option value="12">(GMT +12:00) Auckland, Wellington, Fiji, Marshall Island&nbsp;&nbsp;</option>
									  </select>
					</td>
				</tr>
				
			</table>
								
		  <h2 class="title">·&nbsp;<?=lang('db_setting')?></h2>
		  <table class="marginbox ebox">
				<tr>
					<td width="80" ><?=lang('db_host')?>:</td>
					<td ><input type="text" class="txt" name="sys[dbHost]"  value="<?=$config['dbHost']?>" ></td>
				</tr>
				<tr>
					<td width="80" ><?=lang('db_port')?>:</td>
					<td ><input type="text" class="txt" name="sys[dbPort]"  value="<?=$config['dbPort']?>" ></td>
				</tr>
				<tr>
					<td ><?=lang('db_name')?>:</td>
					<td ><input type="text" class="txt" name="sys[dbName]"  value="<?=$config['dbName']?>"></td>
				</tr>
				<tr>
					<td ><?=lang('db_username')?>:</td>
					<td ><input type="text" class="txt" name="sys[dbUser]"  value="<?=$config['dbUser']?>"></td>
				</tr>
				<tr>
					<td ><?=lang('db_password')?>:</td>
					<td ><input type="text" class="txt" name="sys[dbPassword]" value="<?=$config['dbPassword']?>" ></td>
				</tr>
				<tr>
					<td ><?=lang('db_tablepre')?>:</td>
					<td >
					<input type="text" class="txt" name="sys[tablePre]" value="<?=$config['tablePre']?>" >&nbsp;(<?=lang('db_tablepre_notice')?>)
					</td>
				</tr>
			</table>
			
			<h2 class="title">·&nbsp;<?=lang('admin_setting')?></h2>
		  <table class="marginbox ebox">
				<tr>
					<td  width="80"><?=lang('admin_name')?>:</td>
					<td ><input type="text" class="txt" name="username"  value="admin"></td>
				</tr>
				<tr>
					<td >Email:</td>
					<td ><input type="text" class="txt" name="useremail" value="admin@admin.com" ></td>
				</tr>
				<tr>
					<td ><?=lang('admin_password')?>:</td>
					<td ><input type="password" class="txt" name="userpw1" value="" ></td>
				</tr>
				<tr>
					<td ><?=lang('admin_password2')?>:</td>
					<td ><input type="password" class="txt" name="userpw2" value="" ></td>
				</tr>
			</table>
			
			<div class="center">
				<input type="hidden" value="<?=$lang?>" name="lang">
				<input type="submit" value="<?=lang('step_next'); ?>" name="nexts" >
				
			</div>
			</form>
<?php
	}

}elseif($step==4){
	if (file_exists(WEB_ROOT.'cache'.DS.'install.lock')) {
		ob_clean();
		echo lang('installed');
		exit();
	}
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$syss=(array)_POST('sys');
		$site=(array)_POST('site');
		$username=trim(_POST('username'));
		$useremail=trim(_POST('useremail'));
		$userpw1=trim(_POST('userpw1'));
		$userpw2=trim(_POST('userpw2'));
		$userengine=trim(_POST('userengine'));

		if (!in_array($userengine,array('user','ucenter'))) {
			$userengine='user';
		}
		if (!isEmail($useremail)) {
			$useremail='';
		}

		$syss['dbPort']=(int)$syss['dbPort'];


		$err=array();
		if (strlen($username)<4 || strlen($username) >15) {
			$err[]=lang('err_username_length');
		}

		if (!$useremail) {
			$err[]=lang('err_email');
		}

		if($userpw1 != $userpw2){
			$err[]=lang('err_password1');
		}elseif (!preg_match("/[a-z0-9]{6,32}/i",$userpw1)){
			$err[]=lang('err_password2');
		}

		$syss['tablePre']=str_replace('_','',$syss['tablePre']);
		if (!preg_match("/[a-z0-9]/i",$syss['tablePre'])) {
			$syss['tablePre']='';
		}

		foreach ($syss as $key=>$tempi){
			$tempi=trim($tempi);
			if(empty($tempi) ){
				if ($key=='dbHost') {
					$err[]=lang('err_host');
				}elseif ($key=='dbName'){
					$err[]=lang('err_name');
				}elseif ($key=='dbUser'){
					$err[]=lang('err_username');
				}elseif ($key=='dbPassword'){
					$err[]=lang('err_password');
				}elseif ($key=='tablePre'){
					$err[]=lang('err_tablepre');
				}
			}
		}
		$dberr=db_check($syss['dbHost'],$syss['dbUser'],$syss['dbPassword'],$syss['dbName']);
		if ($dberr !== true) {
			$err[]='DB -> '.$dberr;
		}

		$site['url']=rtrim(str_replace('http://','',$site['url']),'/');
		$site['basedir']=rtrim($site['basedir'],'/');
		foreach ($site as $key=>$tempi){
			$tempi=trim($tempi);
			if(empty($tempi) ){
				if ($key=='name') {
					$err[]=lang('err_webname');
				}elseif ($key=='url'){
					$err[]=lang('err_weburl');
				}
			}
		}
		$uid=1;
		if ($userengine=='ucenter') {
			$uid=uc_add_admin($username,$userpw1,$useremail);
		}

		if ($err) {
			shownotice($err);
			goback();
		}else{
			///***********Begin*************
			if ($syss['tablePre']) {
				$syss['tablePre']=rtrim($syss['tablePre'],'_').'_';
			}else{
				$syss['tablePre']='art_';
			}

			$syss['dbCharset']=DB_CHARSET;
			$syss['dbPconnect']=0;
			$syss['dbDriver']='mysql';
			$syss['dbType']='mysql';
			$syss['pageCharset']=PAGE_CHARSET;
			$syss['language']=$lang;

			$syss['cookiePre']=random(6).'_';
			$syss['cookieDomain']='';
			$syss['cookiePath']='/';

			$syss['artkey']=md5(print_r($syss,true).$_SERVER['SERVER_ADDR'].$_SERVER['HTTP_USER_AGENT'].uniqid(true).random(10));
			$syss['urlartkey']=md5($syss['artkey'].random(10));
			$syss['userEngine']=$userengine;

			if($cfcontent = file_get_contents(WEB_ROOT.'config'.DS.'config_sys.php')) {
				$cfcontent = trim($cfcontent);
			}

			$userconfig='';
			if ($userengine !='user' && strExists($cfcontent,'##USER##')) {
				if(preg_match("/##USER##[\n\r\t]*(.*?)[\n\r\t]*##ENDUSER##/s",$cfcontent,$match)){
					$userconfig=$match[1];
				}
			}

			$data='<?php !defined(\'IN_WSKM\') && exit(\'Access Denied\');'."\n\n##USER##\n{$userconfig}\n##ENDUSER##\n\n".'return ';
			$data.=arrayEval($syss).";\n".'?>';
			file_put_contents(WEB_ROOT.'config'.DS.'config_sys.php',$data);

			if ($err) {
				shownotice($err);
				goback();
			}else{

?>

<script type="text/javascript">
function putmsg(message) {
	document.getElementById('alertwin').innerHTML += message + '<br />';
	document.getElementById('alertwin').scrollTop = 100000000;
}
</script>
		<div class="center"><div id="alertwin" ></div></div>
		<div class="center mt10">
		<input type="button" name="submit" value="<?=lang('step_next')?>" disabled id="nextstep" onclick="window.location='index.php?step=5&lang=<?=$lang?>';">
		</div>
<?php
require(INSTALL_PATH.'class_mysql.php');
$db=new wskm_db_mysql();
$db->addDbServer(array('dbHost'=>$syss['dbHost'],'dbUser'=>$syss['dbUser'],'dbPassword'=>$syss['dbPassword'],'dbPort'=>$syss['dbPort'],'dbName'=>$syss['dbName'],'dbCharset'=>DB_CHARSET));

define('TABLE_PREFIX',$syss['tablePre']);
$sqlfile=file_get_contents(WEB_ROOT.'install'.DS.'Gart.sql');
runSql($sqlfile);

foreach (array('cache'.DS.'data','cache'.DS.'tpl') as $path){
	dir_initdata(WEB_ROOT.$path);
}

table_initdata();

$addsql="

INSERT INTO `art_category` VALUES (1,0,'{$langs['category_default']}','','','','',99,0,'','category','news',1);

UPDATE `art_usergroups` SET groupname='{$langs['groupname_1']}' WHERE groupid='1';
UPDATE `art_usergroups` SET groupname='{$langs['groupname_2']}' WHERE groupid='2';
UPDATE `art_usergroups` SET groupname='{$langs['groupname_3']}' WHERE groupid='3';
UPDATE `art_usergroups` SET groupname='{$langs['groupname_4']}' WHERE groupid='4';
UPDATE `art_usergroups` SET groupname='{$langs['groupname_5']}' WHERE groupid='5';
UPDATE `art_usergroups` SET groupname='{$langs['groupname_6']}' WHERE groupid='6';
UPDATE `art_usergroups` SET groupname='{$langs['groupname_7']}' WHERE groupid='7';

REPLACE INTO `art_settings` (variable,type,value) VALUES ('webName','base','{$site['name']}');
REPLACE INTO `art_settings` (variable,type,value) VALUES ('webUrl','base','{$site['url']}');
REPLACE INTO `art_settings` (variable,type,value) VALUES ('webBaseUrl','base','{$site['basedir']}');
REPLACE INTO `art_settings` (variable,type,value) VALUES ('language','base','{$lang}');
REPLACE INTO `art_settings` (variable,type,value) VALUES ('timeZone','base','{$site['timeoffset']}');

REPLACE INTO `art_settings` (variable,type,value) VALUES ('emailCharset','mail','".PAGE_CHARSET."');
REPLACE INTO `art_settings` (variable,type,value) VALUES ('emailLanguage','mail','{$lang}');

";

jsMessage('-> Table update');
runSql($addsql);

$userip=getUserIp();
$time=time();
$upassword=$salt='';
$salt=random(8,0);
$upassword=md5(sha1($userpw1).$salt);

$db->query("INSERT INTO ".TABLE_PREFIX."users(`uid` ,`uname` ,`email` ,`password` ,`salt` ,`adminid` ,`groupid` ,`sex` ,`createip` ,`createtime` ,`replycount` ,`lastreplytime` ,`lastip` ,`lastvisit` ,`timeformat` ,`timeoffset` ,`birthday` ,`sendemail` ,`showemail` )VALUES ('{$uid}', '{$username}', '{$useremail}', '{$upassword}', '{$salt}', 1, 1, '0', '{$userip}', '{$time}', '0', '0', '{$userip}', '{$time}', '0', '99', '0000-00-00', 1, 0)");
$db->query('INSERT INTO '.TABLE_PREFIX."userprotected (uid,uname,dateline)VALUES('{$uid}','$username','$time') ");

installDone();

			}

		}
	}
}elseif($step==5){
	//@unlink(INSTALL_PATH.'index.php');
?>
<div class="center mt10">
<div style="margin-left:150px;text-align:left;">
	<ul class="tourl">
		<li><a href="../"><?=lang('web_index')?></a></li>
		<li><a href="../admin/"><?=lang('web_setting')?></a></li>
	</ul>

</div>
</div>
<?php	
}
?>
	</div>
	<div class="footer">&copy;2010 <a href="http://www.wskmphp.com/">Gart</a> Inc.</div>
	</div>
</div>
</body>
</html>