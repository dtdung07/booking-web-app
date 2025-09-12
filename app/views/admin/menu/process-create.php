<?php
include "connect.php";
?>

<?php
    if(!empty($_POST['MaDanhMuc'])&&
    !empty($_POST['TenMonAn'])&&
    !empty($_POST['AnhMonAn'])&&
    !empty($_POST['MoTaMonAn'])){
        $danhmuc = $_POST['MaDanhMuc'];
        $tenmon = $_POST['TenMonAn'];
        $anhmon = $_POST['AnhMonAn'];
        $mota = $_POST['MoTaMonAn'];

        $sql = "INSERT INTO `monan`(`MaDM`, `TenMon`, `MoTa`, `HinhAnhURL`) VALUES ('$danhmuc','$tenmon','$mota','$anhmon')";

        mysqli_query($conn, $sql);
        header("location: ?page=admin&section=menu");
        exit(); // Dừng thực thi để tránh output thêm
    }
    else{
        echo "Vui lòng nhập đầy đủ thông tin";
    }
    
    

?>