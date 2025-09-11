<?php

class CoSo {
    private $conn;
    private $table_name = "coso";

    // Thuộc tính bảng cơ sở
    public $MaCoSo;
    public $TenCoSo;
    public $DiaChi;
    public $DienThoai;
   

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAddressSummary() {
    $query = "SELECT DiaChi AS address, COUNT(*) AS count 
              FROM " . $this->table_name . " 
              GROUP BY DiaChi";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt;
}


    /**
     * Lấy tất cả cơ sở
     */

    public function getAll() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE TenCoSo != '' ORDER BY MaCoSo ASC";
        // $query = "SELECT *, COUNT(*) AS SoLuongCoSo FROM " . $this->table_name . " GROUP BY DiaChi;";
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
            
            return true;
        }
        return false;
    }

    /**
     * Lấy cơ sở theo trạng thái
     */
    public function getByStatus($status) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE TrangThai = ? AND TenCoSo != '' ORDER BY MaCoSo ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $status);
        $stmt->execute();
        return $stmt;
    }

    /**
     * Đếm tổng số cơ sở
     */
    public function count() {
        $query = "SELECT COUNT(*) as total FROM `coso`";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    /**
     * Thêm cơ sở mới
     */
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                 SET TenCoSo=:TenCoSo, DiaChi=:DiaChi, DienThoai=:DienThoai, 
                     Mota=:Mota, ThoiGianHoatDong=:ThoiGianHoatDong, 
                     SucChua=:SucChua, DienTich=:DienTich, SoTang=:SoTang, 
                     TrangThai=:TrangThai";

        $stmt = $this->conn->prepare($query);

        // Làm sạch dữ liệu
        $this->TenCoSo = htmlspecialchars(strip_tags($this->TenCoSo));
        $this->DiaChi = htmlspecialchars(strip_tags($this->DiaChi));
        $this->DienThoai = htmlspecialchars(strip_tags($this->DienThoai));
        $this->Mota = htmlspecialchars(strip_tags($this->Mota));
       

        // Bind parameters
        $stmt->bindParam(":TenCoSo", $this->TenCoSo);
        $stmt->bindParam(":DiaChi", $this->DiaChi);
        $stmt->bindParam(":DienThoai", $this->DienThoai);
        $stmt->bindParam(":Mota", $this->Mota);
        $stmt->bindParam(":ThoiGianHoatDong", $this->ThoiGianHoatDong);
        $stmt->bindParam(":SucChua", $this->SucChua);
        $stmt->bindParam(":DienTich", $this->DienTich);
        $stmt->bindParam(":SoTang", $this->SoTang);
        $stmt->bindParam(":TrangThai", $this->TrangThai);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * Cập nhật cơ sở
     */
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                 SET TenCoSo=:TenCoSo, DiaChi=:DiaChi, DienThoai=:DienThoai, 
                     Mota=:Mota, ThoiGianHoatDong=:ThoiGianHoatDong, 
                     SucChua=:SucChua, DienTich=:DienTich, SoTang=:SoTang, 
                     TrangThai=:TrangThai 
                 WHERE MaCoSo=:MaCoSo";

        $stmt = $this->conn->prepare($query);

        // Làm sạch dữ liệu
        $this->TenCoSo = htmlspecialchars(strip_tags($this->TenCoSo));
        $this->DiaChi = htmlspecialchars(strip_tags($this->DiaChi));
        $this->DienThoai = htmlspecialchars(strip_tags($this->DienThoai));
        $this->Mota = htmlspecialchars(strip_tags($this->Mota));
        $this->ThoiGianHoatDong = htmlspecialchars(strip_tags($this->ThoiGianHoatDong));
        $this->TrangThai = htmlspecialchars(strip_tags($this->TrangThai));

        // Bind parameters
        $stmt->bindParam(":TenCoSo", $this->TenCoSo);
        $stmt->bindParam(":DiaChi", $this->DiaChi);
        $stmt->bindParam(":DienThoai", $this->DienThoai);
        $stmt->bindParam(":Mota", $this->Mota);
        $stmt->bindParam(":ThoiGianHoatDong", $this->ThoiGianHoatDong);
        $stmt->bindParam(":SucChua", $this->SucChua);
        $stmt->bindParam(":DienTich", $this->DienTich);
        $stmt->bindParam(":SoTang", $this->SoTang);
        $stmt->bindParam(":TrangThai", $this->TrangThai);
        $stmt->bindParam(":MaCoSo", $this->MaCoSo);

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
        $stmt->bindParam(1, $this->MaCoSo);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>
