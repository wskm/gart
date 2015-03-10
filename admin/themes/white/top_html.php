<?exit?>
<div id="head_top" >
	<div id="logo">Gart</div>
	<div class="leave"></div>
	<div class="top_a"> 
	Welcome <b style="">{UNAME}</b>&nbsp;|&nbsp;<a href="index.php?wskm=auth&act=logout">{lang logout}</a>&nbsp;|&nbsp;<a href="javascript:;" id="mainrefresh" >{lang refresh}</a> |  <a href="javascript:;" onclick="$('#main').attr('src','index.php?wskm=tool&act=updatecache')">{lang update_cache}</a>&nbsp;|&nbsp;<a href="../" target="_blank" >{lang index}</a>&nbsp;|&nbsp;<a href="http://www.wskms.com" target="_blank" >{lang about_gart}</a>
	</div>
	
</div>
<script type="text/javascript">
$(document).ready(function(){	
	$('#mainrefresh').click(function(){
	        $('#main').get(0).contentWindow.location.reload();
	}); 
}); 

</script>
<div style="background-color:#133C56;width:100%;height:26.5px;">

<div class="ddsmoothmenu" id="wtopmenu" >

  <ul id="topnav" >        
    {loop $navs $key $nav}
    {if $nav.isnav }
    <li><a href="{$nav.url}" id="top_{$nav.parent}" target="main" onclick="toggleNav('{$nav.parent}','{$nav.url}')" >{$nav.name}</a></li>

    {/if}
    {/loop}

  </ul>
</div>
</div>