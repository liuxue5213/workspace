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
                                <label>出发地：</label><input type="text" id="from_station" /> 
                                <label>目的地：</label><input type="text" id="to_station" />
                                <label>出发日：</label><input type="date" id="train_date" />
                               <!--  <label for="use_page">是否使用分页</label>
                                <select id="use_page" name="use_page" onchange="usePage()">
                                    <option value="0">否</option>
                                    <option value="1">是</option>
                                </select> -->
                                <button type="button" class="btn" onclick="check()">查询</button><br>

                                <label>車次:</label><input id="train_no" type="text"/>
                                <label>席别:</label>
                                <select id="seat" name="seat">
                                    <option value="">無</option>
                                    <option value="principal_seat">特等座</option>
                                    <option value="first_seat">一等座</option>
                                    <option value="second_seat">二等座</option>
                                    <option value="advanced_soft_sleeper">高级软卧</option>
                                    <option value="soft_sleeper">软卧</option>
                                    <option value="top_sleeper">动卧</option>
                                    <option value="harder_sleeper">硬卧</option>
                                    <option value="soft_seat">软座</option>
                                    <option value="harder_seat">硬座</option>
                                </select>
                                <label>票數:</label><input id="nums" type="text" placeholder="默認50"/>
                                <label>預定日期：</label><input id="pre_date" type="date"/>
                                <button type="button" class="btn" onclick="predefined()">預定</button>
                            </fieldset>
                            <span id="error"></span>
                        </div>
                        <table id="list"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var is_use_page = false;
        $(function () {
            initTable();
        });

        function check() {
            $('#list').bootstrapTable('refresh'); //刷新表格
        }

        function usePage() {
            var usePage = $('#usePage').val();
            if (usePage == 1) {
                is_use_page = true;
            } else {
                is_use_page = false;
            }
        }

        function predefined(){
            var from_station = $("#from_station").val();
            var to_station = $("#to_station").val();
            var pre_date = $("#pre_date").val();
            var train_no = $("#train_no").val();
            var nums = $("#nums").val();
            var seat = $("#seat").val();
            if (seat == '') {
                return false;
            }
            $.ajax({
                type:"POST",
                url:"/workspace/php/12306/get.php",
                data:{from_station:from_station,to_station:to_station,pre_date:pre_date,train_no:train_no,nums:nums,seat:seat},
                async:false,
                success:function (responce){
                    
                }
            });
        }

        function initTable(){
            $('#list').bootstrapTable({
                method:'POST',
                dataType:'json',
                contentType: "application/x-www-form-urlencoded",
                cache: false,
                striped: true,                              //是否显示行间隔色
                sidePagination: "server",           //分页方式：client客户端分页，server服务端分页（*）
                url:"/workspace/php/12306/getTrain.php",
                height: $(window).height() - 110,
                width:$(window).width(),
                showColumns:true,
                pagination:false,
                queryParams : queryParams,
                minimumCountColumns:2,
                pageNumber:1,                       //初始化加载第一页，默认第一页
                pageSize: 100,                       //每页的记录行数（*）
                pageList: [50, 100, 200],        //可供选择的每页的行数（*）
                uniqueId: "id",                     //每一行的唯一标识，一般为主键列
                showExport: true,
                exportDataType: 'all',
                responseHandler: responseHandler,
                columns: [
                    {field: '', title: 'No.', formatter: function (value, row, index) {
                        return index+1;
                    }},
                    {field : 'train_no', title : '车次', align : 'center', valign : 'middle', sortable : true},
                    {field : 'start_station', title : '起始站', align : 'center', valign : 'middle', sortable : true},
                    {field : 'end_station', title : '终点站', align : 'center', valign : 'middle', sortable : true},
                    {field : 'departure_station', title : '出发站', align : 'center', valign : 'middle', sortable : true},
                    {field : 'arrival_station', title : '到达站', align : 'center', valign : 'middle', sortable : true},
                    {field : 'departure_time', title : '出发时间', align : 'center', valign : 'middle', sortable : true},
                    {field : 'arrival_time', title : '到达时间', align : 'center', valign : 'middle', sortable : true},
                    {field : 'lasted', title : '历时', align : 'center', valign : 'middle', sortable : true},
                    {field : 'principal_seat', title : '特等座', align : 'center', valign : 'middle', sortable : true},
                    {field : 'first_seat', title : '一等座', align : 'center', valign : 'middle', sortable : true},
                    {field : 'second_seat', title : '二等座', align : 'center', valign : 'middle', sortable : true},
                    {field : 'advanced_soft_sleeper', title : '高级软卧', align : 'center', valign : 'middle', sortable : true},
                    {field : 'soft_sleeper', title : '软卧', align : 'center', valign : 'middle', sortable : true},
                    {field : 'top_sleeper', title : '动卧', align : 'center', valign : 'middle', sortable : true},
                    {field : 'harder_sleeper', title : '硬卧', align : 'center', valign : 'middle', sortable : true},
                    {field : 'soft_seat', title : '软座', align : 'center', valign : 'middle', sortable : true},
                    {field : 'harder_seat', title : '硬座', align : 'center', valign : 'middle', sortable : true},
                    {field : 'no_seat', title : '无座', align : 'center', valign : 'middle', sortable : true},
                    {field : 'other_info', title : '其他', align : 'center', valign : 'middle', sortable : true},
                    {field : 'remark', title : '备注', align : 'center', valign : 'middle', sortable : true,formatter: function (value, row, index) {
                            if (row['is_predefined'] == 'Y') {
                                return "<a href='#'>"+value+"</a>";
                            } else {
                                return value;
                            }
                    }}
                ],
                onLoadSuccess: function(){  //加载成功时执行  
                    console.info('加载成功时执行');
                },  
                onLoadError: function(e){  //加载失败时执行
                    console.info('加载失败时执行'+e);
                }  
            });
        }

//        formatter : function (value, row, index){
//            return new Date(value).format('yyyy-MM-dd hh:mm:ss');
//        }

        function queryParams(params) {
            var param = {
                from_station : $("#from_station").val(),
                to_station : $("#to_station").val(),
                train_date : $("#train_date").val(),
                limit : this.limit, // 页面大小
                offset : this.offset, // 页码
                limit : this.pageNumber,
                size : this.pageSize,
                // use_page : is_use_page
            };
            return param;
        }

        // 用于server 分页，表格数据量太大的话 不想一次查询所有数据，可以使用server分页查询，数据量小的话可以直接把sidePagination: "server"  改为 sidePagination: "client" ，同时去掉responseHandler: responseHandler就可以了，
        function responseHandler(res) {
            if (res) {
                return {
                    "rows" : res.rows,
                    "total" : res.total
                };
            } else {
                return {
                    "rows" : [],
                    "total" : 0
                };
            }
        }

    </script>
</body>
</html>