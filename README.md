# PHPMailer-Swoole

[![Latest Version](https://img.shields.io/packagist/v/yurunsoft/phpmailer-swoole.svg)](https://packagist.org/packages/yurunsoft/phpmailer-swoole)
[![Php Version](https://img.shields.io/badge/php-%3E=7.0-brightgreen.svg)](https://secure.php.net/)
[![Swoole Version](https://img.shields.io/badge/swoole-%3E=4.0.0-brightgreen.svg)](https://github.com/swoole/swoole-src)
[![IMI License](https://img.shields.io/github/license/Yurunsoft/PHPMailer-Swoole.svg)](https://github.com/Yurunsoft/PHPMailer-Swoole/blob/master/LICENSE)

## 介绍

这是一个适合用于 Swoole 协程环境下的 PHPMailer。

本项目使用非侵入式方案，基于 PHPMailer 6.0 实现 PHPMailer 的 Swoole 协程环境支持，理论上兼容 PHPMailer 6.0 及后续版本。

## 使用说明

Composer:`"yurunsoft/phpmailer-swoole":"~1.0"`

使用方式和 PHPMailer 并无两样，唯一需要注意的是只支持在 Swoole 协程下运行。

```php
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
```

更加详细的示例代码请看`test`目录下代码。