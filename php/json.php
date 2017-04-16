<?php

class Json
{
    //过滤数据中特殊字符
    function json_str_replace($str)
    {
        $str = preg_replace('/[\x00-\x1F]/','', $str);
        return trim($str);
    }

    function json_decode($json, $assoc = false) {
        try {
            //去除空格
            $jsonStr = trim($json);

            //判断不能为空
            if (empty($jsonStr)) {
                return '参数不能为空';
            }

            //转换UTF8编码
            $jsonStr = iconv('gbk', 'utf-8', $jsonStr);

            //过滤特殊字符
            $jsonStr = $this->json_str_replace($jsonStr);

            //解析json
            $tmp = json_decode($jsonStr, $assoc);
            $err = json_last_error();

            switch ($err) {
                case 1:
                    $tmp = '达到最大堆栈深度';
                    break;
                case 2:
                    $tmp = '无效或异常的 JSON';
                    break;
                case 3:
                    $tmp = '控制字符错误，编码异常';
                    break;
                case 4:
                    $tmp = '语法错误';
                    break;
                case 5:
                    $tmp = 'UTF-8编码异常';
                    break;
            }

            return $tmp;

        } catch (Exception $e) {
            
            return sprintf("JSON解析异常，异常信息为：%s", $e);
        }
    }
}

$x = array('123','123《》><1211',array('aac12"'=>'kshdhjk2'),4);
// $x = 'dsadk12j3lk1282384jkjkjk{}><》';
$str = json_encode($x);
$json = new Json();
$arr = $json->json_decode($str);
var_dump($arr);

?>