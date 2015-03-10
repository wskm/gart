<?exit?>
{htmltemplate header}
<div id="rightwrap">
	<div id="rtitle">
		<p id="titlenav">{lang flink_manage}&nbsp;&raquo;&nbsp;{lang flink_list}</p>
	</div>
	
	<div id="rmain">
		<div class="ml5">
		<a class="btnart"  href="index.php?wskm=setting&act=friendlink&hand=add" ><cite >{lang flink_add}</cite></a>
		</div> 
		<div class="clear "></div>
	
			<form  method="POST" action="index.php?wskm=setting&act=friendlink&batchgo=1" id="typeform" class="mt10" >
				<input type="hidden" name="arthash" value="{ART_HASH}" />
			    <table width="100%" cellspacing="0" class="tblist tbmsutil" >
			        <thead>
			        <tr>     
			          	<th width="22" class="first"  ></th>       
			            <th width="100" >{lang name}</th>
			            <th width="120">Logo</th>
			            <th  >{lang link}</th>			            
			            <th width="80" >{lang handler}</th>
			        </tr> 
			        </thead>
			       {loop $flinks $tempi}
			 		<tr >
			            <td  class="width22" ><input type="checkbox" class="select" name="del[]" value="{$tempi.id}" /></td>
			            <td align="center" >{$tempi.name}</td>
			            <td align="center" >{if $tempi.logo }<img src="{ART_URL}attachments/logo/{$tempi.logo}" width="100" border="0" />{/if}</td>
			            <td align="left" >{$tempi.url}</td> 			            
			            <td class="handler"><span><a href="index.php?wskm=setting&amp;act=friendlink&hand=edit&amp;id={$tempi.id}">{lang edit}</a>&nbsp;|&nbsp;<a href="javascript:if(confirm('{lang drop_confirm}'))window.location = 'index.php?wskm=setting&amp;act=friendlink&hand=del&amp;id={$tempi.id}';">{lang drop}</a>
			                </td>
			        </tr>
			        {/loop}
			        
			       
			    </table>
			
			     <div id="actcp" >
					  <div class="toolbar_list"> 
				        <div id="batchAction" >
				        	<div class="chk"><input type="checkbox" onclick="checkAll(this)"  />&nbsp;{lang drop}&nbsp;&nbsp;</div>
				         
				            <div class="btncright mt5">
						    <input class="btncommon" type="submit"  name="batchgo" value="{lang submit}" />            
						    </div>
				           
				        </div>
				    </div> 
			    </div>
			    	
			    
			    <script type="text/javascript" >
			    jQuery(function(){
			    	table_hover('tbmsutil');
			    });

			    </script>
			    <div class="clear"></div>
			    
			</form>
	</div>
</div>

{htmltemplate footer}