<?php
include __DIR__ . "/connect.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        
    </style>
</head>
<body>
<!-- Hiển thị danh sách món ăn -->
<div class="card shadow p-4">
    <h4 class="mb-3">Danh sách danh mục</h4>
    <table class="table table-bordered align-middle text-center" id="categoryTable">
      <thead class="table-dark">
        <tr>
          <th>#</th>
          <th>Tên món ăn</th>
          <th>Mô tả</th>
          <th>Ảnh</th>
          <th>Hành động</th>
        </tr>
      </thead>
      <?php
                $sql = "SELECT * FROM `monan` WHERE 1";
    
                $result = mysqli_query($conn, $sql);
                while($row = mysqli_fetch_array($result)){
                // Giới hạn mô tả
                $motaFull = $row['MoTa'] ?? '';
                $maxLen = 50;  // số ký tự tối đa
                if (mb_strlen($motaFull, 'UTF-8') > $maxLen) {
                    $motaShort = mb_substr($motaFull, 0, $maxLen, 'UTF-8') . '...';
                } else {
                    $motaShort = $motaFull;
                }
        ?>
      <tbody>
        <tr>
          <td><?=$row['MaMon']?></td>
          <td contenteditable="true"><?=$row['TenMon']?></td>
          <td contenteditable="true" ><?=$motaShort?></td>
          <td><img src="<?=$row['HinhAnhURL']?>" width="60" height="60" class="img-fluid rounded"></td>
          <td>
            <a class="btn btn-sm btn-danger" href="index.php?action=delete&MaMon=<?=$row['MaMon']?>">Xóa</a>
            <a class="btn btn-sm btn-success" data-bs-toggle="collapse" data-bs-target="#mon-1" href="?action=update&MaMon=<?=$row['MaMon']?>">Quản lý món</a>
          </td>
        </tr>
        <?php } ?>
        
      </tbody>
    </table>

    <!-- Phân trang -->
    <nav>
      <ul class="pagination justify-content-center">
        <li class="page-item disabled"><a class="page-link">Trước</a></li>
        <li class="page-item active"><a class="page-link" href="#">1</a></li>
        <li class="page-item"><a class="page-link" href="#">2</a></li>
        <li class="page-item"><a class="page-link" href="#">3</a></li>
        <li class="page-item"><a class="page-link" href="#">Sau</a></li>
      </ul>
    </nav>
  </div>  
</body>
</html>