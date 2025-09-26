<?php

class TableStatusManager {
    
    /**
     * Lấy kết nối database
     * @return mysqli
     */
    private static function getConnection() {
        $host = 'db';
        $user = 'root';
        $pass = 'rootpassword';
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
                    
                    // Xóa chi tiết đơn đặt bàn
                    $deleteChiTietSql = "DELETE FROM chitietdondatban WHERE MaDon IN ($placeholders)";
                    $deleteChiTietStmt = mysqli_prepare($conn, $deleteChiTietSql);
                    mysqli_stmt_bind_param($deleteChiTietStmt, str_repeat('i', count($maDonList)), ...$maDonList);
                    mysqli_stmt_execute($deleteChiTietStmt);
                    
                    // Xóa dondatban_ban
                    $deleteBanSql = "DELETE FROM dondatban_ban WHERE MaDon IN ($placeholders)";
                    $deleteBanStmt = mysqli_prepare($conn, $deleteBanSql);
                    mysqli_stmt_bind_param($deleteBanStmt, str_repeat('i', count($maDonList)), ...$maDonList);
                    mysqli_stmt_execute($deleteBanStmt);
                    
                    // Xóa đơn đặt bàn
                    $deleteDonSql = "DELETE FROM dondatban WHERE MaDon IN ($placeholders)";
                    $deleteDonStmt = mysqli_prepare($conn, $deleteDonSql);
                    mysqli_stmt_bind_param($deleteDonStmt, str_repeat('i', count($maDonList)), ...$maDonList);
                    mysqli_stmt_execute($deleteDonStmt);
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
     * Tạo hoặc lấy khách hàng giả cho admin
     * @param mysqli $conn Kết nối database
     * @return int MaKH của khách hàng admin
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
}
?>
