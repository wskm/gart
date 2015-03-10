<?exit?>
{htmltemplate header}
<tr class="sp"  >
	<td colspan="2"></td>
</tr>
<div id="rightwrap">
	<div id="rtitle">
		<p id="titlenav">{lang announce}&nbsp;&raquo;&nbsp;{lang ad_list}</p>
	</div>
	<div id="rmain">
		<div class="ml5"> 
		<a class="btnart" href="index.php?wskm=setting&act=adhandle"><cite >{lang ad_add}</cite></a>&nbsp;&nbsp;<a class="btnart" href="index.php?wskm=setting&act=adtype"><cite >{lang adtype_manage}</cite></a>
		</div> 
		<div  class="clear"></div>
		<div class="h10"></div>
		  <form  method="POST" action="index.php?wskm=setting&act=ad" >
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
		        	<th width="25" >ID</th>       	
		        	<th >{lang title}</th> 	
		        	<th width="80">{lang type}</th>
		        	<th width="50" >{lang style}</th>
		            <th width="100" align="center" >{lang ad_begintime}</th>
		            <th width="100" align="center" >{lang ad_endtime}</th>
		            <th width="40" align="center" >{lang sort}</th>
		            <th width="40" align="center" >{lang enabled}</th>		            
		            <th width="40" align="left" >{lang handle}</th>
		        </tr>
		        </thead> 
		     
		        {loop $list $info} 
		        <tr class="tatr2">
		        	<td class="w25" >
		        	<input type="checkbox" name="selects[]" class="select" value="{$info.id}"/>
		        	</td>
		        	<td align="center" >{$info.id}</td>
		        	<td  style="overflow:hidden">{$info.title}</td>
		        	<td align="center">{$info.name}</td> 
		        	<td align="center">{$info.stylename}</td> 
		        	<td align="center">{$info.begintime|time:Y-m-d}</td>
		        	<td align="center">{if $info.endtime}{$info.endtime|time:Y-m-d}{else}{lang ad_endtime_no}{/if}</td>
		        	<td align="center">{$info.displaysort}</td>
		        	<td align="center">
		        	<!--{if $info.status}--><img src="{ATHEME_IMG}ienabled.gif"  title="{lang editable}"/><!--{else}--><img src="{ATHEME_IMG}idisabled.gif" title="{lang editable}"/><!--{/if}-->
		        	</td>
		            <td><a href="index.php?wskm=setting&amp;act=adhandle&amp;id={$info.id}">{lang edit}</a></td>
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