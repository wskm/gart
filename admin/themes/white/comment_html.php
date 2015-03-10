<?exit?>
{htmltemplate header}
<div id="rightwrap">
<div id="rtitle">
	<p id="titlenav">{lang comment_verify}&nbsp;&raquo;&nbsp;{lang comment_list}</p>
</div>

<div id="rmain">  
	<div class="h5"></div>
	<ul class="main_nav fright">	
	<li {if $status==0}class="current"{/if} ><a href="index.php?wskm=article&act=comment&status=0"><span>{lang comment_verify}</span></a></li>
	<li {if $status==1}class="current"{/if} ><a href="index.php?wskm=article&act=comment&status=1"><span>{lang comment_all}</span></a></li>
	</ul>

  <form  method="POST" action="index.php?wskm=article&act=comment&hand=batch" >
  <input type="hidden" name="arthash" value="{ART_HASH}" />
  <input type="hidden" name="acttype" id="acttype" value="del" />
  
  <div id="actcp" >
  {if $list}
  <div class="toolbar_list">
            
        <div id="batchAction" >
        	<div class="chk"><input type="checkbox" onclick="checkAll(this)"  />&nbsp;{lang checkall}&nbsp;&nbsp;</div>
        	<div class="chk">
            <select name="selectacttype" id="selectacttype" onchange="$('#acttype').val(this.value);"  >
            	<option value="del" >{lang comment_del}</option>
            	<option value="verify" >{lang comment_status_verify}</option>
            	<option value="normal" >{lang comment_status_normal}</option>            	
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
        	<th width="60" >{lang user_photo}</th>
        	<th width="70" align="center" >{lang username}</th>                    	 
          	<th width="90" align="center" >IP</th>   
          	<th width="120" align="center" >{lang time}</th>   
          	<th>{lang comment}</th>            
            <th width="35" >{lang handle}</th>
        </tr>
        </thead> 
     
        {loop $list $item}
        <tr class="tatr2">
        	<td class="w25" >        	
        	<input type="checkbox" name="selectid[{$item.aid}][]" class="select" value="{$item.id}"/>
        	</td>
        	<td align="center" ><a href="{$item.spaceurl}" {if !$item.anonym}target="_blank"{/if} >{$item.photo}</a></td>
            <td align="center" >{$item.uname}</td>
            <td align="center" >{$item.ip}</td>          
            <td align="center" >{$item.dateline|time}</td>                
            <td align="left"  ><div style="height:50px;line-height:50px;overflow:hidden;"><a href="{$item.articleurl}#commentitem_{$item.id}" class="black" title="{$item.message}" target="_blank">{$item.message}</a></div></td>  
            <td><a href="index.php?wskm=article&act=comment&hand=edit&id={$item.id}">{lang detailed}</a></td>
        </tr>
         {/loop}
        {else}
        <tr >
            <td colspan="7" class="no_data" ><div class="no_data">{lang no_data}</div></td>
        </tr>
        {/if}
    </table>
    {if $list}
 	<script type="text/javascript">
 	function goto_confirm(msg,turl){
 		if(confirm(msg)){
 			location.href=turl;
 		}
 	}
 	</script>
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