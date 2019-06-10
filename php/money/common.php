<?php

/**
 * Class Money
 */
class Money
{
    // public function __construct()
    // {
    //     return $this->container;
    // }

    public function curlGet($url, $param = array(), $decode = true)
    {
        if ($param) {
            $url .= '?'.http_build_query($param);
        }
        $ch = curl_init();
        $timeout = 10;
        $header = [];
        //        $header = [
        //            'Access-Control-Allow-Credentials:true',
        //            'Access-Control-Allow-Origin:https://otc.huobi.pro',
        //            'Connection:keep-alive',
        //            'Content-Encoding:gzip',
        //            'Content-Type:application/json;charset=UTF-8',
        //            'Strict-Transport-Security:max-age=86400',
        //            'Transfer-Encoding:chunked',
        //            'Vary:Origin',
        //            'Vary:Accept-Encoding',
        //            'X-Application-Context:gateway-zuul:prod:8080',
        //            'X-Cache:bypass',
        //        ];
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_ENCODING, "gzip"); //指定gzip压缩
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);  // 从证书中检查SSL加密算法是否存在
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $result = curl_exec($ch);
        curl_close($ch);
        $decode && $result = json_decode($result, true);
        return $result;
    }

    public function checkUserInfo($name = '', $key = '')
    {
        $check = false;
        session_start();
        if (!empty($_SESSION)) {
            $check = ($_SESSION['username'] && $_SESSION['key']) ? true: false;
        } else {
            if ($name && $key) {
                $check = password_verify($name.date('Y-m-d'), $key);
            }
        }
        return $check;
    }

    public function generateKey($name)
    {
        $str = '';
        $xml = './xml/white-list.xml';
        if (file_exists($xml)) {
            $sArr = simplexml_load_file($xml);
            $users = (Array)$sArr->user;

            //查看是否在白名单中
            if (in_array($name, $users)) {
                $str = password_hash($name.date('Y-m-d'), PASSWORD_DEFAULT);
                session_start();
                $_SESSION["username"] = $name;
                $_SESSION["key"] = $str;
            }
        }
        return $str;
    }

    function xmlToArray($xml)
    {    
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);        
        return $values;
    }

    public function getDetail($url, &$data)
    {
//        $url = str_replace('http', 'https', $url);
//        $rows = $this->curlGet($url);
//        if (isset($rows['paging'])) {
//            $p = $rows['paging'];
//            $is_end = $p['is_end'];
//            $next = $p['next'];
//
//            if ($data) {
//                foreach ($data as $key => $val) {
//                    array_push($rows['data'], $val);
//                }
//            }
//            $data = $rows['data'];
//
//            if (!$is_end && $next) {
//                return $this->getDetail($next, $data);
//            }
//        }
//        return $data;
    }

    function send($email, $info)
    {
        if (empty($info)) {
            return '队列消息为空';
        }

        //使用哪个邮箱服务器 配置的邮箱信息
        $fromType = empty($info['fromType']) ? 'sina' : $info['fromType'];
        //用户名
        $username = empty($email[$fromType]['username']) ? '' : $email[$fromType]['username'];
        //密码
        $password = empty($email[$fromType]['password']) ? '' : $email[$fromType]['password'];

        //用户名密码不能为空
        if (!empty($username) && !empty($password)) {
            ob_clean();
            ob_start();
            $mail = new SendMail();
            $mail->setServer('smtp.'.$fromType.'.com', $username, $password, 465, true);
            $mail->setFrom($username);
            $mail->setReceiver($info['addr']);
            // $mail->setReceiver("XXX@qq.com");
            // $mail->setCc("XXX@126.com");
            // $mail->setBcc("XXX@126.com");
            // $mail->addAttachment("sms.zip");
            $mail->setMail($info['sub'], $info['cont']);
            $mail->sendMail();
            return ob_get_contents();
        } else {
            return '发件邮箱用户名或密码配置有误';
        }
    }

    public function getCoinInfo($user)
    {
        $res['code'] = 500;
        $coin_name = './xml/'.$user.'-setting.xml';
        if (file_exists($coin_name)) {
            // $aaa = $req->get_history_kline($symbol, '5min', '10');
            // $bbb = $req->get_history_trade($symbol, 10);
            // $info = $req->get_detail_merged($symbol);
            // if (isset($info->status) && $info->status == 'ok') {
            //     $std = array(
            //         'id' => $info->tick->id, //K线id
            //         'nowtime' => $info->ts,
            //         'open' => $info->tick->open, //开盘价
            //         'close' => $info->tick->close, //收盘价 最新价
            //         'amount' => $info->tick->amount, //成交量
            //         'count' => $info->tick->count, //成交笔数
            //         'vol' => $info->tick->vol, //成交额, 即 sum(每一笔成交价 * 该笔的成交量)
            //         'low' => $info->tick->low, //最低价
            //         'high' => $info->tick->high, //最高价
            //         'nowtime' => $info->tick->id
            //     );
            //     $res['data'][$symbol] = $std;
            // }
            $sArr = simplexml_load_file($coin_name);
            $coins = (Array)$sArr->coins;
            foreach ($coins as $key => $symbol) {
                $url = 'https://www.btc123.com/api/getTicker';
                $info = $this->curlGet($url, array('symbol'=>$symbol));
                
                //邮件提醒
                $mailInfo = $this->getEmail($user, $symbol);

                if (isset($info['des']) && $info['des'] == '调用成功') {
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
                    $res['data'][$symbol] = $std;
                }
            }
            $res['code'] = 200;
        }
        return $res;
    }

    public function getEmail($user, $symbol) {
        $coin_name = './xml/email.xml';
        if (file_exists($coin_name)) {
            



        }
        die;
        return $arr;
    }



    // <p class="quzhi">
    //     <em>btcchinabtccny</em>
    //     <em>huobibtccny</em>
    //     <em>okcoincnbtccny</em>
    //     <em>chbtcbtccny</em>
    //     <em>coinbasebtcusd</em>
    //     <em>bitfinexbtcusd</em>
    //     <em>bitstampbtcusd</em>
    //     <em>btcchinaltccny</em>
    //     <em>huobiltccny</em>
    //     <em>okcoincnltccny</em>
    //     <em>chbtcltccny</em>
    //     <em>qiltcusdfuture</em>
    //     <em>bitfinexltcusd</em>
    //     <em>qibtcusdfuture</em>
    //     <em>bitvcbtccnyfuture</em>
    //     <em>okcoinbtcusdfuture</em>
    //     <em>okcoinbtcusd</em>
    //     <em>bterbtccny</em>
    //     <em>bityesbtcusd</em>
    //     <em>btc38btccny</em>
    //     <em>btcebtcusd</em>
    //     <em>btctradebtccny</em>
    //     <em>yunbibtccny</em>
    //     <em>bterltccny</em>
    //     <em>okcoinltcusdfuture</em>
    //     <em>okcoinltcusd</em>
    //     <em>btceltcusd</em>
    //     <em>btc38ltccny</em>
    //     <em>btctradeltccny</em>
    //     <em>bterbtscny</em>
    //     <em>yunbibtscny</em>
    //     <em>bterdashcny</em>
    //     <em>bterdogecny</em>
    //     <em>bternxtcny</em>
    //     <em>bterppccny</em>
    //     <em>bterxmrcny</em>
    //     <em>bternmccny</em>
    //     <em>bterxcpcny</em>
    //     <em>bterxtccny</em>
    //     <em>bterifccny</em>
    //     <em>bterdtccny</em>
    //     <em>bterzetcny</em>
    //     <em>btc123btccny</em>
    //     <em>btc123btcusd</em>
    //     <em>btc123btccnymix</em>
    //</p>
}