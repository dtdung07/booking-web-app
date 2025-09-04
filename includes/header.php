<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quán Nhậu Tự Do - Website Đặt Bàn</title>
    <link rel="stylesheet" href="../public/css/style-menu.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Điều hướng -->
    <header class="header">
        <!-- Điều hướng main -->
        <nav class="main-nav">
            <div class="container">
                <div class="nav-brand">
                    <div class="logo">
                        <span class="star">★</span>
                        <span class="brand-text">
                            QUÁN<br>
                            NHẬU <span class="highlight">TỰ DO</span>
                        </span>
                    </div>
                    <div class="hotline">
                        <span class="hotline-label">HOTLINE</span>
                        <span class="hotline-number">0987654321</span>
                    </div>
                </div>
                
                <!-- Thanh menu -->
                <div class="nav-menu">
                    <!-- Trang chủ -->
                    <a href="index.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">TRANG CHỦ</a>
                    <!-- Thực đơn -->
                    <a href="menu.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'menu.php' ? 'active' : ''; ?>">THỰC ĐƠN</a>
                    <!-- Cơ sở -->
                    <a href="locations.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'locations.php' ? 'active' : ''; ?>">CƠ SỞ</a>
                    <!-- Ưu đãi -->
                    <a href="promotions.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'promotions.php' ? 'active' : ''; ?>">ƯU ĐÃI</a>
                    <!-- Liên hệ -->
                    <a href="contact.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'active' : ''; ?>">LIÊN HỆ</a>
                </div>
                
                <button class="booking-btn" onclick="openBookingModal()">ĐẶT BÀN</button>
            </div>
        </nav>
    </header>