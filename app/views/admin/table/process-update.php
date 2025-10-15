<?php
include dirname(__DIR__,4) . "/config/connect.php";

if(
    !empty($_POST['MaCoSo']) &&
    !empty($_POST['TenBan']) &&
    !empty($_POST['SucChua'])){
        
        $maban = $_GET['MaBan'];
        $macoso = $_POST['MaCoSo'];
        $tenban = $_POST['TenBan'];
        $succhua = $_POST['SucChua'];

        $sql = "UPDATE `ban` SET `MaCoSo`='$macoso', `TenBan`='$tenban', `SucChua`='$succhua' WHERE `MaBan`='$maban'";
        
        if(mysqli_query($conn, $sql)){
            echo "<script>window.location.href='?page=admin&section=table';</script>";
            exit();
        } else {
            echo "Lỗi cập nhật: " . mysqli_error($conn);
        }
    }
    else{
        echo "Vui lòng nhập đầy đủ thông tin!";
    }
?>