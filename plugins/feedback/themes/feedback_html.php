<?exit?>
{htmltemplate header}
<link rel="stylesheet" type="text/css" href="{PLUGIN_URL}themes/css/style.css" />
<div class="inner mt5 mb5"  >
	<div id="artnav" >
		<div class="nav iconround">
		<span><a href="{ART_URL}">{lang index}</a>&nbsp;>&nbsp;{$artnav}</span>
		</div>
		{htmltemplate right_search}
	</div>   
</div>

<div id="artmain">

	<div class="feedbackwrap">
		<form action="" method="POST" onsubmit="return checkpost()" >
		<input type="hidden" name="arthash" value="{ART_HASH}" >
		<script type="text/javascript">
		function checkpost(){
			var title=dom('title');
			if(emptyfocus(title)){return false;	}
			var msg=dom('message');
			if(emptyfocus(msg)){return false;	}
			var author=dom('author');
			if(emptyfocus(author)){return false;	}
			//var email=dom('email');
			//if(emptyfocus(email)){return false;	}
			
			{if $isVcode}
			var vcode=dom('vcode');
			if(emptyfocus(vcode)){return false;	}
			{/if}
			return true;
		}

		var isfirst=true;
		function msgFocus(txt){
			if(isfirst){updatevcode();dom('vcode').style.display='';}
			isfirst=false;
		}

		function textchange(obj)
		{
			var maxlen=500;
			var len = mb_strlen(obj.value);			
			if(len > maxlen){
				obj.value=mb_strcut(obj.value,maxlen,true);
			}
		}
		
		function updatevcode() {
			ajaxXML('{ART_URL}wskm.php?act=updatevcode&random='+Math.random(), 'vcodeshow','vcodeshow');
		}
		
		</script>
		<table width="500" class="feedback" >
			<tr style="height:20px"><td></td></tr>	
			<tr>
				<td><span class="fshow" >{lang fsubject}:</span></td>
			</tr>
			<tr>
				<td><input type="text" name="title" id="title" class="title"></td>
			</tr>		
			<tr><td align="left"><span class="fshow" >{lang fwritemsg}:</span></td></tr>
			<tr>
				<td>
				<textarea name="message"  class="mtxt" onkeyup="textchange(this)"  {if $isVcode}onfocus="msgFocus(this)"{/if} id="message" ></textarea>
				</td>
			</tr>
			<tr>
				<td><span class="fshow" >{lang author}:</span></td>
			</tr>
			<tr>
				<td><input type="text"  class="txt" name="author" id="author" ></td>
			</tr>
			<tr>
				<td><span class="fshow" >{lang email}:</span></td>
			</tr>
			<tr>
				<td><input type="text"  class="txt" name="email" id="email"></td>
			</tr>
			{if $isVcode}
			<tr>
				<td><span class="fshow" >{lang vcode}:</span></td>
			</tr>
			<tr>
				<td>
				 <div class="actrow" >
				<input type="text" size="4" maxlength="4" class="txt"  name="vcode" id="vcode" >&nbsp;<span id="vcodeshow" class="vcodediv"></span>
				</div>
				</td>
			</tr>
			{/if}
			<tr>
				<td align="left">
				<div class="abtnrgob abtn fl" style="margin-left:0px;">
				<button type="submit" class="abtnlgob" name="go" >{lang submit_a}</button>
				</div>
				</td>
			</tr>
		</table>
		</form>
	</div>
</div>

{htmltemplate footer}   