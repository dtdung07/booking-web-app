<?php
include "connect.php";

if(isset($_GET['MaMon'])){
    $id = $_GET['MaMon'];

    $sql = "DELETE FROM menu_coso WHERE MaMon = '$id'; DELETE FROM `monan` WHERE MaMon = '$id'";
    mysqli_multi_query($conn, $sql);
    header("location: ?page=admin&section=menu");
    exit(); // Dừng thực thi để tránh output thêm
}
else{
    echo "Vui lòng nhập đầy đủ thông tin";
}
?>