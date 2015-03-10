<?exit?>
{htmltemplate header}
<div id="rightwrap">
	<div id="rtitle">
		<p id="titlenav">{lang nav_manage}&nbsp;&raquo;&nbsp;{lang nav_edit}</p>
	</div>
	<div class="ml5 ">
		<div class="paddinglr5 txtleft">
		<a class="btnart"  href="index.php?wskm=setting&act=nav&hand=add" ><cite >{lang nav_add}</cite></a>
		</div>
	</div> 
	<div class="clear "></div>
	<div id="rmain" >
		<form method="post" action="index.php?wskm=setting&act=nav&hand={$hand}&navpostgo=1">
		<input type="hidden" name="arthash" value="{ART_HASH}" />
		<input type="hidden" name="nid" value="{$nid}" />
		  <div class="divcommon"  > 
	  	  <table class="tb_article tbms1"  width="100%">
		    <tr>
			  	<td width="130" >
			  	<span class="editatitle">{lang name}：</span>
			  	</td>
			  	<td>
			  	<input type="text" name="name" id="name" maxlength="50" class="editinput" value="{$info.name}" />
			  	</td> 
		   </tr>
		   <tr>
			  	<td width="130" >
			  	<span class="editatitle">{lang color}：</span>
			  	</td>
			  	<td>
			  	<input type="text" name="color" id="color" maxlength="50" class="color" value="{$info.color}" />
			  	</td> 
		   </tr>
		   <tr>
			  	<td>
			  	<span class="editatitle">{lang if_show}：</span>
			  	</td>
			  	<td>
			  	<input type="radio" name="status" value="1" {if $info.status}checked='true'{/if} />{lang yes}&nbsp;<input type="radio" name="status" value="0"  {if !$info.status}checked='true'{/if} />{lang no}
			  	</td> 
		   </tr>
		    <tr>
			  	<td >
			  	<span class="editatitle">{lang sort}：</span>
			  	</td>
			  	<td>
			  	<input type="text" name="displaysort"  value="{$info.displaysort}" size="2" maxlength="3" />			  	
			  	</td> 
		   </tr>
		    <tr>
			  	<td >
			  	<span class="editatitle">{lang tourl}：</span>
			  	</td>
			  	<td>
			  	<input type="text" name="url"  value="{$info.url}"  class="editinput" maxlength="255" />
			  	</td> 
		   </tr>	
  			<tr>
			  	<td >
			  	<span class="editatitle">{lang typetarget}：</span>
			  	</td>
			  	<td>
			  	<input type="radio" name="target" value="1" {if $info.target}checked='true'{/if} />{lang typetarget_blank}&nbsp;<input type="radio" name="target" value="0"  {if !$info.target}checked='true'{/if} />{lang typetarget_self}
			  	</td> 
		   </tr>	
		   
		   <tr class="sp"><td colspan="2"></td></tr>
	  	  </table>
	
	  	  <table width="100%" >
	  	  <tr>
	  	  <td width="80"></td>
		  <td>
          <div class="btncright mt5">
            <input class="btncommon" type="submit" value="{lang submit}" name="navpostgo"  />   
            </div>
             <div class="btncright mt5">
            <input class="btncommon" type="reset" value="{lang reset}"  />   
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
<script type="text/javascript" src="{ART_URL}includes/js/jscolor.js"></script>
<script type="text/javascript" >
table_evenbg('tbms1');
</script>
{htmltemplate footer}