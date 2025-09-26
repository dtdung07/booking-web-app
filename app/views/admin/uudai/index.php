<?php
// Tải file kết nối CSDL (connect.php)
// File này đã được include (hoặc require) ở trang admin chính, nhưng include lại ở đây để đảm bảo cho các file khác như list.php dùng được
include __DIR__ . '/connect.php';

// Lấy hành động (action) từ URL. Mặc định là 'list'
$action = $_GET['action'] ?? 'list';
$uudaiId = $_GET['id'] ?? null; 

// Logic điều phối (Router)
switch ($action) {
    case 'process-create':
        include __DIR__ . '/process-create.php';
        break;
        
    case 'process-update':
        include __DIR__ . '/process-update.php';
        break;
        
    case 'process-delete':
        include __DIR__ . '/process-delete.php';
        break;
        
    case 'update': // Tải form chỉnh sửa (update.php sẽ include create.php nếu bạn dùng chung form)
        include __DIR__ . '/update.php';
        break;
        
    case 'list': // Tải danh sách ưu đãi (Đây là file sẽ hiển thị bảng)
    default:
        include __DIR__ . '/list.php'; 
        break;
}
?>