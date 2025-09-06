<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        
    </style>
</head>
<body>
    <div class="bang">
        <table style="border-collapse: collapse; margin: 20px;" border=1;>
            <caption><h1>Thông tin menu</h1></caption>
            <th>Tên nhân viên</th>
            <th>Tên đăng nhập</th>
            <th>Cơ sở</th>
            <th>Chức vụ</th>
            <th>Hành động</th>
    
            <?php
                $sql = "SELECT * FROM `nhanvien` WHERE 1";
    
                $result = mysqli_query($conn, $sql);
                while($row = mysqli_fetch_array($result)){
            ?>
            <tr>
                <td><?=$row['TenNhanVien']?></td>
                <td><?=$row['TenDN']?></td>
                <td><?=$row['MaCoSo']?></td>                
                <td>
                    <?php
                    if($row['ChucVu'] == 'admin'){
                        echo "Admin";
                    }
                    else if($row['ChucVu'] == 'nhan_vien'){
                        echo "Nhân viên";
                    }
                    ?>
                </td>
                <td style="padding: 15px;">
                <?php $path = dirname(__DIR__,2) . "\admin\index.php"; echo $path ?>
            
                <a style="background-color: blue;" href="<?=$path?>?action=update&id=<?=$row['id']?>">Cập nhật</a> |
                    <a style="background-color: red;"href="">Xoá</a>
                </td>
            </tr>
    
            <?php } ?>
        </table>
    </div>
</body>
</html>