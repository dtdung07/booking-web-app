<?php
// Lấy danh sách danh mục để hiển thị trong dropdown
$query = "SELECT * FROM coso ORDER BY TenCoSo";
$result = mysqli_query($conn, $query);
$list_coso = [];
while($row = mysqli_fetch_array($result)){
    $list_coso[] = $row;
}
?>

 <!-- Modal thêm bàn -->
 <div class="modal fade" id="addTableModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title"> <i class="fas fa-utensils me-2"></i> Thêm bàn mới</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="?page=admin&section=table&action=process-create" method="POST">
      <div class="modal-body">
      <div class="row g-3">

      <div class="col-md-12">
            <label for="MaCoSo">Cơ sở</label>
            <select class="form-control" id="MaCoSo" name="MaCoSo" required>
                <option value="">-- Chọn Cơ Sở --</option>
                <?php foreach ($list_coso as $coso): ?>
                    <option value="<?php echo $coso['MaCoSo']; ?>"><?php echo $coso['TenCoSo']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6">
          <label class="form-label">Tên bàn</label>
          <input type="text" class="form-control" placeholder="Ví dụ: T1-01 (Tầng 1 - Bàn 1)" id="TenBan" name="TenBan" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Sức chứa</label>
          <input type="text" class="form-control" placeholder="Ví dụ: 4, 6, 8" id="SucChua" name="SucChua" required>
        </div>
        
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times"></i> Hủy</button>
        <button type="submit" class="btn" style="background-color: #21A256; border-color: #21A256; color: white;"><i class="fas fa-save"></i> Thêm bàn</button>
        </div>
      </form>
    </div>
  </div>
</div>




                  