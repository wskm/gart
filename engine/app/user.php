<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [Gart] www.wskmphp.com 
 * $Id: user.php 280 2010-12-26 06:05:21Z ws99 $ 
 */

!defined('IN_ART') && exit('Access Denied');

class app_user extends art_page
{
	public $Auser=null;
	public function page_load(){
		$this->Auser=tigerUser();
		assign_var('usergroup',WSKM::getValue('usergroup'));
		loadLang('user');
		loadLang('admin_article');
	}

	function doIndex()
	{
		if (!$this->isLogin()) {
			artMessage('login_must','index.php');
		}

		$profile=$this->Auser->getUser($this->getUid());
		assign_var('profile',$profile);

		assign_var('isEmailVerify',(bool)WSKM::getConfig('isEmailVerify'));
		assign_var('act','home');
		assign_var('unav','default');
		assign_var('user_nav',lang('user_set'));
		template('user');
	}

	function doProfile(){
		if (!$this->isLogin()) {
			artMessage('login_must','index.php');
		}

		if (checkToken()) {
			$email=requestPost('email');
			$sex=requestPost('sex',TYPE_INT);
			$bday=requestPost('birthday');

			if (!isEmail($email)) {
				artMessage('email_format',-1);
			}

			if (!preg_match("/[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}/",$bday)) {
				artMessage('bday_formaterr',-1);
			}
			
			$ischangemail=false;
			if ($email != $this->user->getEmail()) {
				$res=$this->Auser->checkEmail($email);
				if ($res==REGISTER_EMAIL_ERROR){
					artMessage('email_format',-1);
				}elseif ($res==REGISTER_EMAIL_PROTECT){
					artMessage('email_protect',-1);
				}elseif ($res==REGISTER_EMAIL_EXISTS){
					artMessage('email_exists',-1);
				}
				$ischangemail=true;
			}

			$update=array(
			'sex'=>$sex,
			'birthday'=>$bday
			);
			
			if ($ischangemail) {
				$update['emailverify']=0;
			}
			$this->model=usingModel('user');
			if (!$this->model->editProfile($update,$this->getUid())) {
				artMessage('edit_error',-1);
			}

			if ($this->user->getEmail() != $email) {
				$res=$this->Auser->editUser($this->getUid(),'','',$email,array('isforce'=>1,'uname'=>$this->getUname()));
				if ($res===-8) {
					artMessage('user_protect_noeditemail',-1);
				}elseif ($res===false ) {
					artMessage('edit_error',-1);
				}
			}

			artMessage('edit_ok',getUrlReferer());
		}else{
			$this->model=usingModel('user');
			$profile=$this->Auser->getUser($this->getUid());

			assign_var('profile',$profile);
			assign_var('user_nav',lang('user_set'));
			assign_var('act','home');
			assign_var('unav','profile_set');
			template('user');
		}
	}

	function doWebSet(){
		if (!$this->isLogin()) {
			artMessage('login_must','index.php');
		}

		if (checkToken()) {
			$update=array(
			'timeoffset'=>requestPost('timeoffset',TYPE_STRING,4),
			'timeformat'=>requestPost('timeformat',TYPE_INT),
			'showemail'=>requestPost('showemail',TYPE_INT),
			'sendemail'=>requestPost('sendemail',TYPE_INT),
			);
			$this->model=usingModel('user');
			if (!$this->model->editProfile($update,$this->getUid())) {
				artMessage('edit_error',-1);
			}
			artMessage('edit_ok',getUrlReferer());
		}else{
			$this->model=usingModel('user');
			$profile=$this->Auser->getUser($this->getUid());
			assign_var('timeformats', WSKM::getConfig('timeFormats'));
			assign_var('profile',$profile);
			assign_var('act','home');
			assign_var('user_nav',lang('user_set'));
			assign_var('unav','web_set');
			template('user');
		}
	}

