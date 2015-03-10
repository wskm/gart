<?exit?>
{htmltemplate header}
<tr class="sp"  >
	<td colspan="2"></td>
</tr>
<div id="rightwrap">
<div id="rtitle">
	<p id="titlenav">{lang announce}&nbsp;&raquo;&nbsp;{if $id < 1}{lang add}{else}{lang edit}{/if}</p>
</div>
<div id="rmain">
	<div  class="clear"></div>
	<div class="h10"></div>
	<form method="post"id="article_form" onsubmit="return checkPost()" action="index.php?wskm=setting&act=announcehandle" >
    	<input type="hidden" name="arthash" value="{ART_HASH}" />
    	<input type="hidden" name="id" value="{$id}" />
    	 <table width="100%"  class="tb_article tbms1"  >

         <tr class="sp"  >
       		<td colspan="2"></td>
       </tr>
		<tr>
       		<td width="55"><span class="editatitle">{lang title}</span></td>
       		<td>
       		<input type="text" name="title" id="title" value="{$info[title]}" class="editinput" size="60" />
       		</td>
       </tr>
       <tr>
       		<td width="55"><span class="editatitle">{lang sort}</span></td>
       		<td>
       		<input type="text" name="displaysort" id="displaysort" value="{$info[displaysort]}" class="editinput" size="3" maxlength="3" />
       		</td>
       </tr>
       <tr>
       		<td valign="top"><span class="editatitle">{lang content}</span></td>
       		<td valign="top" >
       		<div style="widht:99%">
       		{$editor}
       		</div>
       		</td>
       </tr>
		<tr >
			<td ></td>
			<td >
			     <div class="btncright mt5">
	            <input type="submit" value="{lang submit}" class="btncommon">            
	            </div>
		    </td>
		</tr>
		<tr class="sp"  >
			<td colspan="2"></td>
		</tr>
		
        </table>
    </form>
    </div>
    
 <script type="text/javascript" >
 function checkPost(){
 	if(!$('#title').val()){
 		$('#title').focus();
 		return false;
 	}
 	return true;
 }
 jQuery(function(){
 	table_evenbg('tbms1',1);
 });
</script>
</div>
</div>
{htmltemplate footer}