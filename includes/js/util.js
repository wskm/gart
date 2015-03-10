var urlpath=location.pathname;
var urldir=urlpath.substring(0,urlpath.lastIndexOf('/')) +'/';
var is_ie=is_firefox=is_chrome=is_opera=is_safari=false;
var USERAGENT = navigator.userAgent.toLowerCase();
is_ie= window.ActiveXObject && USERAGENT.indexOf('msie') != -1 && USERAGENT.substr(USERAGENT.indexOf('msie') + 5, 3);
is_firefox = USERAGENT.indexOf('firefox') != -1 && USERAGENT.substr(USERAGENT.indexOf('firefox') + 8, 3);
is_chrome = window.MessageEvent && !document.getBoxObjectFor && USERAGENT.indexOf('chrome') != -1 && USERAGENT.substr(USERAGENT.indexOf('chrome') + 7, 10);
is_opera = window.opera && opera.version();
is_safari = window.openDatabase && USERAGENT.indexOf('safari') != -1 && USERAGENT.substr(USERAGENT.indexOf('safari') + 7, 8);

if(is_ie) {
	document.documentElement.addBehavior("#default#userdata");
}

function dom(id) {
	return document.getElementById(id);
}

function emptyfocus(id)
{
	if(!isObject(id))id=dom(id);
	id.value=strtrim(id.value);
	if(id.value.length<1){id.focus();return true;}
	return false;
}

function htmlwrite(s){
	document.write(s);
}

function isChecked(ckb) {
	return dom(ckb) && dom(ckb).checked == true ? 1 : 0;
}

function isUndefined(types) {
	return typeof types == 'undefined' ? true : false;
}

function isObject(types) {
	return typeof types == 'object' ? true : false;
}

function isLN(val)
{
	if((/[0-9A-Za-z]/.test(val))){
		return true;
	}
	return false;
}

function strtrim(str){
	return (str + '').replace(/(\s+)$/g, '').replace(/^\s+/g, '');
}

function strltrim(str){
	return str.replace(/^\s+/gm,"");
}

function strrtrim(str){
	return str.replace(/\s+$/gm,"");
}

function strlen(str) {
	return (is_ie && str.indexOf('\n') != -1) ? str.replace(/\r?\n/g, '_').length : str.length;
}

function mb_strlen(str) {
	if(typeof page_charset == 'undefined' ){
		page_charset = is_ie ? document.charset : document.characterSet;
	}
	var lengthi = 0;
	for(var i = 0; i < str.length; i++) {
		lengthi += str.charCodeAt(i) < 0 || str.charCodeAt(i) > 255 ? (page_charset == 'utf-8' ? 3 : 2) : 1;
	}
	return lengthi;
}

function mb_strcut(str, maxlength, dot) {
	var len = 0;
	var res = '';
	var dot = !dot ? '...' : '';
	maxlength = maxlength - dot.length;
	for(var i = 0; i < str.length; i++) {
		len += str.charCodeAt(i) < 0 || str.charCodeAt(i) > 255 ? (page_charset == 'utf-8' ? 3 : 2) : 1;
		if(len > maxlength) {
			res += dot;
			break;
		}
		res += str.substr(i, 1);
	}
	return res;
}

function in_array(needle, haystack) {
	if(typeof needle == 'string' || typeof needle == 'number') {
		for(var i in haystack) {
			if(haystack[i] == needle) {
				return true;
			}
		}
	}
	return false;
}

function getcookie(name) {
	var cookie_start = document.cookie.indexOf(name);
	var cookie_end = document.cookie.indexOf(";", cookie_start);
	return cookie_start == -1 ? '' : unescape(document.cookie.substring(cookie_start + name.length + 1, (cookie_end > cookie_start ? cookie_end : document.cookie.length)));
}

function setcookie(cookieName, cookieValue, seconds, path, domain, secure) {
	var expires = new Date();
	expires.setTime(expires.getTime() + seconds * 1000);
	domain = !domain ? cookiedomain : domain;
	path = !path ? cookiepath : path;
	document.cookie = escape(cookieName) + '=' + escape(cookieValue)
	+ (expires ? '; expires=' + expires.toGMTString() : '')
	+ (path ? '; path=' + path : '/')
	+ (domain ? '; domain=' + domain : '')
	+ (secure ? '; secure' : '');
}

