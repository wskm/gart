<?exit?>
{htmltemplate header}

<div class="inner mt5 mb5"  >
	<div id="artnav" >
		<div class="nav iconround">
		<span><a href="{ART_URL}">{lang index}</a>&nbsp;>&nbsp;{$artnav}</span>
		</div>
		{htmltemplate right_search}
	</div>   
</div>
   
<div id="artmain" > 
	<div class="leftmain border_gray" id="leftmain" >
	<div class="height10" ></div>	
	{if $category_chlids}
		<div id="wrapmain" class="bd catelist">
			<ul>
				{loop $category_chlids $child}
				<li><a href="{$child.mvcurl}" >{$child.name}</a></li>
				{/loop}
			</ul>
		</div>
	{/if}
	{if $category.showtitletype && $category.titletypes}
		<div class="height5"></div>
		<div id="wrapmain" class="bd catelist">
			<ul>
				{loop $category.titletypes $temp}
				<li><a href="{$temp.url}" >{$temp.name}</a></li>
				{/loop}
			</ul>
		</div>
	{/if}
		<div id="listmain" class="bd " >
			<ul>
			
			{loopif $news $new}
				<li>
					<p class="atitle"><strong>{$new.typehtml}<a href="{$new.mvcurl}" style="{$new.titlestyle}" target="_blank">{$new.title}</a></strong><i class="idate">{$new.dateline|time:Y-m-d H:i}</i></p>
					<p class="ainfo">{$new.summary}</p>
				</li>	
			{loopelse}			
			<div class="pt15 pb15 ">{lang no_data}</div>
			{/loopelse}
			
			</ul>
		</div>
		<div class="bd">
			<div class="mb10">
			{$htmlpage}
			</div>
			<div class="clear"></div>
		</div>
		<div class="height60" ></div>
	</div>
	<div class="rightside">
		{if $category.cover}
		<div class="catecover">
			<a href="{ART_UPLOAD_URL}{$category.cover}" target="_blank"><img src="{ART_UPLOAD_URL}{$category.cover}" border="0" /></a>
		</div>
		{/if}
		{htmltemplate news_right}
	</div>
</div>

{htmltemplate footer}    
