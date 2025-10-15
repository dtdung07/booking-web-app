<?php
include dirname(__DIR__,4) . "/config/connect.php";

// Kiểm tra xem dữ liệu đã được gửi chưa
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Kiểm tra các trường bắt buộc
    if (!empty($_POST['MaCoSo']) && 
        !empty($_POST['TenNhanVien']) && 
        !empty($_POST['TenDN']) && 
        !empty($_POST['MatKhau']) && 
        !empty($_POST['ChucVu'])) {
        
        $maCoSo = $_POST['MaCoSo'];
        $tenNhanVien = $_POST['TenNhanVien'];
        $tenDN = $_POST['TenDN'];
        $matKhau = $_POST['MatKhau'];
        $chucVu = $_POST['ChucVu'];
        
        // Kiểm tra tên đăng nhập đã tồn tại chưa
        $checkSql = "SELECT COUNT(*) as count FROM `nhanvien` WHERE `TenDN` = '$tenDN'";
        $checkResult = mysqli_query($conn, $checkSql);
        $row = mysqli_fetch_assoc($checkResult);
        
        if ($row['count'] > 0) {
            echo "<script>alert('Tên đăng nhập đã tồn tại! Vui lòng chọn tên khác.'); window.history.back();</script>";
        } else {
            // Mã hóa mật khẩu
            $hashedPassword = password_hash($matKhau, PASSWORD_DEFAULT);
            
            // Thực hiện INSERT
            $sql = "INSERT INTO `nhanvien`(`MaCoSo`, `TenDN`, `MatKhau`, `TenNhanVien`, `ChucVu`) 
                    VALUES ('$maCoSo','$tenDN','$hashedPassword','$tenNhanVien','$chucVu')";
            
            if (mysqli_query($conn, $sql)) {
                echo "<script>alert('Thêm nhân viên thành công!'); window.location.href = '?page=admin&section=users';</script>";
            } else {
                echo "<script>alert('Có lỗi xảy ra: " . mysqli_error($conn) . "'); window.history.back();</script>";
            }
        }
    } else {
        echo "<script>alert('Vui lòng nhập đầy đủ thông tin!'); window.history.back();</script>";
    }
} else {
    // Nếu không phải POST request, chuyển hướng về trang chính (dùng JS)
    echo "<script>window.location.href='?page=admin&section=users';</script>";
    exit();
}
?>
