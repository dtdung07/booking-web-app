<?php
$title = 'Hồ sơ cá nhân - Quán Nhậu Tự Do';
$additional_head = '<link rel="stylesheet" href="' . asset('css/pages/profile.css') . '">';
?>

<div class="profile-container">
    <div class="container">
        <div class="profile-wrapper">
            <!-- Sidebar -->
            <div class="profile-sidebar">
                <div class="profile-avatar">
                    <div class="avatar-circle">
                        <i class="fas fa-user"></i>
                    </div>
                    <h3><?php echo htmlspecialchars($user['full_name']); ?></h3>
                    <p><?php echo htmlspecialchars($user['email']); ?></p>
                    <span class="user-role"><?php echo ucfirst($user['role']); ?></span>
                </div>
                
                <nav class="profile-nav">
                    <ul>
                        <li><a href="?page=profile" class="active"><i class="fas fa-user"></i> Thông tin cá nhân</a></li>
                        <li><a href="?page=profile&action=changePassword"><i class="fas fa-lock"></i> Đổi mật khẩu</a></li>
                        <li><a href="?page=bookings"><i class="fas fa-calendar-alt"></i> Quản lý đặt bàn</a></li>
                        <?php if ($user['role'] === 'admin'): ?>
                            <li><a href="?page=admin"><i class="fas fa-cog"></i> Quản trị hệ thống</a></li>
                        <?php endif; ?>
                        <li><a href="?page=auth&action=logout" class="logout-link"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a></li>
                    </ul>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="profile-content">
                <div class="content-header">
                    <h1><i class="fas fa-user"></i> Thông tin cá nhân</h1>
                    <p>Quản lý thông tin tài khoản của bạn</p>
                </div>

                <!-- Hiển thị thông báo -->
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>

                <div class="profile-info-card">
                    <div class="card-header">
                        <h2><i class="fas fa-info-circle"></i> Thông tin tài khoản</h2>
                    </div>
                    
                    <div class="card-body">
                        <div class="info-grid">
                            <div class="info-item">
                                <label><i class="fas fa-user"></i> Họ và tên</label>
                                <span><?php echo htmlspecialchars($user['full_name']); ?></span>
                            </div>
                            
                            <div class="info-item">
                                <label><i class="fas fa-at"></i> Tên đăng nhập</label>
                                <span><?php echo htmlspecialchars($user['username']); ?></span>
                            </div>
                            
                            <div class="info-item">
                                <label><i class="fas fa-shield-alt"></i> Vai trò</label>
                                <span class="role-badge role-<?php echo $user['role']; ?>">
                                    <?php 
                                    $roles = [
                                        'nhan_vien' => 'Nhân viên',
                                        'admin' => 'Quản trị viên'
                                    ];
                                    echo $roles[$user['role']] ?? ucfirst($user['role']);
                                    ?>
                                </span>
                            </div>
                            
                            <?php if (!empty($user['branch_name'])): ?>
                            <div class="info-item">
                                <label><i class="fas fa-building"></i> Cơ sở làm việc</label>
                                <span><?php echo htmlspecialchars($user['branch_name']); ?></span>
                            </div>
                            <?php endif; ?>
                            
                            <div class="info-item">
                                <label><i class="fas fa-id-badge"></i> Mã nhân viên</label>
                                <span>#<?php echo str_pad($user['id'], 4, '0', STR_PAD_LEFT); ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Thống kê hoạt động -->
                <div class="activity-stats">
                    <h2><i class="fas fa-chart-bar"></i> Thống kê hoạt động</h2>
                    
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div class="stat-info">
                                <h3>0</h3>
                                <p>Lần đặt bàn</p>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-shopping-bag"></i>
                            </div>
                            <div class="stat-info">
                                <h3>0</h3>
                                <p>Đơn hàng</p>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-heart"></i>
                            </div>
                            <div class="stat-info">
                                <h3>0</h3>
                                <p>Món yêu thích</p>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-star"></i>
                            </div>
                            <div class="stat-info">
                                <h3>0</h3>
                                <p>Đánh giá</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hoạt động gần đây -->
                <div class="recent-activity">
                    <h2><i class="fas fa-clock"></i> Thông tin hệ thống</h2>
                    
                    <div class="activity-list">
                        <div class="system-note">
                            <div class="note-icon">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <div class="note-content">
                                <h4>Quyền truy cập</h4>
                                <p>Tài khoản của bạn có quyền <?php echo $user['role'] === 'admin' ? 'quản trị toàn hệ thống' : 'nhân viên cơ sở'; ?></p>
                                <?php if ($user['role'] === 'admin'): ?>
                                    <ul>
                                        <li>Quản lý đặt bàn toàn hệ thống</li>
                                        <li>Quản lý nhân viên và cơ sở</li>
                                        <li>Xem báo cáo thống kê</li>
                                        <li>Cấu hình hệ thống</li>
                                    </ul>
                                <?php else: ?>
                                    <ul>
                                        <li>Quản lý đặt bàn tại cơ sở</li>
                                        <li>Xử lý đơn hàng</li>
                                        <li>Xem thông tin khách hàng</li>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.remove();
            }, 300);
        }, 5000);
    });
});
</script>
