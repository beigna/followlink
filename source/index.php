<?

require_once('./lib/captcha.php');
require_once('./lib/followlink.php');
require_once('./lib/global_messages.php');
require_once('./lib/http.php');
require_once('./lib/mysql.php');
require_once('./lib/vemplator.php');
require_once('./view.php');


set_include_path(get_include_path().PATH_SEPARATOR.'/home/nachopro/desarrollo/followlink/templates');
session_start();

if (!$_SESSION['messages']) {$_SESSION['messages'] = new GlobalMessages();}

# TODO: This should be more tidy.

if ($_GET['k'])
{
    follow();
}
else
{
    home();
}

