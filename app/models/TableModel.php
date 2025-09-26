<?php

class TableModel {
    private $conn;
    
    public function __construct($connection = null) {
        if ($connection) {
            $this->conn = $connection;
        } else {
            // Sử dụng kết nối database mặc định
            require_once __DIR__ . '/../../config/database.php';
            $this->conn = $conn;
        }
    }
    
    /**
     * Lấy danh sách bàn trống theo cơ sở, ngày và giờ
     * 
     * @param int $maCoSo Mã cơ sở
     * @param string $ngayDat Ngày đặt (Y-m-d)
     * @param string $gioDat Giờ đặt (H:i)
     * @param int $soNguoi Số lượng người (để lọc theo sức chứa)
     * @return array Danh sách bàn trống
     */
    public function getAvailableTables($maCoSo, $ngayDat, $gioDat, $soNguoi = 1) {
        try {
            // Lấy tất cả bàn của cơ sở
            $sql = "SELECT MaBan, TenBan, SucChua FROM ban WHERE MaCoSo = ? AND SucChua >= ? ORDER BY TenBan";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ii", $maCoSo, $soNguoi);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $allTables = [];
            while ($row = $result->fetch_assoc()) {
                $allTables[] = $row;
            }
            
            if (empty($allTables)) {
                return [];
            }
            
            // Lấy danh sách bàn đã được đặt trong khoảng thời gian
            $bookedTables = $this->getBookedTables($maCoSo, $ngayDat, $gioDat);
            
            // Lọc bỏ các bàn đã được đặt
            $availableTables = [];
            foreach ($allTables as $table) {
                if (!in_array($table['MaBan'], $bookedTables)) {
                    $availableTables[] = $table;
                }
            }
            
            return $availableTables;
            
        } catch (Exception $e) {
            error_log("Error in getAvailableTables: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Lấy danh sách bàn đã được đặt trong khoảng thời gian
     * 
     * @param int $maCoSo Mã cơ sở
     * @param string $ngayDat Ngày đặt (Y-m-d)
     * @param string $gioDat Giờ đặt (H:i)
     * @return array Danh sách mã bàn đã được đặt
     */
    private function getBookedTables($maCoSo, $ngayDat, $gioDat) {
        try {
            // Tính toán khoảng thời gian xung đột (±2 giờ)
            $timeStart = date('H:i', strtotime($gioDat . ' -2 hours'));
            $timeEnd = date('H:i', strtotime($gioDat . ' +2 hours'));
            
            $sql = "SELECT DISTINCT ddb.MaBan 
                   FROM dondatban ddb 
                   INNER JOIN ban b ON ddb.MaBan = b.MaBan 
                   WHERE b.MaCoSo = ? 
                   AND DATE(ddb.ThoiGianDat) = ? 
                   AND (
                       (TIME(ddb.ThoiGianDat) BETWEEN ? AND ?) OR
                       (TIME(ddb.ThoiGianDat) = ?)
                   )
                   AND ddb.TrangThai NOT IN ('da_huy', 'hoan_thanh')";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("issss", $maCoSo, $ngayDat, $timeStart, $timeEnd, $gioDat);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $bookedTables = [];
            while ($row = $result->fetch_assoc()) {
                $bookedTables[] = $row['MaBan'];
            }
            
            return $bookedTables;
            
        } catch (Exception $e) {
            error_log("Error in getBookedTables: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Lấy thông tin chi tiết một bàn
     * 
     * @param int $maBan Mã bàn
     * @return array|null Thông tin bàn
     */
    public function getTableById($maBan) {
        try {
            $sql = "SELECT b.MaBan, b.TenBan, b.SucChua, b.MaCoSo, cs.TenCoSo 
                   FROM ban b 
                   LEFT JOIN coso cs ON b.MaCoSo = cs.MaCoSo 
                   WHERE b.MaBan = ?";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $maBan);
            $stmt->execute();
            $result = $stmt->get_result();
            
            return $result->fetch_assoc();
            
        } catch (Exception $e) {
            error_log("Error in getTableById: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Kiểm tra bàn có khả dụng tại thời điểm cụ thể không
     * 
     * @param int $maBan Mã bàn
     * @param string $ngayDat Ngày đặt (Y-m-d)
     * @param string $gioDat Giờ đặt (H:i)
     * @return bool True nếu bàn trống
     */
    public function isTableAvailable($maBan, $ngayDat, $gioDat) {
        try {
            // Lấy thông tin bàn để biết cơ sở
            $tableInfo = $this->getTableById($maBan);
            if (!$tableInfo) {
                return false;
            }
            
            $bookedTables = $this->getBookedTables($tableInfo['MaCoSo'], $ngayDat, $gioDat);
            
            return !in_array($maBan, $bookedTables);
            
        } catch (Exception $e) {
            error_log("Error in isTableAvailable: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Lấy tất cả bàn của một cơ sở (không cần ngày/giờ)
     * 
     * @param int $maCoSo Mã cơ sở
     * @return array Danh sách tất cả bàn
     */
    public function getAllTablesByBranch($maCoSo) {
        try {
            $sql = "SELECT MaBan, TenBan, SucChua FROM ban WHERE MaCoSo = ? ORDER BY TenBan";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $maCoSo);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $tables = [];
            while ($row = $result->fetch_assoc()) {
                $tables[] = $row;
            }
            
            return $tables;
            
        } catch (Exception $e) {
            error_log("Error in getAllTablesByBranch: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy danh sách bàn không có trong dondatban_ban (chưa từng được đặt)
     * 
     * @param int $maCoSo Mã cơ sở
     * @return array Danh sách bàn chưa từng được đặt
     */
    public function getUnbookedTablesByBranch($maCoSo) {
        try {
            $sql = "SELECT b.MaBan, b.TenBan, b.SucChua 
                   FROM ban b 
                   LEFT JOIN dondatban_ban ddb ON b.MaBan = ddb.MaBan 
                   WHERE b.MaCoSo = ? AND ddb.MaBan IS NULL 
                   ORDER BY b.TenBan";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $maCoSo);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $tables = [];
            while ($row = $result->fetch_assoc()) {
                $tables[] = $row;
            }
            
            return $tables;
            
        } catch (Exception $e) {
            error_log("Error in getUnbookedTablesByBranch: " . $e->getMessage());
            return [];
        }
    }
}