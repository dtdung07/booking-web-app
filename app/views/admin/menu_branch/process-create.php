<?php
// File xử lý thêm món ăn vào menu cơ sở
include dirname(__DIR__,4) . "/config/connect.php";

if(!empty($_POST['MaCoSo']) && 
   !empty($_POST['MaMon']) && 
   !empty($_POST['Gia']) && 
   !empty($_POST['TinhTrang'])){
    
    $maCoSo = $_POST['MaCoSo'];
    $maMon = $_POST['MaMon'];
    $gia = $_POST['Gia'];
    $tinhTrang = $_POST['TinhTrang'];
    
    // Kiểm tra xem món đã có trong menu cơ sở này chưa
    $checkSql = "SELECT * FROM menu_coso WHERE MaCoSo = '$maCoSo' AND MaMon = '$maMon'";
    $checkResult = mysqli_query($conn, $checkSql);
    
    if(mysqli_num_rows($checkResult) == 0){
        // Chưa có thì thêm mới
        $sql = "INSERT INTO menu_coso (MaCoSo, MaMon, Gia, TinhTrang) VALUES ('$maCoSo', '$maMon', '$gia', '$tinhTrang')";
        mysqli_query($conn, $sql);
    }
    
    // Quay lại trang quản lý menu cơ sở (dùng JS để tránh lỗi header)
    echo "<script>window.location.href='?page=admin&section=menu_branch&branch_id=$maCoSo';</script>";
    exit();
} else {
    echo "Vui lòng nhập đầy đủ thông tin!";
}
?>
