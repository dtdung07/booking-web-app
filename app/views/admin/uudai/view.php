<?php
include __DIR__ . "/connect.php";

// Kiểm tra xem MaUuDai có được truyền qua URL hay không
if (!isset($_GET['MaUuDai']) || empty($_GET['MaUuDai'])) {
    echo '<div class="alert alert-danger">Lỗi: Không tìm thấy Mã Ưu đãi.</div>';
    exit;
}

$mauudai = mysqli_real_escape_string($conn, $_GET['MaUuDai']);

// Truy vấn thông tin chi tiết Ưu đãi
$sql = "SELECT * FROM `uudai` WHERE MaUuDai = '$mauudai'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    echo '<div class="alert alert-warning">Lỗi: Ưu đãi không tồn tại.</div>';
    exit;
}

$uudai = mysqli_fetch_array($result);

// Định dạng ngày tháng
$ngay_bd_hien_thi = date('d/m/Y', strtotime($uudai['NgayBatDau']));
$ngay_kt_hien_thi = date('d/m/Y', strtotime($uudai['NgayKetThuc']));
?>

<div class="card shadow p-4">
    <h3 class="mb-4 text-success">
        <i class="fas fa-eye me-2"></i> Chi tiết Ưu đãi: **<?= htmlspecialchars($uudai['TenUuDai']) ?>**
    </h3>
    
    <div class="row">
        <div class="col-md-4 text-center mb-4">
            <img src="<?= htmlspecialchars($uudai['AnhUuDai']) ?>" 
                 alt="<?= htmlspecialchars($uudai['TenUuDai']) ?>" 
                 class="img-fluid rounded shadow-sm" style="max-height: 250px; object-fit: cover;">
        </div>

        <div class="col-md-8">
            <table class="table table-borderless table-striped">
                <tbody>
                    <tr>
                        <th width="30%">Mã Ưu đãi (ID)</th>
                        <td><?= htmlspecialchars($uudai['MaUuDai']) ?></td>
                    </tr>
                    <tr>
                        <th>Tên Ưu đãi</th>
                        <td><?= htmlspecialchars($uudai['TenUuDai']) ?></td>
                    </tr>
                    <tr>
                        <th>Giá trị/Mức giảm</th>
                        <td class="fw-bold text-danger"><?= htmlspecialchars($uudai['GiaTri']) ?></td>
                    </tr>
                    <tr>
                        <th>Ngày Bắt đầu</th>
                        <td><?= $ngay_bd_hien_thi ?></td>
                    </tr>
                    <tr>
                        <th>Ngày Kết thúc</th>
                        <td><?= $ngay_kt_hien_thi ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <h5 class="mt-4 border-bottom pb-2">Mô tả và Điều kiện áp dụng</h5>
    <div class="p-3 bg-light rounded">
        <p style="white-space: pre-wrap;"><?= htmlspecialchars($uudai['MoTaUuDai']) ?></p>
    </div>

    <div class="mt-4 text-end">
        <a href="?page=admin&section=uudai" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại Danh sách
        </a>
        <button class="btn btn-warning ms-2" data-bs-toggle="modal" data-bs-target="#updateUuDaiModal" 
                onclick="loadUuDaiData('<?= $mauudai ?>', '<?= htmlspecialchars($uudai['TenUuDai'], ENT_QUOTES) ?>', '<?= htmlspecialchars($uudai['GiaTri'], ENT_QUOTES) ?>', '<?= htmlspecialchars($uudai['NgayBatDau'], ENT_QUOTES) ?>', '<?= htmlspecialchars($uudai['NgayKetThuc'], ENT_QUOTES) ?>', '<?= htmlspecialchars($uudai['AnhUuDai'], ENT_QUOTES) ?>', '<?= htmlspecialchars($uudai['MoTaUuDai'], ENT_QUOTES) ?>')">
            <i class="fas fa-edit"></i> Sửa Ưu đãi này
        </button>
    </div>
</div>