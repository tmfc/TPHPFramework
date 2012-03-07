<?php
require_once(dirname(__FILE__) . '/ajaxbase.php');
require_once(dirname(__FILE__) . '/func.validation.php');
require_once(dirname(__FILE__) . '/func.cleancache.php');

function GetParamsData()
{
	$keys = array_keys($_POST);
	$params = array();
	for($i = 0;$i < count($keys);$i ++)
		$params[] = $keys[$i] . '=' . $_POST[$keys[$i]];
	
	return implode('&',$params);
}

function GetParams($data)
{
	$params = array();
	if(!empty($data))
	{
		$data = htmlspecialchars_decode(urldecode($data));
		$paramList = explode('&',$data);
		for($i = 0;$i < count($paramList);$i ++)
		{
			if(!empty($paramList[$i]))
			{
				$param = explode('=',$paramList[$i]);
				if(!empty($param) && count($param) == 2)
				{
					$paramKey = $param[0];
					$paramValue = $param[1];
					$params[$paramKey] = htmlspecialchars($paramValue);
				}
			}
		}
	}
	return $params;
}
?>