function copyToClip(t){
	if (window.clipboardData){
		window.clipboardData.setData("Text", t);
	}else if (window.netscape){
		try {
			netscape.security.PrivilegeManager.enablePrivilege('UniversalXPConnect');
		} catch (e) {
			alert("about:config -> signed.applets.codebase_principal_support=false");
			return;
		}
		var clip = Components.classes['@mozilla.org/widget/clipboard;1'].createInstance(Components.interfaces.nsIClipboard);
		if (!clip) {return;}
		var trans = Components.classes['@mozilla.org/widget/transferable;1'].createInstance(Components.interfaces.nsITransferable);
		if (!trans) {return;}
		trans.addDataFlavor('text/unicode');
		var str = new Object();
		var len = new Object();
		var str = Components.classes["@mozilla.org/supports-string;1"].createInstance(Components.interfaces.nsISupportsString);
		var copytext=t;
		str.data=copytext;
		trans.setTransferData("text/unicode",str,copytext.length*2);
		var clipid=Components.interfaces.nsIClipboard;
		if (!clip) {return false;}
		clip.setData(trans,null,clipid.kGlobalClipboard);
	}
	return;
}

function toggleTab(prefix, current, maxnum) {
	for(i = 1; i <= maxnum;i++) {
		dom(prefix + '_' + i).className = '';
		dom(prefix + '_tab_' + i).style.display = 'none';
	}
	dom(prefix + '_' + current).className = 'current';
	dom(prefix + '_tab_' + current).style.display = '';
}

function checkAll(em)
{
	var form=em.form;
	for(var i = 0; i < form.elements.length; i++) {
		var e = form.elements[i];
		if(e.type == "checkbox" && e.className=='select' ) {
			e.checked =em.checked;
		}
	}
}

function addEvent(obj, evt, func, eventobj){
	eventobj = !eventobj ? obj : eventobj;
	if(obj.addEventListener) {
		obj.addEventListener(evt, func, false);
	} else if(eventobj.attachEvent) {

		obj.attachEvent('on' + evt, func);
	}
}


function removeEvent(obj, evt, func, eventobj) {
	eventobj = !eventobj ? obj : eventobj;
	if(obj.removeEventListener) {
		obj.removeEventListener(evt, func, false);
	} else if(eventobj.detachEvent) {
		obj.detachEvent('on' + evt, func);
	}
}

var csssaves= new Array();
function loadCss(file) {
	if(!csssaves[file]) {
		css = document.createElement('link');
		css.type = 'text/css';
		css.rel = 'stylesheet';
		css.href = WEB_URL+'/images/css/'+ file + '.css';
		var headtag = document.getElementsByTagName("head")[0];
		headtag.appendChild(css);
		csssaves[file] = 1;
	}
}

function loadScript(src, text, charset) {
	var scriptdom = document.createElement("script");
	scriptdom.type = "text/javascript";
	scriptdom.charset = charset ? charset : (is_firefox ? document.characterSet : document.charset);
	try {
		if(src) {
			scriptdom.src = src;
		} else if(text){
			scriptdom.text = text;
		}
		dom('ajax_parent').appendChild(scriptdom);
	} catch(e) {}
}

function styleSelectBoxes(isshow) {
	isshow=isshow?1:0;
	var istest = isshow ?0:1;
	var style=['hidden','visible'];
	var selects=document.body.getElementsByTagName('SELECT');
	for(i = 0;i < selects.length; i ++) {
		if(selects[i].style.visibility != style[isshow]) {
			selects[i].style.visibility = style[isshow];
		}
	}
}

function styleFlashs(isshow) {
	var flashs;
	if(is_ie) {
		flashs= document.body.getElementsByTagName("OBJECT");
	} else {
		flashs = document.body.getElementsByTagName("EMBED");
	}

	if(!isshow){
		for(i = 0;i < flashs.length; i ++) {
			if(flashs[i].style.visibility != 'hidden') {
				flashs[i].setAttribute("bakvisible", flashs[i].style.visibility);
				flashs[i].style.visibility = 'hidden';
			}
		}
	}else{
		for(i = 0;i < flashs.length; i ++) {
			if(flashs[i].attributes['bakvisible']) {
				flashs[i].style.visibility = flashs[i].attributes['bakvisible'].nodeValue;
				flashs[i].removeAttribute('bakvisible');
			}
		}
	}
}

