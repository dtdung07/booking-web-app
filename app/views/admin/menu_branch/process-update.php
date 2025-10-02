<?php
// File này được gọi khi form trong modal update.php được submit

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Kiểm tra và lấy dữ liệu từ form
    $maCoSo = isset($_POST['MaCoSo']) ? (int)$_POST['MaCoSo'] : 0;
    $maMon = isset($_POST['MaMon']) ? (int)$_POST['MaMon'] : 0;
    $gia = isset($_POST['Gia']) ? (float)$_POST['Gia'] : 0;
    $tinhTrang = isset($_POST['TinhTrang']) ? $_POST['TinhTrang'] : '';

    // 2. Validate dữ liệu
    if ($maCoSo > 0 && $maMon > 0 && $gia >= 0 && ($tinhTrang == 'con_hang' || $tinhTrang == 'het_hang')) {
        
        // 3. Thực hiện UPDATE
        $update_sql = "UPDATE menu_coso SET Gia = ?, TinhTrang = ? WHERE MaCoSo = ? AND MaMon = ?";
        $stmt = mysqli_prepare($conn, $update_sql);
        
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "dsii", $gia, $tinhTrang, $maCoSo, $maMon);
            
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
        error_log("Invalid data received for menu_coso update.");
    }

    // 4. Chuyển hướng người dùng trở lại trang quản lý của cơ sở đó
    header("Location: ?page=admin&section=menu_branch&branch_id=" . $maCoSo);
    exit();

} else {
    // Nếu không phải là POST request, chuyển hướng về trang chính
    header("Location: ?page=admin&section=menu_branch");
    exit();
}
?>

