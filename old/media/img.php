<?
session_start();
require('../lib/captcha.php');

if ($_GET['t'] == 'c')
{ $text = $_SESSION['captcha_create']; }

else if ($_GET['t'] == 'f')
{ $text = $_SESSION['captcha_follow']; }

if ($text)
{
    captcha($text);
}
else
{
    captcha('error!');
}

?>
