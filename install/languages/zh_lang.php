<?php

if(!defined('INSTALL_ART')) {	
	exit('Access Denied');
}

return array(
	'lang'=>'简体中文版',
	'page_title'=>'Gart安装向导',
	'step_title_0'=>'欢迎进入Gart安装向导',
	'step_title_1'=>'1.环境检测',
	'step_title_2'=>'2.用户驱动',
	'step_title_3'=>'3.系统设置',
	'step_title_4'=>'4.创建配置',
	'step_title_5'=>'恭喜，安装成功！',
	
	'installed'=>'您要重新安装，必须先删除./cache/install.lock文件',
	
	'agreement_no'=>'我不同意',
	'agreement_yes'=>'我同意',
	'step_up'=>'上一步',
	'step_next'=>'下一步',
	
	'step_error'=>'请检查环境配置，刷新后重试',
	'license_title'=>'Gart中文授权协议',
	'license'=>'授权协议
版权所有 (c)2010，wskms.com 保留所有权利。 

感谢您选择 Gart ，我们会努力为您提供一个强大的、美好的建站系统。

Gart是由WSKM工作室（WSKM Studio）开发制作，独立拥有 Gart 的产品著作权。
官方网址是 www.wskms.com 。

本授权协议适用且仅适用于 Gart 1.x.x 版本，Gart 官方对本授权协议的最终解释权。 

一、协议许可的权利 
1、您可以在完全遵守本最终用户授权协议的基础上，将本软件应用于非商业用途，而不必支付软件版
权授权费用。 

2、您可以在协议规定的约束和限制范围内修改 Gart 源代码或界面风格以适应您的网站要求。 

3、您拥有使用本软件构建的网站全部内容所有权，并独立承担与这些内容的相关法律义务。 

4、获得商业授权之后，您可以将本软件应用于商业用途，同时依据所购买的授权类型中确定的技术支
持内容，自购买时刻起，在技术支持期限内拥有通过指定的方式获得指定范围内的技术支持服务。商业
授权用户享有反映和提出意见的权力，相关意见将被作为首要考虑，但没有一定被采纳的承诺或保证。 

二、协议规定的约束和限制 
1、未获商业授权之前，不得将本软件用于商业用途（包括但不限于企业网站、经营性网站、以营利为
目的或实现盈利的网站）。购买商业授权请登陆 www.wskms.com 了解详情。

2、未经官方许可，不得对本软件或与之关联的商业授权进行出租、出售、抵押或发放子许可证。

3、不管你的网站是否整体使用 Gart ，还是部份栏目使用 Gart，在你使用了 Gart 的网站主页上必须
加上 Gart 官方网址(www.wskms.com)的链接。

4、未经官方许可，禁止在 Gart 的整体或任何部分基础上以发展任何派生版本、修改版本或第三方版
本用于重新分发。

5、如果您未能遵守本协议的条款，您的授权将被终止，所被许可的权利将被收回，并承担相应法律责
任。 

三、有限担保和免责声明 
1、本软件及所附带的文件是作为不提供任何明确的或隐含的赔偿或担保的形式提供的。 

2、用户出于自愿而使用本软件，您必须了解使用本软件的风险，在尚未购买产品技术服务之前，我们
不承诺对免费用户提供任何形式的技术支持、使用担保，也不承担任何因使用本软件而产生问题的相关
责任。 

3、电子文本形式的授权协议如同双方书面签署的协议一样，具有完全的和等同的法律效力。您一旦开
始确认本协议并安装 Gart，即被视为完全理解并接受本协议的各项条款，在享有上述条款授予的权力
的同时，受到相关的约束和限制。协议许可范围以外的行为，将直接违反本授权协议并构成侵权，我们
有权随时终止授权，责令停止损害，并保留追究相关责任的权力。
',
	
	'env_sys'=>'系统环境',
	'php_version'=>'PHP 版本',
	'upload_file'=>'附件上传',
	'gd_support'=>'GD库',
	'mysql_support'=>'Mysql支持',
	
	'fsys_check'=>'目录文件(读写权限)',
	'fun_check'=>'函数支持',
	
	'groupname_1'=>'管理员',
	'groupname_2'=>'网站编辑',
	'groupname_3'=>'贵宾',
	'groupname_4'=>'禁止访问',
	'groupname_5'=>'网友',
	'groupname_6'=>'验证会员',
	'groupname_7'=>'会员',
	
	'web_index'=>'网站首页',
	'web_setting'=>'网站设置',
	'web_name'=>'网站名称',
	'web_url'=>'网站域名',
	'web_url_notice'=>'如：www.test.com',
	'web_basedir'=>'网站地址',
	'web_basedir_notice'=>'绝对地址或目录路径，一般请默认',
	
	'db_setting'=>'数据库配置',
	'db_host'=>'数据库主机',
	'db_name'=>'数据库名',
	'db_username'=>'用户名',
	'db_password'=>'密码',
	'db_port'=>'服务器端口',
	'db_tablepre'=>'表前缀',
	'db_tablepre_notice'=>'更换前缀可以避免旧数据被删除',
	
	'admin_setting'=>'管理员设置',
	'admin_name'=>'用户名',
	'admin_password'=>'密码',
	'admin_password2'=>'重复密码',
	
	'optional'=>'可选',
	'email_setting'=>'邮箱设置',
	'email_host'=>'邮箱主机',
	'email_from'=>'邮箱发送地址',
	'email_fromname'=>'邮箱发送名',
	'email_username'=>'用户名',
	'email_password'=>'密码',
	'email_port'=>'端口',
	
	'err_username_length'=>'管理员名长度必须大于3个字符且小于15个字符',
	'err_password1'=>'两次输入的管理员密码不相同',
	'err_password2'=>'管理员密码只能使用字母和数字组成,长度必须大于5个且小于33个字符',
	'err_email'=>'必须输入邮箱地址',
	
	'err_host'=>'主机名不能为空',
	'err_name'=>'数据库名不能为空',
	'err_username'=>'数据库用户名不能为空',
	'err_password'=>'数据库密码不能为空',
	'err_tablepre'=>'数据表前缀不能为空',
	
	'err_webname'=>'网站名称不能为空',
	'err_weburl'=>'网站地址不能为空',
	
	'ucenter_pwurl_empty'=>'Ucenter的链接和密码不能为空',
	'ucenter_ip_notice'=>'ip一般可以为空',		
	'ucenter_url_unreachable' => 'UCenter 的 URL 地址可能填写错误，请检查',
	'ucenter_admin_invalid' => 'UCenter 创始人密码错误，请重新填写',
	'ucenter_data_invalid' => '通信失败，请检查 UCenter 的URL 地址是否正确 ',
	'ucenter_dbcharset_incorrect' => 'UCenter 数据库字符集与当前应用字符集不一致',
	'ucenter_api_add_app_error' => '向 UCenter 添加应用错误',
	'ucenter_dns_error' => 'UCenter DNS解析错误，请返回填写一下 UCenter 的 IP地址',
	
	'ucenter_ucurl_invalid' => 'UCenter 的URL为空，或者格式错误，请检查',
	'ucenter_ucpw_invalid' => 'UCenter 的创始人密码为空，或者格式错误，请检查',
	'ucenter_version_incorrect' => '您的 UCenter 服务端版本过低，请升级 UCenter 服务端到最新版本，下载地址：http://www.comsenz.com/ 。',	
	
	'app_reg_success'=>'应用添加成功',
	'config_unwriteable' => '安装向导无法写入配置文件, 请设置 config_sys.php 程序属性为可写状态(777)',

	'admin_add_err'=>'添加管理员错误',
	'admin_username_invalid' => '非法用户名，用户名长度不应当超过 15 个英文字符，且不能包含特殊字符，一般是中文，字母或者数字',
	'admin_password_invalid' => '密码和上面不一致，请重新输入',
	'admin_email_invalid' => 'Email 地址错误，此邮件地址已经被使用或者格式无效，请更换为其他地址',
	'admin_invalid' => '您的信息管理员信息没有填写完整，请仔细填写每个项目',
	'admin_exist_password_error' => '该用户已经存在，如果您要设置此用户为论坛的管理员，请正确输入该用户的密码，或者请更换论坛管理员的名字',
	
	'password'=>'密码',
	'uc_ipnotice'=>'可以为空',
	'userengine'=>'用户驱动',
	'uengine_type'=>'驱动类型',
	'uengine_selectnotice'=>'如果需要整合Discuz等康盛公司的产品，请选择Ucenter。',
	
	'article_poll'=>'发布投票',
	'category_default'=>'默认分类',
	
	'timezone'=>'时区',
);

?>