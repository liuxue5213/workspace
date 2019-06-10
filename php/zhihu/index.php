<?php

require 'common.php';

// define('baseUrl', 'https://www.zhihu.com');
$tmp = array();
$zhihu = new Zhihu();
$baseUrl = 'https://www.zhihu.com';

$type = isset($_GET['type']) ? $_GET['type']: '';

switch ($type) {
case 'detail':
    if (isset($_GET['href'])) {
        $is_show = 1;
        $title = isset($_GET['title']) ? $_GET['title'] : '';
        $param = array(
            'include' => 'data[*].is_normal,admin_closed_comment,reward_info,is_collapsed,annotation_action,annotation_detail,collapse_reason,is_sticky,collapsed_by,suggest_edit,comment_count,can_comment,content,editable_content,voteup_count,reshipment_settings,comment_permission,created_time,updated_time,review_info,relevant_info,question,excerpt,relationship.is_authorized,is_author,voting,is_thanked,is_nothelp,upvoted_followees;data[*].mark_infos[*].url;data[*].author.follower_count,badge[?(type=best_answerer)].topics',
            'offset' => 0,
            'limit' => 20,
            'sort_by' => 'default',
        );
        $href = $_GET['href'];
        $params = http_build_query($param);
        $to = explode('/', $href);
        $url = $baseUrl.'/api/v4/questions/'.$to[2].'/answers?'.$params;
        $next = $previous = $is_end = '';
        $i = 0;
        do {
            $i++;
            $rows = $zhihu->curlGet($url);
            if ($rows) {
                if (isset($rows['paging'])) {
                    $p = $rows['paging'];
                    $total = $p['totals'];
                    $is_start = $p['is_start'];
                    $is_end = $p['is_end'];
                    $data = $rows['data'];
                    $previous = $p['previous'];
                    if ($is_start && !$is_end && $p['next']) {
                        $next = $p['next'];
                        // $ttt = $zhihu->getDetail($next, $data);
                    }
                    if (isset($data)) {
                        foreach ($data as $key => $val) {
                            $data = array(
                                'id' => !empty($val['author']['id']) ? $val['author']['id'] : '', //用户ID
                                'description' => isset($val['author']['badge']) ? implode('、', array_column($val['author']['badge'], 'description')) : '',
                                'name' => $val['author']['name'],
                                'user_type' => $val['author']['user_type'],
                                'author_url' => $val['author']['url'],
                                'author_homepage' => str_replace('http', 'https', str_replace('api/v4/', '', $val['author']['url'])),
                                'url_token' => $val['author']['url_token'],
                                'follower_count' => $val['author']['follower_count'], //关注数
                                'headline' => $val['author']['headline'], //简介
                                'avatar_url' => $val['author']['avatar_url'], //头像地址
                                'admin_closed_comment' => $val['admin_closed_comment'] ? '是' :'否',
                                'excerpt' => $val['excerpt'], //内容
                                'created_time' => date('Y-m-d H:i:s', $val['created_time']),
                                'updated_time' => date('Y-m-d H:i:s', $val['updated_time']),
                                'content' => $val['content'], //评论全部内容
                                'type' => $val['type'],
                                'thumbnail' => $val['thumbnail'],
                                'comment_permission' => $val['comment_permission'], //评论权限
                                'url' => $val['url'],
                            );
                            array_push($tmp, $data);
                        }
                    }
                    $is_end = true;
                }
            }
        } while ($is_end || $next == $previous || $i < 5);
    }
    break;
case 'homepage':
    if (isset($_GET['homepage'])) {
        $is_show = 2;
        $homepage = $_GET['homepage'];
        $url_token = $_GET['url_token'];

//https://www.zhihu.com/api/v4/members/michaelmao/publications?include=data%5B*%5D.cover%2Cebook_type%2Ccomment_count%2Cvoteup_count&offset=0&limit=5

//https://www.zhihu.com/api/v4/members/tian-xia-wu-zei-27/followees?include=data%5B*%5D.answer_count%2Carticles_count%2Cgender%2Cfollower_count%2Cis_followed%2Cis_following%2Cbadge%5B%3F(type%3Dbest_answerer)%5D.topics&offset=0&limit=20 关注的人
//https://www.zhihu.com/api/v4/members/tian-xia-wu-zei-27/following-topic-contributions?include=data%5B*%5D.topic.introduction&offset=0&limit=20 他关注的话题
//https://www.zhihu.com/api/v4/members/tian-xia-wu-zei-27/following-columns?include=data%5B*%5D.intro%2Cfollowers%2Carticles_count&offset=0&limit=20  他关注的专栏

//https://www.zhihu.com/api/v4/members/tian-xia-wu-zei-27/questions?include=data%5B*%5D.created%2Canswer_count%2Cfollower_count%2Cauthor%2Cadmin_closed_comment&offset=0&limit=20   提问
//https://www.zhihu.com/api/v4/members/tian-xia-wu-zei-27/articles?include=data%5B*%5D.comment_count%2Csuggest_edit%2Cis_normal%2Ccan_comment%2Ccomment_permission%2Cadmin_closed_comment%2Ccontent%2Cvoteup_count%2Ccreated%2Cupdated%2Cupvoted_followees%2Cvoting%2Creview_info%3Bdata%5B*%5D.author.badge%5B%3F(type%3Dbest_answerer)%5D.topics&offset=0&limit=20&sort_by=created   文章
//https://www.zhihu.com/api/v4/members/tian-xia-wu-zei-27/column-contributions?include=data%5B*%5D.column.intro%2Cfollowers%2Carticles_count&offset=0&limit=20  专栏
//https://www.zhihu.com/api/v4/members/tian-xia-wu-zei-27/pins?offset=0&limit=20&includes=data%5B*%5D.upvoted_followees%2Cadmin_closed_comment  想法
//https://www.zhihu.com/api/v4/members/tian-xia-wu-zei-27/favlists?include=data%5B*%5D.updated_time%2Canswer_count%2Cfollower_count%2Cis_public&offset=0&limit=20  收藏
//https://www.zhihu.com/api/v4/members/tian-xia-wu-zei-27/followees?include=data%5B*%5D.answer_count%2Carticles_count%2Cgender%2Cfollower_count%2Cis_followed%2Cis_following%2Cbadge%5B%3F(type%3Dbest_answerer)%5D.topics&offset=0&limit=20   关注

        $url_token = 'liuxue5213';
        $is_end = '';
        $arr = array();
        $i = 0;
        $persionUrl = 'https://www.zhihu.com/api/v4/members/'.$url_token.'/activities';
        $persion = $zhihu->curlGet($persionUrl);
        while ($i < 2) {
//        while (!$is_end) {
            $i++;
            if (isset($persion['paging'])) {
                $p = $persion['paging'];
                $is_end = $p['is_end'];
                if (!$is_end && $p['next']) {
                    $persionUrl = str_replace('http', 'https', $p['next']);
                }
                if (isset($persion['data'])) {
                    foreach ($persion['data'] as $key => $val) {
                        $data = array(
                            'id' => $val['target']['id'],
                            'type' => $val['type'],
                            'action_text' => $val['action_text'],
                            'c_time' => date('Y-m-d H:i:s', $val['created_time']),
                            'created_time' => isset($val['target']['created_time']) ? date('Y-m-d H:i:s', $val['target']['created_time']) : '',
                            'updated_time' => isset($val['target']['updated_time']) ? date('Y-m-d H:i:s', $val['target']['updated_time']) : '',
                            'url' => $val['target']['url'],
                            'comment_count' => isset($val['target']['comment_count']) ? $val['target']['comment_count'] : '',
                            'excerpt_new' => isset($val['target']['excerpt_new']) ? $val['target']['excerpt_new'] : '',
                            'reshipment_settings' => isset($val['target']['reshipment_settings']) ? $val['target']['reshipment_settings'] : '',
                            'thanks_count' => isset($val['target']['thanks_count']) ? $val['target']['thanks_count'] : '',
                            'content' => isset($val['target']['content']) ? $val['target']['content'] : '',
                            'voteup_count' => isset($val['target']['voteup_count']) ? $val['target']['voteup_count'] : '',
                        );

                        //问题信息
                        if (isset($val['target']['question'])) {
                            $data['q_id'] = $val['target']['question']['id'];
                            $data['q_title'] = $val['target']['question']['title']; //问题标题
                            $data['q_excerpt'] = $val['target']['question']['excerpt']; //问题内容
                            $data['q_bound_topic_ids'] = $val['target']['question']['bound_topic_ids'];
                            $data['q_follower_count'] = $val['target']['question']['follower_count'];
                            $data['q_comment_count'] = $val['target']['question']['comment_count'];
                            $data['q_answer_count'] = $val['target']['question']['answer_count'];
                            $data['q_created'] = date('Y-m-d H:i:s', $val['target']['question']['created']);
                            $data['q_url'] = $val['target']['question']['url'];
                        }

                        //作者信息
                        if (isset($val['target']['question']['author'])) {
                            $data['q_author_id'] = $val['target']['question']['author']['id'];
                            $data['q_author_name'] = $val['target']['question']['author']['name'];
                            $data['q_author_url_token'] = $val['target']['question']['author']['url_token'];
                            $data['q_author_headline'] = $val['target']['question']['author']['headline'];
                            $data['q_author_url'] = $val['target']['question']['author']['url'];
                            $data['q_author_avatar_url'] = $val['target']['question']['author']['avatar_url'];
                        }
                        array_push($arr, $data);
                    }
                }
//                $persion = $zhihu->curlGet($persionUrl);
            }
        }
        var_dump($arr);
        die;

        $infoUrl = 'https://www.zhihu.com/api/v4/members/'.$url_token.'?include=locations%2Cemployments%2Cgender%2Ceducations%2Cbusiness%2Cvoteup_count%2Cthanked_Count%2Cfollower_count%2Cfollowing_count%2Ccover_url%2Cfollowing_topic_count%2Cfollowing_question_count%2Cfollowing_favlists_count%2Cfollowing_columns_count%2Cavatar_hue%2Canswer_count%2Carticles_count%2Cpins_count%2Cquestion_count%2Ccolumns_count%2Ccommercial_question_count%2Cfavorite_count%2Cfavorited_count%2Clogs_count%2Cincluded_answers_count%2Cincluded_articles_count%2Cincluded_text%2Cmessage_thread_token%2Caccount_status%2Cis_active%2Cis_bind_phone%2Cis_force_renamed%2Cis_bind_sina%2Cis_privacy_protected%2Csina_weibo_url%2Csina_weibo_name%2Cshow_sina_weibo%2Cis_blocking%2Cis_blocked%2Cis_following%2Cis_followed%2Cis_org_createpin_white_user%2Cmutual_followees_count%2Cvote_to_count%2Cvote_from_count%2Cthank_to_count%2Cthank_from_count%2Cthanked_count%2Cdescription%2Chosted_live_count%2Cparticipated_live_count%2Callow_message%2Cindustry_category%2Corg_name%2Corg_homepage%2Cbadge%5B%3F(type%3Dbest_answerer)%5D.topics';
        $info = $zhihu->curlGet($infoUrl);
        if ($info) {
            $tmp['user']['following_count'] = @$info['following_count']; //关注了
            $tmp['user']['follower_count'] = @$info['follower_count']; //关注者

            $tmp['user']['pins_count'] = @$info['pins_count']; //想法
            $tmp['user']['vote_from_count'] = @$info['vote_from_count']; //
            $tmp['user']['favorite_count'] = @$info['favorite_count']; //收藏
            $tmp['user']['answer_count'] = @$info['answer_count']; //回答
            $tmp['user']['articles_count'] = @$info['articles_count']; //文章
            $tmp['user']['question_count'] = @$info['question_count']; //提问
            $tmp['user']['columns_count'] = @$info['columns_count']; //专栏
            $tmp['user']['show_sina_weibo'] = @$info['show_sina_weibo']; //是否显示新浪微博

            //个人成就
            $tmp['user']['included_answers_count'] = @$info['included_answers_count']; //知乎收录1个回答
            $tmp['user']['included_text'] = @$info['included_text']; //知乎圆桌和编辑推荐收录
            $tmp['user']['voteup_count'] = @$info['voteup_count']; //获得赞同
            $tmp['user']['thanked_count'] = @$info['thanked_count']; //获得感谢
            $tmp['user']['favorited_count'] = @$info['favorited_count']; //获得收藏
            $tmp['user']['logs_count'] = @$info['logs_count']; //参与几次公共编辑

            $tmp['user']['participated_live_count'] = @$info['participated_live_count']; //赞助的LIVE
            $tmp['user']['following_question_count'] = @$info['following_question_count']; //关注的问题
            $tmp['user']['following_topic_count'] = @$info['following_topic_count']; //关注的话题
            $tmp['user']['following_columns_count'] = @$info['following_columns_count'];//关注的专栏
            $tmp['user']['following_favlists_count'] = @$info['following_favlists_count'];//关注的收藏夹

            $tmp['user']['name'] = @$info['name']; //名称
            $tmp['user']['avatar_url'] = @$info['avatar_url']; //头像地址
            $tmp['user']['avatar_url_template'] = str_replace('is', 'xl', $info['avatar_url']); //头像地址
            $timg = explode('/', $tmp['user']['avatar_url_template']);
            $fname = array_pop($timg);

            $dir = 'images/';
            if (!file_exists($dir.$fname)) {
                $image = file_get_contents($tmp['user']['avatar_url_template']);
                file_put_contents($dir.$fname, $image);
            }
            $tmp['user']['avatar_name'] = $fname;
            $tmp['user']['headline'] = @$info['headline']; //主要介绍
            $tmp['user']['description'] = @$info['description']; //个人简介

            //居住地
            if (isset($info['locations'])) {
                foreach ($info['locations'] as $k => $v) {
                    if ($v['type'] == 'topic') {
                        $tmp['user']['locations_id'] = $v['id'];
                        $tmp['user']['locations'] = '现居'.$v['name'];
                    }
                }
            }

            //所在行业
            if (isset($info['business'])) {
                if ($info['business']['type'] == 'topic') {
                    $tmp['user']['business_id'] = $info['business']['id'];
                    $tmp['user']['business'] = $info['business']['name'];
                }
            }

            //教育经历
            if (isset($info['educations'])) {
                foreach ($info['educations'] as $edu) {
                    foreach ($edu as $school => $val) {
                        if ($val['type'] == 'topic') {
                            $tmp['user']['educations_id'][$school] = $val['id'];
                            $tmp['user']['educations'][$school] = $val['name'];
                        }
                    }
                }
            }

            //工作经历
            if (isset($info['employments'])) {
                foreach ($info['employments'] as $em) {
                    foreach ($em as $e) {
                        $tmp['user']['employments'][] = $e['name'];
                    }
                }
            }
        }
    }
    break;
default:
    $is_show = 0;
    $i = 0;
    $url = '/r/roundtables/bestof2017/activities?offset=1';
    do {
        $i++;
        $rows = $zhihu->getTitle($url, $nextUrl);
        $url = $nextUrl;
        foreach ($rows as $key => $value) {
            $data = array(
                'href' => $value['href'],
                'title' => $value['title'],
            );
            array_push($tmp, $data);
        }
    } while ($i < 5);
//    } while (!empty($url));
    foreach ($tmp as $k => $v) {
        echo '<a href=?type=detail&href='.$v['href'].' target="_blank">详情</a>-----';
        echo '<a href='.$baseUrl.$v['href'].' target="_blank">'.$v['title'].'</a>';
        echo '<br>';
    }
    break;
}
?>


