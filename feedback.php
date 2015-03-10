<?php

define('IN_ART',true);
define('PLUGIN_KEY','feedback');
require('./includes/inc_plugin.php');
pluginLang(PLUGIN_KEY);
if (checkToken()) {

	$feedfack=array(
	'title'=>wkHtmlspecialchars(requestPost('title',TYPE_STRING,80)),
	'message'=>nl2br(wkHtmlspecialchars(requestPost('message',TYPE_STRING,1000))),
	'email'=>requestPost('email'),
	'author'=>wkHtmlspecialchars(requestPost('author',TYPE_STRING,30)),
	'ip'=>USER_IP,
	'dateline'=>WSKM_TIME,
	);

	$vcode=requestPost('vcode',TYPE_ALNUM);
	$user=WSKM::user();
	if(!$user->checkVcode($vcode)){
		artMessage('vcode_inputerr',-1);
	}

	if (empty($feedfack['title']) || empty($feedfack['message']) || empty($feedfack['author'])) {
		artMessage('all_notempty',-1);
	}
	if (strlen($feedfack['message']) > 1000) {
		artMessage('fcontent_toolong',-1);		
	}
	
	if ($feedfack['email'] && !isEmail($feedfack['email'])) {
		artMessage('email_format',-1);
	}
	
	$db=WSKM::SQL();
	if($db->insert(TABLE_PREFIX.'feedback',$feedfack) === false){
		artMessage('submit_error',-1);
	}
	$user->vcode_new();
	artMessage('submit_ok',getUrlReferer());

}else{
	assign_var('artnav',lang('ftitle'));
	assign_var('page_title',lang('ftitle').'_');
	assign_var('nav_current','feedback');
	template('plug:feedback');
}
?>