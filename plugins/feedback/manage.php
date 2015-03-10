<?php !defined('IN_ART') && exit('Access Denied');

pluginLang(PLUGIN_KEY);
$hand=requestGet('hand',TYPE_ALNUM);

if (checkToken() && requestGet('acttype',TYPE_ALNUM)=='del') {
	$selects=requestPost('selectid',TYPE_ARRAY);
	if (count($selects) <1) {
		adminMessage('select_one',-1);
	}
	if ($this->db->delete(TABLE_PREFIX.'feedback','id',$selects) === false) {
		adminMessage('del_error',-1);
	}
	adminMessage('del_ok',getUrlReferer());

}elseif($hand=='edit'){
	if (checkToken()) {
		
		$isdel=requestPost('isdel',TYPE_BOOL);
		$id=requestPost('id',TYPE_INT);
		if ($isdel && $id >0) {
			if ($this->db->delete(TABLE_PREFIX.'feedback'," id='$id' ") === false) {
				adminMessage('del_error',-1);
			}
			adminMessage('del_ok','index.php?wskm=plugin&act=manage&key='.PLUGIN_KEY);
		}

	}else{
		$id=requestGet('id',TYPE_INT);
		$info=$this->db->fetch_first('SELECT * FROM '.TABLE_PREFIX."feedback WHERE id='$id'");
		assign_var('info',$info);
		adminTemplate('plug:feedback_manageinfo');
	}
}elseif(!$hand){
	$list=$this->db->fetch_all('SELECT * FROM '.TABLE_PREFIX."feedback ORDER BY dateline DESC ");
	assign_var('list',$list);
	adminTemplate('plug:feedback_manage');
}

?>