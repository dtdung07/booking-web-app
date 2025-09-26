<?php
// index.php
session_start();

// Define base path
define('BASE_PATH', __DIR__);
define('APP_PATH', BASE_PATH . '/app');

// Auto loader đơn giản
spl_autoload_register(function ($className) {
    $file = APP_PATH . '/controllers/' . $className . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Kết nối database
require_once APP_PATH . '/config/database.php';

// Xử lý routing đơn giản
$page = $_GET['page'] ?? 'home';

switch ($page) {
    case 'uudai':
        $controller = new UuDaiController();
        $controller->index();
        break;
        
    case 'uudai_create':
        $controller = new UuDaiController();
        $controller->create();
        break;
        
    case 'uudai_edit':
        $controller = new UuDaiController();
        $controller->edit();
        break;
        
    case 'uudai_store':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new UuDaiController();
            $controller->store();
        }
        break;
        
    case 'uudai_update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new UuDaiController();
            $controller->update();
        }
        break;
        
    case 'uudai_delete':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new UuDaiController();
            $controller->delete();
        }
        break;
        
    default:
        // Trang mặc định - có thể là trang chủ hoặc login
        if (file_exists(APP_PATH . "/views/{$page}.php")) {
            require_once APP_PATH . "/views/{$page}.php";
        } else {
            echo "Trang không tồn tại!";
        }
        break;
}
?>