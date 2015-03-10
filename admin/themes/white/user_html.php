<?exit?>
{htmltemplate header}
<div id="rightwrap">
<div id="rtitle">
	<p id="titlenav">{lang user_manage}&nbsp;&raquo;&nbsp;{lang user_list}</p>
</div>

<div id="rmain">  
	<div class="cleara ml5"  >
		<a class="btnart " id="btn_addnews" href="index.php?wskm=user&act=add"><cite >{lang user_add}</cite></a>
		<div class="clear"></div>
	</div> 
		
	<div class="h5"></div>
	<div class="divcommon">
        <form method="GET" style="color:#000" >  
            <input type="hidden" name="wskm" value="user" />           			
            <div class="fleft mb5">                
                {lang username}&nbsp;<input class="user_txt" type="text" name="uname" value="{$skeys[uname]}" maxlength="50" />&nbsp;
                Email&nbsp;<input class="user_txt" type="text" name="email" value="{$skeys[email]}" maxlength="50" />&nbsp;
         		IP&nbsp;<input class="user_txt" type="text" name="lastip" value="{$skeys[lastip]}"  maxlength="15" />&nbsp;
                <select class="querySelect" name="groupid">                
                <option value="0">{lang usergroup_name}...</option>
                {loop $groups $group}
                <option value="{$group.groupid}" {if $skeys.groupid==$group.groupid}selected="true"{/if} >{$group.groupname}</option>
                {/loop}
                </select>&nbsp;
                
                <select class="querySelect" name="sorttype">
                <option value="DESC" {if $skeys.sorttype=='DESC' }selected="true"{/if} >{lang sort_desc}</option>
                <option value="ASC" {if $skeys.sorttype=='ASC' }selected="true"{/if}>{lang sort_asc}</option>
                </select>&nbsp;
                <input  type="submit" value=" {lang select} " name="filtergo"  />  
                
            </div>
          
        </form>
        <div class="clear"></div>
    </div>

  <form  method="POST" action="index.php?wskm=user&act=batch" >
  <input type="hidden" name="arthash" value="{ART_HASH}" />
  <input type="hidden" name="acttype" id="acttype" value="del" />
  <input type="hidden" name="movegid" id="movegid" value="0" />
  
  <div id="actcp" >
  {if $users}
  <div class="toolbar_list">
            
        <div id="batchAction" >
        	<div class="chk"><input type="checkbox" onclick="checkAll(this)"  />&nbsp;{lang checkall}&nbsp;&nbsp;</div>
        	<div class="chk">
            <select name="selectacttype" id="selectacttype" onchange="$('#acttype').val(this.value);if(this.value=='movegroup'){$('#selectmovegid').show();}else{$('#selectmovegid').hide();}" >
            	<option value="del" >{lang user_del}</option>
            	<option value="movegroup" >{lang user_movegroup}</option>
            	
            </select>  
        
             <select class="querySelect" name="selectmovegid" id="selectmovegid" onchange="$('#movegid').val(this.value);"  style="display:none">                
                <option value="0">{lang select_pls}</option>
                {loop $groups $group}
                <option value="{$group.groupid}" >{$group.groupname}</option>
                {/loop}
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
        {if $users}
        <thead>
        <tr>
        	<th class="first" width="25">&nbsp;</th>                    	
        	<th ><center>{lang username}</center></th>           	
            <th width="150" align="left">Email</th>  
            <th class="w35">UID</th>          
            <th width="50" align="left" >{lang usergroup_name}</th>    
            <th width="120">{lang reg_time}</th>                     
            <th  width="60" >{lang handle}</th>
        </tr>
        </thead> 
     
        {loop $users $user}
        <tr class="tatr2">
        	<td class="w25" >
        	{if $user.allowedit }<input type="checkbox" name="selectid[]" class="select" value="{$user.uid}"/>{/if}
        	</td>
        	<td  style="overflow:hidden"><a href="{$user.mvcurl}"  target="_blank" >{echo getUserPhoto($user.uid,'s') }</a>&nbsp;<a href="{$user.mvcurl}"  target="_blank" >{$user.uname}</a></td>                    	
            <td>{$user.email}</td>
            <td class="w35" align="center" >{$user.uid}</td>
            <td align="left">{$user.groupname}</td>
            <td>{$user.createtime|time}</td>                  
            <td>{if $user.allowedit }<a href="index.php?wskm=user&amp;act=edit&amp;id={$user.uid}">{lang edit}</a>&nbsp;|&nbsp;<a href="javascript:goto_confirm('{lang drop_confirm}', 'index.php?wskm=user&amp;act=del&amp;id={$user.uid}');">{lang drop}</a>{/if}</td>
        </tr>
         {/loop}
        {else}
        <tr >
            <td colspan="7" class="no_data" >{lang no_data}</td>
        </tr>
        {/if}
    </table>
    {if $users}
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
	            <select name="selectacttype" id="selectacttype2" onchange="$('#acttype').val(this.value);if(this.value=='movegroup'){$('#selectmovegid2').show();}else{$('#selectmovegid2').hide();}" >
	            </select>  
	        
	             <select class="querySelect" name="selectmovegid" id="selectmovegid2" onchange="$('#movegid').val(this.value);"  style="display:none">                
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

    //$('#actcp2').html($('#actcp').html());
    $('#selectacttype2').html($('#selectacttype').html());
    $('#selectmovegid2').html($('#selectmovegid').html());
    </script>
    <div class="clear"></div>
    
    {/if}
	</form>
</div>

</div>


{htmltemplate footer}