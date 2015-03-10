<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [WskmPHP] www.wskmphp.com 
 * $Id: wk_html.php 67 2010-09-30 07:31:19Z ws99 $ 
 */

!defined('IN_WSKM') && exit('Access Denied');


function html_select($name, $arr, $selected = null, $extra = null) {
	echo "<select id=\"{$name}\" name=\"{$name}\" {$extra} >\n";
	echo html_select_option($arr,$selected);
	echo "</select>\n";
}

function html_select_option($arr,$selected,$ishtml=true)
{
	$option='';
	if ($ishtml) {
		foreach ($arr as $value => $title) {
			$option .= '<option value="' . htmlspecialchars($value) . '"';
			if ($selected == $value) { $option.= ' selected'; }
			$option.= '>' . htmlspecialchars($title) . "&nbsp;&nbsp;</option>\n";
		}
	}
	else {
		foreach ($arr as $value => $title) {
			$option .= '<option value="' . $value . '"';
			if ($selected == $value) { $option.= ' selected'; }
			$option.= '>' . $title . "</option>\n";
		}
	}

	return $option;
}

function html_img($name,$src,$width=0,$height=0)
{
	echo "<img name=\"{$name}\" id=\"{$name}\" src=\"{$src}\" ";
	if ($width) {
		echo " width=\"{$width}\" ";
	}

	if ($height) {
		echo " height=\"{$height}\" ";
	}
	echo "/> ";

}

function html_radio_group($name, $arr, $checked = null, $separator = '', $extra = null) {
	$ix = 0;
	foreach ($arr as $value => $title) {
		$value_h = htmlspecialchars($value);
		$title = htmlspecialchars($title);
		echo "<input name=\"{$name}\" type=\"radio\" id=\"{$name}_{$ix}\" value=\"{$value_h}\" ";
		if ($value == $checked) {
			echo "checked=\"checked\"";
		}
		echo " {$extra} />";
		echo "<label for=\"{$name}_{$ix}\">{$title}</label>";
		echo $separator;
		$ix++;
		echo "\n";
	}
}

function html_checkbox_group($name, $arr, $selected = array(), $separator = '', $extra = null) {
	$ix = 0;
	if (!is_array($selected)) {
		$selected = array($selected);
	}
	foreach ($arr as $value => $title) {
		$value_h = htmlspecialchars($value);
		$title = htmlspecialchars($title);
		echo "<input name=\"{$name}[]\" type=\"checkbox\" id=\"{$name}_{$ix}\" value=\"{$value_h}\" ";
		if (in_array($value, $selected)) {
			echo "checked=\"checked\"";
		}
		echo " {$extra} />";
		echo "<label for=\"{$name}_{$ix}\">{$title}</label>";
		echo $separator;
		$ix++;
		echo "\n";
	}
}

function html_checkbox($name, $value = 1, $checked = false, $label = '', $extra = null) {
	echo "<input name=\"{$name}\" type=\"checkbox\" id=\"{$name}_1\" value=\"{$value}\"";
	if ($checked) { echo " checked"; }
	echo " {$extra} />\n";
	if ($label) {
		echo "<label for=\"{$name}_1\">" . htmlspecialchars($label) . "</label>\n";
	}
}

function html_textbox($name, $value = '', $width = null, $maxLength = null, $extra = null) {
	echo "<input name=\"{$name}\" id=\"{$name}\" type=\"text\" value=\"" . htmlspecialchars($value) . "\" ";
	if ($width) {
		echo "size=\"{$width}\" ";
	}
	if ($maxLength) {
		echo "maxlength=\"{$maxLength}\" ";
	}
	echo " {$extra} />\n";
}

function html_password($name, $value = '', $width = null, $maxLength = null, $extra = null) {
	echo "<input name=\"{$name}\" id=\"{$name}\" type=\"password\" value=\"" . htmlspecialchars($value) . "\" ";
	if ($width) {
		echo "size=\"{$width}\" ";
	}
	if ($maxLength) {
		echo "maxlength=\"{$maxLength}\" ";
	}
	echo " {$extra} />\n";
}

function html_textarea($name, $value = '', $width = null, $height = null, $extra = null) {
	echo "<textarea name=\"{$name}\" id=\"{$name}\" ";
	if ($width) { echo "cols=\"{$width}\" "; }
	if ($height) { echo "rows=\"{$height}\" "; }
	echo " {$extra} >";
	echo htmlspecialchars($value);
	echo "</textarea>\n";
}

function html_hidden($name, $value = '', $extra = null) {
	echo "<input name=\"{$name}\" id=\"{$name}\" type=\"hidden\" value=\"";
	echo htmlspecialchars($value);
	echo "\" {$extra} />\n";
}

function html_filefield($name, $width = null, $extra = null) {
	echo "<input name=\"{$name}\" id=\"{$name}\"  type=\"file\"";
	if ($width) {
		echo " size=\"{$width}\"";
	}
	echo " {$extra} />\n";
}

function html_form($name, $action, $method='post',$isclose=false,$content='', $onsubmit='', $extra = null) {
	echo "<form name=\"{$name}\" id=\"{$name}\" action=\"{$action}\" method=\"{$method}\" ";
	if ($onsubmit) {
		echo "onsubmit=\"{$onsubmit}\"";
	}
	echo " {$extra} >\n";

	if ($isclose) {
		echo $content."\n";
		echo "</form>\n";
	}

}

function html_form_close() {
	echo "</form>\n";
}


function creatHtml($type='text',$name,$value,$ext='',$option='')
{
	$html='';
	switch ($type){
		case 'text':
			$html=html_textbox($name,$value,150,100,$ext);
			break;
		case 'textarea':
			$html=html_textarea($name,$value,250,250,$ext);
			break;
		case 'radio':
			$option= is_array($option) ? $option : array(1=>lang('yes'),0=>lang('no'));			
			$html=html_radio_group($name,$option,$value,'',$ext);
			break;
		case 'checkbox':
			$html=html_checkbox($name,$value,false,'',$ext);
			break;
		case 'password':
			$html=html_password($name,$value,150,100,$ext);
			break;
		case 'select':
			$optionnew='';
			if (is_array($option)) {
				$optionnew=$option;
			}
			$html=html_select($name,$optionnew,$value,$ext);
				break;
	}

	return $html;
}

?>