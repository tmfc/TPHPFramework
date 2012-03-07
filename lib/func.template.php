<?php
//模版相关函数
//----------
require_once(dirname(__FILE__) ."/conf/config.php");
require_once(__SMARTY_ROOT . '/libs/Smarty.class.php');

$smarty = new smarty();
$smarty->template_dir = __SMARTY_TEMPLATE;
$smarty->compile_dir = __SMARTY_COMPILE;
$smarty->config_dir = __SMARTY_CONFIG;
$smarty->cache_dir = __SMARTY_CACHE;
$smarty->use_sub_dirs = true;
$smarty->left_delimiter = '{';
$smarty->right_delimiter = '}';

?>