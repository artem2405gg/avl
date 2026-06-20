<?php
// Генератор простой иконки-заглушки
$size = $_GET['size'] ?? 192;
$img = imagecreatetruecolor($size, $size);
$bg = imagecolorallocate($img, 26, 26, 46); // #1a1a2e
$textColor = imagecolorallocate($img, 76, 201, 240); // #4cc9f0
imagefill($img, 0, 0, $bg);

$text = 'AVL';
$fontSize = $size / 3;
$x = ($size - $fontSize * strlen($text) * 0.6) / 2;
$y = ($size + $fontSize * 0.4) / 2;
imagestring($img, 5, (int)$x, (int)$y, $text, $textColor);

header('Content-Type: image/png');
imagepng($img);
imagedestroy($img);