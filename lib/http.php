<?
if (!function_exists(curl_init))
{
    die('PHP do not support libcurl.');
}

function get_http_headers($url)
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_NOBODY, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $headers = curl_exec($ch);
    curl_close($ch);

    $data = Array();

    $lines = explode("\n", $headers);
    foreach($lines as $line)
    {
        if (strpos($line, ':') !== false)
        {
            $tmp = explode(':', $line);
            $key = trim($tmp[0]);

            $tmp = $tmp[1];
            $tmp = explode(';', $tmp);
            $value = trim($tmp[0]);


            $data[$key] = $value;
        }
    }

    $filename = 'index';

    $tmp = explode('/', $url);
    $tmp = $tmp[count($tmp)-1];
    if ($tmp) { $filename = $tmp; }

    $data['filename'] = $filename;

    return $data;
}

?>
