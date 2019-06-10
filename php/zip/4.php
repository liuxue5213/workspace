<?php

$filename = "./test.zip";
$str = "This is a test string.\n";

// 打开一个文件用于写入
$bz = bzopen($filename, "w");

// 写入字符串到文件
bzwrite($bz, $str);

// 关闭文件
bzclose($bz);

// 打开文件用于读取
$bz = bzopen($filename, "r");

// 读取 10 个字符
echo bzread($bz, 10);

// 输出直到文件末尾（或者后面的 1024 个字符），并关闭。 
echo bzread($bz);

bzclose($bz);


?> 
