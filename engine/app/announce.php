<?php !defined('IN_ART') && exit('Access Denied');

class app_announce extends art_page
{
	function  page_load(){
		$this->model=usingModel('announce');
	}
	
	function doIndex(){
		assign_var('list',$this->model->getNews(15));
		template('announce');
	}
	
	function doShow(){
		$id=requestGet('id',TYPE_INT);
		if ($id< 1) {
			artMessage('requer_eror','index.php');
		}
		
		assign_var('info',$this->model->getInfo($id));
		template('announce_info');
	}
}




?>