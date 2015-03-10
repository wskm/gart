<?exit?>
{template header}

{if !IN_AJAX}
<div id="artmain">
	<div class=" border_gray mainbg_1">
{/if}
<div class="artpopct  ">
	<h2>{lang pop_login}</h2>
	{if IN_AJAX}
	<a class="close" onclick="closeBox('login');" href="javascript:void(0);"> </a>
	{/if}
	<div class="content" id="wkcen_login">
		<div class="form_detail" id="loginwin" {if !IN_AJAX}style="padding-left:150px;"{/if} > 
			<form id="loginbox" action="##htmlurl(user/checklogin)##" method="POST" onsubmit="loginpost();return false;">
			<input type="hidden" name="arthash" value="{ART_HASH}" />
			<input type="hidden" name="referer" id="referer" value="{$referer}" />
			<div class="item">
				<div id="noticemsg"  ></div>
			</div>
			<div class="item" id="nameDiv" >
				<div class="left" ><label for="uname">{lang pop_username}</label></div>
				<div class="fl">
				<input type="text" autocomplete="off"  tabindex="7"  name="uname"  id="uname" maxlength="30" class="txt"/>
				</div>
				<div class="clear"></div>
			</div>
			<div class="item" id="pwDiv">
				<div class="left" ><label for="upw">{lang pop_password}</label></div>
				<div class="fl">
				<input type="password" name="upw" id="upw"   tabindex="8"  maxlength="30" value="" class="txt"/>
				<span><a href="##htmlurl(user/ForgotPassword)##" ><i class="blue" >{lang forgotpw}</i></a></span>
				</div>
				<div class="clear"></div>
			</div>
			{if $isVcode}
			<div  id="vcodeDiv" class="item"  >
				<div class="left" ><label for="vcode">{lang vcode}</label></div>
				<div class="fl">
				<input type="text"  tabindex="9"  onfocus="firstcode()"   value=""  autocomplete="off" name="vcode" id="vcode" value="" style="width:60px;font-size:14px;" maxlength="4"  class="txt "/>
				&nbsp;<span id="vcodeshow" class="vcodediv" ></span>&nbsp;<a onclick="firstget=1;updatevcode()" href="javascript:;">{lang changevcode}</a><span id="vcodenotice" ></span>
				</div>
				<div class="clear"></div>
			</div>		
			{/if}	
			<div class="item" id="submitDiv" >
				<div class="left" >&nbsp;</div>
                <div class="fl">
				<button id="logingos"  tabindex="10"  name="logingos" type="submit" class="btn fleft">{lang login}</button>
				<!--
				<input type="hidden" value="1" id="auto_login" name="auto_login"/>
				<input type="checkbox"  tabindex="11"  onchange="if(dom('chk_login').checked==true) {dom('auto_login').value=1;} else {dom('auto_login').value=0;}" id="chk_login" value='1'/>
				-->
				<input type="checkbox"  tabindex="11" id="auto_login" name="auto_login" value='1'/>
				{lang logout_status}
				</div>
				<div class="clear"></div>
			 </div>
			 
			 
			</form>
		</div>
		
		<script type="text/javascript" > 
				function logininput() {
					dom('uname').focus();
				}
				setTimeout('logininput()', 300);

				var lang_vcode_length='{lang vcode_lengtherr}';
				var lang_empty='{lang empty_err}';
				var lang_loginok='{lang login_successed}';
				var lang_curl='{lang click_url}';
				var firstget=0;
				var vcodevalue=oldvcode='';
				function updatevcode() {
					ajaxXML('{ART_URL}wskm.php?act=updatevcode&random='+Math.random(), 'vcodeshow','vcodeshow');
					dom('vcode').focus();
				}
				function firstcode()
				{
					if(firstget==0){
						updatevcode();
					}
					firstget=1;
				} 
				function validvcode()
				{
					if(vcodevalue==oldvcode){ imgNotice('vcodenotice',0);return; }
					else{ oldvcode=vcodevalue; }

					if(isLN(vcodevalue) && vcodevalue.length==4){
						ajaxCall('{ART_URL}wskm.php?act=validvcode&vcode='+ (is_ie && document.charset == 'utf-8' ? encodeURIComponent(vcodevalue) : vcodevalue),function(s,obj){
							if(s=='okvcode' ){
								imgNotice('vcodenotice','',1);dom('noticemsg').style.display='none';
							}
							else{
								imgNotice('vcodenotice','',0);
								dom('noticemsg').style.display='';
								dom('noticemsg').innerHTML=s;
							}

						});
					}
					else{
						imgNotice('vcodenotice','',0);
					}
				}

				
				function loginpost()
				{
					
					var rurl=dom("referer").value;				
					var uname= dom('uname');
					var upw= dom('upw');
					
					var objmsg=dom('noticemsg');
					uname.value=strtrim(uname.value);					
					if(emptyfocus(uname)){ imgNotice(objmsg,lang_empty); return;}
					if(emptyfocus(upw)){ imgNotice(objmsg,lang_empty); return;}
					{if $isVcode}
					var vcode= dom('vcode');
					vcode.value=strtrim(vcode.value);
					if(emptyfocus(vcode)){ imgNotice(objmsg,lang_empty); return;}
					if(vcode.value.length!=4){imgNotice(objmsg,lang_vcode_length); vcode.focus();return; }
					{/if}					
					if(uname.value && upw.value){
						formPost('loginbox','noticemsg','msgerr','logingos',function(s){
							
							if(s.indexOf('[ok]') != -1){
								dom('submitDiv').style.display='none';
								dom('nameDiv').style.display='none';
								dom('pwDiv').style.display='none';
								if(dom('vcodeDiv')){
									dom('vcodeDiv').style.display='none';
								}
																
								rurl=rurl != ''?rurl:WEB_URL;
								imgNotice("noticemsg",lang_loginok+'&nbsp;&nbsp;<a class="grey uline" href="'+rurl+'">'+lang_curl+'</a>',1);								
								dom('noticemsg').style.padding='0 60px';
								if(rurl.indexOf('login') != -1 || rurl.indexOf('reg') != -1 ){
									rurl=WEB_URL+'index.php';
								}
								
								setTimeout(function(){location.href=rurl;},1500);
							}
						});
					}
				}

				</script>
	</div>
	</div>
{if !IN_AJAX}
	</div>
</div>
{/if}
	
{template footer}