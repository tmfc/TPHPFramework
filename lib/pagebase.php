<?php
//启动Session
session_start();

require_once(dirname(__FILE__) ."/conf/config.php");
//是否正在维护
if(__UNDER_MAINTAIN)
{
	echo '我来123正在进行网站数据维护，对您造成的不便深表歉意';
	exit();
}

require_once(dirname(__FILE__) ."/const.php");
require_once(dirname(__FILE__) ."/func.html.php");
require_once(dirname(__FILE__) ."/func.js.php");
require_once(dirname(__FILE__) ."/func.template.php");
require_once(dirname(__FILE__) ."/func.format.php");
require_once(dirname(__FILE__) ."/func.utilities.php");
require_once(dirname(__FILE__) ."/func.control.php");
require_once(dirname(__FILE__) ."/func.session.php");
require_once(dirname(__FILE__) ."/func.inputfilter.php");


//是否是本地测试环境
$LocalDev = false;
if(preg_match('/^192\.168(\.\d{1,3}){2}$/', $_SERVER['SERVER_ADDR']))
{
	$smarty->assign('LocalDev', 'true');
	$LocalDev = true;
}

//是否是本地开发环境
$LocalMachine = false;
if($_SERVER['SERVER_ADDR'] == '127.0.0.1' || $_SERVER['SERVER_ADDR'] == '192.168.0.23')
{
	$LocalMachine = true;
}
//判断是否是ie6
if(strpos($_SERVER['HTTP_USER_AGENT'],"MSIE 6.0") > 0)
	Assign('IsIE6', true);
	
//判断是GET请求还是POST请求
$IsGet = ($_SERVER['REQUEST_METHOD'] == 'GET');
$IsPost = ($_SERVER['REQUEST_METHOD'] == 'POST');


//包含主样式
IncludeCSS('main');

//包含必要的JS
IncludeMootools();
//IncludeGlobal();

//用户是否已经登录
$IsUserLogin = IsUserLogin();
Assign("IsUserLogin",$IsUserLogin);

//处理操作成功/失败
if($IsGet && isset($_GET['flag']))
{
	if(isset($_GET['stype']) && !empty($_GET['stype']))
		$stype = $_GET['stype'];
	else 
		$stype = null;
		
	switch($_GET['flag'])
	{
		case 1:
			$IsSuccess = true;
			$SuccessType = $stype;
			OpSuccess($stype);
			break;
		default:
			$IsFailed = true;
			$FailedType = $_GET['flag'];
			OpFailed($_GET['flag']);
			break;
	}
}

$PageTitle = '';

require_once(dirname(__FILE__) ."/include.pagebase.php");

/***
 * 设置网页 Title
 * 可添加多个关键字，用逗号分隔
 */
function SetPageTitle()
{
	global $PageTitle;
	$args_count = func_num_args();
	$args = func_get_args();
	
	if($args_count){
		$PageTitle = $args[0];
		for($i = 1; $i<$args_count; $i++)
		{
			$PageTitle .= ' ' . __TITLE_SPLIT_CHAR . ' ' . $args[$i];	
		}
		$PageTitle .= ' ' . __TITLE_SPLIT_CHAR . ' ' . __TITLE;
	}
	else {
		$PageTitle = '';
	}
}

$MetaKeywords = '';
/***
 * 设置网页 Meta Keywords
 */
function SetMetaKeyword($keywords)
{
	global $MetaKeywords;
	$MetaKeywords = GetMetaHTML("keywords",$keywords);
}

/***
 * 设置网页 Meta Description
 */
$MetaDescription = '';
function SetMetaDescription($description)
{
	global $MetaDescription;
	$MetaDescription = GetMetaHTML("description",$keywords);
}

$MetaRefresh = '';
/***
 * 设置网页 Meta 刷新
 */
function SetMetaRefresh($timeDelay,$url)
{
	global $MetaRefresh;
	$MetaRefresh = "<meta http-equiv=\"Refresh\" content=\"{$timeDelay}; url={$url}\" />";
}

$AdKeywords = '';
/***
 * 设置AFS广告关键字
 * 可添加多个关键字，用逗号分隔
 */
function SetAdKeywords()
{
	global $AdKeywords;
	$args_count = func_num_args();
	$args = func_get_args();
	
	if($args_count){
		$AdKeywords = $args[0];
		for($i = 1; $i<$args_count; $i++)
		{
			$AdKeywords .= ' ' . $args[$i];	
		}
	}
	else {
		$AdKeywords = '';
	}
	Assign('AdKeywords', $AdKeywords);
}

/***
 * 模板变量赋值
 */
function Assign($key, $value)
{
	global $smarty;
	$smarty->assign($key,$value);
}

//转跳到失败页面
function GoFailedPage($url,$failedFlag){
	if(strpos($url,'?') > 0)
		Location($url . '&flag=' . $failedFlag);
	else 
		Location($url . '?flag=' . $failedFlag);
}

//操作失败
function OpFailed($failType = null){
	Assign("Failed",true);
	if($failType != null)
		Assign("FailedType",$failType);
}

//转跳到成功页面
function GoSuccessPage($url,$params = ''){
	if(strpos($url,'?') > 0)
		Location($url . '&flag=1' . $params);
	else
		Location($url . '?flag=1' . $params);
}

//操作成功
function OpSuccess($successType = null){
	Assign("Success",true);
	if($successType != null)
		Assign("SuccessType",$successType);
}

//显示页面
function DisplayPage($template,$cachekey = NULL)
{
	global $smarty, $PageTitle, $CSSInclude, $JSInclude, $GMapInclude, $PngFixSelector, $MetaKeywords, $MetaDescription, $MetaRefresh;
	$smarty->assign('PageTitle', $PageTitle);
	$smarty->assign('IncludeScript', implode('',$JSInclude));
	$smarty->assign('IncludeStyle', $CSSInclude);
	$smarty->assign('IncludeMeta', $MetaKeywords . $MetaDescription . $MetaRefresh);
	
	//额外的初始化函数
	if(function_exists("AdditionalInit"))
		AdditionalInit();
	
	if($cachekey == NULL)
		$smarty->display($template);
	else
		$smarty->display($template,$cachekey);
	
}
//获取页面html代码
function FetchPage($template)
{
	global $smarty;
	return $smarty->fetch($template);
}

/***
 * 获取JS版本
 */
function insert_GetJSVersion()
{
	return __JS_FUNC_VERSION;
}

/***
 * 获取CSS版本
 */
function insert_GetCSSVersion()
{
	return __CSS_VERSION;
}

/***
 * 获取POST数据，返回过滤后的数据
 */
function GetPostData($key,$needFilter=true)
{
	if(isset($_POST[$key]))
	{
		$data = $_POST[$key];
		
		if ($needFilter) 
		{
			$data = htmlspecialchars($data);
		}
		
		return $data;
	}
	return null;
}
?>