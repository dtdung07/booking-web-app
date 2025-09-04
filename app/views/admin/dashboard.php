<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Quán Nhậu Tự Do</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #333;
        }

        .admin-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(31, 38, 135, 0.37);
            border-right: 1px solid rgba(255, 255, 255, 0.18);
            padding: 2rem 0;
        }

        .logo {
            text-align: center;
            padding-bottom: 2rem;
            border-bottom: 1px solid #e0e0e0;
            margin-bottom: 2rem;
        }

        .logo h1 {
            color: #667eea;
            font-size: 1.5rem;
            font-weight: 700;
        }

        .nav-menu {
            list-style: none;
        }

        .nav-item {
            margin-bottom: 0.5rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 1rem 2rem;
            color: #333;
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .nav-link:hover {
            background: rgba(102, 126, 234, 0.1);
            border-left-color: #667eea;
        }

        .nav-link.active {
            background: rgba(102, 126, 234, 0.15);
            border-left-color: #667eea;
            color: #667eea;
        }

        .nav-link i {
            margin-right: 0.75rem;
            width: 20px;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 2rem;
        }

        .header {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            padding: 1rem 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .header h2 {
            color: #333;
            font-size: 1.5rem;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }

        .logout-btn {
            background: #dc3545;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
            text-decoration: none;
        }

        .logout-btn:hover {
            background: #c82333;
        }

        /* Dashboard Cards */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .dashboard-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
        }

        .card-header {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }

        .card-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            margin-right: 1rem;
        }

        .card-title {
            font-size: 1rem;
            color: #666;
            margin-bottom: 0.25rem;
        }

        .card-value {
            font-size: 2rem;
            font-weight: bold;
            color: #333;
        }

        /* Quick Actions */
        .quick-actions {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .quick-actions h3 {
            margin-bottom: 1rem;
            color: #333;
        }

        .action-buttons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .action-btn {
            display: flex;
            align-items: center;
            padding: 1rem;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: transform 0.3s ease;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            color: white;
        }

        .action-btn i {
            margin-right: 0.75rem;
            font-size: 1.2rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .admin-container {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                padding: 1rem 0;
            }

            .nav-menu {
                display: flex;
                overflow-x: auto;
                padding: 0 1rem;
            }

            .nav-item {
                margin-right: 1rem;
                margin-bottom: 0;
                flex-shrink: 0;
            }

            .nav-link {
                padding: 0.75rem 1rem;
                border-left: none;
                border-bottom: 3px solid transparent;
                white-space: nowrap;
            }

            .nav-link:hover,
            .nav-link.active {
                border-left: none;
                border-bottom-color: #667eea;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo">
                <h1>Admin Panel</h1>
            </div>
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="?page=admin&action=dashboard" class="nav-link active">
                        <i class="fas fa-tachometer-alt"></i>
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="?page=booking" class="nav-link">
                        <i class="fas fa-calendar-check"></i>
                        Đặt Bàn
                    </a>
                </li>
                <li class="nav-item">
                    <a href="?page=branches" class="nav-link">
                        <i class="fas fa-store"></i>
                        Chi Nhánh
                    </a>
                </li>
                <li class="nav-item">
                    <a href="?page=menu" class="nav-link">
                        <i class="fas fa-utensils"></i>
                        Thực Đơn
                    </a>
                </li>
                <li class="nav-item">
                    <a href="?page=auth&action=profile" class="nav-link">
                        <i class="fas fa-user"></i>
                        Hồ Sơ
                    </a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            <div class="header">
                <h2>Dashboard</h2>
                <div class="user-info">
                    <div class="user-avatar">
                        <?php echo strtoupper(substr($_SESSION['user']['ho_ten'], 0, 1)); ?>
                    </div>
                    <span>Xin chào, <?php echo $_SESSION['user']['ho_ten']; ?></span>
                    <a href="?page=auth&action=logout" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i> Đăng xuất
                    </a>
                </div>
            </div>

            <!-- Dashboard Cards -->
            <div class="dashboard-grid">
                <div class="dashboard-card">
                    <div class="card-header">
                        <div class="card-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div>
                            <div class="card-title">Đặt bàn hôm nay</div>
                            <div class="card-value">15</div>
                        </div>
                    </div>
                </div>

                <div class="dashboard-card">
                    <div class="card-header">
                        <div class="card-icon">
                            <i class="fas fa-store"></i>
                        </div>
                        <div>
                            <div class="card-title">Chi nhánh</div>
                            <div class="card-value">3</div>
                        </div>
                    </div>
                </div>

                <div class="dashboard-card">
                    <div class="card-header">
                        <div class="card-icon">
                            <i class="fas fa-utensils"></i>
                        </div>
                        <div>
                            <div class="card-title">Món ăn</div>
                            <div class="card-value">45</div>
                        </div>
                    </div>
                </div>

                <div class="dashboard-card">
                    <div class="card-header">
                        <div class="card-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div>
                            <div class="card-title">Nhân viên</div>
                            <div class="card-value">8</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="quick-actions">
                <h3>Thao tác nhanh</h3>
                <div class="action-buttons">
                    <a href="?page=booking&action=create" class="action-btn">
                        <i class="fas fa-plus"></i>
                        Thêm đặt bàn mới
                    </a>
                    <a href="?page=branches&action=create" class="action-btn">
                        <i class="fas fa-store"></i>
                        Thêm chi nhánh
                    </a>
                    <a href="?page=menu&action=create" class="action-btn">
                        <i class="fas fa-utensils"></i>
                        Thêm món ăn
                    </a>
                    <a href="?page=auth&action=change_password" class="action-btn">
                        <i class="fas fa-key"></i>
                        Đổi mật khẩu
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Active menu highlighting
        document.addEventListener('DOMContentLoaded', function() {
            const navLinks = document.querySelectorAll('.nav-link');
            const currentPage = new URLSearchParams(window.location.search).get('page');
            
            navLinks.forEach(link => {
                const linkPage = new URL(link.href).searchParams.get('page');
                if (linkPage === currentPage) {
                    link.classList.add('active');
                } else {
                    link.classList.remove('active');
                }
            });
        });
    </script>
</body>
</html>
