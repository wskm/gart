<?php !defined('IN_WSKM') && exit('Access Denied');
/*
*	WskmPHP Framework
*
*	Copyright (c) 2009 WSKM Inc.
*
*
*/


class wskm_template_easy {
	private $php_var="((\\\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)(\[[a-zA-Z0-9_\-\.\"\'\[\]\$\x7f-\xff]+\])*)";
	private $php_const='([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)';

	static $instance=null;
	static function complie($tplf,$tof){
		$tplhtml = trim(wskm_io::fRead($tplf));

		if(!empty($tplhtml)) {
			$tplhtml = str_replace('<?exit?>', '', $tplhtml);
			if (!is_object(self::$instance)) {
				self::$instance=new wskm_template_easy();
			}

			$tplhtml = self::$instance->parse($tplhtml);

			$tplhtml = "<?php if(!defined('IN_ART')) exit('Access Denied'); ?>\n$tplhtml";
			return wskm_io::fWrite($tof, $tplhtml);
		}

		return false;
	}

	function htmltemplate(&$html){
		for($i = 0; $i < 3; $i++) {
			if(strExists($html, '{htmltemplate')) {
				$html = preg_replace("/[\n\r\t]*\{htmltemplate\s+([a-z0-9_:]+)\}[\n\r\t]*/ies", "\$this->loadtemplate('\\1')", $html);
			}
		}
	}

	function loadtemplate($name)
	{
		return $this->read($name);
	}

	function addquote($var) {
		return str_replace("\\\"", "\"", preg_replace("/\[([a-zA-Z0-9_\-\.\x7f-\xff]+)\]/s", "['\\1']", $var));
	}

	function striptagquotes($expr) {
		$expr = preg_replace("/\<\?\=(\\\$.+?)\?\>/s", "\\1", $expr);
		$expr = str_replace("\\\"", "\"", preg_replace("/\[\'([a-zA-Z0-9_\-\.\x7f-\xff]+)\'\]/s", "[\\1]", $expr));

		return $expr;
	}

	function stripvtags($expr, $statement='') {
		$expr = str_replace("\\\"", "\"", preg_replace("/\<\?\=(\\\$.+?)\?\>/s", "\\1", $expr));
		$statement = str_replace("\\\"", "\"", $statement);
		return $expr.$statement;
	}

	function read($file,$styleid='',$stylename=''){
		if (strExists($file,'plug:')) {
			$paths=plugin_path($file);
		}elseif (defined('IN_ADMIN') && IN_ADMIN) {
			$paths=template_adminpath($file,$styleid,$stylename);
		}else{
			$paths=template_path($file,$styleid,$stylename);
		}
		$tplfile=$paths['tpl'];

		$template = wskm_io::fRead($tplfile);
		$template = str_replace('<?exit?>', '', $template);
		return $template;
	}

	function filter_html(&$html){
		$html = preg_replace("/([\n\r]+)\t+/s", "\\1", $html);
		$html = preg_replace("/\<\!\-\-\s*\{(.+?)\}\s*\-\-\>/s", "{\\1}", $html);
	}

	function lang_html(&$html){
		$html = preg_replace("/\{lang\s+(.+?)\}/ies", "lang('\\1')", $html);    
	}

	function var_html(&$html){
		$html = preg_replace("/(\\\$[a-zA-Z0-9_\[\]\'\"\$\x7f-\xff]+)\.([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)/s", "\\1['\\2']", $html);	
		$html = preg_replace("/\{(\\\$[a-zA-Z0-9_\[\]\'\"\$\.\x7f-\xff]+)\}/s", "<?=\\1?>", $html);
		$html = preg_replace("/$this->php_var/es", "\$this->addquote('<?=\\1?>')", $html);   
		$html = preg_replace("/\<\?\=\<\?\=$this->php_var\?\>\?\>/es", "\$this->addquote('<?=\\1?>')", $html);   
		
		$html = preg_replace("/\{\<\?\=((\\\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)(\[\'[a-zA-Z0-9_\-\.\"\'\[\]\$\x7f-\xff]+\'\])*)\?\>\s*\|\s*([a-zA-Z0-9_\x7f-\xff\:\@\#\$\%\&\*\~\s\-]+)\s*\}/es", "\$this->getval('\\1','\\4')?>')", $html); 
	}

