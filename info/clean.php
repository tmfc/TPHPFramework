<?php
require_once('../lib/pagebase.php');			//主要函数

// 清除所有已编译的模板文件
$smarty->clear_compiled_tpl();

?>

<script type="text/javascript">
	alert('已编译模板已清除。');
	window.location.href = '/info/';
</script>