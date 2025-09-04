<?php

// Kiểm tra và include User model
$userModelPath = __DIR__ . '/../models/User.php';
if (file_exists($userModelPath)) {
    require_once $userModelPath;
} else {
    throw new Exception("User model not found at: " . $userModelPath);
}

class AuthController extends BaseController 
{
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
        
        // Bắt đầu session nếu chưa có
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function index() 
    {
        // Nếu đã đăng nhập, chuyển hướng về trang chủ
        if ($this->isLoggedIn()) {
            $this->redirect('?page=home');
            return;
        }
        
        // Render trang đăng nhập admin độc lập (không sử dụng layout)
        include __DIR__ . '/../views/auth/admin-login.php';
        exit;
    }
    
    public function login() 
    {
        // Nếu đã đăng nhập, chuyển hướng về trang chủ
        if ($this->isLoggedIn()) {
            $this->redirect('?page=home');
            return;
        }
        
        // Render trang đăng nhập admin độc lập (không sử dụng layout)
        include __DIR__ . '/../views/auth/admin-login.php';
        exit;
    }
    
    public function authenticate() 
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?page=auth&action=login');
            return;
        }

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $remember_me = isset($_POST['remember_me']);

        // Validate input
        if (empty($username) || empty($password)) {
            $_SESSION['error'] = 'Vui lòng nhập đầy đủ thông tin đăng nhập.';
            $this->redirect('?page=auth&action=login');
            return;
        }

        // Tìm user trong database
        if ($this->userModel->findByUsername($username)) {
            // Kiểm tra mật khẩu
            if ($this->userModel->verifyPassword($password)) {
                // Đăng nhập thành công
                $_SESSION['user'] = $this->userModel->toArray();
                $_SESSION['user']['branch_name'] = $this->userModel->getBranchName();
                $_SESSION['success'] = 'Đăng nhập thành công!';
                
                // Xử lý remember me
                if ($remember_me) {
                    $this->setRememberMeCookie();
                }
                
                // Chuyển hướng đến admin dashboard
                $this->redirect('?page=admin&action=dashboard');
                return;
            }
        }
        
        // Đăng nhập thất bại
        $_SESSION['error'] = 'Tên đăng nhập hoặc mật khẩu không chính xác.';
        $_SESSION['old_input'] = ['username' => $username];
        $this->redirect('?page=auth&action=login');
    }
    
    public function logout() 
    {
        // Xóa remember me cookie
        if (isset($_COOKIE['remember_me'])) {
            setcookie('remember_me', '', time() - 3600, '/');
        }
        
        // Xóa session
        session_destroy();
        
        // Khởi tạo session mới để hiện thông báo
        session_start();
        $_SESSION['success'] = 'Đăng xuất thành công!';
        $this->redirect('?page=home');
    }

    // Kiểm tra user đã đăng nhập chưa
    public function isLoggedIn() {
        if (isset($_SESSION['user'])) {
            return true;
        }
        
        // Kiểm tra remember me cookie
        if (isset($_COOKIE['remember_me'])) {
            $user_id = $this->validateRememberMeCookie($_COOKIE['remember_me']);
            if ($user_id && $this->userModel->findById($user_id)) {
                $_SESSION['user'] = $this->userModel->toArray();
                $_SESSION['user']['branch_name'] = $this->userModel->getBranchName();
                return true;
            }
        }
        
        return false;
    }

    // Lấy thông tin user hiện tại
    public function getCurrentUser() {
        if ($this->isLoggedIn()) {
            return $_SESSION['user'];
        }
        return null;
    }

    // Tạo remember me cookie
    private function setRememberMeCookie() {
        $token = bin2hex(random_bytes(32));
        $expires = time() + (30 * 24 * 60 * 60); // 30 ngày
        
        setcookie('remember_me', $token, $expires, '/');
        
        // Lưu token vào session (đơn giản hóa)
        $_SESSION['remember_tokens'][$_SESSION['user']['id']] = $token;
    }

    // Validate remember me cookie
    private function validateRememberMeCookie($token) {
        // Kiểm tra token (đơn giản hóa)
        if (isset($_SESSION['remember_tokens'])) {
            foreach ($_SESSION['remember_tokens'] as $user_id => $stored_token) {
                if ($stored_token === $token) {
                    return $user_id;
                }
            }
        }
        return false;
    }

    // Middleware để yêu cầu đăng nhập
    public function requireAuth() {
        if (!$this->isLoggedIn()) {
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
            $_SESSION['error'] = 'Vui lòng đăng nhập để tiếp tục.';
            $this->redirect('?page=auth&action=login');
            exit;
        }
    }

    // Middleware để yêu cầu quyền admin
    public function requireAdmin() {
        $this->requireAuth();
        $user = $this->getCurrentUser();
        if ($user['role'] !== 'admin') {
            $_SESSION['error'] = 'Bạn không có quyền truy cập vào trang này.';
            $this->redirect('?page=home');
            exit;
        }
    }

    // Đổi mật khẩu
    public function changePassword() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = 'Phương thức không được phép.';
            $this->redirect('?page=profile');
            return;
        }

        $current_password = $_POST['current_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        // Validate input
        $errors = [];
        
        if (empty($current_password)) {
            $errors[] = 'Vui lòng nhập mật khẩu hiện tại.';
        }

        if (empty($new_password)) {
            $errors[] = 'Vui lòng nhập mật khẩu mới.';
        } elseif (strlen($new_password) < 6) {
            $errors[] = 'Mật khẩu mới phải có ít nhất 6 ký tự.';
        }

        if ($new_password !== $confirm_password) {
            $errors[] = 'Xác nhận mật khẩu không khớp.';
        }

        // Kiểm tra mật khẩu hiện tại
        $user = $this->getCurrentUser();
        $this->userModel->findById($user['id']);
        if (!$this->userModel->verifyPassword($current_password)) {
            $errors[] = 'Mật khẩu hiện tại không chính xác.';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $this->redirect('?page=profile&action=changePassword');
            return;
        }

        // Cập nhật mật khẩu
        if ($this->userModel->updatePasswordHash($new_password)) {
            $_SESSION['success'] = 'Đổi mật khẩu thành công!';
            $this->redirect('?page=profile');
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi đổi mật khẩu. Vui lòng thử lại.';
            $this->redirect('?page=profile&action=changePassword');
        }
    }
}