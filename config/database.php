<?php
class Database {
    private $host = "db";
    private $db_name = "booking_restaurant";
    private $username = "root";
    private $password = "rootpassword";
    public $conn;
    public function getConnection() {
    $this->conn = null;

    $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);

    if ($this->conn->connect_error) {
        die("Kết nối thất bại: " . $this->conn->connect_error);
    }
    $this->conn->set_charset("utf8");
    
    return $this->conn;
}

}
?>
