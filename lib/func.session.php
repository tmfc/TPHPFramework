<?php

//判断用户是否已登录
function IsUserLogin()
{
	if(!isset($_SESSION[__SSN_USER]))
		return false;
	return $_SESSION[__SSN_USER] != null;
}

//从session中获取用户信息
function GetLoginUser()
{
	if(isset($_SESSION[__SSN_USER]) && $_SESSION[__SSN_USER] != null){
		return $_SESSION[__SSN_USER];
	}
	else 
		return null;
}
?>