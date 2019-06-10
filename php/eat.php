<?php
if (isset($_POST['iSub'])) {
    $choose = array('楼下');
    $tmpArr = array();
    $res = 0;
    do {
        $tmpGo = array_rand($choose);
        $tmpArr[$choose[$tmpGo]] = empty($tmpArr[$choose[$tmpGo]]) ? 1 : $tmpArr[$choose[$tmpGo]] + 1;
        if (max($tmpArr) == 100) {
            $key = array_search(max($tmpArr), $tmpArr);
            $res = max($tmpArr);
            $res++;
        }
    } while ($res < 100);

    uasort($tmpArr, function ($a, $b) {
        if ($a == $b) return 0;
        return ($a < $b)?-1:1;
    });
    var_dump($tmpArr);
    die;
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>
    <link rel="stylesheet" href="">
</head>
<body>
    <form action="" method="post">
        <input type="hidden" name="iSub" value="1">
        <button type="submit">开始</button>
    </form>
</body>
</html>


