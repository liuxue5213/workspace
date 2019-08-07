<?php
set_time_limit(0);
include_once('RabbitMQCommand.php');

$configs = array('host'=>'127.0.0.1','port'=>5672,'username'=>'test','password'=>'123456','vhost'=>'/');
$exchange_name = 'ex_email';
$queue_name = 'q_email';
$route_key = 'key_email';
$ra = new RabbitMQCommand($configs,$exchange_name,$queue_name,$route_key);
for($i=0;$i<=100;$i++){
    $ra->send(date('Y-m-d H:i:s',time()));
}