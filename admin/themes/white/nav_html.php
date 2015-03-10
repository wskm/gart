<?exit?>
{htmltemplate header}
<div id="rightwrap">
	<div id="rtitle">
		<p id="titlenav">{lang nav_manage}&nbsp;&raquo;&nbsp;{lang nav_list}</p>
	</div>
	
	<div id="rmain">
		<div class="ml5">
		<a class="btnart"  href="index.php?wskm=setting&act=nav&hand=add" ><cite >{lang nav_add}</cite></a>
		</div> 
		<div class="clear "></div>
	
			<form  method="POST" action="index.php?wskm=setting&act=nav&navbatchgo=1" id="typeform" class="mt10" >
				<input type="hidden" name="arthash" value="{ART_HASH}" />
			    <table width="100%" cellspacing="0" class="tblist tbmsutil" >
			        <thead>
			        <tr>     
			          	<th width="22" class="first"  ></th>       
			            <th width="100" align="left" >{lang nav_name}</th>
			            <th class="width30" >{lang if_show}</th>
			            <th width="70" >{lang nav_target}</th>			            
			            <th >{lang nav_url}</th>
			            <th width="80" >{lang handler}</th>
			        </tr> 
			        </thead>
			       {loop $navs $tempi}
			 		<tr >
			            <td  class="width22" ><input type="checkbox" class="select" name="del[]" value="{$tempi.id}" /></td>
			            <td align="left" ><span style="{if $tempi.color}color:{$tempi.color}{/if}" >{$tempi.name}</span></td> 
						<td align="center" >{if $tempi.status}<img src="{ATHEME_IMG}ienabled.gif" border="0" />{else}<img src="{ATHEME_IMG}idisabled.gif" border="0" />{/if}</td> 			            
			            <td ><center>{if $tempi.target}<img src="{ATHEME_IMG}ienabled.gif" border="0" />{else}<img src="{ATHEME_IMG}idisabled.gif" border="0" />{/if}</center></td> 
			            <td align="left" >{$tempi.url}</td> 			            
			            <td class="handler"><span><a href="index.php?wskm=setting&amp;act=nav&hand=edit&amp;id={$tempi.id}">{lang edit}</a>
			                |
			                <a href="javascript:if(confirm('{lang drop_confirm}'))window.location = 'index.php?wskm=setting&amp;act=nav&hand=del&amp;id={$tempi.id}';">{lang drop}</a>
			                </td>
			        </tr>
			        {/loop}
			        
			       
			    </table>
			
			     <div id="actcp" >
					  <div class="toolbar_list"> 
				        <div id="batchAction" >
				        	<div class="chk"><input type="checkbox" onclick="checkAll(this)"  />&nbsp;{lang drop}&nbsp;&nbsp;</div>
				         
				            <div class="btncright mt5">
						    <input class="btncommon" type="submit"  name="navbatchgo" value="{lang submit}" />            
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