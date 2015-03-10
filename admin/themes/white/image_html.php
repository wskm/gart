<?exit?>
<html>
	<head>
		<script src="{ART_URL}includes/js/jquery.js"></script>
		<script src="{ART_URL}includes/js/plugin/jcrop.js"></script>		
		<link rel="stylesheet" href="{ATHEME_CSS}/jcrop.css" type="text/css" />
		
		<script language="Javascript">

		$(function(){

			$('#cropbox').Jcrop({
				//aspectRatio: 1,
				onSelect: updateCoords,
				minSize:[200,200],				
				setSelect: [ 50, 50, {$thumbwidth}, {$thumbheight} ]
				
			});

		});

		function updateCoords(c)
		{
			$('#x').val(c.x);
			$('#y').val(c.y);
			$('#w').val(c.w);
			$('#h').val(c.h);
		};

		function checkCoords()
		{
			if (parseInt($('#w').val())) return true;
			alert('{lang crop_nohand}');
			return false;
		};
		
		function  checkThumb(){
			if (parseInt($('#tw').val()) < 100 || parseInt($('#th').val())<100 ){
				alert('{lang thumb_input_err}');
				 return false;
			}
			return true
		}
		
		</script>

	</head>

	<body>

	<div id="outer">
	<div class="jcExample">
	<div class="article">
		
		<form action="index.php?wskm=image&act=imagethumb&thumbpost=1" method="post" onsubmit="return checkThumb();">
			<input type="hidden" name="arthash" value="{ART_HASH}" />
			<input type="hidden" id="imagepath" name="imagepath" value="{$originpath}" />
			<input type="hidden" id="attachid" name="attachid" value="{$attachid}" />
			<input type="hidden" name="isself" value="{$isself}" />			
			{lang width}：<input type="text" id="tw" name="width" value="{$thumbwidth}" style="width:100px;" />&nbsp;{lang height}：<input type="text" id="th" style="width:100px;" name="height" value="{$thumbheight}" />
			<br><br><input type="image" src="{ATHEME_IMG}thumbsubmit.gif" name="thumbpost" value="1" />
		</form>

		<img src="{$imagepath}" id="cropbox" style="border:solid 1px maroon;" />				
		
		<form action="index.php?wskm=image&act=imagecrop&croppost=1" method="post" onsubmit="return checkCoords();">
			<input type="hidden" id="x" name="x" />
			<input type="hidden" id="y" name="y" />
			<input type="hidden" id="w" name="w" />
			<input type="hidden" id="h" name="h" />
			<input type="hidden" id="imagepath" name="imagepath" value="{$originpath}" />
			<input type="hidden" id="isthumb" name="isthumb" value="{$isthumb}" />
			<input type="hidden" id="attachid" name="attachid" value="{$attachid}" />
			<input type="hidden" name="isself" value="{$isself}" />
			<input type="hidden" name="arthash" value="{ART_HASH}" />
			<br>
			<input type="image" src="{ATHEME_IMG}cropsubmit.gif" name="croppost" value="1" />
		</form>

		
		<br>
		
		
		
	</div>
	</div>
	</div>
	</body>

</html>
