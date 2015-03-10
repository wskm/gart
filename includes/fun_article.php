<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [Gart] www.wskmphp.com 
 * $Id: fun_article.php 222 2010-11-22 09:20:03Z ws99 $ 
 */

!defined('IN_ART') && exit('Access Denied');

function getHtmlEditor($input_name='',$input_value='',$style='',$drive=''){
	$input_name=$input_name==''?'message':$input_name;
	$drive=$drive==''?WSKM::getConfig('editor'):$drive;
	if (!in_array($drive,array('ckeditor','xheditor'))) {
		$drive='ckeditor';
	}
	assign_var('editdrive',$drive);

	if (empty($style)) {
		$style='Art';
	}
	
	$html='';
	$width='100%';
	$height='430px';
	$language=switchLanguage($drive);

	if ($drive == 'xheditor'){
		$name='xheditor-'.$language;
		$html=jsInclude(ART_URL.'includes/editor/xheditor/'.$name.'.js',true);
		$html .='<textarea style="height:'.$height.';width:'.$width.';border:none;" id="'.$input_name.'" name="'.$input_name.'" rows="10">'.$input_value.'</textarea>';		
		$html .=jsWriter("$('#{$input_name}').xheditor({emotPath:'".ART_URL."images/smilies/'});",true);
	}elseif ($drive=='ckeditor'){
		$height='320px';
		$html=jsInclude(ART_URL.'includes/editor/ckeditor/ckeditor.js',true);		
		$html .='<textarea style="width:'.$width.';border:none;" id="'.$input_name.'" name="'.$input_name.'" rows="10">'.$input_value.'</textarea>';
		$html .=jsWriter("CKEDITOR.config.language='{$language}';CKEDITOR.config.toolbar='{$style}';CKEDITOR.replace('{$input_name}',{height:'{$height}','smiley_path':'".ART_URL."images/smilies/qq/'});",true);		
	}

	return $html;
}

function switchLanguage($drive){
	$language='';
	if ($drive=='ckeditor' || $drive=='xheditor') {
		if (LANGUAGE=='zh') {
			$language='zh-cn';
		}elseif (LANGUAGE=='zhbig5'){
			$language='zh-tw';
		}else{
			$language='en';
		}
	}
	return $language;
}

?>