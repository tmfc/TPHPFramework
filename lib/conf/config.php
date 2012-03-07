<?php

if($_SERVER['SERVER_ADDR'] == '127.0.0.1' || $_SERVER['SERVER_ADDR'] == '')
{
	//加载本地配置文件
	require_once(dirname(__FILE__) ."/config.dev.php");
}
else if(preg_match('/^192\.168(\.\d{1,3}){2}$/', $_SERVER['SERVER_ADDR']))
{
//	加载测试服务器配置文件
	require_once(dirname(__FILE__) ."/config.local.php");
}
else
{ 
	//加载正式环境配置文件
	require_once(dirname(__FILE__) ."/config.product.php");
}

define('__CACHE_KEY_PREFIX','w123');
define('__USER_PASSWORD_PREFIX','wolai123-');

//站点相关
define('__SITE_TITLE', '');
define('__TITLE_SPLIT_CHAR', '-');

//Session Key
define('__SSN_USER', 'user');
define('__SSN_VALIDATE', 'validate');
define('__SSN_IS_ADMIN', 'isadmin');
//Cookie Key
define('__COK_USER_CITY',	'_wo123_u_city');
define('__COK_USER_ID',		'_wo123_u_id');
define('__COK_USER_PWD',	'_wo123_u_mp');
define('__COK_USER_DISTRICT',	'_wo123_u_district');

define('__UNDER_MAINTAIN', false);				//网站是否正在维护
define('__ADMIN_USER_ID', 9000);				//网站管理员的id

define('__CASE_LIMIT_NUM_PER_DAY', 5);		     //每天的发帖上限

?>