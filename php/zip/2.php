<script type="text/javascript" src="jquery-1.7.2.js"></script>
<script type="text/javascript" src="ajax.js"></script>
<?php
    header("Content-type:text/html;charset=utf8");
    require('./lib/getfile.php');
    $scandir=new traverseDir();
    $scandir->scandir($scandir->currentdir);
    $scandir->currentdir;
    
    if (isset($_POST['down_load'])){ 
        $items=$_POST['items'];
        $scandir->tozip($items);//将文件压缩成zip格式
    } 
    echo "当前的工作目录:".$scandir->currentdir;
    echo "<br>当前目录下的所有文件";
?>

<form action="2.php" method="POST">
<table>
    <tr>
        <td></td>
        <td>名称</td>
        <td>大小(KB)</td>
    </tr>
<?php
    $res=$scandir->fileinfo;
    foreach ($res as $k=>$v){
        if (!($k=='.' || $k=='..'))    {//过滤掉.和..
?>
    <tr>
        <td><input type="checkbox" name="items[]" class="filename" value="<?php echo $k;?>"></td>
        <td><?php echo $k; ?></td>
        <td><?php echo number_format($v[0],0); ?></td>
    </tr>
<?php
        }
    }
?>
    <tr>
        <td><input type="checkbox" id="selall"><label for="selall">全选</label></td>
        <td><input type="submit" name="down_load" value="打包并下载" id="tozip_tetttt"></td>
    </tr>
</table>
</form>