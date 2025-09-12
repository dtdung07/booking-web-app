<?php
include __DIR__ . "/connect.php";

// Kiểm tra xem dữ liệu đã được gửi chưa
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Kiểm tra các trường bắt buộc
    if (!empty($_POST['MaCoSo']) && 
        !empty($_POST['TenNhanVien']) && 
        !empty($_POST['TenDN']) && 
        !empty($_POST['ChucVu']) &&
        isset($_GET['MaNV'])) {
        
        $maNV = $_GET['MaNV'];
        $maCoSo = $_POST['MaCoSo'];
        $tenNhanVien = $_POST['TenNhanVien'];
        $tenDN = $_POST['TenDN'];
        $matKhau = $_POST['MatKhau'] ?? '';
        $chucVu = $_POST['ChucVu'];
        
        // Kiểm tra tên đăng nhập đã tồn tại chưa (trừ bản ghi hiện tại)
        $checkSql = "SELECT COUNT(*) as count FROM `nhanvien` WHERE `TenDN` = '$tenDN' AND `MaNV` != '$maNV'";
        $checkResult = mysqli_query($conn, $checkSql);
        $row = mysqli_fetch_assoc($checkResult);
        
        if ($row['count'] > 0) {
            echo "<script>alert('Tên đăng nhập đã tồn tại! Vui lòng chọn tên khác.'); window.history.back();</script>";
        } else {
            // Chuẩn bị câu SQL cập nhật
            if (!empty($matKhau)) {
                // Có thay đổi mật khẩu
                $hashedPassword = password_hash($matKhau, PASSWORD_DEFAULT);
                $sql = "UPDATE `nhanvien` SET 
                        `MaCoSo`='$maCoSo', 
                        `TenDN`='$tenDN', 
                        `MatKhau`='$hashedPassword', 
                        `TenNhanVien`='$tenNhanVien', 
                        `ChucVu`='$chucVu' 
                        WHERE `MaNV`='$maNV'";
            } else {
                // Không thay đổi mật khẩu
                $sql = "UPDATE `nhanvien` SET 
                        `MaCoSo`='$maCoSo', 
                        `TenDN`='$tenDN', 
                        `TenNhanVien`='$tenNhanVien', 
                        `ChucVu`='$chucVu' 
                        WHERE `MaNV`='$maNV'";
            }
            
            if (mysqli_query($conn, $sql)) {
                echo "<script>alert('Cập nhật thông tin nhân viên thành công!'); window.location.href = '?page=admin&section=users';</script>";
            } else {
                echo "<script>alert('Có lỗi xảy ra: " . mysqli_error($conn) . "'); window.history.back();</script>";
            }
        }
    } else {
        echo "<script>alert('Vui lòng nhập đầy đủ thông tin!'); window.history.back();</script>";
    }
} else {
    // Nếu không phải POST request, chuyển hướng về trang chính
    header("Location: ?page=admin&section=users");
    exit();
}
?>
