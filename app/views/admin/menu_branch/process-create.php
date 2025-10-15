<?php
// File này được gọi khi form trong modal create.php được submit
// Đã có kết nối CSDL từ file index.php của trang admin

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Kiểm tra và lấy dữ liệu từ form
    $maCoSo = isset($_POST['MaCoSo']) ? (int)$_POST['MaCoSo'] : 0;
    $maMon = isset($_POST['MaMon']) ? (int)$_POST['MaMon'] : 0;
    $gia = isset($_POST['Gia']) ? (float)$_POST['Gia'] : 0;
    $tinhTrang = isset($_POST['TinhTrang']) ? $_POST['TinhTrang'] : '';

    // 2. Validate dữ liệu
    if ($maCoSo > 0 && $maMon > 0 && $gia >= 0 && ($tinhTrang == 'con_hang' || $tinhTrang == 'het_hang')) {
        
        // 3. Kiểm tra xem món ăn đã tồn tại ở cơ sở này chưa (để tránh lỗi race condition)
        $check_sql = "SELECT * FROM menu_coso WHERE MaCoSo = ? AND MaMon = ?";
        $check_stmt = mysqli_prepare($conn, $check_sql);
        mysqli_stmt_bind_param($check_stmt, "ii", $maCoSo, $maMon);
        mysqli_stmt_execute($check_stmt);
        $check_result = mysqli_stmt_get_result($check_stmt);

        if (mysqli_num_rows($check_result) == 0) {
            // 4. Nếu chưa tồn tại, thực hiện INSERT
            $insert_sql = "INSERT INTO menu_coso (MaCoSo, MaMon, Gia, TinhTrang) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $insert_sql);
            
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "iids", $maCoSo, $maMon, $gia, $tinhTrang);
                
                if (mysqli_stmt_execute($stmt)) {
                    // Thành công, có thể thêm thông báo session ở đây nếu muốn
                    // $_SESSION['success_message'] = "Thêm món ăn vào cơ sở thành công!";
                } else {
                    // Lỗi khi thực thi
                    // $_SESSION['error_message'] = "Lỗi: Không thể thêm món ăn.";
                    error_log("MySQLi execute error: " . mysqli_stmt_error($stmt));
                }
                mysqli_stmt_close($stmt);
            } else {
                // Lỗi khi chuẩn bị câu lệnh
                error_log("MySQLi prepare error: " . mysqli_error($conn));
            }
        } else {
            // Món đã tồn tại
            // $_SESSION['error_message'] = "Lỗi: Món ăn này đã có trong thực đơn của cơ sở.";
        }

    } else {
        // Dữ liệu không hợp lệ
        // $_SESSION['error_message'] = "Lỗi: Dữ liệu gửi lên không hợp lệ.";
    }

    // 5. Chuyển hướng người dùng trở lại trang quản lý của cơ sở đó
    // Sử dụng header để chuyển hướng, đảm bảo không có output nào trước nó
    header("Location: ?page=admin&section=menu_branch&branch_id=" . $maCoSo);
    exit();

} else {
    // Nếu không phải là POST request, chuyển hướng về trang chính
    header("Location: ?page=admin&section=menu_branch");
    exit();
}
?>

