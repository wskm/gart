<?exit?>
{htmltemplate header}
<div id="rightwrap">
<div id="rtitle">
	<p id="titlenav">{lang article_top}&nbsp;&raquo;&nbsp;{lang article}</p>
</div>

<div id="rmain">
	<div class="cleara ml5"  >
		<a class="btnart " id="btn_addnews" href="index.php?wskm=article&act=add"><cite >{lang article_add}</cite></a>
		<ul class="main_nav fright">
			<li {if $isallnews}class="current"{/if} ><a href="index.php?wskm=article" ><span>{lang article_normal}</span></a></li>
			<li {if $skeys.status==2}class="current"{/if} ><a href="index.php?wskm=article&status=2" ><span>{lang article_verify}</span></a></li>
			<li {if $skeys.status==-1}class="current"{/if} ><a href="index.php?wskm=article&status=-1" ><span>{lang recycle}</span></a></li>			
			<li {if $skeys.digest}class="current"{/if} ><a href="index.php?wskm=article&digest=1" ><span>{lang article_digest}</span></a></li>
		</ul>
		<div class="clear"></div>
	</div> 
	<script type="text/javascript" >
		if($.browser.msie){
			$('#btn_addnews').addClass('fleft');
		}
	</script>
	
	<div class="clear"></div>
	<div class="divcommon">
        <form method="GET" style="color:#000" >  
            <input type="hidden" name="wskm" value="article" />
            <div class="fleft mb5">                
                {lang title}&nbsp;
                <input class="queryInput" type="text" name="title" value="{$skeys[title]}" />&nbsp;
                <select class="querySelect" id="cid" name="cid">
                <option value="0">{lang all_category}</option>
                {$select_options}
                </select>&nbsp;              
                <select class="querySelect" name="sorttype">
                <option value="DESC" {if $skeys.sorttype=='DESC' }selected="true"{/if} >{lang sort_desc}</option>
                <option value="ASC" {if $skeys.sorttype=='ASC' }selected="true"{/if}>{lang sort_asc}</option>
                </select>&nbsp;
                <input  type="submit" value=" {lang select} " name="filtergo"  />  
                
            </div>
          
        </form>
        <div class="clear"></div>
    </div>

  <form  method="POST" action="index.php?wskm=article&act=batch&articlelistgo=1" >
  <input type="hidden" name="arthash" value="{ART_HASH}" />
  <input type="hidden" name="acttype" id="acttype" value="bignews" />
  <input type="hidden" name="movecid" id="movecid" value="0" />
  <div id="actcp" >
  <!--{if $articles}-->
  <div class="toolbar_list">
            
        <div id="batchAction" >
        	<div class="chk"><input type="checkbox" onclick="checkAll(this)"  />&nbsp;{lang checkall}&nbsp;&nbsp;</div>
        	<div class="chk">
            <select name="selectacttype" onchange="$('#acttype').val(this.value);if(this.value=='category'){$('#selectmovecid').show();}else{$('#selectmovecid').hide();}" >
            	<option value="bignews" >{lang acttype_bignews}</option>
            	<option value="smallnews" >{lang acttype_smallnews}</option>            	
            	<option value="cyclepic" >{lang acttype_cyclepic}</option>
            	<option value="cyclepic_no" >{lang acttype_cyclepic_no}</option>            	
            	<option value="audit" >{lang acttype_audit}</option>   
            	<option value="normal" >{lang acttype_normal}</option>            	
            	<option value="top_yes" >{lang acttype_top_yes}</option>
            	<option value="top_no" >{lang acttype_top_no}</option>
            	<option value="digest_yes" >{lang acttype_digest_yes}</option>
            	<option value="digest_no" >{lang acttype_digest_no}</option>
            	<option value="del" >{lang acttype_del}</option>
            	<option value="completelydel" >{lang acttype_completelydel}</option>
            	<option value="category" >{lang acttype_category}</option>
            </select>  
             <select class="querySelect" id="selectmovecid" name="selectmovecid"  onchange="$('#movecid').val(this.value);" style="display:none">
                <option value="0">{lang select_pls}</option>
                {$select_options}
                </select>
            <input type="submit"  class="easybtn" value=" {lang submit} " name="articlelistgo" />            
            </div>   
            <div class="toolbar_rgiht" >
            	{$htmlpage}
            </div>
        </div>
    </div>      
    <!--{/if}-->      
    </div>
    <table width="100%" cellspacing="0" class="tblist tbmsutil" >
        <!--{if $articles}-->
        <thead>
        <tr>            
            <th class="first"  ><center>{lang title}</center></th>            
            <th width="60" align="left">{lang for_category}</th>
            <th width="20">ID</th>
            <th width="120">{lang add_time}</th> 
            <th width="40" align="left">{lang status}</th>            
            <th width="50" align="left">{lang creator}</th>
            <th  width="60" >{lang handle}</th>
        </tr>
        </thead>
     
        <!--{loop $articles $article}-->
        <tr class="tatr2">
            <td align="left" ><div style="width:380px;overflow:hidden" id="newstitle_{$article.aid}" ><input type="checkbox" name="selectid[]" class="select" value="{$article.aid}"/>&nbsp;<a href="{$article.mvcurl}" style="{$article.titlestyle}" target="_blank" >{$article.title|escape}</a>&nbsp;{if $article.cover}<img height="20" src="{ART_URL}{$article.cover}"/>&nbsp;{/if}{if $article.top}<img src="{ART_URL}images/common/ttop.gif" border="0" >&nbsp;{/if}{if $article.digest}&nbsp;<img src="{ART_URL}images/common/tstar.gif" border="0" >&nbsp;{/if}</div></td>
            <td>{$article.cname|escape}</td>
            <td align="center">{$article.aid}</td>
            <td>{$article.dateline|time}</td>
            <td>{php echo articleStatus($article.status) }</td>            
            <td>{$article.uname|escape}</td>
            <td><a href="index.php?wskm=article&amp;act=edit&amp;id={$article.aid}">{lang edit}</a>&nbsp;|&nbsp;<a href="javascript:goto_confirm('{lang drop_confirm}', 'index.php?wskm=article&amp;act=del&amp;id={$article.aid}');">{lang drop}</a></td>
        </tr>
         <!--{/loop}-->
        <!--{else}-->
        <tr >
            <td colspan="7" class="no_data" >{lang no_data}</td>
        </tr>
        <!--{/if}-->
    </table>
    <!--{if $articles}-->
 	<script type="text/javascript">
 		function goto_confirm(msg,turl){
 			if(confirm(msg)){
 				location.href=turl;
 			}
 		}
 	</script>
    <div id="actcp2"></div>
    <script type="text/javascript" >
    jQuery(function(){
    	table_hover('tbmsutil');
    	if(!$.browser.msie){
    		$('#btn_addnews_menu').css('width','68px');
    	}
    	
    	var cyclejs={$cyclejs};
    	$.each(cyclejs,function(i,n){
    		if($('#newstitle_'+n)){
    			$('#newstitle_'+n).append('<img src="{ART_URL}images/common/tcycle.gif" border="0" />');
    		}
    	});
    });

    $('#actcp2').html($('#actcp').html());
    </script>
    <div class="clear"></div>
    
    <!--{/if}-->
	</form>
</div>

</div>

{htmltemplate footer}