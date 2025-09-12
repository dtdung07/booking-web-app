<?php
/**
 * Model cho bảng nhân viên
 * Cung cấp các phương thức để tương tác với bảng NHANVIEN
 */
class NhanVienModel
{
    private $conn;
    private $table_name = "nhanvien";

    // Các thuộc tính của nhân viên
    public $MaNV;
    public $MaCoSo;
    public $TenDN;
    public $MatKhau;
    public $TenNhanVien;
    public $ChucVu;

    // Hàm khởi tạo với kết nối database
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Lấy tất cả nhân viên
    public function getAll()
    {
        $query = "SELECT n.*, c.TenCoSo 
                  FROM " . $this->table_name . " n
                  LEFT JOIN coso c ON n.MaCoSo = c.MaCoSo
                  ORDER BY n.MaNV";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }

    /**
     * Lấy thông tin một nhân viên theo MaNV
     * int $id Mã nhân viên
     * trả về boolean True nếu thành công
     */
    public function getById($id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE MaNV = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
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

    /*
     * Tạo nhân viên mới
     * trả về boolean True nếu thành công
     */
    public function create()
    {
        // Kiểm tra tên đăng nhập đã tồn tại chưa
        if ($this->usernameExists()) {
            return false;
        }
        
        // Hash mật khẩu trước khi lưu
        $hashed_password = password_hash($this->MatKhau, PASSWORD_DEFAULT);
        
        $query = "INSERT INTO " . $this->table_name . "
                (MaCoSo, TenDN, MatKhau, TenNhanVien, ChucVu)
                VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($query);
        
        // Làm sạch dữ liệu
        $this->MaCoSo = htmlspecialchars(strip_tags($this->MaCoSo));
        $this->TenDN = htmlspecialchars(strip_tags($this->TenDN));
        $this->TenNhanVien = htmlspecialchars(strip_tags($this->TenNhanVien));
        $this->ChucVu = htmlspecialchars(strip_tags($this->ChucVu));
        
        // Ràng buộc các tham số
        $stmt->bindParam(1, $this->MaCoSo);
        $stmt->bindParam(2, $this->TenDN);
        $stmt->bindParam(3, $hashed_password);
        $stmt->bindParam(4, $this->TenNhanVien);
        $stmt->bindParam(5, $this->ChucVu);
        
        // Thực hiện truy vấn
        if ($stmt->execute()) {
            $this->MaNV = $this->conn->lastInsertId();
            return true;
        }
        
        return false;
    }

    /*
     * Cập nhật thông tin nhân viên
     * trả về boolean True nếu thành công
     */
    public function update()
    {
        // Kiểm tra nếu đổi tên đăng nhập
        $check_username = "SELECT TenDN FROM " . $this->table_name . " WHERE MaNV = ?";
        $stmt_check = $this->conn->prepare($check_username);
        $stmt_check->bindParam(1, $this->MaNV);
        $stmt_check->execute();
        $row = $stmt_check->fetch(PDO::FETCH_ASSOC);
        
        if ($row['TenDN'] != $this->TenDN && $this->usernameExists()) {
            return false;
        }
        
        // Xây dựng câu truy vấn update
        if (!empty($this->MatKhau)) {
            // Có cập nhật mật khẩu
            $hashed_password = password_hash($this->MatKhau, PASSWORD_DEFAULT);
            $query = "UPDATE " . $this->table_name . "
                     SET MaCoSo = ?, TenDN = ?, MatKhau = ?, TenNhanVien = ?, ChucVu = ?
                     WHERE MaNV = ?";
            
            $stmt = $this->conn->prepare($query);
            
            // Làm sạch dữ liệu
            $this->MaCoSo = htmlspecialchars(strip_tags($this->MaCoSo));
            $this->TenDN = htmlspecialchars(strip_tags($this->TenDN));
            $this->TenNhanVien = htmlspecialchars(strip_tags($this->TenNhanVien));
            $this->ChucVu = htmlspecialchars(strip_tags($this->ChucVu));
            $this->MaNV = htmlspecialchars(strip_tags($this->MaNV));
            
            // Ràng buộc các tham số
            $stmt->bindParam(1, $this->MaCoSo);
            $stmt->bindParam(2, $this->TenDN);
            $stmt->bindParam(3, $hashed_password);
            $stmt->bindParam(4, $this->TenNhanVien);
            $stmt->bindParam(5, $this->ChucVu);
            $stmt->bindParam(6, $this->MaNV);
        } else {
            // Không cập nhật mật khẩu
            $query = "UPDATE " . $this->table_name . "
                     SET MaCoSo = ?, TenDN = ?, TenNhanVien = ?, ChucVu = ?
                     WHERE MaNV = ?";
            
            $stmt = $this->conn->prepare($query);
            
            // Làm sạch dữ liệu
            $this->MaCoSo = htmlspecialchars(strip_tags($this->MaCoSo));
            $this->TenDN = htmlspecialchars(strip_tags($this->TenDN));
            $this->TenNhanVien = htmlspecialchars(strip_tags($this->TenNhanVien));
            $this->ChucVu = htmlspecialchars(strip_tags($this->ChucVu));
            $this->MaNV = htmlspecialchars(strip_tags($this->MaNV));
            
            // Ràng buộc các tham số
            $stmt->bindParam(1, $this->MaCoSo);
            $stmt->bindParam(2, $this->TenDN);
            $stmt->bindParam(3, $this->TenNhanVien);
            $stmt->bindParam(4, $this->ChucVu);
            $stmt->bindParam(5, $this->MaNV);
        }
        
        // Thực hiện truy vấn
        return $stmt->execute();
    }

    /**
     * Xóa nhân viên
     * trả về boolean True nếu thành công
     */
    public function delete()
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE MaNV = ?";
        
        $stmt = $this->conn->prepare($query);
        $this->MaNV = htmlspecialchars(strip_tags($this->MaNV));
        $stmt->bindParam(1, $this->MaNV);
        
        return $stmt->execute();
    }

    /**
     * Kiểm tra xem tên đăng nhập đã tồn tại chưa
     * trả về boolean True nếu tên đăng nhập đã tồn tại
     */
    private function usernameExists()
    {
        $query = "SELECT MaNV FROM " . $this->table_name . " WHERE TenDN = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->TenDN);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    /**
     * Kiểm tra thông tin đăng nhập
     *  string $username Tên đăng nhập
     *  string $password Mật khẩu
     * trả về boolean True nếu thông tin đăng nhập đúng
     */
    public function login($username, $password)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE TenDN = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $username);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row && password_verify($password, $row['MatKhau'])) {
                $this->MaNV = $row['MaNV'];
                $this->MaCoSo = $row['MaCoSo'];
                $this->TenDN = $row['TenDN'];
                $this->MatKhau = $row['MatKhau'];
                $this->TenNhanVien = $row['TenNhanVien'];
                $this->ChucVu = $row['ChucVu'];
                return [true, $this->toArray()];
        } else {
            error_log("DEBUG MODEL - User NOT found in database");
            return [false, null];
        }
    }

    /**
     * Lấy danh sách nhân viên theo cơ sở
     *  int $MaCoSo Mã cơ sở
     */
    public function getByCoSo($MaCoSo)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE MaCoSo = ? ORDER BY MaNV";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $MaCoSo);
        $stmt->execute();
        
        return $stmt;
    }

    /**
     * Chuyển đổi thuộc tính object thành array
     * @return array
     */
    public function toArray()
    {
        return [
            'MaNV' => $this->MaNV,
            'MaCoSo' => $this->MaCoSo,
            'TenDN' => $this->TenDN,
            'TenNhanVien' => $this->TenNhanVien,
            'ChucVu' => $this->ChucVu
        ];
    }
}
?>