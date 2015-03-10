<?exit?>
{htmltemplate header}
<div id="rightwrap">
	<div id="rtitle">
		<p id="titlenav">{lang theme}&nbsp;&raquo;&nbsp;{lang theme_edit}</p>
	</div>
	<div id="rmain" >
		<div class="clear"></div>

		<form method="post" action="?wskm=theme&act=edit"  >
		<input type="hidden" name="arthash" value="{ART_HASH}" />
		<input type="hidden" name="styleid" value="{$styleid}" />
		  <div class="divcommon"  > 
	  	  <table class="tb_article tbms1"  width="100%" id="cateset_tab_1">
	  	  <tr>
			  	<td >
			  	<span class="editatitle">{lang theme_title}:</span>
			  	</td>
			  	<td>
			  	<input type="text" name="title" id="title" maxlength="50" class="editinput" value="{$info.title}" />
			  	</td> 
		   </tr>
		    <tr>
			  	<td >
			  	<span class="editatitle">{lang theme_color}:</span>
			  	</td>
			  	<td>
			  	<input type="text" name="color" id="color" maxlength="50" class="color editinput" value="{$info.color}" />
			  	</td> 
		   </tr>
	  	  <tr>
	  	  <td width="80"></td>
		  <td>
          <div class="btncright mt5">
            <input class="btncommon" type="submit" value="{lang submit}" name="postgos"  />   
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
<script type="text/javascript" src="{ART_URL}includes/js/jscolor.js"></script>
<script type="text/javascript">
table_evenbg('tbms1',1);
</script>
{htmltemplate footer}