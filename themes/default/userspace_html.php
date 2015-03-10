<?exit?>
{htmltemplate header}

<div id="artmain">
	<div class=" border_gray mainbg_1">
	
<div class="artpopct  ">
	<h2>{lang user_space}</h2>
	<div class="profile  " id="wkcen">
		<div class="photo">							
								<div id="photoshow">
								<div class="height10"></div>
								{echo getUserPhoto($uid,'m') }
								</div>
								</div>
						  	    <div class="icontents" style="width:750px">
						  	      <table cellspacing="0" cellpadding="0" border="0"  width="100%">
						  	      	<tbody><tr>
						  	      	  <td class="name">{lang username}</td>
									  <td>{$profile.uname}</td>
						  	      	</tr>
						  	      	{if $profile.showemail}
									<tr>
						  	      	  <td class="name">{lang email}</td>
									  <td>{$profile.email}</td>
						  	      	</tr>
						  	      	{/if}
						  	      	{if ADMINID > 0}
						  	      	<tr>
						  	      	  <td class="name">IP</td>
									  <td>{$profile.lastip}</td>
						  	      	</tr>
						  	      	{/if}
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
						  	      	  <td class="name">{lang replycount}</td>
									  <td>{$profile.replycount}</td>
						  	      	</tr>		
						  	      	<tr>
						  	      	  <td class="name">{lang regtime}</td>
									  <td>{$profile.createtime|time}</td>
						  	      	</tr>
						  	      	{if $profile.lastreplytime}				  	      	
									<tr>
						  	      	  <td class="name">{lang lastreplytime}</td>
									  <td>{$profile.lastreplytime|time}</td>
						  	      	</tr>		
						  	      	{/if}
						  	      	<tr>
						  	      	  <td class="name">{lang lastlogintime}</td>
									  <td>{$profile.lastvisit|time}</td>
						  	      	</tr>	
								  </tbody></table>
							    </div>
								<div class="clear"></div>
	</div>

	
	</div>
</div>
	
{htmltemplate footer}