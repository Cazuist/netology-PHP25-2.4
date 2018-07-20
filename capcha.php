<?php

    include_once('functions.php');

    $image = imagecreatetruecolor(150, 150);
    $backColor = imagecolorallocate($image, 194, 221, 124);
    
    $r = rand(0, 255);
    $g = rand(0, 255);
    $b = rand(0, 255);

    $textColor = imagecolorallocate($image, $r, $g, $b);

    $boxFile = __DIR__.'/img/capcha.jpg';
    $imBox = imagecreatefromjpeg($boxFile);

    imagefill($image, 0, 0, $backColor);
    imagecopy($image, $imBox, 10, 10, 0, 0, 130, 130);

    $code = rand(10000, 99999);
    $_SESSION['code'] = $code;
    
    $fontFile = __DIR__.'/fonts/PoeticaChancery.ttf';

    imagettftext($image, 50, 45, 45, 120, $textColor, $fontFile, $code);

    header('Content-type: image/jpeg');
    imagejpeg($image);
    imagedestroy($image);

?>