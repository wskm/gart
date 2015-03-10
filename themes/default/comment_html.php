<?exit?>
{htmltemplate header}

<div class="inner mt5 mb5"  >
	<div class="comment_title" >
			<h1><a href="##url(news/show/id:$news.aid)##" target="_blank" >{$news.title}</a></h1>
	</div>
</div>
   
<div id="artmain" > 
	<div id="leftmain" >
	 
	 {if $comments}
	 <div id="commentswrap" class="commentswrap" style="margin-top:0" >	 	
         <div class="titleiC"><span  class="mark">{lang comment_list}</span></div>
		 
         {php $i=2; }
         {loop $comments $comment}         
         	<div class="comment {if $i%2 == 0 }rowodd{/if}">
				<div class="face"><a href="{$comment.spaceurl}" {if !$comment.anonym}target="_blank"{/if} >{$comment.photo}</a></div>
         		<div class="comment_main">

         			<div class="info"><span class="author fl"><a href="{$comment.spaceurl}" {if !$comment.anonym}target="_blank"{/if} >{$comment.uname}</a></span><span class="time fr">{$comment.dateline|time}&nbsp;#{$floori}</span></div>
					
				 	<div class="msgmain" id="commentitem_{$comment.id}" >{$comment.message}</div>
				 	
					<div class="reply"><a class="blue" id="votehandle{$comment.id}" href="javascript:void(0)" onclick="commentvote({$comment.id})"  >{lang support}(<span class="hit" id="kiss{$comment.id}">{$comment.kiss}</span>)</a>&nbsp;&nbsp;<a class="blue" href="javascript:void(0);" onclick="setquote({$comment.id},'{$comment.uname}','{$comment.dateline|time:HOST}')" >{lang reply}</a></div>
					
				</div>
				<div class="clear"></div>
		    </div>
		    {php $i++; $floori--;}
		 {/loop}
		 <div class="height10" ></div>
	 </div>
	
	<script type="text/javascript">
	var sendcount=0;
	var maxsend=3;
	var votehands=[];
	function sendvote(cmid){
		ajaxCall('{ART_URL}wskm.php?act=commentvote&arthash={ART_HASH}&id='+cmid,function(s){
			if(s=='1'){
				var thevote=dom('kiss'+cmid);
				thevote.innerHTML = parseInt(thevote.innerHTML) +1;
				sendcount++;
			}else if(s=='-2'){
				alert('{lang cvote_needlogin}');
			}else if(s=='-1'){ 
				alert('{lang cvote_noself}');
			}else{
				alert('{lang cvote_already}');
			}
			dom('votehandle'+cmid).style.color='gray';
		},'XML');
	}

	function commentvote(cmid){
		if(cmid<1)return;
		if(sendcount < maxsend && !in_array(cmid,votehands)){
			votehands.push(cmid);
			sendvote(cmid);
			return;
		}
		alert('{lang cvote_wait}');
	}

	</script>
	
	  {if $htmlpage}
	  <div id="comment_page" >
		 <div class="fr">
		 {$htmlpage}
		 </div>
		 <div class="clear"></div>
	  </div>
	  {/if}
	 
	 {/if}
	 
	<div class="sendcomment" id="sendcomment" >
		<form method="post" action="##htmlurl(comment/add)##" id="commentForm" name="commentForm" onsubmit="sendcomment();return false;" >
		<table width="595" height="0%" cellspacing="0" cellpadding="0" border="0">
			<tbody>
			<tr>
			  <td>&nbsp;</td>
			  </tr>
			<tr>
				<td>
				<input type="hidden" value="{ART_HASH}" name="arthash" >
				<input type="hidden" value="{$aid}" name="aid" >
				<input type="hidden" value="{PAGE_SELF}" name="request_url" >
								
		        <span id="loginbox" >
		        {if UID}
				<b>{UNAME}</b>
				{else}
					<div class="abtnrw abtn fl">
					<button type="button" class="abtnlw" onclick="showBox('login','##htmlurl(user/login)##');" name="loginbutton" id="loginbutton" >{lang login}</button>
					</div>
					<div class="abtnrw abtn fl">
					<button type="button" class="abtnlw" onclick="showBox('reg','##htmlurl(user/reg)##');" name="regbutton" id="regbutton" >{lang reg}</button>
					</div>
		        {/if}
		        </span>
		        
		        {if $article.replystate ==2 }
		        <span id="anonymbox" style="display:none" >{lang anonym} <input type="text" name="anonym" id="anonym" value="{lang visitor}" maxlength="30" class="text" >&nbsp;</span><span id="otherbox">{lang anonymous}<input type="checkbox" name="isanonym" value="1"  onclick="switchdisplay(this.checked);" /></span>
		        <script type="text/javascript" >
		        function switchdisplay(ischecked){
		        	if(ischecked){
		        		dom('anonymbox').style.display='';
		        		dom('loginbox').style.display='none';
		        	}else{
		        		dom('anonymbox').style.display='none';
		        		dom('loginbox').style.display='';
		        	}

		        }
		        </script>
		        {/if}
				</td>
			</tr>
			<tr>
			  <td height="6"></td>
			</tr>
			<tr>
				<td width="64%" align="left">
				   <textarea rows="7" {if $needlogin}disabled="true"{/if} name="content" {if $isVcode}onfocus="msgFocus(this)"{/if} id="content">{if $needlogin}{lang comment_needlogin}{/if}</textarea>
				   <div id="sendnotice"></div>
				</td>
			</tr>
			<tr>
			  <td>&nbsp;</td>
			  </tr>
			<tr>
			  <td align="left" >
				  <div class="actrow" >
				  	<div class="abtnrgob abtn fl">
