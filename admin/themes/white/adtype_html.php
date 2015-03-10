<?exit?>
{htmltemplate header}
<div id="rightwrap">
<div id="rtitle">
	<p id="titlenav">{lang adtype_manage}</p>
</div>

<div id="rmain">
	<div class="ml5">
	<a class="btnart" href="javascript:;" onclick="addnew()" ><cite >{lang adtype_add}</cite></a>
	</div> 
	<div class="clear "></div>

<form  method="POST" action="index.php?wskm=setting&act=adtype" id="typeform" class="mt10" >
	<input type="hidden" name="arthash" value="{ART_HASH}" />
    <table width="100%" cellspacing="0" class="tblist tbmsutil" >
        <thead>
        <tr>     
          	<th class="first" style="width:22px;overflow:hidden " ></th>       
            <th align="left">{lang name}</th> 
            <th>typeid</th>
        </tr>
        </thead>
        <!--{loop $list $tempi}-->
        <tr class="tatr2">
        	<td class="width22" ><input type="checkbox" class="select" name="del[]" value="{$tempi.typeid}"/></td>
            <td align="left"> <input type="text"  class="textbox_bt"  size="30" maxlength="50" name="list[{$tempi.typeid}][name]"  value="{$tempi.name|escape}" /></td>
            <td align="center" width="80">{$tempi.typeid}</td>
        </tr>
        {/loop}
        
        <tr class="no_data" id="addrow">
            <td colspan="3"></td>
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

	function addnew(){		
		$('#addrow').before('<tr class="tatr2"><td ></td><td> <input type="text"  class="textbox_bt"  size="30" maxlength="50" name="newnames[]"  value="" /></td><td></td></tr>');
	}
	
    </script>
    <div class="clear"></div>
    
</form>
</div>
</div>

{htmltemplate footer}