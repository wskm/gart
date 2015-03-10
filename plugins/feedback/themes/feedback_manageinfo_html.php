<?exit?>
{htmltemplate header}
<div id="rightwrap">
	<div id="rtitle">
		<p id="titlenav">{lang ftitle}&nbsp;&raquo;&nbsp;{lang fcontent}</p>
	</div>
	<div id="rmain" >
		<div class="clear"></div>
		
		<form method="post" action="" >
		<input type="hidden" name="arthash" value="{ART_HASH}" />
		<input type="hidden" name="id" value="{$info.id}" />
		  <div class="divcommon"  > 
	  	  <table class="tb_article tbms1"  width="100%" id="cateset_tab_1">	  	  
	  	  <tr>
			  	<td width="100" >
			  	<span class="editatitle">ID:</span>
			  	</td>
			  	<td>
			  	{$info.id}
			  	</td> 
		   </tr>
		    <tr>
			  	<td valign="top">
			  	<span class="editatitle">{lang author}:</span>
			  	</td>
			  	<td>			  	
			  	{$info.author}
			  	</td> 
		   </tr>
		    <tr>
			  	<td valign="top">
			  	<span class="editatitle">Email:</span>
			  	</td>
			  	<td>			  	
			  	{$info.email}
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
			  	<span class="editatitle">{lang title}:</span>
			  	</td>
			  	<td valign="top" >
			  	{$info.title}
			  	</td> 
		   </tr>
		    <tr>
			  	<td valign="top"  >
			  	<span class="editatitle">{lang message}:</span>
			  	</td>
			  	<td valign="top" >
			  	{$info.message}
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