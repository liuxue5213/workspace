<?php
/**
 * @Author: anchen
 * @Date:   2020-03-27 11:02:44
 * @Last Modified by:   anchen
 * @Last Modified time: 2020-03-29 15:42:21
 */
require_once 'redis.php';
require_once 'corona.php';
require_once 'checkIp.php';

$config = array(
	'host' => '127.0.0.1',
	'port' => '6379'
);
$redis = new Predis($config);
if ($redis->exists('coronaInfo')) {
	$info = $redis->hGetAll('coronaInfo');
} else {
	$corona = new CoronaInfo();
	$info = $corona->index(1);
}

// $ip = new Ip();
// $info['is_cn'] = $ip->index();

print_r(json_encode($info));
