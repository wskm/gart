<?exit?>
{htmltemplate header}

<div class="inner mt5 mb5"  >
	<div id="artnav" >
		<div class="nav iconround">
		<span><a href="{ART_URL}">{lang index}</a>&nbsp;>&nbsp;TAG:{$tagname}</span>
		</div>
		{htmltemplate right_search}
	</div>   
</div>
   
<div id="artmain" > 
	<div class="leftmain border_gray" id="leftmain" >
		<div class="height10" ></div>
		<div id="listmain" class="bd " >
			<ul>
			{loopif $articles $new}
				<li>
					<p class="atitle"><strong><a href="{$new.mvcurl}" target="_blank">{$new.title}</a></strong><i class="idate">{$new.dateline|time:Y-m-d H:i}</i></p>
					<p class="ainfo">{$new.summary}</p>
				</li>	
			{loopelse}
			{lang no_data}
			{/loopelse}
			</ul>
		</div>
		<div class="bd">
			<div class="mb10">
			{$htmlpage}
			</div>
			<div class="clear"></div>
		</div>
		<div class="height35" ></div>
	</div>
	<div class="rightside">
		{htmltemplate news_right}
	</div>
</div>

{htmltemplate footer}    
