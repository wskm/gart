<?exit?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{lang artcms_admin}- Powered by Gart</title>
<meta http-equiv="Content-Type" content="text/html; charset={PAGE_CHARSET}" />
<link rel="stylesheet" type="text/css" href="{ATHEME_URL}css/admin.css" />
<script type="text/javascript" src="{ART_URL}includes/js/jquery.js"></script>
<script type="text/javascript" src="{ART_URL}includes/js/util.js"></script>
</head>
<body>
<table width="100%" height="100%" cellspacing="0" cellpadding="0" border="0" >
<tr> 
	<td colspan="2" height="75" valign="top">
	{htmltemplate top}
	</td>
</tr>
<tr>
	<td valign="top" width="150" id="leftwrap" >
	{htmltemplate menu}
	</td>
	<td valign="top" width="100%" height="100%" id="framemain">
	<iframe width="100%" height="100%" frameborder="0" scrolling="yes" style="overflow: visible;" onload="mainFrame(0)" name="main" id="main" src=""></iframe>
	</td>
</tr>
</table>
<div class="copyright" style="display:none">
	<p style="width:130px;text-align:cetner"><a target="_blank" href="#">Gart</a> 1.0</p>
</div>

<script type="text/Javascript" language="JavaScript">
function getid(id){
	return document.getElementById(id);
}
function frameTo(hurl){
	getid('main').src=hurl;
}

function mainFrame(id, src) {
	var setFrame = !id ? 'main' : 'main_' + id, obj = getid('framemain').getElementsByTagName('IFRAME'), exists = 0, src = !src ? '' : src;
	for(i = 0;i < obj.length;i++) {
		if(obj[i].name == setFrame) {
			exists = 1;
		}
		obj[i].style.display = 'none';
	}
	if(!exists) {
		if($.browser.msie) {
			frame = document.createElement('<iframe name="' + setFrame + '" id="' + setFrame + '"></iframe>');
		} else {
			frame = document.createElement('iframe');
			frame.name = setFrame;
			frame.id = setFrame;
		}
		frame.width = '100%';
		frame.height = '100%';
		frame.frameBorder = 0;
		frame.scrolling = 'yes';
		frame.style.overflow = 'visible';
		frame.style.display = 'none';
		if(src) {
			frame.src = src;
		}
		getid('framemain').appendChild(frame);
	}
	
	getid(setFrame).style.display = '';

}

var headers = new Array({$tops});
var admincpfilename = 'index.php';
var menukey = '', custombarcurrent = 0;
function toggleNav(key, url) {
	if(key == 'index' && url == 'home') {
		if(BROWSER.ie) {
			doane(event);
		}
		parent.location.href = admincpfilename + '?frames=yes';
		return false;
	}
	menukey = key;
	for(var k in headers) {
		if(dom('menu_' + headers[k])) {
			dom('menu_' + headers[k]).style.display = headers[k] == key ? '' : 'none';
		}
	}
	var lis = dom('topnav').getElementsByTagName('li');
	for(var i = 0; i < lis.length; i++) {
		if(lis[i].className == 'navset') lis[i].className = '';
	}
	dom('top_' + key).parentNode.className = 'navset';
	if(url) {
		parent.mainFrame(0);
		var hrefs = dom('menu_' + key).getElementsByTagName('a');
		for(var j = 0; j < hrefs.length; j++) {			
			hrefs[j].className = hrefs[j].href.substr(hrefs[j].href.indexOf(admincpfilename) ) == url ? 'tabset' : (hrefs[j].className == 'tabset' ? '' : hrefs[j].className);
		}
	}
	else{
		var hrefs = dom('menu_' + key).getElementsByTagName('a');
		if(hrefs.length > 0){hrefs[0].className='tabset'}
	}
	return false;
}

var top_key = '{$navkey}';
var menu_key='{$menukey}';
var frametourl=top_key?'index.php?wskm='+top_key+(menu_key?'&act='+menu_key:''):'index.php?act=welcome';
toggleNav(top_key ? top_key : 'home',frametourl); 
frameTo(frametourl);

if(is_firefox){
	$('#wtopmenu').css('height','29px');
}
</script>
</body> 
</html>