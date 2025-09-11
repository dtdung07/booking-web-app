<?php
include "connect.php";
?>
<?php
    if(isset($_GET['MaMon'])){
        $id = $_GET['MaMon'];

        $sql = "DELETE FROM `monan` WHERE MaMon = '$id'";
        mysqli_query($conn, $sql);
        header("location: index.php?action=view");
    }
    
    else{
        echo "Vui lòng nhập đầy đủ thông tin";
    }
?>