-------------------------------
 Gart
-------------------------------
Gart 是中国新一代开源的PHP内容管理系统（CMS），致力于满足用户优异的品质需求，可用于建设各行业门户网站。 

-------------------------------
 Gart 运行环境
-------------------------------
1. WWW 服务器，如 Apache、IIS 等  
2. PHP 5.x 及以上 
3. MySQL 5.x 及以上
4. GD 2.x 及以上

-------------------------------
 Gart 的安装
-------------------------------
1. 上传 upload 目录中的文件到服务器
2. 设置目录属性（windows 服务器不需设置）
   以下这些目录、文件需要可读写权限（777）
	./attachments/  
	./photo/  
	./cache/  
	./cache/backup/  
	./cache/data/  
	./cache/tpl/  
	./config/config_sys.php （安装后可去掉文件写权限）
	./uc_client/data/cache/	（不使用ucenter用户驱动，安装后可取消读写权限）
	./html/

3. 执行安装脚本
   请在浏览器中运行 install 程序（访问 http://您的域名/install/ ）
4. 参照页面提示安装，直至完毕
（安装后建议您删除install目录、更改后台目录名admin为其它名称，旧版本升级请查看update目录）


-------------------------------
 Gart 技术支持
-------------------------------
http://www.wskms.com
http://www.wskmphp.com

永久下载地址：
http://code.google.com/p/wskmphp/
http://sourceforge.net/projects/wskmphp/


