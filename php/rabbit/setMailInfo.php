<?php
set_time_limit(0);
include_once('config.php');
include_once('RabbitMQCommand.php');

$email = empty($_POST['email']) ? '' : $_POST['email'];//地址
$subject = empty($_POST['subject']) ? '' : $_POST['subject'];//主题
$content = empty($_POST['cont']) ? '' : $_POST['cont'];//内容
$useFromType = empty($_POST['fromType']) ? 'default' : $_POST['fromType'];//使用发件箱
$cc = empty($_POST['cc']) ? '' : $_POST['cc'];//抄送
$bcc = empty($_POST['bcc']) ? '' : $_POST['bcc'];//密送
$asc = empty($_POST['asc']) ? '' : $_POST['asc'];//分别发送

$info = array(
	'fromType' => $useFromType,
	'addr' => $email,
	'sub' => $subject,
	'cont' => $content,
	'cc' => $cc,
	'bcc' => $bcc,
	'asc' => $asc,
	'time' => date('Y-m-d H:i:s',time())
);

$exchange_name = 'ex_email';
$queue_name = 'q_email';
$route_key = 'key_email';
$rabbit = new RabbitMQCommand($configs,$exchange_name,$queue_name,$route_key);
$rabbit->send($info);
exit();

?>
<!-- echo htmlspecialchars($htmlData); -->
