<?php

require './common.php';
require '../../rabbit/SendMail.php';
require '../../rabbit/config.php';
include "./demo/lib.php";
include "./demo/conf.php";

// //实例化类库
// $req = new req();
// // 获取账户余额示例
// // var_dump($req->get_detail_merged('btcusdt'));
// // $rows = $req->get_history_kline('btcusdt' ,'1min', 150);
// // var_dump($rows);
// var_dump($req->get_common_currencys());
// die;


$tmp = array();
$Common = new Money();
$type = isset($_POST['type']) ? $_POST['type']: '';

$url = 'https://api-otc.huobi.pro/v1/otc/trade/list/public';
switch ($type) {
case 'detail':
    break;
case 'homepage':
    break;
case 'data':
    // get_history_kline($symbol = '', $period='',$size=0)
    // // 获取聚合行情(Ticker)    
    // get_detail_merged($symbol = '')
    // // 获取 Market Depth 数据   
    // get_market_depth($symbol = '', $type = '')    
    // // 获取 Trade Detail 数据
    // get_market_trade($symbol = '')   
    // // 批量获取最近的交易记录    
    // get_history_trade($symbol = '', $size = '')
    // // 获取 Market Detail 24小时成交量数据
    // get_market_detail($symbol = '')
    $res = [];
    $coin_name = './xml/coin-list.xml';
    $action = isset($_POST['action']) ? $_POST['action']: '';
    $req = new req();
    if ($action == 'init') {
        //初始化所有币种
        $rows = $req->get_common_currencys();
        if (isset($rows->status) && $rows->status == 'ok' && $rows->data) {
            $dom = new DOMDocument('1.0','utf-8');//建立DOM对象
            $no1 = $dom->createElement('coin');//创建普通节点：booklist
            $dom->appendChild($no1);//把booklist节点加入到DOM文档中
            foreach ($rows->data as $key => $val) {
                $no2 = $dom->createElement('name');//创建book节点
                $no1->appendChild($no2);//把book节点加入到booklist节点中
                $no3 = $dom->createTextNode($val);//创建文本节点：天龙八部
                $no2->appendChild($no3);//把天龙八部节点加入到book节点中
            }
            header('Content-type:text/html;charset=utf-8');
            $res = $dom->save($coin_name)?200:500;//存储为xml文档
        }
    } elseif ($action == 'list') {
        if (file_exists($coin_name)) {
            $xml_array = simplexml_load_file($coin_name);
            $res = (Array)$xml_array->name;
            // foreach ((Array)$xml_array->name as $val) {
            //     $res[] = $val;
            // }
        }
    } elseif ($action == 'add') {
        $id = isset($_POST['id']) ? $_POST['id']: '';
        $bcoin = isset($_POST['bcoin']) ? $_POST['bcoin']: '';
        $scoin = isset($_POST['scoin']) ? $_POST['scoin']: '';
        $user = isset($_POST['user']) ? $_POST['user']: 'johnscott';

        $coin_name = './xml/'.$user.'-setting.xml';
        $dom = new DOMDocument('1.0','utf-8');//建立DOM对象 
        $no1 = $dom->createElement('setting');
        $dom->appendChild($no1);//把booklist节点加入到DOM文档中
        $no2 = $dom->createElement('coins');//创建book节点
        $no1->appendChild($no2);//把book节点加入到booklist节点中
        
        // $no3 = $dom->createAttribute('id');//创建属性节点：id
        // $no3->value = 'coin'.$id;//给属性节点赋值
        // $no2->appendChild($no3);//把属性节点加入到book节点中
        // $no3 = $dom->createElement('name');//创建book节点
        // $no2->appendChild($no3);//把天龙八部节点加入到book节点中
        
        $no3 = $dom->createTextNode($bcoin.$scoin);//创建文本节点：天龙八部
        $no2->appendChild($no3);
        header('Content-type:text/html;charset=utf-8');
        $dom->save($coin_name);
        $res = $Common->getCoinInfo($user);
    } elseif ($action == 'edit') {


    } elseif ($action == 'del') {

    } elseif ($action == 'info') {
        $user = isset($_POST['user']) ? $_POST['user']: 'johnscott';
        $res = $Common->getCoinInfo($user);
    }
    echo json_encode($res);
    break;    
default:
    $is_show = 0;
    $list = $res = array();
    $source = isset($_POST['source']) ? $_POST['source']: '';
    $is_all = isset($_POST['is_all']) ? $_POST['is_all']: '';
    $mtime = isset($_POST['mtime']) ? $_POST['mtime']: 0;

    if ($source == 1) {
        $array = array(
            'coinId' => isset($_POST['coinId']) ? $_POST['coinId']: '',
            'tradeType' => isset($_POST['tradeType']) ? $_POST['tradeType']: '',
            'online' => isset($_POST['online']) ? $_POST['online']: '',
            'currentPage' => 1,
            'payWay' => '',
            'country' => '',
            'merchant' => '',
            'range' => 0,
            'currPage' => 1,
        );
        $tmp = $Common->curlGet($url, $array);
        if ($tmp) {
            $total = $tmp['totalCount'];
            $ttt = $tmp['data'];
            //查询全部
            if ($is_all) {
                for ($i = 2;$i <= $tmp['totalPage']; $i++) {
                    $array['currPage'] = $i;
                    $ttttt = $Common->curlGet($url, $array);
                    foreach ($ttttt['data'] as $key => $val) {
                        array_push($ttt, $val);
                    }
                }
            }
        }

        $payMethod = array(
            '1' => '银行卡',
            '2' => '支付宝',
            '3' => '微信',
        );

        if (isset($ttt)) {
            $is_m = $is_m2 = 1;
            foreach ($ttt as $key => $val) {
                $pName = [];
                $aa = explode(',', $val['payMethod']);
                foreach ($aa as $v) {
                    $pName[] = isset($payMethod[$v]) ? $payMethod[$v] : '';
                }
                $data = array(
                    'id' => $val['id'],
                    'userId' => $val['userId'],
                    'userName' => $val['userName'],
                    'isOnline' => $val['isOnline'] ? '是' : '否',
                    'payMethod' => implode(',', $pName), //支付方式 1 2支付宝 3微信
                    'price' => $val['price'], //价格
                    'tradeCount' => $val['tradeCount'], //总量
                    'tradeMonthTimes' => $val['tradeMonthTimes'], //近30日成交
                );
                array_push($res, $data);
                //发送邮件
                $diffTime = time() - $mtime;
                if ($is_m && $_POST['coinId'] == 2 && $diffTime > 200 && $val['price'] <= 6.4) {
                    $is_m = 0;
                    $info = array(
                       'fromType' => 'qq',
                       'addr' => 'liuxue@icsoc.net',
                       'sub' => 'now rmb to usdt is '.$val['price'],
                       'cont' => '',
                       'cc' => '943102912@qq.com',
                       'bcc' => '',
                       'asc' => '',
                       'time' => date('Y-m-d H:i:s',time())
                    );
                    $aaa = $Common->send($emails, $info);
                    $mtime = time();
                }

                // if ($is_m2 && $_POST['coinId'] == 3 && $diffTime > 200 && $val['price'] <= 6200) {
                //     $is_m2 = 0;
                //     $info = array(
                //        'fromType' => 'qq',
                //        'addr' => 'liuxue@icsoc.net',
                //        'sub' => 'now rmb to eth is '.$val['price'],
                //        'cont' => '',
                //        'cc' => '943102912@qq.com',
                //        'bcc' => '',
                //        'asc' => '',
                //        'time' => date('Y-m-d H:i:s',time())
                //     );
                //     $aaa = $Common->send($emails, $info);
                //     $mtime = time();
                // }
            }
        }
    }

    echo json_encode(
        array(
            'total' => isset($total) ? $total : '',
            'rows' => $res,
            'mtime' => $mtime
        )
    );
    break;
}
?>