<?php

use \GatewayWorker\Lib\Gateway;
require_once '../../../vendor/autoload.php';

// 判断是否有房间号
// if(!isset($message_data['room_id']))
// {
//     throw new \Exception("\$message_data['room_id'] not set. client_ip:{$_SERVER['REMOTE_ADDR']} \$message:$message");
// }

$clients_list = array();

// 把房间号昵称放到session中
$room_id = 1;
$client_name = htmlspecialchars('2');

// 获取房间内所有用户列表 
$list = Gateway::getClientSessionsByGroup($room_id);

foreach($list as $tmp_client_id => $val)
{
    $clients_list[$tmp_client_id] = $val['client_name'];
}

//$clients_list[$client_id] = $client_name;

$client_id = '7f00000108fc00000007';

// 转播给当前房间的所有客户端，xx进入聊天室 message {type:login, client_id:xx, name:xx} 
//$new_message = array(
//    'type' => 'login',
//    'client_id' => $client_id,
//    'client_name' => htmlspecialchars($client_name),
//    'time' => date('Y-m-d H:i:s')
//);

//Gateway::sendToGroup($room_id, json_encode($new_message));
//Gateway::joinGroup($client_id, $room_id);

// 给当前用户发送用户列表 
//$new_message['client_list'] = $clients_list;
//Gateway::sendToCurrentClient(json_encode($new_message));

// 客户端发言 message: {type:say, to_client_id:xx, content:xx}


$to_client_id = '7f00000108fc00000006';
$to_client_name = '1';
// 私聊
$new_message = array(
    'type'=>'say',
    'from_client_id'=>$client_id,
    'from_client_name' =>$client_name,
    'to_client_id'=>$to_client_id,
    'content'=>"<b>对你说: </b>".nl2br(htmlspecialchars('hello world')),
    'time'=>date('Y-m-d H:i:s'),
);
//Gateway::sendToClient($to_client_id, json_encode($new_message));


$new_message['content'] = "<b>你对".htmlspecialchars($to_client_name)."说: </b>".nl2br(htmlspecialchars('hello world'));
Gateway::sendToCurrentClient(json_encode($new_message));


//群发消息
//$new_message = array(
//    'type'=>'say',
//    'from_client_id'=>$client_id,
//    'from_client_name' =>$client_name,
//    'to_client_id'=>'all',
//    'content'=>nl2br(htmlspecialchars('hello world')),
//    'time'=>date('Y-m-d H:i:s'),
//);
//return Gateway::sendToGroup($room_id ,json_encode($new_message));








