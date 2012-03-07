<?php
require_once(dirname(__FILE__) ."/conf/config.php");

//包含JS文件
$JSInclude = array();
$GMapInclude = array();
$PngFixSelector = array();
function IncludeJS($jskey)
{
	global $JSInclude, $GMapInclude, $LocalDev, $LocalMachine;
	
	$imgserverurl = __STATIC_SERVER;
	if($LocalMachine)
	{
		$imgserverurl = '';
		$jsfile = '/dev/' . $jskey;
	}
	else
	{
		$jsfile = '/js/' . $jskey;		
	}
	
	$JSContent = '<script language="JavaScript" type="text/javascript" src="' . $imgserverurl . $jsfile . '.js?version=' . __JS_FUNC_VERSION . '"></script>' . "\n";

	if (strpos($jsfile, 'map')) {
		if(!array_key_exists($jskey,$GMapInclude))
			$GMapInclude[$jskey] = $JSContent;
	}
	else {
		if(!array_key_exists($jskey,$JSInclude))
			$JSInclude[$jskey] = $JSContent;
	}
}

//包含Mootools
function IncludeMootools()
{
	IncludeJS("mootools.1.2");
}

//包含JS验证组件
function IncludeValidation()
{
	IncludeJS('validation');
}

//包含对话框
function IncludeDialog()
{
	IncludeJS('dialog');
}

//包含pngfix
function IncludePngFix()
{
	global $JSInclude;
	IncludeJS('pngfix');
	$JSInclude['pngfix'] = "<!--[if IE 6]>\n" . $JSInclude['pngfix'] . "<![endif]-->\n";
}

//增加需要pngfix的元素
function PngFix($selector)
{
	global $PngFixSelector;

	if(!in_array($selector,$PngFixSelector))
	{
		$PngFixSelector[] = $selector;
	}
}

//定义页面所应包含的JS
function RunJS($currentjs)
{
	Assign('RunJS',$currentjs);
}
?>