	function const_html(&$html){
		$html = preg_replace("/\{$this->php_const\}/s", "<?=\\1?>", $html);    
	}

	function getval($val,$funtype){
		if (empty($funtype)) {
			return $val.$funtype;
		}
		$str=$bktype=$args='';

		if (strpos($funtype,':') !== false) {
			$args=array('','','');
			$args=explode(':',$funtype);
			$bktype=$funtype;
			$funtype=$args[0];
		}

		switch ($funtype)
		{
			case 'escape':
				$str="<?=htmlspecialchars($val)?>";
				break;
			case 'time':
			case 'date':
				$format=strpos($bktype,':');
				if ($format) {
					$format= substr($bktype,$format+1);
					$str="<?=now($val,'$format')?>";
				}else{
					$str="<?=now($val)?>";
				}
				break;
			case 'urlencode':
				$str="<?=rawurlencode($val)?>";
				break;
			case 'html':
				$str="<?=nl2br($val)?>";
				break;
			case 'strip':
				$str="<?=strip_tags($val)?>";
				break;
			case 'cut':
				$str="<?=strCut($val,'{$args[1]}','{$args[2]}')?>";
				break;
		}
		return $this->addquote($str);
	}

	function o_html(&$html){
		$html = preg_replace("/[\n\r\t]*\#\#url\((.+?)\)\#\#[\n\r\t]*/ies", "\$this->striptagquotes('<?php echo mvcUrl(\"\\1\"); ?>')", $html);          
		$html = preg_replace("/[\n\r\t]*\#\#htmlurl\((.+?)\)\#\#[\n\r\t]*/ies", "mvcUrl(\"\\1\")", $html);				
		$html = preg_replace("/[\n\r\t]*\#\#CSS\((\w+)?\)\#\#[\n\r\t]*/ies", "\$this->css_load(\"\\1\")", $html);
		$html = preg_replace("/[\n\r\t]*\#\#JS\((\w+)?\)\#\#[\n\r\t]*/ies", "\$this->js_load(\"\\1\")", $html);
		
		$html = preg_replace("/[\n\r\t]*\{insert\s*\(\s*([a-z0-9_\-]+)\s*,*\s*([a-z0-9_\-\|]*)\s*,*\s*([a-z0-9_\-\|]*)\s*\)\s*\}[\n\r\t]*/ies", "\$this->insert('\\1','\\2','\\3')", $html);
	}
	
	function css_load($name=''){
		if ($name=='') {
			$name='common';
		}
		
		if (file_exists(ART_THEMES_PATH.STYLENAME.DS.'css'.DS.$name.'.css')) {
			return '<link rel="stylesheet" type="text/css" href="'.THEME_URL.'css/'.$name.'.css?'.STYLEVERSION.'" />';
		}
		
		return '<link rel="stylesheet" type="text/css" href="'.ART_URL.'themes/default/css/'.$name.'.css?'.STYLEVERSION.'" />';
	}
	
	function js_load($name=''){
		if ($name=='') {
			$name='common';
		}
		
		if (file_exists(ART_THEMES_PATH.STYLENAME.DS.'js'.DS.$name.'.js')) {
			return '<script type="text/javascript" src="'.THEME_URL.'js/'.$name.'.js?'.STYLEVERSION.'" ></script>';
		}
		
		return '<script type="text/javascript" src="'.ART_URL.'themes/default/js/'.$name.'.js?'.STYLEVERSION.'" ></script>';
	}
	
