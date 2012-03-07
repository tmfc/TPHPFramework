<?php
//获取ip地址所在城市/区县
function _GetCityByIP($IPNum)
{
	$mdb2 = GetMDB2();
	$sql = "SELECT C.* FROM ipaddress IP INNER JOIN geo C ON C.id = IP.cid WHERE " . $mdb2->quote($IPNum,'float') . " BETWEEN begin_num and end_num";
	return ExecuteOneRow($mdb2,$sql);
}

//根据aka获取地理位置信息
function GetGeoInfoByAka($aka,$gtid)
{
	$cachekey = GetCacheKey(cache_geoinfo_by_aka,array("aka" => $aka,"gtid" => $gtid));
	$data = GetFromCache($cachekey);
	if($data == __CACHE_NULL)
		return null;
	if ( !$data ) 
	{
		$mdb2 = GetMDB2();
		$sql = "SELECT * FROM geo WHERE aka=" . $mdb2->quote($aka,'text') . ' AND gtid=' . $mdb2->quote($gtid,'integer');
		$data = ExecuteOneRow($mdb2,$sql);
		AddToCache($cachekey,$data,__FOREVER);
	}
	
	return $data;
}

//根据id获取地理位置信息
function GetGeoInfoByID($id)
{
	$cachekey = GetCacheKey(cache_geoinfo_by_id,array("id" => $id));
	$data = GetFromCache($cachekey);
	if($data == __CACHE_NULL)
		return null;
	if ( !$data ) 
	{
		$mdb2 = GetMDB2();
		$sql = "SELECT * FROM geo WHERE id=" . $mdb2->quote($id,'text');

		$data = ExecuteOneRow($mdb2,$sql);
		AddToCache($cachekey,$data,__FOREVER);
	}
	
	return $data;
}


//获取所有省和城市列表
function GetProvinceAndCityList()
{
	$cachekey = cache_province_city_list;
	$data = GetFromCache($cachekey);

	if($data == __CACHE_NULL)
		return null;
	if ( !$data ) 
	{
		$mdb2 = GetMDB2();
		$sql = "SELECT * FROM geo WHERE available AND gtid IN (" . $mdb2->quote(__GEO_TYPE_PROVINCE,'integer') . "," . $mdb2->quote(__GEO_TYPE_CITY,'integer') . ") ORDER BY `order`;";
		$data = ExecuteQuery($mdb2,$sql);
		
		AddToCache($cachekey,$data,__FOREVER);
	}
	
	return $data;
}

//获取区县列表
function GetDistrictList($cid)
{
	$cachekey = GetCacheKey(cache_district_list,array("cid" => $cid));
	$data = GetFromCache($cachekey);
	if($data == __CACHE_NULL)
		return null;
	if ( !$data ) 
	{
		$mdb2 = GetMDB2();
		$sql = "SELECT * FROM geo WHERE available AND pid =" . $mdb2->quote($cid,'integer') ." AND gtid=" . $mdb2->quote(__GEO_TYPE_DISTRICT,'integer');
		$data = ExecuteQuery($mdb2,$sql);
		
		AddToCache($cachekey,$data,__FOREVER);
	}
	
	return $data;
}

//获取关于信息
function GetAboutInfo($type)
{
	$cachekey = GetCacheKey(cache_about,array('type' => $type));
	$data = GetFromCache($cachekey);
	if($data == __CACHE_NULL)
		return null;
	if ( !$data ) 
	{
		$mdb2 = GetMDB2();
		$sql = "SELECT * FROM about WHERE aka=" . $mdb2->quote($type,'text');

		$data = ExecuteOneRow($mdb2,$sql);
		
		AddToCache($cachekey,$data,__FOREVER);
	}
	
	return $data;
}

//获取黑名单列表
function GetBlacklist()
{
	$cachekey = cache_blacklist;
	$data = GetFromCache($cachekey);
	if($data == __CACHE_NULL)
		return null;
	if ( !$data ) 
	{
		$mdb2 = GetMDB2();
		$sql = "SELECT KEYWORD FROM wolai_search.SEARCH_BLACKLIST";

		$data = ExecuteCol($mdb2,$sql,0);
		
		AddToCache($cachekey,$data,__FOREVER);
	}
	
	return $data;
}

//获取友情链接
function GetLinks($cityid = 0,$subjectid = 0)
{
	$cachekey = GetCacheKey(cache_links,array('cityid'=>$cityid,'subjectid'=>$subjectid));
	$data = GetFromCache($cachekey);
	if($data == __CACHE_NULL)
		return null;
	if ( !$data ) 
	{
		$mdb2 = GetMDB2();
		$whereClause = array();
		if($cityid != 0)
			$whereClause[] = ("city_id = " . $mdb2->quote($cityid));
		else
			$whereClause[] = "city_id IS NULL";
		if($subjectid != 0)
			$whereClause[] = ("subject_id = " . $mdb2->quote($subjectid));
		else
			$whereClause[] = "subject_id IS NULL ";
			
		$sql = "SELECT * FROM links WHERE " . implode(' AND ',$whereClause);
		$data = ExecuteQuery($mdb2,$sql);
		
		AddToCache($cachekey,$data,__FOREVER);
	}
	
	return $data;
}
?>