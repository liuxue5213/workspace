<?php
use Snipworks\SMTP\Email;
error_reporting(0);
include_once('config.php');
include_once('RabbitMQCommand.php');
include_once('SendMail.php');

$exchange_name = 'ex_email';
$queue_name = 'q_email';
$route_key = 'key_email';
$rabbit = new RabbitMQCommand($configs,$exchange_name,$queue_name,$route_key);

class A{
	public $emails;

    function processMessage($envelope, $queue)
    {
    	//配置信息
    	global $emails;
    	$this->emails = $emails;
        
        //获取队列消息
        $msg = $envelope->getBody();
        $info = json_decode($msg, true);
        $envelopeID = $envelope->getDeliveryTag();
        $pid = posix_getpid();
		$result = $this->send($this->emails, $info);
        file_put_contents("log{$pid}.log", $result.'|'.$envelopeID.''."\r\n",FILE_APPEND);
        $queue->ack($envelopeID);
    }

	function send($email, $info)
	{
		if (empty($info)) {
			return '队列消息为空';
		}

		//使用哪个邮箱服务器 配置的邮箱信息
		$fromType = empty($info['fromType']) ? 'sina' : $info['fromType'];
		//用户名
		$username = empty($email[$fromType]['username']) ? '' : $email[$fromType]['username'];
		//密码
		$password = empty($email[$fromType]['password']) ? '' : $email[$fromType]['password'];


		//用户名密码不能为空
		if (!empty($username) && !empty($password)) {
			ob_clean();   
			ob_start();
			$mail = new SendMail();
			$mail->setServer('smtp.'.$fromType.'.com', $username, $password, 465, true);
			$mail->setFrom($username);
			$mail->setReceiver($info['addr']);
			// $mail->setReceiver("XXX@qq.com");
			// $mail->setCc("XXX@126.com");
			// $mail->setBcc("XXX@126.com");
			// $mail->addAttachment("sms.zip");
			$mail->setMail($info['sub'], $info['cont']);
			$mail->sendMail();
			return ob_get_contents();		
		} else {
			return '发件邮箱用户名或密码配置有误';
		}
	}
}

$a = new A();
$s = $rabbit->run(array($a,'processMessage'),false);




