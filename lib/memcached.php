<?php
require_once(dirname(__FILE__) ."/conf/config.php");

define('__CACHE_NULL','$^_NULL_^$');			//用来标识空对象的字符串
//缓存时间
define('__HOURS_1',3600);
define('__HOURS_3',10800);
define('__HOURS_5',18000);
define('__HOURS_24',86400);
define('__HOURS_72',259200);
define('__DAYS_7',604800);
define('__FOREVER',0);

/**
 * 获取Memcache对象
 *
 * @return memcache object
 */
function GetMemCache()
{
	global $MemCache_Servers;
	$memcache = new Memcache();
	
	if($MemCache_Servers){
		foreach ($MemCache_Servers as $server)
		{
			$memcache->addServer($server['host'], $server['port']);
		}
	}
	else
	{
		$memcache->addServer(__MEMCACHED_SERVER1, __MEMCACHED_SERVER1_PORT);
		$memcache->addServer(__MEMCACHED_SERVER2, __MEMCACHED_SERVER2_PORT);
	}
return $memcache;
}

/**
 * 从缓存中里面取一个对象
 */ 
function GetFromCache($key)
{
	$key = __CACHE_KEY_PREFIX . $key;
 	$memcache = GetMemCache();
	$data = $memcache->get($key);
	$memcache->close();
	return $data;
}
 
/**
 * 将某个对象加入缓存
 */ 
  function AddToCache($key,$value,$cachetime=0)
 {
 	$key = __CACHE_KEY_PREFIX . $key;
 	$memcache = GetMemCache();
 	if($value == null)
 		$result = $memcache->set($key,__CACHE_NULL,false,$cachetime);
 	else 
		$result = $memcache->set($key,$value,false,$cachetime);
	$memcache->close();
	return $result;
 }
 
 /**
 * 将缓存中某个数值进行自增
 */ 
 function IncrCacheValue($key,$value = 1)
 {
 	$key = __CACHE_KEY_PREFIX . $key;
	$memcache = GetMemCache();
	$result = $memcache->increment($key,$value);
	$memcache->close();
	return $result;
 }

//清除缓存
function CleanMC($key)
{
	$key = __CACHE_KEY_PREFIX . $key;

 	$memcache = GetMemCache();
	return $memcache->delete($key);
}
 /**
 * 获取缓存键函数
 */
 function GetCacheKey($key_template,$data)
 {
 	$keys = array_keys($data);
	for($j = 0;$j < count($keys);$j ++)
	{
		$keys[$j] = '{%' . $keys[$j] . '}';
	}
	$values = array_values($data);
	return str_replace($keys,$values,$key_template);
 }
 
/**
 * 获取过期时间函数
 */
function GetExpireSeconds($hours,$minutes)
{
	date_default_timezone_set('Asia/Shanghai');
	$now = time();
	$nowDate = getdate($now);	//转换为日期数组
	//如果超过了过期时间，设定过期日期为明天
	if($nowDate["hours"] > $hours || ($nowDate["hours"] == $hours && $nowDate["minutes"] >= $minutes) )
	{
		$expireDate = $now + 24 * 3600;
	}
	//否则过期日期为今天
	else
	{
		$expireDate = $now;
	}
	$expireDate = getdate($expireDate);
	//得到过期时间的时间戳
	$expireTime = mktime($hours,$minutes,0,$expireDate["mon"],$expireDate["mday"],$expireDate["year"]);
	return $expireTime - $now;
}
?>