<?
require_once('./lib/captcha.php');
require_once('./lib/followlink.php');
require_once('./lib/http.php');
require_once('./lib/mysql.php');

session_start();

if ($_POST['captcha_follow'])
{
    if ($_POST['captcha_follow'] == $_SESSION['captcha_follow'])
    {
        $fl = new FollowLink($db_conn);
        $fl->get($_GET['k']);
        $fl->compute_hit();
        $fl->save();

        unset($_SESSION['captcha_follow']);
        header('Location: '.$fl->url);
        exit;
    }

    unset($_SESSION['captcha_follow']);
    header('Location: http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
    exit;
}

else if ($_GET['k'])
{
    if (!$_SESSION['captcha_follow']){$_SESSION['captcha_follow'] = captcha_text();}

    $captcha_form = '
<h3>Seguir FollowLink</h3>
<h4>El enlace solicitado no existe.</h4>
<p>Esto se puede a deber a que copi&oacute; mal la URL o la misma ha sido retirada.</p>
<p>Disculpe las molestias.</p>
    ';

    $fl = new FollowLink($db_conn);
    if ($fl->get($_GET['k']))
    {
        $captcha_form = '
<h3>Seguir FollowLink</h3>

<table border="1">
  <tr>
    <th>Archivo</th>
    <td>'.$fl->file_name.'</td>
  </tr>
  <tr>
    <th>Tipo</th>
    <td>'.$fl->content_type.'</td>
  </tr>
  <tr>
    <th>Tama&ntilde;o</th>
    <td>'.round(($fl->file_size/1024/1024), 3).' MiB</td>
  </tr>
</table>

<img src="media/img.php?t=f" />
<form method="post">
  <input type="text" size="6" name="captcha_follow" value="" />
  <input type="submit" name="continue" value="Continuar" />
</form>
        ';
    }
}

else if ($_POST['url'])
{
    if ($_POST['captcha_create'] == $_SESSION['captcha_create'])
    {
        $fl = new FollowLink($db_conn);
        $fl->set_url_info($_POST['url']);
        $fl->save();
        $key = $fl->id;

        if ($key)
        {
            if (!$_SESSION['urls']) {$_SESSION['urls'] = array();}

            array_push($_SESSION['urls'], 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'?k='.$key);

            unset($_SESSION['captcha_create']);
            header('Location: http://'.$_SERVER['HTTP_HOST'].str_replace('index.php', '', $_SERVER['PHP_SELF']));
            exit;
        }
    }

    $_SESSION['posted_url'] = $_POST['url'];

    unset($_SESSION['captcha_create']);
    header('Location: http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
    exit;
}

else
{
    if (!$_SESSION['captcha_create']) { $_SESSION['captcha_create'] = captcha_text(); }

    $url = 'http://';
    if ($_SESSION['posted_url'])
    {
        $url = $_SESSION['posted_url'];
        unset($_SESSION['posted_url']);
    }

    $create_form = '
<h3>Crear FollowLink</h3>
<img src="media/img.php?t=c" />
<form method="post">
  <input type="text" size="6" name="captcha_create" />
  <input type="text" size="50" name="url" value="'.$url.'" />
  <input type="submit" name="save" value="Guardar" />
</form>
    ';

    if (isset($_SESSION['urls']) && count($_SESSION['urls']) > 0)
    {
        $links_list = '
        <h3>Tus FollowLinks</h3>
        <ul>';
        foreach ($_SESSION['urls'] as $value)
        {
            $links_list .= '<li><input type="text" size="50" value="'.$value.'" /></li>';
        }
        $links_list .= '</ul>';
    }
}
?>

<html>
  <head>
    <title>FollowLink - Tus enlaces, seguros.</title>
  </head>
  <body>
    <h1><a href="<? echo 'http://'.$_SERVER['HTTP_HOST'].str_replace('index.php', '', $_SERVER['PHP_SELF']); ?>">FollowLink</a> &gt; &gt; &gt;</h1>
    <h2>Dej&aacute; que s&oacute;lo los humanos puedan descargar tus links ;)</h2>

    <p>FollowLink permite que tus enlaces sean accesibles mediante un CAPTCHA, de modo que bots no puedan hacer hotlinks o descargas abusivas de tus contenidos ;)</p>
    <p>&iquest;C&oacute;mo funciona?
      <ol>
        <li>Carg&aacute; tu enlace.</li>
        <li>Obten&eacute; una URL alternativa.</li>
        <li>Public&aacute; la URL alternativa en Blogs, Foros y donde quieras.</li>
      </ol>
    </p>
    <?
    if (isset($create_form)) { echo $create_form; }
    if (isset($captcha_form)) { echo $captcha_form; }
    if (isset($links_list)) { echo $links_list; }
    ?>
  </body>
</html>
