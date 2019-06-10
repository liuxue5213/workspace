<?php
function RGBToHex($match){
    // $regexp = "/^rgb\(([0-9]{0,3})\,\s*([0-9]{0,3})\,\s*([0-9]{0,3})\)/";
    // $re = preg_match($regexp, $rgb, $match);
    // $re = array_shift($match);

    $hexColor = "#";
    $hex = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F');
    for ($i = 0; $i < 3; $i++) {
        $r = null;
        $c = $match[$i];
        $hexAr = array();
        while ($c > 16) {
            $r = $c % 16;
            $c = ($c / 16) >> 0;
            array_push($hexAr, $hex[$r]);
        }
        array_push($hexAr, $hex[$c]);
        $ret = array_reverse($hexAr);
        $item = implode('', $ret);
        $item = str_pad($item, 2, '0', STR_PAD_LEFT);
        $hexColor .= $item;
    }
    return $hexColor;
}

$im = ImageCreateFromPng("login.png");
$width  = imagesx($im);
$height = imagesy($im);

var_dump($width);
var_dump($height);

$rgb = ImageColorAt($im, 100, 100);
var_dump($rgb);

$r = ($rgb >> 16) & 0xFF;
$g = ($rgb >> 8) & 0xFF;
$b = $rgb & 0xFF;

// echo rgb();
echo RGBToHex(array($r,$g,$b));
echo '<span style="color:#0E0E0E">111111111</span>';

