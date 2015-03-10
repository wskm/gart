<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [Gart] www.wskmphp.com 
 * $Id: class_page.php 280 2010-12-26 06:05:21Z ws99 $ 
 */

!defined('IN_ART') && exit('Access Denied');

class art_page extends wskm_page_abstract
{
	private $isHtml=false;
	private $isHome=false;
	public function isHTML($or=true){
		$this->isHtml=(bool)$or;
	}
	public function isHome($or=true){
		$this->isHome=(bool)$or;
	}
	
	public function __construct()
	{
		parent::__construct();

		$this->page_start();
		$this->page_load();

	}

	public function __destruct(){

		if (wskm_note::read('cache')) {
			ob_clean();
			$ucache=array_unique((array)wskm_note::read('cache'));
			if ($ucache) {
				$isall=false;
				foreach ($ucache as $cachename){
					if ($cachename=='settings') {
						$isall=true;
						break;
					}
				}

				if ($isall) {
					art_cache::updateAll();
				}else{
					foreach ($ucache as $cachename){
						art_cache::update($cachename);
					}
				}
			}else{
				exit('unknown');
			}

			exit('Please refresh the page!');
		}
				
		$this->page_end();
		if (IS_HTML && $this->isHtml) {
			$this->cacheHtml();
		}
	}
	public function page_end(){}

	public function page_start(){
		art_hook::initUtil();
	}

	public function cacheHtml(){
		$url=wskm_http_url::getSingle();
		$url->isHttp();
		$key=$url->htmlKey();
		if (!$key) {
			return ;
		}

		$htmlpath=ART_ROOT.'html'.DS.$key.'.html';
	
		if (!UPDATE_HTML && file_exists($htmlpath) && WSKM_TIME < (int)WSKM::getConfig('cacheHtmlTime') + filemtime($htmlpath)) {
			return ;
		}
		
		$value=ob_get_contents();
		$tourl=str_replace(array('&updatehtml=1','?updatehtml=1'),'',PAGE_SELF);
		$value.= jsWriter('var r=Math.random()*10000000;document.write(\'<script type="text/javascript" src="'.ART_URL.'wskm.php?act=checkhtml&r=\'+ r +\'&url='.rawurlencode($tourl).'&key='.rawurlencode($key).(MVC_APP=='news'?'&update='.requestGet('id',TYPE_INT):'').'" ><\/script>\');',true);
		wskm_io::fWrite($htmlpath,$value); 
		if (!$this->isHome) {
			art_cache::update('static');
			if (MVC_APP=='category') {
				art_cache::update('category');
			}
		}

	}

	public function page_load(){}



}

?>