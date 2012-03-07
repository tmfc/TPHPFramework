<?php
//系统配置
//----------

//站点相关	
define('__SITE_URL', 'http://www.wolai123.com');
define('__SITE_DOMAIN', 'wolai123.com');
define('__SITE_ROOT', '/var/www/www.wolai123.com');
define('__STATIC_SERVER', 'http://s.wolai123.com');
define('__COOKIE_DOMAIN', 'wolai123.com');
define('__IMAGE_ROOT', '/wolai123_img');

//Smarty相关
define('__SMARTY_TEMPLATE', __SITE_ROOT . '/template/');
define('__SMARTY_ROOT', '/usr/share/wolai/smarty');
define('__SMARTY_COMPILE', __SMARTY_ROOT .'/wolai123_templates_c/');
define('__SMARTY_CONFIG', __SMARTY_ROOT .'/configs/');
define('__SMARTY_CACHE', '/data/smarty/wolai123_cache/');

//缓存相关
$MemCache_Servers = array(
					array('host'=>'192.168.0.11', 'port'=>'15000'),
					array('host'=>'192.168.0.11', 'port'=>'15001'),
					array('host'=>'192.168.0.11', 'port'=>'15002'),
					array('host'=>'192.168.0.11', 'port'=>'15003'),
					array('host'=>'192.168.0.11', 'port'=>'15004'),
					array('host'=>'192.168.0.11', 'port'=>'15005')
					);

//数据库连接串
define('__DB_CONN_STR', 'mysql://www-wolai123:GTbMw525iVgmbq@192.168.0.58/wolai123?charset=utf8');
?>