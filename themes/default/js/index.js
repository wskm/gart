//contentslider
var featuredcontentslider={
ajaxloadingmsg: 'load…',
bustajaxcache: false,
enablepersist: false,
settingcaches: {},

buildcontentdivs:function(setting){
var alldivs=document.getElementById(setting.id).getElementsByTagName("div")
for (var i=0; i<alldivs.length; i++){
if (this.css(alldivs[i], "contentdiv", "check")){ 
setting.contentdivs.push(alldivs[i])
alldivs[i].style.display="none"
alldivs[i].style.overflow="hidden"
}
}
},

jumpTo:function(fcsid, pagenumber){ 
this.turnpage(this.settingcaches[fcsid], pagenumber)
},

ajaxconnect:function(setting){
var page_request = false
if (window.ActiveXObject){ 
try {
page_request = new ActiveXObject("Msxml2.XMLHTTP")
}
catch (e){
try{
page_request = new ActiveXObject("Microsoft.XMLHTTP")
}
catch (e){}
}
}
else if (window.XMLHttpRequest)
page_request = new XMLHttpRequest()
else
return false
var pageurl=setting.contentsource[1]
page_request.onreadystatechange=function(){
featuredcontentslider.ajaxpopulate(page_request, setting)
}
document.getElementById(setting.id).innerHTML=this.ajaxloadingmsg
var bustcache=(!this.bustajaxcache)? "" : (pageurl.indexOf("?")!=-1)? "&"+new Date().getTime() : "?"+new Date().getTime()
page_request.open('GET', pageurl+bustcache, true)
page_request.send(null)
},

ajaxpopulate:function(page_request, setting){
if (page_request.readyState == 4 && (page_request.status==200 || window.location.href.indexOf("http")==-1)){
document.getElementById(setting.id).innerHTML=page_request.responseText;
this.buildpaginate(setting);
}
},

buildpaginate:function(setting){
this.buildcontentdivs(setting)
var sliderdiv=document.getElementById(setting.id)
var pdiv=document.getElementById("paginate-"+setting.id)
var phtml=""
var toc=setting.toc
var nextprev=setting.nextprev
if (typeof toc=="string" && toc!="markup" || typeof toc=="object"){
for (var i=1; i<=setting.contentdivs.length; i++){
phtml+='<i class="iToc iToc'+i+'"><a href="#'+i+'" class="toc">'+(typeof toc=="string"? toc.replace(/#increment/, i) : toc[i-1])+'</a></i> '
}
phtml=(nextprev[0]!=''? '<i class="iToc iToc'+i+'"><a href="#prev" class="prev">'+nextprev[0]+'</a></i> ' : '') + phtml + (nextprev[1]!=''? '<i class="iToc iToc'+i+'"><a href="#next" class="next">'+nextprev[1]+'</a></i>' : '')
pdiv.innerHTML='<i class="subLineTab"></i><span class="subPageTab">'+phtml+'</span>'
}


var pdivlinks = [];
var pdivlink1=pdiv.getElementsByTagName("a");
var pdivlink2=pdiv.getElementsByTagName("I");
for(var i =0;i<pdivlink1.length;i++){
pdivlinks.push(pdivlink1[i]);
}
for(var i =0;i<pdivlink2.length;i++){
pdivlinks.push(pdivlink2[i]);
}

var toclinkscount=0,  
	self = this; 
for (var i=0; i<pdivlinks.length; i++){
if (this.css(pdivlinks[i], "toc", "check")){
if (toclinkscount>setting.contentdivs.length-1){ 
pdivlinks[i].style.display="none" 
continue
}
pdivlinks[i].setAttribute("rel", ++toclinkscount) 
pdivlinks[i][setting.revealtype]=function(){
var rel = this.getAttribute("rel");
if(self.delayTimer) window.clearTimeout(self.delayTimer);
self.delayTimer = window.setTimeout(function(){featuredcontentslider.turnpage(setting, rel)},setting.delay); 
return false;
}
setting.toclinks.push(pdivlinks[i])
}
else if (this.css(pdivlinks[i], "prev", "check") || this.css(pdivlinks[i], "next", "check")){ 
pdivlinks[i].onclick=function(){
featuredcontentslider.turnpage(setting, this.className)
return false
}
}
}
this.turnpage(setting, setting.currentpage, true)
if (setting.autorotate[0]){ 
pdiv[setting.revealtype]=function(){
return false
}
pdiv["onmouseover"]=function(){
featuredcontentslider.cleartimer(setting, window["fcsautorun"+setting.id])
return true
}
pdiv["onmouseout"]=function(){
featuredcontentslider.autorotate(setting)
return true
}
sliderdiv["onmouseover"]=function(){ 
featuredcontentslider.cleartimer(setting, window["fcsautorun"+setting.id])
return true
}
sliderdiv["onmouseout"]=function(){
featuredcontentslider.autorotate(setting)
return true
}
setting.autorotate[1]=setting.autorotate[1]+(1/setting.enablefade[1]*80) 
this.autorotate(setting)
}
},

urlparamselect:function(fcsid){
var result=window.location.search.match(new RegExp(fcsid+"=(\\d+)", "i")); 
return (result==null)? null : parseInt(RegExp.$1); 
},

turnpage:function(setting, thepage, autocall){
var currentpage=setting.currentpage 
var totalpages=setting.contentdivs.length
var turntopage=(/prev/i.test(thepage))? currentpage-1 : (/next/i.test(thepage))? currentpage+1 : parseInt(thepage)
turntopage=(turntopage<1)? totalpages : (turntopage>totalpages)? 1 : turntopage 
if (turntopage==setting.currentpage && typeof autocall=="undefined") 
return
setting.currentpage=turntopage
setting.contentdivs[setting.prevpage-1].style.display="none" 
setting.contentdivs[turntopage-1].style.zIndex=++setting.topzindex
this.cleartimer(setting, window["fcsfade"+setting.id])
setting.cacheprevpage=setting.prevpage
if (setting.enablefade[0]==true){
setting.curopacity=0
this.fadeup(setting)
}
if (setting.enablefade[0]==false){ 
setting.onChange(setting.prevpage, setting.currentpage)
}
setting.contentdivs[turntopage-1].style.visibility="visible"
setting.contentdivs[turntopage-1].style.display="block"
if (setting.prevpage<=setting.toclinks.length) 
this.css(setting.toclinks[setting.prevpage-1], "selected", "remove")
if (turntopage<=setting.toclinks.length) 
this.css(setting.toclinks[turntopage-1], "selected", "add")
setting.prevpage=turntopage
if (this.enablepersist)
this.setCookie("fcspersist"+setting.id, turntopage)
},


setopacity:function(setting, value){ 
var targetobject=setting.contentdivs[setting.currentpage-1];
targetobject.className = "contentdiv sliderfilter";
if (targetobject.filters && targetobject.filters[0]){ 
if (typeof targetobject.filters[0].opacity=="number") 
targetobject.filters[0].opacity=value*100;
else //IE 5.5
targetobject.style.filter="alpha(opacity="+value*100+")";
}
else if (typeof targetobject.style.MozOpacity!="undefined") 
targetobject.style.MozOpacity=value;
else if (typeof targetobject.style.opacity!="undefined") 
targetobject.style.opacity=value;
setting.curopacity=value;
},

fadeup:function(setting){
if (setting.curopacity<=0.95){ 
this.setopacity(setting, setting.curopacity+setting.enablefade[1])
window["fcsfade"+setting.id]=setTimeout(function(){featuredcontentslider.fadeup(setting)}, 25)
}
else{ 
this.setopacity(setting, 1); //(setting, setting.curopacity-setting.enablefade[1]);
var targetobject=setting.contentdivs[setting.currentpage-1];
targetobject.className = "contentdiv";
if (setting.cacheprevpage!=setting.currentpage) 
setting.contentdivs[setting.cacheprevpage-1].style.display="none" 
setting.onChange(setting.cacheprevpage, setting.currentpage)
}
},

cleartimer:function(setting, timervar){
if (typeof timervar!="undefined"){
clearTimeout(timervar);
clearInterval(timervar);
if (setting.cacheprevpage!=setting.currentpage){ 
setting.contentdivs[setting.cacheprevpage-1].style.display="none";
}
}
},

css:function(el, targetclass, action){
var needle=new RegExp("(^|\\s+)"+targetclass+"($|\\s+)", "ig");
if (action=="check")
return needle.test(el.className);
else if (action=="remove")
el.className=el.className.replace(needle, "");
else if (action=="add")
el.className+=" "+targetclass;
},

autorotate:function(setting){
window["fcsautorun"+setting.id]=setInterval(function(){featuredcontentslider.turnpage(setting, "next")}, setting.autorotate[1]);
},

getCookie:function(Name){
var re=new RegExp(Name+"=[^;]+", "i"); 
if (document.cookie.match(re)) 
return document.cookie.match(re)[0].split("=")[1]; //return its value
return null;
},

setCookie:function(name, value){
document.cookie = name+"="+value;
},

iframeconnect:function(setting){ 
var ifr = document.createElement('iframe');
ifr.id='iframeconnect'+new Date().getTime();
ifr.style.display='none';
var self = this;
var got = function(){
var d = ifr.contentDocument ? ifr.contentDocument : document.frames[ifr.id].document;
document.getElementById(setting.id).innerHTML=d.body.innerHTML;
document.body.removeChild(ifr);
self.buildpaginate(setting);
};
ifr.onload=got;
ifr.onreadystatechange=function(){
if(this.readyState=='complete') got();
};
ifr.src = setting.contentsource[1];
document.body.insertBefore(ifr,document.body.firstChild);
},

init:function(setting){
if(document.getElementById(setting.id)==null) return; 
var persistedpage=this.getCookie("fcspersist"+setting.id) || 1;
var urlselectedpage=this.urlparamselect(setting.id); 
this.settingcaches[setting.id]=setting; 
setting.contentdivs=[];
setting.toclinks=[];
setting.topzindex=0;
setting.currentpage=urlselectedpage || setting.playtab || ((this.enablepersist)? persistedpage : 1);
setting.prevpage=setting.currentpage;
setting.revealtype="on"+(setting.revealtype || "click");
setting.curopacity=0;
setting.onChange=setting.onChange || function(){};
if (setting.contentsource[0]=="inline")
this.buildpaginate(setting);
if (setting.contentsource[0]=="ajax")
this.ajaxconnect(setting);
if (setting.contentsource[0]=="iframe")
this.iframeconnect(setting);
}

}
