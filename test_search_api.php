<?php
// File test để kiểm tra API search menu
session_start();

// Giả lập session cho test (thay đổi theo user thực tế)
$_SESSION['user'] = [
    'MaCoSo' => 1,
    'VaiTro' => 'nhanvien'
];

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

try {
    echo "Testing NhanVienController searchMenu method...\n\n";
    
    // Test 1: Kiểm tra class tồn tại
    if (class_exists('NhanVienController')) {
        echo "✓ NhanVienController class exists\n";
    } else {
        echo "✗ NhanVienController class not found\n";
        exit;
    }
    
    // Test 2: Tạo instance
    $controller = new NhanVienController();
    echo "✓ NhanVienController instance created\n";
    
    // Test 3: Kiểm tra method
    if (method_exists($controller, 'searchMenu')) {
        echo "✓ searchMenu method exists\n";
    } else {
        echo "✗ searchMenu method not found\n";
        exit;
    }
    
    // Test 4: Giả lập AJAX request
    $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
    $_GET['tenMon'] = 'gỏi';
    $_GET['page'] = 1;
    $_GET['limit'] = 5;
    
    echo "\nTesting with parameters:\n";
    echo "- tenMon: " . $_GET['tenMon'] . "\n";
    echo "- page: " . $_GET['page'] . "\n";
    echo "- limit: " . $_GET['limit'] . "\n\n";
    
    // Capture output
    ob_start();
    $controller->searchMenu();
    $output = ob_get_clean();
    
    echo "API Response:\n";
    echo $output . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
?>