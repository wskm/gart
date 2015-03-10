<?exit?>
{htmltemplate header}
<div id="rightwrap">
	<div id="rtitle">
		<p id="titlenav">{lang loginlog}&nbsp;&raquo;&nbsp;{lang log_list}</p>
	</div>
	
	<div id="rmain">
			<form  method="POST" action="index.php?wskm=tool&act=loginlog" id="typeform" class="mt10" >
				<input type="hidden" name="arthash" value="{ART_HASH}" />
			    <table width="100%" cellspacing="0" class="tblist tbmsutil" >
			        <thead>
			        <tr>     
			          	<th width="22" class="first"  ></th>       
			          	<th align="center">{lang username}</th>
			          	<th>{lang password}</th>
			          	<th width="30" align="center" >{lang status}</th>
			            <th width="120" align="center">IP</th>
			            <th width="120" align="center">{lang time}</th>
			            <th width="70" align="left" >{lang handler}</th>
			        </tr> 
			        </thead>
			       {loop $list $tempi}
			 		<tr >
			            <td  class="width22" ><input type="checkbox" class="select" name="dellog[]" value="{$tempi.logid}" /></td>
			            <td align="center" >{$tempi.uname}</td>
			            <td align="center" >{$tempi.password}</td>
			            <td align="center" >{if $tempi.logintype}{lang login_ok}{else}{lang login_err}{/if}</td>
			            <td align="center" >{$tempi.ip}</td>
			            <td align="center" >{$tempi.logintime|time}</td>
			             
			            <td class="handler" ><a href="index.php?wskm=tool&act=loginlog&hand=del&id={$tempi.logid}" >{lang del}</a>
			                </td>
			        </tr>
			        {/loop}
			      			       
			    </table>
			
			     <div id="actcp" >
					  <div class="toolbar_list"> 
				        <div id="batchAction" >
				        	<div class="chk"><input type="checkbox" onclick="checkAll(this)"  />&nbsp;{lang drop}&nbsp;&nbsp;</div>
				         
				            <div class="btncright mt5">
						    <input class="btncommon" type="submit"  name="articletypepost" value="{lang submit}" />            
						    </div>
				           <div class="toolbar_rgiht">
				           {$htmlpage}
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