<?exit?>
{htmltemplate header}

<link rel="stylesheet" type="text/css" href="{ART_INC_URL}tigra_calendar/calendar.css" />
 <script type="text/javascript">
 var calendar_imgpath=SITE_URL+'includes/tigra_calendar/img/';
 var calendar_lang='{LANGUAGE}';
 </script>
<script type="text/javascript" src="{ART_INC_URL}tigra_calendar/calendar.js" ></script>
<tr class="sp"  >
	<td colspan="2"></td>
</tr>
<div id="rightwrap">
<div id="rtitle">
	<p id="titlenav">{lang ad}&nbsp;&raquo;&nbsp;{if $id < 1}{lang add}{else}{lang edit}{/if}</p>
</div>
<div id="rmain">
	<div  class="clear"></div>
	<div class="h10"></div>
	<form method="post" id="postgo" enctype="multipart/form-data" name="postgo" onsubmit="return checkPost()" action="index.php?wskm=setting&act=adhandle" >
    	<input type="hidden" name="arthash" value="{ART_HASH}" />
    	<input type="hidden" name="id" value="{$id}" />
    	 <table width="100%"  class="tb_article tbms1"  >

    	 <tbody>
         <tr class="sp"  >
       		<td colspan="2"></td>
       </tr>
		<tr>
       		<td width="85"><span class="editatitle">{lang title}</span></td>
       		<td>
       		<input type="text" name="title" id="title" value="{$info[title]}" class="editinput" size="30" />
       		</td>
       </tr>
       <tr>
       		<td ><span class="editatitle">{lang type}</span></td>
       		<td>
       		<select name="typeid" id="typeid" >
       		{loop $adtype $adt}
       		<option value="{$adt.typeid}" {if $info.typeid== $adt.typeid }selected{/if} >{$adt.name}</option>
       		{/loop}
       		</select>
       		</td>
       </tr>
       <tr>
       		<td ><span class="editatitle">{lang enabled}</span></td>
       		<td>
       		<input type="radio" name="status" value="1" {if $info.status}checked='true'{/if} />{lang yes}&nbsp;<input type="radio" name="status" value="0"  {if !$info.status}checked='true'{/if} />{lang no}
       		</td>
       </tr>
       <tr>
       		<td ><span class="editatitle">{lang sort}</span></td>
       		<td>
       		<input type="text" name="displaysort" id="displaysort" value="{$info[displaysort]}" class="editinput" size="3" maxlength="3" />
       		</td>
       </tr>
       <tr>
       		<td><span class="editatitle">{lang ad_begintime}</span></td>
       		<td>
       		<input type="text" name="begintime" id="begintime" value="{$info[begintime]|time:Y-m-d}" class="editinput" size="15"  />
       		<script type="text/javascript">
       		 new tcal ({
			 	'formname': 'postgo',
			 	'controlname': 'begintime'
			 });
       		</script>
       		</td>
       </tr>
        <tr>
       		<td ><span class="editatitle">{lang ad_endtime}</span></td>
       		<td>
       		<input type="text" name="endtime" id="endtime" value="{if $info[endtime]}{$info[endtime]|time:Y-m-d}{/if}" class="editinput" size="15"  />
       		<script type="text/javascript">
			 new tcal ({
			 	'formname': 'postgo',
			 	'controlname': 'endtime'
			 });
       		</script>
       		&nbsp;({lang ad_endtimenotice2})
       		</td>
       </tr>
       <tr>
       		<td></td>
       		<td>
       		<select onchange="var styles, key;styles=['code','text','image','flash']; for(key in styles) {var obj=dom('style_'+styles[key]); obj.style.display=styles[key]==this.options[this.selectedIndex].value?'':'none';}" name="addnew[style]">
       		<option value="code" {if $info[args][style] == 'code'}selected{/if} >{lang ad_code}</option>
       		<option value="text" {if $info[args][style] == 'text'}selected{/if}>{lang ad_text}</option>
       		<option value="image" {if $info[args][style] == 'image'}selected{/if}>{lang ad_image}</option>
       		<option value="flash" {if $info[args][style] == 'flash'}selected{/if}>{lang ad_flash}</option></select>
       		</td>
       </tr>
       </tbody>
       
       <tbody id="style_code"  {if $info[args][style] != 'code'}style="display: none;"{/if} >
		<tr>
		<td><span class="editatitle">{lang code_html}</span></td>
		<td >
		<textarea cols="60" id="addnew[code][html]" name="addnew[code][html]"  rows="6">{$info[code]}</textarea>
		</td></tr>
		</tbody>
		
		<tbody id="style_text" {if $info[args][style] != 'text'}style="display: none;"{/if} >		
		<tr>
			<td ><span class="editatitle">{lang text_title}</span></td>
			<td>
			<input type="text" size="30" class="editinput" value="{$info[args][title]}" name="addnew[text][title]">&nbsp;*</td></tr>
		<tr>
		<td><span class="editatitle">{lang text_link}</span></td>
		<td>
		<input type="text" size="30" class="editinput" value="{$info[args][link]}" name="addnew[text][link]">&nbsp;*</td></tr>
		<tr><td><span class="editatitle">{lang text_color}</span></td>
		<td>
		<input type="text" size="30" class="editinput" value="{$info[args][color]}" name="addnew[text][color]">&nbsp;#FAFAFA</td>
		</tr>
		<tr><td><span class="editatitle">{lang text_size}</span></td>
		<td>
		<input type="text" size="30" class="editinput" value="{$info[args][size]}" name="addnew[text][size]">&nbsp;12px,12pt,12em</td>
		</tr>
		</tbody>
		
		<tbody {if $info[args][style] != 'image'}style="display: none;"{/if} id="style_image">
		<tr>
			<td ><span class="editatitle">{lang image_url}</span></td>
			<td >
			<input type="text" size="30" class="editinput" value="{$info[args][url]}" name="addnew[image][url]">&nbsp;*
			</td>
		</tr>
		<tr>
			<td ></td>
			<td >
			<input type="file" name="imagefile[]" />&nbsp;({lang ad_imagefilenotice})
			</td>
		</tr> 
		<tr>
			<td ><span class="editatitle">{lang image_link}</span></td>
			<td >
		<input type="text" size="30" class="editinput" value="{$info[args][link]}" name="addnew[image][link]">&nbsp;*</td>
		</tr>
		<tr>
			<td ><span class="editatitle">{lang image_width}</span></td><td >
			<input type="text" size="30" class="editinput" value="{$info[args][width]}" name="addnew[image][width]">
			</td>
		</tr>
		<tr>
			<td><span class="editatitle">{lang image_height}</span></td>
			<td>
			<input type="text" size="30" class="editinput" value="{$info[args][height]}" name="addnew[image][height]"></td>
		</tr>
		<tr>
			<td ><span class="editatitle">{lang image_alt}</span></td>
			<td>
		<input type="text" size="30" class="editinput" value="{$info[args][alt]}" name="addnew[image][alt]"></td>
		</tr>
		</tbody>
		
		<tbody {if $info[args][style] != 'flash'}style="display: none;"{/if} id="style_flash">
		<tr><td><span class="editatitle">{lang flash_url}</span></td>
			<td >
			<input type="text" size="30" class="editinput" value="{$info[args][url]}" name="addnew[flash][url]">&nbsp;*</td>
		</tr>
		<tr>
			<td ></td>
			<td >
			<input type="file" name="flashfile[]" />&nbsp;({lang ad_falshfilenotice})
			</td>
		</tr> 
		<tr><td><span class="editatitle">{lang flash_width}</span></td><td >
		<input type="text" size="30" class="editinput" value="{$info[args][width]}" name="addnew[flash][width]">&nbsp;*</td>
		</tr>
		<tr><td><span class="editatitle">{lang flash_height}</span></td><td>
		<input type="text" size="30" class="editinput" value="{$info[args][height]}" name="addnew[flash][height]">&nbsp;*</td>
		</tr>
		</tbody>

       <tbody>
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
		</tbody>
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