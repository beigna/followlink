<?

class User
{
    public $id;
    public $email;
    private $password;
    public $type;
    public $last_login;
    public $created_at;
    public $updated_at;

    private $db;

    function __construct($mysql)
    {
        $this->db = $mysql;
    }

    public function get($email)
    {
        $sql = sprintf('select * from user where email = \'%s\'', $email);
        $result = mysql_query($sql, $this->db);
        if (mysql_num_rows($result) == 1)
        {
            $row = mysql_fetch_assoc($result);

            $this->id = $row['id'];
            $this->email = $row['email'];
            $this->password = $row['password'];
            $this->type = $row['type'];
            $this->last_login = $row['last_login'];
            $this->created_at = $row['created_at'];
            $this->updated_at = $row['updated_at'];

            return true;
        }
        return false;
    }

    public function authenticate($email, $password)
    {
        if (!$this->id) { $this->get($email); }

        if ($this->check_password($password))
        {
            return true;
        }
        return false;
    }

    private function encrypt_password($password)
    {
        # TODO: Sanitize password and return false if its fail

        $sid = substr(sha1(rand()), rand(0,35), 5);
        $encrypted_password = (sha1($sid.$password));

        return sprintf('%s:%s', $sid, $password);
    }

    private function check_password($password)
    {
        $sid = explode(':', $this->password);
        $sid = $sid[0];

        $encrypted_password = sprintf('%s:%s', $sid, sha1($sid.$password));

        if ($this->password == $encrypted_password)
        {
            return true;
        }
        return false;
    }
}

?>