<button type="submit" class="abtnlgob" name="commentgo" {if $needlogin}disabled="true"{/if} >{lang submit_a}</button>
</div>&nbsp;<input type="text" name="vcode" maxlength="4" id="vcode" style="display:none" size="4" class="txt">&nbsp;<span id="vcodeshow" class="vcodediv"></span>
				  </div>
			  </td>		
			</tr>
			<tr>
				<td >&nbsp;</td>
			</tr>
			</tbody>
		</table>
		<script type="text/javascript" >
		var isfirst=true;
		function msgFocus(txt){
			if(isfirst){updatevcode();dom('vcode').style.display='';}
			isfirst=false;
		}

		function updatevcode() {
			ajaxXML('{ART_URL}wskm.php?act=updatevcode&random='+Math.random(), 'vcodeshow','vcodeshow');
		}

		function sendcomment(){
			var msg=dom('content');
			msg.value=strtrim(msg.value);
			if(!isfirst && strlen(dom('vcode').value) !=4){dom('vcode').focus();return ; }
			if(msg.value.length < 3){
				imgNotice('sendnotice','{lang comment_tooshort}');
				return;
			}
			
			var notice=dom('sendnotice');
			formPost('commentForm','sendnotice','','commentgo',function(s){
				if(s=='[ok]'){
					var theurl='##url(comment/list/id:$aid)##';
					theurl += (theurl.search(/\?/) > 0 ? '&' : '?') + 'r='+Math.ceil(Math.random()*100000000);
					location.href =theurl+'#commentswrap';
				}else{
					imgNotice('sendnotice',s);
				}
			});
		}

		function setquote(msgid,author,time){
			var objcontent=dom('content');
			if(objcontent){
				var quotestr=htmlspecialchars_decode(dom('commentitem_'+msgid).innerHTML);
				quotestr=quotestr.replace(/\<div.*?\>.*?(\n)*.*?<\/div>/igm,'');
				quotestr=mb_strcut(quotestr,250).replace(/<.+>/ig,'');
				var msg='[quote][size=12px][color=#777777][b]'+author+'[/b]  '+time+'[/color][/size]\n'+quotestr+'[/quote]\n';
				objcontent.focus();
				objcontent.value=(msg)+objcontent.value;
			}
		}


		</script>
		</form>
	  
		</div>
		
		
	</div>
	<div class="rightside">
		{htmltemplate news_right}
	</div>
</div>

{htmltemplate footer}    
