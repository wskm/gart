<?exit?>
{htmltemplate header}
<div id="rightwrap">
<div id="rtitle">
	<p id="titlenav">{lang filter_word}&nbsp;&raquo;&nbsp;{lang word_list}</p>
</div>


<div id="rmain">
	<div class="ml5">
	<a class="btnart" href="javascript:;" onclick="addnew()" ><cite >{lang word_add}</cite></a>
	</div> 
	<div class="clear "></div>

<form  method="POST" action="index.php?wskm=article&act=filterword" id="typeform" class="mt10" >
	<input type="hidden" name="arthash" value="{ART_HASH}" />
    <table width="100%" cellspacing="0" class="tblist tbmsutil" >
        <thead>
        <tr>     
          	<th class="first" style="width:22px;overflow:hidden " ></th>       
            <th width="150" align="left">{lang word}</th>
            <th align="left">{lang word_replace}</th> 
        </tr>
        </thead>
        <!--{loop $list $tempi}-->
        <tr class="tatr2">
        	<td class="width22" ><input type="checkbox" class="select" name="del[]" value="{$tempi.id}"/></td>
            <td align="left"> <input type="text"  class="textbox_bt"  size="30" maxlength="50" name="list[{$tempi.id}][word]"  value="{$tempi.word|escape}" /></td>
            <td align="left"> <input type="text"  class="textbox_bt"  size="30" maxlength="50" name="list[{$tempi.id}][replace]"  value="{$tempi.replace|escape}" /></td>
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
		$('#addrow').before('<tr class="tatr2"><td ></td><td> <input type="text"  class="textbox_bt"  size="30" maxlength="50" name="newnames[]"  value="" /></td><td> <input type="text"  class="textbox_bt"  size="30" maxlength="50" name="newreplaces[]"  value="" /></td></tr>');
	}
	
    </script>
    <div class="clear"></div>
    
</form>
</div>
</div>

{htmltemplate footer}