<?php
require './common.php';
$name = isset($_GET['name']) ? $_GET['name']: '';
if ($name) {
	$Common = new Money();
	$key = $Common->generateKey($name);
	echo '您好:<b>'.$name.'</b> ';
	echo "<a target='_blank' href='http://localhost/workspace/php/money/index.php?name=$name&key=$key'>跳转到主页</a><br>";
	echo '您的授权Key为：'.$key;
} else {
	header("Location: http://johnscott1989.top"); 
	exit;
}