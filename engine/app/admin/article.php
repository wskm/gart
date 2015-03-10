<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [Gart] www.wskmphp.com 
 * $Id: article.php 151 2010-10-20 06:24:21Z ws99 $ 
 */

!defined('IN_ART') && exit('Access Denied');

usingArtFun('article');
class app_admin_article extends admin_common
{
	function load()
	{
		$this->model=usingAdminModel('article');
		loadCacheSystem('category');
		loadLang('admin_article');
	}

	function doIndex()
	{
		$articles= $this->model->getAdminTitles();
		$keys=$articles['keys'];

		$categoryobject=usingAdminModel('category');
		$select_options=$categoryobject->getSelectOption($categoryobject->getList(),$keys['cid']?(int)$keys['cid']:0);

		if (empty($select_options)) {
			adminMessage('category_empty','index.php?wskm=category');
		}

		$url="index.php?wskm=article";
		$url .='&'.http_build_query($keys);
		$htmlpage=multiPage($articles['count'],$articles['page'],$url,$this->model->adminPageCount);

		if (!$keys['status'] && !$keys['digest']) {
			assign_var('isallnews',1);
		}

		assign_var('skeys',$keys);
		assign_var('htmlpage',$htmlpage);
		assign_var('select_options',$select_options);
		assign_var('articles',$articles['list']);
		assign_var('cyclejs',array2jsarr((array)unserialize(getDbCache('cyclepic'))));
		adminTemplate('article');
	}

	function doAjax_edit()
	{
		$id=(int)requestGet('id');
		if ($id < 1) {
			return ;
		}
		$column=requestGet('column');
		$value=requestGet('value');


		if (in_array($column,array('name','status','displaysort'))) {
			if ($column=='displaysort' ){
				$value=(int)$value;
			}
		}
		else {
			return ;
		}
		if($this->model->updateTitle(array($column=>$value),' aid='.(int)$id))
		{
			echo 'true';
		}
	}

