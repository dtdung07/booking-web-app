<?php
class Database {
    // Sử dụng biến môi trường hoặc giá trị mặc định cho local
    private $host;
    private $db_name;
    private $username;
    private $password;
    public $conn;
    
    public function __construct() {
        // Sử dụng hàm env() từ config.php để đọc file .env
        $this->host = env('DB_HOST', 'localhost');
        $this->db_name = env('DB_NAME', 'booking_restaurant');
        $this->username = env('DB_USER', 'root');
        $this->password = env('DB_PASS', '');
    }
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