function getOffset(obj) {
	var left_offset = 0, top_offset = 0;

	if(obj.getBoundingClientRect){
		var rect = obj.getBoundingClientRect();
		var scrollTop = Math.max(document.documentElement.scrollTop, document.body.scrollTop);
		var scrollLeft = Math.max(document.documentElement.scrollLeft, document.body.scrollLeft);
		if(document.documentElement.dir == 'rtl') {
			scrollLeft = scrollLeft + document.documentElement.clientWidth - document.documentElement.scrollWidth;
		}
		left_offset = rect.left + scrollLeft - document.documentElement.clientLeft;
		top_offset = rect.top + scrollTop - document.documentElement.clientTop;
	}
	if(left_offset <= 0 || top_offset <= 0) {
		left_offset = obj.offsetLeft;
		top_offset = obj.offsetTop;
		while((obj = obj.offsetParent) != null) {
			left_offset += obj.offsetLeft;
			top_offset += obj.offsetTop;
		}
	}
	return { 'left' : left_offset, 'top' : top_offset };
}

function display(id) {
	dom(id).style.display = dom(id).style.display == '' ? 'none' : '';
}

function shadowOpacity(obj,endInt) {
	if(is_ie) {
		obj.filters.alpha.opacity+=10;
		if(obj.filters.alpha.opacity<endInt) {
			setTimeout(function(){shadowOpacity(obj,endInt)},50);
		}
	}else{
		var al=parseFloat(obj.style.opacity);al+=0.05;
		obj.style.opacity=al;
		if(al<(endInt/100)) {
			setTimeout(function(){shadowOpacity(obj,endInt)},50);
		}
	}
}

function hash(string, length) {
	var length = length ? length : 32;
	var start = 0;
	var i = 0;
	var result = '';
	filllen = length - string.length % length;
	for(i = 0; i < filllen; i++){
		string += "0";
	}
	while(start < string.length) {
		result = stringxor(result, string.substr(start, length));
		start += length;
	}
	return result;
}

function stringxor(s1, s2) {
	var s = '';
	var hash = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	var max = Math.max(s1.length, s2.length);
	for(var i=0; i<max; i++) {
		var k = s1.charCodeAt(i) ^ s2.charCodeAt(i);
		s += hash.charAt(k % 52);
	}
	return s;
}

var evalscripts = new Array();
function evalscript(s) {
	if(s.indexOf('<script') == -1) return s;
	var p = /<script[^\>]*?>([^\x00]*?)<\/script>/ig;
	var arr = new Array();
	while(arr = p.exec(s)) {
		var p1 = /<script[^\>]*?src=\"([^\>]*?)\"[^\>]*?(reload=\"1\")?(?:charset=\"([\w\-]+?)\")?><\/script>/i;
		var arr1 = new Array();
		arr1 = p1.exec(arr[0]);
		if(arr1) {
			appendscript(arr1[1], '', arr1[2], arr1[3]);
		} else {
			p1 = /<script(.*?)>([^\x00]+?)<\/script>/i;
			arr1 = p1.exec(arr[0]);
			appendscript('', arr1[2], arr1[1].indexOf('reload=') != -1);
		}
	}
	return s;
}

function appendscript(src, text, reload, charset) {
	var id = hash(src + text);
	if(!reload && in_array(id, evalscripts)) return;
	if(reload && dom(id)) {
		dom(id).parentNode.removeChild(dom(id));
	}

	evalscripts.push(id);
	var scriptNode = document.createElement("script");
	scriptNode.type = "text/javascript";
	scriptNode.id = id;
	scriptNode.charset = charset ? charset : (is_firefox ? document.characterSet : document.charset);
	try {
		if(src) {
			scriptNode.src = src;
		} else if(text){
			scriptNode.text = text;
		}
		dom('ajax_parent').appendChild(scriptNode);
	} catch(e) {}
}