	function doPhoto(){
		if (!$this->isLogin()) {
			artMessage('login_must','index.php');
		}

		if (checkToken()) {

			if ($_FILES && $_FILES['photo']['name'][0]) {

				WSKM::using('wskm_fileupload');
				$bdir= getPhotoDir($this->getUid());
				$attch=uploadEasy('photo',$bdir,$this->getUid().'_temp',1);
				$attch=$attch[0];
				if ($attch['err']) {
					artMessage($attch['err'],-1);
				}
				if ($attch['path']) {
					WSKM::using('wskm_gd_image');
					$srcfile=$bdir.$attch['path'];
					$objectImg=wskm_gd_image::getInstance($srcfile,$bdir);
					$objectImg->isSetExt(false);
					$isgif=$objectImg->animatedgif;
					if ($isgif) {
						foreach (array('s'=>array(50,50),'m'=>array(100,100),'b'=>array(180,180)) as $pk=>$psize){
							@copy($srcfile,$bdir.$this->getUid().'_photo_'.$pk.'.jpg');
						}
					}else{
						foreach (array('s'=>array(50,50),'m'=>array(100,100),'b'=>array(180,180)) as $pk=>$psize){
							$objectImg->Thumb($psize[0],$psize[1],$bdir.$this->getUid().'_photo_'.$pk.'.jpg');
						}
					}
					wskm_io::fDelete($srcfile);
				}

				artMessage('user_photo_editok',mvcUrl('',array('user','photo',array('update'=>1))));

			}else{
				artMessage('uploadfile_mustimg',-1);
			}
		}else{
			assign_var('photohtml',$this->Auser->setPhotoHtml($this->user->getUid()));
			assign_var('updatephoto',requestGet('update',TYPE_BOOL)?'&random='.mt_rand(1,1000):'');
			assign_var('act','home');
			assign_var('user_nav',lang('user_set'));
			assign_var('unav','photo_set');
			template('user');
		}
	}

	function doPassword()
	{
		if (!$this->isLogin()) {
			artMessage('login_must','index.php');
		}

		if (checkToken()) {
			$oldpw=requestPost('oldpw',TYPE_STRING,32);
			$newpw=requestPost('newpw',TYPE_STRING,32);
			$newpwc=requestPost('newpwc',TYPE_STRING,32);

			if(strlen($oldpw)<3 || strlen($newpw)<3){
				artMessage('password_length',-1);
			}
			elseif($newpw != $newpwc) {
				artMessage('password_nomatch',-1);
			}
			elseif(!$newpw || $newpw != addslashes($newpw)) {
				artMessage('password_illegal',-1);
			}

			$res=$this->Auser->editUser($this->getUid(),$oldpw,$newpw,'',array('uname'=>$this->getUname()));
			if ($res===-1) {
				artMessage('password_olderr',-1);
			}elseif($res===false){
				artMessage('password_editerr',-1);
			}

			$this->Auser->logout();
			artMessage('password_editok',ART_URL);
		}else{
			assign_var('act','password');
			assign_var('user_nav',lang('user_password'));
			template('user');
		}
	}

	function doGetForgotPassword(){

		$uid=requestGet('id',TYPE_INT);
		$key=requestGet('key',TYPE_STRING);
		$t=requestGet('t',TYPE_INT);

		if (!$t || (WSKM_TIME - $t > 600)) {
			artMessage('request_expired','index.php');
		}
		if ($uid < 1 || empty($key)) {
			artMessage('request_error','index.php');
		}

		$info=$this->Auser->getUser($uid);
		if ($info==false || $info['uid']<1) {
			artMessage('request_error','index.php');
		}

		if ($key != wskm_user_abstract::forgotPasswordKey($info['uid'],$info['uname'],$info['email'])) {
			artMessage('request_expired','index.php');
		}

		$newpw=wskm_user_abstract::randomPassword(6);
		$this->Auser->editUser($info['uid'],'',$newpw,'',array('isforce'=>true,'uname'=>$info['uname']));
		assign_var('issuccess',true);
		$msg=sprintf(lang('forgotpassword_success'),'***'.substr($info['uname'],3),$newpw);
		assign_var('success_message',$msg);
		template('forgotpassword');
	}

