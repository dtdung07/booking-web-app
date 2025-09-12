<?php
/**
 * File điểm vào chính của ứng dụng
 */

// Bắt đầu session
session_start();

// Bao gồm file cấu hình
require_once 'config/config.php';

// Autoload classes
spl_autoload_register(function($class) {
    $paths = [
        'app/controllers/',
        'app/models/',
        'includes/'
    ];
    
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            break;
        }
    }
});

// Lấy tham số từ URL
$request = $_GET['page'] ?? 'home';
$action = $_GET['action'] ?? 'index';

// Routing đơn giản
switch ($request) {
    case 'home':
        $controller = new HomeController();
        break;
        
    case 'booking':
        $controller = new BookingController();
        break;
        
    case 'auth':
        $controller = new AuthController();
        break;
        
    case 'menu':
        $controller = new MenuController();
        break;
        
    case 'menu2':
        $controller = new MenuController();
        $action = 'menu2';
        break;
        
    case 'contact':
        $controller = new ContactController();
        break;
        
    case 'branches':
        $controller = new BranchController();
        break;
        
    case 'admin':
        $controller = new AdminController();
        break;
    
    default:
        $controller = new HomeController();
        $action = 'notFound';
}

// Gọi phương thức tương ứng
if (method_exists($controller, $action)) {
    $controller->$action();
} else {
    $controller->index();
}
?>
