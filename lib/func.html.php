<?php
//HTML相关函数
//-----------

//获取插入META的HTML
function GetMetaHTML($name, $content)
{
	return "<meta name=\"$name\" content=\"" . htmlspecialchars($content) . "\" />";
}

//包含CSS文件
$CSSInclude;
function IncludeCSS($csskey)
{
	global $CSSInclude,$LocalMachine;
	$imgserverurl = __STATIC_SERVER;
	if($LocalMachine)
	{
		$imgserverurl = '';
		$csskey = '/dev/' . $csskey;
	}
	else 
	{
		$csskey = '/css/' . $csskey;
	}
	$CSSInclude .= '<link rel="stylesheet" href="' . $imgserverurl . $csskey . '.css?version=' . __CSS_VERSION . '" type="text/css" media="screen" />' . "\n";
}

/*
 * 获取关键字列表字符串
 */
function GetKeywordsString()
{
	$args_count = func_num_args();
	$args = func_get_args();
	
	$keywords = '';
	
	if($args_count){
		for($i=0; $i<$args_count; $i++)
		{
			$keywords .= $args[$i] . ', ';	
		}
		
		return substr($keywords, 0, strlen($keywords) - 2);
	}
	else {
		return '';
	}
}

//获取指定的页数参数
function GetPage()
{
	$p = !empty($_GET['page']) ? (int)$_GET['page'] : 1;
	return $p < 1 ? 1 : $p;
}

//重定向到404错误页
function LocationPageNotFound()
{
	header(sprintf("Location: /page_not_found.php"));
	exit;
}

//重定向到指定页
function Location($url)
{
	header("Location: " . $url);
	exit;
}

//初始化年份列表
function InitYearList($startyear = NULL,$endyear = NULL,$insertAge = false)
{
	$theDate = getdate();
	if(!isset($startyear) )
		$startyear = $theDate['year'] - 100;
	if( !isset($endyear) )
		$endyear = $theDate['year'];

	$YearList = array();

	return $YearList;
}

//初始化月份列表
function InitMonthList()
{	
	return range(1,12);
}

//初始化日列表
function InitDayList()
{
	return range(1,31);
}


?>