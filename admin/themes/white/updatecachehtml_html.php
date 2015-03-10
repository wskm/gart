<?exit?>
{htmltemplate header}
<div id="rightwrap">
	<div id="rtitle">
		<p id="titlenav">{lang tool}&nbsp;&raquo;&nbsp;{lang update_cachehtml}</p>
	</div>
	<div id="rmain"  >
		<div style="background-color:#FAFAFA;width:100%;margin-top:120px;text-align:center;padding:20px 0;border-top:dashed 1px #E3E3E3;border-bottom:dashed 1px #E3E3E3">
			<div id="cbox">			
			<input type="checkbox" value="0" name="isindex" id="isindex"  onchange="if(this.checked){this.value=1;}else{this.value=0;}" >{lang html_updateindex}&nbsp;&nbsp;			
			<input type="checkbox" value="0" name="isall" id="isall"  onchange="if(this.checked){this.value=1;}else{this.value=0;}" >{lang html_clearall}<br /><br />
			{lang html_forcategoryid}<input type="text" name="cbeginid" id="cbeginid" value="0" size="5" />~<input type="text" value="0" name="cendid" id="cendid" size="5" />  <br /><br />
			{lang html_fornewsid}<input type="text" name="beginid" id="beginid" value="0" size="5" />~<input type="text" value="0" name="endid" id="endid" size="5" />

				<br><br>
			<a class="btnart"  href="javascript:void(0);" onclick="updatecache()" ><cite >{lang update_cache}</cite></a>
			</div>
			<div id="load" style="display:none"><img src="{ADMIN_URL}images/load.gif"  border="0" ></div>
		</div>	
		<script type="text/javascript">
			var indext='{$indext}';
			var catet='{$catet}'; 
			var newst='{$newst}'; 
			var lang_ok='{lang update_cache_ok}';
			var lang_err='{lang update_cache_bad}';
			var lang_updateindex='{lang update_index}';
			var lang_updateclear='{lang update_clearall}';
			var lang_newsfor='{lang update_newsfor}';
			var msg='';
			var isupdate=0;
			function updatecache(){
				$('#cbox').hide();
				$('#load').show();
				if(parseInt($('#isall').val())){
					isupdate=1;
					$.get('index.php?wskm=tool&act=ajax_htmlclear',function(s){
						msg +=lang_updateclear+'<br />';
						$('#load').html(msg);
					});
				}
				
				if(parseInt($('#isindex').val())){
					isupdate=1;
					$.get(indext,function(){
						msg +=lang_updateindex+'<br />';
						$('#load').html(msg);
					});
				}
				
				var beginid=parseInt($('#cbeginid').val());
				var endid=parseInt($('#cendid').val());
				if(beginid  > 0 && endid >0 && endid >= beginid){
					isupdate=1;
					var oknum=0;
					 while (beginid <= endid){
					 	if(oknum>100)break;
						var tourl=catet.replace(/thetplcid/ig, beginid);
						$.get(tourl,function(){
							oknum++;
							msg +=lang_newsfor+oknum+'<br />';
							$('#load').html(msg);
							
						});	
						beginid++;					
					}
				}
				
				beginid=parseInt($('#beginid').val());
				endid=parseInt($('#endid').val());
				if(beginid  > 0 && endid >0 && endid >= beginid){
					isupdate=1;
					var oknum=0;
					 while (beginid <= endid){
					 	if(oknum>100)break;
						var tourl=newst.replace(/thetplid/ig, beginid);
						$.get(tourl,function(){
							oknum++;
							msg +=lang_newsfor+oknum+'<br />';
							$('#load').html(msg);
							
						});	
						beginid++;					
					}
				}
				
				if(!isupdate){
					$('#load').html('');
				}
			}
		</script>
		<div class="clear "></div>
	</div>
</div>

{htmltemplate footer}