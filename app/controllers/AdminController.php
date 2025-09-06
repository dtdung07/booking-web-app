<?php

class AdminController extends BaseController 
{
    public function __construct() 
    {
        // Yêu cầu đăng nhập để truy cập admin
        if (!$this->isLoggedIn()) {
            $this->redirect('login.php');
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
        include __DIR__ . '/../views/admin/dashboard.php';
        exit;
    }
    
    // Kiểm tra user đã đăng nhập chưa
    private function isLoggedIn() {
        return isset($_SESSION['user']) && isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true;
    }
}
