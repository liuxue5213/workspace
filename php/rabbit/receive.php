<?php
// $exchangeName = 'test';
// $queueName = 'test1';
// $routeKey = 'test';

$conn_args = array(
        'host'=>'127.0.0.1',
        'port'=>5672,
        'login'=>'test',
        'password'=>'123456',
        'vhost'=>'/'
    );
$e_name = 'e_demo';
$q_name = 'q_demo';
$k_route = 'key_1';
$conn = new AMQPConnection($conn_args);
if(!$conn->connect()){
    die('Cannot connect to the broker');
}
$channel = new AMQPChannel($conn);
$ex = new AMQPExchange($channel);
$ex->setName($e_name);
$ex->setType(AMQP_EX_TYPE_DIRECT);
$ex->setFlags(AMQP_DURABLE);
$q = new AMQPQueue($channel);
// var_dump($q);
$q->setName($q_name);
$q->bind($e_name, $k_route);

$arr = $q->get(AMQP_AUTOACK);
$res = $q->ack($arr->getDeliveryTag());
// $msg = $arr->getBody();
var_dump($arr);
$conn->disconnect();
die;

$connection = new AMQPConnection(array('host' => '127.0.0.1', 'port' => '5672', 'vhost' => '/', 'login' => 'test', 'password' => '123456'));
$connection->connect() or die("Cannot connect to the broker!\n");
$channel = new AMQPChannel($connection);
$exchange = new AMQPExchange($channel);
$exchange->setName($exchangeName);
$exchange->setType(AMQP_EX_TYPE_DIRECT);
$exchange->declare();
$queue = new AMQPQueue($channel);
$queue->setName($queueName);
$queue->declare();
$queue->bind($exchangeName, $routeKey);

// var_dump('[*] Waiting for messages. To exit press CTRL+C');
// while (TRUE) {
//     $queue->consume('callback');
// }
// $connection->disconnect();

// function callback($envelope, $queue) {
//     $msg = $envelope->getBody();
//     var_dump(" [x] Received:" . $msg);
//     $queue->nack($envelope->getDeliveryTag());
// }
