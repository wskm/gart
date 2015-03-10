<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [WskmPHP] www.wskmphp.com 
 * $Id: mvc.php 222 2010-11-22 09:20:03Z ws99 $ 
 */

!defined('IN_WSKM') && exit('Access Denied');

class wskm_http_url
{
	public $urlMode='';
	public $urlStyle='';
	public $urlArgsStyle='';
	public $urlExt='';
	public $appUrlName='';
	public $actUrlName='';

	public $appPrefix='';
	public $appDefault='';
	public $actPrefix ='';
	public $actDefault='';
	public $appClass='';
	public $actMethod='';

	public $appName='';
	public $actName='';
	public $urlArgv=array();
	public $mvcArgs=array();
	static $htmlArgs=array();
	public $htmlMvcArgs=array();
	public $isArgs=false;
	public $isHttp=false;

	function __construct()
	{		
		self::$instance=$this;
		$this->init_mvc_url();
	}
	
	public function isHttp($or=true){
		$this->isHttp=$or;
	}

	static $instance=null;
	static function getSingle()
	{
		if (!is_object(self::$instance)) {
			self::$instance=new wskm_http_url();
		}
		return self::$instance;
	}

	private function init_mvc_url()
	{
		$this->urlMode=URLMODE;
		$this->urlStyle=WSKM::getConfig('urlStyle');
		$this->urlArgsStyle=WSKM::getConfig('urlArgsStyle');
		$this->urlExt=trim(WSKM::getConfig('urlExt'));
		if($this->urlExt) $this->urlExt='.'.ltrim($this->urlExt,'.');

		$this->appUrlName=WSKM::getConfig('appUrlName');
		$this->actUrlName=WSKM::getConfig('actUrlName');
	}

	private function init_mvc_config()
	{
		$this->appDefault=WSKM::getConfig('appDefault');
		$this->appPrefix=WSKM::getConfig('appPrefix');

		$this->actDefault=WSKM::getConfig('actDefault');
		$this->actPrefix=WSKM::getConfig('actPrefix');

		$this->appClass='';
		$this->actMethod='';
	}

	public function mvc($querystr='')
	{
		static $first=false;
		$this->isHttp=true;
		if (!$first) {
			$this->init_mvc_config();
		}
		$first=true;

		$querystr=trim($querystr);
		if ($this->urlMode == URLMODE_PATH || $this->urlMode == URLMODE_REWR ) {
			$query=empty($querystr)?getUrlPathInfo():$querystr;
		}else{
			$query=empty($querystr)?$_SERVER["QUERY_STRING"]:$querystr;
		}
		
		$query=strtolower($query);
		switch ($this->urlMode)
		{
			case URLMODE_NONE:
				$this->mode_none('',true);
				break;
			case URLMODE_SIGN:
				$this->mode_sign($query,true);
				break;
			case URLMODE_PATH:
			case URLMODE_REWR:
				$query=ltrim($query,'/');
				$this->mode_pathinfo($query,true);
				break;
		}

		if (empty($this->appClass)) {
			$this->appClass=$this->appDefault;
		}

		if (empty($this->actMethod)) {
			$this->actMethod=$this->actDefault;
		}

		$this->appClass=wskm_filter::getValue($this->appClass,TYPE_ALNUM);
		$this->actMethod=wskm_filter::getValue($this->actMethod,TYPE_ALNUM);

		define('MVC_APP',strtolower($this->appClass));
		define('MVC_ACT',ucfirst(strtolower($this->actMethod)));
		$this->appClass=$this->appPrefix.MVC_APP;
		$this->actMethod=$this->actPrefix.MVC_ACT;
		
		if (IS_POST) {
			wskm_request::initPOST($_POST);
		}

		wskm_request::initGET($this->mvcArgs);
		if ($_GET && $this->urlMode != URLMODE_NONE) {
			wskm_request::addGET($_GET);
		}

		if (IS_HTML) {
			self::$htmlArgs=array('app'=>MVC_APP,'act'=>MVC_ACT);

			if ($this->mvcArgs) {
				if (isset($this->mvcArgs['id']) && $this->mvcArgs['id']) {
					self::$htmlArgs['id']=(int)$this->mvcArgs['id'];
				}

				if (isset($this->mvcArgs['page']) && $this->mvcArgs['page']) {
					self::$htmlArgs['page']=(int)$this->mvcArgs['page'];
				}
			}
		}
	}

