<?php
include "connect.php"; // Kết nối CSDL

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy dữ liệu từ form
    $tieuDe = $_POST['TieuDe'] ?? '';
    $noiDung = $_POST['NoiDung'] ?? '';
    $phanTramGiam = $_POST['PhanTramGiam'] ?? 0;
    $maApDung = $_POST['MaApDung'] ?? null;
    $ngayBatDau = $_POST['NgayBatDau'] ?? '';
    $ngayKetThuc = $_POST['NgayKetThuc'] ?? '';

    // Validate dữ liệu (ví dụ đơn giản)
    if (!empty($tieuDe) && !empty($noiDung) && !empty($ngayBatDau) && !empty($ngayKetThuc)) {
        // Chuẩn bị câu lệnh SQL để chèn dữ liệu
        $sql = "INSERT INTO uudai (TieuDe, NoiDung, PhanTramGiam, MaApDung, NgayBatDau, NgayKetThuc) VALUES (?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        
        // Gán giá trị và thực thi
        $stmt->bind_param("ssisss", $tieuDe, $noiDung, $phanTramGiam, $maApDung, $ngayBatDau, $ngayKetThuc);
        
        if ($stmt->execute()) {
            // Thành công, chuyển hướng về trang danh sách
            header("Location: ?page=admin&section=uudai&status=add_success");
        } else {
            // Lỗi, chuyển hướng với thông báo lỗi
            header("Location: ?page=admin&section=uudai&status=add_failed");
        }
        $stmt->close();
    } else {
        // Dữ liệu không hợp lệ
        header("Location: ?page=admin&section=uudai&status=invalid_data");
    }
} else {
    // Không phải là POST request
    header("Location: ?page=admin&section=uudai");
}
exit();
?>