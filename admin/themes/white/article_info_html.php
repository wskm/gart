<?exit?>
{htmltemplate header}
<script type="text/javascript">
//<![CDATA[
var charset = '{PAGE_CHARSET}', cookiedomain = '{COOKIEDOMAIN}', cookiepath = '{COOKIEPATH}', isattack = '0',isrequestnew='0', WEB_IMG = '{ART_URL}images/common/',WEB_URL=SITE_URL;
//]]>
</script>

<div id="rightwrap">
<div id="rtitle">
	<p id="titlenav">{lang article_top}&nbsp;&raquo;&nbsp;{$acttitle}</p>
</div>
<div id="rmain">
	<div class="ml5"> 
	
	<a class="btnart" href="index.php?wskm=article"><cite >{lang article_list}</cite></a>
	{if $wskmaction != 'add' }
	<a class="btnart" href="index.php?wskm=article&act=add" id="btn_addnews" ><cite >{lang article_add}</cite></a>
	{/if}
	</div> 
	<div  class="clear"></div>
	<div id="thumbupdiv" class="testdiv"  style="display:none;padding:2px 2px 2px 2px;"><form  enctype="multipart/form-data" action="index.php?wskm=upload&act=articlecover&thumbfpost=1" method="POST"  name="thumbform" id="thumbform" ><input type="hidden" name="cid" value="{$cid}" /><input type="hidden" name="aid" value="{$aid}" /><input type="hidden" name="arthash" value="{ART_HASH}" /><input type="file" id="thumbfileupload" style="width:200px;" name="thumbupload[]">&nbsp;&nbsp;<input type="button" class="btnnone" value="{lang upload}" onclick="thumbform_post()" name="thumbuploadsum" />&nbsp;<input type="button" class="btnnone" value="{lang cancel}" onclick="jQuery('#thumbupdiv').hide();" /> </form></div>
	<form method="POST"  enctype="multipart/form-data" action="{ART_URL_FULL}wskm.php?act=swfupload" >
	<div id="flashuploadwrap" class="lookdiv_main" style="display:none;" >
	       		<div class="lookdiv_title" ><i style="float:left">&nbsp;<span id="flashuploadli"></span></i><i style="float:right"><a href="javascript:;" onclick="flashuploadshow_close()" style="color:#A71616" >{lang close}</a>&nbsp;</i></div>
	       		<div id="flashuploadct" class="lookdiv_upload" >
	       			
	       		</div>
	       		<div style="text-align:right;width:100%;">
	       			<a id="flashuploadpost" href="javascript:;" onclick="swobj.startUpload()" ><b>{lang upload}</b></a>&nbsp;&nbsp;<span id="flashCancel"></span>
	       		</div>
	</div>
	</form>
	<form id="attchform" name="attchform" method="POST"  enctype="multipart/form-data" action="index.php?wskm=upload&act=common" >
	<input type="hidden" name="arthash" value="{ART_HASH}" />
	    <div id="uploadwrap" style="display:none;" class="lookdiv_main" >
	       		<div class="lookdiv_title" ><i style="float:left">&nbsp;{lang upload_attch}</i><i style="float:right"><a href="javascript:;" onclick="uploadshow_close()" style="color:#A71616" >{lang close}</a>&nbsp;</i></div>
	       		<div id="uploadct" class="lookdiv_upload" >
	       			<input type="file" name="uploadattch[]"  >
	       		</div>
	       		<div style="text-align:right;width:100%;">
	       			<a href="javascript:;" class="ablue" onclick="uploadshow_add()"><b>{lang add}</b></a>&nbsp;<a href="javascript:;" class="ablue" onclick="uploadshow_post()"><b>{lang upload}</b></a>&nbsp;&nbsp;
	       		</div>
	       </div>
	
	<div id="tempimg" style="display:none"></div>
	<div id="imgshow" style="display:none;" class="lookimg"><img src="" border="0" id="testimg" ></div>
	            	<script type="text/javascript" >
	            	var editname='{$editname}';
	            	var oEditor=null;
	            	var editdrive='{$editdrive}';
	            	function flashuploadshow()
	            	{
	            		var upload=jQuery('#flashuploadwrap');
	            		setPosition('flashuploadshow','flashuploadwrap');
	            		jQuery('#uploadwrap').hide();
	            		upload.show();
	            	}
	            	function uploadshow()
	            	{
	            		var upload=jQuery('#uploadwrap');
	            		setPosition('linkupload','uploadwrap');
	            		jQuery('#flashuploadwrap').hide();
	            		upload.show();
	            	}
	            	function uploadshow_close()
	            	{
	            		jQuery('#uploadwrap').hide();
	            		jQuery('#uploadct').html(uploadhtml);
	            	}
	            	function flashuploadshow_close()
	            	{
	            		jQuery('#flashuploadwrap').hide();
	            	}
	            	
	            	var uploadhtml='<input type="file" name="uploadattch[]" />';
	            	function uploadshow_add()
	            	{
	            		jQuery('#uploadct').append(uploadhtml);
	            	}
	            	
	            	function attach_row(attach){
	            		if(typeof attach != 'object' )return;
	            		var html='';
	            		if(attach['isimage']){
	            			html='<div class="attachli" id="attachdiv'+ attach['aid'] +'"><i style="float: left;"><img border="0" id="addimg'+ attach['aid'] +'" src="'+WEB_URL+'images/aicons/16'+ attach['icon'] +'">'+ attach['name'] +'</i><i style="float:right;"><a class="ablue" onclick="insert_attachto(\''+ WEB_URL+attach['path'] +'\',\''+ attach['icon'] +'\','+attach['aid']+',\''+ attach['eid'] +'\',\''+ attach['name'] +'\')" href="javascript:;">'+lang_insert+'</a>&nbsp;<a class="ablue" onclick="insert_thumbto('+ attach['aid'] +',\''+ attach['eid'] +'\')" href="javascript:;">'+lang_thumb+'</a>&nbsp;<a class="ablue" target="_blank" href="index.php?wskm=image&amp;act=imagecrop&amp;path='+ attach['path'] +'&amp;attachid='+ attach['aid'] +'&amp;isthumb=1">'+lang_edit+'</a>&nbsp;<a class="ablue" onclick="delattach(\''+ attach['aid'] +'\')" href="javascript:;">'+lang_del+'</a><input type="hidden" name="attachid[]" value="'+ attach['aid'] +'"></i></div>';
	            		}else{
	            			html='<div class="attachli" id="attachdiv'+ attach['aid'] +'"><i style="float: left;"><img border="0" id="addimg'+ attach['aid'] +'" src="'+WEB_URL+'images/aicons/16'+ attach['icon'] +'">'+ attach['name'] +'</i><i style="float: right;"><a class="ablue" onclick="insert_attachto(\''+ WEB_URL+attach['path'] +'\',\''+ attach['icon'] +'\','+ attach['aid'] +',\''+ attach['eid'] +'\',\''+ attach['name'] +'\')" href="javascript:;">'+lang_insert+'</a>&nbsp;<a class="ablue" onclick="delattach(\''+ attach['aid'] +'\')" href="javascript:;">'+lang_del+'</a><input type="hidden" name="attachid[]" value="'+ attach['aid'] +'"></i></div>';
	            		}
						return html;
	            	}
	            	
	            	function uploadshow_post()
	            	{
	            		ajaxload_resize();
	            		formPost('attchform','','','',function(s){
	            			var attach=jsonParse(s);
	            			jQuery.each(attach,function(i,v){
	            				if(!attach[i]['err']){
	            					var html=attach_row(attach[i]);
	            					jQuery('#uoloaded').append(html);
	            					var path=WEB_URL+attach[i]['path'];
	            					var width=attach[i]['width'];
	            					var attachid=attach[i]['aid'];
	            					imgPreview(attachid,width,path);
	            					setTimeout(function(){
	            						uploadshow_close();
	            					},500);
	            				}
	            				else{
	            					jQuery('#uploadct').append('<br/><b style="color:red">'+(i+1)+'</b>) '+attach[i]['err']);
	            				}
	            			});


	            		});
	            	}

	            	function imgPreview(attachid,width,path)
	            	{
	            		var img=jQuery('#addimg'+attachid);
	            		width=width<250?parseInt(width):250;
	            		img.hover(
	            		function () {
	            			var aoffset= img.offset();
	            			jQuery('#imgshow').css('left',aoffset.left+20);
	            			jQuery('#imgshow').css('top',aoffset.top+20);
	            			jQuery('#testimg').attr('src',path);

	            			jQuery('#testimg').attr('width',width);
	            			jQuery('#imgshow').show();
	            		},
	            		function () {
	            			jQuery('#imgshow').hide();
	            		}
	            		);
	            	}

					function setEditor(){
	            		if(oEditor != null)return;
	            		if(editdrive=='ckeditor'){
	            			oEditor=CKEDITOR.instances.{$editname};
	            		}else if(editdrive=='xheditor'){
	            			oEditor=$('#'+editname).xheditor();
	            		}
	            		if(oEditor == null) alert('Editor Error!');
	            	}

	            	function getEditorContent(){
	            		setEditor();
	            		if(editdrive=='ckeditor'){
	            			return oEditor.getData();
	            		}else if(editdrive=='xheditor'){
	            			return oEditor.getSource();
	            		}
	            		return '';
	            	}

	            	function setEditorContent(s){
	            		setEditor();
	            		if(editdrive=='ckeditor'){
	            			oEditor.setData(s);
	            		}else if(editdrive=='xheditor'){
	            			oEditor.setSource(s);
	            		}
	            	}
	            	
	            	function insertEditHtml(txt){
	            		if(editdrive=='ckeditor'){
	            			oEditor.insertHtml(txt);
	            		}else if(editdrive=='xheditor'){
	            			oEditor.pasteHTML(txt);
	            		}
	            	}            	

	            	function insert_attachto(path,icon,id,encodeid,fname){
	            		insert_attach_base(path,icon,encodeid,fname);
	            		add_attach(id);
	            	}

	            	function insert_attach(path,icon,id,fname){
	            		insert_attach_base(path,icon,id,fname);
	            	}

	            	function insert_attach_base(path,icon,id,fname)
	            	{
	            		setEditor();	  
						if(!icon){
	            			icon='unknown.gif';
	            		}
	            		var isimage=icon=='image.gif';
	            		
	            		if(isimage){
	            			setTimeout(function(){
	            				insertEditHtml('<img src="'+path+'" border="0" />');
	            			},100);
	            		}
	            		else{
	            			insertEditHtml('&nbsp;<img src="'+ SITE_URL+'images/aicons/'+icon+'" border=0 />&nbsp;<span class="down"><a href="'+WEB_URL+'download.php?id='+id +'" target="_blank" ><b>'+fname+'</b></a></span>&nbsp;');
	            		}

	            	}

	            	function add_attach(id){
	            		jQuery('#uoloaded').append('<input type="hidden" name="attachadd[]" value="'+id+'" />');	            		
	            	}

	            	function insert_thumbto(id,encodeid){
	            		insert_thumb_base(id,encodeid);
	            		add_attach(id);
	            	}

	            	function insert_thumb(id,encodeid){
	            		insert_thumb_base(id,encodeid);
	            	}

	            	function insert_thumb_base(id,encodeid)
	            	{
	            		var r=Math.random();
	            		jQuery.get('index.php?wskm=upload&act=isexist_thumb',{'id':id,'r':r},function(data){
	            			data=jQuery.trim(data);
	            			if(data)
	            			{
	            				setEditor();
	            				setTimeout(function(){
	            					insertEditHtml('<img src="'+WEB_URL+data+'" border="0" >');
	            				},100);
	            				return true;
	            			}else{
	            				alert('{lang thumb_inset_no}');
	            				return false;
	            			}
	            		});

	            	}

	            	function useinsert(path,icon,id,encodeid,fname)
	            	{
	            		insert_attachto(path,icon,id,encodeid,fname);
	            	}

	            	function delattach(attachid)
	            	{
	            		jQuery.get("index.php?wskm=upload&act=ajax_del",{'attachid':attachid},
	            		function(data){
	            			data=$.trim(data);
	            			if(data=='ok'){
	            				jQuery('#attachdiv'+attachid).remove();
	            			}
	            		});
	            	}

	            	function cropattach(path,attachid)
	            	{
	            		var croppic= jQuery(this);
	            		croppic.click(
	            		function () {
	            			var croppath= jQuery('#thumb').val()
	            			if(croppath){
	            				croppic.attr('href','index.php?act=imagecrop&path='+encodeURIComponent(croppath)+'&attachid='+jQuery('#thumbattachid').val());
	            			}
	            			else{
	            				alert('{lang thumb_empty}');
	            				return false;
	            			}
	            		}
	            		);
	            	}

            	</script>
	</form>
	
	<div class="div_article">

    <script type="text/javascript" >
    function checkArticlePost(){
    	var title=$('#title');
    	if(!title.val()){
    		title.focus();
    		return false;
    	}
    	var cid=$('#cate_id');
    	if(!cid.val()){
    		cid.focus();
    		return false;
    	}

		{if $kindid==1}
    	var polloption=$('#firstoption');
    	if(typeof polloption== 'object' && !polloption.val()){
    		polloption.focus();
    		return false;
    	}
    	{/if}
    	return true;
    }

    </script>
    <form method="post" enctype="multipart/form-data" id="article_form" onsubmit="return checkArticlePost()" action="index.php?wskm=article&act={$wskmaction}&articlepost=1" >
		<input type="hidden" name="arthash" value="{ART_HASH}" />
    	<input type="hidden" name="aid" id="aid" value="{$aid}" />
    	<input type="hidden" name="changecolor" id="changecolor" value="0" />
		<input type="hidden" name="kindid" value="{$kindid}" />
        <table width="100%"  class="tb_article tbms1"  >

         <tr class="sp"  >
       		<td colspan="2"></td>
       </tr>
            <tr>
                <td style="width:70px"><span class="editatitle">{lang maintitle}：</span></td>  
                <td >
                    <input  id="title" type="text" name="title"  size="70" maxlength="80" onblur="this.value=$.trim(this.value)" onkeyup="textchange(this,'maxmsg',80)" value="{$article.title|escape}"   class="article_title"  />                  
                    {lang title_max_info}<strong id="maxmsg" style="color:maroon">80</strong>byte
                </td>
            </tr>
            <tr>
                <td valign="top"><span class="editatitle">{lang title_style}：</span></td>
                <td  id="titlestyle" >
					
	                <b>{lang font_color}</b>&nbsp;<input type="text" name="titlestyle[color]" id="title_color" class="color" onchange="$('#changecolor').val(1);settitlestyle();" style="width:80px"  />
					<b>{lang font_size}</b>
					<select name="titlestyle[font-size]" id="title_size" onChange="settitlestyle()">
					<option value="" selected="selected"></option>
					{php $i=12; for($i=10;$i<20;$i++){ }
					<option value="{$i}px">{$i}px</option>
					{php } }
					</select>
					&nbsp;<b >B</b><input id="title_b"  type="checkbox" name="titlestyle[font-weight]" value="bold" onclick="settitlestyle()"  />
	                &nbsp;<b style="text-decoration:underline" >U</b><input type="checkbox" name="titlestyle[text-decoration]"  id="title_u" value="underline" onclick="settitlestyle()"  />
	                &nbsp;<b style="font-style:italic" >I</b><input type="checkbox" name="titlestyle[font-style]" id="title_i" value="italic" onclick="settitlestyle()"  />
	                
	                
	 <script type="text/javascript" >
	 var tB="{echo $article['titlestyle']['font-weight']}";
	 var tI="{echo $article['titlestyle']['font-style']}";
	 var tU="{echo $article['titlestyle']['text-decoration']}";
	 var tSize="{echo $article['titlestyle']['font-size']}";
	 var tColor="{echo $article['titlestyle']['color']}";

	 function initTitle()
	 {
	 	var title=$('#title');

	 	if(tColor != ''){
	 		$('#title_color').val(tColor);
	 		title.css('color',tColor);
	 	}

	 	if(tSize){
	 		$('#title_size').val(tSize);
	 		title.css('font-size',tSize);
	 	}


	 	var ischecked1=tB=='bold'?true:false;
	 	var ischecked2=tI=='italic'?true:false;
	 	var ischecked3=tU=='underline'?true:false;

	 	title.css('font-weight',ischecked1?'bold':'');
	 	box=$('#title_b');
	 	box.attr('checked',ischecked1)
	 	box.val(ischecked1?'bold':'')

	 	title.css('font-style',ischecked2?'italic':'');
	 	box=$('#title_i');
	 	box.attr('checked',ischecked2)
	 	box.val(ischecked2?'italic':'')

	 	title.css('text-decoration',ischecked3?'underline':'');
	 	box=$('#title_u');
	 	box.attr('checked',ischecked3)
	 	box.val(ischecked3?'underline':'')

	 	textchange(dom('title'),'maxmsg',80);
	 }

	 function textchange(obj,msgid,msglen)
	 {
	 	var maxlen=msglen;
	 	var len = mb_strlen(obj.value);
	 	var msg = $('#'+msgid);
	 	var inputlen=maxlen - len;
	 	inputlen=inputlen<1?0:inputlen;
	 	msg.html(inputlen);
	 	if(len > maxlen){
	 		obj.value=mb_strcut(obj.value,maxlen,true);
	 	}
	 }

	 function settitlestyle()
	 {
	 	var title=$('#title');
	 	if($('#changecolor').val()=='1'){
	 		title.css('color',$('#title_color').val());
	 	}

	 	title.css('font-size',$('#title_size').val());

	 	var ischecked1=$('#title_b').attr('checked')?true:false;
	 	$('#title_b').val(ischecked1?'bold':'');

	 	var ischecked2=$('#title_i').attr('checked')?true:false;
	 	$('#title_i').val(ischecked2?'italic':'');

	 	var ischecked3=$('#title_u').attr('checked')?true:false;
	 	$('#title_u').val(ischecked3?'underline':'');

	 	title.css('font-weight',ischecked1?'bold':'');
	 	title.css('font-style',ischecked2?'italic':'');
	 	title.css('text-decoration',ischecked3?'underline':'');

	 }

	 initTitle();
    </script>
                </td>
                
            </tr>   
                            
            <tr>
                <td><span class="editatitle">{lang category}：</span></td>
                <td >
                    <select id="cate_id" name="cid" >$select_options</select>
                </td>
            </tr>
          
             <tr>
                <td><span class="editatitle"> {lang thumb}：</span></td>
                <td >
                    <input class="editinput" id="thumb" type="text" name="coverthumb" value="{$article.cover}" />&nbsp;<a href="javascript:;" target="_blank" id="thumbshow" class="ablue" >{lang preview}</a>&nbsp;<a href="javascript:;" id="thumbupload" class="ablue" >{lang upload}</a>&nbsp;<a href="javascript:;" id="croppic" class="ablue" target="_blank" >{lang cropphoto}</a>
                    <input type="hidden" id="thumbattachid" name="thumbattachid" value="0" />
                    <script type="text/javascript">
                    var thumbshow= jQuery('#thumbshow');
                    thumbshow.hover(
                    function () {
                    	var thumbpath= jQuery('#thumb').val()
                    	if(thumbpath){
                    		var path='{ART_URL}'+thumbpath;
                    		var aoffset= thumbshow.offset();
                    		jQuery('#imgshow').css('left',aoffset.left+30);
                    		jQuery('#imgshow').css('top',aoffset.top);
                    		jQuery('#testimg').attr('src',path);
                    		thumbshow.attr('href',path);
                    		thumbshow.attr('target','_blank');
                    		jQuery('#imgshow').show();
                    	}else{
                    		thumbshow.attr('target','_self');
                    	}

                    },
                    function () {
                    	jQuery('#imgshow').hide();
                    }
                    );

                    var croppic= jQuery('#croppic');
                    croppic.click(
                    function () {
                    	var croppath= jQuery('#thumb').val()
                    	if(croppath){
                    		croppic.attr('href','index.php?wskm=image&act=imagecrop&isself=1&path='+encodeURIComponent(croppath)+'&attachid='+jQuery('#thumbattachid').val());
                    	}
                    	else{
                    		alert('{lang thumb_notempty}');
                    		return false;
                    	}
                    }
                    );

                    function ajaxload_resize()
                    {
                    	var stop=jQuery(window).scrollTop();
                    	jQuery('#ajax_load').css('top',stop);
                    }

                    function thumbform_post(){
                    	var fval=jQuery('#thumbfileupload').val();
                    	if(!fval)return;
                    	ajaxload_resize();
                    	formPost('thumbform','','','',function(s){
                    		var attach=jsonParse(s);
                    		jQuery.each(attach,function(i,v){
                    			if(!attach[i]['err']){
                    				var path=attach[i]['path'];
                    				var attachid=attach[i]['attachid'];
                    				jQuery('#thumbattachid').val(attachid);
                    				jQuery('#thumb').val(path);
                    			}
                    			else{
                    				alert(attach[i]['err']);
                    			}
                    		});
                    		setTimeout(function(){
                    			jQuery('#thumbupdiv').hide();
                    		},500);

                    	});
                    }

                    function setthumb(path)
                    {
                    	jQuery('#thumb').val(path);
                    }

                    var isedit=0;
                    function setsysedit(path)
                    {
                    	setEditor();
                    	setTimeout(function(){
                    		insertEditHtml('<a href="'+SITE_URL+path+'" target="_blank" ><img src="'+SITE_URL+path+'" border="0" ></a>');
                    	},100);
                    }

                    function thumbPreview(id,width,path)
                    {
                    	var img=jQuery('#fsyslook'+id);
                    	width=width<250?parseInt(width):250;
                    	img.mousemove(function(e){
                    		jQuery('#imgshow').css('left',e.pageX+15);
                    		jQuery('#imgshow').css('top',e.pageY+15);
                    		jQuery('#imgshow').css('z-index',1000);
                    		jQuery('#testimg').attr('src',WEB_URL+path);
                    		jQuery('#testimg').attr('width',width);
                    		jQuery('#imgshow').show();
                    	});
                    	img.mouseout(function(e){
                    		jQuery('#imgshow').hide();
                    	});

                    }
                  
                    function thumbuplod()
                    {
                    	var thumbupload= jQuery('#thumbupload');

                    	thumbupload.click(function(){
                    		var thumbform=jQuery('#thumbupdiv');
                    		var aoffset= thumbupload.offset();
                    		thumbform.css('left',aoffset.left);
                    		thumbform.css('top',aoffset.top-30);

                    		thumbform.css('right','auto');
                    		thumbform.css('height','26px');
                    		thumbform.css('line-height','26px');
                    		thumbform.css('width','320px');

                    		thumbform.show();
                    	});
                    }
                    thumbuplod();
                    </script>
                </td>
            </tr> 
			{if $kindid==1}
            {template article_poll}            
            {/if}
  
            <tr>
            	<td valign="top" ><span class="editatitle">{lang article_summary}：</span></td>
            	<td >
            	</td>
            </tr>
            <tr>
	            <td colspan="2">
	            <textarea name="summary" id="summary" style="width:100%;height:55px;"  >{$article.summary|escape}</textarea>
	            </td>
            </tr>
            <tr>
            	<td><span class="editatitle">{lang article_content}：</span></td>
            	<td align="right" >
            	<a href="javascript:;" onclick="autosavea();" id="articlemsgsave" >{lang article_svaedraft}</a>&nbsp;<a href="javascript:;" onclick="loadsavea()" id="articlemsgload" >{lang article_readdraft}</a>            	
            	</td>
            </tr>
  			<script type="text/javascript" >
  				function autosavea(){
  					var sendmsg=getEditorContent();
  					if(sendmsg.length < 20){
  						return;
  					}
  					$('#articlemsgsave').html('save...');
  					$.post('index.php?wskm=admin&act=saveadata',{'data':sendmsg,'arthash':'{ART_HASH}' },function(s){
  						if(s=='1'){
  							$('#articlemsgsave').html('{lang save_ok}');
  						}else{
  							$('#articlemsgsave').html('{lang save_err}');
  						}
  					});
  				}
  				setInterval(autosavea,600000);
  				function loadsavea(){
  					$('#articlemsgload').html('load...');
  					$.post('index.php?wskm=admin&act=getadata',{'arthash':'{ART_HASH}' },function(s){
  						if(s){
  							setEditorContent(s);
  							$('#articlemsgload').html('{lang read_ok}');
  						}else{
  							$('#articlemsgload').html('{lang read_err}');
  						}
  					});
  				}
  			</script>
            <tr class="nobg">
                <td valign="top" colspan="2" align="center">                   
                	<div style="width:99.9%;text-align:left;border:solid 1px #EAEAD9;">
	                <center>{$htmledit}</center>
	                	<div class="editor_foot">
	                	<input type="radio" value="0" {if !$editpagetype}checked="true"{/if} name="pagetype" />{lang pagetype_js}&nbsp;<input type="radio" {if $editpagetype}checked="true"{/if} name="pagetype" value="1" />{lang pagetype_web}&nbsp;&nbsp;
	                	</div>
                	</div>
                </td>
            </tr>
                                
             {if $attachsuse }
             <tr>
	            <td valign="top"><span class="editatitle">{lang attach_use}：</span></td>
	            <td>                
	            	<a href="javascript:;" onclick="showuseattch()" id="useacttacha" class="ablue" >{lang look}</a>
	            	<script type="text/javascript" >
	            	function showuseattch()
	            	{
	            		var useacttach=jQuery('#uesuoloaded');
	            		setPosition('useacttacha','uesuoloaded');
	            		useacttach.show();
	            	}
	            	</script>
	        		<div id="uesuoloaded" class="testdiv" style="display:none" ><div style="height:20px;border-bottom:dashed 1px gray;padding:0;margin:0;" ><i style="float:left"><b>{lang attach_use}</b></i><i style="float:right;padding-right:5px;"><a href="javascript:;" onclick="if(jQuery('#uesuoloaded').css('display')!='none')jQuery('#uesuoloaded').hide()" class="ared" >{lang close}</a></i></div>
						<!--{loop $attachsuse $attach}-->
	        			<div id="attachdiv{$attach['id']}" class="attachli" >
	        			
	        				<i style="float:left"><img src="{ART_URL}images/aicons/16{$attach['icon']}"  border="0"  id="addimg{$attach['id']}" />{$attach['filename']}</i><i style="float:right"><a href="javascript:;" onclick="useinsert('{ART_URL}attachments/{$attach['filepath']}','{$attach['icon']}',{$attach['id']},'$attach['encodeid']','{$attach['filename']}')" class="ablue" >{if $attach.isimage}{lang image_origin}{else}{lang insert_file}{/if}</a>{if $attach.isimage}&nbsp;|&nbsp;<a href="javascript:;" onclick="insert_thumbto({$attach['id']},'{$attach['encodeid']}')" class="ablue" >{lang image_thumb}</a>&nbsp;|&nbsp;<a href="index.php?wskm=image&act=imagecrop&path=attachments/{$attach['filepath']}&attachid={$attach['id']}&isthumb=1" target="_blank" class="ablue" >{lang thumb_edit}</a>{/if}&nbsp;|&nbsp;<a href="javascript:;" onclick="delattach($attach['id'])" class="ablue" >{lang drop}</a><input type="hidden" value="$attach['id']" id="usehide{$attach['id']}" name="uselocalid[]" />&nbsp;</i> 
	        				<script type="text/javascript">
	        				{if $attach['isimage'] }
	        				imgPreview( '{$attach['id']}',{$attach['width']},'{ART_URL}attachments/{$attach['filepath']}');
	        				{/if}
	        				</script>	        				
	        			</div>
	        			<!--{/loop}-->
	        		</div>
	        		
	            </td>
            </tr>
            {/if} 
           <tr class="even">
            	<td><span class="editatitle">{lang upload_attch}：</span></td>
            	</td>
            	<td >
            		<a href="javascript:;" id="linkupload" onclick="uploadshow()" class="ablue"><u>{lang common_upload}</u></a>&nbsp;&nbsp;<a id="flashuploadshow" onclick="flashuploadshow()" href="javascript:;" class="ablue"><u>Flash</u></a>
            	</td>
            </tr>
            <tr class="nobg" >
	            <td valign="top"><span class="editatitle">{lang uploaded_files}：</span></td>
	            <td>
	        		<div id="uoloaded" class="uploadwrap" >
					<!--{loop $attachs $attach}-->
	        			<div id="attachdiv{$attach['id']}" class="attachli" >
	        				<i class="left"><img src="{ART_URL}images/aicons/16{$attach['icon']}"  border="0"  alt="" id="addimg{$attach['id']}" />{$attach['filename']}</i><i  class="right"><a href="javascript:;" onclick="insert_attach('{ART_URL}attachments/{$attach['filepath']}','{$attach['icon']}','{$attach['encodeid']}','{$attach['filename']}')" class="ablue" >{lang insert_file}</a>&nbsp;{if $attach.isimage}<a href="javascript:;" onclick="insert_thumb('{$attach.id}','{$attach.encodeid}')" class="ablue" >{lang image_thumb}</a>&nbsp;<a href="index.php?wskm=image&act=imagecrop&path=attachments/{$attach['filepath']}&attachid={$attach.id}&isthumb=1" target="_blank" class="ablue" >{lang thumb_edit}</a>&nbsp;{/if}<a href="javascript:;" onclick="delattach($attach['id'])" class="ablue" >{lang drop}</a><input type="hidden" value="$attach['id']" name="localid[]" /></i>
	        				<script type="text/javascript">
	        				{if $attach['isimage'] }
	        				imgPreview( '{$attach['id']}',{$attach['width']},'{ART_URL}attachments/{$attach['filepath']}');
	        				{/if}
	        				</script>
	        			</div>
	        			<!--{/loop}-->
	        		</div>
	        		
	        		<script type="text/javascript" >
	        		if($.browser.msie && $('#uoloaded').children('div').length >3 ){
	        			$('#uoloaded').css('height','150px');
	        		}
	        		</script>
	            </td>
            </tr>
               
             <tr class="sp"  >
       		<td colspan="2"></td>
       </tr>  
       		<tr>
                <td valign="top"><span class="editatitle">{lang tag}：</span></td>
                <td >
                <input id="tags" type="text" class="editinput w300" name="tags" value="{$article.tags}" />&nbsp;({lang tag_notice})
                </td>  
            </tr>  
            <tr>
                <td valign="top"><span class="editatitle">{lang author}：</span></td>
                <td >
                <input id="author" type="text" class="editinput" name="author" value="{$article.author}" />     					
                </td>  
            </tr>   
            
             <tr>
                <td valign="top"><span class="editatitle">{lang fromname}：</span></td>
                <td >
                <input id="fromname" type="text"  class="editinput" name="fromname" value="{$article.fromname}" />     					
                </td>
            </tr>   
            
             <tr>
                <td valign="top"><span class="editatitle">{lang fromurl}：</span></td>
                <td >
                <input id="fromurl" type="text"  class="editinput" name="fromurl" value="{$article.fromurl}" /> 
                </td>
            </tr>   
       
       <tr class="sp"  >
       		<td colspan="2"></td>
       </tr>   
		<tr>
                <td>
                    <label for="if_show"><span class="editatitle">{lang article_state}：</span></td>
                <td >
                    <input id="syes" type="radio" name="status" value="1" {if $article.status == 1} checked="checked"{/if} />
                    <label for="syes">{lang normal}</label>
                    <input id="sno" type="radio" name="status" value="2" {if $article.status == 2} checked="checked"{/if} />
                    <label for="sno">{lang verify}</label>
                    <input id="sno" type="radio" name="status" value="0" {if $article.status == 0} checked="checked"{/if} />
                    <label for="sno">{lang close}</label>
                </td>
            </tr>      
       <tr>
            	<td><span class="editatitle">{lang reply_state}：</span></td>
            	</td>
            	<td >
            		<input id="rs2" type="radio" name="replystate" value="2" {if $article.replystate == 2} checked="checked"{/if} />
                    <label for="rs2">{lang replay_anonym}</label>
            		<input id="rs1" type="radio" name="replystate" value="1" {if $article.replystate == 1} checked="checked"{/if} />
                    <label for="rs1">{lang replay_member}</label>
                    <input id="ano" type="radio" name="replystate" value="0" {if $article.replystate == 0} checked="checked"{/if} />
                    <label for="ano">{lang replay_no}</label>
            	</td> 
        </tr>
       	<tr>
            	<td><span class="editatitle">{lang article_digest}：</span></td>
            	</td>
            	<td >
            		<input id="dyes" type="radio" name="digest" value="1" {if $article.digest == 1} checked="checked"{/if} />
                    <label for="dyes">{lang yes}</label>
                    <input id="dno" type="radio" name="digest" value="0" {if $article.digest == 0} checked="checked"{/if} />
                    <label for="dno">{lang no}</label>
            	</td> 
        </tr>

        <tr class="sp"  >
       		<td colspan="2"></td>
       </tr>
        <tr>
            <td></td>
            <td>
	             <div class="btncright mt5">
	            <input class="btncommon" type="submit" value="{lang submit}" name="articlepost"  />            
	            </div>
	            
	             <div class="btncright mt5">
	            <input class="btncommon" type="reset" value="{lang reset}"  />     
	            </div>
            </td>
        </tr>
        </table>
    </form>
