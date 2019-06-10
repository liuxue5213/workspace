<?php
/**
 * @Author: anchen
 * @Date:   2017-07-13 16:58:58
 * @Last Modified by:   anchen
 * @Last Modified time: 2017-07-13 18:07:16
 */
require_once './Tickets.php';
$station = include('./name.php');
$from_station = empty($station[$_REQUEST['from_station']]) ? '' : $station[$_REQUEST['from_station']];
$to_station = empty($station[$_REQUEST['to_station']]) ? '' : $station[$_REQUEST['to_station']];
$pre_date = empty($_REQUEST['pre_date']) ? date('Y-m-d') : $_REQUEST['pre_date'];
$train_no = empty($_REQUEST['train_no']) ? '' : $_REQUEST['train_no'];
$nums = empty($_REQUEST['nums']) ? 50 : $_REQUEST['nums'];
$seat = empty($_REQUEST['seat']) ? '' : $_REQUEST['seat'];

if ($from_station && $to_station) {
    $test = new Tickets();
    $url = "https://kyfw.12306.cn/otn/leftTicket/query?leftTicketDTO.train_date=$pre_date&leftTicketDTO.from_station=$from_station&leftTicketDTO.to_station=$to_station&purpose_codes=ADULT";
    $result = $test->curlGet($url);
    if (!empty($result) && $result['httpstatus'] == 200) {
        $mark = array(
            '0' => '?',
            'remark' => '备注',
            '2' => '2400000G290B',
            'train_no' => '车次',
            'start_station' => '起始站',
            'end_station' => '终点站',
            'departure_station' => '出发站',
            'arrival_station' => '到达站',
            'departure_time' => '出发时间',
            'arrival_time' => '到达时间',
            'lasted' => '历时',
            'is_predefined' => '是否可以预定',
            '12' => '?',
            'now_time' => '当前时间 20170706',
            '14' => '?',
            '15' => '?',
            '16' => '?',
            '17' => '?',
            '18' => '?',
            '19' => '?',
            '20' => '',
            'advanced_soft_sleeper' => '高级软卧',
            'top_sleeper' => '动卧',
            'soft_sleeper' => '软卧',
            'soft_seat' => '软座',
            'principal_seat' => '特等座',
            'no_seat' => '无座',
            '27' => '',
            'harder_sleeper' => '硬卧',
            'harder_seat' => '硬座',
            'second_seat' => '二等座',
            'first_seat' => '一等座',
            '32' => '',
            'other_info' => '其他',
            '34' => '类型0',
            '35' => '类型'
        );

        // echo count($result['data']['result']);
        $res = array();
        $names = array_flip($station);
        foreach ($result['data']['result'] as $key => $val) {
            if (strstr($val, $train_no)) {
                $tmp = explode('|', $val);
                $res = array_combine(array_keys($mark), $tmp);
                break;
            }
        }

        //查找信息
        if (!empty($res)) {
            if (empty($res[$seat]) || $res[$seat] == '无' || $res[$seat] <= $nums) {
                $arr = array('code' => 601,'msg' => '余票不足，还剩'.$res[$seat].'张');
            } else {
                $seatNum = ($res[$seat] == '有') ? '' : $res[$seat].'张';
                $arr = array('code' => 600,'msg' => '还有余票'.$seatNum);
            }
        } else {
            $arr = array('code' => 602,'msg' => '车次查询已经过期');
        }
        print_r($arr);
    }
}




