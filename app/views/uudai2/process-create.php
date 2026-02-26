<?php
/*
File: app/views/admin/uudai/index.php
Hiển thị giao diện quản lý ưu đãi
*/

// Include config database
include __DIR__ . '/../../../../config/connect.php';

// KHỞI TẠO BIẾN $stats ĐỂ TRÁNH LỖI
$stats = [
    'active' => 0,
    'expired' => 0
];

// Lấy thống kê ưu đãi từ database
try {
    // Đếm số ưu đãi đang hoạt động
    $activeQuery = "SELECT COUNT(*) as count FROM uudai 
                   WHERE NgayBatDau <= CURDATE() AND NgayKetThuc >= CURDATE() 
                   AND TrangThai = 'active'";
    $activeResult = mysqli_query($conn, $activeQuery);
    if ($activeResult && $row = mysqli_fetch_assoc($activeResult)) {
        $stats['active'] = $row['count'];
    }
    
    // Đếm số ưu đãi đã kết thúc
    $expiredQuery = "SELECT COUNT(*) as count FROM uudai 
                    WHERE NgayKetThuc < CURDATE() OR TrangThai = 'expired'";
    $expiredResult = mysqli_query($conn, $expiredQuery);
    if ($expiredResult && $row = mysqli_fetch_assoc($expiredResult)) {
        $stats['expired'] = $row['count'];
    }
    
} catch (Exception $e) {
    // Nếu có lỗi, vẫn giữ giá trị mặc định
    error_log("Lỗi khi lấy thống kê ưu đãi: " . $e->getMessage());
}

// Xử lý các action
if(isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'delete':
            include "process-delete.php";
            break;
        case 'process-update':
            include "process-update.php";
            break;
        case 'process-create':
            include "process-create.php";
            break;
    }
}

