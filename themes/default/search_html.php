<?exit?>
{htmltemplate header}

<div class="inner mt5 mb5"  >
	<div id="artnav" >
		<div class="nav iconround">
		<span><a href="{ART_URL}">{lang index}</a>&nbsp;>&nbsp;{lang search}</span>
		</div>	
	</div>   
</div>
   
<div id="artmain" > 
	<div id="searchwrap" >
		<div class="height20"></div>
		<div class="csearch">
				<form action="search.php" method="GET" >
					<input name="wk" type="text" value='{$wk}' class="tinput" maxlength="30" />
					<input type="submit" value="{lang search}" name="go" class="tbuttom" />
				</form>
		</div>
		<div class="height20"></div>
		<div id="searchtmain">
				{if $searchdata}
				<ul>
					{loop $searchdata $info}
					<li>
					<p class="atitle"><strong>{$info.cateurl}<a target="_blank" style="{$info.titlestyle}" href="{$info.mvcurl}">{$info.title}</a></strong><i class="idate">{$info.dateline|time}</i></p>
					<p class="ainfo">{$info.summary}</p>
					</li>
					{/loop}
				</ul>
				{elseif $wk}
				<div class="search_nodata">{lang search_notdata}</div>
				{/if}
		</div>
		<div>{$htmlpage}</div>
		<div class="height35"></div>
		<div class="clear"></div>
	</div>
</div>

{htmltemplate footer}    
