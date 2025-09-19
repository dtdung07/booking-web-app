<?php
include "connect.php";
?>

<?php
    if(!empty($_POST['MaCoSo'])&&
    !empty($_POST['TenBan'])&&
    !empty($_POST['SucChua'])){
        $maCoSo = $_POST['MaCoSo'];
        $tenban = $_POST['TenBan'];
        $succhua = $_POST['SucChua'];

        $sql = "INSERT INTO `ban`(`MaCoSo`, `TenBan`, `SucChua`) VALUES ('$maCoSo','$tenban','$succhua')";

        mysqli_query($conn, $sql);
        header("location: ?page=admin&section=table");
        exit(); // Dừng thực thi để tránh output thêm
    }
    else{
        echo "Vui lòng nhập đầy đủ thông tin";
    }
    
    

?>