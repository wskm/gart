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
<style type="text/css" >
body{width:960px;}
dl, dt, dd {
margin:0;
padding:0;
}
#mtop_nav{
background:none repeat scroll 0 0 #F8F8F8;
border-bottom:1px solid #E6E6E6;
border-left:1px solid #E6E6E6;
border-right:1px solid #E6E6E6;
color:#7A7979;
height:20px;
line-height:20px;
text-align:right;
width:960px;
}
#mtop_nav a{color:#7A7979;}

#map_wrap{
width:960px;
margin:0 auto;
text-align:left;
}
#map_wrap dt{
border-top:solid 1px #D8DDCA;
border-bottom:solid 1px #D8DDCA;
background:none repeat scroll 0 0 #F8F8F8;
color:#000;
font-size:12px;
font-weight:bold;
height:24px;
line-height:24px;
text-indent:10px;
}
#map_wrap dd{
padding-top:9px;
color:#1F376D;
font-family:Verdana, 宋体;
font-size:13px;
margin:0;

padding:12px;
}
#map_wrap p{
padding-bottom:5px;
}
.abblod{font-weight:bold;font-size:14px;}
#map_wrap a{
color:#1F376D;
font-family:Verdana, 宋体;
font-size:13px;
margin-right:12px;
padding:2px 1px 1px;
}

</style>
</head>

<body>

<div id="mtop_nav">
		<div style="margin-right:10px;" >{lang sitemap}·<a href="{ART_URL}">{lang index}</a></div>
</div>

<div id="map_wrap">
<div style="padding:0;margin-top:10px;border-bottom:1px solid #D4D4D4;border-left:1px solid #D4D4D4;border-right:1px solid #D4D4D4;">
	<dl>
		{if $topnav}
		<dt >{lang sys_nav}</dt>
		<dd >
			{loop $topnav $nav}
				<a style="margin-right: 14px;" href="{$nav.url}" target="_blank" >{$nav.name}</a>
			{/loop}
    	</dd>
    	{/if}
    	<dt>{lang sys_category}</dt>
		<dd >
			{loop $categorylist $cate}
				<a style="margin-right: 14px;margin-bottom: 9px;" href="{$cate.mvcurl}" target="_blank" >{$cate.name}</a>
			{/loop}
    	</dd>
		
	</dl>
</div>
</div>

<div id="footer" >

	<div class="fleftwrap">
		<p><a href="{ART_URL}" >{ART_WEB_NAME}</a></p>
	</div>
	<div class="frightwrap">
		<p><a href="http://www.wskmphp.com" target="_blank" >Powered by Gart {ART_VER}</a>&nbsp;</p>
	</div>
	<div class="clear"></div>
	
</div> 
{$page_footer}

</body>
</html>