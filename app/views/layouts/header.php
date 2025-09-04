<?php
// Bắt đầu session nếu chưa có
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Sử dụng static method để tránh lỗi constructor
$isLoggedIn = false;
$currentUser = null;

// Load User model để sử dụng static methods
$userModelPath = __DIR__ . '/../../models/User.php';
if (file_exists($userModelPath)) {
    require_once $userModelPath;
    $isLoggedIn = User::isUserLoggedIn();
    $currentUser = User::getCurrentUser();
}
?>

<header>
    <div class="main-header">
        <div class="container">
            <a href="<?php echo url('/'); ?>" class="logo">
                <i class="fas fa-star" aria-hidden="true"></i>
                <div class="logo-text">
                    <span class="logo-top">QUÁN NHẬU </span>
                    <span class="logo-bottom">TỰ DO</span>
                </div>
            </a>
            
            <div class="hotline">
                <p>HOTLINE</p>
                <strong>*1986</strong>
            </div>
            
            <nav class="main-nav" role="navigation" aria-label="Main navigation">
                <ul>
                    <li><a href="<?php echo url('?page=menu'); ?>" class="<?php echo isActivePage('menu'); ?>">THỰC ĐƠN</a></li>
                    <li><a href="<?php echo url('?page=branches'); ?>" class="<?php echo isActivePage('branches'); ?>">CƠ SỞ</a></li>
                    <li><a href="<?php echo url('?page=promotions'); ?>" class="<?php echo isActivePage('promotions'); ?>">ƯU ĐÃI</a></li>
                    <li><a href="<?php echo url('?page=contact'); ?>" class="<?php echo isActivePage('contact'); ?>">LIÊN HỆ</a></li>
                </ul>
            </nav>
            
            <div class="header-actions">
                <a href="<?php echo url('?page=booking'); ?>" class="btn-booking" role="button">ĐẶT BÀN</a>
                
                <?php if ($isLoggedIn): ?>
                    <!-- User Menu -->
                    <div class="user-menu">
                        <button class="user-toggle" onclick="toggleUserMenu()">
                            <i class="fas fa-user-circle"></i>
                            <span class="user-name"><?php echo htmlspecialchars($currentUser['full_name']); ?></span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="user-dropdown" id="userDropdown">
                            <div class="user-info">
                                <div class="user-avatar">
                                    <i class="fas fa-user-circle"></i>
                                </div>
                                <div class="user-details">
                                    <strong><?php echo htmlspecialchars($currentUser['full_name']); ?></strong>
                                    <span><?php echo htmlspecialchars($currentUser['username']); ?></span>
                                    <span class="user-role"><?php echo ucfirst($currentUser['role']); ?></span>
                                    <?php if (!empty($currentUser['branch_name'])): ?>
                                        <span class="user-branch"><?php echo htmlspecialchars($currentUser['branch_name']); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="dropdown-divider"></div>
                            <ul class="dropdown-menu">
                                <li><a href="?page=profile"><i class="fas fa-user"></i> Hồ sơ cá nhân</a></li>
                                <li><a href="?page=bookings"><i class="fas fa-calendar-alt"></i> Quản lý đặt bàn</a></li>
                                <?php if ($currentUser['role'] === 'admin'): ?>
                                    <li class="divider"></li>
                                    <li><a href="?page=admin"><i class="fas fa-cog"></i> Quản trị hệ thống</a></li>
                                    <li><a href="?page=admin&section=staff"><i class="fas fa-users"></i> Quản lý nhân viên</a></li>
                                    <li><a href="?page=admin&section=branches"><i class="fas fa-building"></i> Quản lý cơ sở</a></li>
                                <?php endif; ?>
                                <li class="divider"></li>
                                <li><a href="?page=auth&action=logout" class="logout-link"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a></li>
                            </ul>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Auth Button -->
                    <div class="auth-buttons">
                        <a href="<?php echo url('?page=auth&action=login'); ?>" class="btn-auth btn-login">
                            <i class="fas fa-sign-in-alt"></i>
                            Đăng nhập
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>

<script>
function toggleUserMenu() {
    const dropdown = document.getElementById('userDropdown');
    dropdown.classList.toggle('show');
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const userMenu = document.querySelector('.user-menu');
    const dropdown = document.getElementById('userDropdown');
    
    if (userMenu && !userMenu.contains(event.target)) {
        dropdown.classList.remove('show');
    }
});

// Handle escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        document.getElementById('userDropdown').classList.remove('show');
    }
});
</script>
