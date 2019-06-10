<?php

use Snipworks\SMTP\Email;
require_once(dirname(__DIR__) . '/email/PhpImap/smtp.php');

$mail = new Email('smtp.sina.com', 465);
$mail->setProtocol(Email::SSL);
// $mail->setLogin('test@qq.com', '');
$mail->setLogin('test@sina.com', '');
$mail->addTo('test@test.com', 'LX');
$mail->setFrom('test@sina.com', 'JohnScott');
$mail->setSubject('Test subject');
$mail->setMessage('<b>test message</b>', true); 

// $mail = new Email('smtp.sina.com', 465);
// $mail->setProtocol(Email::SSL);
// $mail->setLogin('test@sina.com', '');
// // $mail->setLogin('test@gmail.com', '');
// // 
// $mail->addTo('test@qq.com', 'LX');
// $mail->setFrom('test@gmail.com', 'JohnScott');
// $mail->setSubject('Test subject');
// $mail->setMessage('<b>test message</b>', true); 

echo (($mail->send()) ? 'Mail has been sent' : 'Error sending email') . PHP_EOL;

print_r($mail->getLog()); 
