<?php
/**
 * Class zhihu
 *
 * @category Zhihu
 * @package  Zhihu
 * @author   liuxue <liuxue_890725@qq.com>
 * @license  http://johnscott1989.top 1.0
 * @link     https://www.zhihu.com
 */
class Zhihu
{
    // public function __construct()
    // {
    //     return $this->container;
    // }

    /**
     * 采集数据
     * @param $url
     * @param bool $decode
     * @return mixed
     */
    public function curlGet($url, $decode = true)
    {
        $ch = curl_init();
        $timeout = 5;

        $header = [
            'Accept:*/*',
            'Accept-Charset:GBK,utf-8;q=0.7,*;q=0.3',
            'Accept-Encoding:gzip,deflate,sdch',
            'Accept-Language:zh-CN,zh;q=0.8,ja;q=0.6,en;q=0.4',
            'Connection:keep-alive',
            'Accept-Encoding:gzip,deflate,br',
            'authorization:oauth c3cef7c66a1843f8b3a9e6a1e3160e20',
            'Connection:keep-alive',
//            'Cookie:d_c0="AJCCWiaKUguPTlNJGiXnZIBxZLiHg0fkWgU=|1487297703"; _zap=a673675a-3ddc-4ed2-9297-5831700e2991; q_c1=aff96ccb5eb246b39667100edb904d4a|1502159973000|1487297703000; q_c1=aff96ccb5eb246b39667100edb904d4a|1515127478000|1487297703000; aliyungf_tc=AQAAACjfxQvslwAASgZFeVVGlnFpoDPg; _xsrf=3dacac04-bdeb-4893-9f94-ed7c00d8c582; __utma=155987696.382916825.1516159217.1516159217.1516159217.1; __utmc=155987696; __utmz=155987696.1516159217.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none); capsion_ticket="2|1:0|10:1516159108|14:capsion_ticket|44:OWE0YmNjMTYxMmFiNGQzZTg3MzU1NWI3OTZjZmI2ZTA=|dcb97c4a2a6c17e45190c09cc0bd73f4980d73b16f38f4f1fb7ba7a5442e050b"; l_cap_id="YmFlMGVhMGFhMzg5NDI1NjlhNDk5NGJiZmI5NTBlZTI=|1516169382|7036042df9138b0b6dc74a148913347b6526930f"; r_cap_id="ZWZmMWViNzg2YWZjNDQ5ZThmNWViYjYxOGYyM2JmMDI=|1516169382|4371c2ff3e1c36f3d851d4e673fa50eb62b1413d"; cap_id="YWQ0NDA1OGNjNDAzNDAyODk0MmU0NDMxOTRlZDUxNmE=|1516169382|8f8f186953b264d94cc6cd0f55ce560f6ca63558"',
            'Host:www.zhihu.com',
            'Referer:https://www.zhihu.com/',
            'User-Agent:Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.132 Safari/537.36',
            'X-UDID:AJCCWiaKUguPTlNJGiXnZIBxZLiHg0fkWgU='
        ];
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

        //重复访问 验证码处理
        if (isset($result['error']['code']) && $result['error']['code'] == '40352') {
            $rUrl = 'https://www.zhihu.com/api/v4/anticrawl/captcha_appeal';
            $tmp = $this->curlGet($rUrl);
            // $rUrl = $result['error']['redirect'];
            // $aaaa = file_get_contents($rUrl);
            // $aaaa = file_get_contents(file_put_contents('check.html', $aaaa));
            // var_dump($aaaa);
            // $pattern = '/<img src="(.*?)" alt="验证码" class="Unhuman-captcha">/';
            // preg_match_all($pattern, $aaaa, $match);
            var_dump($tmp);
            die;
            if (isset($tmp['img_base64'])) {
                // $cont = '<!DOCTYPE html><html><head><meta charset="utf-8"><img src="data:image/png;base64,'.$tmp['img_base64'].'"/><meta http-equiv="X-UA-Compatible" content="IE=edge"></head><body></body></html>';
                // file_put_contents('captcha.html', $cont);
                // file_get_contents('')
                $this->saveBase64Img('./', $tmp['img_base64'], $fileName = '');

            }
            // https://www.zhihu.com/api/v4/anticrawl/captcha_appeal  
        }
        return $result;
    }

    public function saveBase64Img($fileDir, $image, $fileName = '')
    {
        $res = false;
        if ($fileDir && $image) {
            header('Content-type:text/html;charset=utf-8');
            // if (!file_exists($fileDir)) {
            //     mkdir($fileDir, 0775);
            // }

            if ($fileName) {
                $fileDir .= '/'.$fileName;
            }

            echo $fileDir;
            die;

            if (file_put_contents($fileDir, base64_decode($image))) {
                $res = true;
            }
        }
        return true;
    }

    /**
     * 获取2017总结左右标题
     *
     * @param  string $url     当前页面地址
     * @param  string $nextUrl 下一页地址
     * @return array $tmp 返回的数据
     */
    public function getTitle($url, &$nextUrl)
    {
        $tmp = $match = array();
        $baseUrl = 'https://www.zhihu.com';
        $nextUrl = $baseUrl.$url;
        // echo $nextUrl;
        $rows = $this->curlGet($nextUrl);
        // $dd = file_get_contents($nextUrl);
        // echo ($dd);
        // die;

        if ($rows) {
            $nextUrl = isset($rows['paging']['next']) ? $rows['paging']['next'] : '';
            if ($nextUrl) {
                if (isset($rows['htmls'])) {
                    foreach ($rows['htmls'] as $key => $val) {
                        $pattern = '/<h2 class="item-title"><a (.*?) target="_blank" href="(.*?)">(.*?)<\/a><\/h2>/';
                        preg_match_all($pattern, $val, $match[$key]);
                        $tmp[$key]['href'] = $match[$key][2][0];
                        $tmp[$key]['title'] = $match[$key][3][0];
                    }
                }
            }
        }
        return $tmp;
    }

    public function getDetail($url, &$data)
    {
        $url = str_replace('http', 'https', $url);
        $rows = $this->curlGet($url);
        if (isset($rows['paging'])) {
            $p = $rows['paging'];
            $is_end = $p['is_end'];
            $next = $p['next'];

            if ($data) {
                foreach ($data as $key => $val) {
                    array_push($rows['data'], $val);
                }
            }
            $data = $rows['data'];

            if (!$is_end && $next) {
                return $this->getDetail($next, $data);
            }
        }
        return $data;
    }

    public function saveImage($path)
    {
        if (!preg_match('/\/([^\/]+\.[a-z]{3,4})$/i', $path, $matches)) {
            die('Use image please');
        }
        $image_name = strToLower($matches[1]);
        $ch = curl_init($path);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
        $img = curl_exec($ch);
        curl_close($ch);
        $fp = fopen($image_name, 'w');
        fwrite($fp, $img);
        fclose($fp);
    }
}