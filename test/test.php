<?php
require dirname(__DIR__) . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;

go(function(){
	$mail = new PHPMailer; //PHPMailer对象
	$mail->CharSet = 'UTF-8'; //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
	$mail->IsSMTP(); // 设定使用SMTP服务
	$mail->SMTPDebug = 0; // 关闭SMTP调试功能
	$mail->SMTPAuth = true; // 启用 SMTP 验证功能
	$mail->SMTPSecure = 'ssl'; // 使用安全协议
	$mail->Host = 'smtp.163.com'; // SMTP 服务器
	$mail->Port = '994'; // SMTP服务器的端口号
	$mail->Username = ''; // SMTP服务器用户名
	$mail->Password = ''; // SMTP服务器密码
	$mail->SetFrom('', ''); // 邮箱，昵称
	$mail->Subject = 'title test';
	$mail->MsgHTML('hello world');
	$mail->AddAddress(''); // 收件人
	$result = $mail->Send();
	if($result)
	{
		var_dump('ok');
	}
	else
	{
		$result = $error = $mail->ErrorInfo;
		var_dump($result);
	}
});
