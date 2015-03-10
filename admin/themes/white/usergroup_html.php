<?exit?>
{htmltemplate header}
<div id="rightwrap">
<div id="rtitle">
	<p id="titlenav">{lang usergroup_name}&nbsp;&raquo;&nbsp;{lang group_list}</p>
</div>


<div id="rmain">
	<div class="ml5">
	<a class="btnart" href="javascript:;" onclick="addnew()" ><cite >{lang group_add}</cite></a>
	</div> 
	<div class="clear "></div>

<form  method="POST" action="index.php?wskm=user&act=usergroup" class="mt10" >
	<input type="hidden" name="arthash" value="{ART_HASH}" />
    <table width="100%" cellspacing="0" class="tblist tbmsutil" >
        <thead>
        <tr>     
          	<th class="first" width="22" ></th>       
          	<th width="20" align="left"  >ID</th>
            <th width="110" align="left"  >{lang name}</th>            
            <th width="50">{lang accesslevel}</th>
            <th width="50" align="center">{lang type}</th>            
            <th width="50" align="left"  >{lang group_adminid}</th> 
            <th width="55">{lang isvisit}</th>
            <th width="55">{lang isarticle}</th>
            <th width="55">{lang isarticlefree}</th>
            <th width="55">{lang isupload}</th>
            <th align="left">&nbsp;</th>
        </tr> 
        </thead>
        <!--{loop $list $item}-->
        <tr class="tatr2">
        	<td class="width22" >{if $item.groupid > 7}<input type="checkbox" class="select" name="del[]" value="{$item.groupid}"/>{/if}</td>
        	<td>{$item.groupid}</td>
            <td width="110"><input type="text"  class="textbox_bt"  size="15" maxlength="10" name="data[{$item.groupid}][groupname]"  value="{$item.groupname|escape}" /></td>
            <td><input type="text"  class="textbox_bt"  size="5" maxlength="3" name="data[{$item.groupid}][accesslevel]"  value="{$item.accesslevel}" /></td>
            <td align="center">{if $item.type=='inner'}{lang group_tinner}{elseif $item.type=='member'}{lang group_tmember}{else}{lang group_tother}{/if}</td>                        
             <td><input type="text"  class="textbox_bt"  size="3" maxlength="2" name="data[{$item.groupid}][adminid]"  value="{$item.adminid}" /></td>
            <td  align="center" ><input type="checkbox" name="data[{$item.groupid}][isvisit]" value="1" {if $item.isvisit}checked="true"{/if} ></td>
            <td align="center"><input type="checkbox" name="data[{$item.groupid}][isarticle]" value="1" {if $item.isarticle}checked="true"{/if} ></td>
            <td align="center"><input type="checkbox" name="data[{$item.groupid}][isarticlefree]" value="1" {if $item.isarticlefree}checked="true"{/if} ></td>
            <td align="center"><input type="checkbox" name="data[{$item.groupid}][isupload]" value="1" {if $item.isupload}checked="true"{/if} ></td>
            <td>&nbsp;</td>
        </tr>
        {/loop} 
        
        <tr class="no_data" id="addrow">
            <td colspan="8"></td>
        </tr>
       
    </table>

     <div id="actcp" >
		  <div class="toolbar_list"> 
	        <div id="batchAction" >
	        	<div class="chk"><input type="checkbox" onclick="checkAll(this)"  />&nbsp;{lang drop}&nbsp;&nbsp;</div>
	         
	            <div class="btncright mt5">
			    <input class="btncommon" type="submit"  name="articletypepost" value="{lang submit}" />            
			    </div>
	           
	        </div>
	    </div> 
    </div>
    	
    
    <script type="text/javascript" >
	jQuery(function(){
		 table_hover('tbmsutil');	 
	});

	var newi=0;
	function addnew(){				
		$('#addrow').before('<tr class="tatr2"><td></td><td></td><td width="110"><input type="text"  class="textbox_bt"  size="15" maxlength="10" name="new['+newi+'][groupname]"  value="" /></td><td><input type="text"  class="textbox_bt"  size="5" maxlength="3" name="new['+newi+'][accesslevel]"  value="" /></td><td align="center"><select name="new['+newi+'][type]"><option value="other" >{lang group_tother}</option><option selected="true" value="member" >{lang group_tmember}</option></select></td><td><select name="new['+newi+'][adminid]"><option value="0">0</option>{loop $admingroups $agroup}<option value="{$agroup.adminid}" >{$agroup.adminid}</option>{/loop}</select></td><td><input type="checkbox" name="new['+newi+'][isvisit]"  value="1" checked="true" ></td><td><input type="checkbox" name="new['+newi+'][isarticle]" value="1" ></td><td><input type="checkbox" name="new['+newi+'][isarticlefree]" value="1" ></td><td><input type="checkbox" name="new['+newi+'][isupload]" value="1" ></td><td>&nbsp;</td></tr>');
		newi++;
	}
	
    </script>
    <div class="clear"></div>
    
</form>
</div>
</div>

{htmltemplate footer}