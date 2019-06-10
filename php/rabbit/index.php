<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>设置</title>
	<!-- 最新版本的 Bootstrap 核心 CSS 文件 -->
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css" />

	<!-- 可选的 Bootstrap 主题文件（一般不用引入） -->
	<link rel="stylesheet" href="../bootstrap/css/bootstrap-theme.min.css" />

	<link rel="stylesheet" href="../kindeditor/themes/default/default.css" />

	<!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
	<script charset="utf-8" src="../bootstrap/js/jquery-3.2.1.min.js"></script>

	<style type="text/css">
		.hidden{display: none;}
	</style>
</head>
<body>
	<form id="info" class="form-horizontal" action="setMailInfo.php" method="post">
		<div class="form-group">
			<label for="email" class="col-sm-2 control-label">收件人</label>
			<div class="col-sm-8">
				<input id="email" type="text" class="form-control" name="email" required="required">
			</div>
		</div>

		<div id="gcc" class="form-group">
			<label for="cc" class="col-sm-2 control-label">抄送</label>
			<div class="col-sm-8">
				<input id="cc" type="text" class="form-control" name="cc" required="required">
			</div>
		</div>

		<div id="gbc" class="form-group">
			<label for="bcc" class="col-sm-2 control-label">密送</label>
			<div class="col-sm-8">
				<input id="bcc" type="text" class="form-control" name="bcc" required="required">
			</div>
		</div>

		<div class="form-group">
			<label class="col-sm-2 control-label"></label>
			<span id="aSend">
				<a id="aCC" href="#" title="什么是抄送：同时将这一封邮件发送给其他联系人。" onclick="showCC()">添加抄送</a> --
				<a id="aBCC" href="#" title="什么是密送：同时将这一封邮件发送给其他联系人，但收件人及抄送人不会看到密送人。" onclick="showSC()">添加密送</a> |
			</span>
			<span style="display: none;">每个收件人将收到单独发给他/她的邮件。
				<input type="hidden" name="asc" value="1">
			</span>
			<a id="aSC" href="#" title="什么是分送：会对多个人一对一发送。每个人将收到单独发给他/她的邮件。" onclick="showASend()">分别发送</a>
		</div>

		<div class="form-group">
			<label for="subject" class="col-sm-2 control-label">主题</label>
			<div class="col-sm-8">
				<input id="subject" type="text" class="form-control" name="subject" required="required">
			</div>
		</div>

		<div class="form-group">
			<label for="cont" class="col-sm-2 control-label">邮件内容</label>
			<div class="col-sm-8">
				<textarea name="cont" style="width:720px;height:250px;"></textarea>
				<!-- <textarea name="cont" style="width:720px;height:250px;visibility:hidden;"></textarea> -->
			</div>
		</div>
		
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<a class="btn btn-default" onclick="return checkSubmit()">发送</a>
				<a class="btn btn-default">存草稿</a>
				<a class="btn btn-default">定时发送</a>
			</div>
		</div>
	</form>

	<script charset="utf-8" src="../bootstrap/js/bootstrap.min.js"></script>

	<!-- <script charset="utf-8" src="../kindeditor/kindeditor-min.js"></script> -->
	<!-- <script charset="utf-8" src="../kindeditor/lang/zh_CN.js"></script> -->

	<script>
		// var editor;
		// KindEditor.ready(function(K) {
		// 	var editor = K.create('textarea[name="cont"]', {
		// 		resizeType : 1,
		// 		allowPreviewEmoticons : false,
		// 		allowImageUpload : false,
		// 		items : [
		// 			'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
		// 			'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
		// 			'insertunorderedlist', '|', 'emoticons', 'image', 'link'],
		// 	});
		// });
	</script>

	<script>
		$(document).ready(function(){
			$("#gcc").hide();
			$("#gbc").hide();
		});

		function showCC()
		{
			if($("#gcc").is(":hidden")){
				$("#gcc").show();
				$("#aCC").html('取消抄送');
			}else{
				$("#gcc").hide();
				$("#aCC").html('添加抄送');
			}
		}

		function showSC()
		{
			if($("#gbc").is(":hidden")){
				$("#gbc").show();
				$("#aBCC").html('取消密送');
			}else{
				$("#gbc").hide();
				$("#aBCC").html('添加密送');
			}
		}

		function showASend()
		{
			if($("#aSend").is(":hidden")){
				$("#aSC").html('分别发送');
				$("#aSC").prev().hide();
				$("#aSend").show();
			}else{
				$("#aSC").prev().removeAttr("style");
				$("#aSC").html('取消分别发送');
				$("#gcc").hide();
				$("#gbc").hide();
				$("#aSend").hide();
			}
		}

		function checkSubmit() 
		{
			$('#info').submit();
			// var isSub = true;
			// K('input[name=getHtml]').click(function(e) {
			// 	alert(editor.html());
			// });
			// K('input[name=getText]').click(function(e) {
			// 	alert(editor.text());
			// });

			// return isSub;
		}
	</script>
</body>
</html>