<?exit?>
{htmltemplate header}
<tr class="sp"  >
	<td colspan="2"></td>
</tr>
<div id="rightwrap">
<div id="rtitle">
	<p id="titlenav">{lang poll}&nbsp;&raquo;&nbsp;{if $pollid < 1}{lang add}{else}{lang edit}{/if}</p>
</div>
<div id="rmain">
	<div  class="clear"></div>
	<div class="h10"></div>
	<form method="post" enctype="multipart/form-data" id="article_form" onsubmit="return checkPost()" action="index.php?wskm=poll&act=handle" >
    	<input type="hidden" name="arthash" value="{ART_HASH}" />
    	<input type="hidden" name="pollid" value="{$pollid}" />
    	 <table width="100%"  class="tb_article tbms1"  >

         <tr class="sp"  >
       		<td colspan="2"></td>
       </tr>
       {if !$isuse}
		<tr>
       		<td width="80"><span class="editatitle">{lang title}</span></td>
       		<td>
       		<input type="text" name="others[title]" id="title" value="{$others[poll][title]}" class="editinput" size="50" />
       		</td>
       </tr>
       <tr>
       		<td width="100"><span class="editatitle">{lang multiple_choice}</span></td>
       		<td>
       		<input type="checkbox" name="others[ismore]" value="1" {if $others[poll][ismore]}checked="true"{/if} />
       		</td>
       </tr>
        <tr>
       		<td><span class="editatitle">{lang limit_days}</span></td>
       		<td>
       		<input id="expireday" type="text"  size="3" maxlength="3" class="editinput" name="others[expire]" value="{$others[poll][expire]}" />&nbsp;({lang limit_days_notice})  
       		</td>
       </tr>
		<tr>
			<td valign="top" >
			<span class="editatitle">{lang poll_option}</span>
			</td>
			<td>						
			<div id="addpoll">
			<p class="cleara" >
			<input  type="text"  class="editinput" id="firstoption" name="others[polloptions][{$option_first.optionid}]" value="{$option_first.name}" />
			&nbsp;<a href="javascript:void(0);" onclick="addpolloption()" >{lang option_add}</a></p>
			
			{loop $others[options] $option}
			<p class="cleara" >
			<input type="text"  class="editinput" name="others[polloptions][{$option.optionid}]" value="{$option.name}" />
			</p>
			{/loop}
			</div>
			
			</td>
		</tr>
		<tr >
			<td ></td>
			<td >
			     <div class="btncright mt5">
	            <input type="submit" value="{lang submit}" class="btncommon">            
	            </div>
		    </td>
		</tr>
		{else}
		<tr>
       		<td>&nbsp;<span class="editatitle">{lang poll_use_1}</span></td>
       		<td>
       		<input type="text"  size="60" class="editinput" value='<script type="text/javascript" language="javascript" src="{ART_URL_FULL}poll.php?id={$pollid}" ></script>' />
       		</td>
       </tr>
       <tr>
       		<td valign="top">&nbsp;<span class="editatitle">{lang poll_use_2}</span></td>
       		<td>
       		<input type="text"  size="60" class="editinput" value='<script type="text/javascript" language="javascript" src="{ART_URL_FULL}poll.php?id={$pollid}&type=1" ></script>' />
       		<br />
       		<span class="gray" >{lang poll_use_2_notice}</span>        		
       		</td>
       </tr> 
		{/if}
		<tr class="sp"  >
			<td colspan="2"></td>
		</tr>
		
		<script type="text/javascript" >
		function addpolloption(){
			$('#addpoll').append('<p  class="cleara"><input type="text" class="editinput" name="others[options][]" value="" /></p>');
		}
		</script>
		
        </table>
    </form>
    </div>
    
 <script type="text/javascript" >
 function checkPost(){
	if(!$('#title').val()){
		$('#title').focus();
 		return false;
	}
 	
 	var polloption=$('#firstoption'); 	
 	if(typeof polloption== 'object' && !polloption.val()){
 		polloption.focus();
 		return false;
 	}
 	return true;
 }

 jQuery(function(){
 	table_evenbg('tbms1',1);
 });
</script>
</div>
</div>
{htmltemplate footer}