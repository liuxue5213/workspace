<?php
/**
 * @Author: anchen
 * @Date:   2016-02-22 14:29:54
 * @Last Modified by:   anchen
 * @Last Modified time: 2016-02-22 15:10:43
 */

// size   1-20

// http://my.tv.sohu.com/user/a/wvideo/getQRCode.do?width=200&height=200&text=%E5%88%98%E5%AD%A6
$type=$_REQUEST['type'];
$cont=$_REQUEST['cont'];
$url_long=$_REQUEST['url_long'];
$len=$_REQUEST['len'];



if($type=='0'){
    if(!$len){
        $len='200';
    }
    if(!$cont){
        header("Location:index.php");
        exit;
    }
    $qr=urlencode($cont); 
    // $url = 'http://apis.baidu.com/3023/qr/qrcode?size=20&qr='.$qr; 
    $url='http://my.tv.sohu.com/user/a/wvideo/getQRCode.do?width='.$len.'&height='.$len.'&text='.$qr;
    // $rs=api($url);

    header("Content-Type: image/jpeg;text/html; charset=utf-8");
    $img=file_get_contents($url,true);
    // //使用图片头输出浏览器
    echo $img;
}else{
    if(!$url_long){
        header("Location:index.php");
        exit;
    }
    #短链接 地址
    $url = 'http://apis.baidu.com/3023/shorturl/shorten?url_long='.$url_long;
    $rs=api($url);
    $rds=(array)$rs['urls'][0];
    echo '原链接地址为：'.$rds['url_long']."<br>";
    echo '短链接地址为：'.$rds['url_short'];
}

function api($url){
    $ch = curl_init();
    $header = array(
        'apikey:f04ee5f1626c95be0a8313b779f5620e',
    );
    // 添加apikey到header
    curl_setopt($ch, CURLOPT_HTTPHEADER  , $header);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // 执行HTTP请求
    curl_setopt($ch , CURLOPT_URL , $url);
    $res = curl_exec($ch);
    $rs=(array)json_decode($res); 
    return $rs;
}





