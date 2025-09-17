<?php

require_once __DIR__ . '/AuthController.php';

class AdminController extends BaseController 
{
    private $authController;

    public function __construct() 
    {
        $this->authController = new AuthController();
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    public function index() 
    {
        $this->dashboard();
    }
    
    public function dashboard() 
    {
        // Kiểm tra quyền admin
        $this->authController->requireAdmin();
        
        // Render admin dashboard độc lập (không sử dụng layout)
        include dirname(__DIR__) . '/views/admin/dashboard.php';
        exit;
    }
}
