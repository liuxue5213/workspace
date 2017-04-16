<?php
set_time_limit(0);
include_once('config.php');
include_once('RabbitMQCommand.php');

$email = empty($_POST['email']) ? '' : $_POST['email'];
$subject = empty($_POST['subject']) ? '' : $_POST['subject'];
$content = empty($_POST['cont']) ? '' : $_POST['cont'];
$useFromType = empty($_POST['fromType']) ? 'default' : $_POST['fromType'];

// $htmlData = '';
// if (!empty($content)) {
// 	if (get_magic_quotes_gpc()) {
// 		$htmlData = stripslashes($content);
// 	} else {
// 		$htmlData = $content;
// 	}
// }

$info = array(
	'fromType' => $useFromType,
	'addr' => $email,
	'sub' => $subject,
	'cont' => $content,
	'time' => date('Y-m-d H:i:s',time())
);

$exchange_name = 'ex_email';
$queue_name = 'q_email';
$route_key = 'key_email';
$rabbit = new RabbitMQCommand($configs,$exchange_name,$queue_name,$route_key);
$rabbit->send($info);
exit();

?>

