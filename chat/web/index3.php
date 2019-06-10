<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>jquery动态增减选项卡</title>
    <style type="text/css">
        body { font-family:Lucida Sans, Lucida Sans Unicode, Arial, Sans-Serif; font-size:13px; margin:0px auto;}
        #tabs { margin:0; padding:0; list-style:none; overflow:hidden; }
        #tabs li { float:left; display:block; padding:5px; background-color:#bbb; margin-right:5px;}
        #tabs li a { color:#fff; text-decoration:none; }
        #tabs li.current { background-color:#e1e1e1;}
        #tabs li.current a { color:#000; text-decoration:none; }
        #tabs li a.remove { color:#f00; margin-left:10px;}
        #content { background-color:#e1e1e1;}
        #content p { margin: 0; padding:20px 20px 100px 20px;}

        #main { width:900px; margin:0px auto; overflow:hidden;background-color:#F6F6F6; margin-top:20px;
            -moz-border-radius:10px;  -webkit-border-radius:10px; padding:30px;}
        #wrapper, #doclist { float:left; margin:0 20px 0 0;}
        #doclist { width:150px; border-right:solid 1px #dcdcdc;}
        #doclist ul { margin:0; list-style:none;}
        #doclist li { margin:10px 0; padding:0;}
        #documents { margin:0; padding:0;}

        #wrapper { width:700px; margin-top:20px;}

    </style>
    <script src="http://libs.baidu.com/jquery/1.9.0/jquery.js"></script>
    <!-- <script type="text/javascript" src="jquery-1.4.min.js" ></script> -->
    <script type="text/javascript">
        $(document).ready(function() {
            $("#documents a").click(function() {
                addTab($(this));
            });

            $('#tabs a.tab').on('click', function() {
                // Get the tab name 获取tab名称
                var contentname = $(this).attr("id") + "_content";

                // hide all other tabs 隐藏其他tab
                $("#content p").hide();
                $("#tabs li").removeClass("current");

                // show current tab 显示当前tab
                $("#" + contentname).show();
                $(this).parent().addClass("current");
            });

            $('#tabs a.remove').on('click', function() {
                // Get the tab name 获取tab名称
                var tabid = $(this).parent().find(".tab").attr("id");

                // remove tab and related content 删除标签和相关内容
                var contentname = tabid + "_content";
                $("#" + contentname).remove();
                $(this).parent().remove();

                // if there is no current tab and if there are still tabs left, show the first one 如果没有当前的标签，如果仍然有标签左，显示第一个
                if ($("#tabs li.current").length == 0 && $("#tabs li").length > 0) {

                    // find the first tab     找到第一个标签
                    var firsttab = $("#tabs li:first-child");
                    firsttab.addClass("current");

                    // get its link name and show related content 获取它的链接名称并显示相关内容
                    var firsttabid = $(firsttab).find("a.tab").attr("id");
                    $("#" + firsttabid + "_content").show();
                }
            });
        });
        function addTab(link) {
            // hide other tabs 隐藏其他的标签
            $("#tabs li").removeClass("current");
            $("#content p").hide();

            // If tab already exist in the list, return  如果选项卡中已经存在于列表中，返回
            if ($("#" + $(link).attr("rel")).length != 0){
                //当前标签  高亮
                $("#" + $(link).attr("rel")).parent().addClass("current");
                //显示当前标签的内容
                $("#" + $(link).attr("rel") + "_content").show();
                return;
            }

            // add new tab and related content 添加新标签和相关内容
            $("#tabs").append("<li class='current'><a class='tab' id='" +
                $(link).attr("rel") + "' href='#'>" + $(link).html() +
                "</a><a href='#' class='remove'>x</a></li>");

            $("#content").append("<p id='" + $(link).attr("rel") + "_content'>" +
                $(link).attr("title") + "</p>");

            // set the newly added tab as current 将新添加的标签设置为当前
            $("#" + $(link).attr("rel") + "_content").show();
        }
    </script>
</head>
<body>
<div id="main">
    <div id="doclist">

        <ul id="documents">
            <li><a href="#" rel="Document1" title="This is the content of Document1" id="1110" >Document1</a></li>
            <li><a href="#" rel="Document2" title="This is the content of Document2" >Document2</a></li>
            <li><a href="#" rel="Document3" title="This is the content of Document3" >Document3</a></li>
            <li><a href="#" rel="Document4" title="This is the content of Document4" >Document4</a></li>
            <li><a href="#" rel="Document5" title="This is the content of Document5" >Document5</a></li>

        </ul>
    </div>
    <div id="wrapper">
        <ul id="tabs">
            <!-- Tabs go here -->
        </ul>
        <div id="content">
            <!-- Tab content goes here -->
        </div>

    </div>
</div>
</body>
</html>