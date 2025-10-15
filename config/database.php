<?php
class Database {
    private $host = "db";
    private $db_name = "booking_restaurant";
    private $username = "root";
    private $password = "rootpassword";
    public $conn;
   public function getConnection() {
    $this->conn = null;
    try {
        // Tạo kết nối MySQLi
        $this->conn = new mysqli(
            $this->host,
            $this->username,
            $this->password,
            $this->db_name
        );

        // Kiểm tra lỗi kết nối
        if ($this->conn->connect_error) {
            throw new Exception("Lỗi kết nối: " . $this->conn->connect_error);
        }

        // Đặt charset UTF-8
        if (!$this->conn->set_charset("utf8")) {
            throw new Exception("Không thể set charset UTF-8: " . $this->conn->error);
        }

    } catch (Exception $exception) {
        echo "Lỗi kết nối: " . $exception->getMessage();
    }

    return $this->conn;
}

}
?>
