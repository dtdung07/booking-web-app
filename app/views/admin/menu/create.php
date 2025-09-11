<?php
// Kiểm tra quyền truy cập
// if (!isset($_SESSION['admin']) || $_SESSION['admin']['ChucVu'] !== 'admin') {
//     header('Location: /');
//     exit;
// }

// Lấy danh sách cơ sở để hiển thị trong dropdown
require_once '../../../../config/database.php';
$database = new Database();
$db = $database->getConnection();

$query = "SELECT * FROM danhmuc ORDER BY TenDM";
$stmt = $db->prepare($query);
$stmt->execute();
$list_danh_muc = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Link css -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Form thêm món ăn -->
<div class="card shadow p-4 mb-5">
    <h4 class="mb-3">Thêm món ăn mới</h4>
    <form action="./process-create.php" method="POST">
      <div class="row g-3">

      <div class="col-md-12">
            <label for="MaCoSo">Danh mục</label>
            <select class="form-control" id="MaDanhMuc" name="MaDanhMuc" required>
                <option value="">-- Chọn Danh Mục Món --</option>
                <?php foreach ($list_danh_muc as $danhmuc): ?>
                    <option value="<?php echo $danhmuc['MaDM']; ?>"><?php echo $danhmuc['TenDM']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6">
          <label class="form-label">Tên món ăn</label>
          <input type="text" class="form-control" placeholder="Ví dụ: Hải sản, Nướng, Lẩu" id="TenMonAn" name="TenMonAn" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">URL hình ảnh</label>
          <input type="text" class="form-control" placeholder="Ví dụ: https://storage.quannhautudo.com/data/thumb_400/Data/images/product/2025/06/202506271649402242.webp" id="AnhMonAn" name="AnhMonAn" required>
        </div>
        <!-- <div class="col-md-6">
          <label class="form-label">Ảnh món ăn</label>
          <input type="file" class="form-control" id="AnhMonAn" name="AnhMonAn">
        </div> -->
        <div class="col-12">
          <label class="form-label">Mô tả</label>
          <textarea class="form-control" rows="2" placeholder="Mô tả ngắn gọn" id="MoTaMonAn" name="MoTaMonAn" required></textarea>
        </div>
      </div>
      <button type="submit" class="btn btn-success mt-3">Thêm danh mục</button>
    </form>
  </div>



