<?exit?>
{htmltemplate header}
<div id="rightwrap">
	<div id="rtitle">
		<p id="titlenav">{lang flink_manage}&nbsp;&raquo;&nbsp;{if $hand== 'add' }{lang flink_add}{else}{lang flink_edit}{/if}</p>
	</div>
	{if $hand!= 'add' }
	<div class="ml5 ">
		<div class="paddinglr5 txtleft">
		<a class="btnart"  href="index.php?wskm=setting&act=friendlink&hand=add" ><cite >{lang flink_add}</cite></a>
		</div>
	</div> 
	{/if}
	<div class="clear "></div>
	<div id="rmain" >
		<form method="post"  enctype="multipart/form-data" action="index.php?wskm=setting&act=friendlink&hand={$hand}&postgo=1">
		<input type="hidden" name="arthash" value="{ART_HASH}" />
		<input type="hidden" name="fid" value="{$fid}" />
		  <div class="divcommon"  > 
	  	  <table class="tb_article tbms1"  width="100%">
		    <tr>
			  	<td width="130" >
			  	<span class="editatitle">{lang name}：</span>
			  	</td>
			  	<td>
			  	<input type="text" name="name" id="name" maxlength="50" class="editinput" value="{$info.name}" />
			  	</td> 
		   </tr>
		    <tr>
			  	<td >
			  	<span class="editatitle">{lang sort}：</span>
			  	</td>
			  	<td>
			  	<input type="text" name="displaysort"  value="{$info.displaysort}" size="2" maxlength="3" />			  	
			  	</td> 
		   </tr>
 		  <tr>
			  	<td >
			  	<span class="editatitle">Logo：</span>
			  	</td>
			  	<td>
			  	<input type="file"  name="uploadlogo[]" />&nbsp;<input type="hidden" name="logo" value="{$info.logo}" />{if $info.logo}{lang uploaded}{/if}
			  	</td> 
		   </tr>
		   
		   <tr>
			  	<td >
			  	<span class="editatitle">{lang link}：</span>
			  	</td>
			  	<td>
			  	<input type="text" name="url"  value="{$info.url}"  class="editinput"  maxlength="100" />			  	
			  	</td> 
		   </tr>
		     		   
		   <tr class="sp"><td colspan="2"></td></tr>
	  	  </table>
	
	  	  <table width="100%" >
	  	  <tr>
	  	  <td width="80"></td>
		  <td>
          <div class="btncright mt5">
            <input class="btncommon" type="submit" value="{lang submit}" name="postgo"  />   
            </div>
             <div class="btncright mt5">
            <input class="btncommon" type="reset" value="{lang reset}"  />   
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