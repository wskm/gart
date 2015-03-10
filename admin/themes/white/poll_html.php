<?exit?>
{htmltemplate header}
<tr class="sp"  >
	<td colspan="2"></td>
</tr>
<div id="rightwrap">
	<div id="rtitle">
		<p id="titlenav">{lang poll}&nbsp;&raquo;&nbsp;{lang poll_list}</p>
	</div>
	<div id="rmain">
		<div class="ml5"> 
		<a class="btnart" href="index.php?wskm=poll&act=handle"><cite >{lang poll_add}</cite></a>
		</div> 
		<div  class="clear"></div>
		<div class="h10"></div>
		  <form  method="POST" action="index.php?wskm=poll" >
	  	  <input type="hidden" name="arthash" value="{ART_HASH}" />
		  <div id="actcp" > 
		  {if $list}
		  <div class="toolbar_list">
		            
		        <div id="batchAction" >
		        	<div class="chk"><input type="checkbox" onclick="checkAll(this)"  />&nbsp;{lang del}&nbsp;&nbsp;</div>
		        	<div class="chk">
		            <input type="submit"  class="easybtn" value=" {lang submit} " name="articlelistgo" />            
		            </div>   
		            <div class="toolbar_rgiht" >
		            	{$htmlpage}
		            </div>
		        </div>
		    </div>      
		    {/if}      
		    </div>
		    <table width="100%" cellspacing="0" class="tblist tbmsutil" >
		        {if $list}
		        <thead>
		        <tr>
		        	<th class="first" width="25">&nbsp;</th>                    	
		        	<th >ID</th>
		        	<th >{lang title}</th>           	
		            <th width="50" align="left">{lang poll_hits}</th>		            
		            <th width="120" align="center" >{lang wtime}</th>
		            <th width="120" align="center" >{lang expire}</th>
		            <th width="55" align="left" >{lang handle}</th>
		        </tr>
		        </thead> 
		     
		        {loop $list $info}
		        <tr class="tatr2">
		        	<td class="w25" >
		        	<input type="checkbox" name="selects[]" class="select" value="{$info.pollid}"/>
		        	</td>
		        	 <td align="center" width="50">{$info.pollid}</td>
		        	<td  style="overflow:hidden"><a href="{ART_URL}poll.php?showid={$info.pollid}"  target="_blank" >{$info.title}</a></td>                    	
		            <td align="left">{$info.hits}</td>
		            <td align="center">{$info.wtime|time}</td>
		            <td align="center">{if $info.expire}{$info.expire|time}{else}{lang limit_not}{/if}</td>                  
		            <td><a href="index.php?wskm=poll&amp;act=handle&amp;id={$info.pollid}">{lang edit}</a>&nbsp;<a href="index.php?wskm=poll&amp;act=handle&amp;isuse=1&amp;id={$info.pollid}">{lang poll_use}</a></td>
		        </tr>
		         {/loop}
		        {else}
		        <tr >
		            <td colspan="7" class="no_data" >{lang no_data}</td>
		        </tr>
		        {/if}
		    </table>
		    {if $list}
		 	<script type="text/javascript">
		 	function goto_confirm(msg,turl){
		 		if(confirm(msg)){
		 			location.href=turl;
		 		}
		 	}
		 	</script>
		    <div id="actcp2">
		    	  <div class="toolbar_list">
			            
			        <div id="batchAction" >
			        	<div class="chk"><input type="checkbox" onclick="checkAll(this)"  />&nbsp;{lang del}&nbsp;&nbsp;</div>
			        	<div class="chk">
			        	
			            <input type="submit"  class="easybtn" value=" {lang submit} " name="articlelistgo" />            
			            </div>   
			            <div class="toolbar_rgiht" >
			            	{$htmlpage}
			            </div>
			        </div>
			    </div>  
		    </div>
		    <script type="text/javascript" >
		    jQuery(function(){
		    	table_hover('tbmsutil');
		    	if(!$.browser.msie){
		    		$('#btn_addnews_menu').css('width','68px');
		    	}
		    });
				    
		    $('#selectacttype2').html($('#selectacttype').html());
		    $('#selectmovegid2').html($('#selectmovegid').html());
		    </script>
		    <div class="clear"></div>
		    
		    {/if}
			</form>
	
	
    </div>

</div>
{htmltemplate footer}