<?php
/**
 * Cấu hình chung của ứng dụng
 */

// Khởi động session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Cấu hình múi giờ
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Cấu hình đường dẫn
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('CONFIG_PATH', ROOT_PATH . '/config');

// Cấu hình URL
define('BASE_URL', 'https://fisher-jackets-exchange-execute.trycloudflare.com/booking-web-app');
define('ASSETS_URL', BASE_URL . '/public');

// Cấu hình ứng dụng
define('APP_NAME', 'Hệ thống đặt bàn nhà hàng');
define('APP_VERSION', '1.0.0');

// Cấu hình email (nếu cần)
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'your-email@gmail.com');
define('SMTP_PASSWORD', 'your-password');

// Bao gồm file database
require_once CONFIG_PATH . '/database.php';

// Hàm helper
function redirect($url) {
    header("Location: " . BASE_URL . "/" . ltrim($url, '/'));
    exit();
}

function asset($path) {
    return ASSETS_URL . "/" . ltrim($path, '/');
}

function url($path = '') {
    return BASE_URL . "/" . ltrim($path, '/');
}

function isActivePage($page) {
    $currentPage = $_GET['page'] ?? 'home';
    return $currentPage === $page ? 'active' : '';
}

function view($view, $data = []) {
    extract($data);
    $viewFile = APP_PATH . '/views/' . $view . '.php';
    
    if (file_exists($viewFile)) {
        include $viewFile;
    } else {
        echo "View không tồn tại: " . $view;
    }
}
?>
