<?php
    // Include config.php để sử dụng hàm env() đọc file .env
    require_once __DIR__ . '/config.php';
    
    // Cấu hình database - Sử dụng hàm env() giống config.php
    $host = env('DB_HOST', 'localhost');
    $user = env('DB_USER', 'root');
    $pass = env('DB_PASS', '');
    $database = env('DB_NAME', 'booking_restaurant');
    $port = env('DB_PORT', '3306');

    // Kết nối database
    $conn = mysqli_connect($host, $user, $pass, $database, $port);
    
    // Kiểm tra kết nối trước khi thực hiện các thao tác khác
    if (!$conn) {
        die("Lỗi kết nối database: " . mysqli_connect_error());
    }
    
    // Thiết lập charset UTF-8
    mysqli_set_charset($conn, "utf8");
    
    // Thiết lập múi giờ cho MySQL connection
    mysqli_query($conn, "SET time_zone = '+07:00'");
?>