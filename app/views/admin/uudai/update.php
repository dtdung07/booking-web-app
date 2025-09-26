<?php
// File này thường chỉ cần kết nối để truy vấn nếu cần (nhưng ở đây không cần truy vấn Danh mục)
// include __DIR__ . "/connect.php"; 
?>

<!-- Modal sửa ưu đãi -->
<div class="modal fade" id="updateUuDaiModal<?=$row['MaUuDai']?>" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Sửa ưu đãi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="?page=admin&section=uudai&action=process-update&MaUuDai=<?=$row['MaUuDai']?>" method="POST">
      <div class="modal-body">
      <div class="row g-3">

        <div class="col-md-12">
          <label class="form-label">Tiêu đề ưu đãi</label>
          <input type="text" class="form-control" id="TieuDe" name="TieuDe" value="<?=$row['TieuDe']?>" required>
        </div>
        <div class="col-12">
          <label class="form-label">Nội dung</label>
          <textarea class="form-control" rows="3" id="NoiDung" name="NoiDung" required><?=$row['NoiDung']?></textarea>
        </div>
        <div class="col-md-6">
          <label class="form-label">Phần trăm giảm</label>
          <input type="number" class="form-control" id="PhanTramGiam" name="PhanTramGiam" value="<?=$row['PhanTramGiam']?>" min="0" max="100" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Mã áp dụng</label>
          <input type="text" class="form-control" id="MaApDung" name="MaApDung" value="<?=$row['MaApDung']?>">
        </div>
        <div class="col-md-6">
          <label class="form-label">Ngày bắt đầu</label>
          <input type="date" class="form-control" id="NgayBatDau" name="NgayBatDau" value="<?=$row['NgayBatDau']?>" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Ngày kết thúc</label>
          <input type="date" class="form-control" id="NgayKetThuc" name="NgayKetThuc" value="<?=$row['NgayKetThuc']?>" required>
        </div>
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
        <button type="submit" class="btn btn-warning">Cập nhật ưu đãi</button>
        </div>
      </form>
    </div>
  </div>
</div>