function ajaxOutHtml(s) {
	if(s == null || s=='' || isUndefined(s))return '';

	if(s.indexOf('xmlerror') != -1)
	{
		s = '<div class="errpop">'+s+'</div>';
	}
	else {
		evalscript(s);
	}
	return s;

}

function doane(event) {
	e = event ? event : window.event;
	if(!e) return;
	if(is_ie) {
		e.returnValue = false;
		e.cancelBubble = true;
	} else if(e) {
		e.stopPropagation();
		e.preventDefault();
	}
}

function showLoad(isshow,msg,pos) {
	if(isshow===0){ isshow='none'; }else{ isshow='block' }
	var msg = msg ? msg : 'Load...';
	var pos = pos ? 1:0;
	var load=dom('ajax_load');
	load.innerHTML = msg;
	load.style.display = isshow;
	if(!pos){
		load.style.bottom='0';
		load.style.top='auto';
	}else{
		load.style.bottom='auto';
		load.style.top='0';
	}
}

var ajaxhistory = new Array();
var isdebug=1;
var isattack=isUndefined(isattack) ? 0 : parseInt(isattack);
var isrequestnew=isUndefined(isrequestnew) ? 0 : parseInt(isrequestnew);
var ArtAjax= {
	url: '',
	postString:'',
	resultHandle: '',
	resultType: '',
	loadid:'',
	showid:'',
	isload:0,
	ajax: null,
	setShowId: function(sid) {
		ArtAjax.showid = typeof sid == 'object' ? sid : dom(sid);
	},
	setLoadId: function(lid) {
		ArtAjax.loadid = typeof lid == 'object' ? lid : dom(lid);
	},
	loading: function() {
		var obj=ArtAjax;
		if(obj.isload && (obj.ajax.readyState != 4 || obj.ajax.status != 200)) {
			obj.loadid.style.display = '';
			obj.loadid.innerHTML = '<span><img src="' + WEB_IMG + 'loading.gif"> </span>';
		}
	},
	createAjax: function(){

		ArtAjax.url='';
		ArtAjax.resultHandle='';
		ArtAjax.resultType='';
		ArtAjax.showid='';
		ArtAjax.loadid='';
		ArtAjax.isload=0;
		ArtAjax.postString='';
		var xmlrequest = null;
		if(window.XMLHttpRequest) {
			xmlrequest = new XMLHttpRequest();
			if(xmlrequest.overrideMimeType) {
				xmlrequest.overrideMimeType('text/xml');
			}
		} else if(window.ActiveXObject) {
			var versions = ['Microsoft.XMLHTTP', 'MSXML.XMLHTTP', 'Microsoft.XMLHTTP', 'Msxml2.XMLHTTP.7.0', 'Msxml2.XMLHTTP.6.0', 'Msxml2.XMLHTTP.5.0', 'Msxml2.XMLHTTP.4.0', 'MSXML2.XMLHTTP.3.0', 'MSXML2.XMLHTTP'];
			for(var i=0; i<versions.length; i++) {
				try {
					xmlrequest = new ActiveXObject(versions[i]);
					if(xmlrequest) {
						ArtAjax.ajax=xmlrequest;
						return;
					}
				} catch(e) {}
			}
		}
		ArtAjax.ajax=xmlrequest;

	},
	ajaxHandle: function(){
		//typeof this = XMLHttpRequest Object;
		var obj=ArtAjax;
		var xmlobj = ArtAjax.ajax;//?ArtAjax.ajax:obj.ajax;
		if(xmlobj.readyState == 4 && xmlobj.status == 200) {
			if(obj.isload)
			{
				obj.loadid.style.display='none';
			}

			if(obj.resultType == 'HTML') {				
				obj.resultHandle(xmlobj.responseText, xmlobj);
			}
			else if(obj.resultType == 'JSON') {
				var temp=xmlobj.responseText;
				temp=jsonParse(temp);
				obj.resultHandle(temp, xmlobj);
			}
			else if(obj.resultType == 'XML') {
				if(obj.ajax.responseXML.lastChild) {
					obj.resultHandle(xmlobj.responseXML.lastChild.firstChild.nodeValue, xmlobj);
				} else {
					if(isdebug){
						var err='xmlerror:\n'+mb_strcut(xmlobj.responseText,200);
						obj.resultHandle(err, xmlobj);
					}
				}
			}
			else{obj.resultHandle(xmlobj.responseText, xmlobj); }
		}
	},
	get: function(tourl,resfun){

		if(isrequestnew && in_array(tourl, ajaxhistory)) {
			return false;
		} else {
			ajaxhistory.push(tourl);
		}

		var obj = ArtAjax;//this;
		obj.loading();
		obj.url=tourl;
		obj.resultHandle=resfun;
		obj.ajax.onreadystatechange=obj.ajaxHandle;

		var delay = isattack && 1 ? 1000 : 100;

		if(window.XMLHttpRequest) {
			setTimeout(function(){
				obj.ajax.open('GET', tourl);
				obj.ajax.send(null);}, delay);
		} else {
			setTimeout(function(){
				obj.ajax.open("GET", tourl, true);
				obj.ajax.send();}, delay);
		}


	},
	post: function(tourl, poststr, resfun){
		if(isrequestnew && in_array(tourl, ajaxhistory)) {
			return false;
		} else {
			ajaxhistory.push(tourl);
		}

		var obj = ArtAjax;//this;
		obj.loading();
		obj.url=tourl;
		obj.postString=poststr;
		obj.resultHandle = resultHandle;
		obj.ajax.onreadystatechange = obj.ajaxHandle;

		obj.ajax.open('POST', tourl);
		obj.ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		obj.ajax.send(poststr);
	}

}

