<?php
$title = "Quán Nhậu Tự Do - Quản lý Ưu đãi";
$page_title = "Quản lý Ưu đãi";
?>

<link rel="stylesheet" href="<?= dirname(__DIR__,3) . '/public/css/pages/uudai.css'?>">

<main class="uudai-page">
    <div class="uudai-container">
        <!-- Header -->
        <div class="uudai-header">
            <div class="header-info">
                <h1><i class="fas fa-tags"></i> Quản lý Ưu đãi</h1>
                <p>Quản lý các chương trình khuyến mãi và ưu đãi cho nhà hàng</p>
            </div>
            
            <div class="header-actions">
                <!-- Dropdown chọn cơ sở -->
                <div class="branch-select-wrapper">
                    <select id="branchSelect" class="branch-select">
                        <?php if (isset($branches) && is_array($branches)): ?>
                            <?php foreach ($branches as $branch): ?>
                                <option value="<?= $branch['MaCoSo'] ?>" 
                                    <?= $branch['MaCoSo'] == $maCoSo ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($branch['TenCoSo']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <i class="fas fa-chevron-down"></i>
                </div>
                
                <a href="?page=uudai_create&coso=<?= $maCoSo ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Thêm Ưu đãi
                </a>
            </div>
        </div>

        <!-- Thông báo -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <!-- Thống kê -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon total">
                    <i class="fas fa-tags"></i>
                </div>
                <div class="stat-info">
                    <h3><?= $stats['active'] + $stats['inactive'] ?></h3>
                    <p>Tổng số ưu đãi</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon active">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-info">
                    <h3><?= $stats['active'] ?></h3>
                    <p>Đang hoạt động</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon inactive">
                    <i class="fas fa-pause-circle"></i>
                </div>
                <div class="stat-info">
                    <h3><?= $stats['inactive'] ?></h3>
                    <p>Đã kết thúc</p>
                </div>
            </div>
        </div>

        <!-- Danh sách ưu đãi -->
        <div class="uudai-list">
            <div class="list-header">
                <h2><i class="fas fa-list"></i> Danh sách Ưu đãi</h2>
                <div class="list-actions">
                    <input type="text" id="searchInput" placeholder="Tìm kiếm ưu đãi..." class="search-input">
                </div>
            </div>

            <?php if (isset($uuDais) && count($uuDais) > 0): ?>
                <div class="uudai-grid">
                    <?php foreach ($uuDais as $uuDai): ?>
                        <div class="uudai-card" data-status="<?= $uuDai['status'] ?>">
                            <div class="uudai-header">
                                <div class="uudai-code"><?= htmlspecialchars($uuDai['code']) ?></div>
                                <span class="status-badge <?= $uuDai['status'] ?>">
                                    <?= $uuDai['status'] == 'active' ? 'Đang hoạt động' : 'Đã kết thúc' ?>
                                </span>
                            </div>
                            
                            <div class="uudai-body">
                                <h3 class="uudai-name"><?= htmlspecialchars($uuDai['name']) ?></h3>
                                
                                <div class="uudai-details">
                                    <div class="detail-item">
                                        <i class="fas fa-gift"></i>
                                        <span>
                                            <?php 
                                            if ($uuDai['type'] == 'percentage') {
                                                echo $uuDai['value'] . '% giảm giá';
                                            } elseif ($uuDai['type'] == 'fixed') {
                                                echo number_format($uuDai['value']) . 'đ giảm trực tiếp';
                                            } else {
                                                echo htmlspecialchars($uuDai['value']);
                                            }
                                            ?>
                                        </span>
                                    </div>
                                    
                                    <div class="detail-item">
                                        <i class="fas fa-calendar"></i>
                                        <span>
                                            <?= date('d/m/Y', strtotime($uuDai['start_date'])) ?> - 
                                            <?= date('d/m/Y', strtotime($uuDai['end_date'])) ?>
                                        </span>
                                    </div>
                                    
                                    <?php if (!empty($uuDai['description'])): ?>
                                        <div class="detail-item">
                                            <i class="fas fa-info-circle"></i>
                                            <span><?= htmlspecialchars($uuDai['description']) ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="uudai-footer">
                                <div class="uudai-actions">
                                    <a href="?page=uudai_edit&id=<?= $uuDai['id'] ?>" 
                                       class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Sửa
                                    </a>
                                    <button class="btn btn-danger btn-sm delete-btn" 
                                            data-id="<?= $uuDai['id'] ?>" 
                                            data-name="<?= htmlspecialchars($uuDai['name']) ?>">
                                        <i class="fas fa-trash"></i> Xóa
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-tags"></i>
                    </div>
                    <h3>Chưa có ưu đãi nào</h3>
                    <p>Hãy thêm ưu đãi đầu tiên để bắt đầu quản lý</p>
                    <a href="?page=uudai_create&coso=<?= $maCoSo ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Thêm Ưu đãi đầu tiên
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<!-- Modal xác nhận xóa -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-exclamation-triangle"></i> Xác nhận xóa</h3>
            <span class="close">&times;</span>
        </div>
        <div class="modal-body">
            <p>Bạn có chắc chắn muốn xóa ưu đãi "<strong id="uuDaiName"></strong>"?</p>
            <p class="text-warning">Hành động này không thể hoàn tác!</p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" id="cancelDelete">Hủy bỏ</button>
            <button class="btn btn-danger" id="confirmDelete">Xóa ưu đãi</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Xử lý chọn cơ sở
    const branchSelect = document.getElementById('branchSelect');
    if (branchSelect) {
        branchSelect.addEventListener('change', function() {
            window.location.href = '?page=uudai&coso=' + this.value;
        });
    }

    // Xử lý tìm kiếm
    const searchInput = document.getElementById('searchInput');
    const uudaiCards = document.querySelectorAll('.uudai-card');
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            uudaiCards.forEach(function(card) {
                const uudaiName = card.querySelector('.uudai-name').textContent.toLowerCase();
                const uudaiCode = card.querySelector('.uudai-code').textContent.toLowerCase();
                
                if (uudaiName.includes(searchTerm) || uudaiCode.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }

    // Xử lý xóa ưu đãi
    const deleteButtons = document.querySelectorAll('.delete-btn');
    const deleteModal = document.getElementById('deleteModal');
    const uuDaiNameSpan = document.getElementById('uuDaiName');
    const confirmDeleteBtn = document.getElementById('confirmDelete');
    const cancelDeleteBtn = document.getElementById('cancelDelete');
    const closeModal = document.querySelector('.close');

    let currentUuDaiId = null;

    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            currentUuDaiId = this.getAttribute('data-id');
            const uuDaiName = this.getAttribute('data-name');
            
            uuDaiNameSpan.textContent = uuDaiName;
            deleteModal.style.display = 'block';
        });
    });

    // Xác nhận xóa
    confirmDeleteBtn.addEventListener('click', function() {
        if (currentUuDaiId) {
            // Gửi yêu cầu xóa qua AJAX
            const formData = new FormData();
            formData.append('id', currentUuDaiId);
            
            fetch('?page=uudai_delete', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reload trang sau khi xóa thành công
                    window.location.reload();
                } else {
                    alert('Lỗi: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi xóa ưu đãi!');
            });
        }
        deleteModal.style.display = 'none';
    });

    // Đóng modal
    function closeDeleteModal() {
        deleteModal.style.display = 'none';
    }

    closeModal.addEventListener('click', closeDeleteModal);
    cancelDeleteBtn.addEventListener('click', closeDeleteModal);

    // Đóng modal khi click bên ngoài
    window.addEventListener('click', function(event) {
        if (event.target === deleteModal) {
            closeDeleteModal();
        }
    });

    // Lọc theo trạng thái (nếu có)
    const statusFilters = document.querySelectorAll('.status-filter');
    if (statusFilters.length > 0) {
        statusFilters.forEach(filter => {
            filter.addEventListener('click', function() {
                const status = this.getAttribute('data-status');
                
                uudaiCards.forEach(card => {
                    if (status === 'all' || card.getAttribute('data-status') === status) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
                
                // Cập nhật active class cho filter
                statusFilters.forEach(f => f.classList.remove('active'));
                this.classList.add('active');
            });
        });
    }
});
</script>
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
