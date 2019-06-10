<?php
$stime = date('Y-m-d H:i:s');
$host = '{imap.qq.com:993/imap/ssl}INBOX';
$user = 'test@qq.com';
$pass = '';
// $inbox = imap_open($host, $user, $pass);
// $mailboxes = imap_list($inbox, $host, '*');
// $mailboxes = str_replace($host, '', $mailboxes);
// print_r($mailboxes);


// imap_reopen($inbox, $host.'INBOX');
// $emails = imap_search($inbox,'ALL');
// print_r($emails);


$mbox = imap_open($host, $user, $pass);
// echo count($mbox);
$mailId = 5800;
$headersRaw = imap_fetchheader($mbox, $mailId, FT_UID);
$head = imap_rfc822_parse_headers($headersRaw);


$head->headersRaw = $headersRaw;
$head->headers = $head;
// $head->subject = isset($head->subject) ? $this->decodeMimeStr($head->subject, $this->serverEncoding) : null;
// $head->fromName = isset($head->from[0]->personal) ? $this->decodeMimeStr($head->from[0]->personal, $this->serverEncoding) : null;
// $head->fromAddress = strtolower($head->from[0]->mailbox . '@' . $head->from[0]->host);
var_dump($head);
die;


//邮箱列表 inbox send drafts deleted
// $list = imap_list($mbox, "{imap.qq.com:993/imap/ssl}", "*");
// $list = imap_getmailboxes($mbox, "{imap.qq.com:993/imap/ssl}", "*");

// echo '邮件总数:'.imap_num_msg($mbox);

// $header = imap_fetchheader($mbox, 3126, FT_UID);
// $headers = imap_rfc822_parse_headers($header); 

// $emails = imap_search($mbox,'ALL');
// print_r($emails);
// 
// 获取给定UID的消息序列号
// $no = imap_msgno($mbox, 3120);
// print_r($no);

// $headers = imap_header($mbox, imap_msgno($mbox, 3120));
// 
// 输出内容
// echo imap_qprint(imap_body($mbox, 3120, FT_UID)); 

// FT_UID - The msg_number is a UID
// FT_PEEK - Do not set the Seen flag if not already set
// FT_INTERNAL - The return string is in internal format, will not canonicalize to CRLF.

// echo imap_base64(imap_body($mbox, 3120, FT_UID)); 
// 
// $ac = imap_fetchbody($mbox, 3120, FT_UID); 
// print_r(imap_base64($ac));
// $ac = iconv('gb2312','utf8',$ac);

// $bc = imap_mime_header_decode($ac);

// print_r(imap_base64($ac)); 

//错误信息
// var_dump(imap_alerts());

// for ($i=3100; $i < 3120 ; $i++) {
//     $headersRaw = imap_fetchheader($mbox, $emails[$i], FT_UID);
//     $head = imap_rfc822_parse_headers($headersRaw);
//     echo "<h3>".$head->subject."</h3>";
// }

// $headersRaw = imap_fetchheader($mbox, 5, FT_UID);
// $head = imap_rfc822_parse_headers($headersRaw);
// // print_r($head->subject);
// print_r($head);


// $some = imap_search($mbox, 'FROM "123123" SINCE "26 March 2017"', SE_UID);
// $msgnos = imap_search($mbox, 'ALL');
// $uids   = imap_search($mbox, 'ALL', SE_UID);

// echo count($some);
// print_r($msgnos);
// print_r($uids);


// imap_close($mbox);
// $etime = date('Y-m-d H:i:s');


// echo "<br><br>";
// echo "start time:".$stime;
// echo "<br>";
// echo "end time:".$etime;



