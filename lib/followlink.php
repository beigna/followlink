<?

class FollowLink
{
    private $tt;
    private $key;
    private $data;

    function __construct()
    {
        $this->tt = Tyrant::connect('localhost', 1978);
    }

    private function read_data()
    {
        $tmp_data = $this->tt->get($this->key);
        $this->data = json_decode($tmp_data, true);
    }

    private function save_data()
    {
        $tmp_data = json_encode($this->data);
        $this->tt->put($this->key, $tmp_data);
    }

    private function generate_unique_key()
    {
        do {
            $key = substr(sha1(rand()), rand(0,32), 8);
        } while ($tt[$key]);

        $this->key = $key;
    }

    private function compute_hit()
    {
        $this->data['hits']++;
        $this->data['last_hit'] = gmdate('Y-m-d H:i:s', time());
    }

    private function url_is_valid($url)
    {
        $regex = '/^(https?|ftp):\/\/[A-Za-z0-9\-_]+\\.+[A-Za-z0-9\.\/%&#=\?\-_]+$/i';

        if (preg_match($regex, $url))
        {
            return true;
        }
        return false;
    }

    public function create($url)
    {
        if ($this->url_is_valid($url))
        {
            $url_info = get_http_headers($url);

            $this->generate_unique_key();
            $this->data = Array(
                'url' => $url,
                'file_name' => $url_info['filename'],
                'content_type' => $url_info['Content-Type'],
                'file_size' => $url_info['Content-Length'],
                'created_at' => gmdate('Y-m-d H:i:s', time()),
                'ip' => $_SERVER['REMOTE_ADDR'],
                'hits' => 0,
                'last_hit' => '1984-09-28 06:50:00',
            );

            $this->save_data();
        }
    }

    public function get($key, $stats=0)
    {
        $this->key = $key;

        $this->read_data();

        if ($stats == 1)
        {
            $this->compute_hit();
            $this->save_data();
        }

        return $this->data;
    }

    public function get_key()
    {
        return $this->key;
    }
}

?>
