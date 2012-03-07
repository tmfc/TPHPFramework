<?php
require_once('db.php');
require_once('memcached.php');

//取得常量信息
function GetConstList()
{
	$cachekey = "CACHE_SYS_CONST";
	$data = GetFromCache($cachekey);
	if($data == __CACHE_NULL)
		return null;
	//如果没有找到缓存
	if ( !$data ) {
		$mdb2 = GetMDB2();
	
		$sql = 'SELECT * FROM sys_const;';
		$data = ExecuteQuery($mdb2,$sql);

		AddToCache($cachekey,$data,__HOURS_72);
	}
	return $data;
}

$constList = GetConstList();
for($i = 0;$i < count($constList);$i ++)
{
	define($constList[$i]['key'],$constList[$i]['value']);
}
?>