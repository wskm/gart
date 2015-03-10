<?exit?>
{htmltemplate header}
<div id="rightwrap">
<div id="rtitle">
	<p id="titlenav">{lang setting}&nbsp;&raquo;&nbsp;{lang theme}</p>
</div>

<div id="rmain" >
	<div class="ml5" >
		<ul class="main_nav">
			<li class="current" id="theme_1" ><a href="javascript:;" onclick="toggleTab('theme',1,2)" ><span>{lang theme_webtheme}</span></a></li>
			<li id="theme_2" ><a href="javascript:;"  onclick="toggleTab('theme',2,2)" ><span>{lang theme_admintheme}</span></a></li>
		</ul>
		
	</div>	 
	<div class="clear"></div>
	
	<div  id="theme_tab_1">
		<div id="notice">
		{lang theme_notice1}
		</div>
		<form method="POST" action="" >
		<input type="hidden" name="arthash" value="{ART_HASH}" />
		<div class="themelist" >
		<ul> 
		{loop $themes.themes $theme}
			<li {if $theme.isdefault }class="selected"{/if} >		
				<span>{$theme.title}</span><br>
				<a target="_blank" href="{$theme.previewurl}"><img width="120" height="130" src="{$theme.pic}" border="0" /></a>
				<br>
				<input type="radio" value="{$theme.styleid}"  {if $theme.isdefault }checked="true"{/if} name="defaultsid" />{lang default}&nbsp;<a href="index.php?wskm=theme&act=edit&styleid={$theme.styleid}">{lang edit}</a>&nbsp;{if !$theme.notdisable }<a href="index.php?wskm=theme&act=uninstall&styleid={$theme.styleid}">{lang uninstall}</a>{/if}
			</li> 
		{/loop}
		
		{loop $themes.installs $theme}
			<li>		
				<span>{$theme.name}</span><br>
				<img width="120" height="130" src="{$theme.pic}" border="0" />
				<div style="padding:5px 0" >
				<a href="index.php?wskm=theme&act=install&style={$theme.name}">{lang install}</a>
				</div>
			</li> 
		{/loop}
		</ul>
		</div>
		<div class="clear"></div>
		<div style="float:left;width:15px;">&nbsp;</div>
				<div class="btncright">
	            <input class="btncommon" type="submit" value="{lang submit}" name="id" uri="index.php?app=article&act=drop" presubmit="confirm('{lang drop_confirm}');" />            
	           </div>
	          
		
		</form>
	
	</div>
	
	<div id="theme_tab_2" style="display:none">
		<div id="notice">
		{lang theme_notice2}
		</div>
		<form method="POST" action="" >
		<input type="hidden" name="arthash" value="{ART_HASH}" />
		<div class="themelist" >
		<ul>
		{loop $adminthemes.themes $theme}
			<li {if $theme.isdefault }class="selected"{/if} >		
				<span>{$theme.title}</span><br>
				<a target="_blank" href="{$theme.previewurl}"><img width="120" height="130" src="{$theme.pic}" border="0" /></a>
				<br>
				<input type="radio" value="{$theme.styleid}"  {if $theme.isdefault }checked="true"{/if} name="defaultadminsid" />{lang default}&nbsp;<a href="index.php?wskm=theme&act=edit&styleid={$theme.styleid}">{lang edit}</a>&nbsp;{if !$theme.notdisable }<a href="index.php?wskm=theme&act=uninstall&styleid={$theme.styleid}">{lang uninstall}</a>{/if}
			</li> 
		{/loop}
		{loop $adminthemes.installs $theme}
			<li>		
				<span>{$theme.name}</span><br>
				<img width="120" height="130" src="{$theme.pic}" border="0" />
				<div style="padding:5px 0" >
				<a href="index.php?wskm=theme&act=install&style={$theme.name}&styletype=1">{lang install}</a>
				</div>
			</li> 
		{/loop}
		
		</ul>
		</div>
		<div class="clear"></div>
	
		<div style="float:left;width:15px;">&nbsp;</div>
				<div class="btncright ">
	            <input class="btncommon" type="submit" value="{lang submit}" name="ad" uri="index.php?app=article&act=drop" presubmit="confirm('{lang drop_confirm}');" />            
	           </div>
	           <div class="clear"></div>
	
		</form>
	</div>
	<div class="clear"></div>
	<br>
	
</div>
{htmltemplate footer}