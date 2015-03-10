<?exit?>
{template header}

{if !IN_AJAX}
<div id="artmain">
	<div class=" border_gray mainbg_1">
{/if}
<div class="artpopct" id="showregs" >
	<h2>{lang pop_reg}</h2>
	{if IN_AJAX}
	<a class="close" onclick="closeBox('reg');" href="javascript:void(0);"> </a>
	{/if}
	<div class="content" id="wkcen_reg">
		<div class="form_detail" {if !IN_AJAX}style="padding-left:150px;"{/if} >
			<div class="" id="showterms" style="display:none;width:550px;text-align:center">
				<h2 style="height:25px; line-height:25px; padding:0 10px;color:#303030; font-size:14px;border-bottom:1px solid #C4C4C4;background:none">{lang terms_service}</h3>
				<div style="text-align:left;float:left;padding:10px 5px;width:90%" >
					<div style="font-size:12px;font-family:Verdana,Helvetica,Arial,sans-serif;" >
					{$rules_txt}
						<div style="text-align:center;padding:10px 5px;">
							<button onclick="dom('registerms').checked = true;dom('showterms').style.display='none';dom('regbox').style.display='';setBoxPosition('art_reg');">{lang terms_agree}</button> &nbsp; <button onclick="history.go(-1)" >{lang terms_notagree}</button>
						</div>
					</div>
				</div>
			</div>

			<form id="regbox" action="##htmlurl(user/checkreg)##" method="POST" onsubmit="regpost();return false;">
					<input type="hidden" name="referer" id="referer" value="{$referer}" />
					<input type="hidden" name="arthash" value="{ART_HASH}" />
					<div class="item" id="sumdiv" >
						<div id="noticemsg_reg" ></div>
					</div>
			           <div class="item">
			                <div class="left"><label >{lang username}</label></div>
			                <div class="fl">
							<input type="text"  onblur="checkuname()" maxlength="15" class="txt" name="reguname" id="reguname" tabindex="1" /> 
							<span class="notice" id='regunameexist' >*</span><br><span id='regunamealert'  ></span>
							</div>
							<div class="clear" ></div>
						</div>
			            <div class="item">
			                <div class="left"><label>{lang password}</label></div>
			                <div class="fl">
			                 <input type="password" maxlength="32" class="txt" id="regupw" name="regupw" tabindex="2" /> <span class="notice" >*</span>
			                 </div>
							<div class="clear" ></div>
						</div>
			            <div class="item">
			                <div class="left"><label >{lang password_repeat}</label></div>
			                <div class="fl">
			                <input type="password" maxlength="30"  onblur="isSamepw()" class="txt" id="regupw2" name="regupw2" tabindex="3" /> <span class="notice" id="smaepw" >*</span>
			                </div>
							<div class="clear" ></div>
						</div>
			            <div class="item">
			                <div class="left"><label >{lang email}</label></div>
			                <div class="fl">
			                <input type="text" maxlength="32" class="txt" onblur="checkemail()"  id="regemail" name="regemail" tabindex="3" /> <span class="notice" id="vemail" >*</span><br><span id='emailalert'  ></span>
			                </div>
							<div class="clear" ></div>
						</div>
						{if $isVcode}
			            <div class="item">
			                <div class="left"><label>{lang vcode}</label></div>
			                <div class="fl">
							<input type="text"  tabindex="9"  onfocus="firstcode2()"   value=""  autocomplete="off" name="regvcode" id="regvcode" value="" style="width:60px;font-size:14px;" maxlength="4"  class="txt"/>
							<span id="vcodeshow2" class="vcodediv" ></span>&nbsp;<a onclick="firstget2=1;updatevcode2()" href="javascript:;">{lang changevcode}</a>								
							</div>
							<div class="clear" ></div>
						</div>
						{/if}
						{if $isterm}
						<div class="item">
						 <div class="left">&nbsp;</div>
						 <div class="fl">
			                <input type="checkbox" id="registerms" name="registerms" value="1"  checked />{lang terms_seeandagree}<a onclick="showterm()" class=" blue " href="javascript:;">{lang terms_service}</a>
			                </div>
							<div class="clear" ></div>

						</div>
						{/if}
						
						<div class="item">
							<div class="left">&nbsp;</div>		
							<div class="fl">					
			                <button id="reggos"  tabindex="10"   name="reggos" type="submit" class="btn fleft">{lang reg}</button>&nbsp;			               
			                </div>
							<div class="clear" ></div>
						</div>
						
			      </form>
			   </div>
		</div>
		
		
		<script type="text/javascript" > 
		var lang_uname_tooshort='{lang uname_tooshort}';
		var lang_uname_toolong='{lang uname_toolong}';
		var lang_terms_agree='{lang reg_terms_agree}';
		var lang_regin_ok='{lang reg_ok}';
		var lang_regin_ok2='{lang reg_ok2}';
		var lang_curl='{lang click_url}';
		var firstget2=0;
		var lastreguname='*';		
		var posterr=1;
		
		//function reginput() {
		//	dom('reguname').focus();
		//}
		//setTimeout('reginput()', 350);
				
		function updatevcode2() {
			ajaxXML('{ART_URL}wskm.php?act=updatevcode&tab=2&random='+Math.random(), 'vcodeshow2','vcodeshow2');
			dom('regvcode').focus();
		}
		function firstcode2()
		{
			if(firstget2==0){
				updatevcode2();
			}
			firstget2=1;
		}

		function isSamepw()
		{
			if(dom('regupw').value.length<1 || dom('regupw').value != dom('regupw2').value)
			{
				imgNotice('smaepw','');
				return false;
			}
			else if(dom('regupw').value && dom('regupw2').value){
				imgNotice('smaepw','',1);
				return true;
			}
		}

		function checkuname() {
			var reguname = strtrim(dom('reguname').value);
			dom('reguname').value=reguname;
			if( reguname == lastreguname) {
				return;
			} else {
				lastreguname = reguname;
			}
			var ulen = reguname.replace(/[^\x00-\xff]/g, "zz").length;
			if(ulen < 3 || ulen > 15) {
				imgNotice('regunamealert',ulen < 3?lang_uname_tooshort:lang_uname_toolong);dom('regunameexist').style.display='none';return;
			}
			else{ dom('regunamealert').style.display='none'; }

			ajaxCall('{ART_URL}wskm.php?act=checkuname&uname='+ (is_ie && document.charset == 'utf-8' ? encodeURIComponent(reguname) : reguname),function(s,obj){
				if(s=='[ok]'){
					posterr=0;
					imgNotice('regunameexist','',1);
				}
				else if(s){
					posterr=1;
					imgNotice('regunamealert',s);dom('regunameexist').style.display='none';
				}
			},'XML');
		}

		function checkemail()
		{
			var mail=dom('regemail');
			mail.value=strtrim(mail.value);
			if(!isemail(mail.value)){
				imgNotice('vemail');dom('emailalert').style.display='none';
			}else{
				//imgNotice('emailalert','',1);
				ajaxCall('{ART_URL}wskm.php?act=checkemail&email='+ (is_ie && document.charset == 'utf-8' ? encodeURIComponent(mail.value) : mail.value),function(s,obj){
					if(s=='[ok]'){
						posterr=0;
						imgNotice('vemail','',1);dom('emailalert').style.display='none';
					}
					else if(s){
						posterr=1;
						imgNotice('emailalert',s);dom('vemail').style.display='none';
					}
				},'XML');
			}
		}

		function regpost()
		{
			var refererurl=dom('referer').value;
			var uname= dom('reguname');
			var upw= dom('regupw');
			var upw2= dom('regupw2');
			var email= dom('regemail');
			uname.value=strtrim(uname.value);
			if(emptyfocus(uname))return;
			if(emptyfocus(upw))return;
			if(emptyfocus(upw2))return;
			if(emptyfocus(email))return;			
			if(!isSamepw())return;
			
			{if $isVcode}
			var vcode= dom('regvcode');
			vcode.value=strtrim(vcode.value);			
			if(emptyfocus(vcode))return;
			if(vcode.value.length!=4){ vcode.focus();return; }
			{/if}

			var isregtrerm=true;
			{if $isterm}
			isregtrerm=dom('registerms').checked;
			if(!isregtrerm){alert(lang_terms_agree); return; }
			{/if}
			
			if(posterr)return;
			if(uname.value && upw.value && upw2.value && email.value && isregtrerm ){
				formPost('regbox','noticemsg_reg','msgerr','reggos',function(s){
					if(s.indexOf('[ok]') != -1){
						dom('regbox').innerHTML=dom('sumdiv').innerHTML;
						var sendverify=s.indexOf('[ok]2') != -1 ? 1:0;
						var formsg=sendverify ? lang_regin_ok2 : lang_regin_ok;
						imgNotice("noticemsg_reg",formsg+'&nbsp;&nbsp;<a class="grey uline" href="'+refererurl+'">'+lang_curl+'</a>',1);
						refererurl=refererurl != ''?refererurl:WEB_URL;
						
						dom('noticemsg_reg').style.padding='0 80px';
						if(refererurl.indexOf('login') != -1 || refererurl.indexOf('reg') != -1 ){
							refererurl=WEB_URL;
						}
						var waittime=1500;
						if(sendverify){
							ajaxXML('##url(user/emailverify)##');
							waittime=5000;
						}
						setTimeout(function(){location.href=refererurl;},waittime);
					}
				});
			}
		}

		var iscreat=0;
		var bakw=bakh=baktop=bakleft=0;
		var tytop=tyleft='';
		
		function showterm(){
			var termdiv=dom('showterms');
			var regbox=dom('regbox');
			termdiv.style.display='';
			regbox.style.display='none';
			setBoxPosition('art_reg');
		}

		</script>
</div>

		<div class="clear"></div>
		
		{if !IN_AJAX}
	</div>
</div>
{/if}
{template footer}