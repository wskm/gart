<?php 

if(!defined('INSTALL_ART')) { 
	exit('Access Denied');
} 

return array( 
'lang'=>'繁體中文版', 
'page_title'=>'Gart安裝嚮導', 
'step_title_0'=>'歡迎進入Gart安裝嚮導', 
'step_title_1'=>'1.環境檢測', 
'step_title_2'=>'2.用戶驅動', 
'step_title_3'=>'3.系統設置', 
'step_title_4'=>'4.創建配置', 
'step_title_5'=>'恭喜，安裝成功！ ', 

'installed'=>'您要重新安裝，必須先刪除./cache/install.lock文件', 

'agreement_no'=>'我不同意', 
'agreement_yes'=>'我同意', 
'step_up'=>'上一步', 
'step_next'=>'下一步', 

'step_error'=>'請檢查環境配置，刷新後重試', 
'license_title'=>'Gart中文授權協議', 
'license'=>'授權協議 
版權所有(c)2010，wskms.com 保留所有權利。 

感謝您選擇Gart ，我們會努力為您提供一個強大的、美好的建站系統。 

Gart是由WSKM工作室（WSKM Studio）開發製作，獨立擁有Gart 的產品著作權。 
官方網址是 www.wskms.com 。 

本授權協議適用且僅適用於Gart 1.xx 版本，Gart 官方對本授權協議的最終解釋權。 

一、協議許可的權利 
1、您可以在完全遵守本最終用戶授權協議的基礎上，將本軟件應用於非商業用途，而不必支付軟件版 
權授權費用。 

2、您可以在協議規定的約束和限制範圍內修改Gart 源代碼或界面風格以適應您的網站要求。 

3、您擁有使用本軟件構建的網站全部內容所有權，並獨立承擔與這些內容的相關法律義務。 

4、獲得商業授權之後，您可以將本軟件應用於商業用途，同時依據所購買的授權類型中確定的技術支 
持內容，自購買時刻起，在技術支持期限內擁有通過指定的方式獲得指定範圍內的技術支持服務。商業 
授權用戶享有反映和提出意見的權力，相關意見將被作為首要考慮，但沒有一定被採納的承諾或保證。 

二、協議規定的約束和限制 
1、未獲商業授權之前，不得將本軟件用於商業用途（包括但不限於企業網站、經營性網站、以營利為 
目的或實現盈利的網站）。購買商業授權請登陸www.wskms.com 了解詳情。 

2、未經官方許可，不得對本軟件或與之關聯的商業授權進行出租、出售、抵押或發放子許可證。 

3、不管你的網站是否整體使用Gart ，還是部份欄目使用Gart，在你使用了Gart 的網站主頁上必須 
加上Gart 官方網址(www.wskms.com)的鏈接。 

4、未經官方許可，禁止在Gart 的整體或任何部分基礎上以發展任何派生版本、修改版本或第三方版 
本用於重新分發。 

5、如果您未能遵守本協議的條款，您的授權將被終止，所被許可的權利將被收回，並承擔相應法律責 
任。 

三、有限擔保和免責聲明 
1、本軟件及所附帶的文件是作為不提供任何明確的或隱含的賠償或擔保的形式提供的。 

2、用戶出於自願而使用本軟件，您必須了解使用本軟件的風險，在尚未購買產品技術服務之前，我們 
不承諾對免費用戶提供任何形式的技術支持、使用擔保，也不承擔任何因使用本軟件而產生問題的相關 
責任。 

3、電子文本形式的授權協議如同雙方書面簽署的協議一樣，具有完全的和等同的法律效力。您一旦開 
始確認本協議並安裝Gart，即被視為完全理解並接受本協議的各項條款，在享有上述條款授予的權力 
的同時，受到相關的約束和限制。協議許可範圍以外的行為，將直接違反本授權協議並構成侵權，我們 
有權隨時終止授權，責令停止損害，並保留追究相關責任的權力。 
', 

'env_sys'=>'系統環境', 
'php_version'=>'PHP 版本', 
'upload_file'=>'附件上傳', 
'gd_support'=>'GD庫', 
'mysql_support'=>'Mysql支持', 

'fsys_check'=>'目錄文件(讀寫權限)', 
'fun_check'=>'函數支持', 

'groupname_1'=>'管理員', 
'groupname_2'=>'網站編輯', 
'groupname_3'=>'貴賓', 
'groupname_4'=>'禁止訪問', 
'groupname_5'=>'網友', 
'groupname_6'=>'驗證會員', 
'groupname_7'=>'會員', 

'web_index'=>'網站首頁', 
'web_setting'=>'網站設置', 
'web_name'=>'網站名稱', 
'web_url'=>'網站域名', 
'web_url_notice'=>'如：www.qq.com', 
'web_basedir'=>'網站地址', 
'web_basedir_notice'=>'絕對地址或目錄路徑，根目錄則為空', 

'db_setting'=>'數據庫配置', 
'db_host'=>'數據庫主機', 
'db_name'=>'數據庫名', 
'db_username'=>'用戶名', 
'db_password'=>'密碼', 
'db_port'=>'服務器端口', 
'db_tablepre'=>'表前綴', 
'db_tablepre_notice'=>'更換前綴可以避免舊數據被刪除', 

'admin_setting'=>'管理員設置', 
'admin_name'=>'用戶名', 
'admin_password'=>'密碼', 
'admin_password2'=>'重複密碼', 

'optional'=>'可選', 
'email_setting'=>'郵箱設置', 
'email_host'=>'郵箱主機', 
'email_from'=>'郵箱發送地址', 
'email_fromname'=>'郵箱發送名', 
'email_username'=>'用戶名', 
'email_password'=>'密碼', 
'email_port'=>'端口', 

'err_username_length'=>'管理員名長度必須大於3個字符且小於15個字符', 
'err_password1'=>'兩次輸入的管理員密碼不相同', 
'err_password2'=>'管理員密碼只能使用字母和數字組成,長度必須大於5個且小於33個字符', 
'err_email'=>'必須輸入郵箱地址', 

'err_host'=>'主機名不能為空', 
'err_name'=>'數據庫名不能為空', 
'err_username'=>'數據庫用戶名不能為空', 
'err_password'=>'數據庫密碼不能為空', 
'err_tablepre'=>'數據表前綴不能為空', 

'err_webname'=>'網站名稱不能為空', 
'err_weburl'=>'網站地址不能為空', 

'ucenter_pwurl_empty'=>'Ucenter的鏈接和密碼不能為空', 
'ucenter_ip_notice'=>'ip一般可以為空', 
'ucenter_url_unreachable' => 'UCenter 的URL 地址可能填寫錯誤，請檢查', 
'ucenter_admin_invalid' => 'UCenter 創始人密碼錯誤，請重新填寫', 
'ucenter_data_invalid' => '通信失敗，請檢查UCenter 的URL 地址是否正確', 
'ucenter_dbcharset_incorrect' => 'UCenter 數據庫字符集與當前應用字符集不一致', 
'ucenter_api_add_app_error' => '向UCenter 添加應用錯誤', 
'ucenter_dns_error' => 'UCenter DNS解析錯誤，請返回填寫一下UCenter 的IP地址', 

'ucenter_ucurl_invalid' => 'UCenter 的URL為空，或者格式錯誤，請檢查', 
'ucenter_ucpw_invalid' => 'UCenter 的創始人密碼為空，或者格式錯誤，請檢查', 
'ucenter_version_incorrect' => '您的UCenter 服務端版本過低，請升級UCenter 服務端到最新版本，下載地址：http://www.comsenz.com/ 。 ', 

'app_reg_success'=>'應用添加成功', 
'config_unwriteable' => '安裝嚮導無法寫入配置文件, 請設置config_sys.php 程序屬性為可寫狀態(777)', 

'admin_add_err'=>'添加管理員錯誤', 
'admin_username_invalid' => '非法用戶名，用戶名長度不應當超過15 個英文字符，且不能包含特殊字符，一般是中文，字母或者數字', 
'admin_password_invalid' => '密碼和上面不一致，請重新輸入', 
'admin_email_invalid' => 'Email 地址錯誤，此郵件地址已經被使用或者格式無效，請更換為其他地址', 
'admin_invalid' => '您的信息管理員信息沒有填寫完整，請仔細填寫每個項目', 
'admin_exist_password_error' => '該用戶已經存在，如果您要設置此用戶為論壇的管理員，請正確輸入該用戶的密碼，或者請更換論壇管理員的名字', 

'password'=>'密碼', 
'uc_ipnotice'=>'可以為空', 
'userengine'=>'用戶驅動', 
'uengine_type'=>'驅動類型', 
'uengine_selectnotice'=>'如果需要整合Discuz等康盛公司的產品，請選擇Ucenter。 ', 

'article_poll'=>'發佈投票',
'category_default'=>'默認分類',

'timezone'=>'時區',
); 

?>