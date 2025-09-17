<?php
// Kiểm tra quyền truy cập - chỉ cho phép nhân viên
if (!isset($_SESSION['user']) || $_SESSION['user']['ChucVu'] !== 'nhan_vien') {
    $_SESSION['error_message'] = 'Bạn không có quyền truy cập trang này.';
    header('Location: index.php?page=auth&action=login');
    exit;
}

// Lấy thông tin nhân viên hiện tại
$currentUser = $_SESSION['user'];

// Kết nối database để lấy thống kê (sử dụng mysqli)
$host = 'db';
$user = 'bookinguser';
$pass = 'bookingpass';
$database = 'booking_restaurant';
$port = 3306;
$conn = mysqli_connect($host, $user, $pass, $database, $port);

if (!$conn) {
    die("Kết nối database thất bại: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8");

// Lấy thống kê cho nhân viên
if ($conn && isset($currentUser['MaCoSo'])) {
    $maCoSo = $currentUser['MaCoSo'];

    // ---------- Thông tin cơ sở ----------
    $stmt = $conn->prepare("SELECT * FROM coso WHERE MaCoSo = ?");
    $stmt->bind_param("s", $maCoSo);
    $stmt->execute();
    $coSoInfo = $stmt->get_result()->fetch_assoc();

    // ---------- Tổng số đơn đặt bàn ----------
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM dondatban WHERE MaCoSo = ?");
    $stmt->bind_param("s", $maCoSo);
    $stmt->execute();
    $todayBookings = $stmt->get_result()->fetch_assoc()['total'];

    // ---------- Đơn đặt bàn hôm nay ----------
    $today = date('Y-m-d');
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM dondatban WHERE MaCoSo = ? AND DATE(ThoiGianTao) = ?");
    $stmt->bind_param("ss", $maCoSo, $today);
    $stmt->execute();
    $todayNewBookings = $stmt->get_result()->fetch_assoc()['total'];

    // ---------- Đơn chờ xác nhận ----------
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM dondatban WHERE MaCoSo = ? AND TrangThai = 'cho_xac_nhan'");
    $stmt->bind_param("s", $maCoSo);
    $stmt->execute();
    $pendingBookings = $stmt->get_result()->fetch_assoc()['total'];

    // ---------- Tổng số đặt bàn (tính luôn cho tháng) ----------
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM dondatban WHERE MaCoSo = ?");
    $stmt->bind_param("s", $maCoSo);
    $stmt->execute();
    $monthlyBookings = $stmt->get_result()->fetch_assoc()['total'];

    $stmt->close();
} 
else {
    $coSoInfo = null;
    $todayBookings = 2;
    $todayNewBookings = 0;
    $pendingBookings = 2;
    $monthlyBookings = 0;
}

// Lấy tham số để quyết định hiển thị content nào
$section = $_GET['section'] ?? 'dashboard';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Nhân Viên - <?php echo htmlspecialchars($currentUser['TenNhanVien']); ?></title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        :root {
            --sidebar-width: 210px;
            --header-height: 70px;
            --colorPrimary: #1B4E30;
            --colorYellow: #FFA827;
            --colorLinkGreen: #1B4E30;
            --colorGrey: #D9D9D9;
        }
        
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: linear-gradient(135deg, var(--colorPrimary) 0%, #2d6b47 100%);
            color: white;
            z-index: 1000;
            transition: transform 0.3s ease;
        }
        
        .sidebar .brand {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 1rem 1.5rem;
            border: none;
            transition: all 0.3s ease;
        }
        
        .sidebar .nav-link:hover {
            color: white;
            background: rgba(255,255,255,0.1);
            transform: translateX(5px);
        }
        
        .sidebar .nav-link.active {
            color: white;
            background: rgba(255,255,255,0.2);
            border-right: 3px solid var(--colorYellow);
        }
        
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            background: #f8f9fa;
            transition: margin-left 0.3s ease;
        }
        
        .sidebar.collapsed {
            transform: translateX(-100%);
        }
        
        .main-content.expanded {
            margin-left: 0;
        }
        
        .header {
            height: var(--header-height);
            background: white;
            border-bottom: 1px solid #dee2e6;
            padding: 0 2rem;
        }
        
        .content-area {
            padding: 2rem;
        }
        
        .stat-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        
        .quick-action {
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 1.5rem;
            text-decoration: none;
            color: #495057;
            transition: all 0.3s ease;
        }
        
        .quick-action:hover {
            border-color: var(--colorLinkGreen);
            color: var(--colorLinkGreen);
            text-decoration: none;
            transform: translateY(-3px);
            box-shadow: 0 4px 15px rgba(33, 162, 86, 0.1);
        }
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .main-content.expanded {
                margin-left: 0;
            }
            
            .sidebar.collapsed {
                transform: translateX(-100%);
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <nav class="sidebar">
        <div class="brand">
            <h4 class="mb-0">
                <i class="fas fa-utensils me-2"></i>
                Dashboard
            </h4>
        </div>
        
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?php echo $section === 'dashboard' ? 'active' : ''; ?>" href="index.php?page=nhanvien&action=dashboard&section=dashboard">
                    <i class="fas fa-tachometer-alt me-2"></i>
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $section === 'bookings' ? 'active' : ''; ?>" href="index.php?page=nhanvien&action=dashboard&section=bookings">
                    <i class="fas fa-calendar-check me-2"></i>
                    Đơn đặt bàn
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="index.php?page=menu">
                    <i class="fas fa-utensils me-2"></i>
                    Xem Menu
                </a>
            </li>
            <li class="nav-item mt-3">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-home me-2"></i>
                    Về trang chủ
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-warning" href="index.php?page=auth&action=logout">
                    <i class="fas fa-sign-out-alt me-2"></i>
                    Đăng xuất
                </a>
            </li>
        </ul>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Header -->
        <header class="header d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <button class="btn d-md-none me-2" id="sidebarToggleMobile" style="background-color: var(--colorLinkGreen); border-color: var(--colorLinkGreen); color: white;">
                    <i class="fas fa-bars"></i>
                </button>
                <button class="btn d-none d-md-block me-3" id="sidebarToggleDesktop" style="background-color: var(--colorLinkGreen); border-color: var(--colorLinkGreen); color: white;" title="Ẩn/Hiện Menu">
                    <i class="fas fa-bars"></i>
                </button>
                <h1 class="h3 mb-0">
                    <?php 
                    switch($section) {
                        case 'bookings': echo 'Quản lý đơn đặt bàn'; break;
                        default: echo $coSoInfo ? htmlspecialchars($coSoInfo['TenCoSo']) : 'Dashboard Nhân Viên'; break;
                    }
                    ?>
                </h1>
            </div>
            
            <div class="d-flex align-items-center">
                <span class="text-muted me-3">
                    Chào mừng, <?php echo htmlspecialchars($currentUser['TenNhanVien']); ?>!
                </span>
                <div class="dropdown">
                    <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" 
                            style="background-color: var(--colorLinkGreen); border-color: var(--colorLinkGreen); color: white;">
                        <i class="fas fa-user-circle me-1"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2" style="color: var(--colorPrimary);"></i>Thông tin cá nhân</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="index.php?page=auth&action=logout"><i class="fas fa-sign-out-alt me-2"></i>Đăng xuất</a></li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Content Area -->
        <div class="content-area">
            <?php if ($section === 'dashboard'): ?>
                <!-- Dashboard Content -->
                <!-- Welcome Section -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="alert alert-success border-0" style="background: linear-gradient(var(--colorLinkGreen) 0%, var(--colorLinkGreen) 100%);">
                            <div class="d-flex align-items-center text-white">
                                <i class="fas fa-user-tie fa-2x me-3"></i>
                                <div>
                                    <h4 class="alert-heading mb-1">Chào mừng đến với Dashboard Quản trị!</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card stat-card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="stat-icon text-white me-3" style="background-color: #8B5CF6;">
                                        <i class="fas fa-calendar-day"></i>
                                    </div>
                                    <div>
                                        <h6 class="text-muted mb-1">Đặt bàn hôm nay</h6>
                                        <h3 class="mb-0"><?php echo number_format($todayNewBookings); ?></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card stat-card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="stat-icon text-white me-3" style="background-color: #F59E0B;">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <div>
                                        <h6 class="text-muted mb-1">Chờ xác nhận</h6>
                                        <h3 class="mb-0"><?php echo number_format($pendingBookings); ?></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card stat-card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="stat-icon text-white me-3" style="background-color: var(--colorLinkGreen);">
                                        <i class="fas fa-calendar-check"></i>
                                    </div>
                                    <div>
                                        <h6 class="text-muted mb-1">Đặt bàn trước</h6>
                                        <h3 class="mb-0"><?php echo number_format($todayBookings); ?></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Branch Info Card -->
             

                <!-- Quick Actions -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h4 class="mb-3">Thao tác nhanh</h4>
                    </div>
                    
                    <div class="col-lg-3 col-md-6 mb-3">
                        <a href="index.php?page=nhanvien&action=dashboard&section=bookings" class="quick-action d-block">
                            <div class="text-center">
                                <i class="fas fa-calendar-check fa-2x mb-2" style="color: var(--colorLinkGreen);"></i>
                                <h6>Quản lý đặt bàn</h6>
                                <small class="text-muted">Xem và xử lý các đơn đặt bàn</small>
                            </div>
                        </a>
                    </div>
                    
                    <div class="col-lg-3 col-md-6 mb-3">
                        <a href="index.php?page=menu" class="quick-action d-block">
                            <div class="text-center">
                                <i class="fas fa-utensils fa-2x mb-2" style="color: var(--colorYellow);"></i>
                                <h6>Xem Menu</h6>
                                <small class="text-muted">Danh sách món ăn của cơ sở</small>
                            </div>
                        </a>
                    </div>
                    
                    <div class="col-lg-3 col-md-6 mb-3">
                        <a href="index.php?page=branches" class="quick-action d-block">
                            <div class="text-center">
                                <i class="fas fa-map-marker-alt fa-2x mb-2" style="color: var(--colorPrimary);"></i>
                                <h6>Xem cơ sở</h6>
                                <small class="text-muted">Thông tin các cơ sở nhà hàng</small>
                            </div>
                        </a>
                    </div>
                    
                    <div class="col-lg-3 col-md-6 mb-3">
                        <a href="index.php" class="quick-action d-block">
                            <div class="text-center">
                                <i class="fas fa-home fa-2x mb-2" style="color: #E67E22;"></i>
                                <h6>Về trang chủ</h6>
                                <small class="text-muted">Quay về trang chủ website</small>
                            </div>
                        </a>
                    </div>
                </div>

            <?php elseif ($section === 'bookings'): ?>
                <!-- Bookings Content -->
                <?php include 'bookings_section.php'; ?>

            <?php endif; ?>
        </div>
    </main>

    <script>
        // Sidebar Toggle Functionality  
        const toggleDesktop = document.getElementById('sidebarToggleDesktop');
        const toggleMobile = document.getElementById('sidebarToggleMobile');
        const sidebar = document.querySelector('.sidebar');
        const mainContent = document.querySelector('.main-content');
        
        if (toggleDesktop) {
            const toggleIcon = toggleDesktop.querySelector('i');
            
            toggleDesktop.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('expanded');
                
                // Đổi icon
                if (sidebar.classList.contains('collapsed')) {
                    toggleIcon.className = 'fas fa-chevron-right';
                } else {
                    toggleIcon.className = 'fas fa-bars';
                }
                
                // Lưu trạng thái
                localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
            });
            
            // Khôi phục trạng thái khi load trang
            const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            if (isCollapsed) {
                sidebar.classList.add('collapsed');
                mainContent.classList.add('expanded');
                toggleIcon.className = 'fas fa-chevron-right';
            }
        }
        
        // Mobile sidebar toggle
        if (toggleMobile) {
            toggleMobile.addEventListener('click', function() {
                sidebar.classList.toggle('show');
            });
        }

        // Cập nhật thời gian thực
        function updateDateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('vi-VN');
            const dateString = now.toLocaleDateString('vi-VN');
            
            console.log(`Cập nhật lúc: ${dateString} ${timeString}`);
        }

        // Cập nhật mỗi phút
        setInterval(updateDateTime, 60000);
        
        // Hiệu ứng fade in cho các thẻ
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.stat-card, .quick-action');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });

        // Auto refresh cho trang bookings
        if (window.location.href.includes('section=bookings')) {
            setInterval(() => {
                // Reload trang mỗi 30 giây để cập nhật đơn đặt bàn mới
                if (document.visibilityState === 'visible') {
                    location.reload();
                }
            }, 30000);
        }
    </script>
</body>
</html>

<?php
// Đóng kết nối database
if ($conn) {
    mysqli_close($conn);
}
?>
