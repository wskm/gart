<?exit?>
{htmltemplate header}
<div id="rightwrap">
	<div id="rtitle">
		<p id="titlenav">{lang tool}&nbsp;&raquo;&nbsp;{lang update_cache}</p>
	</div>
	<div id="rmain"  >
		<div style="background-color:#FAFAFA;width:100%;margin-top:120px;text-align:center;padding:20px 0;border-top:dashed 1px #E3E3E3;border-bottom:dashed 1px #E3E3E3">
			<div id="cbox">
			<input type="checkbox" checked="true" value="1" name="istpl" id="istpl" onchange="if(this.checked){this.value=1;}else{this.value=0;}" >{lang update_tpl}&nbsp;&nbsp;&nbsp;
			<input type="checkbox" checked="true" value="1" name="isdata" id="isdata"  onchange="if(this.checked){this.value=1;}else{this.value=0;}" >{lang update_data}
				<br><br>
			<a class="btnart"  href="javascript:void(0);" onclick="updatecache()" ><cite >{lang update_cache}</cite></a>
			</div>
			<div id="load" style="display:none"><img src="{ADMIN_URL}images/load.gif"  border="0" ></div>
		</div>	
		<script type="text/javascript">
			var lang_ok='{lang update_cache_ok}';
			var lang_err='{lang update_cache_bad}';
			function updatecache(){
				$('#cbox').hide();
				$('#load').show();
				$.get('index.php?wskm=tool&act=ajax_updatecache',{istpl:$('#istpl').val(),isdata:$('#isdata').val()},function(s){
					if(s){
						$('#load').html(lang_ok);
					}else{
						$('#load').html(lang_err);
					}
				});
			}
		</script>
		<div class="clear "></div>
	</div>
</div>

{htmltemplate footer}