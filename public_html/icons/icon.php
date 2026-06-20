<?php
header('Content-Type: image/png');
$img = imagecreatetruecolor(192, 192);
$bg = imagecolorallocate($img, 26, 26, 46);
$text = imagecolorallocate($img, 255, 255, 255);
imagefill($img, 0, 0, $bg);
imagestring($img, 5, 70, 85, 'AVL', $text);
imagepng($img);
imagedestroy($img);