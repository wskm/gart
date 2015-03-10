<?exit?>
{htmltemplate header}
<div id="rightwrap">
<div id="rtitle">
	<p id="titlenav">{lang article_top}&nbsp;&raquo;&nbsp;{lang articletypes_manage}</p>
</div>


<div id="rmain">
	<div class="ml5"  style="display:none" >
	﻿<a class="btnart" href="javascript:;" onclick="addnewtype()" ><cite >{lang articletypes_add}</cite></a>
	</div> 	
	<div class="clear "></div>

<form  method="POST" action="index.php?wskm=article&act=kinds&articlekindpost=1" id="typeform" class="mt10" >
	<input type="hidden" name="arthash" value="{ART_HASH}" />
    <table width="100%" cellspacing="0" class="tblist tbmsutil" >
        <thead>
        <tr>     
            <th width="35" class="first"><center>{lang display}</center></th>
          	<th width="35" align="left"  >{lang sort_order}</th>
            <th align="left" >{lang name}</th>
            
        </tr> 
        </thead>
        <!--{loop $kinds $kind}-->
        <tr class="tatr2">
			<td  class="width22" ><input type="checkbox" class="select" name="kinds[{$kind.kindid}][status]" {if $kind.status}checked='true'{/if} value="1"  /></td>
        	<td ><input type="text"  class="textbox_bt"  size="2" maxlength="2"  name="kinds[{$kind.kindid}][sort]"  value="{$kind.displaysort}" /></td>
            <td> <input type="text"  class="textbox_bt"  readonly='true' size="15" maxlength="15" name="kinds[{$kind.kindid}][name]"  value="{$kind.name|escape}" /></td>
        </tr>
        {/loop}
        
        <tr class="no_data" id="addrow">
            <td colspan="3"></td>
        </tr>
       
    </table>

     <div id="actcp" >
		  <div class="toolbar_list"> 
	        <div id="batchAction" >
	        	<div class="chk"><input type="checkbox" onclick="checkAll(this)"  />&nbsp;{lang disabled}&nbsp;&nbsp;</div>
	         
	            <div class="btncright mt5">
			    <input class="btncommon" type="submit"  name="articlekindpost" value="{lang submit}" />            
			    </div>
	           <span style="color:gray">&nbsp;&nbsp;({lang articlekind_notice})</span>
	        </div>
	    </div> 
    </div> 
    	
    
    <script type="text/javascript" >
	jQuery(function(){
		 table_hover('tbmsutil');	 
	});

	function addnewtype(){		
		$('#addrow').before('<tr class="tatr2"><td><input type="checkbox" name="newstatus[]" value="1" checked="true" /></td><td ><input type="text" class="textbox_bt" name="newsorts[]"  size="2" maxlength="2" value="0" /></td><td> <input type="text"  class="textbox_bt"  size="15" maxlength="15" name="newnames[]"  value="" /></td></tr>');
	}
	
    </script>
    <div class="clear"></div>
    
</form>
</div>
</div>

{htmltemplate footer}