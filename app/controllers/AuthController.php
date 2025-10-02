<?php

require_once __DIR__ . '/../../config/database.php'; 
require_once __DIR__ . '/../models/NhanVienModel.php'; 
require_once __DIR__ . '/../../includes/BaseController.php'; 

class AuthController extends BaseController 
{
    private $nhanVienModel;

    public function __construct() {
        // Khởi tạo kết nối DB và NhanVienModel
        $database = new Database();
        $db = $database->getConnection();
        $this->nhanVienModel = new NhanVienModel($db);
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Hiển thị form đăng nhập
    public function login()
    {
        if ($this->isLoggedIn()) {
            // Chuyển hướng dựa trên vai trò
            $user = $_SESSION['user'];
            if ($user['ChucVu'] === 'admin') {
                $this->redirect('index.php?page=admin&action=dashboard');
            } else if ($user['ChucVu'] === 'nhan_vien') {
                $this->redirect('index.php?page=nhanvien&action=dashboard&section=dashboard');
            } else {
                // Logout nếu vai trò không hợp lệ
                $this->logout();
            }
            return;
        }
        include __DIR__ . '/../../login.php';
        exit;
    }
    
    // Xử lý thông tin đăng nhập từ form
    public function authenticate() 
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?page=auth&action=login');
            return;
        }

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember']);

     
        if (empty($username) || empty($password)) {
            error_log("DEBUG LOGIN - Empty username or password");
            $_SESSION['error_message'] = 'Vui lòng nhập đầy đủ thông tin.';
            $this->redirect('index.php?page=auth&action=login');
            return;
        }

        // Sử dụng NhanVienModel để xác thực
        list($isSuccess, $nhanVienData) = $this->nhanVienModel->login($username, $password);

        if ($nhanVienData) {
            error_log("DEBUG LOGIN - User data: " . json_encode($nhanVienData));
        }

        if ($isSuccess) {
            // Đăng nhập thành công
            $_SESSION['user'] = $nhanVienData;
            $_SESSION['is_logged_in'] = true;
            
            if ($remember) {
                // Xử lý "Ghi nhớ đăng nhập" (tương tự logic cũ)
                $token = bin2hex(random_bytes(32));
                $expires = time() + (30 * 24 * 60 * 60); // 30 ngày
                setcookie('remember_token', $token, $expires, '/');
                setcookie('remember_user', $nhanVienData['MaNV'], $expires, '/');
            }
            
            // Phân quyền dựa trên ChucVu
            if ($nhanVienData['ChucVu'] === 'admin') {
                $this->redirect('index.php?page=admin&action=dashboard');
            } else if ($nhanVienData['ChucVu'] === 'nhan_vien') {
                $this->redirect('index.php?page=nhanvien&action=dashboard&section=dashboard');
            } else {
                // Trường hợp không xác định được vai trò
                $_SESSION['error_message'] = 'Vai trò không hợp lệ. Vui lòng liên hệ quản trị viên.';
                $this->redirect('index.php?page=auth&action=login');
            }
            return;
        }
        
        // Đăng nhập thất bại
        error_log("DEBUG LOGIN - Login failed, redirecting to login page");
        $_SESSION['error_message'] = 'Tên đăng nhập hoặc mật khẩu không chính xác.';
        $this->redirect('index.php?page=auth&action=login');
    }
    
    public function logout() 
    {
        // Xóa cookie
        if (isset($_COOKIE['remember_token'])) {
            setcookie('remember_token', '', time() - 3600, '/');
            setcookie('remember_user', '', time() - 3600, '/');
        }
        session_destroy();
        
        // Khởi tạo lại session để lưu thông báo
        session_start();
        $_SESSION['success_message'] = 'Đăng xuất thành công!';
        $this->redirect('index.php?page=auth&action=login');
    }

    public function isLoggedIn() {
        if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) {
            return true;
        }
        // Kiểm tra cookie "Ghi nhớ đăng nhập"
        if (isset($_COOKIE['remember_user']) && isset($_COOKIE['remember_token'])) {
            $maNV = $_COOKIE['remember_user'];
            if ($this->nhanVienModel->getById($maNV)) {
                $_SESSION['user'] = $this->nhanVienModel->toArray();
                $_SESSION['is_logged_in'] = true;
                return true;
            }
        }
        return false;
    }

    // Middleware yêu cầu đăng nhập
    public function requireAuth() {
        if (!$this->isLoggedIn()) {
            $_SESSION['error_message'] = 'Vui lòng đăng nhập để tiếp tục.';
            $this->redirect('index.php?page=auth&action=login');
            exit;
        }
    }

    // Middleware yêu cầu quyền admin
    public function requireAdmin() {
        $this->requireAuth();
        
        if (!isset($_SESSION['user']) || $_SESSION['user']['ChucVu'] !== 'admin') {
            $_SESSION['error_message'] = 'Bạn không có quyền truy cập trang này.';
            $this->redirect('index.php?page=auth&action=login');
            exit;
        }
    }

    // Middleware yêu cầu quyền nhân viên
    public function requireNhanVien() {
        $this->requireAuth();
        
        if (!isset($_SESSION['user']) || $_SESSION['user']['ChucVu'] !== 'nhan_vien') {
            $_SESSION['error_message'] = 'Bạn không có quyền truy cập trang này.';
            $this->redirect('index.php?page=auth&action=login');
            exit;
        }
    }

    // Kiểm tra xem user có phải admin không
    public function isAdmin() {
        return $this->isLoggedIn() && 
               isset($_SESSION['user']) && 
               $_SESSION['user']['ChucVu'] === 'admin';
    }

    // Kiểm tra xem user có phải nhân viên không
    public function isNhanVien() {
        return $this->isLoggedIn() && 
               isset($_SESSION['user']) && 
               $_SESSION['user']['ChucVu'] === 'nhan_vien';
    }
}