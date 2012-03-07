<?php
//格式化相关
//----------

//取得距离描述
function GetDistanceDescription($distance) {
	
	if($distance > 1000)
	{
		return bcdiv($distance , 1000) . '公里';
	}
	else 
	{
		return ceil($distance / 100) * 100 . '米';
	}
}

//取得日期描述
function GetDateDescription($date) {
	date_default_timezone_set('Asia/Shanghai');
	
	$timedifference = time() - strtotime($date);
	
	if($timedifference >= 1296000)
	{
		return date("Y-m-d", strtotime($date));
	}
	elseif($timedifference >= 86400)
	{
		return bcdiv($timedifference,86400) . '天前';
	}
	elseif($timedifference >= 3600)
	{
		return bcdiv($timedifference,3600) . '小时前';
	}
	elseif($timedifference >= 60)
	{
		return bcdiv($timedifference,60) . '分钟前';
	}
	else 
	{
		return '一分钟前';
	}
}

//取得可用描述
function GetAvailableDescription($Available)
{
	switch($Available)
	{
		case __AVAILABLE:
			return "可用";
		case __UNAVAILABLE:
			return "不可用";
		default:
			return null;
	}		
}

//把时间转成类似（两小时前,昨天2：45）格式
function FormatTimeAgo($time)
{
	date_default_timezone_set('Asia/Shanghai');
	
	if(gettype($time) == 'string')
		$time = strtotime($time);
	
	$timedifference = time() - $time;

	$today = getdate(time());
	$todayTime = mktime(0,0,0,$today['mon'],$today['mday'],$today['year']);
	$todaytimedifference = $todayTime - $time;
	if($todaytimedifference >= 172800)
	{
		return date("Y-m-d H:i", $time);
	}
	elseif($todaytimedifference >= 86400)
	{
		return '前天 ' . date("H:i", $time);
	}
	elseif($todaytimedifference >= 0)
	{
		return '昨天 '  . date("H:i", $time);
	}
	elseif($timedifference >= 3600)
	{
		return bcdiv($timedifference,3600) . '小时前';
	}
	elseif($timedifference >= 60)
	{
		return bcdiv($timedifference,60) . '分钟前';
	}
	else 
	{
		return '一分钟前';
	}
}


//格式化数组中指定文本字段的长度
function TitleLengthFormat($array, $column, $length, $postfix = '...')
{
	$count = count($array);
	
	for($i = 0; $i < $count; $i++)
	{
		if(mb_strlen($array[$i][$column], "utf-8") > $length)
		{
			$array[$i][$column] = mb_substr($array[$i][$column],0,$length,"utf-8") . $postfix;
		}
	}
	
	return $array;
}

//通过生日获得用户星座名称
function GetConstellationName($month, $day)
{   
    $staritem = "魔羯水瓶双鱼牧羊金牛双子巨蟹狮子处女天秤天蝎射手魔羯";
    $dayArr = array(20, 19, 21, 21, 21, 22, 23, 23, 23, 23, 22, 22);
    $index = 0;
    if ($day < $dayArr[$month - 1])
    	$index = 2;

    return mb_substr($staritem,($month*2 - $index),2,'utf-8');
}

//通过生日获得用户星座ID
function GetConstellationID($month, $day)
{
    $dayArr = array( 20, 19, 21, 21, 21, 22, 23, 23, 23, 23, 22, 22 );
    $index = 0;
    if ($day < $dayArr[$month - 1])
    	$index = 2;
    
    $id =($month * 2 - $index)/2 + 1 - 3;
    if($id <= 0)
    	$id += 12;
    	
    return $id;
}

//获取用户性别描述
function GetGenderText($gender)
{
	$gender = ($gender==1)?"他":"她";
	return $gender;
}

/**
 * 获取较短的用户名
 *
 * @param string $name
 * @param int $maxlength
 * @param int $showlength
 * @param string $suffix
 * @return unknown
 */
function GetUserShortName($name, $maxlength, $showlength, $suffix='...')
{
	return (mb_strlen($name,'utf-8') > $maxlength) ? (mb_substr($name,0,$showlength,'utf-8') . $suffix) : $name;
}

?>