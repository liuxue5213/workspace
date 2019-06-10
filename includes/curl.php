<?php
/**
 * @Author: anchen
 * @Date:   2015-11-20 10:17:33
 * @Last Modified by:   anchen
 * @Last Modified time: 2015-12-22 14:55:37
 */
class curl {



    /*
     * get 方式获取访问指定地址
    * @param  string url 要访问的地址
    * @param  string cookie cookie的存放地址,没有则不发送cookie
    * @return string curl_exec()获取的信息
    * @author andy
    **/
    public static function get( $url, $cookie='' )
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 60);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_URL, $url);
        $res = curl_exec($curl);
        curl_close($curl);
        return $res;
    }

    /*
     * post 方式模拟请求指定地址
    * @param  string url    请求的指定地址
    * @param  array  params 请求所带的
    * #patam  string cookie cookie存放地址
    * @return string curl_exec()获取的信息
    * @author andy
    **/
    public static function post( $url,$jsonmenu)
    {
        return self::httpPost($url,$jsonmenu);
    }

    /**
     * 发送HTTP请求
     * @param $url
     * @param string $post
     * @param array $extra
     * @param int $timeout
     * @return bool
     */
    static  function httpRequest($url, $post = '', $extra = array(), $timeout = 60) {
        $urlset = parse_url($url);
        if(empty($urlset['path'])) {
            $urlset['path'] = '/';
        }
        if(!empty($urlset['query'])) {
            $urlset['query'] = "?{$urlset['query']}";
        }
//        if(empty($urlset['port'])) {
//            $urlset['port'] = $urlset['scheme'] == 'https' ? '443' : '80';
//        }
        if(function_exists('curl_init') && function_exists('curl_exec')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,trim($url));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 1);
            if($post) {
                curl_setopt($ch, CURLOPT_POST, 1);
                if (is_array($post)) {
                    $post = http_build_query($post);
                }
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
            }
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:9.0.1) Gecko/20100101 Firefox/9.0.1');
            if (!empty($extra) && is_array($extra)) {
                $headers = array();
                foreach ($extra as $opt => $value) {
                    if (self::strexists($opt, 'CURLOPT_')) {
                        curl_setopt($ch, constant($opt), $value);
                    } elseif (is_numeric($opt)) {
                        curl_setopt($ch, $opt, $value);
                    } else {
                        $headers[] = "{$opt}: {$value}";
                    }
                }
                if(!empty($headers)) {
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                }
            }
            $data = curl_exec($ch);
            $status = curl_getinfo($ch);
            $errno = curl_errno($ch);
            curl_close($ch);
            if($errno || empty($data)) {
                return false;
            } else {
                return self::httpResponseParse($data);
            }
        }
        $method = empty($post) ? 'GET' : 'POST';
        $fdata = "{$method} {$urlset['path']}{$urlset['query']} HTTP/1.1\r\n";
        $fdata .= "Host: {$urlset['host']}\r\n";
        if(function_exists('gzdecode')) {
            $fdata .= "Accept-Encoding: gzip, deflate\r\n";
        }
        $fdata .= "Connection: close\r\n";
        if (!empty($extra) && is_array($extra)) {
            foreach ($extra as $opt => $value) {
                if (!self::strexists($opt, 'CURLOPT_')) {
                    $fdata .= "{$opt}: {$value}\r\n";
                }
            }
        }
        $body = '';
        if ($post) {
            if (is_array($post)) {
                $body = http_build_query($post);
            } else {
                $body = urlencode($post);
            }
            $fdata .= 'Content-Length: ' . strlen($body) . "\r\n\r\n{$body}";
        } else {
            $fdata .= "\r\n";
        }
        if($urlset['scheme'] == 'https') {
            $fp = fsockopen('ssl://' . $urlset['host'], $urlset['port']);
        } else {
            $fp = fsockopen($urlset['host'], $urlset['port']);
        }
        stream_set_blocking($fp, true);
        stream_set_timeout($fp, $timeout);
        if (!$fp) {
            return false;
        } else {
            fwrite($fp, $fdata);
            $content = '';
            while (!feof($fp))
                $content .= fgets($fp, 512);
            fclose($fp);
            return self::httpResponseParse($content, true);
        }
    }
    
    static  function httpResponseParse($data, $chunked = false) {
        $rlt = array();
        $pos = strpos($data, "\r\n\r\n");
        $split1[0] = substr($data, 0, $pos);
        $split1[1] = substr($data, $pos + 4, strlen($data));

        $split2 = explode("\r\n", $split1[0], 2);
        preg_match('/^(\S+) (\S+) (\S+)$/', $split2[0], $matches);
        $rlt['code'] = $matches[2];
        $rlt['status'] = $matches[3];
        $rlt['responseline'] = $split2[0];
        @$header = explode("\r\n", $split2[1]);
        $isgzip = false;
        $ischunk = false;
        foreach ($header as $v) {
            $row = explode(':', $v);
            $key = trim($row[0]);
            @$value = trim($row[1]);
            if(!$isgzip && strtolower($key) == 'content-encoding' && strtolower($value) == 'gzip') {
                $isgzip = true;
            }
            if(!$ischunk && strtolower($key) == 'transfer-encoding' && strtolower($value) == 'chunked') {
                $ischunk = true;
            }
        }
        if($chunked && $ischunk) {
            $rlt['content'] = httpResponseParse_unchunk($split1[1]);
        } else {
            $rlt['content'] = $split1[1];
        }
        if($isgzip && function_exists('gzdecode')) {
            $rlt['content'] = gzdecode($rlt['content']);
        }

        $rlt['meta'] = $data;
        if($rlt['code'] == '100') {
            return httpResponseParse(@$rlt['content']);
        }
        return $rlt;
    }
    static function httpResponseParse_unchunk($str = null) {
        if(!is_string($str) or strlen($str) < 1) {
            return false;
        }
        $eol = "\r\n";
        $add = strlen($eol);
        $tmp = $str;
        $str = '';
        do {
            $tmp = ltrim($tmp);
            $pos = strpos($tmp, $eol);
            if($pos === false) {
                return false;
            }
            $len = hexdec(substr($tmp, 0, $pos));
            if(!is_numeric($len) or $len < 0) {
                return false;
            }
            $str .= substr($tmp, ($pos + $add), $len);
            $tmp  = substr($tmp, ($len + $pos + $add));
            $check = trim($tmp);
        } while(!empty($check));
        unset($tmp);
        return $str;
    }
    static  function httpGet($url) {
        return self::httpRequest($url);
    }
    static  function httpPost($url, $data) {
        $headers = array('Content-Type' => 'application/x-www-form-urlencoded');
        $result=self::httpRequest($url, $data, $headers);
        if(isset($result['code']) && isset($result['content']) && !empty($result['content'])){
            return $result['content'];
        }
        return false;
    }


    static function strexists($string, $find)
    {
        return !(strpos($string, $find) === FALSE);
    }
}