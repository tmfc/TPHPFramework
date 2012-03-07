<?php
//工具函数
//----------

//获取一个GUID
function GetGuid() {
	return md5(uniqid(rand()));
}

//产生一个随机密码
function RandomPassword($length) {
	$possible_charactors = "23456789abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ";
	$string = "";
	
	while(strlen($string)<$length) {
		$string .= substr($possible_charactors,(rand()%(strlen($possible_charactors))),1);
	}
	
	return($string);
}

//产生一个随机文件名
function RandomFileName($length) {
	$possible_charactors = "1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$string = "";
	
	while(strlen($string)<$length) {
		$string .= substr($possible_charactors,(rand()%(strlen($possible_charactors))), 1);
	}
	
	return($string);
}

//根据字符串生成一个由子母和数字组成的散列值
function GetHashKey($str)
{
	$str = md5($str) . '';

	$hash = 0;
	$n = strlen($str);
	for ($i = 0; $i <$n; $i ++)
	{
		$hash = bcadd($hash,bcadd(bcmul($hash,2),ord($str[$i])));
	}
	//echo $hash . " ";
	$hash = bcmod($hash,4294967291);
	//echo $hash . " ";
	return ChangeTo36Base(floatval($hash),7);
}

//将数字从10进制转换成36进制
function ChangeTo36Base($number,$minlength)
{
	$result = "";
	while ($number!=0)
	{
		$temp = bcmod($number,26);
		
		if ($temp >= 10)
		{
			$result.= chr(($temp+87));
		}
		else
		{
			$result.= $temp;
		}
		$number = floatval(bcdiv($number,26));
	}
	for($i = strlen($result);$i < $minlength;$i ++)
		$result.="0";
		
	//return strrev($result);
	return str_replace(array('a','e','i','o','u'),array('v','w','x','y','z'),strrev($result));
}

//获取用户IP
function GetClientIP()
{
	global  $_SERVER;
	if  (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
	{
		$realip  =  $_SERVER["HTTP_X_FORWARDED_FOR"];
	}
	elseif  (isset($_SERVER["HTTP_CLIENT_IP"]))
	{
		$realip  =  $_SERVER["HTTP_CLIENT_IP"];
	}
	else
	{
		$realip  =  $_SERVER["REMOTE_ADDR"];
	}
	return  $realip;
}

/*
 * 判断一个点是否在多边形内
 */
function pointInside($p, &$points) {
    $c     = 0;
    $p1 = $points[0];
    $n     = count($points);

    for ($i=1; $i<=$n; $i++)
    {
        $p2 = $points[$i % $n];
        if ($p->y > min($p1->y, $p2->y)
            && $p->y <= max($p1->y, $p2->y)
            && $p->x <= max($p1->x, $p2->x)
            && $p1->y != $p2->y)
        {
            $xinters = ($p->y - $p1->y) * ($p2->x - $p1->x) / ($p2->y - $p1->y) + $p1->x;
            if ($p1->x == $p2->x || $p->x <= $xinters)
                $c++;
        }
        $p1 = $p2;
    }
    // if the number of edges we passed through is even, then it's not in the poly.
    return $c % 2 != 0;
}

/*
 * 点对象
 */
class Point
{
    var $x;
    var $y;

    function Point($x, $y)
    {
        $this->x = $x;
        $this->y = $y;
    }
} 

//取得微秒
function GetMicrotime(){
    list($usec, $sec) = explode(" ",microtime());
    return ((float)$usec + (float)$sec);
}

//取得拼音首字母
function GetFirstLetter($String){
	$fchar = ord($String{0});
	if($fchar >= ord("A") && $fchar <= ord("z"))
		return strtoupper($String{0});
	
	$s = iconv("UTF-8", "gb2312", $String);
	$asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
	
	if($asc>=-20319 and $asc<=-20284)return "A";
	if($asc>=-20283 and $asc<=-19776)return "B";
	if($asc>=-19775 and $asc<=-19219)return "C";
	if($asc>=-19218 and $asc<=-18711)return "D";
	if($asc>=-18710 and $asc<=-18527)return "E";
	if($asc>=-18526 and $asc<=-18240)return "F";
	if($asc>=-18239 and $asc<=-17923)return "G";
	if($asc>=-17922 and $asc<=-17418)return "H";
	if($asc>=-17417 and $asc<=-16475)return "J";
	if($asc>=-16474 and $asc<=-16213)return "K";
	if($asc>=-16212 and $asc<=-15641)return "L";
	if($asc>=-15640 and $asc<=-15166)return "M";
	if($asc>=-15165 and $asc<=-14923)return "N";
	if($asc>=-14922 and $asc<=-14915)return "O";
	if($asc>=-14914 and $asc<=-14631)return "P";
	if($asc>=-14630 and $asc<=-14150)return "Q";
	if($asc>=-14149 and $asc<=-14091)return "R";
	if($asc>=-14090 and $asc<=-13319)return "S";
	if($asc>=-13318 and $asc<=-12839)return "T";
	if($asc>=-12838 and $asc<=-12557)return "W";
	if($asc>=-12556 and $asc<=-11848)return "X";
	if($asc>=-11847 and $asc<=-11056)return "Y";
	if($asc>=-11055 and $asc<=-10247)return "Z";
	
	return null;
}

/*
 * 取得日期数组
 */
function GetDayArray($year=0, $month=0)
{
	$TheDay = getdate(mktime(0,0,0,$month,1,$year));
	$TheWeekDay = $TheDay['wday'];
	$DayGrid = array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41);
	$Days = array();

	for($i = 1; $i < $TheWeekDay; $i++)
	{
		array_push($Days, 0);
	}

	switch($month)
	{
		case 1:
		case 3:
		case 5:
		case 7:
		case 8:
		case 10:
		case 12:
			for($i = 1; $i <= 31; $i++)
			{
				array_push($Days, $i);
			}
			break;
		case 4:
		case 6:
		case 9:
		case 11:
			for($i = 1; $i <= 30; $i++)
			{
				array_push($Days, $i);
			}
			break;
		case 2:
			if(checkdate(2,29,$year))
			{
				for($i = 1; $i <= 29; $i++)
				{
					array_push($Days, $i);
				}
			}
			else
			{
				for($i = 1; $i <= 28; $i++)
				{
					array_push($Days, $i);
				}
			}
			break;
	}
	return array($DayGrid,$Days);
}