	public function url($str,$argv=null,$baseurl='',$modetype='',$showhtml=true)
	{		
		static $cacheurl;
		$this->isHttp=false;
		$str=trim($str);
		$modetype= $modetype ? $modetype:$this->urlMode;

		$cachekey=$str.$modetype.$baseurl.print_r($argv,true);
		
		if (isset($cacheurl[$cachekey])) {
			return $cacheurl[$cachekey];
		}

		if (is_array($argv)) {
			$this->isArgs=true;
			$this->appName=isset($argv[0])?wskm_filter::getValue($argv[0],TYPE_ALNUM):'';
			$this->actName=isset($argv[1])?wskm_filter::getValue($argv[1],TYPE_ALNUM):'';
			$this->urlArgv=isset($argv[2])?(array)$argv[2]:array();
		}
		$url=$urlfull='';
		switch ($modetype)
		{
			case URLMODE_NONE:
				$url=$this->mode_none($str);	//?a=1
				$urlfull=empty($url)?'':'?'.$url;				
				break;
			case URLMODE_SIGN:
				$url=$this->mode_sign($str);	//?a-b.html
				$urlfull=empty($url)?'':'?'.$url.$this->urlExt;
				break;
			case URLMODE_PATH:
				$url=$this->mode_pathinfo($str);	//index.php/a/b
				if (empty($url)) {
					$urlfull='';
				}elseif($baseurl){
					if (!strExists($baseurl,URL_HOME)) {
						$baseurl=$baseurl.URL_HOME;
					}
					$urlfull=$baseurl.'/'.$url.$this->urlExt;
				}else{
					$urlfull=getBaseDir().URL_NAME.'/'.$url.$this->urlExt;
				}
				break;
			case URLMODE_REWR:
				$url=$this->mode_pathinfo($str);	//a/b.html
				if (empty($url)) {
					$urlfull='';
				}elseif($baseurl){
					$baseurl=rtrim(str_replace(URL_HOME,'',$baseurl),'/').'/';
					$urlfull=$baseurl.$url.$this->urlExt;
				}else{
					$urlfull=getBaseDir().$url.$this->urlExt;
				}
				
				break;		
		}

		if ($modetype != URLMODE_PATH && $modetype != URLMODE_REWR) {
			$urlfull=$baseurl==''?getBaseDir().$urlfull:$baseurl.$urlfull;
		}
		
		if (IS_HTML && (bool)$showhtml && $this->isNeedHtml()) {
			$this->htmlMvcArgs=array('app'=>$this->appName,'act'=>$this->actName);
			if (isset($this->urlArgv['id']) && $this->urlArgv['id']) {
				$this->htmlMvcArgs['id']=(int)$this->urlArgv['id'];
			}

			if (isset($this->urlArgv['page']) && $this->urlArgv['page']) {
				$this->htmlMvcArgs['page']=(int)$this->urlArgv['page'];
			}
			$urlfull=$this->html($urlfull);
		}

		$this->initUrlClear();
		$cacheurl[$cachekey]=$urlfull;
		return $urlfull;
	}

	public function html($path)
	{
		if (file_exists(ART_ROOT.'html'.DS.$this->htmlKey().'.html') ) {
			return ART_URL.'html/'.$this->htmlKey().'.html';
		}
		return $path;
	}
	
	private function initUrlClear()
	{
		$this->isArgs=false;
		$this->urlArgv=array();
		$this->htmlMvcArgs=array();
		$this->appName='';
		$this->actName='';
	}

	private function must()
	{
		if (empty($this->appName) && empty($this->actName)) {
			return false;
		}

		return true	;
	}

	private function parse_mode_sign($str)
	{
		$argvs=explode($this->urlStyle,$str);
		$argvlen=count($argvs);
		if($argvlen<2){
			$this->appClass=empty($argvs[0])?'':$argvs[0];
			return;
		}

		$this->appClass=empty($argvs[0])?'':$argvs[0];
		$this->actMethod=empty($argvs[1])?'':$argvs[1];

		if ($this->urlArgsStyle == $this->urlStyle) {
			for ($i=2;$i<$argvlen;$i+=2){
				if (!empty($argvs[$i])) {
					$this->mvcArgs[ $argvs[$i] ]=rawurldecode($argvs[$i+1]);
				}
			}
		}
		else
		{
			for ($i=2;$i<$argvlen;$i++){
				$pargs= explode($this->urlArgsStyle,$argvs[$i]);
				if (!empty($pargs[0])) {
					$this->mvcArgs[ $pargs[0] ]=rawurldecode($pargs[1]);
				}
			}
		}

	}