	function doArticle(){
		if (!$this->isLogin()) {
			artMessage('login_must',URL_HOME);
		}
		if (!isAccessFor('isarticle')) {
			artMessage('no_access',URL_HOME);
		}

		if (checkToken()) {
			$list=requestPost('selectid',TYPE_ARRAY);
			$ptype=requestPost('ptype',TYPE_WORD);
			$selctcount=count($list);
			if ($selctcount < 1) {
				artMessage('select_onetitle',-1);
			}

			if ($selctcount > 20) {
				artMessage('request_error',-1);
			}

			$list=wkAddslashes($list);
			$instr=wkImplode($list,',');
			$this->model=usingAdminModel('article');
			if(!$this->model->updateTitle(array('status'=>0)," aid IN ({$instr}) " )){
				artMessage('del_bad',-1);
			}

			if ($ptype=='my') {
				$this->model->sync($list);
			}
			artMessage('torecycle_ok',getUrlReferer());

		}else{
			assign_var('act','article');
			assign_var('user_nav',lang('article_my'));
			$this->model=usingAdminModel('article');
			$data=$this->model->getAdminTitles(UID);

			$keys=array('page'=>$data['page']);
			if (isset($data['keys']['status'])) {
				$keys['status']=$data['keys']['status'];
			}
			if ($keys['status']==2) {
				assign_var('unav','audit');
			}else{
				assign_var('unav','my');
			}

			assign_var('htmlpage',multiPage($data['count'],$data['page'],array('user','article',$keys),$this->model->adminPageCount));
			assign_var('list',$data['list']);

			template('user');
		}
	}

