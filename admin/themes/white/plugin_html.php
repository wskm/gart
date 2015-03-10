<?exit?>
{htmltemplate header}
<div id="rightwrap">
	<div id="rtitle">
		<p id="titlenav">{lang plugin_manage}&nbsp;&raquo;&nbsp;{lang plugin_list}</p>
	</div>
	
	<div id="rmain">
			<form  method="POST" action="index.php?wskm=plugin" id="typeform" class="mt10" >
				<input type="hidden" name="arthash" value="{ART_HASH}" />
			    <table width="100%" cellspacing="0" class="tblist tbmsutil" >
			        <thead>
			        <tr>     
			          	<th width="22" class="first"  ></th>       			          	
			          	<th align="center" width="150">{lang plugin_name}</th>
			          	<th align="center" width="150">{lang plugin_key}</th>
			          	<th width="50" align="center" >{lang plugin_version}</th>
			            <th width="100" align="center">{lang plugin_versioninfo}</th>
			            <th width="40">{lang status}</th>
			            <th align="left" >{lang handler}</th>
			        </tr> 
			        </thead>
			       {loop $plugins $tempi}
			 		<tr >
			            <td  class="width22" ><input type="checkbox" class="select" name="selectid[]" value="{$tempi.pluginid}" {if $tempi.status}checked="true"{/if} /></td>			            
			            <td align="center" >{$tempi.plugintitle}</td>			            
			            <td align="center" >{$tempi.pluginname}</td>
			            <td align="center" >{$tempi.version}</td>
			            <td align="center" >{$tempi.copyright}</td>
			             <td align="center" >{if $tempi.status}{lang enable}{else}{lang disable}{/if}</td>
			            <td class="handler">&nbsp;<a class="blue" href="index.php?wskm=plugin&act=edit&id={$tempi.pluginid}" >{lang edit}</a>&nbsp;|&nbsp;<a  class="blue" href="index.php?wskm=plugin&act=uninstall&id={$tempi.pluginid}&key={$tempi.pluginname}" >{lang uninstall}</a>
			                </td>
			        </tr>
			        {/loop}
			         {loop $installs $tempi}
			 		<tr >
			            <td  class="width22" ></td>			            
			            <td align="center" >{$tempi.plugintitle}</td>			            
			            <td align="center" >{$tempi.pluginname}</td>
			            <td align="center" >{$tempi.version}</td>
			            <td align="center" >{$tempi.copyright}</td>
			            <td align="center" >{lang notinstalled}</td>
			            <td class="handler ">&nbsp;<a class="blue"  href="index.php?wskm=plugin&act=install&name={$tempi.pluginname}" >{lang install}</a>
			                </td>
			        </tr>
			        {/loop}
			      			       
			    </table>
			
			     <div id="actcp" >
					  <div class="toolbar_list"> 
				        <div id="batchAction" >
				        	<div class="chk"><input type="checkbox" onclick="checkAll(this)"  />&nbsp;{lang enable}&nbsp;&nbsp;</div>
				         
				            <div class="btncright mt5">
						    <input class="btncommon" type="submit"  name="articletypepost" value="{lang submit}" />            
						    </div>
				           <div class="toolbar_rgiht">
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