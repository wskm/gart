<?exit?>
{htmltemplate header}

<div class="inner mt5 mb5"  >
	<div id="artnav" >
		<div class="nav iconround">
		<span><a href="{ART_URL}">{lang index}</a>&nbsp;>&nbsp;{$artnav}</span>
		</div>
		{htmltemplate right_search}
	</div>   
</div>

<div id="artmain" >      
	<div id="leftmain" >
	 <div id="contentwrap" class="cwrap cwrap_bg1" >
   
		<div class="hd" >
			<h1>{$article.title}</h1>
			<div class="titleinfo">
				<div class="rinfo">
					<span>{$article.dateline|time}{$data['comefrom']}</span>&nbsp;&nbsp;{if $article.author}<span>{$article.author}</span>&nbsp;&nbsp;{/if}{if $article.comefrom}<span>{$article.comefrom}</span>&nbsp;&nbsp;{/if}<span>{lang browse}:<i class="num" >{$article.views}</i></span>&nbsp;&nbsp;{if $article.replystate !=0 }<a href="{$commenturl}" target="_blank" class="blue" >{lang comment_write}:<i class="num">{$article.replies}</i></a>&nbsp;&nbsp;{/if}
				</div>
				<div class="linfo">
				{lang fontsize}:<span id="fontSmall" title="{lang fontsize_tosmall}" class="small" >T</span>|<span id="fontBig" title="{lang fontsize_tobig}" class="big" >T</span>
				</div>			
			</div>
			 
		</div> 

		<div class="bd" >
			{if $article.summary}<div class="summary">{$article.summary}</div>{/if}
			{if $article.htmltags}
				<div class="titletag">
				TAG:&nbsp;{$article.htmltags}
				</div>  
			{/if}  		
			<div id="othermain" >
			</div>
			<div id="contentmain" >
			{$article.message}
			</div>
			 
		</div> 
		
		<div id="pagewrap">
		{$htmlpage}		
		</div>		
		{if !$article.pagetype}
		<script type="text/javascript" >
		function ArtPage_Draw(page){
			var pagehtml='<div class="pages">';
			pagehtml +='<a class="prev" href="#"  onclick="ArtPage_PrevPage()">&lsaquo;&lsaquo;</a>';
			var pagei=ARTPAGE_COUNT<10?1:(page<10?1:page-4);
			var addsp=1;
			for(;pagei<=ARTPAGE_COUNT;pagei++){
				if(addsp>9)break;
				if(pagei==page){
					pagehtml +='<span class="current">'+pagei+'</span>';
				}else{
					pagehtml +='<a  href="#" onclick="ArtPage_Page('+pagei+')" >'+pagei+'</a>';
				}
				addsp++;
			}

			pagehtml +='<a class="next" href="#"  onclick="ArtPage_NextPage()" >&rsaquo;&rsaquo;</a>';
			dom('pagewrap').innerHTML=pagehtml+'</div>';
		}

		function ArtPage_PrevPage(){
			var p=ArtPage_GetP(ARTPAGE_PAGE-1);
			ArtPage_Page(p);
		}

		function ArtPage_GetP(p){
			return Math.max(1,Math.min(p,ARTPAGE_COUNT));
		}

		function ArtPage_NextPage(){
			var p=ArtPage_GetP(ARTPAGE_PAGE+1);
			ArtPage_Page(p);
		}

		function ArtPage_Base(page){
			for(var i=1;i<=ARTPAGE_COUNT;i++){
				if(i != page){
					dom('artpage_'+i).style.display='none';
				}else{
					dom('artpage_'+i).style.display='';
				}
			}

			ArtPage_Draw(page);
		}

		function ArtPage_Page(page){
			ArtPage_Base(page);
			ARTPAGE_PAGE=page;
		}

		if(typeof ARTPAGE_COUNT =='number' && typeof ARTPAGE_PAGE =='number'){
			if(ARTPAGE_COUNT >1){
				ArtPage_Draw(ARTPAGE_PAGE);
			}
		}

		</script>
		{/if} 
		{if $isNewsKiss}
		<div class="newsact">		
			<div onclick="newsKiss({$aid})" class="kissit"> 
				<span id="kiss_{$aid}" class="kissnum">{$article.kiss}</span>
			</div>
			<div onclick="newsBury({$aid})" class="buryit"> 
				<span id="bury_{$aid}" class="burynum">{$article.bury}</span>
			</div>	
			<div class="clear"></div>			
			<script type="text/javascript">
				function newsKiss(id){
					newsKissBase(id,1);					
				}
				
				function newsBury(id){
					newsKissBase(id,-1);
				}
				
				var clickkiss=0;
				function newsKissBase(id,kisstype){
					if(clickkiss>0){
						alert('{lang click_already}');
						return;
					}
										
					ajaxCall('{ART_URL}wskm.php?act=newskiss&id='+id+'&type='+kisstype,function(s){
						clickkiss++;
						if(s=='1'){
							if(kisstype > 0){
								dom('kiss_'+id).innerHTML=parseInt(dom('kiss_'+id).innerHTML)+1;
							}else{
								dom('bury_'+id).innerHTML=parseInt(dom('bury_'+id).innerHTML)+1;
							}							
						}else{
							alert('{lang click_already}');
						}
					},'XML');
				}
			</script>
		</div>
		{/if}
		<div class="ft">
			
			<div class="isCommt"><span><a href="javascript:;" onclick="window.print()">{lang print}</a></span>{if $article.replystate !=0 }&nbsp;&nbsp;<a id="cmt_2" href="{$commenturl}" target="_blank" >{lang comment_write}(<em id="comNum2">{$article.replies}</em>)</a>{/if}</div>
			<div class="Line"></div>
			 <ul class="ulcommon">
			 	{$switchurl.last}
		 	 	{$switchurl.next}
		 	 </ul>
		</div>
					
	 </div>
	 <script type="text/javascript" language="javascript" src="{ART_URL}ad.php?id=3" ></script>
	 {if !IS_HTML}
	 {if $article.replystate !=0 }
	
	 <div id="commentswrap" class="commentswrap" {if !$comments}style="display:none"{/if} >	 	
         <div class="titleiC"><span  class="mark">{lang comment_new}</span><span  class="subMark"><a href="{$commenturl}" target="_blank" >{lang comment_lookall}</a></span></div>
		 
         {php $commenti=2; }
         {loop $comments $comment}         
         	<div class="comment {if $commenti%2 == 0 }rowodd{/if}">
         		<div class="face"><a href="{$comment.spaceurl}" {if !$comment.anonym}target="_blank"{/if} >{$comment.photo}</a></div>
         		<div class="comment_main">
					<div class="info"><span class="author fl"><a href="{$comment.spaceurl}" {if !$comment.anonym}target="_blank"{/if} >{$comment.uname}</a></span><span class="time fr">{$comment.dateline|time}</span></div>
					
				 	<div class="msgmain" id="commentitem_{$comment.id}" >{$comment.message}</div>
				 	
					<div class="reply" ><a class="blue" id="votehandle{$comment.id}" href="javascript:void(0)" onclick="commentvote({$comment.id})"  >{lang support}(<span class="hit" id="kiss{$comment.id}">{$comment.kiss}</span>)</a>&nbsp;&nbsp;<a class="blue" href="javascript:void(0);" onclick="setquote({$comment.id},'{$comment.uname}','{$comment.dateline|time:HOST}')" >{lang reply}</a></div>
				</div>
				<div class="clear"></div>
		    </div>
		    {php $commenti++; }
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
<button type="submit" class="abtnlgob" name="commentgo" id="commentgo" {if $needlogin}disabled="true"{/if} >{lang submit_a}</button>
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
					var theurl='##url(news/show/id:$aid)##';
					theurl += (theurl.search(/\?/) > 0 ? '&' : '?') + 'r='+Math.ceil(Math.random()*100000000);
					location.href =theurl+'#commentswrap';
				}else{
					imgNotice('sendnotice',s);
				}
			});
		}

		</script>
		</form>
	  
		</div>
		
		{/if}
		{/if}
	 
	</div>
	<div class="rightside">
	{htmltemplate news_right}
 	</div> 	
 	
</div>
<script type="text/javascript" >
addEvent(dom('fontSmall'),'click',function(){
	dom('contentmain').style.fontSize='14px';
});
addEvent(dom('fontBig'),'click',function(){
	dom('contentmain').style.fontSize='18px';
});

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

{htmltemplate footer}