	function doAdd()
	{
		$article=array();

		if (checkToken()) {

			$temp=requestPost('titlestyle',TYPE_ARRAY);
			if (strtoupper($temp['color']=='FFFFFF')) {
				$temp['color']='';
			}else{
				$temp['color']='#'.$temp['color'];
			}

			$tstyletemp='';
			if (count($temp)>0) {
				if ($temp['color']) {
					$tstyletemp.='color:'.$temp['color'].';';
				}
				if ($temp['font-size']) {
					$tstyletemp.='font-size:'.$temp['font-size'].';';
				}
				if ($temp['font-weight']) {
					$tstyletemp.='font-weight:bold;';
				}
				if ($temp['font-style']) {
					$tstyletemp.='font-style:italic;';
				}
				if ($temp['text-decoration']) {
					$tstyletemp.='text-decoration:underline;';
				}
				unset($temp);
			}

			$kindid=requestPost('kindid',TYPE_INT);
			$cid=requestPost('cid',TYPE_INT);
			$title=requestPost('title',TYPE_STRING,80);
			$status=requestPost('status',TYPE_INT);
			$message=requestPost('message',TYPE_HTMLTEXT);
			$others=requestPost('others',TYPE_ARRAY);

			if (empty($title) || empty($cid) || empty($message)) {
				adminMessage('article_input_err1',-1);
			}

			if (count($others) < 1) {
				$kindid=0;
			}

			$titles=array(
			'cid'=>$cid,
			'kindid'=>$kindid,
			'uid'=>$this->getUid(),
			'uname'=>$this->getUname(),
			'dateline'=>WSKM_TIME,
			'title'=>$title,
			'titlestyle'=>$tstyletemp,
			'cover'=>requestPost('coverthumb',TYPE_STRING,100),
			'summary'=>requestPost('summary',TYPE_HTMLTEXT,250),
			'digest'=>requestPost('digest',TYPE_INT),
			'author'=>requestPost('author',TYPE_STRING,20),
			'fromname'=>requestPost('fromname',TYPE_STRING,20),
			'fromurl'=>str_replace('http://','', requestPost('fromurl',TYPE_STRING,150)),
			'tags'=>$tags,
			'replystate'=>requestPost('replystate',TYPE_INT),
			'status'=>$status,
			);

			if ($titles['summary']) {
				$titles['summary']=toText($titles['summary']);
			}

			$aid=$this->model->insertTitle($titles);
			if ($aid<1) {
				adminMessage('title_add_bad',-1);
			}

			if ((bool)WSKM::getConfig('articleUrlAbsolute')) {
				$message=$this->model->urlAbsolute($message);
			}

			$tags='';
			$articlemessage=array(
			'aid'=>$aid,
			'dateline'=>WSKM_TIME,
			'message'=>$message,
			'pagetype'=>requestPost('pagetype',TYPE_INT)
			);

			if(!$this->model->insertMessage($articlemessage)){
				adminMessage('article_add_bad',-1);
			}

			unset($titles,$articlemessage);

			$attachclass=null;
			$thumbattachid=requestPost('thumbattachid',TYPE_INT);
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

			if ($kindid >0 && $others) {
				
			}

			$tags=requestPost('tags',TYPE_STRING);
			if ($tags) {
				$this->model->setTags($tags,$aid);
			}
			$this->model->sync($aid);
			adminMessage('article_add_ok','index.php?wskm=article');
		}
		else {
			$article["titlestyle"]=titleFormat($article["titlestyle"]);
			$actiontitle=lang('article_add');
			$kindid=(int)requestGet('kindid');
			$others=array();
			if ($kindid==1) {
				$actiontitle=lang('poll_add');
			}
			assign_var('others',$others);

			usingArtFun('filesystem');

			$categoryObject=usingAdminModel('category');
			$select_options=$categoryObject->getSelectOption($categoryObject->getList(),0);

			$editfrom='message';
			$htmledit=getHtmlEditor($editfrom,$article['message']);

			$article['status']=(int)WSKM::getConfig('articleStatus');
			$article['replystate']=(int)WSKM::getConfig('articleReplyState');
			$article['digest']=0;

			$attachclass=usingAdminModel('attachment');
			$attachsuse=$attachclass->getUseAttachment();
			assign_var('attachsuse',$attachsuse);

			assign_var('acttitle',$actiontitle);
			assign_var('editpagetype',(bool)WSKM::getConfig('articlePageType'));
			assign_var('editname',$editfrom);
			assign_var('wskmaction','add');
			assign_var('htmledit',$htmledit);
			assign_var('article',$article);
			assign_var('select_options',$select_options);
			assign_var('attachMaxSize',WSKM::getConfig('attachMaxSize'));
			assign_var('uploadhash',md5(substr(md5(ART_KEY), 8).UID));
			assign_var('kindid',$kindid);
			adminTemplate('article_info');
		}
	}

