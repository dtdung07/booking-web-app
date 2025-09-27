<?php
include __DIR__ . "/connect.php";

// Kiểm tra xem dữ liệu đã được gửi chưa
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Kiểm tra các trường bắt buộc
    if (!empty($_POST['TenDM']) && isset($_GET['MaDM'])) {
        
        $maDM = $_GET['MaDM'];
        $tenDM = trim($_POST['TenDM']);
        $moTa = trim($_POST['MoTa']) ?? '';
        
        // Kiểm tra tên danh mục đã tồn tại chưa (trừ bản ghi hiện tại)
        $checkSql = "SELECT COUNT(*) as count FROM `danhmuc` WHERE `TenDM` = '$tenDM' AND `MaDM` != '$maDM'";
        $checkResult = mysqli_query($conn, $checkSql);
        $row = mysqli_fetch_assoc($checkResult);
        
        if ($row['count'] > 0) {
            echo "<script>alert('Tên danh mục đã tồn tại! Vui lòng chọn tên khác.'); window.history.back();</script>";
        } else {
            // Cập nhật danh mục
            $sql = "UPDATE `danhmuc` SET `TenDM`='$tenDM' WHERE `MaDM`='$maDM'";
            
            if (mysqli_query($conn, $sql)) {
                echo "<script>alert('Cập nhật danh mục thành công!'); window.location.href = '?page=admin&section=categories';</script>";
            } else {
                echo "<script>alert('Có lỗi xảy ra: " . mysqli_error($conn) . "'); window.history.back();</script>";
            }
        }
    } else {
        echo "<script>alert('Vui lòng nhập đầy đủ thông tin!'); window.history.back();</script>";
    }
} else {
    // Nếu không phải POST request, chuyển hướng về trang chính
    header("Location: ?page=admin&section=categories");
    exit();
}
?>
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