function ajaxPost(tourl,restype,poststr,showid,fun) {
	restype=isUndefined(restype)?'XML':restype;
	var resarr=['HTML','XML','JSON'];
	if(!in_array(restype,resarr))return ;
	ArtAjax.createAjax();
	ArtAjax.resultType=restype;
	if(showid){
		ArtAjax.setShowId(showid);
	}
	ArtAjax.post(tourl,poststr,function(res,obj) {
		res=ajaxOutHtml(res);
		if(showid){
			ArtAjax.showid.style.display='';
			ArtAjax.showid.innerHTML=res;
		}
		if(typeof fun =='function'){
			fun(res);
		}
	});
}

function ajaxCall(tourl,fun,restype)
{
	if(typeof fun !='function'){alert('ajaxArt not function args!');return false;}
	restype=isUndefined(restype)?'JSON':restype;
	ArtAjax.createAjax();
	ArtAjax.resultType=restype;
	ArtAjax.get(tourl,fun);
}

function ajaxGet(tourl,restype,showid,loadid,fun)
{
	restype=isUndefined(restype)?'XML':restype;
	var resarr=['HTML','XML','JSON'];
	if(!in_array(restype,resarr))return ;
	ArtAjax.createAjax();
	if(!isUndefined(loadid) && loadid !=null && loadid != ''){
		ArtAjax.isload=1;
		ArtAjax.setLoadId(loadid);
	}
	ArtAjax.resultType=restype;
	ArtAjax.setShowId(showid);	
	ArtAjax.get(tourl,function(res,obj) {
		ArtAjax.showid.style.display='';
		if(restype=='HTML' || restype=='XML' ){
			ArtAjax.showid.innerHTML=ajaxOutHtml(res);
			if (typeof fun == 'function') {
				fun();
			}
		}
	});
}

function ajaxHTML(tourl,showid,loadid) {
	ajaxGet(tourl,'HTML',showid,loadid);
}

function ajaxXML(tourl,showid,loadid,israndom) {
	tourl += (tourl.search(/\?/) > 0 ? '&' : '?') + '&inajax=1'+(isUndefined(israndom)?'':'&r='+Math.random()*1000000);
	ajaxGet(tourl,'XML',showid,loadid);
}

function ajaxJSON(tourl,fun) {
	if(typeof fun !='function'){alert('ajaxJSON not function args!');return false;}
	ArtAjax.createAjax();
	ArtAjax.resultType='JSON';
	ArtAjax.get(tourl,fun);
}

function createFrame(frameid) {
	var artframe = dom(frameid);
	if(artframe == null) {
		if (is_ie) {
			artframe = document.createElement("<iframe name='" + frameid + "' id='" + frameid + "'></iframe>");
		} else {
			artframe = document.createElement("iframe");
			artframe.name = frameid;
			artframe.id = frameid;
		}
		artframe.style.display = 'none';
		dom('ajax_parent').appendChild(artframe);
	}
	return artframe;
}
function removeFrame(frameid){
	dom('ajax_parent').removeChild(dom(frameid));
}

