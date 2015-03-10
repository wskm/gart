<?exit?>
{htmltemplate header}
<div id="rightwrap">
	<div id="rtitle">
		<p id="titlenav">{lang user_manage}&nbsp;&raquo;&nbsp;{if $action=='edit'}{lang user_edit}{else}{lang user_add}{/if}</p>
	</div>
	<div id="rmain" >
		<div class="clear"></div>

		<form method="post" action="?wskm=user&act={$action}" name="userform" >
		<input type="hidden" name="arthash" value="{ART_HASH}" />
		<input type="hidden" name="uid" value="{$uid}" />
		  <div class="divcommon"  > 
	  	  <table class="tb_article tbms1"  width="100%" id="cateset_tab_1">
	  	  {if $action =='edit'}
	  	  <tr>
	  	  	<td colspan="2" >
	  	  	{echo getUserPhoto($uid,'m') }
	  	  	<br><input type="checkbox" name="delphoto" value="1" >{lang photo_del}
	  	  	</td>
	  	  </tr>
	  	  {/if}
	  	   <tr>
			  	<td width="90">
			  	<span class="editatitle">{lang username}:</span>
			  	</td>
			  	<td>
			  	{if $action=='add' }
			  	<input type="text" name="uname" id="uname" maxlength="15" class="editinput" value="" />
			  	{else}
			  	<b>{$info.uname}</b>
			  	{/if}
			  	</td> 
		   </tr>		    
		    <tr>
			  	<td >
			  	<span class="editatitle">Email:</span>
			  	</td>
			  	<td>
			  	<input type="text" name="email" id="email" maxlength="50" class="editinput" value="{$info.email}" />
			  	</td> 
		   </tr>
		    <tr>
			  	<td >
			  	<span class="editatitle">{lang pw_new}:</span>
			  	</td>
			  	<td>
			  	<input type="password" name="newpw" id="newpw" maxlength="32" class="editinput" value="" />
			  	</td> 
		   </tr>
		     <tr>
			  	<td>
			  	<span class="editatitle">{lang usergroup_name}:</span>
			  	</td>
			  	<td>
			  		<select name="groupid" id="groupid" >
			  		{loop $groups $group}
			  		<option value="{$group.groupid}" {if $group.groupid== $info.groupid }selected="true"{/if}  >{$group.groupname}</option>
			  		{/loop}
			  		</select>
			  	</td> 
		   </tr>
		   <tr>
			  	<td>
			  	<span class="editatitle">{lang sex}:</span>
			  	</td>
			  	<td>
			  	<input type="radio" name="sex" value="1" {if $info.sex}checked='true'{/if} />{lang female}&nbsp;<input type="radio" name="status" value="0"  {if !$info.sex}checked='true'{/if} />{lang male}
			  	</td> 
		   </tr>
		    <tr>
			  	<td >
			  	<span class="editatitle">{lang birthday}:</span>
			  	</td>
			  	<td>
			  	<input type="text" name="birthday" id="birthday" maxlength="50" class="editinput" value="{$info.birthday}" />
			  			<link rel="stylesheet" type="text/css" href="{ART_INC_URL}tigra_calendar/calendar.css" />
						 <script type="text/javascript">
						 	var calendar_imgpath=SITE_URL+'includes/tigra_calendar/img/';
						 	var calendar_lang='{LANGUAGE}';
						 </script>
						 <script type="text/javascript" src="{ART_INC_URL}tigra_calendar/calendar.js" ></script>
						 <script language="JavaScript">			  
								  new tcal ({
								  	'formname': 'userform',
								  	'controlname': 'birthday'
								  });
						</script>
			  	</td> 
		   </tr>
		   <tr>
			  	<td >
			  	<span class="editatitle">{lang timezone}:</span>
			  	</td>
			  	<td>
			  	<select name="timeoffset" id="timeoffset" >			  	
			  	<option value="99" >{lang default}</option>
			  	{$timezone_option}
			    </select>
			  	</td> 
		   </tr>
		      <tr>
			  	<td >
			  	<span class="editatitle">{lang timezone_format}:</span>
			  	</td>
			  	<td>
			  	<select name="timeformat" id="timeformat" >
			  	{loop $timeFormats $key $format}
			  		<option value="{$key}" {if $key == $info.timeformat}selected="true"{/if} >{$format}</option>
			  	{/loop}
			    </select>
			  	</td> 
		   </tr>
		       <tr>
			  	<td >
			  	<span class="editatitle">{lang showemail}:</span>
			  	</td>
			  	<td>
			  	<input type="radio" name="showemail" value="1" {if $info.showemail}checked='true'{/if} />{lang yes}&nbsp;<input type="radio" name="showemail" value="0"  {if !$info.showemail}checked='true'{/if} />{lang no}
			  	</td> 
		   </tr>
		       <tr>
			  	<td >
			  	<span class="editatitle">{lang acceptemail}:</span>
			  	</td>
			  	<td>
			  	<input type="radio" name="sendemail" value="1" {if $info.sendemail}checked='true'{/if} />{lang yes}&nbsp;<input type="radio" name="sendemail" value="0"  {if !$info.sendemail}checked='true'{/if} />{lang no}
			  	</td> 
		   </tr>
		   
		   <tr class="sp"><td colspan="2"></td></tr>
	  	  </table>

	  	  
	  	  <table width="100%" >
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
</div>


</div>

<script type="text/javascript">
table_evenbg('tbms1',1);
</script>
{htmltemplate footer}