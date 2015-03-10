<?exit?>
{htmltemplate header}
##CSS(index)##
##JS(index)##
<div id="artmain"  >
{wskm(index)} 
	<div class="home mt5">
	<div class="lwrap720" >
		<div class="lwrap320 mr10">
			<div class="slidebox">
			
				<div id="slidePic" >   
				{loop $wskm_index.cycles $tempi}
					<div class="contentdiv" >
						<div class="spic"><a target="_blank" 
	href="{$tempi.mvcurl}"><img width="318" height="200" src="{$tempi.cover}" 
	alt="{$tempi.title}"/></a></div>
						<div class="stitle">{$tempi.title}</div>
					</div> 
				{/loop}  
				</div>     
				                  
				<div id="paginate-slidePic" class="pagination">
				{loop $wskm_index.cycles $tempi}
					<a href="javascript:void(0)" class="toc"></a>
				{/loop} 
				</div>   
				
				<script type="text/javascript">			
				function showSlide(){
					featuredcontentslider.init({
						id: "slidePic",
						contentsource: ["inline", ""],
						toc: "#increment",
						nextprev: ["", ""],
						revealtype: "mouseover",
						enablefade: [false, 0.1],
						autorotate: [true, 2500],
						onChange: function(previndex, curindex){}
					})
				}
				{if $wskm_index.cycles}showSlide();{/if}
				</script>
				
			</div>
		
			<div class="mb5"></div>
			<div class="box" >
				<div class="titlei titleiD">
					<span class="mark">{lang hot_sort}</span>
				</div>
				<div class="contentdiv h235">
					<ul class="ulTxt">
						{loop $wskm_index.hots $tempi}
							<li><i class="iTitle"><a href="{$tempi.mvcurl}" >{$tempi.title}</a></i></li>
						{/loop}
					</ul> 
				</div>         
			</div>
		</div>
		
		<div class="center390" id='centerwrap'>
					
			<div class="FocusTxt">
				{if $wskm_index.bignews}
				<div class="topNewsWrap" >
					<div class="bigTitle mt10 red"><a href="{$wskm_index[bignews][mvcurl]}" target="_blank" >{$wskm_index[bignews][title]}</a> </div>
					{loop $wskm_index.smallnews $tempi}
						[<a href="{$tempi[mvcurl]}" class="smallTitle" target="_blank" >{$tempi[title]}</a>]&nbsp;
					{/loop}
				</div>
				{/if}
				<div class="txtTitle noneBg pt5">
					<ul class="ulTxt f14">					
					{loop $wskm_index.news $tempi}
						<li><i class="iTitle"><a href="{$tempi.mvcurl}" style="{$tempi.titlestyle}" >{$tempi.title}</a></i></li>
					{/loop}
					</ul>
				</div>
			</div>
		
		</div>
		<div class="clear"></div>
		
		<div class="mt10" >
					
			<div class="lwrap720">
				<div class="box h360" >
					<div class="titleiB">
						<span class="mark">{lang pic_sort}</span>
						<span class="subMark red"></span>
					</div>
					<div class="tbody">
						<ul class="ulPic hPic hPic155 mb5">
						{loop $wskm_index.pics $tempi}
							<li><i class="iPic"><a target="_blank" href="{$tempi.mvcurl}"><img width="160" height="100" src="{$tempi.cover}" alt="{$tempi.title}"/></a></i><i class="iTitle"><a href="{$tempi.mvcurl}" target="_blank">{$tempi.title}</a></i></li>
					    {/loop}
						</ul>
					</div>
					
				</div>
			</div>
			<div class="clear"></div>  
		</div>
		
	</div>
	<div class="rwrap220">
	
		<div class="mb5 indexsreach"> 
					<form action="{ART_URL}search.php" method="GET" target="_blank" >
						<input name="wk" type="text" value='' class="tinput" maxlength="30" />
						<div class="abtnrb abtn fr">
					<button type="submit" class="abtnlb" name="go" >{lang search}</button>
					</div>
					<div class="clear"></div>
					</form>
		</div>
		{if $wskm_index.announce}
		<div class="mt5 mb5 box" >  
			<div class="titlei titleiA">
				<span class="mark">{lang announce}</span>
			</div> 
			<div  style="padding:9px 0px 9px 8px;">  
				<ul class="ulTxt">  
					{loop $wskm_index.announce $tempi}
						<li><i class="iTitle" style="width:190px;" ><a href="{$tempi.mvcurl}" title="{$tempi.title}" target="_blank" >{$tempi.title}</a></i></li>
					{/loop}
				</ul>
				<div class="clear"></div>
			</div>
			
		</div>
		{/if}
		<div class="box"> 
			<div class="titleiB">
			<span class="mark">{lang best_sort}</span>
			</div>
			<div style="padding:9px 0px 9px 8px;" >
				<ul class="ulTxt">
					
				{loop $wskm_index.bests $tempi}
					<li><i class="iTitle"><a href="{$tempi.mvcurl}" >{$tempi.title}</a></i></li>
				{/loop}
				
				</ul>
				<div class="clear"></div>
			</div>
		</div>
		<div class="mt5 box" > 
			<div class="titlei titleiB">
				<span class="mark">{lang poll}</span>
			</div>
			{if $wskm_index.poll}
			<div>  
			{$wskm_index.poll}
			</div>
			{else}
			<div class="tbody">  
			</div>
			{/if}
			<div class="clear"></div>
		</div>		
	
	</div>	
	
	<div class="clear"></div>
	</div>
		
</div>

<div class="inner" >
	
	<div class="friendlink " >	
		<ul>			
			<li class="fb">{lang friend_sort}:</li>	
			{if $flinktype}
				{loop $friendlink $temp}
				<li><a href="http://{$temp.url}"  target="_blank" ><img src="{ART_URL}attachments/logo/{$temp.logo}" width="100" height="25" border="0" />{$temp.name}</a></li>
				{/loop}
			{else}
				{loop $friendlink $temp}
				<li><a href="http://{$temp.url}"  target="_blank" >{$temp.name}</a></li>
				{/loop}
			{/if}
			
		</ul> 
	</div>
	<div class="clear"></div>  
</div>

{htmltemplate footer}