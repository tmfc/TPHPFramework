<?php
/*  
 * 关于页面
 * */
require_once('lib/pagebase.php');
require_once('lib/data.common.php');

$about_type = $_GET['type'];
if(empty($about_type))
	Location('/');
// 从 about 表中取出数据
$AboutInfo = GetAboutInfo($about_type);
if(empty($AboutInfo))
	Location('/');

// 替换变量
$AboutInfo['content']= str_replace('site_name',__SITE_NAME,$AboutInfo['content']);
$AboutInfo['content']= str_replace('site_domain',__SITE_DOMAIN,$AboutInfo['content']);
	
//组织导航信息
AddBreadCrumbIndex();
if(isset($_GET['page']) && $_GET['page'] == 'help')
{
	AddBreadCrumb('帮助');
}
AddBreadCrumb($AboutInfo['title']);

Assign("PageName","AboutPage");

Assign('Title',$AboutInfo["title"]);
Assign('Content',$AboutInfo["content"]);

SetPageTitle($AboutInfo["title"]);
DisplayPage('about.html');
?>