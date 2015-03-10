<?php

if(!defined('INSTALL_ART')) {	
	exit('Access Denied');
}

return array(
	'lang'=>'English',
	'page_title'=>'Gart Setup Wizard',
	'step_title_0'=>'Gart Setup Wizard',
	'step_title_1'=>'1.Environmental Monitoring',
	'step_title_2'=>'2.User driven',
	'step_title_3'=>'3.System Configuration',
	'step_title_4'=>'4.Create configuration',
	'step_title_5'=>'Installation successful!',
	
	'installed'=>'You have to re-install,you must first remove"./cache/install.lock"',
	
	'agreement_no'=>'I do not agree',
	'agreement_yes'=>'I agree',
	'step_up'=>'Back',
	'step_next'=>'Next',
	
	'step_error'=>'Please check the environment configuration,refresh and try again',
	'license_title'=>'Gart License Agreement',
	'license'=>'License Agreement
Copyright (c) 2010,wskms.com All rights reserved.

Thank you for choosing Gart,we will be more efforts to provide you with a perfect,beautiful Station system.

Gart is WSKM Studio (WSKM Studio) development of production,independently owned Gart products copyright.
Official website is www.wskms.com.

This License Agreement applies and only applies to Gart 1.xx version,Gart official final interpretation of the licensing agreement.

First,the right to license agreement
1,you can fully comply with the end user license agreement,based on the software used in this non-commercial use,without having to pay for software version
The right to licensing fees.

2,the agreement you can within the constraints and limitations Gart modify the source code or interface style to suit your site requirements.

3,you have to use this software to build the entire contents of the website ownership,and independent take on the content of the relevant legal obligations.

4,a commercial license,you can use this software for commercial applications,while according to the type of license purchased to determine the technical support
Support content,from the moment of purchase,technical support period has been designated by the manner specified within the scope of technical support services. Business
Authorized users have the power to reflect and comment,relevant comments will be a primary consideration,but not necessarily to be accepted promise or guarantee.

Second,constraints and limitations stipulated in the agreement
1 business license has not been before the Software may not be used for commercial purposes (including but not limited to business sites,business operations,profit
Purpose or profit web site). Purchase of commercial license,please visit www.wskms.com details.

2,without official permission of the software or associated with the commercial license to lease,sell,mortgage or grant sub-licenses.

3,regardless of whether the overall use of your site Gart,or part of the column using Gart,Gart you use the website home page must
With Gart official website (www.wskms.com) link.

4,without official permission,prohibit Gart whole or any part of the basis for the development of any derivative version,modified version or third-party version
This used to re-distribute.

5,if you fail to comply with the terms of this Agreement,your license will be terminated,the licensee\'s rights will be recovered,and bear the corresponding legal responsibility
Ren.

Third,limited warranty and disclaimer
1,the software and the accompanying documents as not to provide any express or implied,or guarantee in the form of compensation provided.

2,the user did not choose to use the software,you must understand the risks of using this software,technical services in the not to purchase products,we
Commitment to free users not provide any form of technology support,use of guarantees,nor liable for any use of the software related issues arising
Responsibility.

3,electronic text form of license agreement as the two sides signed the agreement in writing as a complete and equivalent legal effect. Once you open
Before confirmation of this agreement and install Gart,shall be deemed to fully understand and accept the terms of this Agreement,in the enjoyment of the powers conferred by these provisions
The same time,by the relevant constraints and restrictions. Licensing agreement outside the scope of actions that directly violate the licensing agreement and constitutes infringement,we
Reserves the right to terminate the authorization,shall be ordered to stop the damage,and retains the investigation related to the power of responsibility.',
	
	'env_sys'=>'System Environment',
	'php_version'=>'PHP Version',
	'upload_file'=>'Attachment upload',
	'gd_support'=>'GD Library',
	'mysql_support'=>'Mysql supports',
	
	'fsys_check'=>'Read and write permissions',
	'fun_check'=>'Function',
	
	'groupname_1' => 'administrator',
	'groupname_2' => 'editors',
	'groupname_3' => 'guest',
	'groupname_4' => 'no access',
	'groupname_5' => 'users',
	'groupname_6' => 'validation',
	'groupname_7' => 'member',
	
	'web_index' => 'Home',
	'web_setting' => 'Site settings',
	'web_name' => 'Site name',
	'web_url' => 'Web address',
	'web_url_notice' => 'Example: www.china.com',
	'web_basedir' => 'Web directory',
	'web_basedir_notice' => 'If this is the root directory was empty',
	
	'db_setting' => 'Database configuration',
	'db_host' => 'Database host',
	'db_name' => 'Database name',
	'db_username' => 'User name',
	'db_password' => 'Password',
	'db_port' => 'Server port',
	'db_tablepre' => 'Table prefix',
	'db_tablepre_notice' => 'Prefix to avoid replacing old data is deleted',
	
	'admin_setting' => 'Admin settings',
	'admin_name' => 'User name',
	'admin_password' => 'Password',
	'admin_password2' => 'Repeat password',
	
	'optional' => 'Optional',
	'email_setting' => 'Mail settings',
	'email_host' => 'Mail host',
	'email_from' => 'Email address to send',
	'email_fromname' => 'Mail send name',
	'email_username' => 'User name',
	'email_password' => 'Password',
	'email_port' => 'Port',
	
	'err_username_length' => 'administrator name must be greater than three characters in length and less than 15 characters',
	'err_password1' => 'twice for the administrator password is not the same',
	'err_password2' => 'administrator password can only use letters and numbers,the length must be greater than 5 and less than 33 characters',
	'err_email'=>'E-mail address must be entered',
	
	'err_host' => 'host name can not be empty',
	'err_name' => 'database name can not be empty',
	'err_username' => 'database user name can not be empty',
	'err_password' => 'database password can not be empty',
	'err_tablepre' => 'table prefix can not be empty',
	
	'err_webname' => 'web site name can not be empty',
	'err_weburl' => 'web site address can not be empty',
	
	'ucenter_pwurl_empty'=>'Ucenter links and password can not be empty',
	'ucenter_ip_notice'=>'ip generally empty',		
	'ucenter_url_unreachable' => 'UCenter the URL address may be complete wrong,please check',
	'ucenter_admin_invalid' => 'UCenter founder password error,please re-fill',
	'ucenter_data_invalid' => 'communication failure,check UCenter the URL address is correct',
	'ucenter_dbcharset_incorrect' => 'UCenter database character set character set consistent with the current applications',
	'ucenter_api_add_app_error' => 'add to UCenter application error',
	'ucenter_dns_error' => 'UCenter DNS resolution error,please return to fill out the IP address UCenter',
	
	'ucenter_ucurl_invalid' => 'UCenter the URL is empty or wrong format,please check',
	'ucenter_ucpw_invalid' => 'UCenter the founder of the password is blank,or formatting errors,please check',
	'ucenter_version_incorrect' => 'Your version is too low UCenter server,please upgrade UCenter service ended to the latest version,download address: http://www.comsenz.com/. ',	
	
	'app_reg_success'=>'application to add the successful',
	'config_unwriteable' => 'Setup Wizard can not write configuration file,set config_sys.php program can write the state property (777)',

	'admin_add_err'=>'add admin error',
	'admin_username_invalid' => 'illegal user name,user name length should not be more than 15 English characters,and can not contain special characters,usually Chinese,letters or numbers',
	'admin_password_invalid' => 'password and the above discrepancies,please re-enter',
	'admin_email_invalid' => 'Email address error,the e-mail address is being used or the format is invalid,please change to other addresses',
	'admin_invalid' => 'Your message did not fill out complete information administrator,please carefully fill out each item',
	'admin_exist_password_error' => 'This user already exists,if you want to set this user forum administrator,to enter the user\'s password,or replace the forum administrator\'s name',

	'password' => 'password',
	'uc_ipnotice' => 'can be empty',
	'userengine' => 'user-driven',
	'uengine_type' => 'drive type',
	'uengine_selectnotice' => 'Discuz if the need to integrate the company\'s products such as Sing,select Ucenter. ',
	
	'article_poll'=>'Published votes',
	'category_default'=>'Default Category',

	'timezone'=>'Time Zone',
);

?>