<?exit?>
{htmltemplate header}

<div class="inner mt5 mb5"  >
	<div id="artnav" >
		<div class="nav iconround">
		<span><a href="{ART_URL}">{lang index}</a>&nbsp;&gt;&nbsp;<a href="##htmlurl(announce)##" >{lang announce}</a>&nbsp;&gt;&nbsp;{$info.title}</span>
		</div>
	
	</div>   
</div>

<div id="artmain" >      
<!--<div class="inner"  >--> 
	<div id="leftmain" >
	 <div id="contentwrap" class="cwrap cwrap_bg1" >
	 <!--
		<div class="colorwrap" >   
				<div class="colora" id="artcolora" ></div>
				<div class="colorb" id="artcolorb" ></div>
				<div class="colorc" id="artcolorc" ></div>
		</div>		
		 -->       
		<div class="hd" >
			<h1>{$info.title}</h1>
			<div class="titleinfo">
				<div class="rinfo">
					<span>{$info.dateline|time}</span>&nbsp;&nbsp;<span>{$info.author}</span>
				</div>
					
			</div>
			 
		</div> 

		<div class="bd" >
			<div class="Line"></div>
		
			<div id="contentmain" >
			{$info.message}
			</div>
			 
		</div> 
		<div class="height60" ></div>
		
	 </div>
	 
	</div>
	<div class="rightside">
	{htmltemplate news_right}
 	</div> 	
 	
</div>

{htmltemplate footer}