<?php

// Kiểm tra và include Database class
$databasePath = __DIR__ . '/../../config/database.php';
if (file_exists($databasePath)) {
    require_once $databasePath;
} else {
    throw new Exception("Database config not found at: " . $databasePath);
}

class User {
    private $conn;
    private $table_name = "nhanvien";

    public $MaNV;
    public $MaCoSo;
    public $TenDN;
    public $MatKhau;
    public $TenNhanVien;
    public $ChucVu;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Tìm user theo tên đăng nhập
    public function findByUsername($username) {
        $query = "SELECT * FROM " . $this->table_name . " 
                 WHERE TenDN = :username LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->MaNV = $row['MaNV'];
            $this->MaCoSo = $row['MaCoSo'];
            $this->TenDN = $row['TenDN'];
            $this->MatKhau = $row['MatKhau'];
            $this->TenNhanVien = $row['TenNhanVien'];
            $this->ChucVu = $row['ChucVu'];
            return true;
        }
        return false;
    }

    // Tìm user theo ID
    public function findById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE MaNV = :id LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->MaNV = $row['MaNV'];
            $this->MaCoSo = $row['MaCoSo'];
            $this->TenDN = $row['TenDN'];
            $this->TenNhanVien = $row['TenNhanVien'];
            $this->ChucVu = $row['ChucVu'];
            return true;
        }
        return false;
    }

    // Xác thực mật khẩu
    public function verifyPassword($password) {
        // Kiểm tra xem mật khẩu có được hash không
        if (password_verify($password, $this->MatKhau)) {
            return true;
        }
        // Nếu không phải hash, kiểm tra trực tiếp (cho database cũ)
        return $password === $this->MatKhau;
    }

    // Cập nhật mật khẩu đã hash
    public function updatePasswordHash($newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $query = "UPDATE " . $this->table_name . " SET MatKhau = :password WHERE MaNV = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":password", $hashedPassword);
        $stmt->bindParam(":id", $this->MaNV);
        
        return $stmt->execute();
    }

    // Lấy thông tin user dưới dạng array
    public function toArray() {
        return [
            'id' => $this->MaNV,
            'username' => $this->TenDN,
            'full_name' => $this->TenNhanVien,
            'role' => $this->ChucVu,
            'branch_id' => $this->MaCoSo
        ];
    }

    // Lấy tên cơ sở
    public function getBranchName() {
        $query = "SELECT TenCoSo FROM coso WHERE MaCoSo = :branch_id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":branch_id", $this->MaCoSo);
        $stmt->execute();
        
        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['TenCoSo'];
        }
        return '';
    }

    // Static method để kiểm tra login đơn giản
    public static function isUserLoggedIn() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['user']);
    }

    // Static method để lấy user hiện tại
    public static function getCurrentUser() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return $_SESSION['user'] ?? null;
    }
}
?>
?>
