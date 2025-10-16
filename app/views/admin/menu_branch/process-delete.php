<?php
// File xử lý xóa món ăn khỏi menu cơ sở
include dirname(__DIR__,4) . "/config/connect.php";

if(isset($_GET['MaCoSo']) && isset($_GET['MaMon'])){
    $maCoSo = $_GET['MaCoSo'];
    $maMon = $_GET['MaMon'];
    
    // Xóa món khỏi menu cơ sở (không xóa khỏi bảng monan)
    $sql = "DELETE FROM menu_coso WHERE MaCoSo = '$maCoSo' AND MaMon = '$maMon'";
    mysqli_query($conn, $sql);
    
    // Quay lại trang quản lý menu cơ sở (dùng JS để tránh lỗi header)
    echo "<script>window.location.href='?page=admin&section=menu_branch&branch_id=$maCoSo';</script>";
    exit();
} else {
    echo "Thiếu thông tin để xóa!";
}
?>
