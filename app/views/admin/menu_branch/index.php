<?php
// Kết nối CSDL
include dirname(__DIR__, 4) . "/config/connect.php";

// Xử lý các action (Thêm, Sửa, Xóa)
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'process-create':
            include __DIR__ . "/process-create.php";
            break;
        case 'process-update':
            include __DIR__ . "/process-update.php";
            break;
        case 'delete':
            include __DIR__ . "/process-delete.php";
            break;
    }
}


// Lấy danh sách tất cả cơ sở để hiển thị trong dropdown
$sql_branches = "SELECT * FROM coso ORDER BY TenCoSo";
$result_branches = mysqli_query($conn, $sql_branches);
$branches = [];
while ($row = mysqli_fetch_assoc($result_branches)) {
    $branches[] = $row;
}

// Lấy mã cơ sở được chọn từ URL, nếu không có thì mặc định là null
$selected_branch_id = isset($_GET['branch_id']) ? (int)$_GET['branch_id'] : null;

?>

<div class="container-fluid">
    <div class="card shadow p-4">
        <h3 class="mb-4">Quản lý Menu theo Cơ sở</h3>

        <!-- Form chọn cơ sở -->
        <form action="" method="GET">
            <input type="hidden" name="page" value="admin">
            <input type="hidden" name="section" value="menu_branch">
            <div class="row g-3 align-items-end mb-4">
                <div class="col-md-4">
                    <label for="branch_id" class="form-label"><strong>Chọn một cơ sở để quản lý:</strong></label>
                    <select class="form-select" id="branch_id" name="branch_id" onchange="this.form.submit()">
                        <option value="">-- Vui lòng chọn cơ sở --</option>
                        <?php foreach ($branches as $branch) : ?>
                            <option value="<?php echo $branch['MaCoSo']; ?>" <?php echo ($selected_branch_id == $branch['MaCoSo']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($branch['TenCoSo']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </form>

        <hr>

        <?php
        // Nếu một cơ sở đã được chọn, thì hiển thị phần quản lý chi tiết
        if ($selected_branch_id) {
            include __DIR__ . "/view.php";
        } else {
            // Nếu chưa, hiển thị thông báo hướng dẫn
            echo '<div class="alert alert-info text-center" role="alert">';
            echo '  <i class="fas fa-info-circle fa-2x mb-3"></i><br>';
            echo '  Vui lòng chọn một cơ sở từ danh sách ở trên để xem và quản lý thực đơn chi tiết.';
            echo '</div>';
        }
        ?>
    </div>
</div>
