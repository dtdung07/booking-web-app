<?php

class AuthController extends BaseController 
{
    public function index() 
    {
        $this->render('auth/login');
    }
    
    public function login() 
    {
        $this->render('auth/login');
    }
    
    public function register() 
    {
        $this->render('auth/register');
    }
    
    public function authenticate() 
    {
        // Xử lý đăng nhập
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Logic xác thực ở đây
            $this->redirect('?page=home');
        }
    }
    
    public function logout() 
    {
        // Xử lý đăng xuất
        session_destroy();
        $this->redirect('?page=home');
    }
}