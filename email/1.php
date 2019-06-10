<?php
require "PhpImap/Mailbox.php";
require "PhpImap/IncomingMail.php";

use PhpImap\Mailbox as ImapMailbox;
use PhpImap\IncomingMail;
use PhpImap\IncomingMailAttachment;

$stime = date('Y-m-d H:i:s');

// 4. argument is the directory into which attachments are to be saved:
$mailbox = new PhpImap\Mailbox('{imap.qq.com:993/imap/ssl}INBOX', 'test@qq.com', '', __DIR__);

// Read all messaged into an array:cls

//$mailsIds = $mailbox->searchMailbox('FROM "zz_msg@baidu.com" SINCE "19 May 2017"');
$mailsIds = $mailbox->searchMailbox('BODY "1" SINCE "17 May 2017"');
// print_r($mailsIds);

if (!$mailsIds) {
    die('Mailbox is empty');
}

//print_r(($mailsIds));
//die;

// Get the first message and save its attachment(s) to disk:
$mail = $mailbox->getMail($mailsIds[5630]);

// echo "邮件总数：".empty($mailsIds) ? 0 :count($mailsIds);

// echo count($mailsIds);

// echo "<pre>";
//print_r($mail);
// echo "</pre>";

// foreach ($mailsIds as $key => $value) {
//     $mail = $mailbox->getMail($mailsIds[$key]);
    // echo "<h3>".$mail->subject."</h3>";
// }


//$etime = date('Y-m-d H:i:s');
//
//echo "开始时间:".$stime;
//echo "<br>";
//echo "结束时间:".$etime;
// var_dump($mail);
// echo "\n\n\n\n\n";
// var_dump($mail->getAttachments());
