<?php
/**
 * @Author: anchen
 * @Date:   2016-01-20 09:14:05
 * @Last Modified by:   anchen
 * @Last Modified time: 2016-01-20 17:14:13
 */
$redis = new Redis();
//php客户端设置的ip及端口
$redis->connect('127.0.0.1','6379');  
//存储一个 值
// $redis->set('say','Hello World');
// print($redis->get('12312'));     //应输出Hello World

// //存储多个值
// $array = array('first_key'=>'first_val',
//           'second_key'=>'second_val',
//           'third_key'=>'third_val');
// $array_get = array('first_key','second_key','third_key');
// $redis->mset($array);
// var_dump($redis->mget($array_get));
// include 'redis.class.php';

// $array_mset_keys=array('one'=>'1',
//           'two'=>'2',
//           'three '=>'3',
//           'four'=>'4');
// $redis->mset($array_mset_keys); #用MSET一次储存多个值
// var_dump($redis->keys('*o*')); //array(3) { [0]=> string(4) 'four' [1]=> string(3) 'two' [2]=> string(3) 'one' }
// var_dump($redis->keys('t??')); //array(1) { [0]=> string(3) 'two' }
// var_dump($redis->keys('t[w]*')); //array(1) { [0]=> string(3) 'two' }
// print_r($redis->keys('*')); //Array ( [0] => four [1] => three [2] => two [3] => one )


// //TTL
// # 情况1：带TTL的key
// $redis->flushdb();
// $redis->set('name','ikodota'); # 设置一个key
// $redis->expire('name',30);  # 设置生存时间为30秒 //return (integer) 1
// echo $redis->get('name'); //return ikodota
// echo $redis->ttl('name'); //(integer) 25

// //echo $redis->ttl('name');  # 30秒过去，name过期 //(integer) -1
// var_dump($redis->get('name')); # 过期的key将被删除 //return bool(false);

// # 情况2：不带TTL的key
// $redis->set('site','wikipedia.org');//OK
// var_dump($redis->ttl('site'));//int(-1)

// # 情况3：不存在的key
// $redis->EXISTS('not_exists_key');//int(0)
// var_dump($redis->TTL('not_exists_key'));//int(-1)


# 情况1：key存在且newkey不存在
// $redis->SET('message','hello world');
// var_dump($redis->RENAME('message','greeting'));  //bool(true)
// var_dump($redis->EXISTS('message'));  # message不复存在 //bool(false)
// var_dump($redis->EXISTS('greeting'));   # greeting取而代之 //bool(true)

# 情况2：当key不存在时，返回错误 ,php返回false;
// var_dump($redis->RENAME('fake_key','never_exists'));  //bool(false)
// $redis->flushdb();
# 情况3：newkey已存在时，RENAME会覆盖旧newkey
// $redis->SET('pc','lenovo');
// $redis->SET('personal_computer','dell'); 
// var_dump($redis->RENAME('pc','personal_computer')); //bool(true)
// var_dump($redis->GET('pc')); //(nil)   bool(false)
// var_dump($redis->GET('personal_computer'));  # dell“没有”了 //string(6) 'lenovo'
// 


// $redis->flushALL();
// echo '<br><br>TYPE<br>';

// var_dump($redis->TYPE('fake_key')); //none /int(0)

// $redis->SET('weather','sunny');  # 构建一个字符串
// var_dump($redis->TYPE('weather'));//string / int(1)

// $redis->SADD('pat','dog');  # 构建一个集合
// var_dump($redis->TYPE('pat')); //set /int(2)

// $redis->LPUSH('book_list','programming in scala');  # 构建一个列表
// var_dump($redis->TYPE('book_list'));//list / int(3) 

