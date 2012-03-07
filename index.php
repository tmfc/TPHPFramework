<?php
/*  
 * 首页
 * */
require_once('lib/pagebase.php');
require_once('lib/app.case.php');
require_once('lib/app.common.php');

IncludeJS("index");
RunJS("Index.init();");

if($CurrentCityURL == 'www')
{
//	//获取城市列表
//	$cityListGrouped = GetCityGroupByProvince();
//	Assign("CityListGrouped",$cityListGrouped);

	//获取信息
	$CaseList = GetCaseListTop(100);
	
	for($i=0;$i<count($CaseList);$i++)
	{
		$CaseList[$i]['image_count'] = count(explode(',',$CaseList[$i]['images']));
		$CaseList[$i]['tag_list'] = explode(',',$CaseList[$i]['tags']);
	
		if($CaseList[$i]['price'] == 0)
		{
			$CaseList[$i]['price'] = '';
		}
		if($CaseList[$i]['price'] > 10000)
		{
			$CaseList[$i]['price'] = round(($CaseList[$i]['price'] / 10000),2) . '<span class="wan">万</span>'; 
		}
	}
	Assign("CaseList",$CaseList);  
	Assign("PageName","IndexPage");
}
else
{
	//分类列表
	$subjectListGrouped = GetSubjectListGrouped();
	Assign("SubjectListGrouped",$subjectListGrouped);
    Assign("PageName","HomePage");
}

   
$Links = GetLinks($CurrentCityID);
Assign("Links",$Links);
//组织导航
//AddBreadCrumbIndex(false);
$PageTitle = ($CurrentCityURL != 'www' ? $CurrentCityName : '') . __SITE_NAME . " - 中国十佳分类信息网站，免费搜索发布各类城市生活信息";
SetMetaKeyword($CurrentCityName . __SITE_NAME . ", 分类信息, 同城, 生活信息, 免费发布, 招聘, 求租, 二手房, 找工作, 二手交易");

DisplayPage("index.html");
?>