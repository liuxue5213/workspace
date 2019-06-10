<?php
/*
 * des加密操作类
 */
class Jiami {
    public function __construct(){
        parent::__construct();
    }

    public static function genIvParameter() {
        return mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_TRIPLEDES,MCRYPT_MODE_ECB), MCRYPT_RAND);
    }
    private static function pkcs5Pad($text, $blocksize) {
        $pad = $blocksize - (strlen($text) % $blocksize); // in php, strlen returns the bytes of $text
        return $text . str_repeat(chr($pad), $pad);
    }

    private static function pkcs5Unpad($text) {
        $pad = ord($text{strlen($text)-1});
        if ($pad > strlen($text)) return false;
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) return false;
        return substr($text, 0, -1 * $pad);
    }

    public static function encryptText($plain_text, $key, $iv) {
        $padded = self::pkcs5Pad($plain_text, mcrypt_get_block_size(MCRYPT_TRIPLEDES, MCRYPT_MODE_ECB));
        return mcrypt_encrypt(MCRYPT_TRIPLEDES, $key, $padded, MCRYPT_MODE_ECB, $iv);
    }

    public static function decryptText($cipher_text, $key, $iv) {
        $plain_text = mcrypt_decrypt(MCRYPT_TRIPLEDES, $key, $cipher_text, MCRYPT_MODE_ECB, $iv);
        return self::pkcs5Unpad($plain_text);
    }

    //调用方法
    public static function encodeDes($key,$text){
        $iv = self::genIvParameter();
        $cipher_text = self::encryptText($text, $key, $iv);
        return strtoupper( bin2hex($cipher_text));
    }

}

?>
