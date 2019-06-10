<?php
/**
 * Redis缓存操作类
 * Created by http://www.5adian.com.
 * User: jerry.yang@5adian.com
 * File:Cache.php
 * Date: 2015/10/27
 * Time: 22:30
 */
class RedisCache {
    // protected static $logger;
    protected  $options;
    /**
     * 架构函数
     * @param array $options 缓存参数
     * @access public
     */
    public function __construct($cache="default",$options=array()) {
        // if (!isset(self::$logger)) {
        //     self::$logger = new CLog("RedisCache");
        // }
        // if ( !extension_loaded('redis') ) {
        //     self::$logger->debug("Redis缓存扩展不存在");
        // }
        // $cacheConfig=CConfig::get("redis.{$cache}");
        $cacheConfig['host']='127.0.0.1';
        $cacheConfig['port']='6379';
        $cacheConfig['expire']='';
        $cacheConfig['prefix']='';
        
        $options = array_merge(array (
            'host'          =>$cacheConfig['host'],
            'port'          =>$cacheConfig['port'],
            'expire'       =>$cacheConfig['expire'],
            'prefix'       =>$cacheConfig['prefix'],
        ),$options);
        try{
            $this->handler=new Redis();
            $this->options=$options;
            $this->handler->connect($options['host'], $options['port']);
        }catch (Exception $e){
            // self::$logger->error("Redis链接失败");
        }
    }

    /**
     * 读取缓存
     * @access public
     * @param string $name 缓存变量名
     * @return mixed
     */
    public function get($name) {
        $value = $this->handler->get($this->options['prefix'].$name);
        $jsonData  = json_decode($value);
        return ($jsonData === NULL) ? $value : $jsonData;   //检测是否为JSON数据 true 返回JSON解析数组, false返回源数据
    }

    /**
     * 写入缓存
     * @access public
     * @param string $name 缓存变量名
     * @param mixed $value  存储数据
     * @param integer $expire  有效时间（秒）
     * @return boolean
     */
    public function set($name, $value, $expire = null) {
        if(is_null($expire)) {
            $expire  =  $this->options['expire'];
        }
        $name   =   $this->options['prefix'].$name;
        //对数组/对象数据进行缓存处理，保证数据完整性
        $value  =  (is_object($value) || is_array($value)) ? json_encode($value) : $value;
        if(is_int($expire) && $expire) {
            $result = $this->handler->setex($name, $expire, $value);
        }else{
            $result = $this->handler->set($name, $value);
        }
        return $result;
    }

    /**
     * 判断某一个数据是否存在
     * @param $name
     * @return mixed
     */
    public function  nameExists($name)
    {
        $name   =   $this->options['prefix'].$name;
       return  $this->handler->exists($name);
    }
    /**
     * 删除缓存
     * @access public
     * @param string $name 缓存变量名
     * @return boolean
     */
    public function delete($name) {

        return $this->handler->delete($this->options['prefix'].$name);
    }

    /**
     * 清除缓存
     * @access public
     * @return boolean
     */
    public function clear() {
        return $this->handler->flushDB();
    }
}