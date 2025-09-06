<?php

class AdminController extends BaseController 
{
    public function __construct() 
    {
        // Yêu cầu đăng nhập để truy cập admin
        if (!$this->isLoggedIn()) {
            $this->redirect('?page=auth&action=login');
            return;
        }
    }
    
    public function index() 
    {
        $this->dashboard();
    }
    
    public function dashboard() 
    {
        // Render admin dashboard độc lập (không sử dụng layout)
        include dirname(__DIR__) . '/views/admin/test.php';
        exit;
    }
    
    // Kiểm tra user đã đăng nhập chưa
    private function isLoggedIn() {
        return isset($_SESSION['user']) && isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true;
    }
    
    // Chuyển hướng
    public function redirect($url) {
        header("Location: $url");
        exit;
    }
}
