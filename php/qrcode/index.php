<?php

// $t=time();
// file_put_contents('data/'.$t.'.txt',$_COOKIE);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>测试</title>
</head>
<body>
    <h2>生成二维码 长链接地址--->短链接</h2>
    <form action="qrcode_save.php" method="post">
        类型：<input id='a' type="radio" name="type" value="0" checked="true"  /><label for="a">二维码</label>
        <input id="b" type="radio" name="type" value="1" /><label for="b">长链接</label><br><br>
        二维码内容：<input type="text" name="cont" /><br><br>
        二维码大小：<input type="text" name="len" /><br><br>
        长链接：<input type="text" name="url_long" /><br><br>
        <input type="submit" value="生成" /><br>
    </form>
</body>
</html>