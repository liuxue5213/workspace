<?php
error_reporting(0);
include_once('RabbitMQCommand.php');

$configs = array('host'=>'127.0.0.1','port'=>5672,'username'=>'test','password'=>'123456','vhost'=>'/');
$exchange_name = 'ex_email';
$queue_name = 'q_email';
$route_key = 'key_email';
$ra = new RabbitMQCommand($configs,$exchange_name,$queue_name,$route_key);


class A{
    function processMessage($envelope, $queue) {
        $msg = $envelope->getBody();
        $envelopeID = $envelope->getDeliveryTag();
        $pid = posix_getpid();
        file_put_contents("log{$pid}.log", $msg.'|'.$envelopeID.''."\r\n",FILE_APPEND);
        $queue->ack($envelopeID);
    }
}
$a = new A();


$s = $ra->run(array($a,'processMessage'),false);