/*
 * 取得标签字符串
 */
function GetTagsString($obj)
{
	if(is_array($obj))
	{
		$tags = '';
		for($i=0, $c=count($obj); $i < $c; $i++)
		{
			$tags .= $obj[$i]['tag_name'] . ', ';
		}
		$tags = substr($tags,0, strlen($tags)-2);
		return $tags;
	}
	else {
		return '';
	}
}

/*
 * 取得标签数组
 */
function GetTagArray($string, $limit = 0)
{
	$string = str_replace(',',' ', $string);
	$string = str_replace('/',' ', $string);
	$string = str_replace('\\',' ', $string);
	$string = str_replace('��',' ', $string);
	$string = str_replace('��',' ', $string);
	
	$tags = explode(' ', $string);
	$tags2 = array();
	$tagcount = 0;
	
	for($i=0, $c=count($tags); $i < $c; $i++)
	{
		if($limit && count($tags2) == $limit)
		{
			break;
		}
		
		$tag = trim($tags[$i]);

		$regex = '/^[\x80-\xff_a-zA-Z0-9]+$/';
		$isMatch = preg_match($regex, $tag);
		$len = mb_strlen($tag, "utf-8");
	
		if($tag && $isMatch && $len >=1 && $len <= 15)
		{
			array_push($tags2, $tag);
		}
	}
	
	return $tags2;
}

//从数据源生成哈希表(关联数组)
function GetHashTable($source,$keyname,$valuename)
{
	$result = array();
	for($i = 0;$i < count($source);$i ++)
	{
		$key = $source[$i][$keyname];
		$value = $source[$i][$valuename];
		$result[$key] = $value;
	}
	return $result;
}

//将IP地址转换成数字
function IPtoNum($ipaddress)
{
	$ipArr = explode('.',$ipaddress);
	if(count($ipArr) != 4)
		return 0;
	
	return $ipArr[0] * pow(256,3) + $ipArr[1] * pow(256,2) + $ipArr[2] * 256 + $ipArr[3];
}

//DES解密
function des_decode($key,$encrypted)
{
    $encrypted = base64_decode($encrypted);
    $td = mcrypt_module_open(MCRYPT_DES,'',MCRYPT_MODE_CBC,''); //使用MCRYPT_DES算法,cbc模式
    $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
    $ks = mcrypt_enc_get_key_size($td);
    mcrypt_generic_init($td, $key, $key);       //初始处理
    $decrypted = mdecrypt_generic($td, $encrypted);       //解密
    mcrypt_generic_deinit($td);       //结束
    mcrypt_module_close($td);
    $y=pkcs5_unpad($decrypted);
    return $y;
}

//DES加密
function des_encode($key,$text)
{
    $y=pkcs5_pad($text);
    $td = mcrypt_module_open(MCRYPT_DES,'',MCRYPT_MODE_CBC,''); //使用MCRYPT_DES算法,cbc模式
    $ks = mcrypt_enc_get_key_size($td);
    mcrypt_generic_init($td, $key, $key);       //初始处理
    $encrypted = mcrypt_generic($td, $y);       //解密
    mcrypt_generic_deinit($td);       //结束
    mcrypt_module_close($td);

    return base64_encode($encrypted);
}
//DES加解密辅助函数
function pkcs5_pad($text,$block=8)
{
	$pad = $block - (strlen($text) % $block);
	return $text . str_repeat(chr($pad), $pad);
}
function pkcs5_unpad($text)
{
   $pad = ord($text{strlen($text)-1});
   if ($pad > strlen($text)) return $text;
   if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) return $text;
   return substr($text, 0, -1 * $pad);
}

//过滤一切html标签
function OutHtmlMark($str)
{
	$i = preg_replace("/(<[^>]*>)/","",$str);
//	$i = htmlentities($str);
	return  $i;	
}

//可设置替换次数的字符串替换函数
function str_replace_limit($search, $replace, $subject, $limit=-1) 
{
  // constructing mask(s)...
  if (is_array($search)) 
  {
      foreach ($search as $k=>$v) 
      {
          $search[$k] = '`' . preg_quote($search[$k],'`') . '`';
      }
  }

  else 
  {
      $search = '`' . preg_quote($search,'`') . '`';
  }
  // replacement
  return preg_replace($search, $replace, $subject, $limit);
}

?>