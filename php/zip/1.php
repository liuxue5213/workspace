<?php

$zip = new ZipArchive;//新建一个ZipArchive的对象
/*
通过ZipArchive的对象处理zip文件
$zip->open这个方法的参数表示处理的zip文件名。
如果对zip文件对象操作成功，$zip->open这个方法会返回TRUE
*/
if ($zip->open('test.zip') === TRUE){
    $zip->extractTo('');//假设解压缩到在当前路径下images文件夹的子文件夹php
    $zip->close();//关闭处理的zip文件
}


