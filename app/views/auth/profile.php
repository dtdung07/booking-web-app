<?php
// Kiểm tra đăng nhập
if (!isset($_SESSION['user'])) {
    header('Location: index.php?page=auth&action=login');
    exit;
}

$user = $_SESSION['user'];
$isAdmin = $user['ChucVu'] === 'admin';
$isNhanVien = $user['ChucVu'] === 'nhan_vien';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin cá nhân</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root {
            --colorPrimary: #1B4E30;
            --colorYellow: #FFA827;
        }
        
        .profile-container {
            max-width: 500px;
            margin: 10px auto;
        }
        
        .profile-header {
            background: linear-gradient(135deg, var(--colorPrimary) 0%, #2d6b47 100%);
            color: white;
            padding: 2rem;
            border-radius: 15px 15px 0 0;
        }
        
        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: var(--colorPrimary);
            margin: 0 auto 1rem;
            border: 5px solid rgba(255,255,255,0.3);
        }
        
        .profile-body {
            background: white;
            padding: 2rem;
            border-radius: 0 0 15px 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .info-row {
            padding: 1rem 0;
            border-bottom: 1px solid #e9ecef;
        }
        
        .info-row:last-child {
            border-bottom: none;
        }
        
        .info-label {
            font-weight: 600;
            color: #6c757d;
            margin-bottom: 0.25rem;
        }
        
        .info-value {
            font-size: 1.1rem;
            color: #212529;
        }
        
        .badge-role {
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container profile-container">
        <!-- Back Button -->
        <div class="mb-3">
            <a href="<?php echo $isAdmin ? 'index.php?page=admin' : 'index.php?page=nhanvien&action=dashboard'; ?>" 
               class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Quay lại Dashboard
            </a>
        </div>

        <!-- Profile Card -->
        <div class="card border-0">
            <!-- Header -->
            <div class="profile-header text-center">
                <div class="profile-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <h2 class="mb-2"><?php echo htmlspecialchars($user['TenNhanVien']); ?></h2>
                <span class="badge <?php echo $isAdmin ? 'bg-warning' : 'bg-info'; ?> badge-role">
                    <i class="fas fa-<?php echo $isAdmin ? 'crown' : 'user-tie'; ?> me-1"></i>
                    <?php echo $isAdmin ? 'Quản trị viên' : 'Nhân viên'; ?>
                </span>
            </div>

            <!-- Body -->
            <div class="profile-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">
                                <i class="fas fa-id-card me-2"></i>Mã nhân viên
                            </div>
                            <div class="info-value"><?php echo htmlspecialchars($user['MaNV']); ?></div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">
                                <i class="fas fa-user me-2"></i>Tên đăng nhập
                            </div>
                            <div class="info-value"><?php echo htmlspecialchars($user['TenDN']); ?></div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">
                                <i class="fas fa-phone me-2"></i>Số điện thoại
                            </div>
                            <div class="info-value">
                                <?php echo !empty($user['DienThoai']) ? htmlspecialchars($user['DienThoai']) : '<span class="text-muted">Chưa cập nhật</span>'; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">
                                <i class="fas fa-envelope me-2"></i>Email
                            </div>
                            <div class="info-value">
                                <?php echo !empty($user['Email']) ? htmlspecialchars($user['Email']) : '<span class="text-muted">Chưa cập nhật</span>'; ?>
                            </div>
                        </div>
                    </div>
                    
                    <?php if (isset($user['MaCoSo']) && !$isAdmin): ?>
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">
                                <i class="fas fa-store me-2"></i>Cơ sở làm việc
                            </div>
                            <div class="info-value"><?php echo htmlspecialchars($user['MaCoSo']); ?></div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">
                                <i class="fas fa-briefcase me-2"></i>Chức vụ
                            </div>
                            <div class="info-value">
                                <?php echo $isAdmin ? 'Quản trị viên' : 'Nhân viên'; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-4 pt-3 border-top">
                    <div class="d-flex justify-content-between">
                        <button class="btn btn-outline-primary" onclick="alert('Tính năng đổi mật khẩu đang được phát triển')">
                            <i class="fas fa-key me-2"></i>Đổi mật khẩu
                        </button>
                        <a href="index.php?page=auth&action=logout" class="btn btn-danger">
                            <i class="fas fa-sign-out-alt me-2"></i>Đăng xuất
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
