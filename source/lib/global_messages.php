<?

class GlobalMessages
{
    private $messages;

    function __construct()
    {
        $this->messages = Array();
    }

    public function set($type, $text)
    {
        $this->messages[] = Array(
            'type' => $type,
            'text' => $text,
        );
    }

    public function get_all()
    {
        $tmp = $this->messages;
        $this->messages = Array();

        return $tmp;
    }
}

?>
