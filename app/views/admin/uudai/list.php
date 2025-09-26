<?php
include __DIR__ . "/connect.php";

// Include modal sửa ưu đãi
// include __DIR__ . "/update.php";

// Cấu hình phân trang
$recordsPerPage = 10; // Số bản ghi trên mỗi trang
$page = isset($_GET['p']) ? max(1, (int)$_GET['p']) : 1;
$offset = max(0, ($page - 1) * $recordsPerPage);

// Đếm tổng số bản ghi
$countSql = "SELECT COUNT(*) as total FROM `uudai` WHERE 1";
$countResult = mysqli_query($conn, $countSql);
$totalRecords = mysqli_fetch_array($countResult)['total'];
$totalPages = ceil($totalRecords / $recordsPerPage);

// Lấy dữ liệu với phân trang
$sql = "SELECT * FROM `uudai` ORDER BY MaUuDai DESC LIMIT $offset, $recordsPerPage";
$result = mysqli_query($conn, $sql);

// Die and show error if query fails
if (!$result) {
    die("Database query failed: " . mysqli_error($conn));
}

$uudaiItems = [];
while($row = mysqli_fetch_array($result)){
    $uudaiItems[] = $row;
}
?>
<!-- Hiển thị danh sách ưu đãi -->
<div class="card shadow p-4">
    <h4 class="mb-3">Danh sách ưu đãi (<?php echo $totalRecords; ?> ưu đãi)</h4>
    <table class="table table-bordered align-middle text-center" id="uudaiTable">
      <thead class="table-dark">
        <tr>
          <th width="5%">ID</th>
          <th width="25%">Tiêu đề</th>
          <th width="30%">Nội dung</th>
          <th>Giảm giá</th>
          <th>Thời hạn</th>
          <th>Hành động</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($uudaiItems)): ?>
          <?php foreach($uudaiItems as $row): ?>
            <?php
            $noiDungFull = $row['NoiDung'] ?? '';
            $maxLen = 50;
            if (mb_strlen($noiDungFull, 'UTF-8') > $maxLen) {
                $noiDungShort = mb_substr($noiDungFull, 0, $maxLen, 'UTF-8') . '...';
            } else {
                $noiDungShort = $noiDungFull;
            }
            
            $ngayBatDau = date("d/m/Y", strtotime($row['NgayBatDau']));
            $ngayKetThuc = date("d/m/Y", strtotime($row['NgayKetThuc']));
            $today = date("Y-m-d");
            $statusClass = $row['NgayKetThuc'] < $today ? 'text-danger' : 'text-success';
            ?>
            <tr>
              <td><?=$row['MaUuDai']?></td>
              <td><?=$row['TieuDe']?></td>
              <td><?=$noiDungShort?></td>
              <td><?=$row['PhanTramGiam']?>%</td>
              <td class="<?=$statusClass?>"><?=$ngayBatDau?> - <?=$ngayKetThuc?></td>
              <td>
              <div class="d-flex justify-content-center gap-2" role="group">
              <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#updateUuDaiModal<?=$row['MaUuDai']?>">
                <i class="fas fa-edit"></i> Sửa
              </button>
              <?php include __DIR__ . "/update.php"; ?>

              <a class="btn btn-danger btn-sm" href="?page=admin&section=uudai&action=delete&MaUuDai=<?=$row['MaUuDai']?>" onclick="return confirm('Bạn có chắc chắn muốn xóa ưu đãi này không?');"><i class="fas fa-trash"></i> Xoá</a>
              </div>
            </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="6" class="text-center text-muted py-4">
              <i class="fas fa-gift fa-3x mb-3"></i>
              <br>
              Chưa có ưu đãi nào trong hệ thống
            </td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>

    <!-- Phân trang -->
    <?php if ($totalPages > 1): ?>
    <nav>
      <ul class="pagination justify-content-center">
        <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
          <a class="page-link" href="?page=admin&section=uudai&p=<?=($page - 1)?>">Trước</a>
        </li>
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <li class="page-item <?=($i == $page) ? 'active' : ''?>">
          <a class="page-link" href="?page=admin&section=uudai&p=<?=$i?>"><?=$i?></a>
        </li>
        <?php endfor; ?>
        <li class="page-item <?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>">
          <a class="page-link" href="?page=admin&section=uudai&p=<?=($page + 1)?>">Sau</a>
        </li>
      </ul>
    </nav>
    <?php endif; ?>
   </div>