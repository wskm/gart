<?exit?>
{htmltemplate header}
<div id="rightwrap">
	<div id="rtitle">
		<p id="titlenav">{lang plugin_manage}&nbsp;&raquo;&nbsp;{lang plugin_edit}</p>
	</div>
	<div id="rmain" >
		<div class="clear"></div>

		<form method="post" action="?wskm=plugin&act=edit">
		<input type="hidden" name="arthash" value="{ART_HASH}" />
		<input type="hidden" name="pluginid" value="{$pluginid}" />
		  <div class="divcommon"  > 
	  	  <table class="tb_article tbms1"  width="100%" >
	  	   <tr>
			  	<td width="120" >
			  	<span class="editatitle">{lang plugin_name}:</span>
			  	</td>
			  	<td>
			  	<input type="text" class="editinput" value="{$info.plugintitle}" name="plugin[plugintitle]" >
			  	</td> 
		   </tr>
		    <tr>
			  	<td width="120" >
			  	<span class="editatitle">{lang plugin_key}:</span>
			  	</td>
			  	<td>
			  	<span>{$info.pluginname}</span>
			  	</td> 
		   </tr>
		    <tr>
			  	<td width="120" >
			  	<span class="editatitle">{lang plugin_version}:</span>
			  	</td>
			  	<td>
			  	<span>{$info.version}</span>
			  	</td> 
		   </tr>
		   <tr>
			  	<td width="120" >
			  	<span class="editatitle">{lang plugin_versioninfo}:</span>
			  	</td>
			  	<td>
			  	<span>{$info.copyright}</span>
			  	</td> 
		   </tr>
		   <tr>
			  	<td width="120" >
			  	<span class="editatitle">{lang status}:</span>
			  	</td>
			  	<td>
			  	<input type="radio" name="plugin[status]" value="1" {if $info.status}checked="true"{/if} >启用&nbsp;
			  	<input type="radio" name="plugin[status]" value="0"  {if !$info.status}checked="true"{/if}>停用&nbsp;
			  	
			  	</td> 
		   </tr>
		   <tr>
			  	<td width="120" >
			  	<span class="editatitle">{lang showmanage}:</span>
			  	</td>
			  	<td>
			  	<input type="radio" name="plugin[ismanage]" value="1" {if $info.ismanage}checked="true"{/if} >{lang yes}&nbsp;
			  	<input type="radio" name="plugin[ismanage]" value="0"  {if !$info.ismanage}checked="true"{/if}>{lang no}&nbsp;
			  	
			  	</td> 
		   </tr>
		   <tr>
			  	<td width="120" >
			  	<span class="editatitle">{lang shownav}:</span>
			  	</td>
			  	<td>
			  	<input type="radio" name="plugin[isnav]" value="1" {if $info.isnav}checked="true"{/if} >{lang yes}&nbsp;
			  	<input type="radio" name="plugin[isnav]" value="0"  {if !$info.isnav}checked="true"{/if}>{lang no}&nbsp;
			  	
			  	</td> 
		   </tr>
		    <tr>
			  	<td width="120" >
			  	<span class="editatitle">{lang navname}:</span>
			  	</td>
			  	<td>
			  	<input type="text" class="editinput" value="{$info[hook][navname]}" name="hook[navname]" >
			  	</td> 
		   </tr>
		  <tr>
			  	<td width="120" >
			  	<span class="editatitle">{lang navurl}:</span>
			  	</td>
			  	<td>
			  	<input type="text" class="editinput" value="{$info[hook][navurl]}" name="hook[navurl]" >&nbsp;(test.php)
			  	</td> 
		   </tr>
		   <tr>
			  	<td width="120" valign="top" >
			  	<span class="editatitle" >{lang plugin_description}:</span>
			  	</td>
			  	<td>
			  	<textarea style="width: 500px; height: 100px; padding: 2px;" rows="250" cols="250"  name="plugin[description]">{$info.description}</textarea>
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
            <input class="btncommon" type="reset" value="{lang reset}" name="resset22"  />   
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
table_evenbg('tbms1',1);
</script>
{htmltemplate footer}