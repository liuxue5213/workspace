<?php
$routingkey='key';
//设置你的连接
$conn_args = array('host' => 'localhost', 'port' => '5672', 'login' => 'test', 'password' => '123456');
$conn = new AMQPConnection($conn_args);
if ($conn->connect()) {
    echo "Established a connection to the broker \n";
}
else {
    echo "Cannot connect to the broker \n ";
}
//你的消息
$message = json_encode(array('Hello World3!','php3','c++3:'));
//创建channel
$channel = new AMQPChannel($conn);
//创建exchange
$ex = new AMQPExchange($channel);
$ex->setName('exchange');//创建名字
$ex->setType(AMQP_EX_TYPE_DIRECT);
$ex->setFlags(AMQP_DURABLE);
//$ex->setFlags(AMQP_AUTODELETE);
//echo "exchange status:".$ex->declare();
// echo "exchange status:".$ex->declareExchange();
// echo "\n";
for($i=0;$i<100;$i++){
    if($routingkey=='key2'){
        $routingkey='key';
    }else{
        $routingkey='key2';
    }
    $ex->publish($message,$routingkey);
}

$q = new AMQPQueue($channel);
// //设置队列名字 如果不存在则添加
$q->setName('queue');
$q->setFlags(AMQP_DURABLE | AMQP_AUTODELETE);
// echo "queue status: ".$q->declare();
// echo "\n";
// echo 'queue bind: '.$q->bind('exchange','route.key');
// //将你的队列绑定到routingKey
// echo "\n";
$channel->startTransaction();
echo "send: ".$ex->publish($message, 'route.key'); //将你的消息通过制定routingKey发送
$channel->commitTransaction();
$conn->disconnect();
