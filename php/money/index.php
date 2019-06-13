<?php
    require './common.php';
    $check = false;
    $key = isset($_GET['key']) ? $_GET['key']: '';
    $name = isset($_GET['name']) ? $_GET['name']: '';
    $Common = new Money();
    // $check = $Common->checkUserInfo($name, $key);
    // if (!$check) {
    //     header("Location: http://johnscott1989.club"); 
    //     exit;
    // }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>
    <script src="http://libs.baidu.com/jquery/2.0.0/jquery.min.js"></script>
    <link rel="stylesheet" href="">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <style>
        .coin{width:120px;}
        .red{color:red;}
    </style>
</head>
<body>
    <div>
        <fieldset>
            <legend>用户设置</legend>
            <label>来源：</label>
            <select id="source" class="coin">
                <option value="huobi">火币Pro</option>
            </select>

            <label>基础币种：</label>
            <select id="bcoin" class="coin"></select>

            <label>计价币种</label>
            <select id="scoin" class="coin"></select>

            <!-- <button type="button" class="btn" onclick="fnCoin('add')" disabled="true">添加</button> -->
            <button type="button" class="btn" onclick="editXml()">更新币种</button>
            <!-- <button type="button" class="btn" onclick="reFresh()">查询</button> -->

            <label>定时刷新：</label>
            <input id="intime" type="number" value="10" style="width: 50px;">秒&nbsp;&nbsp;
            <button id="inbutton" type="button" class="btn" onclick="setInter()">开始</button>
            <span id="error" style="color: red"></span><br>
        </fieldset>
        <div id="coinInfo"></div>
    </div>

    <script>
        $(document).ready(function() {
            getCoinList();
            getUserCoin();
            $('#source').select2();
        });

        var intval = 0;
        function setInter() {
            intval += 1;
            var intime = $('#intime').val();
            if (intime >= 5) {
                $('#error').html('');
                intime = parseInt(intime) * 1000;
                if (intval % 2 == 0) {
                    $('#intime').next().html('开始');
                    clearInterval(msginterval);
                } else {
                    $('#intime').next().html('停止');
                    msginterval = window.setInterval("getUserCoin()", intime);
                }
            } else {
                intval += 1;
                $('#error').html('定时刷新间隔必须大于5');
            }
        }

        function getUserCoin() {
            $.ajax({
                type : "POST",
                url : "/workspace/php/money/post.php",
                async : 'false',
                data : {type:'data',action:'info'},
                success : function(result) {
                    $('#coinInfo').html('');
                    result = JSON.parse(result);
                    if (result['code'] == 200) {
                        var str = '';
                        $.each(result['data'], function(index, val) {
                            str += '<h4>来源：'+val['from']+' 名称：'+val['coinName']+index+' 价格(美元)：<span class="">'+val['dollar']+'</span> 价格(人民币)：'+val['last']+' 最高价：'+val['highdollar']+' 最低价：'+val['lowdollar']+'</h4>';
                        });
                        $('#coinInfo').html(str);
                    }
                } 
            });
        }

        function fnCoin(tag) {
            var bcoin = $('#bcoin').val();
            var scoin = $('#scoin').val();
            if (bcoin && bcoin != scoin) {
                $.ajax({
                    type : "POST",
                    url : "/workspace/php/money/post.php",
                    async : 'false',
                    data : {type:'data',action:tag,bcoin:bcoin,scoin:scoin},
                    success : function(result) {
                        result = JSON.parse(result);
                        if (result['code'] == 200) {
                            var str = '';
                            $.each(result['data'], function(index, val) {
                                str += '<h4>来源：'+val['from']+' 名称：'+val['coinName']+index+' 价格(美元)：<span class="">'+val['dollar']+'</span> 价格(人民币)：'+val['last']+' 最高价：'+val['highdollar']+' 最低价：'+val['lowdollar']+'</h4>';
                            });
                            $('#coinInfo').html(str);
                        }
                    } 
                });
            }
        }

        function getCoinList() {
            $.ajax({
                type : "POST",
                url : "/workspace/php/money/post.php",
                async : 'false',
                data : {type:'data',action:'list'},
                success : function(result) {
                    if (result.length > 0) {
                        var str = '';
                        result = JSON.parse(result);
                        $.each(result, function(index, val) {
                            str += '<option value="'+val+'">'+val+'</option>';
                        });
                        $('#bcoin,#scoin').empty().select2().append(str);
                    } else {
                        console.info('获取币种信息失败');
                    }
                } 
            });
        }

        function editXml() {
            $.ajax({
                type : "POST", //提交方式 
                url : "/workspace/php/money/post.php",//路径 
                async : 'false',
                data : {type:'data',action:'init'},//数据，这里使用的是Json格式进行传输 
                success : function(result) {//返回数据根据结果进行相应的处理 
                    if (result == 200) {
                        $('#error').html('更新成功');
                    } else {
                        $('#error').html('更新失败');
                    }
                } 
            });
        }
    </script>
</body>
</html>