<script type="text/javascript">
jQuery(function(){
	table_evenbg('tbms1',1);
});
</script>
 	</div>

</div>
</div>

<script type="text/javascript" src="{ART_URL}includes/js/jscolor.js"></script>
<script type="text/javascript">
var lang_del='{lang drop}';
var lang_edit='{lang thumb_edit}';
var lang_insert='{lang insert_file}';
var lang_thumb='{lang image_thumb}';
</script>
<link href="{ART_URL}includes/swfupload/swfupload.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="{ART_URL}includes/swfupload/swfupload.js"></script>
<script type="text/javascript" src="{ART_URL}includes/swfupload/handlers.js"></script>
<script type="text/javascript" >
var swobj;
$(function(){
	var settings = {
		flash_url : "{ART_URL}includes/swfupload/swfupload.swf",
		upload_url: "{ART_URL_FULL}wskm.php?act=swfupload",
		post_params: {'uid':"{UID}",'hash':'{$uploadhash}'},
		file_size_limit : "{$attachMaxSize}",
		file_types : "*.*",
		file_types_description : "All Files",
		file_upload_limit : 10,
		file_queue_limit : 0,
		custom_settings : {
			progressTarget : "flashuploadct",
			cancelButtonId : "flashCancel"
		},
		debug: false,
		button_image_url : "{ART_URL}includes/swfupload/upbtn.png",
		button_width: 61,
		button_height: 22,
		button_placeholder_id: "flashuploadli",

		file_queued_handler : fileQueued,
		file_queue_error_handler : fileQueueError,

		upload_start_handler : uploadStart,
		upload_progress_handler : uploadProgress,
		upload_error_handler : uploadError,
		upload_success_handler : uploadSuccess
	};

	swobj = new SWFUpload(settings);
});
</script>
{htmltemplate footer}