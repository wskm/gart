<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [WskmPHP] www.wskmphp.com 
 * $Id: tiger.php 16 2010-07-11 14:06:18Z ws99 $ 
 */

!defined('IN_WSKM') && exit('Access Denied');

define('OUTPAGE_JS',0);
define('OUTPAGE_WEB',1);
class wskm_page_tiger{
	public $PAGE_KEY='';

	public $message='';
	public $pagelist='';
	public $pagetype=0;
	public $pagecount=1;
	public $page=1;

	function outHtml($tid){
		$html='';
		if ($this->pagetype == OUTPAGE_WEB) {	
			$html=multiPage($this->pagecount,$this->page,array('news','show',array('id'=>$tid)),1);
		}
		
		return $html;
	}
	
	function factory(&$txt,$type){
		$this->message=&$txt;
		$this->pagetype=$type;
		$this->init();

		if ($this->pagecount == 1) {
			return ;
		}

		$page=(int)requestGet('page');
		$page = max(1,min($page,$this->pagecount));
		$this->page=$page;

		if ($type==OUTPAGE_JS) {
			$this->pageJs();
		}
		elseif ($type==OUTPAGE_WEB){
			$this->pageWeb();
		}
	}
	function init(){
		if ($this->pagetype==OUTPAGE_JS || $this->pagetype==OUTPAGE_WEB) {
			$list=explode($this->PAGE_KEY,$this->message);
			$this->pagecount=count($list);
			
			if ($this->pagecount > 1) {
				$this->pagelist= array_filter($list,'trim');
				$this->pagecount=count($this->pagelist);	
			}
			unset($list);
		}
	}

	function pageJs(){
		$temp=$style='';
		$i=1;
		foreach ($this->pagelist as $index=>$msg){
			if ($i == $this->page) {
				$style='';
			}else{
				$style=' style="display:none" ';
			}
			$temp.='<div id="artpage_'.($index+1).'" '.$style.' class="artpage_wrap" >'.$msg.'</div>';
			$i++;
		}

		$this->message=jsWriter("var ARTPAGE_COUNT={$this->pagecount};var ARTPAGE_PAGE={$this->page};",true).$temp;
	}

	function pageWeb(){
		$this->message=$this->pagelist[$this->page-1];
	}

}

?>