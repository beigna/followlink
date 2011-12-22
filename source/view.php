<?

function home()
{
    $home_url = sprintf('http://%s', $_SERVER['HTTP_HOST'].str_replace('index.php', '', $_SERVER['PHP_SELF']));

    if ($_SERVER['REQUEST_METHOD'] == 'GET')
    {
        if (!$_SESSION['captcha_create']) {$_SESSION['captcha_create'] = captcha_text();}

        $previous_url = $_SESSION['previous_url'] ? $_SESSION['previous_url'] : 'http://';

        # Sets and Renders
        $ct = new vemplator();
        $ct->assign('previous_url', $previous_url);
        $ct->assign('links', $_SESSION['urls']);
        $content = $ct->output('home.html');

        $bt = new vemplator();
        $bt->assign('home_url', $home_url);
        $bt->assign('messages', $_SESSION['messages']->get_all());
        $bt->assign('content', $content);

        echo $bt->output('base.html');
    }

    else if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        if ($_POST['captcha_create'] == $_SESSION['captcha_create'])
        {
            $fl = new FollowLink($GLOBALS['db_conn']);
            if ($fl->set_url_info($_POST['url']))
            {
                $fl->save();
                $key = $fl->id;

                if ($key)
                {
                    if (!$_SESSION['urls']) {$_SESSION['urls'] = array();}

                    array_push($_SESSION['urls'], sprintf('%s?k=%s', $home_url, $key));

                    $_SESSION['messages']->set('ok', 'Tu URL ha sido creada.');

                    unset($_SESSION['captcha_create']);
                    unset($_SESSION['previous_url']);
                    header(sprintf('Location: %s', $home_url));
                    exit;
                }
                else
                {
                    $_SESSION['messages']->set('error', 'Error interno.');
                }
            }
            else
            {
                $_SESSION['messages']->set('error', 'La URL es mala.');
            }
        }
        else
        {
            $_SESSION['messages']->set('error', 'El captcha es malo.');
        }


        $_SESSION['previous_url'] = $_POST['url'];

        unset($_SESSION['captcha_create']);
        header(sprintf('Location: %s', $home_url));
        exit;
    }

    else
    {
        echo sprintf('%s request method is not supported.', $_SERVER['REQUEST_METHOD']);
    }
}

function follow()
{
    $home_url = sprintf('http://%s', $_SERVER['HTTP_HOST'].str_replace('index.php', '', $_SERVER['PHP_SELF']));
    $requested_url = $_SERVER['REQUEST_URI'];

    if ($_SERVER['REQUEST_METHOD'] == 'GET')
    {
        if (!$_SESSION['captcha_follow']) {$_SESSION['captcha_follow'] = captcha_text();}


        $key_exists = false;
        $fl = new FollowLink($GLOBALS['db_conn']);
        if ($fl->get($_GET['k']))
        {
            $file_name = $fl->file_name;
            $content_type = $fl->content_type;
            $file_size = round(($fl->file_size/1024/1024), 3);
            $key_exists = true;
        }
        else
        {
            $_SESSION['messages']->set('error', 'El link solicitado no existe.');
        }

        # Sets and Renders
        $ct = new vemplator();
        $ct->assign('key_exists', $key_exists);
        $ct->assign('file_name', $file_name);
        $ct->assign('content_type', $content_type);
        $ct->assign('file_size', $file_size);
        $content = $ct->output('follow.html');

        $bt = new vemplator();
        $bt->assign('home_url', $home_url);
        $bt->assign('messages', $_SESSION['messages']->get_all());
        $bt->assign('content', $content);

        echo $bt->output('base.html');
    }

    else if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        if ($_POST['captcha_follow'] == $_SESSION['captcha_follow'])
        {
            $fl = new FollowLink($GLOBALS['db_conn']);
            if ($fl->get($_GET['k']))
            {
                $fl->compute_hit();
                $fl->save();

                unset($_SESSION['captcha_follow']);
                header('Location: '.$fl->url);
                exit;
            }
            else
            {
                $_SESSION['messages']->set('error', 'El link no existe.');
            }
        }
        else
        {
            $_SESSION['messages']->set('error', 'El captcha es malo.');
        }

        unset($_SESSION['captcha_follow']);
        header(sprintf('Location: %s', $requested_url));
        exit;
    }

    else
    {
        echo sprintf('%s request method is not supported.', $_SERVER['REQUEST_METHOD']);
    }
}

?>
