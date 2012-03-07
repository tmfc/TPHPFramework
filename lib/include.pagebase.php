<?php
/*此页面将本项目专用的逻辑和pagebase分开，保证pagebase的通用性*/
require_once(dirname(__FILE__) ."/func.utilities.php");
require_once(dirname(__FILE__) ."/app.common.php");

IncludeJS('suggest');
IncludeJS('global');

$CurrentCityURL = GetCurrentCity();

if($CurrentCityURL == 'this')
{
	//$CurrentCityURL = "shanghai";
	$CurrentCityURL = "www";
}
//判断用户访问的是否是www.wolai123.com
if($CurrentCityURL == 'www')
{
	//判断用户是否有当前城市Cookie
	$userCity = GetUserCityCookie();

	//没有当前城市Cookie,根据IP获取城市信息
	if(empty($userCity))
	{
		$city = GetCityByIP(GetClientIP());
		
		if($city != null)
			$userCity = $city['aka'];
		else
			$userCity = __DEFAULT_CITY_URL;
	}
	//保存用户城市Cookie
	SaveUserCityCookie($userCity);
	//Location("http://" . $userCity . "." . __SITE_DOMAIN);
}


if($CurrentCityURL != 'www')
{
	$NoCity = false;
	Assign("NoCity",false);
	$CurrentCityInfo = GetCityByAka($CurrentCityURL);
	$CurrentCityID = $CurrentCityInfo['id'];
	$CurrentCityName = $CurrentCityInfo['name'];
	Assign("CurrentCityName", $CurrentCityName);
	Assign("CurrentCityURL", $CurrentCityURL);
}
else{
	$NoCity = true;
	Assign("NoCity",true);
	$GotoCityInfo = GetCityByAka($userCity);
	$GotoCityID = $GotoCityInfo['id'];
	$GotoCityName = $GotoCityInfo['name'];
	$GotoURL = $_SERVER['REQUEST_URI'];
	$CurrentCityID = 0;
	$CurrentCityName = "全国";
	Assign("GotoCityName", $GotoCityName);
	Assign("GotoCityURL", $userCity);
	Assign("GotoURL", $GotoURL);
}
$BreadCrumb = array();

function AddBreadCrumb($text,$link = null)
{
	global $BreadCrumb;
	if($link != null)
		$BreadCrumb[] = "<a href=\"{$link}\">{$text}</a>";
	else
		$BreadCrumb[] = $text;
}

function AddBreadCrumbIndex($show_link = true)
{
	AddBreadCrumb("首页",$show_link?"/":null);
}

function AdditionalInit()
{
	global $BreadCrumb;
	Assign("BreadCrumb",implode(" &gt; " , $BreadCrumb));
}
?>