	function other_html(&$html){
		$html = preg_replace("/[\n\r\t]*\{template\s+([a-z0-9_:]+)\}[\n\r\t]*/is", "<?php template('\\1'); ?>", $html);
		$html = preg_replace("/[\n\r\t]*\{template\s+(.+?)\}[\n\r\t]*/is", "<?php template('\\1'); ?>", $html);
		$html = preg_replace("/[\n\r\t]*\{php\s+(.+?)\}[\n\r\t]*/ies", "\$this->stripvtags('<?php \\1 ?>','')", $html);		
		$html = preg_replace("/[\n\r\t]*\{wskm\s*\(\s*([a-z0-9_\-]+)\s*,*\s*([a-z0-9_\-\|]*)\s*,*\s*([a-z0-9_\-\|]*)\s*\)\s*\}[\n\r\t]*/is", "<?php \$wskm_\\1=getWskm('\\1','\\2','\\3'); ?>", $html);
		$html = preg_replace("/[\n\r\t]*\{echo\s+(.+?)\}[\n\r\t]*/ies", "\$this->stripvtags('<?php echo \\1; ?>','')", $html);
		
		$html = preg_replace("/[\n\r\t]*\{sql\s*\(\s*([^|]+)\s*\|\s*([a-z0-9_\-]+)\s*\|*\s*([a-z0-9_\-]*)\s*\|*\s*([a-z0-9_\-]*)\s*\)\s*\}[\n\r\t]*/is", "<?php \$sqldata_\\2=sqlData('\\1','\\3','\\2','\\4'); ?>", $html);
	}

	function loop_html(&$html){
		$html = preg_replace("/([\n\r\t]*)\{elseif\s+(.+?)\}([\n\r\t]*)/ies", "\$this->stripvtags('\\1<? } elseif(\\2) { ?>\\3','')", $html);
		$html = preg_replace("/([\n\r\t]*)\{else\}([\n\r\t]*)/is", "\\1<? } else { ?>\\2", $html);

		$loopcount=5;
		for($i = 0; $i < $loopcount; $i++) {
	
			$html = preg_replace("/[\n\r\t]*\{loopif\s+(\S+)\s+(\S+)\}[\n\r]*(.+?)[\n\r]*\{loopelse\}[\n\r]*(.+?)[\n\r]*\{\/loopelse\}[\n\r\t]*/ies", "\$this->stripvtags('<?php if(is_array(\\1) && count(\\1) >0) { foreach(\\1 as \\2) { ?>','\\3<?php } }else{ ?>\\4<?php } ?>')", $html);
			$html = preg_replace("/[\n\r\t]*\{loopif\s+(\S+)\s+(\S+)\s+(\S+)\}[\n\r\t]*(.+?)[\n\r\t]*\{loopelse\}[\n\r]*(.+?)[\n\r]*\{\/loopelse\}[\n\r\t]*/ies", "\$this->stripvtags('<?php if(is_array(\\1)  && count(\\1) >0 ) { foreach(\\1 as \\2 => \\3) { ?>','\\4<?php } }else{ ?>\\5<?php } ?>')", $html);

			$html = preg_replace("/[\n\r\t]*\{loop\s+(\S+)\s+(\S+)\s*\}[\n\r]*(.+?)[\n\r]*\{\/loop\}[\n\r\t]*/ies", "\$this->stripvtags('<?php if(is_array(\\1)) { foreach(\\1 as \\2) { ?>','\\3<?php } } ?>')", $html);
			$html = preg_replace("/[\n\r\t]*\{loop\s+(\S+)\s+(\S+)\s+(\S+)\s*\}[\n\r\t]*(.+?)[\n\r\t]*\{\/loop\}[\n\r\t]*/ies", "\$this->stripvtags('<?php if(is_array(\\1)) { foreach(\\1 as \\2 => \\3) { ?>','\\4<?php } } ?>')", $html);

			$html = preg_replace("/([\n\r\t]*)\{if\s+(.+?)\}([\n\r]*)(.+?)([\n\r]*)\{\/if\}([\n\r\t]*)/ies", "\$this->stripvtags('\\1<?php if(\\2) { ?>\\3','\\4\\5<?php } ?>\\6')", $html);
		}
	}

