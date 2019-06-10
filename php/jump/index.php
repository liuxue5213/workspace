<?php
define('BODY_WIDTH', 75);

function similar($rgb1, $rgb2, $value = 10) {
    $r1 = ($rgb1 >> 16) & 0xFF;
    $g1 = ($rgb1 >> 8) & 0xFF;
    $b1 = $rgb1 & 0xFF;
    $r2 = ($rgb2 >> 16) & 0xFF;
    $g2 = ($rgb2 >> 8) & 0xFF;
    $b2 = $rgb2 & 0xFF;
    return abs($r1 - $r2) < $value && abs($b1 - $b2) < $value && abs($g1 - $g2) < $value;
}

function getStart() {
    global $image;
    $l_r    = 0;
    $cnt    = 0;
    $width  = imagesx($image);
    $height = imagesy($image);

    for ($i = $height / 3 * 2; $i > $height / 3; $i--) {
        for ($l = 0; $l < $width; $l++) {
            $c = imagecolorat($image, $l, $i);
            if (similar($c, 3750243, 20)) {
                $r = $l;
                while($r+1 < $width && similar(imagecolorat($image, $r+1, $i), 3750243, 20)){
                    $r++;
                }
                if ($r - $l > BODY_WIDTH * 0.5){
                    if ($r <= $l_r) {
                        return [$i, round(($l + $r) / 2)];
                    } else {
                        $cnt = 0;
                    }
                    $l_r = $r;
                }
                $l = $r;
            }
        }
    }
    // return array($x, $y);
}


$image = imagecreatefrompng('./autojump.png');
var_dump($image);
list($sx, $sy) = getStart();
var_dump($sx);
var_dump($sy);



