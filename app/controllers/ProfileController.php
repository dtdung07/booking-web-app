<?php

require_once __DIR__ . '/../models/User.php';

class ProfileController extends BaseController 
{
    private $userModel;
    private $authController;

    public function __construct() {
        $this->userModel = new User();
        
        require_once __DIR__ . '/AuthController.php';
        $this->authController = new AuthController();
        
        // Bắt đầu session nếu chưa có
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Yêu cầu đăng nhập
        $this->authController->requireAuth();
    }

    public function index() 
    {
        $user = $this->authController->getCurrentUser();
        
        $this->render('profile/index', [
            'title' => 'Hồ sơ cá nhân - Quán Nhậu Tự Do',
            'user' => $user
        ]);
    }
    
    public function changePassword() 
    {
        $user = $this->authController->getCurrentUser();
        
        $this->render('profile/change-password', [
            'title' => 'Đổi mật khẩu - Quán Nhậu Tự Do',
            'user' => $user
        ]);
    }
}
