<?php
//邮件相关函数
//----------

include_once(dirname(__FILE__) . "/class.phpmailer.php");
include_once(dirname(__FILE__) . "/class.phpmailer.lang-en.php");
include_once(dirname(__FILE__) . "/class.smtp.php");

$mail=new PHPMailer();
$mail->IsSMTP();
//$mail->IsSMTP();
//$mail->SMTPAuth   = true;                  // enable SMTP authentication
//$mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
//$mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
//$mail->Port       = 465;                   // set the SMTP port 
//
//$mail->Username   = "no-reply@wolai.com";  	// GMAIL username
//$mail->Password   = "94P197";            // GMAIL password

$mail->SMTPAuth   = false;                  // enable SMTP authentication
$mail->Host       = "210.51.52.171";      // sets GMAIL as the SMTP server
$mail->Port       = 25;                   // set the SMTP port 

$mail->Username   = "user";  				// GMAIL username
$mail->Password   = "password";				// GMAIL password


$mail->From       = "no-reply@mail.wolai.com";
$mail->FromName   = GetUTF8Base64String('wolai123');
//$mail->AddBCC("marilawang@gmail.com",'wolai BCC');

$mail->WordWrap   = 50; 					// set word wrap
$mail->IsHTML(false); 						// send as HTML
$mail->ContentType = "text/plain";
$mail->CharSet = "UTF-8";
$mail->Encoding = "base64";

//取得UTF文本
function GetUTF8String($string)
{
	return '=?utf-8?B?'. $string . '?=';
}
//取得UTF编码文本
function GetUTF8Base64String($string)
{
	return '=?utf-8?B?'. base64_encode($string) . '?=';
}


//发送邮件
function SendEmail($address, $nickname, $mail_title, $mail_body)
{
	global $mail;

	$mail->AddAddress($address,GetUTF8Base64String($nickname));
	$mail->Subject    = GetUTF8Base64String($mail_title);

	$body = $mail_body;
	
	$mail->Body = $body;

	$result = $mail->Send();
	if(!$result) {
	  die("Mailer Error: " . $mail->ErrorInfo);
	}	
	echo $result;
}
?>