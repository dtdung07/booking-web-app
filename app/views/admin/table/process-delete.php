<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include "connect.php";

if(isset($_GET['MaBan'])){
    $maban = $_GET['MaBan'];

    // Bắt đầu transaction để đảm bảo tính toàn vẹn dữ liệu
    mysqli_begin_transaction($conn);
    
    try {
        // 1. Xóa các bản ghi trong dondatban_ban trước (bảng con)
        $sql1 = "DELETE FROM dondatban_ban WHERE MaBan = ?";
        $stmt1 = mysqli_prepare($conn, $sql1);
        mysqli_stmt_bind_param($stmt1, "i", $maban);
        mysqli_stmt_execute($stmt1);
        
        // 2. Xóa các bản ghi trong chitietdondatban nếu có
        $sql2 = "DELETE c FROM chitietdondatban c 
                 JOIN dondatban_ban dbb ON c.MaDon = dbb.MaDon 
                 WHERE dbb.MaBan = ?";
        $stmt2 = mysqli_prepare($conn, $sql2);
        mysqli_stmt_bind_param($stmt2, "i", $maban);
        mysqli_stmt_execute($stmt2);
        
        // 3. Xóa các đơn đặt bàn liên quan
        $sql3 = "DELETE dd FROM dondatban dd 
                 JOIN dondatban_ban dbb ON dd.MaDon = dbb.MaDon 
                 WHERE dbb.MaBan = ?";
        $stmt3 = mysqli_prepare($conn, $sql3);
        mysqli_stmt_bind_param($stmt3, "i", $maban);
        mysqli_stmt_execute($stmt3);
        
        // 4. Cuối cùng mới xóa bàn (bảng cha)
        $sql4 = "DELETE FROM ban WHERE MaBan = ?";
        $stmt4 = mysqli_prepare($conn, $sql4);
        mysqli_stmt_bind_param($stmt4, "i", $maban);
        mysqli_stmt_execute($stmt4);
        
        // Commit transaction nếu tất cả thành công
        mysqli_commit($conn);
        
        // Redirect với thông báo thành công
        $_SESSION['success_message'] = 'Xóa bàn thành công!';
        header("location: ?page=admin&section=table");
        exit();
        
    } catch (Exception $e) {
        // Rollback nếu có lỗi
        mysqli_rollback($conn);
        $_SESSION['error_message'] = 'Có lỗi xảy ra khi xóa bàn: ' . $e->getMessage();
        header("location: ?page=admin&section=table");
        exit();
    }
}
else{
    $_SESSION['error_message'] = 'Vui lòng nhập đầy đủ thông tin';
    header("location: ?page=admin&section=table");
    exit();
}
?>