	function clear_html(&$html){
		$html = preg_replace("/ \?\>[\n\r]*\<\? /s", " ", $html);      
	}

	function parse($html){
		$this->htmltemplate($html);
		$this->filter_html($html);
		$this->lang_html($html);
		$this->var_html($html);
		$this->o_html($html);
		$this->other_html($html);
		$this->loop_html($html);
		$this->const_html($html);
		$this->clear_html($html);

		return $html;
	}

	function insert($key,$args='',$file=''){
		if ($key=='') {
			exit('insert:'.$key);
		}
		
		$fun='wskm_'.$key;
		if (!function_exists($fun)) {
			usingArtFun('wskm');		
			usingThemeIncs();
			
			if (!function_exists($fun) && $file ) {
				usingThemeInc(wskm_filter::getValue($file,TYPE_ALNUM));
			}
		}
		
		if (function_exists($fun)) {
			return $fun($args);
		}
		
		return '';
	}
}

class wskm_tpl_vars
{
	public static $vars=array();
	static function getValue($key){
		return self::$vars[$key];
	}

	static function assign($k,$v)
	{
		if (is_string($k)) {
			self::$vars[$k]=$v;
		}
	}
}

function assign_get($k){
	return wskm_tpl_vars::getValue($k);
}

function assign_var($k,$v)
{
	wskm_tpl_vars::assign($k,$v);
}

function template_path($basename,$styleid='',$stylename='')
{
	$basename=strtolower($basename);
	$styleid=is_int($styleid) ? $styleid:'';
	if(($basename=='header' || $basename=='footer') && defined('IN_AJAX')){
		$basename .=IN_AJAX?'_ajax':'';
	}
	$sid=$styleid==''?STYLEID:$styleid;
	$sname=$stylename==''?STYLENAME:$stylename;

	$targetfile = CACHE_DIR.'tpl'.DS.$sid.'_'.$basename.'_tpl.php';
	$tplfile=TPL_DIR.$sname.DS.$basename.'_html.php';

	if ($stylename !='') {		
		define('THEME_URL', ART_URL.'themes/'.$sname.'/');
	}

	if($stylename != 'default' && !file_exists($tplfile)) {
		$tplfile = TPL_DIR.'default'.DS.$basename.'_html.php';
	}

	return array('tpl'=>$tplfile,'to'=>$targetfile);
}

function plugin_path($name){
	if ($name) {
		$name=str_replace('plug:','',$name);
	}
	$targetfile = CACHE_DIR.'tpl'.DS.STYLEID.'_plugin_'.$name.'_tpl.php';
	$tplfile=PLUGIN_PATH.'themes'.DS.$name.'_html.php';
	define('PTHEME_URL', PLUGIN_URL.'themes/');

	return array('tpl'=>$tplfile,'to'=>$targetfile);
}

function template($file,$styleid='',$stylename='') {

	if (strExists($file,'plug:')) {
		$paths=plugin_path($file);
	}elseif (defined('IN_ADMIN') && IN_ADMIN) {
		$paths=template_adminpath($file,$styleid,$stylename);

	}else{
		$paths=template_path($file,$styleid,$stylename);
	}

	if (!defined('THEME_URL')) {
		define('THEME_URL', ART_URL.'themes/'.STYLENAME.'/');		
	}

	$targetfile = $paths['to'];
	$tplfile = $paths['tpl'];

	if(!is_readable($tplfile)){
		exit('Themes file : <b style="color:red">'.pathSame($tplfile).'</b> Not found or have no access!');
	}

	if (count(wskm_tpl_vars::$vars)>0) {
		extract(wskm_tpl_vars::$vars);
	}

	$res=true;
	if( @filemtime($tplfile) > @filemtime($targetfile) ) {
		$res=wskm_template_easy::complie($tplfile,$targetfile);
	}

	if ($res !== false) {
		include $targetfile;

	}

}

?>