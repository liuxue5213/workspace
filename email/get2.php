<?php
set_time_limit(0);
require_once "PhpImap/Mailbox.php";
require_once "PhpImap/IncomingMail.php";
include_once ('../rabbit/config.php');
require_once ('../rabbit/RabbitMQCommand.php');
require_once ('../php-pdo/Db.class.php');

use PhpImap\Mailbox as ImapMailbox;
use PhpImap\IncomingMail;
use PhpImap\IncomingMailAttachment;

$mailbox = new PhpImap\Mailbox('{imap.qq.com:993/imap/ssl}INBOX', 'test@qq.com', '', __DIR__);
//$mailsIds = $mailbox->searchMailbox('FROM "zz_msg@baidu.com" SINCE "19 May 2017"');
// $mailsIds = $mailbox->searchMailbox('BODY "1" SINCE "17 May 2017"');
// Get the first message and save its attachment(s) to disk:
// echo "邮件总数：".empty($mailsIds) ? 0 :count($mailsIds);
// echo count($mailsIds);

// $dbms='mysql';     //数据库类型
// $host='localhost'; //数据库主机名
// $dbName='symfony';    //使用的数据库
// $user='root';      //数据库连接用户名
// $pass='123456';          //对应的密码
// $dsn="$dbms:host=$host;dbname=$dbName";
// try {
//     $conn = new PDO($dsn, $user, $pass); //初始化一个PDO对象
//     $sth = $conn->prepare("SELECT * FROM js_system_menus");
// 	$sth->execute();
// 	$result = $sth->fetchAll();
// 	print_r($result);

//     /*你还可以进行一次搜索操作
//     foreach ($dbh->query('SELECT * from FOO') as $row) {
//         print_r($row); //你可以用 echo($GLOBAL); 来看到这些值
//     }
//     */
//     // $conn = null;
// } catch (PDOException $e) {
//     die ("Error!: " . $e->getMessage() . "<br/>");
// }
//默认这个不是长连接，如果需要数据库长连接，需要最后加一个参数：array(PDO::ATTR_PERSISTENT => true) 变成这样：
// $db = new PDO($dsn, $user, $pass, array(PDO::ATTR_PERSISTENT => true));

//获取邮件设置
$db = new Db();
$mailConfig = $db->row("SELECT * FROM js_email_setting WHERE is_del = 0 AND user_id = 1");
if (!empty($mailConfig['id'])) {
	// $count = $db->row("SELECT count(id) as cid FROM js_email WHERE is_del = 0 AND user_id = 1");
	//收件箱没有记录
	if ($mailConfig['has_mail'] == 0) {
		$mailsIds = $mailbox->searchMailbox('ALL');
	} else {
		//获取当天记录
		$nowDate = date('d M Y');
		$mailsIds = $mailbox->searchMailbox('SINCE "'.$nowDate.'"');
	}
	if (!$mailsIds) {
    	return '没有收件记录';
	}

	$exchange_name = 'ex_inbox_email';
	$queue_name = 'q_inbox_email';
	$route_key = 'inbox_email';
	$rabbit = new RabbitMQCommand($configs,$exchange_name,$queue_name,$route_key);
	foreach ($mailsIds as $key => $val) {
		$info = array(
			'user_id' => 1,
			'mail_id' => $val,
			'time' => date('Y-m-d H:i:s',time())
		);
		$rabbit->send($info);
	}
}

//$etime = date('Y-m-d H:i:s');
//echo "开始时间:".$stime;
//echo "<br>";
//echo "结束时间:".$etime;
// var_dump($mail);
// echo "\n\n\n\n\n";
// var_dump($mail->getAttachments());
