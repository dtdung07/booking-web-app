<<<<<<< HEAD
<!-- Modal thêm danh mục -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="addCategoryModalLabel">
          <i class="fas fa-tag"></i> Thêm Danh Mục Mới
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="addCategoryForm" action="?page=admin&section=categories&action=process-create" method="POST">
          <div class="form-group mb-3">
            <label for="TenDM">Tên Danh Mục</label>
            <input type="text" class="form-control" id="TenDM" name="TenDM" required 
                   placeholder="Ví dụ: Hải sản, Đồ nướng, Lẩu...">
            <small class="text-muted">Tên danh mục không được trùng lặp.</small>
          </div>

          <div class="form-group mb-3">
            <label for="MoTa">Mô Tả (Tùy chọn)</label>
            <textarea class="form-control" id="MoTa" name="MoTa" rows="3" 
                      placeholder="Mô tả ngắn gọn về danh mục món ăn này..."></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="fas fa-times"></i> Hủy
        </button>
        <button type="submit" form="addCategoryForm" class="btn" style="background-color: #21A256; border-color: #21A256; color: white;">
          <i class="fas fa-save"></i> Thêm Danh Mục
        </button>
      </div>
=======
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
          <label class="form-label">Mô tả</label>
          <textarea class="form-control" rows="3" placeholder="Mô tả chi tiết về ưu đãi" id="MoTa" name="MoTa" required></textarea>
        </div>
        <div class="col-md-6">
          <label class="form-label">Giá trị giảm</label>
          <input type="number" class="form-control" placeholder="Ví dụ: 20" id="GiaTriGiam" name="GiaTriGiam" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Loại giảm giá</label>
          <select class="form-control" name="LoaiGiamGia">
            <option value="phantram">Phần trăm</option>
            <option value="sotien">Số tiền</option>
          </select>
        </div>
        <div class="col-md-12">
          <label class="form-label">Điều kiện</label>
          <input type="text" class="form-control" placeholder="Ví dụ: Áp dụng cho hóa đơn trên 500k" id="DieuKien" name="DieuKien">
        </div>
        <div class="col-md-6">
          <label class="form-label">Ngày bắt đầu</label>
          <input type="date" class="form-control" id="NgayBD" name="NgayBD" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Ngày kết thúc</label>
          <input type="date" class="form-control" id="NgayKT" name="NgayKT" required>
        </div>
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times"></i> Hủy</button>
        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Thêm ưu đãi</button>
        </div>
      </form>
>>>>>>> 6f18b4ab1a54beb0dcafb5d866161a31ef913636
    </div>
  </div>
</div>

<<<<<<< HEAD
<script>
// Validation form thêm danh mục
document.getElementById('addCategoryForm').addEventListener('submit', function(e) {
    const tenDM = document.getElementById('TenDM').value.trim();
    
    if (tenDM === '') {
        e.preventDefault();
        alert('Vui lòng nhập tên danh mục!');
    } else if (tenDM.length < 2) {
        e.preventDefault();
        alert('Tên danh mục phải có ít nhất 2 ký tự!');
    }
});
</script>
=======



>>>>>>> 6f18b4ab1a54beb0dcafb5d866161a31ef913636
