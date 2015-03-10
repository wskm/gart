<?php !defined('IN_ART') && exit('Access Denied');

class app_admin_plugin extends admin_common
{
	function load()
	{
		loadLang('admin_plugin');
		$this->model=usingAdminModel('plugin');
	}

	function doIndex(){
		if (checkToken()) {
			$selectid=requestPost('selectid',TYPE_ARRAY);
			$this->model->updatePluginStatus($selectid);
			$this->model->updateCache();
			
			adminMessage('edit_ok',getUrlReferer());
		}else{
			$data=$this->model->getPluginInstalls();

			assign_var('plugins',$data['plugins']);
			assign_var('installs',$data['installs']);
			adminTemplate('plugin');
		}
	}

	function doInstall(){
		$name=requestGet('name',TYPE_ALNUM);
		$dirpath=ART_ROOT.'plugins'.DS.$name.DS;
		require($dirpath.'install.php');

		$xmldata=array();
		if (file_exists($dirpath.DS.'version.xml')) {
			WSKM::using('wskm_xml');
			$xmldata=wskm_xml::xml2array(wskm_io::fRead( $dirpath.DS.'version.xml'));
			$xmldata=$xmldata['root'];
		}

		$info=array(
		'pluginname'=>$name,
		'plugintitle'=>$xmldata['title'],
		'version'=>$xmldata['version'],
		'copyright'=>$xmldata['copyright'],
		'ismanage'=>(int)$xmldata['ismanage'],
		'isnav'=>(int)$xmldata['isnav'],
		'hook'=>$xmldata['hook']?serialize($xmldata['hook']):'',
		'status'=>1
		);

		if(!$this->model->install($info)){
			adminMessage('install_err',-1);
		}
		$this->model->updateCache();
		adminMessage('install_ok','index.php?wskm=plugin');
	}

	function doUninstall(){
		$id=requestGet('id',TYPE_INT);
		$key=requestGet('key',TYPE_ALNUM);
		$dirpath=ART_ROOT.'plugins'.DS.$key.DS;
		require($dirpath.'uninstall.php');

		if(!$this->model->uninstall($id)){
			adminMessage('uninstall_err',-1);
		}
		$this->model->updateCache();
		adminMessage('uninstall_ok','index.php?wskm=plugin');
	}

	function doEdit(){
		if (checkToken()) {
			$pluginid=requestPost('pluginid',TYPE_INT);
			$plugin=requestPost('plugin',TYPE_ARRAY);
			$hook=requestPost('hook',TYPE_ARRAY);
			if ($pluginid < 1) {
				adminMessage('request_error','index.php?wskm=plugin');
			}

			if ($hook) {
				$plugin['hook']=serialize($hook);
			}

			if ($plugin['description']) {
				$plugin['description']=strCut($plugin['description'],300);
			}

			if(!$this->model->edit($plugin,$pluginid)){
				adminMessage('edit_error',-1);
			}

			$this->model->updateCache();
			adminMessage('edit_ok',getUrlReferer());

		}else{
			$id=requestGet('id',TYPE_INT);

			$info=$this->model->getInfo($id);
			if ($info==false) {
				adminMessage('request_error','index.php?wskm=plugin');
			}

			assign_var('info',$info);
			assign_var('pluginid',$id);
			adminTemplate('plugin_info');
		}
	}

	function doManage(){
		$key=requestGet('key',TYPE_ALNUM);
		if (empty($key)) {
			adminMessage('request_error',URL_HOME);
		}
		define('PLUGIN_KEY',$key);
		define('PLUGIN_URL',ART_URL.'plugins/'.PLUGIN_KEY.'/');
		define('PLUGIN_PATH',ART_ROOT.'plugins'.DS.PLUGIN_KEY.DS);

		$pluginpath=ART_ROOT.'plugins'.DS.PLUGIN_KEY.DS.'manage.php';
		if (file_exists($pluginpath)) {
			include $pluginpath;
		}

	}

}

function runSql($sql) {
	if(empty($sql)) return;

	$sql = str_replace("\r\n", "\n", $sql);
	$sql = str_replace(' `art_', ' `'.TABLE_PREFIX, $sql);
	$sql = str_replace("\r", "\n", $sql);
	$ret = array();
	$num = 0;

	foreach(explode(";\n", trim($sql)) as $query) {
		$queries = explode("\n", trim($query));
		foreach($queries as $query) {
			$query=trim($query);
			if ($query && ($query[0] != '#') && ($query[0].$query[1] != '--') && ($query[0].$query[1] != '/*' )) {
				$ret[$num] .= $query;
			}

		}
		$num++;
	}
	unset($sql);

	foreach($ret as $query) {
		if($query) {
			WSKM::SQL()->exec($query);
		}
	}

}

?>