<?php
set_time_limit(0);
require_once "PhpImap/Mailbox.php";
require_once "PhpImap/IncomingMail.php";
include_once ('../rabbit/config.php');
require_once ('../rabbit/RabbitMQCommand.php');
require_once ('../php-pdo/Db.class.php');
require_once("../php-pdo/easyCRUD/easyCRUD.class.php");

use PhpImap\Mailbox as ImapMailbox;
use PhpImap\IncomingMail;
use PhpImap\IncomingMailAttachment;

$exchange_name = 'ex_inbox_email';
$queue_name = 'q_inbox_email';
$route_key = 'inbox_email';
$rabbit = new RabbitMQCommand($configs,$exchange_name,$queue_name,$route_key);
$mailbox = new PhpImap\Mailbox('{imap.qq.com:993/imap/ssl}INBOX', 'test@qq.com', '', __DIR__);
date_default_timezone_set('PRC');

class EasyDB Extends Crud {
  protected $table = 'js_email';
  protected $pk = 'id';
}

class A {
	public $mailbox;
    function processMessage($envelope, $queue)
    {
		//配置信息
    	global $mailbox;
    	$this->mailbox = $mailbox;
        
        //获取队列消息
        $msg = $envelope->getBody();
        $info = json_decode($msg, true);
        $envelopeID = $envelope->getDeliveryTag();
        $pid = posix_getpid();
		$result = $this->saveMail($this->mailbox, $info);
        // file_put_contents("log{$pid}.log", $result.'|'.$envelopeID.''."\r\n",FILE_APPEND);
        $queue->ack($envelopeID);
        // die;
    }

    function saveMail($mailbox, $info)
    {
    	try {
	    	if (!empty($info['mail_id'])) {
				$log = '';
				$db = new Db();
	    		$mailInfo = $mailbox->getMail($info['mail_id']);
		    	$data = array(
		    		'mail_id' => $info['mail_id'],
		    		'mail_date' => date('Y-m-d H:i:s', strtotime($mailInfo->date)),
		    		'mail_from' => $mailInfo->fromName,
		    		'fromMail' => $mailInfo->fromAddress,
		    		'reply_to' => empty($mailInfo->headers->reply_to[0]->personal) ? '' : $mailInfo->headers->reply_to[0]->personal,
		    		'mail_to' => $mailInfo->toString,
		    		'cc' => $this->arrToStr($mailInfo->cc),
		    		'bcc' => $this->arrToStr($mailInfo->bcc),
		    		'subject' => $mailInfo->subject,
		    		'textPlain' => $mailInfo->textPlain,
		    		'user_id' => $info['user_id'],
				);

				// $mailbox->deleteMail(3204);
				// die;

				echo date('Y-m-d H:i:s', strtotime($mailInfo->date));
				echo $mailInfo->subject;
				// echo $info['mail_id'];
				// die;
				// print_r($data);
				// die;
				$count = $db->row("SELECT count(id) as cid FROM js_email WHERE is_del = 0 AND user_id = 1 AND mail_id = " . $info['mail_id']);
				//判断邮件是否已经存在
				if ($count['cid'] == 0) {
					$rows = $db->row("SELECT hexo_key,hexo_dir,has_mail FROM js_email_setting WHERE is_del = 0 AND user_id = 1");
					//博客保存地址 关键字
			    	if (!empty($rows['hexo_key']) && !empty($rows['hexo_dir'])) {
			    		//判断标题是否满足创建博文条件
			    		if (strstr($mailInfo->subject, $rows['hexo_key'])) {
			    			//添加博文
				    		$path = $rows['hexo_dir'];
							//判断目录存在否，存在给出提示，不存在则创建目录
							if (!is_dir($path)){
								//第三个参数是“true”表示能创建多级目录，iconv防止中文目录乱码
								$res = mkdir($path,0777,true);
								if (!$res){
									echo "目录 $path 创建失败";
								}
							}
							$title = substr_replace($mailInfo->subject, $rows['hexo_key']);
							$path .= '/'.$title.'.md';
							$myfile = fopen($path, "w");
							fwrite($myfile, $mailInfo->textPlain);
							fclose($myfile);
							// file_put_contents($path, $mailInfo->textPlain);
							$log .= '创建文件：'.$title;
			    		}
			    	}
					//更新读取邮件标记
					if ($rows['has_mail'] == 0) {
						$db->query("UPDATE js_email_setting SET has_mail = 1 WHERE id = :id", array("id" => 1));
					}
		    		// $created = $db->query("INSERT INTO js_email(mail_id,mail_date,mail_from,fromMail,reply_to,mail_to,cc,bcc,subject,textPlain,user_id) VALUES(:mail_id,:mail_date,:mail_from,:fromMail,:reply_to,:mail_to,:cc,:bcc,:subject,:textPlain,:user_id)", $data);
					$EDB = new EasyDB($data);
					$created = $EDB->Create();
					// var_dump($created);
					// echo '21312';
					// die;
				}
	    	}
    	} catch (Exception $e) {
    		print_r($e);
    		file_put_contents("error{$pid}.log", '邮件ID：'.$$info['mail_id'].'|'.$envelopeID.''."\r\n",FILE_APPEND);
    	}
    }

    function arrToStr($arr)
    {
    	$tmp_arr = array();
    	if ($arr) {
			foreach ($arr as $key => $val) {
				array_push($tmp_arr, $key);
			}

    	}
    	return implode(';', $tmp_arr);
    }    
}

$a = new A();
$s = $rabbit->run(array($a,'processMessage'),false);

