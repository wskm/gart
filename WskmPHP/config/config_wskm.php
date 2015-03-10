<?php !defined('IN_WSKM') && exit('Access Denied');

return array(
'userCacheDir'				=> ART_ROOT.'cache',
'userMvcDir'				=> ART_ROOT.'engine',
'userModDir'				=> ART_ROOT.'engine'.DIRECTORY_SEPARATOR.'model',
'userAppDir'				=> ART_ROOT.'engine'.DIRECTORY_SEPARATOR.'app',
'userTplDir'				=> ART_ROOT.'themes',
'userHtmlDir'				=> ART_ROOT.'cache'.DIRECTORY_SEPARATOR.'html',
'cacheHtmlTime'				=> 1800,
'cachePageType'				=> CACHETYPE_SQL,
'cachePageTime'				=> 3600,
'cacheStaticType'			=> CACHETYPE_FILE,
'cacheStaticTime'			=> 10800,
'cachePlusTableNameGrade'	=> 0,

'langdir'					=> ART_ROOT.'languages',

'mvcEnabled'				=> true,
'urlStyle'     				=> '-',						
'urlArgsStyle'     			=> '-',
'urlExt'					=> '.html',

'appUrlName'        		=> 'wskm',					
'actUrlName'           		=> 'act',
'appPrefix'		    		=> 'app_',					
'appDefault'    			=> 'index',					
'actPrefix'        			=> 'do',
'actDefault'		        => 'Index',					
'modelPrefix'				=> 'model_',				
'appAdminPrefix'		 	=> 'app_admin_',
'modelAdminPrefix'			=> 'model_admin_',

'displayException'			=>true,
'exceptionHandler'			=>'EXCEPTION_HANDLER',
'auotHeader'				=>true,						

)

?>