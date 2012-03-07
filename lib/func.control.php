<?php
//控件函数
//----------
require_once(dirname(__FILE__) . '/pagebase.php');

//控件函数
//----------
//翻页控件
function PageControl($recordcount, $pagesize, $currentpage, $show_page=10, $url_pre, $url_post,$template)
{
	if($recordcount <= 0)
		return '';
		
	$pagecount = floor($recordcount / $pagesize) + ($recordcount % $pagesize > 0 ? 1 : 0);

	if($currentpage > $pagecount)
		$currentpage = $pagecount;
	if($currentpage < 1)
		$currentpage = 1;

	//显示全部页码
	if($show_page === 0)
	{
		$pagebegin = 1;
		$pageend = $pagecount;
	}
	//计算需要显示的页码
	else
	{
		if($pagecount > $show_page)
		{
			if($currentpage < 6)
				$pagebegin = 1;
			elseif($currentpage + 5 > $pagecount)
				$pagebegin = $pagecount - 9;
			else
				$pagebegin = $currentpage - 5;
		}
		else
		{
			$pagebegin = 1;
		}
		
		if($pagecount > 10)
		{
			if($currentpage < 6)
			{
				$pageend = 10;
			}
			else if($currentpage + 5 > $pagecount)
			{
				$pageend = $pagecount;
			}
			else
			{
				$pageend = $currentpage + 4;
			}
		}
		else
		{
			$pageend = $pagecount;
		}
	}
	
	$recordbegin = ($currentpage - 1) * $pagesize + 1;
	$recordend = ($pagecount > $currentpage ? (($currentpage - 1) * $pagesize + $pagesize) : $recordcount);
	Assign("AllPages",range(1,$pagecount));
	Assign("Pages",range($pagebegin,$pageend));
	Assign("PageEnd",$pageend);
	Assign("PageCount",$pagecount);
	Assign("UrlPre",$url_pre);
	Assign("UrlPost",$url_post);
	Assign("CurrentPage",$currentpage);
	Assign("PageSize",$pagesize);
	Assign("RecordCount",$recordcount);
	Assign("RecordBegin",$recordbegin);
	Assign("RecordEnd",$recordend);
	
	return FetchPage($template);
}
?>