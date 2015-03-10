<?exit?>
{htmltemplate header}
<div id="rightwrap">
<div id="rtitle">
	<p id="titlenav">{lang tool}&nbsp;&raquo;&nbsp;{lang db_nav}</p>
</div>
<div id="rmain" >

	<div class="ml5" >
		<ul class="main_nav">
			<li {if $hand=='backup'}class="current"{/if} ><a href="index.php?wskm=tool&act=db&hand=backup" ><span>{lang backup_export}</span></a></li>
			<li {if $hand=='import'}class="current"{/if} ><a href="index.php?wskm=tool&act=db&hand=import" ><span>{lang backup_import}</span></a></li>
			<li {if $hand=='optimize'}class="current"{/if} ><a href="index.php?wskm=tool&act=db&hand=optimize" ><span>{lang optimize}</span></a></li>
		</ul>
		
	</div>	 
	<div class="clear"></div>

<div class="divcommon">
  {if $hand=='backup' }
 <table class="tb_article" width="100%">
 <tr><td> 
 <span class="gray">{lang backup_notice}</span>
 </td></tr>
 </table>
 {/if}
 
  <form method="post" action="index.php?wskm=tool&act=db&hand={$hand}" {if $hand == 'import'} enctype="multipart/form-data"{/if}>  
  <input type="hidden" name="arthash" value="{ART_HASH}" />
	   <table class="tb_article tbms1" width="100%">
	   
	   {if $hand=='backup' }
	   	<tr>
		  	<td width="100" valign="top" >
		  	<span class="editatitle">{lang file_name}:</span>
		  	</td>
		  	<td>
		  	<input type="text" class="editinput" size="20" name="filename" maxlength="20" value="{$filename}" >.sql&nbsp;&nbsp;&nbsp;<span class="gray">({lang backup_name_notice})</span>
		  	</td>
	   </tr>
	   <tr>
		  	<td width="100" valign="top" >
		  	<span class="editatitle">{lang backup_type}:</span>
		  	</td>
		  	<td>
		  	<input type="radio" value="mysqldump" name="backuptype" checked="true" >MysqlDump
		  	</td>
	   </tr>
	   
	   <tr>
		  	<td width="100" valign="top" >
		  	<span class="editatitle">{lang zip}:</span>
		  	</td>
		  	<td>
		  	<input type="radio" name="iszip" value="1"  >{lang yes}&nbsp;
		  	<input type="radio" name="iszip" value="0" checked="true" >{lang no}
		  	</td>
	   </tr>
	   <tr>
		  	<td width="100" valign="top" >
		  	<span class="editatitle">{lang einsert}:</span>
		  	</td>
		  	<td>
		  	<input type="radio" name="isextend" value="1" >{lang yes}&nbsp;
		  	<input type="radio" name="isextend" value="0" checked="true" >{lang no}
		  	</td>
	   </tr>

	   <tr>
		  	<td width="100" valign="top" >
		  	<span class="editatitle">{lang backup_all}:</span>
		  	</td>
		  	<td>
		  	<input type="radio" name="isall" onclick="$('#tablewrap').hide();" value="1" checked="true" >{lang yes}&nbsp;
		  	<input type="radio" name="isall" onclick="if(this.checked)showtables();" value="0"  >{lang no}		  	
		  	</td>
	   </tr>
	   <tr id="tablewrap"  style="display:none">
	   	<td colspan="2">
	   	<input type="checkbox" id="alltable" onclick="checkAll(this)" >{lang select_all}
	   		<div>
	   		<ul  id="tablelist" class="tablelist">
	   		</ul>
		  	</div>
	   	</td>
	   </tr>
	   <script type="text/javascript">
	   		function showtables(){
	   			$('#tablewrap').show();
	   			$('#alltable').attr('checked',false);
	   			$('#tablelist').html('');
	   			$.getJSON('index.php?wskm=tool&act=showtables','',function(data){
	   				$(data).each(function(i,s){
	   					$('#tablelist').append('<li><input type="checkbox" name="backuptable[]" class="select" value="'+s+'" >'+s+'&nbsp;&nbsp;</li>');
	   				});
	   			});
	   		}
	   </script>
	    {elseif $hand=='import'}
	   <tr>
	   	<td colspan="2" >
	   	{lang import}&nbsp;&nbsp;<input type="file" name="importfile" >&nbsp;({lang backup_import_notice})
	   	</td>
	   </tr>
	    	    		
	   {elseif $hand=='optimize'}
	   <tr class="nobg">
	   		<td colspan="2" >
	   		{if $frees}
	   			 <table class="tbwb" width="100%">
					  <thead>
					  <tr>
					  	<th width="22" class="first" ><input type="checkbox" onclick="checkAll(this)" value="{$free.Name}" ></th>
					  	<th align="left" >{lang data_name}</th>
					  	<th width="80" align="center" >{lang data_type}</th>
					  	<th width="100" align="center" >{lang data_charset}</th>
					  	<th width="80" align="center" >{lang data_rows}</th>
					  	<th width="80" align="center" >{lang data_length}</th>
					  	<th width="80" align="center" >{lang data_indexlength}</th>
					  	<th width="80" align="center" >{lang data_freelength}</th>
					  </tr>
					  </thead>
					  <tbody>
					  {loop $frees $free}
	   					 <tr class="nobg">
	   					 	<td><input type="checkbox" class="select" name="optselects[]" value="{$free.Name}" ></td>
	   					 	<td>{$free.Name}</td>
	   					 	<td align="center" >{$free.Engine}</td>
	   					 	<td align="center" >{$free.Collation}</td>
	   					 	<td align="center" >{$free.Rows}</td>
	   					 	<td align="center" >{echo fSize($free.Data_length)}</td>
	   					 	<td align="center" >{echo fSize($free.Index_length)}</td>
	   					 	<td align="center" >{echo fSize($free.Data_free)}</td>
						  </tr>
					{/loop}
					  </tbody>
	   			</table>
	   			{else}
	   			{lang not_free}
	   			{/if}
	   		</td>
	   </tr>
	   {/if}
	   
    {if $isshell || $hand =='optimize' }
	  <tr class="nobg">
		  <td colspan="2">
          <div class="btncright mt5">
            <input class="btncommon" type="submit" value="{lang submit}" name="pgosgo"  />   
            </div>
             <div class="btncright mt5">
            <input class="btncommon" type="reset" value="{lang reset}" name="resset22"  />   
            </div>
          </td>
	  </tr>
	  {else}
	     <tr><td colspan="2"><b class="red">{lang not_shell}</b></td></tr>
	
	  {/if} 
	 
	  </table>
	   
	  </div>
   </form>
   {if $hand=='import' && $bkfiles}
	  <div class="h15" ></div>
	  <form method="POST" action="index.php?wskm=tool&act=db&hand=batchdel" >
	  <table class="tblist tbmsutil " width="100%">
	  <thead>
	  <tr>
	  	<th width="22" class="first" ></th>
	  	<th  align="left" >{lang file_name}</th>
	  	<th width="80" align="left" >{lang file_size}</th>
	  	<th width="120" align="left"  >{lang file_mtime}</th>
	  	<th width="150"  align="left" >{lang handle}</th>
	  </tr>
	  </thead>
	  <tbody>
	  {loop $bkfiles $file}
	  <tr>
	  	<td align="left" class="w25" ><input type="checkbox" name="selectfile[]" value="{$file.path}" class="select" ></td>
	   	<td>{$file.filename}</td>
	   	<td>{echo fSize($file.size); }</td>
	   	<td>{$file.mtime|time}</td>
	   	<td align="left">
	   		{if $file.type=='zip'}<a href="index.php?wskm=tool&act=db&hand=unzip&path={$file.path}">{lang unzip}</a>{else}<a href="index.php?wskm=tool&act=db&hand=importfile&path={$file.path}" >{lang import}</a>{/if}&nbsp;|&nbsp;<a href="{ART_URL}cache/backup/{$file.path}" target="_blank">{lang download}</a>&nbsp;|&nbsp;<a href="index.php?wskm=tool&act=db&hand=delbk&path={$file.path}" >{lang del}</a>{if $file.type!='zip'}&nbsp;|&nbsp;<a href="index.php?wskm=tool&act=db&hand=zip&path={$file.path}">{lang zip}</a>{/if}
	   	</td> 
	  </tr>
	  {/loop}	  
	   <tr class="nobg">
	   <td ><input type="checkbox" onclick="checkAll(this)" ></td>
		  <td >
          <div class="btncright mt5">
            <input class="btncommon" type="submit" value="{lang del}" name="pgosgo"  />   
            </div>
         
          </td>
	  </tr>
	  </tbody>
	  </table>
	  </form>
	  {/if}
 </div>
<script type="text/javascript" >
table_evenbg('tbms1');
</script>
</div>
{htmltemplate footer}