<?exit?>
{htmltemplate header}
<script type="text/javascript" src="{ART_URL}includes/js/plugin/jeditable.js"></script>
<div id="rightwrap">
<div id="rtitle">
	<p id="titlenav">{lang category}&nbsp;&raquo;&nbsp;{lang cate_manage}</p>
</div>
<div id="rmain" >

	<div class="ml5" >
			<a class="btnart" id="addcate" href="index.php?wskm=category&amp;act=add" ><cite >{lang cate_add}</cite></a>&nbsp;
			<a class="btnart" id="addcate" href="index.php?wskm=category&amp;act=updatecache" ><cite >{lang update_cache}</cite></a>
			
	</div>
	<div class="mt10"></div>
 <form  method="POST" action="" >
 <input type="hidden" name="arthash" value="{ART_HASH}" />
	    <table  class="tblist tbmsutil" id="catelist" >
        <!--{if $categorys}-->
        <thead>
        <tr>
            <th class="first"  class="w25"><input id="checkall_1" type="checkbox" class="checkall" onclick="checkAll(this)" /></th>
            <th >{lang cate_name}</th>
            <th width="35">{lang sort_order}</th>
            <th width="70">{lang isnav}</th>
            <th width="110">{lang handler}</th>
        </tr>
        </thead>
        <!--{/if}-->
        
        <tbody>
        <!--{loopif $categorys $category}-->  
        <tr class="{$category.layer}">
            <td  class="w25" ><input type="checkbox" class="select" name="selects[]" value="{$category.cid}" /></td>
            <td style="padding-left:{$category.layer}em" valign="middle">{if $category.childcount}<a href="javascript:;" onclick="rowToggle(this)" class="treeiclo"></a>{else}<a href="javascript:;" class="treeiitem"></a>{/if}<span class="node_name jedit_name" title="{lang editable}" id="can{$category.cid}" >{$category.name|escape}</span></td>
            <td align="center"><span class="jedit_sort" id="cas{$category.cid}" title="{lang editable}">{$category.displaysort}</span></td>
            <td align="center" ><!--{if $category.isnav}--><img src="{ATHEME_IMG}ienabled.gif" class="jedit_ispic"  title="{lang editable}"/><!--{else}--><img src="{ATHEME_IMG}idisabled.gif" class="jedit_ispic" title="{lang editable}"/><!--{/if}--></td>
            <td class="handler">
            <span><a href="index.php?wskm=category&amp;act=add&amp;id={$category.cid}">{lang add}</a>&nbsp;|&nbsp;<a href="index.php?wskm=category&amp;act=edit&amp;id={$category.cid}">{lang edit}</a>&nbsp;|&nbsp;<a href="javascript:if(confirm('{lang cate_delconfirm}'))window.location = 'index.php?wskm=category&amp;act=drop&amp;id={$category.cid}';">{lang drop}</a></span>
                </td>
        </tr>
        <!--{loopelse}-->
        <tr class="no_data">
            <td colspan="5">{lang no_data}</td>
        </tr>  
        <!--{/loopelse}--> 
        </tbody>
        <tfoot>
            <tr class="tr_pt10">  
            <!--{if $category} -->
                <td colspan="5" class="align_center">
                	<div class="fleft mt5">
                		<input id="checkall_2"  type="checkbox" class="checkall" onclick="checkAll(this)">&nbsp;{lang checkall}&nbsp;&nbsp;
					</div>	
                	<div class="btncright mt5">
						<input class="btncommon" type="submit" value="{lang drop}"  />            
					</div>
                </td>
            <!--{/if}-->
            </tr>
        </tfoot>
    </table>
    
</form>

<script type="text/javascript">
jQuery(function(){
	table_hover('tbmsutil');

	$(".jedit_name").editable("index.php?wskm=category&act=ajaxname", {
		height:'20px',
		width:'200px'
	});
	
	$(".jedit_sort").editable("index.php?wskm=category&act=ajaxsort", {
		height:'20px',
		width:'30px'
	});
});

function rowToggle(obj)
{
	obj = obj.parentNode.parentNode;
	var caterows= document.getElementById("catelist");
	var parentlayer = parseInt(obj.className);
	var isbreak = false;
	var rowlength=caterows.rows.length;
	for (i = 0; i < rowlength; i++)
	{
		var row = caterows.rows[i];
		if (caterows.rows[i] == obj){
			isbreak = true;
		}
		else if (isbreak == true){
			var nextlayer = parseInt(row.className);
			if (nextlayer > parentlayer)
			{
				if(obj.cells[1].childNodes[0].className == 'treeiclo'){
					row.style.display ='none';
				}else{
					row.style.display = (row.style.display != 'none') ? 'none' : ($.browser.msie ) ? 'block' : 'table-row';
				}
				if(row.cells[1].childNodes[0].className=='treeiexp')row.cells[1].childNodes[0].className ='treeiclo';
			}
			else
			{
				isbreak = false;
				break;
			}
		}
	}

	for (i = 0; i < obj.cells[1].childNodes.length; i++)
	{
		var imgObj = obj.cells[1].childNodes[i];
		if (imgObj.tagName == "A")
		{
			imgObj.className = imgObj.className =='treeiexp'?'treeiclo':'treeiexp';
		}
	}
}

</script>
</div>



{htmltemplate footer}