<?php
include __DIR__ . "/connect.php";

if(
    !empty($_POST['TenMonAn']) &&
    !empty($_POST['AnhMonAn']) &&
    // !empty($_POST['MoTaMonAn']) &&
    !empty($_POST['MaDanhMuc'])){
        
        $mamon = $_GET['MaMon'];
        $tenmon = $_POST['TenMonAn'];
        $anhmmon = $_POST['AnhMonAn'];
        $mota = $_POST['MoTaMonAn'] ?? '';
        $madm = $_POST['MaDanhMuc'];

        $sql = "UPDATE `monan` SET `TenMon`='$tenmon', `HinhAnhURL`='$anhmmon', `MoTa`='$mota', `MaDM`='$madm' WHERE `MaMon`='$mamon'";
        
        if(mysqli_query($conn, $sql)){
            header("location: ?page=admin&section=menu");
            exit(); // Dừng thực thi để tránh output thêm
        } else {
            echo "Lỗi cập nhật: " . mysqli_error($conn);
        }
    }
    else{
        echo "Vui lòng nhập đầy đủ thông tin!";
    }
?>