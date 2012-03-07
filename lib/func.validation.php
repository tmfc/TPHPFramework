<?php
require_once(dirname(__FILE__) ."/app.user.php");
require_once(dirname(__FILE__) ."/app.common.php");
require_once(dirname(__FILE__) ."/const.php");
//验证类函数
//----------

//验证字符串是否为空
function IsStringEmpty($str)
{
	return (strlen($str) == 0);
}

//验证字符串是否不到给定长度
function IsStringShorterThan($str, $len)
{
	return mb_strlen($str, "utf-8") < $len;
}

//验证字符串是否超过给定长度
function IsStringLongerThan($str, $len)
{
	return mb_strlen($str, "utf-8") > $len;
}

//获取一个无首尾空格的字符串
function GetTrimedString($str)
{
	return trim($str);
}

//获取一个全部小写的字符串
function GetLowerString($str)
{
	return strtolower($str);
}

//获取一个无首尾空格并全部小写的字符串
function GetLTString($str)
{
	return GetLowerString(GetTrimedString($str));
}

//获取一个好的Email
function GetGoodEmail($Email) { return GetLTString($Email); }

//验证Email是否合法
function IsEmailValid($Email, $matchall = true)
{
	$regex = '\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*';
	$regex = $matchall ? '/^' . $regex . '$/' : '/' . $regex . '/';
	$isMatch = preg_match($regex, $Email);
	
	return $isMatch;
}

//验证Email是否不被允许
function IsEmailNotAllowed($Email)
{
	$cond1 = stripos($Email, '@yahoo');
	$cond2 = preg_match('/happyxc.*\@163\.com/', $Email);
	return $cond1 || $cond2;
}

//是否是合法的QQ号
function IsQQValid($QQ){
	return IsPositiveInteger($QQ) && strlen($QQ) >=5 && strlen($QQ) <= 11;
}

//验证月份是否合法
function IsMonthValid($Month)
{
	return ($Month >= 1 && $Month <= 12);
}

//验证密码是否为空
function IsPasswordEmpty($Password) { return IsStringEmpty($Password); }

//验证密码长度
function IsPasswordGood($Password)
{
	$len = strlen($Password);
	return ($len >= 6 && $len <= 20);
}

//验证两次密码是否相同
function IsPasswordTheSame($Password, $Password2) { return ($Password === $Password2); }

//验证昵称
function IsNicknameGood($Nickname)
{
	$regex = '/^[\x80-\xff_a-zA-Z0-9]+$/';
	$isMatch = preg_match($regex, $Nickname);
	
	$len = mb_strlen($Nickname, "utf-8");

	return ($isMatch && $len >= 2 && $len <= 10);
}

//获取一个好的昵称
function GetGoodNickname($Nickname) { return GetTrimedString($Nickname); }

//验证性别
function IsGenderGood($Gender)
{
	return ($Gender === __USER_GENDER_FEMALE || $Gender === __USER_GENDER_MALE);
}

//验证出生年份
function IsBirthYearGood($BirthYear)
{
	$theDate = getdate();
	return ((int)$BirthYear != -1 && (int)$BirthYear >= 1930 && (int)$BirthYear <= $theDate['year'] - 9);
}

//验证用户角色
function IsUserRoleGood($UserRole)
{
	if($UserRole == '')
		return false;
	return ((int)$UserRole >= __USER_ROLE_UNKNOWN && (int)$UserRole <= __USER_ROLE_PROFESSIONAL);
}

//验证自我介绍
function IsAboutMeGood($AboutMe)
{
	$AboutMe = GetGoodAboutMe($AboutMe);
	$len = mb_strlen($AboutMe, "utf-8");
	return ($len <= 500);
}

//获得一个好的自我介绍
function GetGoodAboutMe($AboutMe)
{
	return trim(strip_tags(htmlspecialchars($AboutMe)));
}

//验证博客地址
function IsBlogUrlGood($BlogUrl)
{
	$BlogUrl = trim($BlogUrl);
	
	if($BlogUrl == 'http://' || empty($BlogUrl))
		return true;
	
	$regex = '/^http:\/\/([\w-]+\.)+[\w-]+(\/[\w- .\/?%&=]*)?$/';
	$isMatch = preg_match($regex, $BlogUrl);
	
	return $isMatch;
}

//获得一个好的博客地址
function GetGoodBlogUrl($BlogUrl)
{
	$BlogUrl = GetTrimedString($BlogUrl);
	
	if ($BlogUrl == 'http://')
		return '';

	$regex = '/^http:\/\/([\w-]+\.)+[\w-]+(\/[\w- .\/?%&=]*)?$/';
	$isMatch = preg_match($regex, $BlogUrl);
	
	if (!$isMatch)
		return '';

	return $BlogUrl;
}

//验证永久连接
function IsPermalinkGood($Permalink)
{
	$Permalink = GetTrimedString($Permalink);
	
	if(IsStringEmpty($Permalink))
		return false;

	$regex = '/^[a-zA-Z][a-zA-Z0-9]{3,14}$/';
	$isMatch = preg_match($regex, $Permalink);
	
	return $isMatch;	
}

//获得一个好的永久连接
function GetGoodPermalink($Permalink)
{
	return GetLTString($Permalink);
}

//验证用户是否匿名
function IsUserAnonymous($userid)
{
	return ($userid == 0);
}

