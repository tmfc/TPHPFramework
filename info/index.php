<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta name="robots" content="noindex, nofollow">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>服务器信息</title>
<?php include_once('style.html'); ?>
</head>
<body>

<table>
	<tr>
		<td valign="top" width="250">
			<b>服务器状态</b>
		</td>
		<td valign="top" width="250">
			<b>信息/设置</b>
		</td>
		<td valign="top" width="250">
			<b>系统</b>
		</td>
	</tr>
	<tr>
		<td valign="top">
			<ul>
				<li></li>

			</ul>
		</td>
		<td valign="top">
			<ul>

				<li><a href="http://210.51.37.53/phpmyadmin/" target="_blank">数据库(37.58)管理</a></li>
				<li><a href="/info/clean.php" onclick="return confirm('确定要清空 Smarty 已编译模板吗？');">刷新Smarty模板</a></li>
			</ul>
		</td>
		<td valign="top">
			<ul>
				<li><a href="http://trace.wolai.com/" target="_blank">事务追踪系统</a></li>
				<li><a href="http://admin.wolai.com/" target="_blank">后台管理系统</a></li>
				<li><a href="http://bms.hichina.com/jsp/apimanage/" target="_blank">SMS后台</a></li>
				<li><a href="https://mail.google.com/a/wolai.com/" target="_blank">公司邮件</a></li>
				<li><a href="https://docs.google.com/a/wolai.com/" target="_blank">公司文档</a></li>
			</ul>
		</td>
	</tr>
</table>

<?php include_once('footer.html'); ?>

</body>
</html>