// $redis->ZADD('pats',1,'cat');  # 构建一个zset (sorted set) // int(1)
// $redis->ZADD('pats',2,'dog');
// $redis->ZADD('pats',3,'pig');
// var_dump($redis->zRange('pats',0,-1)); // array(3) { [0]=> string(3) 'cat' [1]=> string(3) 'dog' [2]=> string(3) 'pig' }
// var_dump($redis->TYPE('pats')); //zset / int(4)
// $redis->HSET('website','google','www.g.cn');   # 一个新域
// var_dump($redis->HGET('website','google')); //string(8) 'www.g.cn'
// var_dump($redis->TYPE('website')); //hash /int(5)


// $redis->SET('cache','www.google.com');
// echo $redis->EXPIREAT('cache','1355292000'); # 这个key将在2012.12.12过期

// echo ($redis->TTL('cache')); //return 124345085

// $redis->PERSIST('time_to_say_goodbye')
// 


# 将数据一一加入到列表中
// $redis->LPUSH('today_cost', 30);
// $redis->LPUSH('today_cost', 1.5);
// $redis->LPUSH('today_cost', 10);
// $redis->LPUSH('today_cost', 8);
# 排序
// var_dump($redis->SORT('today_cost')); //array(4) { [0]=> string(3) '1.5' [1]=> string(1) '8' [2]=> string(2) '10' [3]=> string(2) '30' }
// var_dump($redis->SORT('website', array('ALPHA'=>TRUE)));

# 将数据一一加入到列表中
// $redis->flushdb();
// $redis->LPUSH('rank', 30); //(integer) 1
// $redis->LPUSH('rank', 56); //(integer) 2
// $redis->LPUSH('rank', 42); //(integer) 3
// $redis->LPUSH('rank', 22); //(integer) 4
// $redis->LPUSH('rank', 0);  //(integer) 5
// $redis->LPUSH('rank', 11); //(integer) 6
// $redis->LPUSH('rank', 32); //(integer) 7
// $redis->LPUSH('rank', 67); //(integer) 8
// $redis->LPUSH('rank', 50); //(integer) 9
// $redis->LPUSH('rank', 44); //(integer) 10
// $redis->LPUSH('rank', 55); //(integer) 11

# 排序
// $redis_sort_option=array('LIMIT'=>array(0,5));
// var_dump($redis->SORT('rank',$redis_sort_option));  
// $redis_sort_option=array('LIMIT'=>array(0,5),'SORT'=>'DESC');
// var_dump($redis->SORT('rank',$redis_sort_option));

// $redis->LPUSH('user_id', 1);//(integer) 1
// $redis->SET('user_name_1', 'admin');
// $redis->SET('user_level_1',9999);

// # huangz
// $redis->LPUSH('user_id', 2);//(integer) 2
// $redis->SET('user_name_2', 'huangz');
// $redis->SET('user_level_2', 10);

// # jack
// $redis->LPUSH('user_id', 59230);//(integer) 3
// $redis->SET('user_name_59230','jack');
// $redis->SET('user_level_59230', 3);

// # hacker
// $redis->LPUSH('user_id', 222);  //(integer) 4
// $redis->SET('user_name_222', 'hacker');
// $redis->SET('user_level_222', 9999);


// $redis_sort_option=array('BY'=>'user_level_*','SORT'=>'DESC');
// var_dump($redis->SORT('user_id',$redis_sort_option));


// $redis_sort_option=array('BY'=>'user_level_*','SORT'=>'DESC','GET'=>'user_name_*');
// var_dump($redis->SORT('user_id', $redis_sort_option)); 

# 先添加一些测试数据
// $redis->SET('user_password_222', 'hey,im in');
// $redis->SET('user_password_1', 'a_long_long_password');
// $redis->SET('user_password_2', 'nobodyknows');
// $redis->SET('user_password_59230', 'jack201022');

// # 获取name和password
// $redis_sort_option=array('BY'=>'user_level_*',
//             'SORT'=>'DESC',
//             'GET'=>array('user_name_*','user_password_*')
//             );
// var_dump($redis->SORT('user_id',$redis_sort_option));//array(8) { [0]=> string(6) 'hacker' [1]=> string(9) 'hey,im in' [2]=> string(5) 'admin' [3]=> string(20) 'a_long_long_password' [4]=> string(6) 'huangz' [5]=> string(11) 'nobodyknows' [6]=> string(4) 'jack' [7]=> string(10) 'jack201022' }

