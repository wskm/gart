<?exit?>

<tr class="sp"  >
	<td colspan="2"></td>
</tr>

<tr class="nobg">
<td valign="top" >
<span class="editatitle">{lang poll_option}：</span>
</td>
<td>
<div class="mb5">
{lang multiple_choice}：<input type="checkbox" name="others[ismore]" value="1" {if $others[poll][ismore]}checked="true"{/if} />
&nbsp;&nbsp;{lang limit_days}：<input id="expireday" type="text"  size="2" maxlength="2" class="editinput" name="others[expire]" value="{$others[poll][expire]}" />
</div>

<div id="addpoll">
<p class="cleara" >
<input  type="text"  class="editinput" id="firstoption" name="others[polloptions][{$option_first.optionid}]" value="{$option_first.name}" />
<a href="javascript:void(0);" onclick="addpolloption()" >{lang option_add}</a></p>

{loop $others[options] $option}
<p class="cleara" >
<input  type="text"  class="editinput" name="others[polloptions][{$option.optionid}]" value="{$option.name}" />
</p>
{/loop}
</div>

</td>
</tr>
<tr class="sp"  >
	<td colspan="2"></td>
</tr>

<script type="text/javascript" >
	function addpolloption(){
		$('#addpoll').append('<p  class="cleara"><input type="text" class="editinput" name="others[options][]" value="" /></p>');
	}
</script>