<?php

$dom = new DOMDocument('1.0','utf-8');//建立DOM对象
$no1 = $dom->createElement('config');//创建普通节点：booklist
$dom->appendChild($no1);//把booklist节点加入到DOM文档中

$no2 = $dom->createElement('user');//创建book节点
$no1->appendChild($no2);//把book节点加入到booklist节点中

$no3 = $dom->createAttribute('id');//创建属性节点：id
$no3->value = 1;//给属性节点赋值
$no2->appendChild($no3);//把属性节点加入到book节点中

$no3 = $dom->createElement('title');
$no2->appendChild($no3);
$no4 = $dom->createTextNode('天龙八部');//创建文本节点：天龙八部
$no3->appendChild($no4);//把天龙八部节点加入到book节点中



// $no3 = $dom->createElement('author');
// $no2->appendChild($no3);
// $no4 = $dom->createTextNode('金庸');//创建文本节点：天龙八部
// $no3->appendChild($no4);//把天龙八部节点加入到book节点中

// header('Content-type:text/html;charset=utf-8');
// echo $dom->save('./xml/user.xml')?'存储成功':'存储失败';//存储为xml文档

// 直接以xml文档格式打开
// header('Content-type:text/xml');
// echo $dom->savexml();




