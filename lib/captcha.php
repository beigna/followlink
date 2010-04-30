<?
function captcha($text)
{
    $img = imagecreatetruecolor(100, 30);
    $bg_color = imagecolorallocate($img, 145, 165, 235);
    $font_color = imagecolorallocate($img, 0, 255, 0);

    imagefilledrectangle($img, 0, 0, 99, 29, $bg_color);

    $font = 'dejavusans.ttf';

    imagefttext($img, 14, 0, 5, 22, $font_color, $font, $text);

    header('Content-Type: image/png');
    return imagepng($img);
}

function captcha_text($len=4)
{
    $text = md5(rand(0, 999999));
    return substr($text, rand(0, strlen($text) - $len), $len);
}
?>