	private function parse_mode_pathinfo($str)
	{
		$argvs=explode($this->urlStyle,$str);
		
		$argvlen=count($argvs);
		if($argvlen<2){
			$this->appClass=empty($argvs[0])?'':$argvs[0];
			return;
		}
		$this->appClass=empty($argvs[0])?'':$argvs[0];
		$this->actMethod=empty($argvs[1])?'':$argvs[1];

		if ($this->urlArgsStyle == $this->urlStyle) {
			for ($i=2;$i<$argvlen;$i+=2){
				if (!empty($argvs[$i])) {
					$this->mvcArgs[ $argvs[$i] ]=rawurldecode($argvs[$i+1]);
				}
			}
		}
		else
		{
			for ($i=2;$i<$argvlen;$i++){
				$pargs= explode($this->urlArgsStyle,$argvs[$i]);
				if (!empty($pargs[0])) {
					$this->mvcArgs[ $pargs[0] ]=rawurldecode($pargs[1]);
				}
			}
		}

	}

	private function parseQueryUrl($str)
	{

		if ($this->urlExt ) {
			$str=substr($str,0,strpos($str,$this->urlExt));
		}

		if ($this->urlMode==URLMODE_SIGN) {
			$this->parse_mode_sign($str);
		}
		elseif ($this->urlMode==URLMODE_PATH || $this->urlMode==URLMODE_REWR) {
			$this->parse_mode_pathinfo($str);
		}
	
	}

	private function parseTplUrl($str)
	{
		$argvs=explode('/',$str);
		$argvlen=count($argvs);
		if($argvlen == 0)return false;

		$this->appName=empty($argvs[0])?'':wskm_filter::getValue($argvs[0],TYPE_ALNUM);
		$this->actName=empty($argvs[1])?'':wskm_filter::getValue($argvs[1],TYPE_ALNUM);

		if ($argvlen > 2) {
			for ($i=2;$i<$argvlen;$i++){
				if(strpos($argvs[$i],':')>0){
					$pargs= explode(':',$argvs[$i]);
					if (count($pargs)==2) {
						$this->urlArgv[ $pargs[0] ]=$pargs[1];
					}
				}
			}
		}

		return true;
	}

	private function mode_none($str,$isdecode=false)
	{
		if ($isdecode) {
			$arr=array();

			$this->appClass=isset($_REQUEST[$this->appUrlName])?$_REQUEST[$this->appUrlName]:'';
			$this->actMethod=isset($_REQUEST[$this->actUrlName])?$_REQUEST[$this->actUrlName]:'';

			$this->mvcArgs=array_merge($_POST,$_GET);;
			return;
		}

		if (!$this->isArgs) {
			if(!$this->parseTplUrl($str)) return '';
		}

		if (!$this->must()) {
			return '';
		}

		$format="&%s=%s";
		$murl=empty($this->appName)?'':$this->appUrlName.'='.$this->appName;
		if (empty($this->appName) && !empty($this->actName)) {
			$murl.=$this->actUrlName.'='.$this->actName;
		}
		elseif (!empty($this->appName) && !empty($this->actName)){
			$murl.=sprintf($format,$this->actUrlName,$this->actName);
		}

		if ( count($this->urlArgv) >0 ) {
			foreach ($this->urlArgv as $k=>$v){
				if (is_string($k) && !empty($k) && $v != '') {
					$murl.=sprintf($format,$k,rawurlencode($v));
				}
			}
		}

		$murl=strtolower($murl);
		return $murl;
	}

	private function mode_sign($str,$isdecode=false)
	{
		if ($isdecode) {
			$this->parseQueryUrl($str);
			return ;
		}

		if (!$this->isArgs) {
			if(!$this->parseTplUrl($str)) return '';
		}

		if (!$this->must()) {
			return '';
		}

		$murl=empty($this->appName)?'':$this->appName;
		if ($this->actName =='' && count($this->urlArgv) ==0) {
			return $murl;
		}

		$format="{$this->urlStyle}%s";
		$murl.=sprintf($format,$this->actName);
		if ( count($this->urlArgv) >0 ) {
			foreach ($this->urlArgv as $k=>$v){
				if (is_string($k) && !empty($k) && $v != '') {
					$murl.=sprintf($format,$k.$this->urlArgsStyle.rawurlencode($v));
				}
			}
		}

		$murl=strtolower($murl);
		return $murl;

	}