	function doEdit()
	{
		if (checkToken()) {
			$temp=requestPost('titlestyle',TYPE_ARRAY);
			if (strtoupper($temp['color']=='FFFFFF')) {
				$temp['color']='';
			}else{
				$temp['color']='#'.$temp['color'];
			}

			$tstyletemp='';
			if (count($temp)>0) {
				if ($temp['color']) {
					$tstyletemp.='color:'.$temp['color'].';';
				}
				if ($temp['font-size']) {
					$tstyletemp.='font-size:'.$temp['font-size'].';';
				}
				if ($temp['font-weight']) {
					$tstyletemp.='font-weight:bold;';
				}
				if ($temp['font-style']) {
					$tstyletemp.='font-style:italic;';
				}
				if ($temp['text-decoration']) {
					$tstyletemp.='text-decoration:underline;';
				}
				unset($temp);
			}

			$kindid=requestPost('kindid',TYPE_INT);
			$aid=requestPost('aid',TYPE_INT);
			$cid=requestPost('cid',TYPE_INT);
			$title=requestPost('title',TYPE_STRING,80);
			$status=requestPost('status',TYPE_INT);
			$message=requestPost('message',TYPE_HTMLTEXT);
			$pagetype=requestPost('pagetype',TYPE_INT);

			if (empty($title) || empty($cid) || empty($message)) {
				adminMessage('article_input_err1',-1);
			}

			$titles=array(
			'aid'=>$aid,
			'cid'=>$cid,
			'title'=>$title,
			'titlestyle'=>$tstyletemp,
			'cover'=>requestPost('coverthumb',TYPE_STRING,100),
			'summary'=>requestPost('summary',TYPE_HTMLTEXT,300),
			'digest'=>requestPost('digest',TYPE_INT),
			'author'=>requestPost('author',TYPE_STRING,20),
			'fromname'=>requestPost('fromname',TYPE_STRING,20),
			'fromurl'=>str_replace('http://','', requestPost('fromurl',TYPE_STRING,150)),
			'tags'=>'',
			'replystate'=>requestPost('replystate',TYPE_INT),
			'status'=>$status,
			);

			if ($titles['summary']) {
				$titles['summary']=toText($titles['summary']);
			}

			if(!$this->model->updateTitleById($titles,$aid)){
				adminMessage('article_edittitle_bad',-1);
			}

			if ((bool)WSKM::getConfig('articleUrlAbsolute')) {
				$message=$this->model->urlAbsolute($message);
			}

			$articlemessage=array(
			'aid'=>$aid,
			'message'=>$message,
			'pagetype'=>$pagetype,
			);

			if(!$this->model->updateMessage($articlemessage,$aid) ){
				adminMessage('article_edit_bad',-1);
			}
			unset($titles,$articlemessage);

			$attachclass=null;
			$thumbattachid=requestPost('thumbattachid',TYPE_INT);
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
			$this->model->setTags($tags,$aid);

			$others=requestPost('others',TYPE_ARRAY);
			if ($kindid >0 && $others) {
				
			}

			$this->model->sync($aid);
			adminMessage('article_edit_ok','index.php?wskm=article&act=edit&id='.$aid);

		}
		else {
			$aid=(int)requestGet('id');
			if ($aid<1) {
				showMessage('request_error');
			}

			$article=$this->model->getAdminArticleFullInfo($aid);
			$article["titlestyle"]=titleFormat($article["titlestyle"]);

			usingArtFun('filesystem');
			$categoryObject=usingAdminModel('category');
			$select_options=$categoryObject->getSelectOption($categoryObject->getList(),$article['cid']);

			$editfrom='message';
			$htmledit=getHtmlEditor($editfrom,$article['message']);

			$attachclass=usingAdminModel('attachment');
			$attachs=$attachclass->getAttachment($aid);
			$attachsuse=$attachclass->getUseAttachment();

			$others=array();
			$actiontitle=lang('article_edit');

			assign_var('acttitle',$actiontitle);
			assign_var('editname',$editfrom);
			assign_var('editpagetype',(bool)$article['pagetype']);
			assign_var('attachs',$attachs);
			assign_var('attachsuse',$attachsuse);
			assign_var('titlestyle',$titlestyle);

			assign_var('aid',$aid);
			assign_var('cid',$article['cid']);
			assign_var('kindid',$article['kindid']);
			assign_var('htmledit',$htmledit);
			assign_var('article',$article);
			assign_var('select_options',$select_options);
			assign_var('wskmaction','edit');
			assign_var('attachMaxSize',WSKM::getConfig('attachMaxSize'));
			assign_var('uploadhash',md5(substr(md5(ART_KEY), 8).UID));			
			assign_var('others',$others);
			adminTemplate('article_info');
		}
	}

	function doDel(){
		$aid=requestGet('id',TYPE_INT);
		if ($aid>0) {
			if(!$this->model->updateTitle(array('status'=>0),' aid='.(int)$aid)){
				adminMessage('del_error',-1);
			}
			$this->model->sync($aid);
			adminMessage('del_ok','index.php?wskm=article');
		}
		adminMessage('request_error','index.php');
	}