function formPost(formid,showid,noticeclass,submitid,resfun) {
	var framez='';
	artframeid='artframe'+framez;
	var framepost=createFrame(artframeid);
	var sourceform=dom(formid);

	var formPostOnLoad=function () {
		showLoad(0);
		if(dom(submitid)) {
			dom(submitid).disabled = false;
		}
		objframe=dom(artframeid);
		var res='';
		try {
			if(is_ie) {
				res = objframe.contentWindow.document.XMLDocument.text;
			} else {
				res = objframe.contentWindow.document.documentElement.firstChild.nodeValue;
			}
		} catch(e) {
			if(isdebug) {
				if(objframe.contentWindow.document.body.innerText){
					res ='Form post xmlerror:\n'+mb_strcut(objframe.contentWindow.document.body.innerText,200);
				}
			}
		}
		removeFrame(artframeid);
		if(!res)return false;
		res=ajaxOutHtml(res)

		var isok=res.indexOf('[ok]') != -1;
		if(!isok && showid && dom(showid)){
			objshow=dom(showid);
			objshow.innerHTML=res;
			objshow.style.display='';
			if(noticeclass){
				objshow.className = noticeclass;
			}
		}

		if(typeof resfun == 'function') {
			resfun(res);
		}
	};

	addEvent(framepost, 'load', formPostOnLoad);
	showLoad();
	if(dom(submitid)) {
		dom(submitid).disabled = true;
	}
	sourceform.target=artframeid;
	sourceform.action += '&inajax=1';
	sourceform.submit();
	return false;
}

function imgNotice(obj,msg,type)
{
	var types=['error','right'];
	type=type?types[1]:types[0]; msg=msg?' '+msg:'';
	if(!isObject(obj))obj=dom(obj);
	obj.style.display = '';
	obj.innerHTML = '<img src="'+ WEB_IMG +'check_'+type+'.gif" width="16" height="16" />'+msg;
	obj.className = "formNotice";
}

function ismatch(re, str) {
	var matches = re.exec(str);
	return matches != null;
}

function isemail(email)
{
	email = strtrim(email);
	if(mb_strlen(email) < 7 || !ismatch(/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/, email)) {
		return false;
	}
	return true;
}

function rand(min,max){
	if(typeof min == 'undefined' || typeof max=='undefined'){
		return Math.random();
	}
	return parseInt(Math.random()*(max-min+1)+min);
}
function randDeepColor(){
	return 'rgb('+rand(0,128)+','+rand(0,128)+','+rand(0,128)+')';
}
function randShallowColor(){
	return 'rgb('+rand(128,255)+','+rand(128,255)+','+rand(128,255)+')';
}

function stripScript(s) {
	return s.replace(/<script.*?>.*?<\/script>/ig, '');
}

function preg_replace(search, replace, str) {
	var len = search.length;
	for(var i = 0; i < len; i++) {
		re = new RegExp(search[i], "ig");
		str = str.replace(re, typeof replace == 'string' ? replace : (replace[i] ? replace[i] : replace[0]));
	}
	return str;
}

function htmlspecialchars_decode(str){
	return preg_replace(['&nbsp;', '&lt;', '&gt;', '&amp;','&quot;'], [' ', '<', '>', '&','"'], str);
}

function htmlspecialchars(str) {
	return preg_replace(['&', '<', '>', '"'], ['&amp;', '&lt;', '&gt;', '&quot;'], str);
}

function gotop(){
	document.body.scrollTop='1px';
	document.documentElement.scrollTop='1px';
}

var popstack=new Array();
var popisnew=0;
var ispopbg=isUndefined(ispopbg) ? 0 : parseInt(ispopbg);
var backgb='wskmbg';
var backgdcolor= typeof popbgcolor == 'undefined' ? '#FFFFFF' : (popbgcolor==''?'#FFFFFF':popbgcolor);