/**
 * 取得全角标点的文本
 *
 * @param string $sentence
 * return string
 */
function GetGoodPunctuationText($sentence)
{
	$sentence = str_replace(',','，',$sentence);
	$sentence = str_replace('!','！',$sentence);
	$sentence = str_replace('！！','！',$sentence);
	$sentence = str_replace('?','？',$sentence);
	$sentence = str_replace('？？','？',$sentence);
	return $sentence;
}

//验证一个图片文件类型是否合法
function IsGoodImageType($ImageType)
{
	switch($ImageType)
	{
		case 'image/pjpeg':
		case 'image/jpeg':
		case 'image/gif':
		case 'image/png':
		case 'image/x-png':
			return true;
		default:
			return false;
	}
}

//验证是否是正整数
function IsPositiveInteger($value)
{
	if($value == null || $value == '')
		return true;
	$reg = '/^\d+$/';
	return preg_match($reg,$value);
}
//验证是否是正实数
function IsPositiveNumber($value)
{
	if($value == null || $value == '')
		return true;
	$reg = '/^\d+(\.\d+)?$/';
	return preg_match($reg,$value);
}

//验证是否是合法的手机号码
function IsMobileValid($value, $matchall = true)
{
	$reg = $matchall ? '/^1[3|5|8]\d{9}$/' : '/1[3|5|8]\d{9}/';
	return preg_match($reg,$value);
}

//验证是否是合法的电话号码
function IsTelphoneValid($value, $matchall = true)
{
	$reg = '/^[\d-()]+$/';
	return preg_match($reg,$value);
}

//验证是否是合法的邮政编码
function IsZipCodeValid($value)
{
	if($value == null || $value == '')
		return true;
	if(!IsPositiveInteger($value))
		return false;
	if(strlen($value) != 6)
		return false;
	return true;	
}

//验证是否是合法的真名
function IsRealNameGood($realname)
{
	//先验证是不是中文
	$reg = '/^[\x80-\xff]+$/';
	$isMatch = preg_match($reg, $realname);
	if(!$isMatch)
		return false;
		
	$surnameValid = (IsVaildSurname(mb_substr($realname,0,1,'UTF-8')) > 0 || IsVaildSurname(mb_substr($realname,0,2,'UTF-8')) > 0);
	if(!$surnameValid)
		return false;
	else 
		return mb_strlen($realname,'UTF-8') >= 2 && mb_strlen($realname,'UTF-8') <= 4;
}

//验证码是否正确输入
function IsValidateCodeRight($validateCode)
{
	return md5(strtolower($validateCode)) == $_SESSION['validate_code'];
}

//日期是否正确
function IsDateGood($year,$month,$date)
{
	//处理70后，80后，90后
	if($year == 70 || $year == 80 || $year == 90)
	{
		$year = 1980;
	}
	
	//纠正用户选择的日期
	$RealTime = getdate(mktime(0,0,0,$month,$date,$year));
	$RealYear = $RealTime['year'];
	$RealMonth = $RealTime['mon'];
	$RealDate = $RealTime['mday'];
	
	return $RealYear == $year && $RealMonth == $month && $RealDate == $date;
}
//是否是合法的身份
function IsRoleGood($role)
{
	$roleList = InitRoleList();
	return array_key_exists($role,$roleList);
}

//是否是自己网站的url
function IsOwnDomainUrl($url)
{
	if(strpos($url,'http://') === false && strpos($url,'https://') === false)
		return true;
	if(strpos($url,'http://') !== false)
	{
		$url = strstr($url,'http://');
		$url = ltrim($url,'http://');
	}
	else if(strpos($url,'https://') !== false)
	{
		$url = strstr($url,'https://');
		$url = ltrim($url,'https://');
	}
	$url = substr($url,0,strpos($url,'/'));
	return $url == __SITE_DOMAIN;

}

//验证给定字符串是否为纯数字
function IsAllNumber($string)
{
	for($i=0;$i<mb_strlen($string,"utf-8");$i++)
	{
		$char = mb_substr($string,$i,1,"utf-8");
		if(ord($char)>47&&ord($char)<58)
			;
		else 
			return false;
	}
	return true;
}
//检验是否在黑名单中
function IsInBlackList($strings,&$blackword)
{
	$blacklist = GetBlacklist();
	//单个字符串
	if(gettype($strings) == "string")
	{
		$blackword = _IsInBlackList($strings,$blacklist);
		return $blackword;
	}
	//字符串数组
	else if(gettype($strings) == "array")
	{
		for($i = 0;$i < count($strings);$i ++)
		{		
			$blackword = _IsInBlackList($strings[$i],$blacklist);
			return $blackword;
		}
		return false;
	}
}
function _IsInBlackList($str,&$blacklist)
{
	for($i = 0;$i < count($blacklist);$i ++)
	{
		if(mb_strpos($str,$blacklist[$i]) !== false)
		{
//			try
//			{
//				$file = fopen('/var/www/www.wolai123.com/blackword.txt','a');
//				fwrite($file,"黑名单：" . $blacklist[$i] . "\r\n");
//				fwrite($file,$str . "\r\n------------------------------------------------\r\n");
//				fclose($file);
//			}
//			catch(Exception $ex)
//			{
//				
//			}
			return $blacklist[$i];
		}
	}
	return false;
}
?>