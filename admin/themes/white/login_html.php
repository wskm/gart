<?exit?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset={PAGE_CHARSET}" />
<title>{lang login_required}- Powered by Gart</title>
<script type="text/javascript" src="{ART_URL}includes/js/jquery.js"></script>
<script type="text/javascript" src="{ATHEME_URL}js/admin.js"></script>
<script type="text/javascript">
if (self != top)
{
    top.location = self.location;
}
$(function(){
    $('#uname').focus();
});
</script>
<style type="text/css" >
body {
background:none repeat scroll 0 0 #EEEEEE;
color:#000000;
font-family:Verdana;
font-size:13px;
margin-top:40px;
padding:32px;
}
#main {
background:none repeat scroll 0 0 #FFFFFF;
border:1px solid #D0D0D0;
padding:38px 50px 38px 60px;
text-align:left;
width:220px;
margin:15px 0;
}
.foot{text-align:right;font-size:12px;color:#565656;}
p {
margin:0;
padding:6px 0;
}
a:link {
color:#002C99;
font-size:12px;
}
a:visited {
color:#002C99;
font-size:12px;
}
a:hover {
background-color:#F5F5F5;
color:#CC0066;
font-size:12px;
text-decoration:underline;
}
.lbl {
color:#666;
display:block;
font-size:12px;
font-weight:bold;
margin:6px 0 2px;
text-transform:uppercase;
}
input.Textbox {
font-family:verdana,arial,sans-serif;
height:20px;
padding:2px;
width:200px;
width:180px;
}
input.Textbox_nob {
font-family:verdana,arial,sans-serif;
height:20px;
padding:2px;
width:200px;
width:180px;
}

.uname{
font-family:verdana,arial,sans-serif;
height:20px;
padding:2px;
width:200px;
display:block;
font-size:15px;
}

.Button {
color:#000;
font-family:Verdana;
font-size:16px;
font-weight:bold;
padding-bottom:2px;
padding-top:3px;
width:80px;
}

</style>
</head>
<body>
<div  style="width:100%;text-align:center">
    <form method="post" action="?wskm=auth&act=login&adminlogin=1" onsubmit="return checkpost()" >
    <input type="hidden" name="arthash" value="{ART_HASH}" />
    <script type="text/javascript" >
    	function checkpost(){
    		if(!$('#uname').val()){
    			$('#uname').focus();
    			return false;
    		}
    		
    		if(!$('#password').val()){
    			$('#password').focus();
    			return false;
    		}
    		
    		return true;
    	}
    </script>
    
    <div style="width:100%;">
    <div align="center">
    				
                    <div id="main">                        
	                        <label class="lbl">{lang username}</label>
	                         <input name="uname" type="text" id="uname" class="Textbox_nob" value="" /><br/><br/>
	                        <label class="lbl">{lang password}</label>
	                        <input name="password" type="password" id="password" class="Textbox" /><br/>
	                     <br>
                        <div>
                        <input type="submit" name="btnLogin" value="{lang login}" id="btnLogin" class="Button" style="margin-top: 8px" />                
                        </div>
                        <div>
                     
                        </div>         
                        <br style="clear: both;" />
                        <div class="foot">&raquo;&nbsp;{lang login_footmsg}</div>
				      </div>
				      
		    </div>
		</div>

    </form>
</div>

</body>
</html>