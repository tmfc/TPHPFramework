<?php
require_once(dirname(__FILE__) ."/conf/config.php");
require_once(dirname(__FILE__) ."/const.php");
require_once(dirname(__FILE__) ."/func.template.php");
require_once(dirname(__FILE__) ."/func.format.php");
require_once(dirname(__FILE__) ."/func.utilities.php");
require_once(dirname(__FILE__) ."/func.session.php");


//启动Session
session_start();

$smarty->left_delimiter = '<';
$smarty->right_delimiter = '>';

header("Content-type: text/plain");
//判断是否是ie6
if(strpos($_SERVER['HTTP_USER_AGENT'],"MSIE 6.0") > 0)
	Assign("IsIE6",true);


function Assign($key,$value)
{
	global $smarty;
	$smarty->assign($key,$value);
}

function DisplayAjaxPage($template,$cachekey = NULL)
{
	global $smarty;
	header("Content-type: text/plain");
	if($cachekey == NULL)
		$smarty->display($template);
	else
		$smarty->display($template,$cachekey);
}

function FetchAjaxPage($template,$cachekey = NULL)
{
	global $smarty;
	if($cachekey == NULL)
		return $smarty->fetch($template);
	else
		return $smarty->fetch($template,$cachekey);
}

$IsGet = ($_SERVER['REQUEST_METHOD'] == 'GET');
$IsPost = ($_SERVER['REQUEST_METHOD'] == 'POST');

//获取POST数据，返回过滤后的数据
function GetPostData($key,$needFilter=true)
{
//	global $_POST;
	$data = $_POST[$key];
	
	if ($needFilter) 
	{
		//filter
		$data = htmlspecialchars($data);
	}
	
	return $data;
}
?>