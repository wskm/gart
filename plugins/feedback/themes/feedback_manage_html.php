<?exit?>
{htmltemplate header}
<div id="rightwrap">
<div id="rtitle">
	<p id="titlenav">{lang ftitle}&nbsp;&raquo;&nbsp;{lang flist}</p>
</div>

<div id="rmain">  

  <form  method="POST" action="index.php?wskm=plugin&act=manage&key={PLUGIN_KEY}" >
  <input type="hidden" name="arthash" value="{ART_HASH}" />
  <input type="hidden" name="acttype" id="acttype" value="del" />
  
  <div id="actcp" >
  {if $list}
  <div class="toolbar_list">
            
        <div id="batchAction" >
        	<div class="chk"><input type="checkbox" onclick="checkAll(this)"  />&nbsp;{lang checkall}&nbsp;&nbsp;</div>
        	<div class="chk">
            <select name="selectacttype" id="selectacttype" onchange="$('#acttype').val(this.value);"  >
            	<option value="del" >{lang del}</option>
            </select>  
        
            <input type="submit"  class="easybtn" value=" {lang submit} " name="articlelistgo" />            
            </div>   
            <div class="toolbar_rgiht" >
            	{$htmlpage}
            </div>
        </div>
    </div>      
    {/if}      
    </div>
    <table width="100%" cellspacing="0" class="tblist tbmsutil" >
        {if $list}
        <thead>
        <tr>
        	<th class="first" width="25">&nbsp;</th>                    	
        	<th width="70" align="center" >{lang author}</th>            	 
        	<th>{lang title}</th>          	
          	<th width="90" align="center" >IP</th>   
          	<th width="120" align="center" >{lang time}</th>   
          	<th width="120" align="center" >Email</th>
            <th width="35" >{lang handle}</th>
        </tr>
        </thead> 
     
        {loop $list $item}
        <tr class="tatr2">
        	<td class="w25" >
        	<input type="checkbox" name="selectid[]" class="select" value="{$item.id}"/>
        	</td>
            <td align="left" >{$item.author}</td>            
             <td align="left"  ><div style="overflow:hidden;">{$item.title}</div></td> 
            <td align="center" >{$item.ip}</td>          
            <td align="center" >{$item.dateline|time}</td>                
            <td align="left" width="120" >{$item.email}</td>
            <td><a href="index.php?wskm=plugin&act=manage&key={PLUGIN_KEY}&hand=edit&id={$item.id}" >{lang detailed}</a></td>
        </tr>
         {/loop}
        {else}
        <tr >
            <td colspan="7" class="no_data" >{lang no_data}</td>
        </tr>
        {/if}
    </table>
    {if $list}
 
    <div id="actcp2">
    	  <div class="toolbar_list">
	            
	        <div id="batchAction" >
	        	<div class="chk"><input type="checkbox" onclick="checkAll(this)"  />&nbsp;{lang checkall}&nbsp;&nbsp;</div>
	        	<div class="chk">
	            <select name="selectacttype" id="selectacttype2"  onchange="$('#acttype').val(this.value);"  >
	            </select>  
	             
	            <input type="submit"  class="easybtn" value=" {lang submit} " name="articlelistgo" />            
	            </div>   
	            <div class="toolbar_rgiht" >
	            	{$htmlpage}
	            </div>
	        </div>
	    </div>  
    </div>
    <script type="text/javascript" >
    jQuery(function(){
    	table_hover('tbmsutil');
    	if(!$.browser.msie){
    		$('#btn_addnews_menu').css('width','68px');
    	}
    });

    $('#selectacttype2').html($('#selectacttype').html());
    $('#selectmovegid2').html($('#selectmovegid').html());
    </script>
    <div class="clear"></div>
    
    {/if}
	</form>
</div>

</div>


{htmltemplate footer}