<?exit?>
{htmltemplate header}

<div id="rightwrap">
	<div id="rtitle">
	<p id="titlenav">{lang welcome_index}</p>
	</div>
	<div id="rmain">
	<h3 class="msg1">&nbsp;{lang welcome_gart}{if $lastlogintime}&nbsp;{lang lastlogintime}{$lastlogintime|time}{lang welcome_ip}{$lastip}{/if}</h3>
	<div id="updatemsg"></div>
	
	<table class="tdc_a tdc" >
		<tr>
			<th colspan="2" >{lang online_members}</th>
		</tr>
		<tr>
			<td colspan="2" >
			{loop $adminlist $tempi}
			{$tempi.uname}&nbsp;&nbsp;
			{/loop}
			</td>
		</tr>
	</table>
	<table class="tdc_a tdc" >
		<tr>
			<th colspan="2" >{lang sys_info}</th>
		</tr>
		<tr>
			<td class="td120">Gart {lang version}</td> 
			<td>{ART_VER} ({ART_RELEASE})</td>
		</tr>
		<tr>
			<td class="td120">{lang web_charset}</td> 
			<td>{PAGE_CHARSET}</td>
		</tr>
		<tr> 
			<td class="td120">{lang web_soft}</td>
			<td>{$server_software}</td> 
		</tr>
		<tr>
			<td class="td120">PHP {lang version}</td> 
			<td>{PHP_VERSION}</td>
		</tr>
		<tr>
			<td class="td120">{lang mysql_version}</td>
			<td>{$mysql_version}</td> 
		</tr>
		<tr>
			<td class="td120">{lang db_size}</td>
			<td>{$dbSize}</td> 
		</tr>
		<tr>
			<td class="td120">{lang upload_max}</td>
			<td>{$upload_maxsize}</td> 
		</tr>
	</table>
		
	<table class="tdc_a tdc" >
		<tr>
			<th colspan="2" >{lang gart_team}</th>
		</tr>
		<tr>
			<td class="td120">{lang gart_rights}</td>
			<td>Wskm Studio</td>
		</tr>
		<tr>
			<td class="td120">{lang gart_team}</td>
			<td>Wskm Team</td>
		</tr>
		<tr>
			<td class="td120">{lang offical_site}</td>
			<td><a href="http://www.wskms.com" target="_blank" >www.wskms.com</a></td>
		</tr>
		
	</table>
	
	</div>			
</div>
{htmltemplate footer}