	function doBatch()
	{
		if (checkToken()) {
			$acttype=requestPost('acttype');
			$selects=requestPost('selectid',TYPE_ARRAY);
			$movecid=requestPost('movecid',TYPE_INT);
			if (count($selects) <1) {
				adminMessage('select_onetitle',-1);
			}

			$column=$value='';
			$isother=false;
			if (in_array($acttype,array('del','audit','normal'))) {
				$column='status';
				$value=$acttype=='del'?0:($acttype=='audit'?2:1);
			}
			elseif(in_array($acttype,array('digest_yes','digest_no'))){
				$column='digest';
				$value= $acttype=='digest_no' ?0:1;
			}
			elseif($acttype=='category'){
				if ($movecid <1) {
					adminMessage('select_onecategory',-1);
				}
				$column='cid';
				$value=$movecid;
			}
			elseif($acttype=='completelydel'){
				$aids=array();
				foreach ($selects as $id){
					$aids[]=$id;
				}
				$aids2 = implode(',', $aids);
				$msg=$this->model->deleteComplete($aids2);
				if($msg !== true){
					adminMessage($msg,-1);
				}

				$this->model->sync($aids);
				if (IS_HTML) {
					usingArtClass('cache');
					art_cache::deleteHtml($aids);
				}
				adminMessage('del_ok','index.php?wskm=article');
			}elseif ($acttype=='bignews') {
				$isother=true;
				$big=$selects[0];
				setDbCache('bignews',$big);
			}elseif ($acttype=='smallnews') {
				$isother=true;
				$selects=array_slice($selects,0,3);
				setDbCache('smallnews',serialize($selects));
			}elseif (in_array($acttype,array('cyclepic','cyclepic_no')) ) {
				$isother=true;
				$oldpic=(array)unserialize(getDbCache('cyclepic'));
				if ($acttype=='cyclepic') {
					$oldpic=array_unique(array_merge($oldpic,$selects));
				}else{
					$oldpic=array_diff($oldpic,$selects);
				}

				$cyclepic=array_slice($oldpic,0,10);
				setDbCache('cyclepic',serialize($cyclepic));
			}

			if (!$isother) {
				if(empty($column) || $value ==='' ){
					adminMessage('request_error','index.php');
				}

				foreach ($selects as $id){
					if(!$this->model->updateTitle(array($column=>$value),' aid='.(int)$id)){
						adminMessage('edit_error',-1);
					}
				}
			}
			$this->model->sync($isother?'':$selects);
			adminMessage('edit_ok',getUrlReferer());
		}
	}

	function getPostConvert($issys)
	{
		$convert=array();
		$postname=array_unique(array_filter(requestGet('convertname')));
		$posttype=requestGet('converttype');
		$postvalue=requestGet('convertvalue');
		$index=0;
		foreach ($postname as $k=>$v){
			if ($issys || $posttype[$k] == 'text' || $posttype[$k] == 'textarea' || !empty($postvalue[$k]) ){
				$convert[$index]['name']=trim($v);
				$convert[$index]['type']=$posttype[$k];
				//if ($posttype[$k] == 'select' || $posttype[$k] == 'checkbox' || $posttype[$k] == 'radio' ) {
				//	$convert[$index]['value']=array_split("\n",$postvalue[$k]);
				//}
				$convert[$index]['value']=trim(strip_tags($postvalue[$k]));
				$index++;
			}
		}
		return serialize($convert);
	}

	public function doComment(){
		$hand=requestGet('hand',TYPE_ALNUM);
		if ($hand=='batch' && checkToken()) {
			$selectacttype=requestPost('acttype');
			$selects=requestPost('selectid',TYPE_ARRAY);
			if (count($selects) <1) {
				adminMessage('select_one',-1);
			}

			if ($selectacttype=='del') {
				if (!$this->model->deleteComment($selects)) {
					adminMessage('del_error',-1);
				}
				adminMessage('del_ok',getUrlReferer());
			}elseif ($selectacttype=='verify' || $selectacttype=='normal'){
				$status=$selectacttype=='verify' ?0:1;
				foreach ($selects as $commentid){
					if( !$this->model->updateComment(array('status'=>$status),(int)$commentid) ) {
						adminMessage('edit_error',-1);
					}

				}
				adminMessage('edit_ok',getUrlReferer());
			}
			adminMessage('request_error');
		}elseif ($hand=='edit'){
			if (checkToken()) {
				$aid=requestPost('aid',TYPE_INT);
				$commentid=requestPost('commentid',TYPE_INT);
				if ($commentid<1) {
					adminMessage('request_error',URL_HOME);
				}
				$status=requestPost('status',TYPE_INT);
				$isdel=requestPost('isdel',TYPE_BOOL);

				if ($isdel) {
					if (!$this->model->deleteComment($commentid,$aid)) {
						adminMessage('del_error',-1);
					}
					adminMessage('del_ok','index.php?wskm=article&act=comment');
				}

				if( !$this->model->updateComment(array('status'=>$status),$commentid) ) {
					adminMessage('edit_error',-1);
				}
				adminMessage('edit_ok','index.php?wskm=article&act=comment');

			}else {
				$commentid=requestGet('id');
				if ($commentid<1) {
					adminMessage('request_error',URL_HOME);
				}
				$info=$this->model->getComment($commentid);

				assign_var('commentid',$commentid);
				assign_var('info',$info);
				adminTemplate('comment_info');
			}
			exit();
		}else{
			$data=$this->model->getCommentList();
			$url="index.php?wskm=article&act=comment";
			$url .='&'.http_build_query($data['keys']);
			$htmlpage=multiPage($data['count'],$data['page'],$url,$this->model->adminPageCount);

			assign_var('list',$data['list']);
			assign_var('status',$data['keys']['status']);
			assign_var('htmlpage',$htmlpage);
			adminTemplate('comment');
		}
	}

