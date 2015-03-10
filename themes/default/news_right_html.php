<?exit?>
		{wskm(right)}
		<div class="box linews">
			<div class="bHead "><strong>{lang nownew_sort}</strong></div>
			<ul>
				{loop $wskm_right.news $tempi}
					<li>·<a href="{$tempi.mvcurl}" >{$tempi.title}</a></li>
				{/loop}
			</ul>
			
		</div>
		
		{if $wskm_right.weekhots}
		<div class="box mt5 linews">
			<div class="bHead "><strong>{lang hotweek_sort}</strong></div>
			<ul >
				{loop $wskm_right.weekhots $tempi}
					<li>·<a href="{$tempi.mvcurl}" >{$tempi.title}</a></li>
				{/loop}
			</ul>
		</div>
		{/if}
		
		{if $wskm_right.tags}
		<div class="tagwrap box mt5">
			<div class="bHead "><strong>TAG</strong></div>
			<div class="tagmain">
				{loop $wskm_right.tags $tempi}
				<a class="tagskin{$tempi.classkey}" href="{$tempi.mvcurl}"  target="_blank" >{$tempi.name}</a>
				{/loop}
			</div>
			<div class="clear"></div>
		</div>
		{/if}