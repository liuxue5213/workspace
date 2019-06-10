<?php
// $exchangeName = 'test';
// $queueName = 'test1';
// $routeKey = 'test';
// $message = 'Hello World!';

// $connection = new AMQPConnection(array('host' => '127.0.0.1', 'port' => '5672', 'vhost' => '/', 'login' => 'test', 'password' => '123456'));
// $connection->connect() or die("Cannot connect to the broker!\n");

// try {
//     $channel = new AMQPChannel($connection);
//     $exchange = new AMQPExchange($channel);
//     $exchange->setName($exchangeName);
//     $queue = new AMQPQueue($channel);
//     $queue->setName($queueName);
//     $exchange->publish($message, $routeKey);
//     // var_dump("[x] Sent 'Hello World!'");
// } catch (AMQPConnectionException $e) {
//     var_dump($e);
//     exit();
// }
// $connection->disconnect();


$conn_args = array(
        'host'=>'127.0.0.1',  //rabbitmq 服务器host
        'port'=>5672,         //rabbitmq 服务器端口
        'login'=>'test',     //登录用户
        'password'=>'123456',   //登录密码
        'vhost'=>'/'         //虚拟主机
    );
$e_name = 'e_demo';
$q_name = 'q_demo';
$k_route = 'key_1';
$msg = 'hello world';
$conn = new AMQPConnection($conn_args);
if(!$conn->connect()){
    die('Cannot connect to the broker');
}
$channel = new AMQPChannel($conn);
$ex = new AMQPExchange($channel);
$ex->setName($e_name);
$ex->setType(AMQP_EX_TYPE_DIRECT);
$ex->setFlags(AMQP_DURABLE);
$status = $ex->declareExchange();  //声明一个新交换机，如果这个交换机已经存在了，就不需要再调用declareExchange()方法了.
$q = new AMQPQueue($channel);
$q->setName($q_name);
$status = $q->declareQueue(); //同理如果该队列已经存在不用再调用这个方法了。
$ex->publish($msg, $k_route);
$conn->disconnect();

