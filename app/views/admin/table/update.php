<!-- Modal sửa bàn -->
<div class="modal fade" id="updateTableModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Sửa bàn</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="updateTableForm" method="POST">
      <div class="modal-body">
      <div class="row g-3">
        <div class="col-md-12">
            <label for="MaCoSo">Cơ sở</label>
            <select class="form-control" id="updateMaCoSo" name="MaCoSo" required>
                <option value="">-- Chọn Cơ Sở --</option>
                <?php 
                // Lấy danh sách danh mục
                $sql_coso = "SELECT * FROM `coso`";
                $result_coso = mysqli_query($conn, $sql_coso);
                while($coso = mysqli_fetch_array($result_coso)): 
                ?>
                    <option value="<?php echo $coso['MaCoSo']; ?>"><?php echo $coso['TenCoSo']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-md-12">
          <label class="form-label">Tên bàn</label>
          <input type="text" class="form-control" placeholder="Ví dụ: T1-01 (Tầng 1 - Bàn 1)" id="updateTenBan" name="TenBan" required>
        </div>
        <div class="col-12">
          <label class="form-label">Sức chứa</label>
          <textarea class="form-control" rows="2" placeholder="Ví dụ: 4, 6, 8" id="updateSucChua" name="SucChua" required></textarea>
        </div>
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
        <button type="submit" class="btn" style="background-color: #FFA827; border-color: #FFA827; color: #333;">Cập nhật bàn</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function loadTableData(maban, coso, tenban, succhua) {
    // Cập nhật action của form
    document.getElementById('updateTableForm').action = '?page=admin&section=table&action=process-update&MaBan=' + maban;
    
    // Điền dữ liệu vào form
    document.getElementById('updateTenBan').value = tenban;
    document.getElementById('updateSucChua').value = succhua;
    document.getElementById('updateMaCoSo').value = coso;
}
</script>