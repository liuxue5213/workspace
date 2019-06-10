<?php
    require './common.php';
    $Common = new Money();
    $check = $Common->checkUserInfo();
    if (!$check) {
        header("Location: http://johnscott1989.top"); 
        exit;
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>
    <link rel="stylesheet" href="">
    <!-- 最新版本的 Bootstrap 核心 CSS 文件 -->
    <link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="../../bootstrap-table/bootstrap-table.css">
    <!-- 可选的 Bootstrap 主题文件（一般不用引入） -->
    <link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <script src="http://libs.baidu.com/jquery/2.0.0/jquery.min.js"></script>
    <!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
    <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script type="text/javascript" src="../../bootstrap-table/bootstrap-table.js"></script>
    <script type="text/javascript" src="../../bootstrap-table/bootstrap-table-zh-CN.js"></script>
    <script type="text/javascript" src="../../bootstrap-table/bootstrap-table-export.js"></script>
</head>
<body>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="row-fluid">
                <div class="span12">
                    <div>
                        <fieldset>
                            <legend>服务中心</legend>
                            <label>来源：</label>
                            <select id="source">
                                <option value="1">HB</option>
                            </select>

                            <label>操作类型：</label>
                            <select id="tradeType">
                                <option value="1">我要买入</option>
                                <option value="0">我要卖出</option>
                            </select>

                            <label>货币类型：</label>
                            <select id="coinId">
                                <option value="1">BTC</option>
                                <option value="2">USDT</option>
                                <option value="3">ETH</option>
                            </select>

                            <label>是否在线：</label>
                            <select id="online">
                                <option value="1">是</option>
                                <option value="2">否</option>
                                <option value="0">全部</option>
                            </select>

                            <label>全部查询：</label>
                            <select id="is_all">
                                <option value="0">否</option>
                                <option value="1">是</option>
                            </select>
                            <button type="button" class="btn" onclick="reFresh()">查询</button>

                            <label>定时刷新：</label>
                            <input id="mtime" type="hidden">
                            <input id="intime" type="number" value="10" style="width: 50px;">秒&nbsp;&nbsp;
                            <button id="inbutton" type="button" class="btn" onclick="setInter()">开始</button>
                        </fieldset>
                        <span id="error" style="color: red"></span>
                    </div>
                    <table id="list"></table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var msginterval = '';
    var intval = 0;
    var is_use_page = null;
    $(function () {
        initTable();
    });

    function setInter() {
        intval += 1;
        var intime = $('#intime').val();
        if (intime >= 10) {
            $('#error').html('');
            intime = parseInt(intime) * 1000;
            if (intval % 2 == 0) {
                $('#intime').next().html('开始');
                clearInterval(msginterval);
            } else {
                $('#intime').next().html('停止');
                msginterval = window.setInterval("reFresh()", intime);
            }
        } else {
            intval += 1;
            $('#error').html('定时刷新间隔必须大于10');
        }
    }

    function reFresh() {
        $('#list').bootstrapTable('refresh'); //刷新表格
    }

    function initTable(){
        $('#list').bootstrapTable({
            method:'POST',
            dataType:'json',
            contentType: "application/x-www-form-urlencoded",
            cache: false,
            striped: true, //是否显示行间隔色
            sidePagination: "server", //分页方式：client客户端分页，server服务端分页（*）
            url:"/workspace/php/money/post.php",
            height: $(window).height() - 110,
            width:$(window).width(),
            showColumns:true,
            pagination:false,
            queryParams : queryParams,
            minimumCountColumns:2,
            pageNumber:1, //初始化加载第一页，默认第一页
            pageSize: 100, //每页的记录行数（*）
            pageList: [50, 100, 200], //可供选择的每页的行数（*）
            uniqueId: "id", //每一行的唯一标识，一般为主键列
            showExport: true,
            exportDataType: 'all',
            responseHandler: responseHandler,
            columns: [
                {field: '', title: 'No.', formatter: function (value, row, index) {
                    return index+1;
                }},
                {field : 'userName', title : '用户名', align : 'center', valign : 'middle', sortable : true},
                {field : 'tradeMonthTimes', title : '近30日成交', align : 'center', valign : 'middle', sortable : true},
                {field : 'isOnline', title : '是否在线', align : 'center', valign : 'middle', sortable : true},
                {field : 'price', title : '价格', align : 'center', valign : 'middle', sortable : true},
                {field : 'tradeCount', title : '总量', align : 'center', valign : 'middle', sortable : true},
                {field : 'payMethod', title : '支付方式', align : 'center', valign : 'middle', sortable : true},
            ],
            onLoadSuccess: function() {
//                console.info('加载成功时执行');
            },
            onLoadError: function(e) {
//                console.info('加载失败时执行'+e);
            }
        });
    }

    function queryParams(params) {
        var param = {
            tradeType : $("#tradeType").val(),
            coinId : $("#coinId").val(),
            online : $("#online").val(),
            source : $("#source").val(),
            is_all : $("#is_all").val(),
            mtime : $("#mtime").val(),
            offset : this.offset, // 页码
            limit : this.pageNumber,
            size : this.pageSize
        };
        return param;
    }

    function responseHandler(res) {
        if (res) {
            $('#mtime').val(res.mtime);
            return {
                "rows" : res.rows,
                "total" : res.total,
            };
        } else {
            return {
                "rows" : [],
                "total" : 0,
            };
        }
    }
</script>
</body>
</html>