	function doFilterWord(){
		$model=usingAdminModel('word');
		if (checkToken()) {
			//del
			$list=requestPost('list',TYPE_ARRAY);
			$dels=requestPost('del',TYPE_ARRAY);
			foreach ($dels as $id){
				if(!$model->deleteFilterWord((int)$id)){
					adminMessage('del_error',-1);
				}
			}

			//edit
			$names=array();
			foreach ($list as $wid=>$tempi){
				if (in_array($wid,$dels)) {
					continue;
				}

				$word=trim($tempi['word']);
				$replace=trim($tempi['replace']);
				if (strlen($word) < 2 || strlen($replace) < 2) {
					adminMessage('word_tooshort',-1);
				}
				$names[]=$word;
				if(!$model->updateFilterWord(array('word'=>$word,'replace'=>$replace),(int)$wid)){
					adminMessage('edit_error','index.php?wskm=article&act=filterword');
				}
			}

			//new
			$newnames=requestPost('newnames',TYPE_ARRAY);
			$newreplaces=requestPost('newreplaces',TYPE_ARRAY);
			foreach ($newnames as $index=>$name){
				if (in_array($name,$names)) {
					continue;
				}
				$name=trim($name);
				$replace=trim($newreplaces[$index]);
				if (strlen($name) < 2 || strlen($replace) < 2) {
					adminMessage('word_tooshort',-1);
				}

				if ($name && $replace) {
					if(!$model->insertFilterWord(array('word'=>$name,'replace'=>$replace))){
						adminMessage('insert_error','index.php?wskm=article&act=filterword');
					}
				}
			}

			$model->updateCache();
			adminMessage('edit_ok','index.php?wskm=article&act=filterword');
		}else{

			assign_var('list',$model->getFilterWords());
			adminTemplate('filterword');
		}
	}

	function doKinds()
	{
		if (checkToken()) {
			$kinds=requestPost('kinds',TYPE_ARRAY);

			$names=array();
			foreach ($kinds as $kid=>$kind){
				$name=trim($kind['name']);
				$names[]=$name;
				$this->model->updateArticleKinds((int)$kid,$name,(int)$kind['sort'],(int)$kind['status']);
			}

			$newsorts=requestPost('newsorts',TYPE_ARRAY);
			$newnames=requestPost('newnames',TYPE_ARRAY);
			$newstatus=requestPost('newstatus',TYPE_ARRAY);
			foreach ($newnames as $index=>$name){
				if (in_array($name,$names)) {
					continue;
				}
				$name=trim($name);
				if ($name) {
					$this->model->insertArticleKinds($name,(int)$newsorts[$index],trim($newstatus[$index]));
				}
			}

			adminMessage('articletypes_editok','index.php?wskm=article&act=kinds');
		}else{
			assign_var('kinds',$this->model->getArticleKinds());
			adminTemplate('article_kinds');
		}
	}
}

function clearTitleTag($title){
	return preg_replace("/\<[^\>]+\>/",'',$title);
}

function titleFormat($styles){
	$formats=array(
	'color'=>'','font-size'=>'','font-weight'=>'','font-style'=>'','text-decoration'=>'',
	);

	$temp=explode(';',$styles);
	$styles=array();
	foreach ($temp as $style){
		if ($style) {
			$temp2=explode(':',$style);
			if (count($temp2)==2) {
				$styles[$temp2[0]]=$temp2[1];
			}
		}
	}

	return array_format_set($styles,$formats);
}

function array_format_set($data,$formats){
	foreach ($data as $key=>$tempi){
		if (!isset($formats[$key])) {
			unset($data[$key]);
		}
	}
	foreach ($formats as $key =>$tempi){
		if (!isset($data[$key])) {
			$data[$key]=$tempi;
		}
	}

	return $data;
}


?>