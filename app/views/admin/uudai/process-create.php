<?php
<<<<<<< HEAD
include "connect.php";

// Kiểm tra xem dữ liệu đã được gửi chưa
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Kiểm tra trường bắt buộc
    if (!empty($_POST['TenDM'])) {
        
        $tenDM = trim($_POST['TenDM']);
        $moTa = trim($_POST['MoTa']) ?? '';
        
        // Kiểm tra tên danh mục đã tồn tại chưa
        $checkSql = "SELECT COUNT(*) as count FROM `danhmuc` WHERE `TenDM` = '$tenDM'";
        $checkResult = mysqli_query($conn, $checkSql);
        $row = mysqli_fetch_assoc($checkResult);
        
        if ($row['count'] > 0) {
            echo "<script>alert('Tên danh mục đã tồn tại! Vui lòng chọn tên khác.'); window.history.back();</script>";
        } else {
            // Thực hiện INSERT
            $sql = "INSERT INTO `danhmuc`(`TenDM`) VALUES ('$tenDM')";
            
            if (mysqli_query($conn, $sql)) {
                echo "<script>alert('Thêm danh mục thành công!'); window.location.href = '?page=admin&section=categories';</script>";
            } else {
                echo "<script>alert('Có lỗi xảy ra: " . mysqli_error($conn) . "'); window.history.back();</script>";
            }
        }
    } else {
        echo "<script>alert('Vui lòng nhập tên danh mục!'); window.history.back();</script>";
    }
} else {
    // Nếu không phải POST request, chuyển hướng về trang chính
    header("Location: ?page=admin&section=categories");
    exit();
}
?>
=======
include "connect.php"; // Kết nối CSDL

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy dữ liệu từ form
    $moTa = $_POST['MoTa'] ?? '';
    $giaTriGiam = $_POST['GiaTriGiam'] ?? 0;
    $loaiGiamGia = $_POST['LoaiGiamGia'] ?? 'phantram';
    $dieuKien = $_POST['DieuKien'] ?? null;
    $ngayBatDau = $_POST['NgayBD'] ?? '';
    $ngayKetThuc = $_POST['NgayKT'] ?? '';

    // Validate dữ liệu (ví dụ đơn giản)
    if (!empty($moTa) && !empty($ngayBatDau) && !empty($ngayKetThuc)) {
        // Chuẩn bị câu lệnh SQL để chèn dữ liệu
        $sql = "INSERT INTO uudai (MoTa, GiaTriGiam, LoaiGiamGia, DieuKien, NgayBD, NgayKT) VALUES (?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        
        // Gán giá trị và thực thi
        $stmt->bind_param("sdssss", $moTa, $giaTriGiam, $loaiGiamGia, $dieuKien, $ngayBatDau, $ngayKetThuc);
        
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
>>>>>>> 6f18b4ab1a54beb0dcafb5d866161a31ef913636
