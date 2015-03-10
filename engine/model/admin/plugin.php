<?php !defined('IN_ART') && exit('Access Denied');

class model_admin_plugin extends wskm_model_abstract
{
	function getInfo($id){
		$info= $this->db->fetch_first('SELECT * FROM '.TABLE_PREFIX."plugins WHERE pluginid='{$id}' ");
		if ($info['hook']) {
			$info['hook']=unserialize($info['hook']);
		}
		return $info;
	}

	function edit($info,$id){
		return $this->db->update(TABLE_PREFIX.'plugins',$info," pluginid='{$id}' " ) !==false;
	}
	
	function updatePluginStatus($showids){
		$query= $this->db->query('SELECT pluginid FROM '.TABLE_PREFIX."plugins ");
		while ($tempi=$this->db->fetch($query)) {
			$status=0;
			if ( in_array($tempi['pluginid'],$showids) ) {
				$status=1;
			}
			$this->db->update(TABLE_PREFIX.'plugins',array('status'=>$status)," pluginid='{$tempi['pluginid']}' ");
		}
		
	}

	function getPluginInstalls(){
		$query=$this->db->query('SELECT * FROM '.TABLE_PREFIX."plugins ");
		$plugins=$plugnames=$installs=array();
		while ($item=$this->db->fetch($query)) {
			$plugins[]=$item;
			$plugnames[]=$item['pluginname'];
		}

		$dirpath = ART_ROOT.'plugins'.DS;
		$dirhand = dir($dirpath);
		WSKM::using('wskm_xml');
		while($dirname = $dirhand->read()) {
			$fullpath = realpath($dirpath.$dirname);
			if(preg_match("/[a-z0-9_\-]+/i",$dirname) !==false &&  !in_array($dirname, $plugnames) && is_dir($fullpath)) {

				$plugdir=dir($fullpath);
				$isinstall=$isuninstall=$isversion=false;
				while ($plugname=$plugdir->read()){
					if ($plugname == 'install.php') {
						$isinstall=true;
					}elseif ($plugname == 'uninstall.php'){
						$isuninstall=true;
					}elseif ($plugname=='version.xml'){
						$isversion=true;
					}

				}
				if ($isinstall && $isuninstall && $isversion) {
					$xmldata=array();
					if (file_exists($fullpath.DS.'version.xml')) {
						$xmldata=wskm_xml::xml2array(wskm_io::fRead( $fullpath.DS.'version.xml'));
						$xmldata=$xmldata['root'];
					}

					$installs[]=array('pluginname'=>$dirname,'plugintitle'=>$xmldata['title'],'version'=>$xmldata['version'],'copyright'=>$xmldata['copyright'],'status'=>-1);
				}

			}
		}

		return array('plugins'=>$plugins,'installs'=>$installs);
	}


	function install($info){
		return $this->db->insert(TABLE_PREFIX.'plugins',$info)  !==false;
	}

	function uninstall($id){
		return $this->db->delete(TABLE_PREFIX.'plugins',"pluginid='{$id}' ")  !==false;
	}

	function updateCache(){
		$nav=usingAdminModel('nav');
		$nav->updateCache();
	}

}

?>