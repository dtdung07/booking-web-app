<?php
// File xử lý cập nhật giá và tình trạng món ăn tại cơ sở
include dirname(__DIR__,4) . "/config/connect.php";

if(!empty($_POST['MaCoSo']) && 
   !empty($_POST['MaMon']) && 
   !empty($_POST['Gia']) && 
   !empty($_POST['TinhTrang'])){
    
    $maCoSo = $_POST['MaCoSo'];
    $maMon = $_POST['MaMon'];
    $gia = $_POST['Gia'];
    $tinhTrang = $_POST['TinhTrang'];
    
    // Cập nhật giá và tình trạng
    $sql = "UPDATE menu_coso SET Gia = '$gia', TinhTrang = '$tinhTrang' WHERE MaCoSo = '$maCoSo' AND MaMon = '$maMon'";
    mysqli_query($conn, $sql);
    
    // Quay lại trang quản lý menu cơ sở (dùng JS để tránh lỗi header)
    echo "<script>window.location.href='?page=admin&section=menu_branch&branch_id=$maCoSo';</script>";
    exit();
} else {
    echo "Vui lòng nhập đầy đủ thông tin!";
}
?>
