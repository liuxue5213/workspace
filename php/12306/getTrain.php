<?php
/**
 * @Author: anchen
 * @Date:   2017-06-30 13:26:14
 * @Last Modified by:   anchen
 * @Last Modified time: 2020-01-06 09:22:05
 */

require_once './Tickets.php';

$station = include('./name.php');
if ($_POST) {
    $from_station = empty($station[$_POST['from_station']]) ? '' : $station[$_POST['from_station']];
    $to_station = empty($station[$_POST['to_station']]) ? '' : $station[$_POST['to_station']];
    $train_date = empty($_POST['train_date']) ? date('Y-m-d') : $_POST['train_date'];
    $limit = empty($_POST['limit']) ? 1 : $_POST['limit']; //页码
    $size = empty($_POST['size']) ? 100 : $_POST['size']; //数量
    $is_use_page = isset($_POST['use_page']) ? true : false; //是否数据分页

    if ($from_station && $to_station) {
        $test = new Tickets();
        $url = "https://kyfw.12306.cn/otn/leftTicket/queryZ?leftTicketDTO.train_date=$train_date&leftTicketDTO.from_station=$from_station&leftTicketDTO.to_station=$to_station&purpose_codes=ADULT";
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
                '35' => '类型1',
                '36' => '类型2'
            );

            // echo count($result['data']['result']);
            $res = array();
            $names = array_flip($station);
            foreach ($result['data']['result'] as $key => $val) {
                $tmp = explode('|', $val);
                $res[] = array_combine(array_keys($mark), $tmp);
                $res[$key]['start_station'] = @$names[$res[$key]['start_station']];
                $res[$key]['end_station'] = @$names[$res[$key]['end_station']];
                $res[$key]['departure_station'] = @$names[$res[$key]['departure_station']];
                $res[$key]['arrival_station'] = @$names[$res[$key]['arrival_station']];
            }
            // if ($is_use_page) {
            //     $res = array_slice($res, $limit, $size);
            // }
            echo json_encode(array('total' => count($res), 'rows' => $res));
        }
    }
}




