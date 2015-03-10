<?exit?>
{template header}

<div id="artmain">
	<div class=" border_gray mainbg_1">
	
<div class="artpopct  ">
	<h2>{lang forgotpassword_nav}</h2>

	<div class="content" id="wkcen" style="padding-left:150px;" >
		<div class="form_detail" id="fpwwin" > 
		{if $issuccess}
			<div class="item" id="sumdiv" >
				<div id="noticemsg"  >{$success_message}</div>
			</div>
		{else}
			<form id="fpwbox" method="POST" action="##htmlurl(user/ForgotPassword)##" onsubmit="sendrequest();return false;">
			<input type="hidden" name="arthash" value="{ART_HASH}" />
			<div class="item" id="sumdiv" >
				<div id="noticemsg"  ></div>
			</div>
			<div class="item" id="nameDiv" >
				<div class="left" ><label for="uname">{lang username}</label></div>
				<input type="text" autocomplete="off"  tabindex="7"  name="uname"  id="uname" maxlength="30" class="txt"/>
			</div>
			<div class="item" id="pwDiv">
				<div class="left" ><label for="upw">{lang email}</label></div>
				<input type="text" name="email" id="email"  tabindex="8"  maxlength="30" value="" class="txt"/>
			</div>
			{if $isVcode}
			<div  id="vcodeDiv" class="item"  >
				<div class="left" ><label for="vcode">{lang vcode}</label></div>
				<input type="text"  tabindex="9"  onfocus="firstcode()"   value=""  autocomplete="off" name="vcode" id="vcode" value="" style="width:60px;font-size:14px;" maxlength="4"  class="txt "/>
				&nbsp;<span id="vcodeshow" class="vcodediv" ></span>&nbsp;<a onclick="firstget=1;updatevcode()" href="javascript:;">{lang changevcode}</a><span id="vcodenotice" ></span>
				
			</div>		
			{/if}	
			<div class="item" id="submitDiv" >
				<div class="left" >&nbsp;</div>
				<button id="fpwgos"  tabindex="10"  name="fpwgos" type="submit" class="btn fleft">{lang submit}</button>
			 </div>
			 <div class="clear"></div>
			</form>
		{/if}
		</div>
		
		<script type="text/javascript" > 

				var rquest_ok='{lang forgotpassword_sendmailok}';
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

				function sendrequest(){
					var name=dom('uname');
					var email=dom('email');					
					if(emptyfocus(name))return;
					if(emptyfocus(email))return;
					{if $isVcode}
					var vcode=dom('vcode');
					if(emptyfocus(vcode))return;
					{/if}
					if(!isemail(email.value)){
						email.focus();return;
					}
					
					formPost('fpwbox','noticemsg','msgerr','fpwgos',function(s){
						if(s.indexOf('[ok]') != -1){
							dom('fpwbox').innerHTML=dom('sumdiv').innerHTML;
							imgNotice("noticemsg",rquest_ok,1);
						}
					});
				}

				</script>
	</div>
	</div>

	
	</div>
</div>
	
{template footer}