	function doArticleDo(){
		if (!$this->isLogin()) {
			artMessage('login_must','index.php');
		}

		if (!isAccessFor('isarticle')) {
			artMessage('no_access',URL_HOME);
		}

		if (checkToken()) {
			$aid=requestPost('aid',TYPE_INT);
			$isnew=false;
			if ($aid<1) {
				$isnew=true;
			}

			$cid=requestPost('cid',TYPE_INT);
			$title=requestPost('title',TYPE_STRING,80);
			$message=requestPost('message',TYPE_HTMLTEXT);

			if (empty($title) || empty($cid) || empty($message)) {
				artMessage('article_input_err1',-1);
			}

			$status=isAccessFor('isarticlefree')?1:2;
			$titles=array(
			'aid'=>$aid,
			'cid'=>$cid,
			'title'=>$title,
			'cover'=>requestPost('coverthumb',TYPE_STRING,100),
			'summary'=>requestPost('summary',TYPE_HTMLTEXT,250),
			'author'=>requestPost('author',TYPE_STRING,20),
			'fromname'=>requestPost('fromname',TYPE_STRING,20),
			'fromurl'=>str_replace('http://','', requestPost('fromurl',TYPE_STRING,150)),
			'tags'=>'',
			'replystate'=>requestPost('replystate',TYPE_INT),
			'status'=>$status,
			);
			if ($isnew) {
				$titles['uid']=$this->getUid();
				$titles['uname']=$this->getUname();
				$titles['dateline']=WSKM_TIME;
			}

			WSKM::helper('util');
			if ($titles['summary']) {
				$titles['summary']=toText($titles['summary']);
			}

			$this->model=usingAdminModel('article');

			if ($isnew) {
				$aid=$this->model->insertTitle($titles);
				if ($aid<1) {
					artMessage('title_add_bad',-1);
				}
			}elseif(!$this->model->updateTitleById($titles,$aid)){
				artMessage('article_edittitle_bad',-1);
			}

			if ((bool)WSKM::getConfig('articleUrlAbsolute')) {
				$message=$this->model->urlAbsolute($message);
			}

			$articlemessage=array(
			'aid'=>$aid,
			'message'=>$message,
			'pagetype'=>WSKM::getConfig('articlePageType')
			);
			if ($isnew) {
				$articlemessage['dateline']=WSKM_TIME;
			}

			if ($isnew && !$this->model->insertMessage($articlemessage)){
				artMessage('article_add_bad',-1);
			}elseif(!$this->model->updateMessage($articlemessage,$aid) ){
				artMessage('article_edit_bad',-1);
			}

			$attachclass=null;
			$thumbattachid=requestPost('coverid',TYPE_INT);
			if ($thumbattachid >0) {
				$attachclass=usingAdminModel('attachment');
				$attachclass->update($thumbattachid,$aid,$this->getUid(),$cid);
			}

			$uploadattachs=(array)requestPost('attachadd');
			if(count($uploadattachs) > 0){
				if (!is_object($attachclass)) {
					$attachclass=usingAdminModel('attachment');
				}
				$attachsstr=wkImplode($uploadattachs,',');
				$attachclass->update($attachsstr,$aid,$this->getUid(),$cid,true);
			}

			$tags=requestPost('tags',TYPE_STRING);
			if ($tags) {
				$this->model->setTags($tags,$aid);
			}

			if ($status) {
				$this->model->sync($aid);
			}
			artMessage($isnew?'article_add_ok':'article_edit_ok',getUrlReferer());

		}else{
			$id=requestGet('id',TYPE_INT);
			$info=array();
			if ($id >0) {
				assign_var('user_nav',lang('article_edit'));
				assign_var('unav','edit');
				$this->model=usingAdminModel('article');
				$info=$this->model->getAdminArticleFullInfo($id);
			}else{
				assign_var('user_nav',lang('article_write'));
				assign_var('unav','do');
				$info['cid']=0;
				$info['message']='';
				$info['replystate']=WSKM::getConfig('articleReplyState');
			}
			$categoryObject=usingAdminModel('category');
			assign_var('cateoption',$categoryObject->getSelectOption(WSKM::getValues('category','tree'),$info['cid']));

			usingArtFun('article');
			assign_var('editname','message');
			assign_var('editor',getHtmlEditor('message',$info['message']));
			assign_var('act','article');
			assign_var('aid',$id);
			assign_var('info',$info);
			template('user');
		}

	}

	function doForgotPassword(){
		if ($this->isLogin()) {
			artMessage('logged','index.php');
		}

		if (checkToken()) {
			if(!$this->user->checkVcode(requestPost('vcode',TYPE_STRING,4))){
				xmlMessage('vcode_inputerr');
			}
			$name=requestPost('uname',TYPE_STRING,15);
			$email=requestPost('email',TYPE_STRING,50);

			if (!isEmail($email)) {
				xmlMessage('email_format');
			}

			$info=$this->Auser->getUser($name,array('type'=>'uname'));
			if ($info === false || $info['email'] != $email) {
				xmlMessage('forgotpassword_notexists');
			}

			$link=mvcUrl('',array('user','getForgotPassword',array('key'=>wskm_user_abstract::forgotPasswordKey($info['uid'],$info['uname'],$info['email']),'t'=>WSKM_TIME,'id'=>$info['uid'] )),ART_URL_FULL);
			$body=lang('forgotpassword_mailbody');
			$body .= "<br/><a href=\"$link\" target='_blank' >{$link}</a>";

			WSKM::using('wskm_email');
			@set_time_limit(60);
			$res=wskm_email::sendmail(ART_WEB_NAME.'-'.lang('forgotpassword_mailtitle'),$body,$email);
			if (empty($res)) {
				xmlMessage('[ok]');
			}
			xmlMessage('forgotpassword_sendmailerr');
		}else{

			template('forgotpassword');
		}
	}

	function doLogin()
	{
		if ($this->isLogin()) {
			if (IN_AJAX) {
				xmlMessage('logged');
			}
			artMessage('logged','index.php');
		}
		assign_var('referer',getUrlReferer());
		template('login');
	}

