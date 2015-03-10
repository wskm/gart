<?exit?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset={PAGE_CHARSET}" />
<title>{$page_title}{$page_seotitle}{ART_WEB_NAME}_Gart</title>
<meta name="keywords" content="{$page_keywords}{$page_seokeywords}" />
<meta name="description" content="{$page_description} {$page_seodescription} {ART_WEB_NAME} - Gart - WskmPHP" />
<meta name="generator" content="Gart {ART_VER}" />
<meta name="author" content="Gart Team" />
<meta name="copyright" content="2009-2010 WSKM." />
<meta http-equiv="x-ua-compatible" content="ie=7" />
##CSS()##
<script type="text/javascript">
//<![CDATA[
var STYLEID = '{STYLEID}', page_charset = '{PAGE_CHARSET}', cookiedomain = '{COOKIEDOMAIN}', cookiepath = '{COOKIEPATH}', isattack = '0',isrequestnew='0', WEB_IMG = '{ART_URL}images/common/',WEB_URL='{ART_URL}',ispopbg='{$popBgShow}',popbgcolor='{$popBgColor}';
//]]> 
</script>
<script type="text/javascript" src="{ART_INC_URL}js/util.js?{STYLEVERSION}"></script>
</head>
<body>
<div id="ajax_parent"></div><div id="ajax_load"></div>
<div id="header">
	<div id="logo"><a href="{ART_URL}">&nbsp;</a></div>
	<div id="lead">
		<a href="{ART_URL}" >{lang index}</a> | <span id="user"><script type="text/javascript" language="javascript" src="{ART_URL}wskm.php?act=loginstate" ></script></span> | <a href="##htmlurl(map)##" >{lang sitemap}</a>
	</div> 
	<div id="switchtheme">
		<ul >
		{loop $themelist $styles}
			{if $styles.type==0}
			<li><a style="background-color:{$styles.color};" title="{$styles.title}" onclick="switchTheme({$styles.styleid})" href="#">&nbsp;</a></li>
			{/if}
		{/loop}
		</ul>
		<script type="text/javascript">
			function switchTheme(styleid) {
				switchurl = '##htmlurl(index/index/styleid:ws99)##';
				switchurl = switchurl.replace(/ws99/ig, styleid); 
				location.href = switchurl;
			}
		</script>

	</div>
	<div class="clear"></div>
</div>
<div class="inner">
		
		##CSS(util)##
		<div id="navIND">
			<div class="navINDL fl">&nbsp;</div>
			<div class="navINDR fr">&nbsp;</div>
			<div class="navIND">

				<ul>
					<li><a href="{ART_URL}" id="topnav_index"  >{lang index}</a></li>
					{loop $topnav $nav}
					<li><a {if $nav.target}target="_blank"{/if} href="{$nav.url}" id="topnav_{$nav.key}" ><font {if $nav.color}color="{$nav.color}"{/if} >{$nav.name}</font></a></li>
					{/loop}
				</ul>
			</div>
					
		</div>
		<script type="text/javascript">
		var navCurrent = dom('topnav_{$nav_current}');
		if(navCurrent){
			navCurrent.parentNode.className = 'current';
		}else if(dom('topnav_index')){
			dom('topnav_index').parentNode.className = 'current';
		}
		</script>
</div>
<div class="navBottom ">
{php $i=0;}
{loop $categorylist $cate}
	{php $i++; if($i>20)break;}	
	<a href="{$cate.mvcurl}" >{$cate.name}</a>&nbsp;|&nbsp;
{/loop}
</div>	

<script type="text/javascript" language="javascript" src="{ART_URL}ad.php?id=1" ></script>