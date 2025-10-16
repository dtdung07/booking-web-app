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
          <label class="form-label">Tiêu đề ưu đãi <span class="text-danger">*</span></label>
          <input type="text" class="form-control" placeholder="Ví dụ: Giảm giá 20% cho nhóm từ 4 người" id="TenMaUD" name="TenMaUD" maxlength="50" required>
          <small class="text-muted">Tên hiển thị của chương trình ưu đãi (tối đa 50 ký tự)</small>
        </div>
        <div class="col-md-12">
          <label class="form-label">Mô tả chi tiết <span class="text-danger">*</span></label>
          <textarea class="form-control" rows="3" placeholder="Mô tả chi tiết về ưu đãi và cách thức áp dụng..." id="MoTa" name="MoTa" required></textarea>
          <small class="text-muted">Thông tin đầy đủ về ưu đãi</small>
        </div>
        <div class="col-md-6">
          <label class="form-label">Giá trị giảm <span class="text-danger">*</span></label>
          <input type="number" class="form-control" placeholder="Ví dụ: 20" id="GiaTriGiam" name="GiaTriGiam" min="0" step="0.01" required>
          <small class="text-muted">Nhập số tiền hoặc phần trăm giảm</small>
        </div>
        <div class="col-md-6">
          <label class="form-label">Loại giảm giá <span class="text-danger">*</span></label>
          <select class="form-control" name="LoaiGiamGia" required>
            <option value="phantram">Phần trăm (%)</option>
            <option value="sotien">Số tiền (VNĐ)</option>
          </select>
          <small class="text-muted">Chọn đơn vị tính giảm giá</small>
        </div>
        <div class="col-md-12">
          <label class="form-label">Điều kiện áp dụng</label>
          <input type="text" class="form-control" placeholder="Ví dụ: Hóa đơn tối thiểu 500,000 VNĐ" id="DieuKien" name="DieuKien">
          <small class="text-muted">Điều kiện để khách hàng được áp dụng ưu đãi (không bắt buộc)</small>
        </div>
        <div class="col-md-6">
          <label class="form-label">Ngày bắt đầu <span class="text-danger">*</span></label>
          <input type="date" class="form-control" id="NgayBD" name="NgayBD" required>
          <small class="text-muted">Ngày bắt đầu có hiệu lực</small>
        </div>
        <div class="col-md-6">
          <label class="form-label">Ngày kết thúc <span class="text-danger">*</span></label>
          <input type="date" class="form-control" id="NgayKT" name="NgayKT" required>
          <small class="text-muted">Ngày hết hiệu lực</small>
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

<<<<<<< HEAD
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


=======
<script>
// Validation cho form thêm ưu đãi
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('#addUuDaiModal form');
    const giaTriGiamInput = document.getElementById('GiaTriGiam');
    const loaiGiamGiaSelect = document.querySelector('select[name="LoaiGiamGia"]');
    const ngayBDInput = document.getElementById('NgayBD');
    const ngayKTInput = document.getElementById('NgayKT');
    
    // Validate mức giảm giá theo loại
    function validateGiaTriGiam() {
        const giaTriGiam = parseFloat(giaTriGiamInput.value);
        const loaiGiamGia = loaiGiamGiaSelect.value;
        
        if (loaiGiamGia === 'phantram' && giaTriGiam > 100) {
            giaTriGiamInput.setCustomValidity('Giảm giá phần trăm không được vượt quá 100%');
        } else if (giaTriGiam <= 0) {
            giaTriGiamInput.setCustomValidity('Mức giảm giá phải lớn hơn 0');
        } else {
            giaTriGiamInput.setCustomValidity('');
        }
    }
    
    // Validate ngày
    function validateDates() {
        if (ngayBDInput.value && ngayKTInput.value) {
            const ngayBD = new Date(ngayBDInput.value);
            const ngayKT = new Date(ngayKTInput.value);
            
            if (ngayBD >= ngayKT) {
                ngayKTInput.setCustomValidity('Ngày kết thúc phải sau ngày bắt đầu');
            } else {
                ngayKTInput.setCustomValidity('');
            }
        }
    }
    
    // Thêm event listeners
    giaTriGiamInput.addEventListener('input', validateGiaTriGiam);
    loaiGiamGiaSelect.addEventListener('change', validateGiaTriGiam);
    ngayBDInput.addEventListener('change', validateDates);
    ngayKTInput.addEventListener('change', validateDates);
    
    // Validate khi submit
    form.addEventListener('submit', function(e) {
        validateGiaTriGiam();
        validateDates();
        
        if (!form.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }
    });
});
</script>
>>>>>>> b12307961b7a3ce595324f3c47e524fa0f4928b2

>>>>>>> 6f18b4ab1a54beb0dcafb5d866161a31ef913636
