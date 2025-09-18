<?php
include "connect.php";

if(isset($_GET['MaBan'])){
    $maban = $_GET['MaBan'];

    $sql = "DELETE FROM ban WHERE MaBan = '$maban';";
    mysqli_multi_query($conn, $sql);
    header("location: ?page=admin&section=table");
    exit(); // Dừng thực thi để tránh output thêm
}
else{
    echo "Vui lòng nhập đầy đủ thông tin";
}
?>