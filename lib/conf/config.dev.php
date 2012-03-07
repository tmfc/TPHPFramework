<?php
//系统配置
//----------

//站点相关			
define('__SITE_URL', 'http://www.wolai123.com');
define('__SITE_DOMAIN', 'wolai123.com');
define('__SITE_ROOT', 'E:\wolai123\trunk\front');
define('__STATIC_SERVER', 'http://s.wolai123.com');
define('__COOKIE_DOMAIN', 'wolai123.com');
define('__IMAGE_ROOT', 'E:\wolai123\trunk\front\wolai-image');					
//Smarty相关
define('__SMARTY_TEMPLATE', __SITE_ROOT . '/template/');
define('__SMARTY_ROOT', 'E:\\smarty');
define('__SMARTY_COMPILE', __SMARTY_ROOT .'/wolai123_templates_c/');
define('__SMARTY_CONFIG', __SMARTY_ROOT .'/configs/');
define('__SMARTY_CACHE', 'E:\\smarty\\cache\\wolai123_cache\\');


//缓存相关
$MemCache_Servers = array(
					array('host'=>'192.168.100.36', 'port'=>'11211')//,
					//array('host'=>'192.168.0.223', 'port'=>'12121'),
					);

//数据库连接串
define('__DB_CONN_STR', 'mysql://www-wolai-2:zQ(u2FcK@9a@210.51.37.58/wolai123?charset=utf8');
?>