#------------------------------------
#1) 'hacker'       # 用户名
#2) 'hey,im in'    # 密码
#3) 'jack'
#4) 'jack201022'
#5) 'huangz'
#6) 'nobodyknows'
#7) 'admin'
#8) 'a_long_long_password'


// $redis->EXISTS('user_info_sorted_by_level');  # 确保指定key不存在   //(integer) 0
// $redis_sort_option=array('BY'=>'user_level_*',
//             'GET'=>array('#','user_name_*','user_password_*'),
//             'STORE'=>'user_info_sorted_by_level'
//             );

// var_dump($redis->SORT('user_id',$redis_sort_option)); //int(12)
// var_dump($redis->LRANGE('user_info_sorted_by_level', 0 ,11)); 


// # 情况2：对非字符串类型的key进行SET
// $redis->LPUSH('greet_list', 'hello');  # 建立一个列表 #(integer) 1 //int(1)
// $redis->TYPE('greet_list');#list //int(3)
// $redis->SET('greet_list', 'yooooooooooooooooo');   # 覆盖列表类型 #OK //bool(true)
// $redis->TYPE('greet_list');#string //int(1)


# 情况1：key不存在
// $redis->SETEX('cache_user_id', 60,10086);//bool(true)
// echo $redis->GET('cache_user_id');  # 值 //'10086'
// sleep(4);
// echo $redis->TTL('cache_user_id');  # 剩余生存时间 //int(56)


# 情况2：key已经存在，key被覆写
// $redis->SET('cd', 'timeless'); //bool(true);
// $redis->SETEX('cd', 5,'goodbye my love'); //bool(true);
// echo $redis->GET('cd');//'goodbye my love'



// # 情况1：对非空字符串进行SETRANGE
// $redis->SET('aa', 'hello world');
// $redis->SETRANGE('aa', 3, 'Redis'); //int(11)
// $redis->GET('aa');//'aa Redis'

// # 情况2：对空字符串/不存在的key进b行SETRANGE
// $redis->EXISTS('bb');//bool(false)
// $redis->SETRANGE('bb', 5 ,'Redis!');  # 对不存在的key使用SETRANGE //int(11)
// var_dump($redis->GET('bb'));  # 空白处被'\x00'填充  #'\x00\x00\x00\x00\x00Redis!'   //return string(11) 'Redis!'


// 情况1：对不存在的key执行APPEND
$redis->EXISTS('myphone');  # 确保myphone不存在 //bool(false)
$redis->APPEND('myphone','nokia');# 对不存在的key进行APPEND，等同于SET myphone 'nokia' //int(5) # 字符长度
# 情况2：对字符串进行APPEND
$redis->APPEND('myphone', '-1110');# 长度从5个字符增加到12个字符 //int(12)
echo $redis->GET('myphone');  # 查看整个字符串 //'nokia - 1110'




//INCRBY
// echo '<br><br>INCRBY<br>';
// # 情况1：key存在且是数字值
// $redis->SET('rank', 50);  # 设置rank为50
// $redis->INCRBY('rank', 20);  # 给rank加上20
// var_dump($redis->GET('rank')); #'70'   //string(2) '70'

// # 情况2：key不存在
// $redis->EXISTS('counter'); //bool(false)
// $redis->INCRBY('counter'); #int 30  //bool(false)
// var_dump($redis->GET('counter')); #30 //经测试 与手册上结果不一样，不能直接从bool型转为int型。 return bool(false) 

// # 情况3：key不是数字值
// $redis->SET('book', 'long long ago...');
// var_dump($redis->INCRBY('book', 200)); #(error) ERR value is not an integer or out of range 

// $info=$redis->get('baidu');
// echo $info;






