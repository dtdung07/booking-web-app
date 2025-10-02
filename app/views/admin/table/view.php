<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include __DIR__ . "/connect.php";

// Include modal sửa bàn
include __DIR__ . "/update.php";

// Lấy danh sách cơ sở
$sqlCoSo = "SELECT * FROM coso ORDER BY TenCoSo";
$resultCoSo = mysqli_query($conn, $sqlCoSo);
$listCoSo = [];
while($row = mysqli_fetch_array($resultCoSo)){
    $listCoSo[] = $row;
}

// Lấy cơ sở được chọn
$maCoSo = isset($_GET['maCoSo']) ? (int)$_GET['maCoSo'] : 0;
$tenCoSo = '';

// Cấu hình phân trang
$recordsPerPage = 10; // Số bản ghi trên mỗi trang
$page = isset($_GET['p']) ? max(1, (int)$_GET['p']) : 1;
$offset = max(0, ($page - 1) * $recordsPerPage);

// Xây dựng điều kiện WHERE
$whereClause = "";
if ($maCoSo > 0) {
    $whereClause = "WHERE b.MaCoSo = $maCoSo";
    // Lấy tên cơ sở
    $sqlTenCoSo = "SELECT TenCoSo FROM coso WHERE MaCoSo = $maCoSo";
    $resultTenCoSo = mysqli_query($conn, $sqlTenCoSo);
    $coSo = mysqli_fetch_array($resultTenCoSo);
    $tenCoSo = $coSo['TenCoSo'] ?? '';
}

// Đếm tổng số bản ghi
$countSql = "SELECT COUNT(*) as total FROM `ban` b $whereClause";
$countResult = mysqli_query($conn, $countSql);
$totalRecords = mysqli_fetch_array($countResult)['total'];
$totalPages = ceil($totalRecords / $recordsPerPage);

// Lấy dữ liệu với phân trang
$sql = "SELECT b.*, c.TenCoSo 
        FROM `ban` b 
        LEFT JOIN `coso` c ON b.MaCoSo = c.MaCoSo 
        $whereClause 
        ORDER BY b.MaBan 
        LIMIT $offset, $recordsPerPage";
$result = mysqli_query($conn, $sql);
$menuItems = [];
while($row = mysqli_fetch_array($result)){
    $menuItems[] = $row;
}


