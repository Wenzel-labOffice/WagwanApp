<?php
class Database
{
    private $host = "localhost";
    private $db_name = "practice_php";
    private $username = "root";
    private $password = "";
    public $conn;
    public function __construct()
    {
        $this->connect();
    }
    private function connect()
    {
        date_default_timezone_set('Africa/Lagos');
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }
}
class Crud extends Database
{
    public function __construct()
    {
        parent::__construct();
    }

    public function InsertData($table, $data = [])
    {
        if (empty($table) || empty($data)) {
            return false;
        }
        $columns = implode("`,`", array_keys($data));
        $postValues = array_map([$this->conn, 'real_escape_string'], array_values($data));
        $values = implode("','", $postValues);
        $sql = "INSERT INTO `$table` (`$columns`) VALUES('$values') ";
        $query = $this->conn->query($sql);
        return $query ? true : false;
    }
    public function FetchData($table, $where = "", $condition = "")
    {
        $sql = "SELECT * FROM `$table`";
        if (!empty($where)) {
            $sql .= " WHERE $where";
        }
        if (!empty($condition)) {
            $sql .= " $condition";
        }
        $query = $this->conn->query($sql);
        $records = [];
        if ($query && $query->num_rows > 0) {
            while ($row = $query->fetch_assoc()) {
                $records[] = $row;
            }
        }
        return $records;
    }
}
class Auth extends Database
{
    public function __construct()
    {
        parent::__construct();
    }
    public function loginUser($table, $email, $password)
    {
        if (empty($table) || empty($email) || empty($password)) {
            return false;
        }
        $email = $this->conn->real_escape_string($email);
        $sql = "SELECT * FROM `$table` WHERE `email`='$email'";
        $query = $this->conn->query($sql);
        if ($query && $query->num_rows > 0) {
            $user = $query->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                return $user;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}

function timeAgo($datetime, $full = false)
{
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $string = [
        'y' => 'year',
        'm' => 'month',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    ];
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);

    return $string ? implode(', ', $string) . ' ago' : 'just now';
}
