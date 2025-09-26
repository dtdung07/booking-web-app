// 1. KHÔNG cần truy vấn danh mục món ăn.
// 2. KHÔNG cần include connect.php (vì nó đã được include trong index.php của thư mục uudai).
?>

<div class="modal fade" id="addUuDaiModal" tabindex="-1"> 
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"> <i class="fas fa-gift me-2"></i> Thêm Ưu đãi mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="?page=admin&section=uudai&action=process-create" method="POST">
            <div class="modal-body">
            <div class="row g-3">

                <div class="col-md-12">
                    <label class="form-label">Tên Ưu đãi</label>
                    <input type="text" class="form-control" placeholder="Ví dụ: Giảm 20% tổng hóa đơn, Tặng nước uống" id="TenUuDai" name="TenUuDai" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Giá trị/Mức giảm</label>
                    <input type="text" class="form-control" placeholder="Ví dụ: 20% hoặc 50000" id="GiaTri" name="GiaTri" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">URL hình ảnh</label>
                    <input type="text" class="form-control" placeholder="Ví dụ: https://storage.quannhautudo.com/data/uudai.webp" id="AnhUuDai" name="AnhUuDai" required>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Ngày bắt đầu</label>
                    <input type="date" class="form-control" id="NgayBatDau" name="NgayBatDau" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Ngày kết thúc</label>
                    <input type="date" class="form-control" id="NgayKetThuc" name="NgayKetThuc" required>
                </div>

                <div class="col-12">
                    <label class="form-label">Mô tả chi tiết</label>
                    <textarea class="form-control" rows="2" placeholder="Chi tiết ưu đãi, điều kiện áp dụng..." id="MoTaUuDai" name="MoTaUuDai" required></textarea>
                </div>
            </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times"></i> Hủy</button>
                <button type="submit" class="btn" style="background-color: #21A256; border-color: #21A256; color: white;"><i class="fas fa-save"></i> Thêm Ưu đãi</button>
                </div>
            </form>
        </div>
    </div>
</div>




