<?php
include "connect.php"; // Kết nối CSDL

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy dữ liệu từ form
    $tieuDe = $_POST['TieuDe'] ?? '';
    $moTa = $_POST['MoTa'] ?? '';
    $giaTriGiam = $_POST['GiaTriGiam'] ?? 0;
    $loaiGiamGia = $_POST['LoaiGiamGia'] ?? 'phantram';
    $dieuKien = $_POST['DieuKien'] ?? null;
    $ngayBatDau = $_POST['NgayBD'] ?? '';
    $ngayKetThuc = $_POST['NgayKT'] ?? '';

    // Validate dữ liệu (ví dụ đơn giản)
    if (!empty($tieuDe) && !empty($moTa) && !empty($ngayBatDau) && !empty($ngayKetThuc)) {
        // Chuẩn bị câu lệnh SQL để chèn dữ liệu
        $sql = "INSERT INTO uudai (TieuDe, MoTa, GiaTriGiam, LoaiGiamGia, DieuKien, NgayBD, NgayKT) VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        
        // Gán giá trị và thực thi
        $stmt->bind_param("ssdssss", $tieuDe, $moTa, $giaTriGiam, $loaiGiamGia, $dieuKien, $ngayBatDau, $ngayKetThuc);
        
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