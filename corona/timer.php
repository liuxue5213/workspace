<?php
require_once 'get.php';
require_once 'corona.php';

$corona = new Corona();
$data = $corona->index();
var_dump('data refresh success');

$corona = new CoronaInfo();
$data = $corona->index();
var_dump('info refresh success');
