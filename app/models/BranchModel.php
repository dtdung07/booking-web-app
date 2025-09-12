<?php

class BranchModel {
    private $conn;
    private $table_name = "coso";

    // Thuộc tính của Model, tương ứng với các cột trong bảng
    public $MaCoSo;
    public $TenCoSo;
    public $DiaChi;
    public $AnhUrl;
    public $DienThoai;

    public function __construct($db) {
        $this->conn = $db;
    }


    /**
     * Lấy tất cả cơ sở
     */
    public function getAll() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE TenCoSo != '' ORDER BY MaCoSo ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    /**
     * Lấy cơ sở theo địa chỉ (tìm kiếm quận trong địa chỉ)
     */
    public function getByAddress($address) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE DiaChi LIKE ? AND TenCoSo != '' ORDER BY MaCoSo ASC";
        $stmt = $this->conn->prepare($query);
        $searchTerm = '%' . $address . '%';
        $stmt->bindParam(1, $searchTerm);
        $stmt->execute();
        return $stmt;
    }
    
    /**
     * Lấy tóm tắt địa chỉ
     */
    public function getAddressSummary() {
        $query = "SELECT DiaChi AS address, COUNT(*) AS count 
                  FROM " . $this->table_name . " 
                  GROUP BY DiaChi";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    /**
     * Lấy cơ sở theo ID
     */
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE MaCoSo = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->MaCoSo = $row['MaCoSo'];
            $this->TenCoSo = $row['TenCoSo'];
            $this->DiaChi = $row['DiaChi'];
            $this->DienThoai = $row['DienThoai'];
            $this->AnhUrl = $row['AnhUrl'];
            return true;
        }
        return false;
    }


    /**
     * Thêm cơ sở mới
     */
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                 SET TenCoSo=:TenCoSo, DiaChi=:DiaChi, DienThoai=:DienThoai, AnhUrl=:AnhUrl";

        $stmt = $this->conn->prepare($query);

        // Làm sạch dữ liệu đầu vào
        $this->TenCoSo = htmlspecialchars(strip_tags($this->TenCoSo));
        $this->DiaChi = htmlspecialchars(strip_tags($this->DiaChi));
        $this->DienThoai = htmlspecialchars(strip_tags($this->DienThoai));
        $this->AnhUrl = htmlspecialchars(strip_tags($this->AnhUrl));

        // Bind parameters
        $stmt->bindParam(":TenCoSo", $this->TenCoSo);
        $stmt->bindParam(":DiaChi", $this->DiaChi);
        $stmt->bindParam(":DienThoai", $this->DienThoai);
        $stmt->bindParam(":AnhUrl", $this->AnhUrl);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * Cập nhật cơ sở
     */
    public function update() {
        // SỬA LỖI: Bỏ dấu phẩy thừa trước AnhUrl và thêm MaCoSo vào bindParam
        $query = "UPDATE " . $this->table_name . " 
                 SET TenCoSo=:TenCoSo, DiaChi=:DiaChi, DienThoai=:DienThoai, AnhUrl=:AnhUrl
                 WHERE MaCoSo=:MaCoSo";

        $stmt = $this->conn->prepare($query);

        // Làm sạch dữ liệu
        $this->MaCoSo = htmlspecialchars(strip_tags($this->MaCoSo));
        $this->TenCoSo = htmlspecialchars(strip_tags($this->TenCoSo));
        $this->DiaChi = htmlspecialchars(strip_tags($this->DiaChi));
        $this->DienThoai = htmlspecialchars(strip_tags($this->DienThoai));
        $this->AnhUrl = htmlspecialchars(strip_tags($this->AnhUrl));

        // Bind parameters
        $stmt->bindParam(":MaCoSo", $this->MaCoSo);
        $stmt->bindParam(":TenCoSo", $this->TenCoSo);
        $stmt->bindParam(":DiaChi", $this->DiaChi);
        $stmt->bindParam(":DienThoai", $this->DienThoai);
        $stmt->bindParam(":AnhUrl", $this->AnhUrl);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * Xóa cơ sở
     */
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE MaCoSo = ?";
        $stmt = $this->conn->prepare($query);
        
        $this->MaCoSo = htmlspecialchars(strip_tags($this->MaCoSo));
        
        $stmt->bindParam(1, $this->MaCoSo);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>