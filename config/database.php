<?php
class Database {
    private $host = "db";
    private $db_name = "booking_restaurant";
    private $username = "root";
    private $password = "rootpassword";
    public $conn;
   public function getConnection() {
    $this->conn = null;

    // Tạo kết nối MySQLi
    $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);

    // Kiểm tra lỗi kết nối
    if ($this->conn->connect_error) {
        die("Kết nối thất bại: " . $this->conn->connect_error);
    }

    // Thiết lập charset UTF-8
    $this->conn->set_charset("utf8");

    return $this->conn;
}

}
?>
