<?exit?>
{htmltemplate header}
<div id="rightwrap">
<div id="rtitle">
	<p id="titlenav">{lang setting}&nbsp;&raquo;&nbsp;{lang base_setting}</p>
</div>
<div id="rmain" >

	<div class="ml5" >
		<ul class="main_nav">
			<li {if $type=='base'}class="current"{/if} ><a href="index.php?wskm=setting&type=base" ><span>{lang base_setting}</span></a></li>
			<li {if $type=='reg'}class="current"{/if} ><a href="index.php?wskm=setting&type=reg"  ><span>{lang reg_setting}</span></a></li>
			<li {if $type=='login'}class="current"{/if} ><a href="index.php?wskm=setting&type=login"  ><span>{lang login_setting}</span></a></li>
			<li {if $type=='attach'}class="current"{/if} ><a href="index.php?wskm=setting&type=attach"  ><span>{lang attach_setting}</span></a></li>
			<li {if $type=='img'}class="current"{/if} ><a href="index.php?wskm=setting&type=img"  ><span>{lang pic_setting}</span></a></li>
			<li {if $type=='article'}class="current"{/if} ><a href="index.php?wskm=setting&type=article"  ><span>{lang article_setting}</span></a></li>
			<li {if $type=='mail'}class="current"{/if} ><a href="index.php?wskm=setting&type=mail"  ><span>{lang mail_setting}</span></a></li>
			<li {if $type=='style'}class="current"{/if} ><a href="index.php?wskm=setting&type=style"  ><span>{lang style_setting}</span></a></li>
		</ul>
		
	</div>	 
	<div class="clear"></div>

  <form method="post" enctype="multipart/form-data" action="?wskm=setting&type={$type}">
  <input type="hidden" name="arthash" value="{ART_HASH}" />
	  <div class="divcommon"  > 
	  <table class="tb_article tbms1" width="100%">
		 {loop $setting $key $value}
		 {php $setname='setting['.$key.']'; }
		 <tr>
		  	<td width="130" valign="top" >
		  	<span class="editatitle">{php echo lang('setting_'.$key); }</span>
		  	</td>
			  <td>			  
			  {if in_array($key ,array('webStatus' , 'isRules','isHtml' , 'isEmailValid' , 'isWaterMark' ,  'regVerify' ,  'articleUrlAbsolute' ,  'commentStatus' ,  'attachValidPoint' , 'attachValidUser' , 'attachValidReferer' , 'attachIntermittentDownload' ,  'isGzip' ,  'popBgShow' ,  'isSwitchTheme' ,  'isHtml', 'isNewsKiss' ,  'isVcode' , 'pollLevel' ,'isEmailVerify' ,'isAllowReg' ,'sendEmailVerify')) }
			  	{php echo creatHtml('radio',$setname,$value); }
			  {elseif $key == 'webCloseReason' || $key == 'timeFormats' || $key == 'unameProtect' || $key == 'regRulesText' || $key=='emailProtect' || $key=='pageFooter'}
			  	{php echo creatHtml('textarea',$setname,$value,'style="width:500px;height:100px;padding:2px 2px;"'); }
			  {elseif $key =='waterMarkType' }
			  	{php echo creatHtml('radio',$setname,$value,'',array(0=>'gif',1=>'png')); }
			  {elseif $key =='waterMarkPosition' }
			  <select name="setting[waterMarkPosition]" id="waterMarkPosition">{$imgposition_option}</select>
			  {elseif $key == 'groupId' }
			  	<select name="setting[groupId]" id="groupId">{$usergroup_option}</select>
			  {elseif $key == 'regGroupId'}
			  	<select name="setting[regGroupId]" id="regGroupId">{$usergroup_option}</select>
			  {elseif $key == 'timeZone'}
			  	<select name="setting[timeZone]" id="time_zone">{$timezone_option}</select>
			  {elseif $key == 'friendLinkType'}
			  	{php echo creatHtml('radio',$setname,$value,'',array(0=>lang("text"),1=>lang("img"))); }
			  {elseif $key == 'articleStatus'}
			  	{php echo creatHtml('radio',$setname,$value,'',array(0=>lang("close"),1=>lang("normal"),2=>lang("verify")) ); }
			  {elseif $key == 'articleReplyState'}
			  	{php echo creatHtml('radio',$setname,$value,'',array(0=>lang("replay_no"),1=>lang("replay_member"),2=>lang("replay_anonym")) ); }
			  {elseif $key == 'articlePageType'}
			  	{php echo creatHtml('radio',$setname,$value,'',array(0=>lang("pagetype_js"),1=>lang("pagetype_web")) ); }
			  {elseif $key == 'urlMode'}
			  	{php echo creatHtml('radio',$setname,$value,'',array('URLMODE_NONE'=>lang("URLMODE_NONE"),'URLMODE_SIGN'=>lang("URLMODE_SIGN"),'URLMODE_PATH'=>lang("URLMODE_PATH"),'URLMODE_REWR'=>lang("URLMODE_REWR")) ); }			  	
			  {elseif $key == 'emailType'}
			  	{php echo creatHtml('radio',$setname,$value,'',array(0=>lang("sendmail_sys"),1=>lang("sendmail_smtp")) ); }			  	
			  {elseif $key == 'emailDelimiter'}
			  	{php echo creatHtml('radio',$setname,$value,'',array(0=>lang("delimiter_0"),1=>lang("delimiter_1"),2=>lang("delimiter_2")) ); }
			  {elseif $key == 'language'}
			  	{php echo creatHtml('select',$setname,$value,'',$weblangs ); }
              {elseif $key == 'commentVote'}
			  	{php echo creatHtml('radio',$setname,$value,'',array(0=>lang("vote_none"),1=>lang("vote_user")) ); }
			  {elseif $key == 'editor'}
			  	{php echo creatHtml('radio',$setname,$value,'',array('ckeditor'=>'ckeditor','xheditor'=>'xheditor') ); }
			  {elseif $key == 'popBgColor'}
			    {php echo creatHtml('text',$setname,$value,'style="width:250px;padding:2px 2px;" class="color" '); }
			  {elseif $key == 'emailPassword' }
				{php echo creatHtml('password',$setname,$value,'style="width:250px;padding:2px 2px;"'); }			  
			  {else}
			  	{php echo creatHtml('text',$setname,$value,'style="width:250px;padding:2px 2px;"'); }
			  {/if}
			  </td>
		  </tr>
		  {/loop}
		   
		  {if $type=='mail'}
		  <tr>
		  	<td width="130" valign="top" ></td>
		  	<td>
		  	<input id="testmail_to" type="text" size="150" maxlength="100" value="{lang mail_to}" onfocus="if(firsttest){this.value=''; firsttest=false;}" style="width:150px;padding:2px 2px;" />&nbsp;&nbsp;<a class="btnart " style="margin-top:-6px;" id="btn_addnews" href="javascript:void(0);" onclick="sendtest();" ><cite >{lang mail_test}</cite></a>&nbsp;<span id="testmail_msg" style="color:red;"></span>
		  	<script type="text/javascript">
		  	var firsttest=true;
		  	function sendtest(){
		  		var mailto=$('#testmail_to');
		  		if(!isemail(mailto.val())){
		  			alert('{lang email_format}');
		  			return;
		  		}
		  		
		  		$('#testmail_msg').html('Load...');
		  		$.get('index.php?act=testmail',{'mailto':mailto.val()},function(s){
		  			if(s==1){
		  				$('#testmail_msg').html('{lang mail_send_succeed}');
		  			}else{
		  				$('#testmail_msg').html('{lang mail_send_failure}');
		  			}
		  		});
		  	}
		  	</script>
		  	</td>
		  </tr>
		   <tr>
		  	<td width="130" valign="top" ></td>
		  	<td>
		  	<span style="color:gray">{lang mail_test_notice}</span>
		  	</td>
		  </tr>
		  {/if}
		  <tr>
			  <td width="130" valign="top" ></td>
			  <td>
	          <div class="btncright mt5">
	            <input class="btncommon" type="submit" value="{lang save}" name="settingsgos"  />   
	            </div>
	             <div class="btncright mt5">
	            <input class="btncommon" type="reset" value="{lang reset}" name="ressetz"  />   
	            </div>
	          </td>
		  </tr>
		 
	  </table>
	  </div>
   </form>
 
<script type="text/javascript" src="{ART_URL}includes/js/jscolor.js"></script>
<script type="text/javascript" >
table_evenbg('tbms1');
</script>
</div>
{htmltemplate footer}