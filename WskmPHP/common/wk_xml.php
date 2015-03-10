<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [WskmPHP] www.wskmphp.com 
 * $Id: wk_xml.php 16 2010-07-11 14:06:18Z ws99 $ 
 */

!defined('IN_WSKM') && exit('Access Denied');

function xml_encode($data, $encoding = 'utf-8', $root = "wskm")
{
	$xml = '<?xml version="1.0" encoding="' . $encoding . '"?>';
	$xml .= '<' . $root . '>';
	$xml .= data_to_xml ( $data );
	$xml .= '</' . $root . '>';
	return $xml;
}

function data_to_xml($data)
{
	if (is_object ( $data ))
	{
		$data = get_object_vars ( $data );
	}
	$xml = '';
	foreach ( $data as $key => $val )
	{
		is_numeric ( $key ) && $key = "item id=\"$key\"";
		$xml .= "<$key>";
		$xml .= (is_array ( $val ) || is_object ( $val )) ? data_to_xml ( $val ) : $val;
		list ( $key ) = explode ( ' ', $key );
		$xml .= "</$key>";
	}
	return $xml;
}

	function getChildren($vals, &$i) {

		$children = array();
		if(isset($vals[$i]['value'])) {
			$children['VALUE'] = $vals[$i]['value'];
		}

		while(++$i < count($vals)) {
			switch($vals[$i]['type']) {

			case 'cdata':
				if(isset($children['VALUE'])) {
					$children['VALUE'] .= $vals[$i]['value'];
				} else {
					$children['VALUE'] = $vals[$i]['value'];
				}
				break;

			case 'complete':
				if(isset($vals[$i]['attributes'])) {
					$children[$vals[$i]['tag']][]['ATTRIBUTES'] = $vals[$i]['attributes'];
					$index = count($children[$vals[$i]['tag']]) - 1;

					if(isset($vals[$i]['value'])) {
						$children[$vals[$i]['tag']][$index]['VALUE'] = $vals[$i]['value'];
					} else {
						$children[$vals[$i]['tag']][$index]['VALUE'] = '';
					}
				} else {
					if(isset($vals[$i]['value'])) {
						$children[$vals[$i]['tag']][]['VALUE'] = $vals[$i]['value'];
					} else {
						$children[$vals[$i]['tag']][]['VALUE'] = '';
					}
				}
				break;

			case 'open':
				if(isset($vals[$i]['attributes'])) {
					$children[$vals[$i]['tag']][]['ATTRIBUTES'] = $vals[$i]['attributes'];
					$index = count($children[$vals[$i]['tag']]) - 1;
					$children[$vals[$i]['tag']][$index] = array_merge($children[$vals[$i]['tag']][$index], $this->getChildren($vals, $i));
				} else {
					$children[$vals[$i]['tag']][] = $this->GetChildren($vals, $i);
				}
				break;

			case 'close':
				return $children;
			}
		}
	}

	function getXMLTree($data) {

		$parser = xml_parser_create('UTF-8');
		xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 0);
		xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
		xml_parse_into_struct($parser, $data, $vals, $index);
		xml_parser_free($parser);

		$tree = array();
		$i = 0;

		if(isset($vals[$i]['attributes'])) {
			$tree[$vals[$i]['tag']][]['ATTRIBUTES'] = $vals[$i]['attributes'];
			$index = count($tree[$vals[$i]['tag']]) - 1;
			$tree[$vals[$i]['tag']][$index] =  array_merge($tree[$vals[$i]['tag']][$index], $this->getChildren($vals, $i));
		} else {
			$tree[$vals[$i]['tag']][] = $this->getChildren($vals, $i);
		}
		return $tree;
	}

?>