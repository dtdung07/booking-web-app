<?php
// File này thường chỉ cần kết nối để truy vấn nếu cần (nhưng ở đây không cần truy vấn Danh mục)
// include __DIR__ . "/connect.php"; 
?>

<div class="modal fade" id="updateUuDaiModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-edit me-2"></i> Sửa Ưu đãi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="updateUuDaiForm" method="POST">
            <div class="modal-body">
            <div class="row g-3">
                
                <div class="col-md-12">
                    <label class="form-label">Tên Ưu đãi</label>
                    <input type="text" class="form-control" placeholder="Ví dụ: Giảm 20% tổng hóa đơn" id="updateTenUuDai" name="TenUuDai" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Giá trị/Mức giảm</label>
                    <input type="text" class="form-control" placeholder="Ví dụ: 20% hoặc 50000" id="updateGiaTri" name="GiaTri" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">URL hình ảnh</label>
                    <input type="text" class="form-control" placeholder="Ví dụ: https://storage.quannhautudo.com/data/uudai.webp" id="updateAnhUuDai" name="AnhUuDai" required>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Ngày bắt đầu</label>
                    <input type="date" class="form-control" id="updateNgayBatDau" name="NgayBatDau" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Ngày kết thúc</label>
                    <input type="date" class="form-control" id="updateNgayKetThuc" name="NgayKetThuc" required>
                </div>

                <div class="col-12">
                    <label class="form-label">Mô tả chi tiết</label>
                    <textarea class="form-control" rows="2" placeholder="Chi tiết ưu đãi, điều kiện áp dụng..." id="updateMoTaUuDai" name="MoTaUuDai" required></textarea>
                </div>
            </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times"></i> Hủy</button>
                <button type="submit" class="btn" style="background-color: #FFA827; border-color: #FFA827; color: #333;"><i class="fas fa-sync-alt"></i> Cập nhật Ưu đãi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
/**
 * Hàm điền dữ liệu ưu đãi vào modal sửa
 * @param {string} mauudai - Mã ưu đãi
 * @param {string} ten_uudai - Tên ưu đãi
 * @param {string} gia_tri - Giá trị/Mức giảm
 * @param {string} ngay_bd - Ngày bắt đầu (định dạng YYYY-MM-DD)
 * @param {string} ngay_kt - Ngày kết thúc (định dạng YYYY-MM-DD)
 * @param {string} anh_uudai - URL hình ảnh
 * @param {string} mo_ta - Mô tả chi tiết
 */
function loadUuDaiData(mauudai, ten_uudai, gia_tri, ngay_bd, ngay_kt, anh_uudai, mo_ta) {
    // 1. Cập nhật action của form để gửi đến process-update với MaUuDai
    document.getElementById('updateUuDaiForm').action = '?page=admin&section=uudai&action=process-update&MaUuDai=' + mauudai;
    
    // 2. Điền dữ liệu vào form
    document.getElementById('updateTenUuDai').value = ten_uudai;
    document.getElementById('updateGiaTri').value = gia_tri;
    document.getElementById('updateNgayBatDau').value = ngay_bd;
    document.getElementById('updateNgayKetThuc').value = ngay_kt;
    document.getElementById('updateAnhUuDai').value = anh_uudai;
    document.getElementById('updateMoTaUuDai').value = mo_ta;
}
</script>