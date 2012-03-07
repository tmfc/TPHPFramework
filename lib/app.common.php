<?php
require_once(dirname(__FILE__) ."/data.common.php");
require_once(dirname(__FILE__) ."/func.utilities.php");
require_once(dirname(__FILE__) ."/func.cookie.php");

//根据ip地址获得城市信息
function GetCityByIP($IPAddress)
{
	$IPNum = IPtoNum($IPAddress);
	return _GetCityByIP($IPNum);
}

//保存用户所在城市Cookie
function SaveUserCityCookie($cityurl)
{
	//cookie保存90天
	$ExpireTime = time() + 86400*90;
	setcookie(__COK_USER_CITY, $cityurl, $ExpireTime, '/', GetCookieDomain());
}

//获取用户所在城市Cookie
function GetUserCityCookie()
{
	if(array_key_exists(__COK_USER_CITY,$_COOKIE))
		return $_COOKIE[__COK_USER_CITY];
	else
		return null;
}

//保存用户所在城市Cookie
function SaveUserDistrictCookie($did)
{
	//cookie保存90天
	$ExpireTime = time() + 86400*90;
	setcookie(__COK_USER_DISTRICT, $did, $ExpireTime, '/', GetCookieDomain());
}

//获取用户所在城市Cookie
function GetUserDistrictCookie()
{
	if(array_key_exists(__COK_USER_DISTRICT,$_COOKIE))
		return $_COOKIE[__COK_USER_DISTRICT];
	else
		return null;
}

//通过$_SERVER['HOST_NAME']获取所在城市
function GetCurrentCity()
{
	return substr($_SERVER['HTTP_HOST'],0,strpos($_SERVER['HTTP_HOST'],'.'));
}

//获取按省分组的城市
function GetCityGroupByProvince()
{
	$provinceAndCityList = GetProvinceAndCityList();
	
	$provinceHT = array();
	for($i = 0;$i < count($provinceAndCityList);$i ++)
	{
		$gtid = $provinceAndCityList[$i]['gtid'];
		if($gtid == __GEO_TYPE_PROVINCE)
		{
			$id = $provinceAndCityList[$i]['id'];
			$provinceHT[$id] = $provinceAndCityList[$i];
		}
	}
	for($i = 0;$i < count($provinceAndCityList);$i ++)
	{
		$gtid = $provinceAndCityList[$i]['gtid'];
		if($gtid == __GEO_TYPE_CITY)
		{		
			$pid = $provinceAndCityList[$i]['pid'];
			if(!array_key_exists('cities',$provinceHT[$pid]))
			{
				$provinceHT[$pid]['cities'] = array();
			}
			
			$provinceHT[$pid]['cities'][] = $provinceAndCityList[$i];
		}
	}
	return $provinceHT;
}

//根据aka获取城市信息
function GetCityByAka($aka)
{
	return GetGeoInfoByAka($aka,__GEO_TYPE_CITY);
}

//根据id获取城市信息
function GetCityByID($id)
{
	return GetGeoInfoByID($id);
}
//根据aka获取区县信息
function GetDistrictByAka($aka)
{
	return GetGeoInfoByAka($aka,__GEO_TYPE_DISTRICT);
}


?>