// Lấy danh sách ưu đãi
$promotions = [];
try {
    $query = "SELECT * FROM uudai ORDER BY NgayTao DESC";
    $result = mysqli_query($conn, $query);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $promotions[] = $row;
        }
    }
} catch (Exception $e) {
    error_log("Lỗi khi lấy danh sách ưu đãi: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Ưu đãi</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .stats-card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .stats-card:hover {
            transform: translateY(-5px);
        }
        .promotion-card {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            margin-bottom: 15px;
            transition: box-shadow 0.3s ease;
        }
        .promotion-card:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        .status-active {
            border-left: 4px solid #28a745;
        }
        .status-expired {
            border-left: 4px solid #dc3545;
        }
        .status-upcoming {
            border-left: 4px solid #ffc107;
        }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="h3 mb-0">Quản lý Ưu đãi</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Ưu đãi</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Thống kê -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card stats-card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="card-title"><?php echo count($promotions); ?></h4>
                                <p class="card-text">Tổng số ưu đãi</p>
                            </div>
                            <i class="fas fa-tags fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stats-card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="card-title"><?php echo $stats['active']; ?></h4>
                                <p class="card-text">Đang hoạt động</p>
                            </div>
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stats-card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="card-title"><?php echo $stats['expired']; ?></h4>
                                <p class="card-text">Đã kết thúc</p>
                            </div>
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thanh công cụ -->
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="input-group">
                    <input type="text" class="form-control" id="searchInput" placeholder="Tìm kiếm ưu đãi...">
                    <button class="btn btn-outline-secondary" type="button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-6 text-end">
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addPromotionModal">
                    <i class="fas fa-plus"></i> Thêm ưu đãi mới
                </button>
            </div>
        </div>

        <!-- Danh sách ưu đãi -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Danh sách Ưu đãi</h5>
            </div>
            <div class="card-body">
                <?php if (empty($promotions)): ?>
                    <div class="text-center py-4">
                        <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Chưa có ưu đãi nào</p>
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addPromotionModal">
                            <i class="fas fa-plus"></i> Thêm ưu đãi đầu tiên
                        </button>
                    </div>
                <?php else: ?>
                    <div class="row">
                        <?php foreach ($promotions as $promotion): ?>
                            <?php
                            // Xác định trạng thái
                            $currentDate = date('Y-m-d');
                            $startDate = $promotion['NgayBatDau'];
                            $endDate = $promotion['NgayKetThuc'];
                            
                            if ($currentDate < $startDate) {
                                $statusClass = 'status-upcoming';
                                $statusText = 'Sắp diễn ra';
                                $statusBadge = 'bg-warning';
                            } elseif ($currentDate > $endDate) {
                                $statusClass = 'status-expired';
                                $statusText = 'Đã kết thúc';
                                $statusBadge = 'bg-danger';
                            } else {
                                $statusClass = 'status-active';
                                $statusText = 'Đang hoạt động';
                                $statusBadge = 'bg-success';
                            }
                            ?>
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="card promotion-card <?php echo $statusClass; ?>">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="card-title"><?php echo htmlspecialchars($promotion['TenUD']); ?></h6>
                                            <span class="badge <?php echo $statusBadge; ?>"><?php echo $statusText; ?></span>
                                        </div>
                                        <p class="card-text small text-muted mb-2">
                                            <i class="fas fa-calendar-alt me-1"></i>
                                            <?php echo date('d/m/Y', strtotime($startDate)); ?> - <?php echo date('d/m/Y', strtotime($endDate)); ?>
                                        </p>
                                        <p class="card-text mb-2">
                                            <strong>Giảm: </strong>
                                            <?php 
                                            if ($promotion['LoaiGiamGia'] == 'percent') {
                                                echo $promotion['GiaTri'] . '%';
                                            } else {
                                                echo number_format($promotion['GiaTri']) . ' VNĐ';
                                            }
                                            ?>
                                        </p>
                                        <?php if (!empty($promotion['MoTa'])): ?>
                                            <p class="card-text small"><?php echo htmlspecialchars($promotion['MoTa']); ?></p>
                                        <?php endif; ?>
                                        <div class="d-flex justify-content-between mt-3">
                                            <button class="btn btn-sm btn-outline-primary" 
                                                    onclick="editPromotion(<?php echo $promotion['MaUD']; ?>)">
                                                <i class="fas fa-edit"></i> Sửa
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" 
                                                    onclick="deletePromotion(<?php echo $promotion['MaUD']; ?>, '<?php echo htmlspecialchars($promotion['TenUD']); ?>')">
                                                <i class="fas fa-trash"></i> Xóa
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Modal Thêm Ưu đãi -->
    <div class="modal fade" id="addPromotionModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thêm Ưu đãi Mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addPromotionForm" action="?action=process-create" method="POST">
                        <div class="mb-3">
                            <label for="promotionName" class="form-label">Tên ưu đãi</label>
                            <input type="text" class="form-control" id="promotionName" name="promotion_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="promotionDescription" class="form-label">Mô tả</label>
                            <textarea class="form-control" id="promotionDescription" name="promotion_description" rows="3"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="discountType" class="form-label">Loại giảm giá</label>
                                    <select class="form-select" id="discountType" name="discount_type" required>
                                        <option value="percent">Phần trăm (%)</option>
                                        <option value="fixed">Số tiền cố định</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="discountValue" class="form-label">Giá trị</label>
                                    <input type="number" class="form-control" id="discountValue" name="discount_value" step="0.01" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="startDate" class="form-label">Ngày bắt đầu</label>
                                    <input type="date" class="form-control" id="startDate" name="start_date" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="endDate" class="form-label">Ngày kết thúc</label>
                                    <input type="date" class="form-control" id="endDate" name="end_date" required>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" form="addPromotionForm" class="btn btn-success">Thêm ưu đãi</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap & JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editPromotion(promotionId) {
            // Chuyển hướng đến trang chỉnh sửa
            window.location.href = 'update.php?id=' + promotionId;
        }

        function deletePromotion(promotionId, promotionName) {
            if (confirm('Bạn có chắc muốn xóa ưu đãi "' + promotionName + '" không?')) {
                window.location.href = '?action=delete&id=' + promotionId;
            }
        }

        // Tự động set ngày bắt đầu là hôm nay, ngày kết thúc là 30 ngày sau
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            const endDate = new Date();
            endDate.setDate(endDate.getDate() + 30);
            const endDateFormatted = endDate.toISOString().split('T')[0];
            
            document.getElementById('startDate').value = today;
            document.getElementById('endDate').value = endDateFormatted;
        });

        // Tìm kiếm ưu đãi
        document.getElementById('searchInput').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const promotionCards = document.querySelectorAll('.promotion-card');
            
            promotionCards.forEach(card => {
                const title = card.querySelector('.card-title').textContent.toLowerCase();
                const description = card.querySelector('.card-text') ? card.querySelector('.card-text').textContent.toLowerCase() : '';
                
                if (title.includes(searchTerm) || description.includes(searchTerm)) {
                    card.parentElement.style.display = 'block';
                } else {
                    card.parentElement.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>