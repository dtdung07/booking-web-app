<?php
// File này thường chỉ cần kết nối để truy vấn nếu cần (nhưng ở đây không cần truy vấn Danh mục)
// include __DIR__ . "/connect.php"; 
?>

<!-- Modal sửa ưu đãi -->
<div class="modal fade" id="updateUuDaiModal<?=$row['MaUD']?>" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Sửa ưu đãi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="?page=admin&section=uudai&action=process-update&MaUuDai=<?=$row['MaUD']?>" method="POST">
      <div class="modal-body">
      <div class="row g-3">

        <div class="col-md-12">
            <label class="form-label">Tiêu đề ưu đãi</label>
            <input type="text" class="form-control" id="TieuDe" name="TieuDe" value="<?=$row['TieuDe']?>" required>
        </div>
        <div class="col-md-12">
          <label class="form-label">Mô tả</label>
          <textarea class="form-control" rows="3" id="MoTa" name="MoTa" required><?=$row['MoTa']?></textarea>
        </div>
        <div class="col-md-6">
          <label class="form-label">Giá trị giảm</label>
          <input type="number" class="form-control" id="GiaTriGiam" name="GiaTriGiam" value="<?=$row['GiaTriGiam']?>" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Loại giảm giá</label>
          <select class="form-control" name="LoaiGiamGia">
            <option value="phantram" <?=$row['LoaiGiamGia'] == 'phantram' ? 'selected' : ''?>>Phần trăm</option>
            <option value="sotien" <?=$row['LoaiGiamGia'] == 'sotien' ? 'selected' : ''?>>Số tiền</option>
          </select>
        </div>
        <div class="col-md-12">
          <label class="form-label">Điều kiện</label>
          <input type="text" class="form-control" id="DieuKien" name="DieuKien" value="<?=$row['DieuKien']?>">
        </div>
        <div class="col-md-6">
          <label class="form-label">Ngày bắt đầu</label>
          <input type="date" class="form-control" id="NgayBD" name="NgayBD" value="<?=$row['NgayBD']?>" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Ngày kết thúc</label>
          <input type="date" class="form-control" id="NgayKT" name="NgayKT" value="<?=$row['NgayKT']?>" required>
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