	function doReg()
	{
		if ($this->isLogin()) {
			if (IN_AJAX) {
				xmlMessage('logged');
			}
			artMessage('logged','index.php');
		}

		if (!(bool)WSKM::getConfig('isAllowReg')) {

			if (IN_AJAX) {
				xmlMessage('<div style="padding:15px">'.lang('reg_disablenotice').'</div>');
			}else{
				artMessage('reg_disablenotice');
			}
		}

		loadCacheSystem('rules');
		assign_var('rules_txt',WSKM::getValue('rules'));

		assign_var('isterm',WSKM::getConfig('isRules'));
		assign_var('referer',getUrlReferer());
		template('reg');
	}

	function doSpace()
	{
		$uid=requestGet('uid',TYPE_INT);
		if ($uid<1) {
			artMessage('request_error',URL_HOME);
		}
		$profile=$this->Auser->getUser($uid);
		if (!$profile) {
			artMessage('user_notexists',URL_HOME);
		}

		assign_var('page_title',$profile['uname'].'_'.lang('user_space').'_');
		assign_var('page_keywords',$profile['uname'].',');
		assign_var('page_description',$profile['uname']);

		assign_var('uid',$uid);
		assign_var('profile',$profile);
		assign_var('usergroup',readCacheSystem('usergroup'.$profile['groupid']));
		template('userspace');
	}

	function doLogout()
	{
		if ($this->isLogin()) {
			$this->Auser->logout();
			artMessage(lang('logout_successed'),getUrlReferer());
		}
		artMessage('request_error','index.php');
	}

	function doChecklogin()
	{
		if($this->user->isLogin() ){
			xmlMessage('logged');
		}

		if(checkToken(URL_POST,-1))
		{
			$lastupdate=0;
			$maxcount=(int)WSKM::getConfig('loginFailedCount');
			$logincount=(int)$this->loginIsFailed($lastupdate);
			if ($maxcount && $logincount >= $maxcount) {
				$lastupdate=round(((int)WSKM::getConfig('loginFailedHold')-(WSKM_TIME - (int)$lastupdate))/60);
				xmlMessage(sprintf(lang('login_fatally'),$maxcount,$lastupdate));
			}

			$vcode=requestPost('vcode',TYPE_STRING,4);
			$uname=requestPost('uname');
			$password=requestPost('upw');
			$autologin=requestPost('auto_login',TYPE_INT);

			if(!$this->user->checkVcode($vcode)){
				xmlMessage('vcode_inputerr');
			}

			$loginnotice=$maxcount > 0?sprintf(lang('login_allow_notice'),$maxcount - $logincount -1):'';
			$res=$this->Auser->login($uname,$password,array('save'=>$autologin));
			if($res == LOGIN_ERROR_NONE){
				xmlMessage('[ok]');
			}
			elseif($res == LOGIN_ERROR_NOTEXIST){
				$this->loginUpdateFailed();
				xmlMessage(lang('uname_noexists').$loginnotice );
			}
			elseif($res == LOGIN_ERROR_PASSWORD){
				$this->loginUpdateFailed();
				xmlMessage(lang('password_error').$loginnotice );
			}
		}

		xmlMessage('unknow_error');
	}

