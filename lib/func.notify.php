<?php
require_once(dirname(__FILE__) ."/db.php");
require_once(dirname(__FILE__) ."/func.mailto.php");
require_once(dirname(__FILE__) ."/conf/config.php");
//发送邮件到邮件队列
function SendQueueEmail($address, $nickname, $title, $body)
{
	//$mdb2 = GetMDB2();
	$mdb2 =& MDB2::connect('mysql://www-wolai-admin:zQ(u2FcK@9a@210.51.37.58/wolai_extend?charset=utf8');
	if (PEAR::isError($mdb2)) { echo var_dump($mdb2);die($mdb2->getMessage());}

	$sql = "INSERT INTO MAIL_SEND_QUEUE(TITLE,CONTENT,RECIPIENTS,CREATE_TIME,BOOKING_SEND_TIME,PRIORITY) VALUE (?,?,?,NOW(),NOW(),1); ";
	$types = array('text', 'text', 'text');
	$data = array($title,$body,$mailaddress);
	
	return ExecuteNonQueryWithPrepare($mdb2,$sql,$types,$data);
}
//直接发送邮件
function SendDirectEmail($address,$nickname, $title, $body)
{
	//SendEmail($address, $nickname, $title, $body);
}

?>