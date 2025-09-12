<?php
include "connect.php";

// Kiểm tra xem có MaNV được truyền qua GET không
if (isset($_GET['MaNV'])) {
    $maNV = $_GET['MaNV'];
    
    // Kiểm tra xem có thể xóa nhân viên này không (không phải admin cuối cùng)
    $checkAdminSql = "SELECT COUNT(*) as admin_count FROM `nhanvien` WHERE `ChucVu` = 'admin'";
    $checkResult = mysqli_query($conn, $checkAdminSql);
    $adminCount = mysqli_fetch_assoc($checkResult)['admin_count'];
    
    // Kiểm tra nhân viên hiện tại có phải admin không
    $checkCurrentUserSql = "SELECT `ChucVu` FROM `nhanvien` WHERE `MaNV` = '$maNV'";
    $currentUserResult = mysqli_query($conn, $checkCurrentUserSql);
    $currentUser = mysqli_fetch_assoc($currentUserResult);
    
    if ($currentUser['ChucVu'] === 'admin' && $adminCount <= 1) {
        echo "<script>alert('Không thể xóa admin cuối cùng trong hệ thống!'); window.location.href = '?page=admin&section=users';</script>";
    } else {
        // Thực hiện xóa nhân viên
        $sql = "DELETE FROM `nhanvien` WHERE `MaNV` = '$maNV'";
        
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Xóa nhân viên thành công!'); window.location.href = '?page=admin&section=users';</script>";
        } else {
            echo "<script>alert('Có lỗi xảy ra: " . mysqli_error($conn) . "'); window.location.href = '?page=admin&section=users';</script>";
        }
    }
} else {
    echo "<script>alert('Không tìm thấy thông tin nhân viên cần xóa!'); window.location.href = '?page=admin&section=users';</script>";
}
?>
