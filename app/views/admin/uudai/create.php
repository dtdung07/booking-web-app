<?php
// 1. KHÔNG cần truy vấn danh mục món ăn.
// 2. KHÔNG cần include connect.php (vì nó đã được include trong index.php của thư mục uudai).
?>

<!-- Modal thêm ưu đãi -->
<div class="modal fade" id="addUuDaiModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title"> <i class="fas fa-tags me-2"></i> Thêm ưu đãi mới</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="?page=admin&section=uudai&action=process-create" method="POST">
      <div class="modal-body">
      <div class="row g-3">

        <div class="col-md-12">
          <label class="form-label">Tiêu đề ưu đãi</label>
          <input type="text" class="form-control" placeholder="Ví dụ: Giảm giá 20% cho nhóm 4 người" id="TieuDe" name="TieuDe" required>
        </div>
        <div class="col-12">
          <label class="form-label">Nội dung</label>
          <textarea class="form-control" rows="3" placeholder="Mô tả chi tiết về ưu đãi" id="NoiDung" name="NoiDung" required></textarea>
        </div>
        <div class="col-md-6">
          <label class="form-label">Phần trăm giảm</label>
          <input type="number" class="form-control" placeholder="Ví dụ: 20" id="PhanTramGiam" name="PhanTramGiam" min="0" max="100" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Mã áp dụng</label>
          <input type="text" class="form-control" placeholder="Ví dụ: TUDO20" id="MaApDung" name="MaApDung">
        </div>
        <div class="col-md-6">
          <label class="form-label">Ngày bắt đầu</label>
          <input type="date" class="form-control" id="NgayBatDau" name="NgayBatDau" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Ngày kết thúc</label>
          <input type="date" class="form-control" id="NgayKetThuc" name="NgayKetThuc" required>
        </div>
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times"></i> Hủy</button>
        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Thêm ưu đãi</button>
        </div>
      </form>
    </div>
  </div>
</div>