<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title></title>
        <link rel="stylesheet" href="">
        <style>
        </style>
    </head>
    <body>
        <?php if ($is_show == 1) { ?>
            标题：<?php echo $title;?>
            全部评论：<?php echo @$total;?>条
            <a href="">返回上一页</a>
            <?php foreach ($tmp as $key => $val) {
                echo '<p><img src="'.$val['avatar_url'].'" height="160" width="160"> 作者：<b>'.$val['name'].' </b> '.'  关注者：'.$val['follower_count'].'</p>';
                echo '个人介绍：'.$val['headline'];
                echo '  <span><a href="'.$val['author_homepage'].'" target="_blank">链接到知乎的个人主页</a></span>';
                echo '  <span><a href="?type=homepage&url_token='.$val['url_token'].'&homepage='.$val['author_homepage'].'" target="_blank">测试详情</span>';
                // echo '<p id="">评论内容：'.$val['excerpt'].'</p>';
                echo '<p id="">'.$val['content'].'</p>';
                // echo '<a href="#" onclick="show_detail(this)">展开阅读全文</a>';
                echo '<p>评论创建时间：'.$val['created_time'].'  修改时间：'.$val['updated_time'].'</p>';
                echo '<br><br>';
            } ?>
        <?php } elseif ($is_show == 2) { ?>
            <div style="float: left; height:300px;">
                <p><img src="images/<?php echo $tmp['user']['avatar_name'];?>" alt="" width="160" height="160" /></p>
                <p><a href="">关注了 <b><?php echo $tmp['user']['following_count'];?></b></a></p>
                <p><a href="">关注者 <b><?php echo $tmp['user']['follower_count'];?></b></a></p><br>
                
                <p><a href="">赞助的LIVE <b><?php echo $tmp['user']['participated_live_count'];?></b></a></p>
                <p><a href="">关注的话题 <b><?php echo $tmp['user']['following_topic_count'];?></b></a></p>
                <p><a href="">关注的专栏 <b><?php echo $tmp['user']['following_columns_count'];?></b></a></p>
                <p><a href="">关注的问题 <b><?php echo $tmp['user']['following_question_count'];?></b></a></p>
                <p><a href="">关注的收藏夹 <b><?php echo $tmp['user']['following_favlists_count'];?></b></a></p>
            </div>
            <div>
                <p><span style="font-size: 26px;"><b><?php echo $tmp['user']['name'];?></b></span><?php echo $tmp['user']['headline'];?></p>
                <p><b>居住地</b></p>
                <p><b>所在行业</b><?php echo $tmp['user']['business'];?></p>
                <p><b>职业经历</b><?php echo implode(',', $tmp['user']['employments']);?></p>
                <p><b>教育经历</b><?php foreach($tmp['user']['educations'] as $val){echo $val;}?></p>
                <p><b>个人简介</b><?php echo $tmp['user']['description'];?></p>
            </div>
            <div>
                <b>个人成就</b>
                <?php
                if ($tmp['user']['included_answers_count']) {
                    echo '知乎收录'.$tmp['user']['included_answers_count'].'个回答  ';
                    echo $tmp['user']['included_text'];
                }
                ?>

                <span>获得 <b><?php echo $tmp['user']['voteup_count'];?></b> 次赞同</span>
                <span>获得 <b><?php echo $tmp['user']['thanked_count'];?></b> 次感谢，<b><?php echo $tmp['user']['favorited_count'];?></b> 次收藏</span>
                <span>参与 <b><?php echo $tmp['user']['logs_count'];?></b> 次公共编辑</span>
            </div>
            <div>
                <b>个人动态</b>
                <span><a href="">回答 <b><?php echo $tmp['user']['answer_count'];?></b></a></span>
                <span><a href="">提问 <b><?php echo $tmp['user']['question_count'];?></b></a></span>
                <span><a href="">文章 <b><?php echo $tmp['user']['articles_count'];?></b></a></span>
                <span><a href="">专栏 <b><?php echo $tmp['user']['columns_count'];?></b></a></span>
                <span><a href="">想法 <b><?php echo $tmp['user']['pins_count'];?></b></a></span>
                <span><a href="">收藏 <b><?php echo $tmp['user']['favorite_count'];?></b></a></span>
                <span><a href="">关注 </a></span>
            </div>
            <div>



            </div>
        <?php } ?>

    </body>
</html>
