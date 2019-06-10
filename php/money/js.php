<?php
header("Content-Type: text/html; charset=utf-8");
require './common.php';
include "./demo/lib.php";
include "./demo/conf.php";
require_once(dirname(__FILE__) . './../push/' . 'IGt.Push.php');
require_once(dirname(__FILE__) . './../push/' . 'igetui/IGt.AppMessage.php');
require_once(dirname(__FILE__) . './../push/' . 'igetui/IGt.APNPayload.php');
require_once(dirname(__FILE__) . './../push/' . 'igetui/template/IGt.BaseTemplate.php');
require_once(dirname(__FILE__) . './../push/' . 'IGt.Batch.php');
require_once(dirname(__FILE__) . './../push/' . 'igetui/utils/AppConditions.php');

//http的域名
define('HOST','http://sdk.open.api.igexin.com/apiex.htm');

//定义常量, appId、appKey、masterSecret 采用本文档 "第二步 获取访问凭证 "中获得的应用配置               
define('APPKEY','kPzorttDWh5enlIk24Rtp8');
define('APPID','iMJ0wH4DeS6HFP5pAKVtr8');
define('MASTERSECRET','cVvtjpmgpeAhTwapuLxLa7');

class M
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

    function sendEmail($email, $info)
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
            $mail->setCc($info['cc']);
            // $mail->setBcc("XXX@126.com");
            // $mail->addAttachment("sms.zip");
            $mail->setMail($info['sub'], $info['cont']);
            $mail->sendMail();
            return ob_get_contents();
        } else {
            return '发件邮箱用户名或密码配置有误';
        }
    }

    public function getC($coins)
    {
        $tmpArr = $tmpArr2 = array();
        foreach ($coins as $key => $symbol) {
            $url = 'https://www.btc123.com/api/getTicker';
            $info = $this->curlGet($url, array('symbol'=>$symbol));
            
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
                $tmpArr[] = $items['coinSign'].' 最新价：'.$items['ticker']['dollar'].' => '.$items['ticker']['last'];
                $tmpArr2[] = $items['coinSign'].'：'.$items['ticker']['dollar'];
                $res['data'][$symbol] = $std;
            }
        }
        $info = array(
           'fromType' => 'qq',
           'addr' => 'liuxue@icsoc.net',
           'sub' => '最新价格提醒',
           'cont' => '<h3>JohnScott提醒</h3>'.implode('<br>', $tmpArr),
           'cc' => '943102912@qq.com',
           'time' => date('Y-m-d H:i:s',time())
        );
        // $this->sendEmail($emails, $info);
        $this->pushMessageToApp(implode('，', $tmpArr2));
        var_dump(implode('，', $tmpArr2));
        return $res;
    }

    //群推接口案例
    function pushMessageToApp($cont){
        $igt = new IGeTui(HOST,APPKEY,MASTERSECRET);
        //定义透传模板，设置透传内容，和收到消息是否立即启动启用
        $template = $this->IGtNotificationTemplateDemo($cont);
        //$template = IGtLinkTemplateDemo();
        // 定义"AppMessage"类型消息对象，设置消息内容模板、发送的目标App列表、是否支持离线发送、以及离线消息有效期(单位毫秒)
        $message = new IGtAppMessage();
        $message->set_isOffline(true);
        $message->set_offlineExpireTime(10 * 60 * 1000);//离线时间单位为毫秒，例，两个小时离线为3600*1000*2
        $message->set_data($template);

        $appIdList=array(APPID);
        // $phoneTypeList=array('ANDROID');
        // $provinceList=array('北京');
        // $tagList=array('haha');
        //用户属性
        //$age = array("0000", "0010");

        //$cdt = new AppConditions();
       // $cdt->addCondition(AppConditions::PHONE_TYPE, $phoneTypeList);
       // $cdt->addCondition(AppConditions::REGION, $provinceList);
        //$cdt->addCondition(AppConditions::TAG, $tagList);
        //$cdt->addCondition("age", $age);
        $message->set_appIdList($appIdList);
        //$message->set_conditions($cdt->getCondition());
        $rep = $igt->pushMessageToApp($message, "JS价格提醒");
        var_dump($rep);
        echo ("<br><br>");
    }

    function IGtNotificationTemplateDemo($cont){
        $template =  new IGtNotificationTemplate();
        $template->set_appId(APPID);                   //应用appid
        $template->set_appkey(APPKEY);                 //应用appkey
        $template->set_transmissionType(1);            //透传消息类型
        $template->set_transmissionContent("价格消息提醒");//透传内容
        $template->set_title(date('Y-m-d H:i:s').' 实时行情');                  //通知栏标题
        $template->set_text($cont);     //通知栏内容
        $template->set_logo("./push.png");
        $template->set_notifyStyle(1);                       //通知栏logo
        $template->set_logoURL("http://johnscott1989.top");   //通知栏logo链接
        $template->set_isRing(true);                   //是否响铃
        $template->set_isVibrate(true);                //是否震动
        $template->set_isClearable(true);              //通知栏是否可清除
        return $template;
    }
}



$Common = new M();
$coins = array('huobibtcusdt','huobiethusdt');
$res = $Common->getC($coins);
die;


















