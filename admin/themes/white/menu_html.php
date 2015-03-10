<?exit?>
<div class="leftmenu" id="leftmenu">
	<div class="menu_title" style="display:none"><h3>Gart</h3></div>
	<div class="navdiv">
		{loop $menus $index $menuchild}
			<ul id="menu_{$index}" style="display:none"  >
				{loop $menuchild.children $ichind $menu}
				<li>
				<a target="main" href="{$menu.url}" id="childnav_{$ichind}"  hidefocus="true" onclick="setmenu('$index',this.id)"   >{$menu.name}</a>
				</li>	
				{/loop}
			</ul>
		{/loop}
	</div>
	
	</div>
</div>
<script type="text/javascript" >

function setmenu(pr,id)
{
	var hrefs = dom('menu_' + pr).getElementsByTagName('a');
	for(var j = 0; j < hrefs.length; j++) {			
			hrefs[j].className =  '';
	}
	dom(id).className = 'tabset';
	return false;
}

</script>