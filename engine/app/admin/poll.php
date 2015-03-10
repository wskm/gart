<?php !defined('IN_ART') && exit('Access Denied');

usingArtFun('article');
class app_admin_poll extends admin_common
{
	function load()
	{
		$this->model=usingAdminModel('article');
		loadLang('admin_article');
	}

	function doIndex()
	{
		if (checkToken()) {
			$selects=requestPost('selects',TYPE_ARRAY);
			if (count($selects)  > 0) {
				if($this->model->deletePoll(wkImplode($selects,','),1)==false){
					adminMessage('del_error',-1);
				}
				art_cache::update('static');
				adminMessage('del_ok','index.php?wskm=poll');
			}
		}
		$data=$this->model->getPolls();
		assign_var('list',$data['list']);
		assign_var('htmlpage',multiPage($data['count'],$data['page'],'index.php?wskm=poll',$this->model->adminPageCount));
		adminTemplate('poll');
	}

	function doHandle(){
		if (checkToken()) {
			$pollid=requestPost('pollid',TYPE_INT);
			$isadd=$pollid<1;
			$others=requestPost('others',TYPE_ARRAY);
			if (!isset($others['ismore'])) {
				$others['ismore']=0;
			}
			$others['expire']=(int)$others['expire'];
			if ($others['expire']>0) {
				$others['expire']=$others['expire']*86400 + WSKM_TIME;
			}

			$others['options']=array_merge((array)$others['polloptions'],(array)$others['options']);
			$others['options']=array_unique(array_filter((array)$others['options'],'trim'));
			if (count($others['options']) > 20) {
				$others['options']=array_slice($others['options'],0,20);
			}

			if (!$others['title'] && !$others['options'] ) {
				adminMessage('poll_inputmsg',-1);
			}

			if ($isadd) {
				if(!$this->model->insertPoll($others['title'],$others['ismore'],WSKM_TIME,$others['expire'],$others['options'])){
					adminMessage('poll_add_bad',-1);
				}
				art_cache::update('static');
				adminMessage('poll_add_ok','index.php?wskm=poll');
			}else{
				if(!$this->model->updatePoll($pollid,$others['title'],$others['ismore'],WSKM_TIME,$others['expire'])){
					adminMessage('poll_edit_bad',-1);
				}

				foreach ((array)$others['polloptions'] as $optionid=>$name){
					if(!$this->model->updatePollOption($optionid,$name)){
						adminMessage('poll_edit_bad',-1);
					}
				}

				if ($others['options']) {

					foreach ($others['options'] as $name){
						if (!in_array($name,$others['polloptions'] )) {
							if(!$this->model->insertPollOption($pollid,$name)){
								adminMessage('poll_edit_bad',-1);
							}
						}
					}
				}
				art_cache::update('static');
				adminMessage('poll_edit_ok',getUrlReferer());
			}


		}else{
			$pollid=requestGet('id',TYPE_INT);
			
			$others=array();
			if ($pollid > 0) {
				$others=$this->model->getPoll($pollid);
				if ($others['poll']['expire']) {
					$others['poll']['expire']=floor(($others['poll']['expire']-$others['poll']['dateline'])/86400);
				}
				assign_var('option_first',$others?array_shift($others['options']):'');
			}else{
				$others['poll']['expire']=0;
			}
			
			assign_var('others',$others);
			assign_var('pollid',$pollid);
			assign_var('isuse',requestGet('isuse',TYPE_BOOL));
			adminTemplate('poll_info');
		}
	}
	
}

?>