<?

function tt_connect()
{
    return Tyrant::connect('localhost', 1978);
}

function get_url($key)
{
    if (!$tt){$tt = tt_connect();}

    $data = json_decode($tt->get($key), true);
    $data['hits'] = $data['hits'] + 1;
    $data['last_hit'] = gmdate('Y-m-d H:i:s', time());

    $tt->put($key, json_encode($data));

    return $data;
}

function save_url($url)
{
    if (!$tt){$tt = tt_connect();}

    $key = substr(sha1(rand()), 0, 8);
    while ($tt[$key])
    {
        $key = substr(sha1(rand()), 0, 8);
    }

    $data = json_encode(array(
        'url' => $url,
        'created_at' => gmdate('Y-m-d H:i:s', time()),
        'ip' => $_SERVER['REMOTE_ADDR'],
        'hits' => 0,
        'last_hit' => '0000-00-00 00:00:00'
    ));

    $tt->put($key, $data);

    return $key;
}

?>
