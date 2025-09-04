<?php
// Kiểm tra quyền truy cập
// if (!isset($_SESSION['admin']) || $_SESSION['admin']['ChucVu'] !== 'admin') {
//     header('Location: /');
//     exit;
// }

// Lấy danh sách cơ sở để hiển thị trong dropdown
require_once '../../../../config/database.php';
$database = new Database();
$db = $database->getConnection();

$query = "SELECT * FROM coso ORDER BY TenCoSo";
$stmt = $db->prepare($query);
$stmt->execute();
$co_so_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4>Thêm Nhân Viên Mới</h4>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['error_message'])): ?>
                        <div class="alert alert-danger">
                            <?php 
                                echo $_SESSION['error_message']; 
                                unset($_SESSION['error_message']);
                            ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['success_message'])): ?>
                        <div class="alert alert-success">
                            <?php 
                                echo $_SESSION['success_message']; 
                                unset($_SESSION['success_message']);
                            ?>
                        </div>
                    <?php endif; ?>

                    <form action="/admin/user/store" method="POST">
                        <div class="form-group mb-3">
                            <label for="MaCoSo">Cơ Sở</label>
                            <select class="form-control" id="MaCoSo" name="MaCoSo" required>
                                <option value="">-- Chọn Cơ Sở --</option>
                                <?php foreach ($co_so_list as $coso): ?>
                                    <option value="<?php echo $coso['MaCoSo']; ?>"><?php echo $coso['TenCoSo']; ?></option>
                                <?php endforeach; ?>
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

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary">Thêm Nhân Viên</button>
                            <a href="/admin/users" class="btn btn-secondary ml-2">Hủy</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Kiểm tra mật khẩu trùng khớp khi submit form
document.querySelector('form').addEventListener('submit', function(e) {
    const password = document.getElementById('MatKhau').value;
    const confirmPassword = document.getElementById('XacNhanMatKhau').value;
    
    if (password !== confirmPassword) {
        e.preventDefault();
        alert('Mật khẩu và xác nhận mật khẩu không khớp!');
    }
});
</script>
