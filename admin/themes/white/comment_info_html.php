<?exit?>
{htmltemplate header}
<div id="rightwrap">
	<div id="rtitle">
		<p id="titlenav">{lang comment}</p>
	</div>
	<div id="rmain" >
		<div class="clear"></div>
		
		<form method="post" action="?wskm=article&act=comment&hand=edit">
		<input type="hidden" name="arthash" value="{ART_HASH}" />
		<input type="hidden" name="commentid" value="{$commentid}" />
		<input type="hidden" name="aid" value="{$info.aid}" />
		  <div class="divcommon"  > 
	  	  <table class="tb_article tbms1"  width="100%" id="cateset_tab_1">	  	  
	  	  <tr class="nobg"><td colspan="2">
	  	  <a href="{$info.spaceurl}" {if !$info.anonym}target="_blank"{/if} >{$info.photo}</a>
	  	  </td></tr>
	  	  <tr>
			  	<td width="100" >
			  	<span class="editatitle">ID:</span>
			  	</td>
			  	<td>
			  	{$info.id}&nbsp;&nbsp;[<a href="{$info.articleurl}#commentitem_{$info.id}" class="gray" target="_blank">{lang link}</a>]
			  	</td> 
		   </tr>
		    <tr>
			  	<td valign="top">
			  	<span class="editatitle">{lang username}:</span>
			  	</td>
			  	<td>			  	
			  	{$info.uname}
			  	</td> 
		   </tr>
		    <tr>
			  	<td >
			  	<span class="editatitle">IP:</span>
			  	</td>
			  	<td>
			  	{$info.ip}
			  	</td> 
		   </tr>
		    <tr>
			  	<td >
			  	<span class="editatitle">{lang time}:</span>
			  	</td>
			  	<td>
			  	{$info.dateline|time}
			  	</td> 
		   </tr>
		    <tr>
			  	<td valign="top"  >
			  	<span class="editatitle">{lang comment_content}:</span>
			  	</td>
			  	<td valign="top" >
			  	{$info.message}
			  	</td> 
		   </tr>
		  <tr>
			  	<td >
			  	<span class="editatitle">{lang status}:</span>
			  	</td>
			  	<td>
			  	<input type="radio" name="status" value="1" {if $info.status}checked='true'{/if} />{lang comment_status_normal}&nbsp;<input type="radio" name="status" value="0"  {if !$info.status}checked='true'{/if} />{lang comment_status_verify}
			  	</td> 
		   </tr>
		   <tr>
		   		<td></td>
			  	<td>
			  	<input type="checkbox" name="isdel" value="1" >{lang del}
			  	</td> 
		   </tr>
		   
		   <tr class="sp"><td colspan="2"></td></tr>
	  	  </table>

	  	  
	  	  <table width="100%" >
	  	  <tr>
	  	  <td width="80"></td>
		  <td>
          <div class="btncright mt5">
            <input class="btncommon" type="submit" value="{lang submit}" name="postgos"  />   
            </div>
             <div class="btncright mt5">
            <input class="btncommon" type="button" value="{lang back}" name="z"  onclick="history.go(-1)" />
            </div>
          </td>
		  </tr>
		  </table>
		  
	  	  </div>
		</form>
	</div>	 


	</div>
</div>


</div>

<script type="text/javascript" >
table_evenbg('tbms1');
</script>
{htmltemplate footer}