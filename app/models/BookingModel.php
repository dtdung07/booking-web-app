<?php

class BookingModel
{
    private $conn;
    private $table = 'dondatban';

    // Booking properties
    public $MaDon;
    public $MaKH;
    public $MaCoSo;
    public $ThoiGianBatDau;
    public $ThoiGianKetThuc;
    public $SoLuongKhach;
    public $TrangThai;
    public $GhiChu;
    public $ThoiGianTao;
    public $MaNV_XacNhan;
    public $TongTien;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Lấy booking theo ID
    public function getById($maDon)
    {
        try {
            $query = "SELECT * FROM " . $this->table . " WHERE MaDon = :maDon";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':maDon', $maDon);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $this->MaDon = $row['MaDon'];
                $this->MaKH = $row['MaKH'];
                $this->MaCoSo = $row['MaCoSo'];
                $this->ThoiGianBatDau = $row['ThoiGianBatDau'];
                $this->ThoiGianKetThuc = $row['ThoiGianKetThuc'];
                $this->SoLuongKhach = $row['SoLuongKhach'];
                $this->TrangThai = $row['TrangThai'];
                $this->GhiChu = $row['GhiChu'];
                $this->ThoiGianTao = $row['ThoiGianTao'];
                $this->MaNV_XacNhan = $row['MaNV_XacNhan'];
                $this->TongTien = $row['TongTien'];
                return true;
            }
            return false;
        } catch (Exception $e) {
            error_log("Error in BookingModel::getById: " . $e->getMessage());
            return false;
        }
    }

    // Đếm tổng số đơn đặt bàn theo cơ sở
    public function countBookingsByBranch($maCoSo)
    {
        try {
            $query = "SELECT COUNT(*) as total FROM " . $this->table . " WHERE MaCoSo = :maCoSo";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':maCoSo', $maCoSo);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (Exception $e) {
            error_log("Error in BookingModel::countBookingsByBranch: " . $e->getMessage());
            return 0;
        }
    }

    // Đếm đơn đặt bàn hôm nay theo cơ sở
    public function countTodayBookingsByBranch($maCoSo)
    {
        try {
            $today = date('Y-m-d');
            $query = "SELECT COUNT(*) as total FROM " . $this->table . " 
                     WHERE MaCoSo = :maCoSo AND DATE(ThoiGianTao) = :today";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':maCoSo', $maCoSo);
            $stmt->bindParam(':today', $today);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (Exception $e) {
            error_log("Error in BookingModel::countTodayBookingsByBranch: " . $e->getMessage());
            return 0;
        }
    }

    // Đếm đơn chờ xác nhận theo cơ sở
    public function countPendingBookingsByBranch($maCoSo)
    {
        try {
            $query = "SELECT COUNT(*) as total FROM " . $this->table . " 
                     WHERE MaCoSo = :maCoSo AND TrangThai = 'cho_xac_nhan'";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':maCoSo', $maCoSo);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (Exception $e) {
            error_log("Error in BookingModel::countPendingBookingsByBranch: " . $e->getMessage());
            return 0;
        }
    }

    // Đếm đơn đã xác nhận theo cơ sở
    public function countConfirmedBookingsByBranch($maCoSo)
    {
        try {
            $query = "SELECT COUNT(*) as total FROM " . $this->table . " 
                     WHERE MaCoSo = :maCoSo AND TrangThai = 'da_xac_nhan'";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':maCoSo', $maCoSo);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (Exception $e) {
            error_log("Error in BookingModel::countConfirmedBookingsByBranch: " . $e->getMessage());
            return 0;
        }
    }

    // Lấy danh sách đơn đặt bàn theo cơ sở với filter
    public function getBookingsByBranch($maCoSo, $limit = 10, $offset = 0, $statusFilter = 'all', $timeFilter = 'hom_nay', $searchKeyword = '')
    {
        try {
            $whereConditions = ["d.MaCoSo = :maCoSo"];
            $params = [':maCoSo' => $maCoSo];

            // Filter theo trạng thái
            if ($statusFilter !== 'all') {
                $whereConditions[] = "d.TrangThai = :statusFilter";
                $params[':statusFilter'] = $statusFilter;
            }

            // Filter theo thời gian
            if ($timeFilter === 'hom_nay') {
                $whereConditions[] = "DATE(d.ThoiGianBatDau) = CURDATE()";
            } elseif ($timeFilter === 'dat_truoc') {
                $whereConditions[] = "DATE(d.ThoiGianBatDau) > CURDATE()";
            }

            // Search keyword
            if (!empty($searchKeyword)) {
                $whereConditions[] = "(kh.TenKH LIKE :search OR kh.SDT LIKE :search OR d.MaDon LIKE :search)";
                $params[':search'] = "%$searchKeyword%";
            }

            $whereClause = implode(" AND ", $whereConditions);

            $query = "SELECT d.*, kh.TenKH, kh.SDT, kh.Email as EmailKH,
                             GROUP_CONCAT(CONCAT(b.TenBan, ' (', b.SucChua, ' người)') SEPARATOR ', ') as DanhSachBan
                      FROM " . $this->table . " d 
                      LEFT JOIN khachhang kh ON d.MaKH = kh.MaKH
                      LEFT JOIN dondatban_ban db ON d.MaDon = db.MaDon
                      LEFT JOIN ban b ON db.MaBan = b.MaBan
                      WHERE $whereClause
                      GROUP BY d.MaDon
                      ORDER BY d.ThoiGianTao DESC 
                      LIMIT :limit OFFSET :offset";

            $stmt = $this->conn->prepare($query);
            
            // Bind parameters
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            error_log("Error in BookingModel::getBookingsByBranch: " . $e->getMessage());
            return [];
        }
    }

    // Đếm số đơn đặt bàn theo cơ sở với filter
    public function countBookingsByBranchWithFilter($maCoSo, $statusFilter = 'all', $timeFilter = 'hom_nay', $searchKeyword = '')
    {
        try {
            $whereConditions = ["d.MaCoSo = :maCoSo"];
            $params = [':maCoSo' => $maCoSo];

            // Filter theo trạng thái
            if ($statusFilter !== 'all') {
                $whereConditions[] = "d.TrangThai = :statusFilter";
                $params[':statusFilter'] = $statusFilter;
            }

            // Filter theo thời gian
            if ($timeFilter === 'hom_nay') {
                $whereConditions[] = "DATE(d.ThoiGianBatDau) = CURDATE()";
            } elseif ($timeFilter === 'dat_truoc') {
                $whereConditions[] = "DATE(d.ThoiGianBatDau) > CURDATE()";
            }

            // Search keyword
            if (!empty($searchKeyword)) {
                $whereConditions[] = "(kh.TenKH LIKE :search OR kh.SDT LIKE :search OR d.MaDon LIKE :search)";
                $params[':search'] = "%$searchKeyword%";
            }

            $whereClause = implode(" AND ", $whereConditions);

            $query = "SELECT COUNT(*) as total FROM " . $this->table . " d 
                     LEFT JOIN khachhang kh ON d.MaKH = kh.MaKH 
                     WHERE $whereClause";

            $stmt = $this->conn->prepare($query);
            
            // Bind parameters
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }

            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];

        } catch (Exception $e) {
            error_log("Error in BookingModel::countBookingsByBranchWithFilter: " . $e->getMessage());
            return 0;
        }
    }

    // Cập nhật trạng thái đơn đặt bàn
    public function updateStatus($maDon, $status, $maNVXacNhan = null, $ghiChu = null)
    {
        try {
            $query = "UPDATE " . $this->table . " SET TrangThai = :status";
            $params = [':status' => $status, ':maDon' => $maDon];

            if ($maNVXacNhan !== null) {
                $query .= ", MaNV_XacNhan = :maNVXacNhan";
                $params[':maNVXacNhan'] = $maNVXacNhan;
            }

            if ($ghiChu !== null) {
                $query .= ", GhiChu = CONCAT(IFNULL(GhiChu, ''), :ghiChu)";
                $params[':ghiChu'] = "\n[" . date('d/m/Y H:i') . "] " . $ghiChu;
            }

            $query .= " WHERE MaDon = :maDon";

            $stmt = $this->conn->prepare($query);
            
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }

            return $stmt->execute();

        } catch (Exception $e) {
            error_log("Error in BookingModel::updateStatus: " . $e->getMessage());
            return false;
        }
    }

    // Lấy chi tiết đơn đặt bàn với thông tin khách hàng và bàn
    public function getBookingDetail($maDon, $maCoSo = null)
    {
        try {
            $query = "SELECT d.*, kh.TenKH, kh.SDT, kh.Email as EmailKH,
                             GROUP_CONCAT(CONCAT(b.TenBan, ' (', b.SucChua, ' người)') SEPARATOR ', ') as DanhSachBan,
                             nv.TenNhanVien as NhanVienXacNhan,
                             cs.TenCoSo, cs.DiaChi as DiaChiCoSo
                      FROM " . $this->table . " d 
                      LEFT JOIN khachhang kh ON d.MaKH = kh.MaKH
                      LEFT JOIN dondatban_ban db ON d.MaDon = db.MaDon
                      LEFT JOIN ban b ON db.MaBan = b.MaBan
                      LEFT JOIN nhanvien nv ON d.MaNV_XacNhan = nv.MaNV
                      LEFT JOIN coso cs ON d.MaCoSo = cs.MaCoSo
                      WHERE d.MaDon = :maDon";
            
            $params = [':maDon' => $maDon];

            if ($maCoSo !== null) {
                $query .= " AND d.MaCoSo = :maCoSo";
                $params[':maCoSo'] = $maCoSo;
            }

            $query .= " GROUP BY d.MaDon";

            $stmt = $this->conn->prepare($query);
            
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }

            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            error_log("Error in BookingModel::getBookingDetail: " . $e->getMessage());
            return false;
        }
    }

    // Lấy món ăn đã đặt cho một đơn
    public function getBookingMenuItems($maDon)
    {
        try {
            $query = "SELECT m.TenMon, m.DonGia, dm.SoLuong, (m.DonGia * dm.SoLuong) as ThanhTien
                     FROM dondatban_mon dm
                     JOIN monan m ON dm.MaMon = m.MaMon
                     WHERE dm.MaDon = :maDon
                     ORDER BY m.TenMon";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':maDon', $maDon);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            error_log("Error in BookingModel::getBookingMenuItems: " . $e->getMessage());
            return [];
        }
    }

    // Chuyển object thành array
    public function toArray()
    {
        return [
            'MaDon' => $this->MaDon,
            'MaKH' => $this->MaKH,
            'MaCoSo' => $this->MaCoSo,
            'ThoiGianBatDau' => $this->ThoiGianBatDau,
            'ThoiGianKetThuc' => $this->ThoiGianKetThuc,
            'SoLuongKhach' => $this->SoLuongKhach,
            'TrangThai' => $this->TrangThai,
            'GhiChu' => $this->GhiChu,
            'ThoiGianTao' => $this->ThoiGianTao,
            'MaNV_XacNhan' => $this->MaNV_XacNhan,
            'TongTien' => $this->TongTien
        ];
    }
}

?>