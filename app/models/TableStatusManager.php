<?php

class TableStatusManager {
    private $conn;

    public function __construct($connection = null) {
        if ($connection) {
            $this->conn = $connection;
        } else {
            $this->conn = self::getConnection();
        }
    }
    
    /**
     * Lấy kết nối database
     * @return mysqli
     */
    private static function getConnection() {
        $host = 'localhost';
        $user = 'root';
        $pass = '';
        $database = 'booking_restaurant';
        $port = '3306';

        $conn = mysqli_connect($host, $user, $pass, $database, $port);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        mysqli_set_charset($conn, "utf8");
        
        return $conn;
    }
    
    /**
     * Kiểm tra trạng thái bàn - đơn giản chỉ cần kiểm tra có trong dondatban_ban hay không
     * @param int $maBan Mã bàn
     * @return string 'trong' hoặc 'da_dat'
     */
    public static function kiemTraTrangThaiBan($maBan) { 
        $conn = self::getConnection();

        $sql = "SELECT COUNT(*) as so_don_dat
                FROM dondatban_ban dbb
                JOIN dondatban dd ON dbb.MaDon = dd.MaDon
                WHERE dbb.MaBan = ?
                AND dd.TrangThai IN ('cho_xac_nhan', 'da_xac_nhan')";

        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $maBan);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);

        return $row['so_don_dat'] > 0 ? 'da_dat' : 'trong';
    }

    /**
     * Lấy danh sách bàn theo cơ sở với trạng thái dựa vào dondatban_ban
     * @param int $maCoSo Mã cơ sở
     * @return array Danh sách bàn với trạng thái
     */
    public static function layBanTheoCoSo($maCoSo) {
        $conn = self::getConnection();

        $sql = "SELECT b.*,
                CASE
                    WHEN EXISTS (
                        SELECT 1
                        FROM dondatban_ban dbb
                        JOIN dondatban dd ON dbb.MaDon = dd.MaDon
                        WHERE dbb.MaBan = b.MaBan
                        AND dd.TrangThai IN ('cho_xac_nhan', 'da_xac_nhan')
                    ) THEN 'da_dat'
                    ELSE 'trong'
                END as TrangThai
                FROM ban b
                WHERE b.MaCoSo = ?
                ORDER BY b.MaBan";

        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $maCoSo);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $banList = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $banList[] = $row;
        }

        return $banList;
    }

    /**
     * Cập nhật trạng thái bàn - tạo hoặc xóa đơn đặt bàn admin để đánh dấu trạng thái
     * @param int $maBan Mã bàn
     * @param string $trangThai Trạng thái ('trong' hoặc 'da_dat')
     * @return bool
     */
    public static function capNhatTrangThaiBan($maBan, $trangThai) {
        $conn = self::getConnection();

        if ($trangThai == 'da_dat') {
            // Kiểm tra xem bàn đã có đơn đặt chưa
            $checkSql = "SELECT COUNT(*) as count FROM dondatban_ban dbb
                        JOIN dondatban dd ON dbb.MaDon = dd.MaDon
                        WHERE dbb.MaBan = ? AND dd.TrangThai IN ('cho_xac_nhan', 'da_xac_nhan')";
            $checkStmt = mysqli_prepare($conn, $checkSql);
            mysqli_stmt_bind_param($checkStmt, "i", $maBan);
            mysqli_stmt_execute($checkStmt);
            $checkResult = mysqli_stmt_get_result($checkStmt);
            $checkRow = mysqli_fetch_assoc($checkResult);
            
            if ($checkRow['count'] > 0) {
                return true; // Bàn đã được đặt rồi
            }

            // Lấy MaCoSo từ bàn
            $sqlGetCoSo = "SELECT MaCoSo FROM ban WHERE MaBan = ?";
            $stmtGetCoSo = mysqli_prepare($conn, $sqlGetCoSo);
            mysqli_stmt_bind_param($stmtGetCoSo, "i", $maBan);
            mysqli_stmt_execute($stmtGetCoSo);
            $result = mysqli_stmt_get_result($stmtGetCoSo);
            $ban = mysqli_fetch_assoc($result);
            $maCoSo = $ban['MaCoSo'];

            // Tạo hoặc lấy khách hàng admin
            $maKH = self::getOrCreateAdminCustomer($conn);

            // Tạo đơn đặt bàn admin để đánh dấu bàn đã đặt
            $sql = "INSERT INTO dondatban (MaKH, MaCoSo, SoLuongKH, ThoiGianBatDau, ThoiGianTao, TrangThai, GhiChu) 
                    VALUES (?, ?, 1, NOW(), NOW(), 'da_xac_nhan', 'Admin đánh dấu bàn đã đặt')";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ii", $maKH, $maCoSo);
            mysqli_stmt_execute($stmt);
            $maDon = mysqli_insert_id($conn);

            // Thêm bàn vào đơn đặt
            $sql2 = "INSERT INTO dondatban_ban (MaDon, MaBan) VALUES (?, ?)";
            $stmt2 = mysqli_prepare($conn, $sql2);
            mysqli_stmt_bind_param($stmt2, "ii", $maDon, $maBan);
            return mysqli_stmt_execute($stmt2);
        } else {
            // Xóa tất cả các đơn đặt bàn (cả admin và nhân viên) để đánh dấu bàn trống
            mysqli_begin_transaction($conn);
            
            try {
                // Lấy danh sách MaDon cần xóa
                $getMaDonSql = "SELECT DISTINCT dd.MaDon 
                               FROM dondatban dd
                               JOIN dondatban_ban dbb ON dd.MaDon = dbb.MaDon
                               WHERE dbb.MaBan = ? 
                               AND dd.TrangThai IN ('cho_xac_nhan', 'da_xac_nhan')";
                $getMaDonStmt = mysqli_prepare($conn, $getMaDonSql);
                mysqli_stmt_bind_param($getMaDonStmt, "i", $maBan);
                mysqli_stmt_execute($getMaDonStmt);
                $result = mysqli_stmt_get_result($getMaDonStmt);
                
                $maDonList = [];
                while ($row = mysqli_fetch_assoc($result)) {
                    $maDonList[] = $row['MaDon'];
                }
                
                if (!empty($maDonList)) {
                    $placeholders = str_repeat('?,', count($maDonList) - 1) . '?';
                   
                    // Xóa dondatban_ban
                    $deleteBanSql = "DELETE FROM dondatban_ban WHERE MaDon IN ($placeholders)";
                    $deleteBanStmt = mysqli_prepare($conn, $deleteBanSql);
                    mysqli_stmt_bind_param($deleteBanStmt, str_repeat('i', count($maDonList)), ...$maDonList);
                    mysqli_stmt_execute($deleteBanStmt);
                  
                }
                
                mysqli_commit($conn);
                return true;
            } catch (Exception $e) {
                mysqli_rollback($conn);
                return false;
            }
        }
    }


    /**
     * Lấy danh sách cơ sở
     * @return array
     */
    public static function layDanhSachCoSo() {
        $conn = self::getConnection();
        
        $sql = "SELECT * FROM coso ORDER BY TenCoSo";
        $result = mysqli_query($conn, $sql);
        
        $coSoList = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $coSoList[] = $row;
        }
        
        return $coSoList;
    }

    /**
     * Lấy thông tin cơ sở theo mã cơ sở
     * @param int $maCoSo Mã cơ sở
     * @return array|null Thông tin cơ sở
     */
    public static function layThongTinCoSo($maCoSo) {
        $conn = self::getConnection();
        
        $sql = "SELECT * FROM coso WHERE MaCoSo = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $maCoSo);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        return mysqli_fetch_assoc($result);
    }

    /**
     * Lấy thông tin cơ bản của bàn
     * @param int $maBan Mã bàn
     * @return array|null Thông tin bàn
     */
    public static function layThongTinBan($maBan) {
        $conn = self::getConnection();
        
        $sql = "SELECT b.*, c.TenCoSo 
                FROM ban b 
                JOIN coso c ON b.MaCoSo = c.MaCoSo 
                WHERE b.MaBan = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $maBan);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        return mysqli_fetch_assoc($result);
    }

    /**
     * Lấy thông tin chi tiết của bàn bao gồm trạng thái hiện tại
     * @param int $maBan Mã bàn
     * @return array|null Thông tin bàn chi tiết
     */
    public static function layThongTinBanChiTiet($maBan) {
        $conn = self::getConnection();
        
        $sql = "SELECT b.*, c.TenCoSo,
                CASE
                    WHEN EXISTS (
                        SELECT 1
                        FROM dondatban_ban dbb
                        JOIN dondatban dd ON dbb.MaDon = dd.MaDon
                        WHERE dbb.MaBan = b.MaBan
                        AND dd.TrangThai IN ('cho_xac_nhan', 'da_xac_nhan')
                    ) THEN 'da_dat'
                    ELSE 'trong'
                END as TrangThaiHienTai
                FROM ban b 
                JOIN coso c ON b.MaCoSo = c.MaCoSo 
                WHERE b.MaBan = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $maBan);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        return mysqli_fetch_assoc($result);
    }

    // =================================================================
    // CÁC HÀM ĐƯỢC GỘPVÀO TỪ TableModel.php
    // =================================================================

    /**
     * Lấy danh sách bàn trống của cơ sở khi tạo đơn đặt bàn (từ TableModel)
     * @param int $maCoSo Mã cơ sở
     * @param string $ngayDat Ngày đặt (Y-m-d)
     * @param string $gioDat Giờ đặt (H:i)
     * @param int $soNguoi Số người
     * @return array Danh sách bàn trống
     */
    public static function layBanTrong($maCoSo, $ngayDat, $gioDat, $soNguoi = 1) {
        $conn = self::getConnection();
        
        try {
            // Lấy tất cả bàn của cơ sở
            $sql = "SELECT MaBan, TenBan, SucChua FROM ban WHERE MaCoSo = ? AND SucChua >= ? ORDER BY TenBan";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ii", $maCoSo, $soNguoi);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            $allTables = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $allTables[] = $row;
            }
            
            if (empty($allTables)) {
                return [];
            }
            
            // Lấy danh sách bàn đã được đặt trong khoảng thời gian
            $bookedTables = self::layBanDaDat($maCoSo, $ngayDat, $gioDat);
            
            // Lọc bỏ các bàn đã được đặt
            $availableTables = [];
            foreach ($allTables as $table) {
                if (!in_array($table['MaBan'], $bookedTables)) {
                    $availableTables[] = $table;
                }
            }
            
            return $availableTables;
            
        } catch (Exception $e) {
            error_log("Error in layBanTrong: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy danh sách bàn đã được đặt trong khoảng thời gian (±2 giờ) (từ TableModel)
     * @param int $maCoSo Mã cơ sở
     * @param string $ngayDat Ngày đặt (Y-m-d)
     * @param string $gioDat Giờ đặt (H:i)
     * @return array Danh sách mã bàn đã đặt
     */
    public static function layBanDaDat($maCoSo, $ngayDat, $gioDat) {
        $conn = self::getConnection();
        
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
            
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "issss", $maCoSo, $ngayDat, $timeStart, $timeEnd, $gioDat);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            $bookedTables = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $bookedTables[] = $row['MaBan'];
            }
            
            return $bookedTables;
            
        } catch (Exception $e) {
            error_log("Error in layBanDaDat: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Kiểm tra xem bàn có sẵn vào thời điểm cụ thể không (từ TableModel)
     * @param int $maBan Mã bàn
     * @param string $ngayDat Ngày đặt (Y-m-d)
     * @param string $gioDat Giờ đặt (H:i)
     * @return bool True nếu bàn có sẵn
     */
    public static function kiemTraBanCoSan($maBan, $ngayDat, $gioDat) {
        try {
            // Lấy thông tin bàn để biết cơ sở
            $tableInfo = self::layThongTinBan($maBan);
            if (!$tableInfo) {
                return false;
            }
            
            $bookedTables = self::layBanDaDat($tableInfo['MaCoSo'], $ngayDat, $gioDat);
            
            return !in_array($maBan, $bookedTables);
            
        } catch (Exception $e) {
            error_log("Error in kiemTraBanCoSan: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lấy tất cả bàn của một cơ sở (từ TableModel) - tương tự layBanTheoCoSo nhưng không có trạng thái
     * @param int $maCoSo Mã cơ sở
     * @return array Danh sách bàn
     */
    public static function layTatCaBanTheoCoSo($maCoSo) {
        $conn = self::getConnection();
        
        try {
            $sql = "SELECT MaBan, TenBan, SucChua FROM ban WHERE MaCoSo = ? ORDER BY TenBan";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "i", $maCoSo);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            $tables = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $tables[] = $row;
            }
            
            return $tables;
            
        } catch (Exception $e) {
            error_log("Error in layTatCaBanTheoCoSo: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy danh sách bàn không có trong dondatban_ban (từ TableModel)
     * @param int $maCoSo Mã cơ sở
     * @return array Danh sách bàn chưa được đặt
     */
    public static function layBanChuaDuocDat($maCoSo) {
        $conn = self::getConnection();
        
        try {
            $sql = "SELECT b.MaBan, b.TenBan, b.SucChua 
                   FROM ban b 
                   LEFT JOIN dondatban_ban ddb ON b.MaBan = ddb.MaBan 
                   WHERE b.MaCoSo = ? AND ddb.MaBan IS NULL 
                   ORDER BY b.TenBan";
            
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "i", $maCoSo);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            $tables = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $tables[] = $row;
            }
            
            return $tables;
            
        } catch (Exception $e) {
            error_log("Error in layBanChuaDuocDat: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Hàm helper để tạo hoặc lấy khách hàng admin (cần thiết cho capNhatTrangThaiBan)
     */
    private static function getOrCreateAdminCustomer($conn) {
        // Kiểm tra xem đã có khách hàng admin chưa
        $sql = "SELECT MaKH FROM khachhang WHERE TenKH = 'Admin System' AND Email = 'admin@system.local'";
        $result = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            return $row['MaKH'];
        } else {
            // Tạo khách hàng admin mới
            $sql = "INSERT INTO khachhang (TenKH, Email, SDT) VALUES ('Admin System', 'admin@system.local', '0000000000')";
            mysqli_query($conn, $sql);
            return mysqli_insert_id($conn);
        }
    }

    // Xóa các đơn đặt bàn quá hạn thời gian
    public static function xoaDonDatBanQuaHan($maCoSo = null) {
        $conn = self::getConnection();
        
        try {
            mysqli_begin_transaction($conn);
            
            // Tính thời gian hết hạn (1 phút trước thời điểm hiện tại)
            $currentTime = date('Y-m-d H:i:s');
            
            // Tìm các đơn đặt bàn quá hạn (thêm 7 giờ cho UTC -> Vietnam time)
            $sql = "SELECT dd.MaDon, dd.ThoiGianBatDau, dd.MaCoSo, cs.TenCoSo
                    FROM dondatban dd
                    JOIN coso cs ON dd.MaCoSo = cs.MaCoSo
                    WHERE dd.TrangThai IN ('cho_xac_nhan', 'da_xac_nhan')
                    AND TIMESTAMPDIFF(SECOND, dd.ThoiGianBatDau, DATE_ADD(NOW(), INTERVAL 7 HOUR)) > 40 ";
            //  $sql = "SELECT dd.MaDon, dd.ThoiGianBatDau, dd.MaCoSo, cs.TenCoSo
            //     FROM dondatban dd
            //     JOIN coso cs ON dd.MaCoSo = cs.MaCoSo
            //     WHERE dd.TrangThai IN ('cho_xac_nhan', 'da_xac_nhan')
            //     AND NOW() > DATE_ADD(dd.ThoiGianBatDau, INTERVAL 30 SECOND)"; // Quá hạn 30 giây để dễ test
            
            // Nếu có mã cơ sở cụ thể
            if ($maCoSo !== null) {
                $sql .= " AND dd.MaCoSo = ?";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "i", $maCoSo);
            } else {
                $stmt = mysqli_prepare($conn, $sql);
            }
            
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            $expiredOrders = [];
            $maDonList = [];
            
            while ($row = mysqli_fetch_assoc($result)) {
                $expiredOrders[] = $row;
                $maDonList[] = $row['MaDon'];
            }
            
            if (empty($maDonList)) {
                mysqli_commit($conn);
                return [
                    'success' => true,
                    'deleted_count' => 0,
                    'message' => 'Không có đơn đặt bàn nào quá hạn'
                ];
            }
            
            // Tạo placeholders cho IN clause
            $placeholders = str_repeat('?,', count($maDonList) - 1) . '?';
            $paramTypes = str_repeat('i', count($maDonList));
            
           
            
            // Cập nhật trạng thái đơn đặt bàn thành 'da_huy' thay vì xóa hoàn toàn
            $updateDonSql = "UPDATE dondatban 
                            SET TrangThai = 'hoan_thanh', 
                                GhiChu = CONCAT(IFNULL(GhiChu, ''), ' [Tự động hủy do quá hạn]')
                            WHERE MaDon IN ($placeholders)";
            $updateDonStmt = mysqli_prepare($conn, $updateDonSql);
            mysqli_stmt_bind_param($updateDonStmt, $paramTypes, ...$maDonList);
            mysqli_stmt_execute($updateDonStmt);
            $updatedDon = mysqli_stmt_affected_rows($updateDonStmt);
            
            mysqli_commit($conn);
            
            // Log thông tin cleanup
            error_log("TableStatusManager: Đã cleanup " . count($maDonList) . " đơn đặt bàn quá hạn");
            foreach ($expiredOrders as $order) {
                error_log("- MaDon: {$order['MaDon']}, ThoiGianBatDau: {$order['ThoiGianBatDau']}, CoSo: {$order['TenCoSo']}");
            }
            
            return [
                'success' => true,
                'deleted_count' => count($maDonList),
                'expired_orders' => $expiredOrders,
                'details' => [
                    'chi_tiet_deleted' => $deletedChiTiet,
                    'ban_deleted' => $deletedBan,
                    'don_updated' => $updatedDon
                ],
                'message' => "Đã cleanup " . count($maDonList) . " đơn đặt bàn quá hạn"
            ];
            
        } catch (Exception $e) {
            mysqli_rollback($conn);
            error_log("Error in xoaDonDatBanQuaHan: " . $e->getMessage());
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'message' => 'Có lỗi xảy ra khi cleanup đơn đặt bàn quá hạn'
            ];
        }
    }

    
}
?>