	private function mode_pathinfo($str,$isdecode=false)
	{
		if ($isdecode) {
			$this->parseQueryUrl($str);
			return ;
		}

		if (!$this->isArgs) {
			if(!$this->parseTplUrl($str)) return '';
		}

		if (!$this->must()) {
			return '';
		}

		$murl=empty($this->appName)?'':$this->appName;
		$this->actName=empty($this->actName)?'':$this->actName;

		if ($this->actName =='' && count($this->urlArgv) ==0) {
			return $murl;
		}
		
		$format=$this->urlStyle."%s";
		$murl.=sprintf($format,$this->actName);
		if ( count($this->urlArgv) >0 ) {
			foreach ($this->urlArgv as $k=>$v){
				if (is_string($k) && !empty($k) && $v != '') {
					$murl.=sprintf($format,$k.$this->urlArgsStyle.rawurlencode($v));
				}
			}
		}

		$murl=strtolower($murl);
		return $murl;
	}

	static $htmlPages=array('index','category','news');
	public function isNeedHtml(){
		return in_array($this->appName,self::$htmlPages);
	}

	public function htmlKey(){
		$srcArgs=$this->isHttp?self::$htmlArgs:$this->htmlMvcArgs;
		if ($srcArgs['app'] == $this->appDefault && $srcArgs['act'] == $this->actDefault && !IN_ADMIN && MVC_FUN=='usingMVC') {
			return getUrlName(false);
		}

		if ( in_array($srcArgs['app'],array('news','category')) && !isset($srcArgs['page'])) {
			$srcArgs['page']=1;
		}
		$key='';

		$htmlArgs=array_values($srcArgs);
		foreach ($htmlArgs as $temp){
			$key .= $temp.'-';
		}

		$key=strtolower(rtrim($key,'-'));
		if (!in_array($srcArgs['app'],array('category')) ) {
			$key=substr(md5($key),0,1).'/'.$key;
		}
		return $key;
	}
}

function mvcUrl($url,$argv=null,$baseurl='',$modetype='',$showhtml=true)
{
	$mvc=wskm_http_url::getSingle();
	return $mvc->url($url,$argv,$baseurl,$modetype,$showhtml);
}

function mvcUrl_page($argv,$page){
	if (WSKM::isExistConfig('tplpagekey')) {
		$argv[2][ WSKM::getConfig('tplpagekey') ]=$page;
	}else{
		$argv[2]['page']=$page;
	}
	
	return mvcUrl('',$argv,ART_URL);
}

class wskm_http_mvc
{
	static function mvc()
	{
		$mvcurl=new wskm_http_url();
		$mvcurl->mvc();

		IS_HTML && define('UPDATE_HTML',(isset($_GET['updatehtml']) && (bool)$_GET['updatehtml'])?true:false);
		if (IS_HTML && !UPDATE_HTML && in_array(MVC_APP,wskm_http_url::$htmlPages ) && file_exists(ART_ROOT.'html'.DS.$mvcurl->htmlKey().'.html' )) {
			gotoUrl(ART_URL.'html/'.$mvcurl->htmlKey().'.html',true);
		}
		
		if(!IN_ADMIN && WSKM::getConfig('webStatus')=='0' && !WSKM::user()->isAdmin() && MVC_APP !='user' && MVC_ACT != 'login' ){
			showMessage(WSKM::getConfig('webCloseReason').'<br /><a href="'.mvcUrl('',array('user','login')).'" >'.lang('login').'</a>');
		}
		
		$fun= MVC_FUN;
		if (MVC_APP == $mvcurl->appDefault && MVC_ACT == $mvcurl->actDefault && !IN_ADMIN) {
			$cbydomains=(array)WSKM::getConfig('cbydomain');
			if ($cbydomains && isset( $cbydomains[ $_SERVER['HTTP_HOST'] ] )) {
				$tocid=(int)$cbydomains[ $_SERVER['HTTP_HOST'] ];
				if ($tocid>0) {
					$mvcurl->appClass=$mvcurl->appPrefix.'category';
					$mvcurl->actMethod=$mvcurl->actPrefix.'list';
					wskm_request::setGET('id',$tocid);
				}
			}
		}

		if(!$fun($mvcurl->appClass))
		{
			showMessage('mvc_noapp');
		}
		define('IN_AJAX',requestGet('inajax')=='1');
		if(!IN_AJAX && OBGZIP && function_exists('ob_gzhandler') ) {
			ob_start('ob_gzhandler');
		} else {
			ob_start();
		}
		$mvcClass=new $mvcurl->appClass();

		if(method_exists($mvcClass,$mvcurl->actMethod))
		{
			return $mvcClass->{$mvcurl->actMethod}();
		}
		showMessage('mvc_noact');
	}
}

?>