	function doCheckreg()
	{
		if($this->user->isLogin() ){
			xmlMessage('Logged');
		}

		if (!(bool)WSKM::getConfig('isAllowReg')) {
			xmlMessage('reg_disablenotice');
		}

		if(checkToken(URL_POST,-1))
		{
			if ((bool)WSKM::getConfig('isRules') && !requestPost('registerms',TYPE_INT) ) {
				xmlMessage('reg_terms_agree');
			}

			$vcode=requestPost('regvcode',TYPE_STRING);
			$uname=requestPost('reguname',TYPE_STRING);
			$password=requestPost('regupw',TYPE_STRING);
			$password2=requestPost('regupw2',TYPE_STRING);
			$email=requestPost('regemail',TYPE_STRING);

			if(!$this->user->checkVcode($vcode)){
				xmlMessage('vcode_inputerr');
			}

			if(strlen($password)<6){
				xmlMessage('password_length');
			}
			elseif($password != $password2) {
				xmlMessage('password_nomatch');
			}
			elseif(!$password || $password != addslashes($password)) {
				xmlMessage('password_illegal');
			}

			$res=$this->Auser->checkUserName($uname);
			if ($res==REGISTER_NAME_ILLEGAL){
				xmlMessage('uname_illegal');
			}elseif ($res==REGISTER_NAME_PROTECT){
				xmlMessage('uname_protect');
			}elseif ($res==REGISTER_NAME_EXISTS){
				xmlMessage('uname_exists');
			}

			$res=$this->Auser->checkEmail($email);
			if ($res==REGISTER_EMAIL_ERROR){
				xmlMessage('email_format');
			}elseif ($res==REGISTER_EMAIL_PROTECT){
				xmlMessage('email_protect');
			}elseif ($res==REGISTER_EMAIL_EXISTS){
				xmlMessage('email_exists');
			}

			if(!$this->Auser->addUser($uname,$password,$email)){
				xmlMessage('reg_err');
			}

			if ((bool)WSKM::getConfig('sendEmailVerify')) {
				xmlMessage('[ok]2');
			}
			xmlMessage('[ok]');

		}
		xmlMessage('unknow_error');
	}

	function doEmailForVerify(){
		if (!(bool)WSKM::getConfig('isEmailVerify')) {
			artMessage('request_error');
		}

		if (!$this->isLogin()) {
			artMessage('login_must',URL_HOME);
		}

		if ($this->user->isEmailVerify()) {
			artMessage('verifyed',URL_HOME);
		}
		usingArtFun('user');
		$key=emailVerifyKey();
		$hash=requestGet('hash');

		if ($hash==$key ) {
			$array=array('emailverify'=>1);
			if (GROUPID==6) {
				$array['groupid']=7;
			}
			if($this->db->update(TABLE_PREFIX.'users',$array," uid= '{$this->getUid()}'  ") ==false){
				artMessage('emailverify_err');
			}
			artMessage('emailverify_ok',mvcUrl('',array('user')));
		}
		artMessage('request_expired');
	}

	function doEmailVerify(){
		$lastsend=wskm_cookie::getValue('lastmverify');
		if ($lastsend && WSKM_TIME - $lastsend < 43200) {
			xmlMessage('emailverify_wait');
		}

		usingArtFun('user');
		$res=emailVerify();
		if ($res !== false && empty($res)) {
			wskm_cookie::write('lastmverify',WSKM_TIME,43200);
			xmlMessage('emailverify_sendok');
		}
		xmlMessage('emailverify_senderr');
	}

	private function loginUpdateFailed()
	{
		$this->db->exec('UPDATE '.TABLE_PREFIX."loginlog SET count=count+1,dateline='".WSKM_TIME."' WHERE ip='{$this->user->getIp()}'  ");
	}

	private function loginIsFailed(&$lastupdate)
	{
		$fhold=(int)WSKM::getConfig('loginFailedHold');
		$sql="SELECT count,dateline FROM ".TABLE_PREFIX."loginlog WHERE ip='{$this->user->getIp()}' ";
		$isupdate=false;
		$time=WSKM_TIME;

		$count=$this->db->fetch_first($sql);
		$lastupdate=(int)$count['dateline'];
		if($count == false || ($time-$count['dateline']) >$fhold)
		{
			$isupdate=true;
		}

		if ($isupdate) {
			$this->db->exec('REPLACE INTO '.TABLE_PREFIX."loginlog  (ip,count,dateline)VALUES('{$this->user->getIp()}',1,'{$time}') ");
			$this->db->exec('DELETE FROM '.TABLE_PREFIX."loginlog WHERE dateline<$time-$fhold");
			return 1;
		}

		return $count['count'];
	}

}

?>