function showBox(actkey,tourl,msg){
	var actid='art_'+actkey;
	if(popisnew && !isUndefined(popstack[actid]) ){
		if(dom(actid)){
			dom('ajax_parent').removeChild(dom(actid));
		}
		if(dom(backgb)){
			dom('ajax_parent').removeChild(dom(backgb));
		}
		popisnew=0;
	}

	if(!dom(actid))
	{
		popstack[actid]=new Array();
		var back='';
		ispopbg=parseInt(ispopbg);
		if(ispopbg){
			if(!dom(backgb)){
				var viewWidth=viewHeight=scrollTop=0;
				viewHeight=document.compatMode=='CSS1Compat'?document.documentElement.clientHeight:document.body.clientHeight;
				viewWidth=document.compatMode=='CSS1Compat'?document.documentElement.clientWidth:document.body.clientWidth;
				scrollTop = document.body.scrollTop ? document.body.scrollTop : document.documentElement.scrollTop;

				back=document.createElement("div");
				back.id=backgb;
				back.style.zIndex = '900';
				var theBody = document.getElementsByTagName("BODY")[0];
				if (viewHeight > theBody.scrollHeight) {
					popHeight = viewHeight;
				} else {
					popHeight = theBody.scrollHeight;
				}
				if (viewWidth > theBody.scrollWidth) {
					popWidth = viewWidth;
				} else {
					popWidth = theBody.scrollWidth;
				}

				var styleStr="top:0px;left:0px;position:absolute;background:"+backgdcolor+";width:"+popWidth+"px;height:"+popHeight+"px;";
				styleStr+=(is_ie)?"filter:alpha(opacity=50);":"opacity:0.5;";
				back.style.cssText=styleStr;
				document.body.appendChild(back);
			}else{
				back=dom(backgb);
				back.style.display='';
			}
		}
		styleFlashs();
		styleSelectBoxes();
		
		pop = document.createElement('div');
		pop.className = 'artpop';
		pop.id = actid;
		pop.style.position = 'absolute';
		pop.style.zIndex = '999';
		pop.style.width='auto';
		pop.style.display = '';
		pop.innerHTML = '<table cellspacing="0" cellpadding="0" class="boxtable"><tr><td id="boxcontent_' + actkey + '" ><div class="artloadtitle"><span class="fl" ><img src="' + WEB_IMG + 'loading.gif">&nbsp;</span><span class="artclose"><a href="javascript:;" onclick="closeBox(\'' + actkey + '\');">&nbsp;</a></span><div></td></tr></table>';
		dom('ajax_parent').appendChild(pop);
		
		if(tourl.length > 1)
		{
			try{
				tourl += (tourl.search(/\?/) > 0 ? '&' : '?') + 'inbox=1&inajax=1&actkey=' + actkey;
				ajaxCall(tourl,	function(s){s=ajaxOutHtml(s);dom('boxcontent_' + actkey ).innerHTML=s; setBoxPosition(actid);},'XML');
			}catch(e){
				alert(e);
			}
		}
		else if (typeof msg == 'object' ){
			pop.innerHTML = '<table cellspacing="0" cellpadding="0" class="boxtable"><tr><td id="boxcontent_' + actkey + '" ><div class="artpopct" ><h2>'+msg.title+'</h2><a class="close" onclick="closeBox(\'' + actkey + '\');" href="javascript:void(0);"> </a><div class="content" id="wkcen_' + actkey + '">'+msg.msg+'</div></div></td></tr></table>';
		}else{
			pop.innerHTML = '<table cellspacing="0" cellpadding="0" class="boxtable"><tr><td id="boxcontent_' + actkey + '" ><div class="artloadtitle"><span class="fl" >Error!</span><span class="artclose"><a href="javascript:;" onclick="closeBox(\'' + actkey + '\');">&nbsp;</a></span><div></td></tr></table>';
		}
		setBoxPosition(actid);
	}
	else {
		styleFlashs();
		styleSelectBoxes();
		if(ispopbg)dom(backgb).style.display = '';
		dom(actid).style.display = '';
		setBoxPosition(actid);
	}

	popstack[actid]=1;
	popstack.push(actkey);
	doane();
}

function closeBox(actkey){
	var actid='art_'+actkey;
	dom(actid).style.display = 'none';
	if(ispopbg && dom(backgb))dom(backgb).style.display = 'none';
	styleSelectBoxes(1);
	styleFlashs(1);
}

