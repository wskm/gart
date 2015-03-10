<?exit?>
{htmltemplate header}
<div id="rightwrap">
	<div id="rtitle">
		<p id="titlenav">{lang category}&nbsp;&raquo;&nbsp;{if $action =='edit'}{lang cate_edit}{else}{lang cate_add}{/if}</p>
	</div>
	<div id="rmain" >
		 
		<div class="clear"></div>

		<form method="post"  enctype="multipart/form-data" action="?wskm=category&act=edit&cateeidtgos=1">
		<input type="hidden" name="arthash" value="{ART_HASH}" />		
		<input type="hidden" name="postact" value="{$action}" />
		<input type="hidden" name="cid" value="{$cid}" />
		  <div class="divcommon"  > 
	  	  <table class="tb_article tbms1"  width="100%" id="cateset_tab_1">
	  	   <tr>
			  	<td width="90">
			  	<span class="editatitle">{lang parent_id}:</span>
			  	</td>
			  	<td>
			  	<select name="parentid" ><option value="0">{lang top_cate}</option>{$html_selects}</select>
			  	</td> 
		   </tr>
		    <tr>
			  	<td >
			  	<span class="editatitle">{lang cate_name}:</span>
			  	</td>
			  	<td>
			  	<input type="text" name="catename" id="catename" maxlength="50" class="editinput" value="{$cate.name}" />
			  	</td> 
		   </tr>
		   <tr>
			  	<td>
			  	<span class="editatitle">{lang isnav}:</span>
			  	</td>
			  	<td> 
			  	<input type="radio" name="isnav" value="1" {if $cate.isnav}checked='true'{/if} />{lang yes}&nbsp;<input type="radio" name="isnav" value="0"  {if !$cate.isnav}checked='true'{/if} />{lang no}
			  	</td> 
		   </tr>
		   <tr>
			  	<td >
			  	<span class="editatitle">{lang navkey}:</span>
			  	</td>
			  	<td>
			  	<input type="text" name="navkey"  value="{$cate.navkey}" class="editinput"  maxlength="20" />&nbsp;({lang navkey_notice})
			  	</td>  
		   </tr>
		    <tr>
			  	<td >
			  	<span class="editatitle">{lang sort}:</span>
			  	</td>
			  	<td>
			  	<input type="text" name="displaysort"  value="{$cate.displaysort}" size="2" maxlength="3" />			  	
			  	</td> 
		   </tr>
		    <tr>
			  	<td > 
			 	<span class="editatitle">{lang cover}:</span>
			  	</td>
			  	<td>
			  	<input type="file" name="cover[]" value="{$cate.cover}" /><input type="hidden" name="oldcover" id="oldcover" value="{$cate.cover}" />&nbsp;<a href="{ART_URL}attachments/{$cate.cover}" target="_blank"><img src="{ART_URL}attachments/{$cate.cover}" border="0" width="25" id="lookcover" /></a>
			  	</td> 
		   </tr>	 
		    <tr>
			  	<td >
			  	<span class="editatitle">{lang gourl}:</span>
			  	</td>
			  	<td>
			  	<input type="text" name="url"  value="{$cate.url}"  class="editinput" maxlength="255" />
			  	</td> 
		   </tr>	
		   
		   <tr class="sp"><td colspan="2"></td></tr>
		   
		    <tr class="nobg">
			  	<td  valign="top" >
			  	<span class="editatitle">{lang page_keywords}:</span>
			  	</td>
			  	<td>
			  	<textarea  name="keywords" rows="5" cols="80"  >{$cate.keywords}</textarea>
			  	</td> 
		   </tr>
		   <tr class="nobg">
			  	<td  valign="top">
			  	<span class="editatitle">{lang page_description}:</span>
			  	</td>
			  	<td>
			  	<textarea  name="description" rows="5" cols="80"    >{$cate.description}</textarea>
			  	</td> 
		   </tr>
		   <tr class="sp"><td colspan="2"></td></tr>
		   <tr >
			  	<td  valign="top">
			  	<span class="editatitle">{lang tpl_list}:</span>
			  	</td>
			  	<td>
			  	<input type="text" name="tpllist"  value="{$cate.tpllist}"  class="editinput" maxlength="50" />_html.php
			  	</td> 
		   </tr>
		   <tr >
			  	<td  valign="top">
			  	<span class="editatitle">{lang tpl_show}:</span>
			  	</td>
			  	<td>
			  	<input type="text" name="tplshow"  value="{$cate.tplshow}"  class="editinput" maxlength="50" />_html.php
			  	</td> 
		   </tr>
	  	  </table>
	  	  
	  	  <table width="100%" >
	  	  <tr>
	  	  <td width="80"></td>
		  <td>
          <div class="btncright mt5">
            <input class="btncommon" type="submit" value="{lang submit}" name="cateeidtgos"  />   
            </div>
             <div class="btncright mt5">
            <input class="btncommon" type="reset" value="{lang reset}" name="resset22"  />   
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

<div id="lookcover_menu" class="float_div" style="display:none"  ><img src="" id="coverbig" /></div>
<script type="text/javascript" >
table_evenbg('tbms1');
if($('#oldcover').val()){
	$('#coverbig').attr('src',$('#lookcover').attr('src'));
	displayMenu('lookcover');
}else{
	$('#lookcover').hide();
}
</script>
{htmltemplate footer}