<?php
require_once(dirname(__FILE__) ."/const.php");
require_once(dirname(__FILE__) ."/memcached.php");
require_once(dirname(__FILE__) ."/data.common.php");

//--------------------清除多个缓存的函数（括号内为清除条件）---------------------//

//清除分类信息（后台：更新了分类信息）
function CleanSubjectInfo($id,$aka,$pid)
{
	$r1 = CleanSubjectInfoByID($id)?1:0;
	$r2 = CleanSubjectInfoByAka($aka)?2:0;
	$r3 = CleanSubjectListByPid($pid)?4:0;
	$r4 = CleanSubjectList()?8:0;
	return ($r1 + $r2 + $r3 + $r4);
}

//清除地理信息（后台：更新了地理信息）
function CleanGeoInfo($pid,$pid2,$aka,$gtid)
{
	$r1 = CleanGeoInfoByAka($aka,$gtid)?1:0;
	$r2 = CleanDistrictList($pid)?2:0;
	$r2 = CleanProvinceCityList()?4:0;
	return ($r1 + $r2 + $r3);
}

//--------------------分类信息相关---------------------//
//通过id清除分类信息
function CleanSubjectInfoByID($id)
{
	$cachekey = GetCacheKey(cache_subject_info_by_id,array('id' => $id));
	return CleanMC($cachekey);
}

//通过aka清除分类信息
function CleanSubjectInfoByAka($aka)
{
	$cachekey = GetCacheKey(cache_subject_info_by_aka,array('aka' => $aka));
	return CleanMC($cachekey);
}
//通过pid清除子分类列表
function CleanSubjectListByPid($pid)
{
	$cachekey = GetCacheKey(cache_subject_list_by_pid,array('pid' => $pid));
	return CleanMC($cachekey);
}
//清除所有分类列表
function CleanSubjectList()
{
	$cachekey = cache_subject_list;
	return CleanMC($cachekey);
}
//--------------------Case信息相关---------------------//
function CleanCaseInfoCache($cid)
{
	$cachekey = GetCacheKey(cache_case_info,array("cid" => $cid));
	return CleanMC($cachekey);
}
//--------------------tag相关---------------------//
function CleanTagList()
{
	$cachekey = cache_tag_list;
	return CleanMC($cachekey);
}

function CleanSubjectTagList()
{
	$cachekey = cache_subject_tag_list;
	return CleanMC($cachekey);
}
//--------------------地理信息相关---------------------//
//根据aka清除地理信息
function CleanGeoInfoByAka($aka,$gtid)
{
	$cachekey = GetCacheKey(cache_geoinfo_by_aka,array('aka' => $aka,'gtid' => $gtid));
	return CleanMC($cachekey);
}
//根据城市id清除区县列表
function CleanDistrictList($cid)
{
	$cachekey = GetCacheKey(cache_district_list,array('cid' => $cid));
	return CleanMC($cachekey);
}
//清除省市列表
function CleanProvinceCityList()
{
	$cachekey = GetCacheKey(cache_province_city_list,array('cid' => $cid));
	return CleanMC($cachekey);
}

//--------------------关于信息相关---------------------//
//清除About信息（后台：更新了About信息）
function CleanAboutInfo($type)
{
	$cachekey = GetCacheKey(cache_about,array('type' => $type));
	return CleanMC($cachekey);
}
//--------------------友情链接相关---------------------//
//清除友情链接信息（后台：更新了友情链接）
function CleanLinksCache($cid)
{
	$subjectlist = GetSubjectList();
	for($i = 0;$i < count($subjectlist);$i ++)
	{
		$cachekey = GetCacheKey(cache_links,array('cityid' => $cid,'subjectid' => $subjectlist[$i]['id']));
		CleanMC($cachekey);
	}
	$cachekey = GetCacheKey(cache_links,array('cityid' => $cid,'subjectid' => 0));
	CleanMC($cachekey);
	
	return true;	
}
function CleanAllLinksCache()
{
	$citylist = GetDistrictList();
	for($i = 0;$i < count($citylist);$i ++)
	{
		CleanLinksCache($citylist['id']);
	}
	CleanLinksCache(0);
	return true;
}
//--------------------黑名单相关---------------------//
function CleanBlackList()
{
	return CleanMC(cache_blacklist);
}
//--------------------常量相关---------------------//
function CleanConst()
{
	return CleanMC("CACHE_SYS_CONST");
}

?>