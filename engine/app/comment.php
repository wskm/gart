<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [Gart] www.wskmphp.com 
 * $Id: comment.php 259 2010-11-29 09:41:49Z ws99 $ 
 */

!defined('IN_ART') && exit('Access Denied');

class app_comment extends art_page
{
	function doAdd(){
		if (!checkToken()) {			
			xmlMessage('request_error');
		}

		$aid=requestPost('aid',TYPE_INT);
		if ($aid < 1) {
			xmlMessage('request_error');
		}

		$vcode=requestPost('vcode',TYPE_ALNUM);
		if(!$this->user->checkVcode($vcode)){
			xmlMessage('vcode_inputerr');
		}
		
		$replywait=(int)WSKM::getConfig('replyWait');
		$lastreply=(int)wskm_cookie::getValue('lastreply');
		if ($replywait && wskm_cookie::isExist('lastreply')  && (WSKM_TIME-$lastreply < $replywait) ) {
			xmlMessage(sprintf(lang('replay_toofast'),$replywait-(WSKM_TIME-$lastreply)));
		}

		$rquesturl=requestPost('request_url',TYPE_STRING);
		$content=requestPost('content',TYPE_HTMLTEXT);
		$content=ubb2html($content);

		if (strlen($content) < 3 ) {
			xmlMessage('comment_tooshort');
		}
		if (strlen($content) > 3000  ) {
			xmlMessage('comment_toolong');
		}

		$this->model=usingModel('comment');
		$article=$this->model->getArticleInfo($aid);

		$anonym=$isanonym='';
		if ($article['replystate'] == 2) {
			$isanonym=requestPost('isanonym',TYPE_BOOL);
			$anonym=requestPost('anonym',TYPE_STRING,30);
		}

		if ($article['replystate']==0 || $article['status'] !=1 ) {
			xmlMessage('comment_notallowed');
		}
		elseif ($article['replystate']==1 && $this->getUid() <1) {
			xmlMessage('comment_needlogin');
		}
		elseif ($article['replystate'] == 2) {
			if ($this->isLogin() && !$isanonym) {
				$anonym='';
			}elseif(empty($anonym)){
				$anonym=lang('visitor');
			}			
		}
		
		$fword=readCacheSystem('filterword');
		if ($fword) {
			if ($anonym) {
				$anonym=str_replace($fword['word'],$fword['replace'],$anonym);
			}
			
			$content=str_replace($fword['word'],$fword['replace'],$content);
		}

		$comment=array(
		'aid'=>$aid,
		'message'=>$content,
		'uid'=>$this->getUid(),
		'uname'=>$this->getUname(),
		'anonym'=>$anonym,
		'dateline'=>WSKM_TIME,
		'ip'=>USER_IP,
		'status'=>$this->user->isAdmin()?1:(int)WSKM::getConfig('commentStatus'),
		);

		$commentid=$this->model->insert($comment);
		if (!$commentid) {
			xmlMessage('sumbit_err');
		}
		$this->user->vcode_new();
		
		if ($comment['status']==0) {
			xmlMessage('comment_needaudit');
		}
		
		wskm_cookie::write('lastreply',WSKM_TIME);
		xmlMessage('[ok]');
	}

	function doList()
	{
		$aid=(int)requestGet('id');
		if ($aid < 1) {
			artMessage('request_error','index.php');
		}

		$this->model=usingModel('comment');
		$article=$this->model->getArticleInfo($aid);
		if ($article['replystate']==0 || $article['status'] != 1 ) {
			artMessage('comment_notallowed',-1);
		}

		$infodata=$this->model->getList($aid);
		$htmlpage=multiPage($infodata['count'],$infodata['page'],array('comment','list',array('id'=>$aid,'page'=>$infodata['page'])),$this->model->commentPageCount);
		assign_var('needlogin',$article['replystate']==1 && !$this->isLogin() ? true:false);
		assign_var('comments',$infodata['list']);
		assign_var('htmlpage',$htmlpage);
		assign_var('floori',$infodata['count']-($infodata['page']-1)*$this->model->commentPageCount);
		assign_var('article',$article);
		assign_var('aid',$aid);
		assign_var('news',$article);
		assign_var('page_title',lang('comment').':'.$article['title'].'_');
		template('comment');
	}

}


function ubb2html($html){
	$html=wkHtmlspecialchars($html);

	if (strpos($html, '[/quote]') !== false) {
		$html = preg_replace("/\s*\[quote\][\n\r]*(.+?)[\n\r]*\[\/quote\]\s*/is", "<div class=\"ubbquote\">\\1</div>", $html);
	}

	$html = str_replace(
	array(
	'[/color]', '[/size]', '[/font]', '[/align]', '[b]', '[/b]', '[s]', '[/s]', '[hr]', '[/p]','[i]', '[/i]', '[u]', '[/u]','[/float]'
	),
	array(
	'</font>', '</font>', '</font>', '</p>', '<strong>', '</strong>', '<strike>', '</strike>', '<hr class="solidline" />', '</p>','<i>','</i>', '<u>', '</u>', '</span>'
	),
	preg_replace(array(
	"/\[color=([#\w]+?)\]/i",
	"/\[size=(\d+?)\]/i",
	"/\[size=(\d+(\.\d+)?(px|pt|in|cm|mm|pc|em|ex|%)+?)\]/i",
	"/\[font=([^\[\<]+?)\]/i",
	"/\[align=(left|center|right)\]/i",
	"/\[float=(left|right)\]/i"

	), array(
	"<font color=\"\\1\">",
	"<font size=\"\\1\">",
	"<font style=\"font-size: \\1\">",
	"<font face=\"\\1 \">",
	"<p align=\"\\1\">",
	"<span style=\"float: \\1;\">"
	), $html));

	return nl2br(str_replace(array("\t", '   ', '  '), array('&nbsp; &nbsp; &nbsp; &nbsp; ', '&nbsp; &nbsp;', '&nbsp;&nbsp;'), $html));
}

?>