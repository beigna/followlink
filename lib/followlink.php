<?

class FollowLink
{

    public $id;
    public $user_id;
    public $url;
    public $file_name;
    public $content_type;
    public $file_size;
    public $hits;
    public $last_hit;
    public $created_at;
    public $updated_at;

    private $db;

    function __construct($mysql)
    {
        $this->db = $mysql;

        $this->id = 0;
        $this->user_id = 0;
        $this->url = '';
        $this->file_name = '';
        $this->content_type = '';
        $this->file_size = 0;
        $this->hits = 0;
        $this->last_hit = '1984-09-28 06:50:00';
        $this->created_at = gmdate('Y-m-d H:i:s', time());
        $this->updated_at = gmdate('Y-m-d H:i:s', time());
    }

    public function get($key)
    {
        $sql = sprintf('select * from link where id = \'%s\'', $key);
        $result = mysql_query($sql, $this->db);
        if (mysql_num_rows($result) == 1)
        {
            $row = mysql_fetch_assoc($result);

            $this->id = $row['id'];
            $this->user_id = $row['user_id'];
            $this->url = $row['url'];
            $this->file_name = $row['file_name'];
            $this->content_type = $row['content_type'];
            $this->file_size = $row['file_size'];
            $this->hits = $row['hits'];
            $this->last_hit = $row['last_hit'];
            $this->created_at = $row['created_at'];
            $this->updated_at = $row['updated_at'];

            return true;
        }
        return false;
    }

    public function save()
    {
        if ($this->id)
        {
            $this->updated_at = gmdate('Y-m-d H:i:s', time());

            $sql = sprintf('update link set
                id = \'%s\', user_id = \'%d\', url = \'%s\',
                file_name = \'%s\', content_type = \'%s\', file_size = \'%d\',
                hits = \'%d\', last_hit = \'%s\', created_at = \'%s\',
                updated_at = \'%s\' where id = \'%s\';',
                $this->id, $this->user_id, $this->url, $this->file_name,
                $this->content_type, $this->file_size, $this->hits,
                $this->last_hit, $this->created_at, $this->updated_at, $this->id);
        }
        else
        {
            $this->generate_unique_key();

            $sql = sprintf('insert link set
                id = \'%s\', user_id = \'%d\', url = \'%s\',
                file_name = \'%s\', content_type = \'%s\', file_size = \'%d\',
                hits = \'%d\', last_hit = \'%s\', created_at = \'%s\',
                updated_at = \'%s\';',
                $this->id, $this->user_id, $this->url, $this->file_name,
                $this->content_type, $this->file_size, $this->hits,
                $this->last_hit, $this->created_at, $this->updated_at);
        }

        $result = mysql_query($sql, $this->db);
    }

    private function generate_unique_key()
    {
        do {
            $key = substr(sha1(rand()), rand(0,32), 8);
            $sql = sprintf('select id from link where id = \'%s\'', $key);
            $result = mysql_query($sql, $this->db);

        } while (mysql_num_rows($result));

        $this->id = $key;
    }

    public function compute_hit()
    {
        $this->hits++;
        $this->last_hit = gmdate('Y-m-d H:i:s', time());
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

    public function set_url_info($url)
    {
        if ($this->url_is_valid($url))
        {
            $url_info = get_http_headers($url);

            $this->url = $url;
            $this->file_name = $url_info['filename'];
            $this->content_type = $url_info['Content-Type'];
            $this->file_size = $url_info['Content-Length'];
            if (!$this->file_size) { $this->file_size = 0; }

            return true;
        }
        return false;
    }

}

?>
