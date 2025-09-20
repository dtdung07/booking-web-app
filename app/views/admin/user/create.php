<?php
// Lấy danh sách cơ sở để hiển thị trong dropdown
$sql_coso = "SELECT * FROM coso ORDER BY TenCoSo";
$result_coso = mysqli_query($conn, $sql_coso);
?>

<!-- Modal thêm nhân viên -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="addUserModalLabel">
          <i class="fas fa-user-plus"></i> Thêm Nhân Viên Mới 
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="addUserForm" action="?page=admin&section=users&action=process-create" method="POST">
          <div class="form-group mb-3">
            <label for="MaCoSo">Cơ Sở</label>
            <select class="form-control" id="MaCoSo" name="MaCoSo" required>
              <option value="">-- Chọn Cơ Sở --</option>
              <?php while ($coso = mysqli_fetch_assoc($result_coso)): ?>
                <option value="<?php echo $coso['MaCoSo']; ?>"><?php echo $coso['TenCoSo']; ?></option>
              <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="TenNhanVien">Tên Nhân Viên</label>
                            <input type="text" class="form-control" id="TenNhanVien" name="TenNhanVien" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="TenDN">Tên Đăng Nhập</label>
                            <input type="text" class="form-control" id="TenDN" name="TenDN" required>
                            <small class="text-muted">Tên đăng nhập phải là duy nhất trong hệ thống.</small>
                        </div>

                        <div class="form-group mb-3">
                            <label for="MatKhau">Mật Khẩu</label>
                            <input type="password" class="form-control" id="MatKhau" name="MatKhau" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="XacNhanMatKhau">Xác Nhận Mật Khẩu</label>
                            <input type="password" class="form-control" id="XacNhanMatKhau" name="XacNhanMatKhau" required>
                        </div>

                        <div class="form-group mb-3">
                            <label>Chức Vụ</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="ChucVu" id="ChucVuNhanVien" value="nhan_vien" checked>
                                <label class="form-check-label" for="ChucVuNhanVien">
                                    Nhân Viên
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="ChucVu" id="ChucVuAdmin" value="admin">
                                <label class="form-check-label" for="ChucVuAdmin">
                                    Quản Trị Viên
                                </label>
                            </div>
                        </div>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="fas fa-times"></i> Hủy
        </button>
        <button type="submit" form="addUserForm" class="btn" style="background-color: #21A256; border-color: #21A256; color: white;">
          <i class="fas fa-save"></i> Thêm Nhân Viên
        </button>
      </div>
    </div>
  </div>
</div>

<script>
// Kiểm tra mật khẩu trùng khớp khi submit form
document.getElementById('addUserForm').addEventListener('submit', function(e) {
    const password = document.getElementById('MatKhau').value;
    const confirmPassword = document.getElementById('XacNhanMatKhau').value;
    
    if (password !== confirmPassword) {
        e.preventDefault();
        alert('Mật khẩu và xác nhận mật khẩu không khớp!');
    }
});
</script>
