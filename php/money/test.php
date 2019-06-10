<?php

require './common.php';
include "./demo/lib.php";
include "./demo/conf.php";

$str = array();
$Common = new Money();
$coins = array('huobibtcusdt', 'huobiethusdt', 'huobieosusdt', 'huobiqtumusdt');
if ($coins) {
foreach ($coins as $key => $symbol) {
    $url = 'https://www.btc123.com/api/getTicker';
    $info = $Common->curlGet($url, array('symbol'=>$symbol));
    
    if (@isset($info['des']) && @$info['des'] == '调用成功') {
        $items = $info['datas'];
        $std = array(
            'from' => $items['cName'], //交易所
            'coinId' => $items['coinId'], //货币ID
            'coinName' => $items['coinName'], //货币名称
            'coinSign' => $items['coinSign'], //货币简称
            'moneyType' => $items['moneyType'], //法币类型（1为人民币，2位美元）
            'type' => $items['type'], //币类型（0：比特币，1：莱特币，100: 山寨币）
            'dollar' => $items['ticker']['dollar'], //最新成交价
            'last' => $items['ticker']['last'], //最新成交价（人民币）
            'buy' => $items['ticker']['buy'], //买一价
            'buydollar' => $items['ticker']['buydollar'], //买一价（美元）
            'high' => $items['ticker']['high'], //最高价
            'highdollar' => $items['ticker']['highdollar'], //最高价（美元）
            'low' => $items['ticker']['low'], //最低价
            'lowdollar' => $items['ticker']['lowdollar'], //最低价（美元）
            'riseRate' => $items['ticker']['riseRate'], //
            'sell' => $items['ticker']['sell'], //卖一价
            'selldollar' => $items['ticker']['selldollar'], //卖一价（美元）
            'vol' => $items['ticker']['vol'], //成交量
        );
        $res[$symbol] = $std;
        $str[] = $items['coinName'].'价格'.$items['ticker']['dollar'];
    }
}             
}
$cont = isset($_GET['cont']) ? $_GET['cont'] : '';
// $url = 'http://tts.baidu.com/text2audio?lan=zh&ie=UTF-8&spd=2&text='.implode(',', $str);
$url = 'http://tts.baidu.com/text2audio?lan=zh&ie=UTF-8&spd=2&text='.$cont;
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="refresh" content="20" />
	<title></title>
</head>
    <form action="">
        <input type="text" name="cont">
        <input type="submit">
    </form>
    <?php if ($cont) { ?>
        <iframe src="<?php echo $url;?>"></iframe>
    <?php }?>
    <!-- <iframe src="http://tts.baidu.com/text2audio?lan=zh&ie=UTF-8&spd=2&text=大家好 我系古天乐 大家好 我系渣渣辉 大家好 我是刘学 祝大家春节快乐"></iframe> -->
    
</body>
</html>



