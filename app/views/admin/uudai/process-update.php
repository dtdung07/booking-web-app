<?php
include __DIR__ . "/connect.php"; // Kết nối CSDL

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['MaUuDai'])) {
    $maUuDai = $_GET['MaUuDai'];
    
    // Lấy dữ liệu từ form
    $moTa = $_POST['MoTa'] ?? '';
    $giaTriGiam = $_POST['GiaTriGiam'] ?? 0;
    $loaiGiamGia = $_POST['LoaiGiamGia'] ?? 'phantram';
    $dieuKien = $_POST['DieuKien'] ?? null;
    $ngayBatDau = $_POST['NgayBD'] ?? '';
    $ngayKetThuc = $_POST['NgayKT'] ?? '';

    // Validate
    if (!empty($moTa) && !empty($ngayBatDau) && !empty($ngayKetThuc)) {
        $sql = "UPDATE uudai SET MoTa = ?, GiaTriGiam = ?, LoaiGiamGia = ?, DieuKien = ?, NgayBD = ?, NgayKT = ? WHERE MaUD = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdssssi", $moTa, $giaTriGiam, $loaiGiamGia, $dieuKien, $ngayBatDau, $ngayKetThuc, $maUuDai);
        
        if ($stmt->execute()) {
            header("Location: ?page=admin&section=uudai&status=update_success");
        } else {
            header("Location: ?page=admin&section=uudai&status=update_failed");
        }
        $stmt->close();
    } else {
        header("Location: ?page=admin&section=uudai&status=invalid_data");
    }
} else {
    header("Location: ?page=admin&section=uudai");
}
exit();
?>