<?php
include __DIR__ . "/connect.php"; // Kết nối CSDL

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['MaUuDai'])) {
    $maUuDai = $_GET['MaUuDai'];
    
    // Lấy dữ liệu từ form
    $tieuDe = $_POST['TieuDe'] ?? '';
    $noiDung = $_POST['NoiDung'] ?? '';
    $phanTramGiam = $_POST['PhanTramGiam'] ?? 0;
    $maApDung = $_POST['MaApDung'] ?? null;
    $ngayBatDau = $_POST['NgayBatDau'] ?? '';
    $ngayKetThuc = $_POST['NgayKetThuc'] ?? '';

    // Validate
    if (!empty($tieuDe) && !empty($noiDung) && !empty($ngayBatDau) && !empty($ngayKetThuc)) {
        $sql = "UPDATE uudai SET TieuDe = ?, NoiDung = ?, PhanTramGiam = ?, MaApDung = ?, NgayBatDau = ?, NgayKetThuc = ? WHERE MaUuDai = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssisssi", $tieuDe, $noiDung, $phanTramGiam, $maApDung, $ngayBatDau, $ngayKetThuc, $maUuDai);
        
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