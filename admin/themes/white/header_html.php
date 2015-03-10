<?exit?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset={PAGE_CHARSET}" />
<title>admin</title>
<script type="text/javascript" src="{ART_URL}includes/js/jquery.js"></script>
<script type="text/javascript" src="{ART_URL}includes/js/util.js"></script>
<script type="text/javascript" src="{ADMIN_URL}includes/js/common.js"></script>
<script type="text/javascript">
//<!CDATA[
var SITE_URL = "{ART_URL}";var STYLEID="{ADMIN_STYLEID}",ISFRAME=1,adminindex = 'index.php',page_charset='{PAGE_CHARSET}',ispopbg='0';
function redirect(url) {
	window.location.replace(url);
}
if(ISFRAME && !parent.document.getElementById('leftmenu')) {
	var rurl=document.URL.substr(document.URL.indexOf(adminindex)).replace(adminindex+'?','').replace('wskm=','nav=').replace('act=','menukey=');
	redirect(adminindex + '?frames=yes&' + rurl);
}
//]]>
</script>
<link href="{ATHEME_URL}css/admin.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="ajax_parent"></div><div id="ajax_load"></div>
<div id="bodywrap" >