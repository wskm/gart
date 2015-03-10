<?exit?>
{htmltemplate header}

<div class="inner mt5 mb5"  >
	<div id="artnav" >
		<div class="nav iconround">
		<span><a href="{ART_URL}">{lang index}</a>&nbsp;>&nbsp;<a href="{ART_URL}poll.php?isall=1" >{lang poll}</a></span>&nbsp;>&nbsp;{$others[title]}
		</div>	
	</div>   
</div>
   
<div id="artmain" > 
	<div id="searchwrap"  style="padding:20px 20px;">
		
		<div id="pollwrap"  class="mt5 mb15" style="width:650px;margin-left:auto;margin-right:auto;" >
			<form action="{ART_URL}poll.php" method="POST" >
			<input type="hidden" name="pollid" value="{$pollid}" />
			<table width="100%" >
			<tr>
				<td colspan="3"><span style="padding:5px 0;font-size:12px;font-weight:bold" >{$others[title]}</span></td>
			</tr>
			<tr>
				<td colspan="3"><span class="pollinfo" >{lang poll_count}:{$others[hits]}{if $others[expire]},{lang poll_endtime}:{$others[expire]|time:Y-m-d}{/if}&nbsp;({if $others[ismore]}{lang poll_moreselect}{else}{lang poll_singleselect}{/if})</span></td>
			</tr>
			<tr class="pollsp" ><td colspan="3"></td></tr>
			
			{loop $others[options] $option}
			<tr>
				<td colspan="2">{if !$others[isput] && !$others[isexpire]}<input type="{$others[inputtype]}" value="{$option[optionid]}" name="polloptions[]"/>{/if}<label for="poptionid{$option[optionid]}">{$option[name]}</label></td>
				<td></td>
			</tr>
			<tr > 
				<td width="20"></td>
				<td >    
				<div class="pollratiobar barbg" >
				<div class="pollratiobar" id="pollbar{$option[optionid]}" style="width:{$option[width]};"></div>
				</div> 
				<script type="text/javascript">
					dom('pollbar{$option[optionid]}').style.backgroundColor=randDeepColor();
				</script>
				</td>
				<td class="pollratioinfo" >
					&nbsp;&nbsp;{$option[ratio]}%&nbsp;/&nbsp;{$option[total]}
				</td>
			</tr>
			<tr class="pollsp" ><td colspan="3"></td></tr>
			{/loop}
			
			<tr>
			<td colspan="3" align="left" >{if $others[isput]}{lang poll_err_alreadyput}{elseif $others[isexpire]}{lang poll_err_expire}{else}<input type="submit" name="pollpostgo" value="{lang poll}" />{/if}</td>
			</tr>
			
			
			</table>
			</form>
			</div>
		
		
		<div class="clear"></div>
	</div>
</div>

{htmltemplate footer}    
