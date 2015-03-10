<?exit?>
{htmltemplate header}

<div class="inner mt5 mb5"  >
	<div id="artnav" >
		<div class="nav iconround">
		<span><a href="{ART_URL}">{lang index}</a>&nbsp;>&nbsp;{lang announce}</span>
		</div>
		{htmltemplate right_search}
	</div>   
</div>
   
<div id="artmain" > 
	<div class="leftmain border_gray" id="leftmain" >
	<div class="height10" ></div>	
		<div id="listmain" class="bd " >
			<ul>
			
			{loopif $list $tempi}
				<li style="padding:5px 0 5px;" >
					<p class="atitle"><strong><a href="{$tempi.mvcurl}" target="_blank">{$tempi.title}</a></strong><i class="idate">{$tempi.dateline|time}</i></p>
				</li>	
			{loopelse}			
			<div class="pt15 pb15 ">{lang no_data}</div>
			{/loopelse}
			</ul>
		</div> 
		<div class="height60" ></div>
	</div> 
	<div class="rightside">		
		{htmltemplate news_right}
	</div>
</div>

{htmltemplate footer}    