?>
<!-- Hiển thị danh sách bàn ăn -->
<div class="card shadow p-4">
    <!-- Hiển thị thông báo -->
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> <?=$_SESSION['success_message']?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> <?=$_SESSION['error_message']?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>
    
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>
            Danh sách bàn ăn 
            <?php if ($maCoSo > 0): ?>
                - <?=$tenCoSo?> (<?php echo $totalRecords; ?> bàn)
            <?php else: ?>
                (<?php echo $totalRecords; ?> bàn)
            <?php endif; ?>
        </h4>
        <a href="?page=admin&section=table&action=status<?=($maCoSo > 0) ? '&maCoSo=' . $maCoSo : ''?>" class="btn btn-info">
            <i class="fas fa-clock"></i> Quản lý trạng thái bàn
        </a>
    </div>

    <!-- Form chọn cơ sở -->
    <form method="GET" class="mb-4">
        <input type="hidden" name="page" value="admin">
        <input type="hidden" name="section" value="table">
        <input type="hidden" name="action" value="view">
        
        <div class="row">
            <div class="col-md-6">
                <label class="form-label">Chọn cơ sở:</label>
                <select name="maCoSo" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Tất cả cơ sở --</option>
                    <?php foreach($listCoSo as $coSo): ?>
                        <option value="<?=$coSo['MaCoSo']?>" <?=($maCoSo == $coSo['MaCoSo']) ? 'selected' : ''?>>
                            <?=$coSo['TenCoSo']?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">&nbsp;</label>
                <div>
                    <?php if ($maCoSo > 0): ?>
                        <a href="?page=admin&section=table&action=view" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Xóa bộ lọc
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </form>
    <table class="table table-bordered align-middle text-center" id="menuTable">
      <thead class="table-dark">
        <tr>
          <th width="10%">Mã bàn</th>
          <th width="20%">Cơ sở</th>
          <th width="35%">Tên bàn</th>
          <th>Sức chứa</th>
          <th>Hành động</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($menuItems)): ?>
          <?php foreach($menuItems as $row): ?>
            <?php
            // Giới hạn mô tả
            $motaFull = $row['MoTa'] ?? '';
            $maxLen = 50;  // số ký tự tối đa
            if (mb_strlen($motaFull, 'UTF-8') > $maxLen) {
                $motaShort = mb_substr($motaFull, 0, $maxLen, 'UTF-8') . '...';
            } else {
                $motaShort = $motaFull;
            }
            ?>
            <tr>
              <td><?=$row['MaBan']?></td>
              <td><?=$row['TenCoSo']?></td>
              <td><?=$row['TenBan']?></td>
              <td><span class="badge bg-danger"><?=$row['SucChua']?></span></td>
              <td>
              <div class="d-flex justify-content-center gap-2" role="group">
              <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#updateTableModal" 
                          onclick="loadTableData(<?=$row['MaBan']?>, '<?=htmlspecialchars($row['MaCoSo'], ENT_QUOTES)?>', '<?=htmlspecialchars($row['TenBan'], ENT_QUOTES)?>', '<?=htmlspecialchars($row['SucChua'], ENT_QUOTES)?>')">
              <i class="fas fa-edit"></i> Sửa
              </button>

              <a class="btn btn-danger" href="?page=admin&section=table&action=delete&MaBan=<?=$row['MaBan']?>"><i class="fas fa-trash"></i> Xoá</a>
              </div>
            </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="5" class="text-center text-muted py-4">
              <i class="fas fa-utensils fa-3x mb-3"></i>
              <br>
              Chưa có bàn nào trong hệ thống
            </td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>

    <!-- Phân trang (chỉ hiện khi có nhiều hơn 1 trang) -->
    <?php if ($totalPages > 1): ?>
    <nav>
      <ul class="pagination justify-content-center">
        <!-- Nút Trước -->
        <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
          <a class="page-link" href="<?php echo ($page <= 1) ? '#' : '?page=admin&section=table&action=view&maCoSo=' . $maCoSo . '&p=' . ($page - 1); ?>">Trước</a>
        </li>
        
        <!-- Các trang số -->
        <?php
        $startPage = max(1, $page - 2);
        $endPage = min($totalPages, $page + 2);
        
        // Hiện trang đầu nếu không ở gần đầu
        if ($startPage > 1) {
            echo '<li class="page-item"><a class="page-link" href="?page=admin&section=table&action=view&maCoSo=' . $maCoSo . '&p=1">1</a></li>';
            if ($startPage > 2) {
                echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
        }
        
        // Hiện các trang xung quanh trang hiện tại
        for ($i = $startPage; $i <= $endPage; $i++) {
            $activeClass = ($i == $page) ? 'active' : '';
            echo '<li class="page-item ' . $activeClass . '"><a class="page-link" href="?page=admin&section=table&action=view&maCoSo=' . $maCoSo . '&p=' . $i . '">' . $i . '</a></li>';
        }
        
        // Hiện trang cuối nếu không ở gần cuối
        if ($endPage < $totalPages) {
            if ($endPage < $totalPages - 1) {
                echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
            echo '<li class="page-item"><a class="page-link" href="?page=admin&section=table&action=view&maCoSo=' . $maCoSo . '&p=' . $totalPages . '">' . $totalPages . '</a></li>';
        }
        ?>
        
        <!-- Nút Sau -->
        <li class="page-item <?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>">
          <a class="page-link" href="<?php echo ($page >= $totalPages) ? '#' : '?page=admin&section=table&action=view&maCoSo=' . $maCoSo . '&p=' . ($page + 1); ?>">Sau</a>
        </li>
      </ul>
    </nav>
    <?php endif; ?>
   </div>