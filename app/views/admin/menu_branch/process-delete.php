<?php
// File này được gọi khi người dùng nhấn vào link Xóa

// 1. Kiểm tra và lấy dữ liệu từ URL
$maCoSo = isset($_GET['branch_id']) ? (int)$_GET['branch_id'] : 0;
$maMon = isset($_GET['MaMon']) ? (int)$_GET['MaMon'] : 0;

// 2. Validate dữ liệu
if ($maCoSo > 0 && $maMon > 0) {
    
    // 3. Thực hiện DELETE
    $delete_sql = "DELETE FROM menu_coso WHERE MaCoSo = ? AND MaMon = ?";
    $stmt = mysqli_prepare($conn, $delete_sql);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ii", $maCoSo, $maMon);
        
        if (!mysqli_stmt_execute($stmt)) {
            // Ghi lại lỗi nếu thực thi thất bại
            error_log("MySQLi execute error: " . mysqli_stmt_error($stmt));
        }
        mysqli_stmt_close($stmt);
    } else {
        // Ghi lại lỗi nếu chuẩn bị câu lệnh thất bại
        error_log("MySQLi prepare error: " . mysqli_error($conn));
    }
} else {
    // Ghi lại lỗi nếu dữ liệu không hợp lệ
    error_log("Invalid data received for menu_coso delete.");
}

// 4. Chuyển hướng người dùng trở lại trang quản lý của cơ sở đó
header("Location: ?page=admin&section=menu_branch&branch_id=" . $maCoSo);
exit();
?>