function setBoxPosition(bgid, pos) {
	var bgdom = dom(bgid);
	if(!bgdom) return;

	var postype = isUndefined(pos)?0:parseInt(pos);
	var bgl = bgt = bgw = bgcw = bgh = bgch = 0;

	bgw = bgdom.offsetWidth;
	bgcw = bgdom.clientWidth;
	bgh = bgdom.offsetHeight;
	bgch = bgdom.clientHeight;

	if(postype == 0) {
		var viewWidth=viewHeight=scrollTop=0;
		viewHeight=document.compatMode=='CSS1Compat'?document.documentElement.clientHeight:document.body.clientHeight;
		viewWidth=document.compatMode=='CSS1Compat'?document.documentElement.clientWidth:document.body.clientWidth;
		bgdom.style.left = (viewWidth - bgdom.clientWidth) / 2 + 'px';
		bgt = (viewHeight - bgdom.clientHeight) / 2;
	}

	if(postype == 0) {
		if(is_ie && is_ie < 7) {
			if(postype == 0) bgt += Math.max(document.documentElement.scrollTop, document.body.scrollTop);
		} else {			
			bgdom.style.position = 'fixed';
		}
	}
	if(bgl) bgdom.style.left = bgl + 'px';
	if(bgt) bgdom.style.top = bgt + 'px';
	if(postype == 0 && is_ie && !document.documentElement.clientHeight) {
		bgdom.style.position = 'absolute';
		bgdom.style.top = (document.body.clientHeight - bgdom.clientHeight) / 2 + 'px';
	}
	if(bgdom.style.clip && !is_opera) {
		bgdom.style.clip = 'rect(auto, auto, auto, auto)';
	}
}

window.jsonParse=function(){var r="(?:-?\\b(?:0|[1-9][0-9]*)(?:\\.[0-9]+)?(?:[eE][+-]?[0-9]+)?\\b)",k='(?:[^\\0-\\x08\\x0a-\\x1f"\\\\]|\\\\(?:["/\\\\bfnrt]|u[0-9A-Fa-f]{4}))';k='(?:"'+k+'*")';var s=new RegExp("(?:false|true|null|[\\{\\}\\[\\]]|"+r+"|"+k+")","g"),t=new RegExp("\\\\(?:([^u])|u(.{4}))","g"),u={'"':'"',"/":"/","\\":"\\",b:"\u0008",f:"\u000c",n:"\n",r:"\r",t:"\t"};function v(h,j,e){return j?u[j]:String.fromCharCode(parseInt(e,16))}var w=new String(""),x=Object.hasOwnProperty;return function(h,j){h=h.match(s);var e,c=h[0],l=false;if("{"===c)e={};else if("["===c)e=[];else{e=[];l=true}for(var b,d=[e],m=1-l,y=h.length;m<y;++m){c=h[m];var a;switch(c.charCodeAt(0)){default:a=d[0];a[b||a.length]=+c;b=void 0;break;case 34:c=c.substring(1,c.length-1);if(c.indexOf("\\")!==-1)c=c.replace(t,v);a=d[0];if(!b)if(a instanceof Array)b=a.length;else{b=c||w;break}a[b]=c;b=void 0;break;case 91:a=d[0];d.unshift(a[b||a.length]=[]);b=void 0;break;case 93:d.shift();break;case 102:a=d[0];a[b||a.length]=false;b=void 0;break;case 110:a=d[0];a[b||a.length]=null;b=void 0;break;case 116:a=d[0];a[b||a.length]=true;b=void 0;break;case 123:a=d[0];d.unshift(a[b||a.length]={});b=void 0;break;case 125:d.shift();break}}if(l){if(d.length!==1)throw new Error;e=e[0]}else if(d.length)throw new Error;if(j){var p=function(n,o){var f=n[o];if(f&&typeof f==="object"){var i=null;for(var g in f)if(x.call(f,g)&&f!==n){var q=p(f,g);if(q!==void 0)f[g]=q;else{i||(i=[]);i.push(g)}}if(i)for(g=i.length;--g>=0;)delete f[i[g]]}return j.call(n,o,f)};e=p({"":e},"")}return e}}();