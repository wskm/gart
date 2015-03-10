<?exit?>
{htmltemplate header}

<div id="artmain">
	<div class="skinaua mainbg_1">
	 	<div id="main_1" class="w210 border_au" >
		 	<div class="au_head">
		 		<span class="title">{lang user_home}</span>
		 	</div>
		 	<div class="au_body">
		 		<div class="menu">
					
					<ul>
					  <li {if $act=='home'}class="current"{/if} >
					 	<span class="dot"><a href="##htmlurl(user)##" >{lang user_set}</a></span>
					  </li>
					  {if $usergroup.isarticle}
					  <li {if $act=='article'}class="current"{/if}>
					  <span class="dot"><a href="##htmlurl(user/article)##" >{lang article_my}</a></span>
					  </li>
					  {/if}
					  <li {if $act=='password'}class="current"{/if}>
					 	<span class="dot"><a href="##htmlurl(user/password)##" >{lang user_password}</a></span>
					  </li>
					  
					</ul>
  				 </div>
		 	</div>
		 	<div class="au_foot"></div>
	 	</div>
	 	<div id="main_2" class="ml10 w720 border_au" >
		 	<div class="au_head">
		 		<span class="title">{$user_nav}</span>
		 	</div>
		 	<div class="au_main_body">
            	<div class="center">
            	{if $act=='home'}
	                <div class="center_nav">
	                    <div class="aunav">
	                        <ul>
	                         <li {if $unav=='default' }class="current"{/if} ><span><a href="##htmlurl(user)##">{lang user_info}</a></span><span class="navr"></span></li>
	                         <li {if $unav=='profile_set' }class="current"{/if} ><span><a href="##htmlurl(user/profile)##">{lang user_profile}</a></span><span class="navr"></span></li>
	                         <li {if $unav=='web_set' }class="current"{/if} ><span><a href="##htmlurl(user/webset)##"">{lang user_webset}</a></span><span class="navr"></span></li>
	                         <li {if $unav=='photo_set' }class="current"{/if} ><span><a href="##htmlurl(user/photo)##"">{lang user_photo}</a></span><span class="navr"></span></li>
	                        </ul>
	                    </div>  
					</div>
				
					<div class="wrap">
						<div class="profile">
						{if $unav=='default' }
						  	  	<div class="photo">
						  	  	  <div>{echo getUserPhoto(UID,'b') }</div>
								  <p><a class="sub" href="##htmlurl(user/photo)##" >{lang photo_update}</a></p>
								</div>
						  	    <div class="icontents">
						  	      <table cellspacing="0" cellpadding="0" border="0"  width="100%">
						  	      	<tbody><tr>
						  	      	  <td class="name">{lang username}</td>
									  <td>{$profile.uname}</td>
						  	      	</tr>
						  	      	
									<tr>
						  	      	  <td class="name">{lang email}</td>
									  <td>
									  {$profile.email}&nbsp;
									  {if $isEmailVerify && !$profile.emailverify}	
									  <a href="javascript:;" id="verifyemail" onclick="emailVerify()" style="color:#E66C02">{lang emailverify}</a>
									  <script type="text/javascript" >
									  	function emailVerify(){
									  		ajaxXML('##url(user/emailverify)##','verifyemail','verifyemail');
									  	}
									  </script>
						  	      	{/if}
									  </td>
						  	      	</tr>
						  	      
						  	      	<tr>
						  	      	  <td class="name">{lang usergroup_name}</td>
									  <td>{$usergroup.groupname}</td>
						  	      	</tr>
						  	      	<tr>
						  	      	  <td class="name">{lang user_accesslevel}</td>
									  <td>{$usergroup.accesslevel}</td>
						  	      	</tr>
						  	      	
									<tr>
						  	      	  <td class="name">{lang sex}</td>
									  <td>{if $profile.sex}{lang female}{else}{lang male}{/if}</td>
						  	      	</tr>
									<tr>
						  	      	  <td class="name">{lang birthday}</td>
									  <td>{$profile.birthday}</td>
						  	      	</tr>
									<tr>
						  	      	  <td class="name">IP</td>
									  <td>{$profile.lastip}</td>
						  	      	</tr>
						  	      	<tr>
						  	      	  <td class="name">{lang replycount}</td>
									  <td>{$profile.replycount}</td>
						  	      	</tr>	
						  	      	<tr>
						  	      	  <td class="name">{lang regtime}</td>
									  <td>{$profile.createtime|time}</td>
						  	      	</tr>						  	      	
									<tr>
						  	      	  <td class="name">{lang lastreplytime}</td>
									  <td>{$profile.lastreplytime|time}</td>
						  	      	</tr>		
						  	      	<tr>
						  	      	  <td class="name">{lang lastlogintime}</td>
									  <td>{$profile.lastvisit|time}</td>
						  	      	</tr>
						  	      	
								  </tbody></table>
							    </div>
								<div class="clear"></div>
						 {elseif $unav=='web_set'}
						 		<div class="mcontents">
						 		<form action="##htmlurl(user/webset)##" method="POST"  >
								<input type="hidden" name="arthash" value="{ART_HASH}" >
						  	      <table cellspacing="0" cellpadding="0" border="0"  width="100%">
						  	      	<tbody>
								
									<tr>
						  	      	  <td class="name">{lang timezone}</td>
									  <td>
									  <select name="timeoffset" id="timeoffset" >
									  	<option value="-12">(GMT -12:00) Eniwetok, Kwajalein&nbsp;&nbsp;</option>
										<option value="-11">(GMT -11:00) Midway Island, Samoa&nbsp;&nbsp;</option>
										<option value="-10">(GMT -10:00) Hawaii&nbsp;&nbsp;</option>
										<option value="-9">(GMT -09:00) Alaska&nbsp;&nbsp;</option>
										<option value="-8">(GMT -08:00) Pacific Time (US &amp; Canada), Tijuana&nbsp;&nbsp;</option>
										<option value="-7">(GMT -07:00) Mountain Time (US &amp; Canada), Arizona&nbsp;&nbsp;</option>
										<option value="-6">(GMT -06:00) Central Time (US &amp; Canada), Mexico City&nbsp;&nbsp;</option>
										<option value="-5">(GMT -05:00) Eastern Time (US &amp; Canada), Bogota, Lima, Quito&nbsp;&nbsp;</option>
										<option value="-4">(GMT -04:00) Atlantic Time (Canada), Caracas, La Paz&nbsp;&nbsp;</option>
										<option value="-3.5">(GMT -03:30) Newfoundland&nbsp;&nbsp;</option>
										<option value="-3">(GMT -03:00) Brassila, Buenos Aires, Georgetown, Falkland Is&nbsp;&nbsp;</option>
										<option value="-2">(GMT -02:00) Mid-Atlantic, Ascension Is., St. Helena&nbsp;&nbsp;</option>
										<option value="-1">(GMT -01:00) Azores, Cape Verde Islands&nbsp;&nbsp;</option>
										<option value="0">(GMT) Casablanca, Dublin, Edinburgh, London, Lisbon, Monrovia&nbsp;&nbsp;</option>
										<option value="1">(GMT +01:00) Amsterdam, Berlin, Brussels, Madrid, Paris, Rome&nbsp;&nbsp;</option>
										<option value="2">(GMT +02:00) Cairo, Helsinki, Kaliningrad, South Africa&nbsp;&nbsp;</option>
										<option value="3">(GMT +03:00) Baghdad, Riyadh, Moscow, Nairobi&nbsp;&nbsp;</option>
										<option value="3.5">(GMT +03:30) Tehran&nbsp;&nbsp;</option>
										<option value="4">(GMT +04:00) Abu Dhabi, Baku, Muscat, Tbilisi&nbsp;&nbsp;</option>
										<option value="4.5">(GMT +04:30) Kabul&nbsp;&nbsp;</option>
										<option value="5">(GMT +05:00) Ekaterinburg, Islamabad, Karachi, Tashkent&nbsp;&nbsp;</option>
										<option value="5.5">(GMT +05:30) Bombay, Calcutta, Madras, New Delhi&nbsp;&nbsp;</option>
										<option value="5.75">(GMT +05:45) Katmandu&nbsp;&nbsp;</option>
										<option value="6">(GMT +06:00) Almaty, Colombo, Dhaka, Novosibirsk&nbsp;&nbsp;</option>
										<option value="6.5">(GMT +06:30) Rangoon&nbsp;&nbsp;</option>
										<option value="7">(GMT +07:00) Bangkok, Hanoi, Jakarta&nbsp;&nbsp;</option>
										<option value="8">(GMT +08:00) Beijing, Hong Kong, Perth, Singapore, Taipei&nbsp;&nbsp;</option>
										<option value="9">(GMT +09:00) Osaka, Sapporo, Seoul, Tokyo, Yakutsk&nbsp;&nbsp;</option>
										<option value="9.5">(GMT +09:30) Adelaide, Darwin&nbsp;&nbsp;</option>
										<option value="10">(GMT +10:00) Canberra, Guam, Melbourne, Sydney, Vladivostok&nbsp;&nbsp;</option>
										<option value="11">(GMT +11:00) Magadan, New Caledonia, Solomon Islands&nbsp;&nbsp;</option>
										<option value="12">(GMT +12:00) Auckland, Wellington, Fiji, Marshall Island&nbsp;&nbsp;</option>
									  </select>
									  <script type="text/javascript">
									  var timezone=parseFloat('{$profile.timeoffset}');
									  if(timezone==99){
									  	timezone=8;
									  }

									  dom('timeoffset').value=timezone;

									  </script>
									  </td>
						  	      	</tr>
						  	      	<tr>
						  	      	  <td class="name">{lang timezone_format}</td>
									  <td>
									  <select name="timeformat" >
									  {loop $timeformats $key $tempi}
										<option value="{$key}" {if $profile.timeformat == $key}selected="true"{/if} >{$tempi}</option>
									   {/loop}
									  </select>&nbsp;&nbsp;({php echo now();})
									 </td>
						  	      	</tr>
						  	      	<tr>
						  	      	  <td class="name">{lang showemail}</td>
									  <td><input type="radio" name="showemail" {if $profile.showemail}checked="true"{/if} value="1">{lang yes}&nbsp;&nbsp;&nbsp;<input type="radio" name="showemail" {if !$profile.showemail}checked="true"{/if} value="0">{lang no}</td>
						  	      	</tr>
						  	      	<tr>
						  	      	  <td class="name">{lang acceptemail}</td>
									  <td><input type="radio" name="sendemail" {if $profile.sendemail}checked="true"{/if} value="1">{lang yes}&nbsp;&nbsp;&nbsp;<input type="radio" name="sendemail" {if !$profile.sendemail}checked="true"{/if} value="0">{lang no}</td>
						  	      	</tr>
						  	      	
									<tr class="nobor">
										<td></td>
										<td>
										<!--
										<input type="submit"  class="submit" name="pgo" value="" >
										-->
										<div class="abtnrw abtn fl">
										<button type="submit" class="abtnlw" name="pgo" >{lang submit}</button>
										</div>
										
										</td>
									</tr>
								  </tbody></table>
								 </form>
							    </div>
								<div class="clear"></div>
						 {elseif $unav=='profile_set'}
						 <link rel="stylesheet" type="text/css" href="{ART_INC_URL}tigra_calendar/calendar.css" />
						 <script type="text/javascript">
						 var calendar_imgpath=WEB_URL+'includes/tigra_calendar/img/';
						 var calendar_lang='{LANGUAGE}';
						 </script>
						 <script type="text/javascript" src="{ART_INC_URL}tigra_calendar/calendar.js" ></script>
						 
						 
						 		<div class="mcontents">
						 		<form action="##htmlurl(user/profile)##" method="POST" name="userform" >
								<input type="hidden" name="arthash" value="{ART_HASH}" >
						  	      <table cellspacing="0" cellpadding="0" border="0"  width="100%">
						  	      	<tbody>
									<tr>
						  	      	  <td class="name">{lang email}</td>
									  <td><input type="text" value="{$profile.email}" name="email" id="email"  maxlength="50" /></td>
						  	      	</tr>
									<tr>
						  	      	  <td class="name">{lang sex}</td>
									  <td><input type="radio" name="sex" value="0" {if !$profile.sex}checked="true"{/if} >{lang male}&nbsp;&nbsp;&nbsp;<input type="radio" name="sex" value="1" {if $profile.sex}checked="true"{/if} >{lang female}</td>
						  	      	</tr>
									<tr>
						  	      	  <td class="name">{lang birthday}</td>
									  <td><input type="text" value="{$profile.birthday}" name="birthday" id="birthday"  maxlength="15" />
									  <script language="JavaScript">			  
									  new tcal ({
									  	'formname': 'userform',
									  	'controlname': 'birthday'
									  });
									</script>
									  </td>
						  	      	</tr>
									<tr class="nobor">
										<td></td>
										<td>
										
										<div class="abtnrw abtn fl">
										<button type="submit" class="abtnlw" name="pgo" >{lang submit}</button>
										</div>
										</td>
									</tr>
								  </tbody></table>
								</form>
							    </div>
								<div class="clear"></div>
						{elseif $unav=='photo_set'}
								<div>
									{$photohtml}
									  <br> <br>
							  	  	  <div>{echo getUserPhoto(UID,'s',1,$updatephoto) }&nbsp;{lang photo_small}</div>
							  	  	  <br>
							  	  	   <div>{echo getUserPhoto(UID,'m',1,$updatephoto) }&nbsp;{lang photo_middle}</div>
							  	  	   <br>
							  	  	    <div>{echo getUserPhoto(UID,'b',1,$updatephoto) }&nbsp;{lang photo_big}</div>
							  	  	 
						  	  	 
								</div>
						 {/if}
						</div>
		           </div>
		           {elseif $act=='password' }
		           <div class="wrap">
						<div class="profile">
							<div class="mcontents">
							<form action="##htmlurl(user/password)##" method="POST" >
							<input type="hidden" name="arthash" value="{ART_HASH}" >
						  	      <table cellspacing="0" cellpadding="0" border="0"  width="100%">
						  	      	<tbody>
									<tr>
						  	      	  <td class="name">{lang pw_old}</td>
									  <td><input type="password" value="" name="oldpw" id="oldpw"  maxlength="32" /></td>
						  	      	</tr>
									<tr>
						  	      	  <td class="name">{lang pw_new}</td>
									  <td><input type="password" value="" name="newpw" id="newpw"  maxlength="32" /></td>
						  	      	</tr>
									<tr>
						  	      	  <td class="name">{lang pw_newrepeat}</td>
									  <td><input type="password" value="" name="newpwc" id="newpwc"  maxlength="32" /></td>
						  	      	</tr>
									<tr  class="nobor">
										<td></td>
										<td>
										<div class="abtnrw abtn fl">
										<button type="submit" class="abtnlw" name="pgo" >{lang submit}</button>
										</div>
										</td>
									</tr>
								  </tbody></table>
							</form>
							    </div>
								<div class="clear"></div>
						</div>
				   </div>
				   {elseif $act=='article' }
					  <div class="center_nav">
		                    <div class="aunav">
		                        <ul>
		                         <li {if $unav=='my' }class="current"{/if} ><span><a href="##htmlurl(user/article)##">{lang article_normal}</a></span><span class="navr"></span></li>
		                         <li {if $unav=='audit' }class="current"{/if} ><span><a href="##htmlurl(user/article/status:2)##">{lang article_audit}</a></span><span class="navr"></span></li>
		                         <li {if $unav=='do' }class="current"{/if} ><span><a href="##htmlurl(user/articledo)##" id="articleunav" >{lang article_write}</a></span><span class="navr"></span></li>		                         		      
		                        </ul>
		                    </div>  
						</div>
					
						<div class="wrap">						
							
							<div class="profile">
								<div class="mcontents">
								{if $unav=='do' || $unav=='edit' }
									<style type="text/css">
										.coveruploadwrap{width:280px;padding:30px 60px 50px 50px}
									</style>
									<div id="coveruploadwrap" style="display:none" class="coveruploadwrap" ><form id="attchform" enctype="multipart/form-data" action="{ART_URL}wskm.php?act=upload"  method="POST" onsubmit="coverPost();return false;" ><input type="hidden" name="arthash" value="{ART_HASH}" /><input type="file" name="postfile[]" id="coverupload"  style="height:22px;" />&nbsp;&nbsp;<input type="submit" class="submit" value="{lang submit}" /></form></div>									
									<script type="text/javascript" src="{ART_URL}includes/js/jquery.js"></script>
									<script type="text/javascript">
									var editname='{$editname}';
					            	var oEditor=null;
					            	var editdrive='{$editdrive}';
									function setEditor(){
										if(oEditor != null)return;
										if(editdrive=='ckeditor'){
											oEditor=CKEDITOR.instances.{$editname};
										}else if(editdrive=='xheditor'){
											oEditor=$('#'+editname).xheditor();
										}
										if(oEditor == null) alert('Editor Error!');
									}
									
									function insertEditHtml(txt){
										setEditor();
					            		if(editdrive=='ckeditor'){
					            			oEditor.insertHtml(txt);
					            		}else if(editdrive=='xheditor'){
					            			oEditor.pasteHTML(txt);
					            		}
					            	}
									
					            	 function checkArticlePost(){
								    	var title=$('#title');
								    	if(!title.val()){
								    		title.focus();
								    		return false;
								    	}
								    	var cid=$('#cid');
								    	if(!cid.val()){
								    		cid.focus();
								    		return false;
								    	}
								    	return true;
								    }
								    
								    var boxhtml='';
									function showCoverUpload(){
										boxhtml='<div class="coveruploadwrap"><div id="coveruploadmain" >'+$('#coveruploadwrap').html()+'</div></div>';
										if($('#wkcen_coverupload')){
											$('#wkcen_coverupload').html(boxhtml);
										}
										showBox('coverupload','',{'title':'{lang upload_attch}','msg':boxhtml});
									}
									
									var clicktype='';
									var origincode='';
									function coverPost(){
										if(!$('#coverupload').val()){
											alert('{lang upload_onepls}');
											return;
										}
																														
										if(clicktype == 'news' ){
											newsPost();
										}else{
											formPost('attchform','','','',function(s){
												var attach=jsonParse(s);
												if(attach[0]['aid'] > 0){
													$('#coverthumb').val(attach[0]['path']);
													$('#coverid').val(attach[0]['aid']);
													imgNotice('coveruploadmain','{lang upload_ok}',1);
													
													setTimeout(function(){														
														closeBox('coverupload');
													},300);
												}else{
													$('#wkcen_coverupload').html('<div class="coveruploadwrap">'+attach[0]['err']+'</div>');
													//alert(attach[0]['err']);
												}
												
											});
										}								
										
									}

									function newsPost(){
										formPost('attchform','','','',function(s){
											var attach=jsonParse(s);
											if(attach[0]['aid'] > 0){
												imgNotice('coveruploadmain','{lang upload_ok}',1);
												$('#inputwrap').append('<input type="hidden" name="attachadd[]" value="'+attach[0]['aid']+'" />');
												insertEditHtml('<img src="'+WEB_URL+attach[0]['path']+'" border="0" />');
												$('#wkcen_coverupload').html(boxhtml);
												setTimeout(function(){
													closeBox('coverupload');
												},600);
											}else{
												alert(attach[0]['err']);
											}
										});

									}


									</script>
									<form method="POST" action="##htmlurl(user/articledo)##" enctype="multipart/form-data" onsubmit="return checkArticlePost()" >
									<input type="hidden" name="arthash" value="{ART_HASH}" />
						    		<input type="hidden" name="aid" id="aid" value="{$aid}" />
						    		<input type="hidden" name="coverid" id="coverid" value="0" />
											<table cellspacing="0" cellpadding="0" border="0"  width="100%">
								  	      	<tbody>
								  	      	<tr>
								  	      		<td width="60" align="right" >{lang maintitle}</td>
								  	      		<td><input type="text" name="title" value="{$info.title}"  id="title" size="50" maxlength="80" />&nbsp;({lang maintitle_notice})</td>
								  	      	</tr>   
								  	      	<tr>
								  	      		<td align="right" >{lang sys_category}</td>
								  	      		<td><select id="cid" name="cid" ><option value="" >{lang select_pls}</option>{$cateoption}</select> </td>
								  	      	</tr>
								  	      	<tr>
								  	      		<td align="right" >{lang cover}</td>
								  	      		<td>
								  	      			<input type="text" name="coverthumb" id="coverthumb" value="{$info.cover}" size="30" />{if $usergroup.isupload}&nbsp;<button type="button" style="height:25px;line-height:25px;" onclick="clicktype='cover';showCoverUpload()" >{lang upload}</button>{/if}&nbsp;({lang cover_notice})		
								  	      		</td>								  	      		
								  	      	</tr>
								  	      	<tr> 
								  	      		<td align="right" >{lang article_summary}</td>
								  	      		<td><textarea id="summary" name="summary" style="height:42px" cols="60" >{$info.summary}</textarea>	</td>
								  	      	</tr>
								  	      	<tr class="nobor" >    
								  	      		<td  colspan="2" >
								  	      		<div style="width:99%;padding:8px 0">								  	      		
								  	      		{$editor}							  	      		
								  	      		</div>
								  	      		</td>
								  	      	</tr>
								  	      	{if $usergroup.isupload}
								  	      	<tr class="nobor" >    
								  	      		<td  colspan="2" align="right" >
								  	      		<a href="javascript:;" onclick="clicktype='news';showCoverUpload();" >{lang upload_attch}</a>&nbsp;&nbsp;
								  	      		</td>
								  	      	</tr>
								  	      	{/if}
								  	      	<tr>
								  	      		<td align="right" >{lang tag}</td>
								  	      		<td><input type="text" name="tags" id="tags" value="{$info.tags}" size="30"  maxlength="50" />&nbsp;({lang tag_notice})</td>
								  	      	</tr>
								  	      	<tr>
								  	      		<td align="right" >{lang author}</td>
								  	      		<td><input type="text" name="author" id="author" value="{$info.author}" size="30" maxlength="20" /></td>
								  	      	</tr>
								  	      	<tr>
								  	      		<td align="right" >{lang fromname}</td>
								  	      		<td><input type="text" name="fromname" id="fromname" value="{$info.fromname}" size="30" maxlength="20" /></td>
								  	      	</tr>
								  	      	<tr>
								  	      		<td align="right" >{lang fromurl}</td>
								  	      		<td><input type="text" name="fromurl" id="fromurl" value="{$info.fromurl}" size="30" maxlength="150" /></td>
								  	      	</tr>
								  	      	<tr>
								  	      		<td align="right" >{lang reply_state}</td>
								  	      		<td>
								  	      		<input id="rs2" type="radio" name="replystate" value="2" {if $info.replystate == 2} checked="checked"{/if} />
							                    <label for="rs2">{lang replay_anonym}</label>
							            		<input id="rs1" type="radio" name="replystate" value="1" {if $info.replystate == 1} checked="checked"{/if} />
							                    <label for="rs1">{lang replay_member}</label>
							                    <input id="ano" type="radio" name="replystate" value="0" {if $info.replystate == 0} checked="checked"{/if} />
							                    <label for="ano">{lang replay_no}</label>
								  	      		</td>
								  	      	</tr>
								  	      	<tr class="nobor">
												<td align="left" ></td>
												<td colspan="3">
													<div style="display:none" id="inputwrap"></div>
													<div class="abtnrw abtn fl">
													<button name="pgo" class="abtnlw" type="submit">{lang submit}</button>
													</div>
												</td>
											</tr>
								  	      	
								  	      	</tbody>
								  	      	</table>
								  	 </form>
								{else}
										{if $list}
											<form method="POST" action="##htmlurl(user/article)##" >
											<input type="hidden" name="arthash" value="{ART_HASH}" />
											<input type="hidden" name="ptype" value="{$unav}" />
											<table cellspacing="0" cellpadding="0" border="0"  width="100%">
								  	      	<tbody>
								  	      	{loop $list $tempi}
											<tr>
												<td align="left" valign="middle">
													<div style="width:450px;overflow:hidden">
													<input type="checkbox" value="{$tempi.aid}" name="selectid[]" class="select" />&nbsp;
													<span class="grey ff1">[{$tempi.cname}]</span>&nbsp;&nbsp;<a href="{$tempi.mvcurl}" target="_blank">{$tempi.title}</a>
													</div>
												</td>
												<td align="left" width="120">
													{$tempi.dateline|time}
												</td>
												<td width="30" align="right" ><a href="##url(user/articledo/id:$tempi.aid)##">{lang edit}</a></td>
											</tr>
											{/loop}
											<tr>												
												<td colspan="3">
													<div class="fleft" style="margin-right:5px;">
													<input type="checkbox" onclick="checkAll(this)" />
													</div>
													<div class="abtnrw abtn fl">
													<button name="pgo" class="abtnlw" type="submit">{lang del}</button>
													</div>
												</td> 
											</tr>
											{if $htmlpage}
											<tr>
												<td colspan="3">
												{$htmlpage}
												</td>
											</tr>
											{/if}
										  </tbody></table>
										  </form>
										  {else}
										  		{lang no_data}
										  {/if}
								 {/if}
							    </div>
							    <div class="clear"></div>
							</div>
						
						</div>
				   
		           {/if}
		           		           
            </div>
            
		 	</div>
	 	</div>
	 	<div class="clear"></div>
	</div>
</div>
	
{htmltemplate footer}