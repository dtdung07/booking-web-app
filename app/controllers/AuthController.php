<?php
/**
 * Auth Controller - Xử lý đăng nhập/đăng ký
 */

class AuthController extends BaseController {
    
    public function login() {
        if ($this->isLoggedIn()) {
            redirect('');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processLogin();
        } else {
            $data = [
                'title' => 'Đăng nhập - ' . APP_NAME
            ];
            $this->render('auth/login', $data);
        }
    }
    
    public function register() {
        if ($this->isLoggedIn()) {
            redirect('');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processRegister();
        } else {
            $data = [
                'title' => 'Đăng ký - ' . APP_NAME
            ];
            $this->render('auth/register', $data);
        }
    }
    
    public function logout() {
        session_destroy();
        redirect('');
    }
    
    public function profile() {
        $this->requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->updateProfile();
        } else {
            $data = [
                'title' => 'Thông tin cá nhân - ' . APP_NAME,
                'user' => $this->getCurrentUser()
            ];
            $this->render('auth/profile', $data);
        }
    }
    
    private function processLogin() {
        try {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            
            if (empty($email) || empty($password)) {
                throw new Exception('Vui lòng điền đầy đủ thông tin!');
            }
            
            $user = $this->getUserByEmail($email);
            
            if (!$user || !password_verify($password, $user['password'])) {
                throw new Exception('Email hoặc mật khẩu không đúng!');
            }
            
            if ($user['status'] !== 'active') {
                throw new Exception('Tài khoản đã bị khóa!');
            }
            
            // Đăng nhập thành công
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_data'] = [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'phone' => $user['phone'],
                'role' => $user['role']
            ];
            
            // Cập nhật last login
            $this->updateLastLogin($user['id']);
            
            $_SESSION['message'] = 'Đăng nhập thành công!';
            $_SESSION['message_type'] = 'success';
            
            $redirect = $_GET['redirect'] ?? '';
            redirect($redirect ?: '');
            
        } catch (Exception $e) {
            $_SESSION['message'] = $e->getMessage();
            $_SESSION['message_type'] = 'error';
            redirect('auth/login');
        }
    }
    
    private function processRegister() {
        try {
            $data = [
                'name' => $_POST['name'] ?? '',
                'email' => $_POST['email'] ?? '',
                'phone' => $_POST['phone'] ?? '',
                'password' => $_POST['password'] ?? '',
                'confirm_password' => $_POST['confirm_password'] ?? ''
            ];
            
            // Validation
            if (empty($data['name']) || empty($data['email']) || 
                empty($data['phone']) || empty($data['password'])) {
                throw new Exception('Vui lòng điền đầy đủ thông tin!');
            }
            
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Email không hợp lệ!');
            }
            
            if (strlen($data['password']) < 6) {
                throw new Exception('Mật khẩu phải có ít nhất 6 ký tự!');
            }
            
            if ($data['password'] !== $data['confirm_password']) {
                throw new Exception('Mật khẩu xác nhận không khớp!');
            }
            
            // Kiểm tra email đã tồn tại
            if ($this->getUserByEmail($data['email'])) {
                throw new Exception('Email đã được sử dụng!');
            }
            
            // Tạo tài khoản
            $userId = $this->createUser($data);
            
            if ($userId) {
                $_SESSION['message'] = 'Đăng ký thành công! Vui lòng đăng nhập.';
                $_SESSION['message_type'] = 'success';
                redirect('auth/login');
            } else {
                throw new Exception('Có lỗi xảy ra khi tạo tài khoản!');
            }
            
        } catch (Exception $e) {
            $_SESSION['message'] = $e->getMessage();
            $_SESSION['message_type'] = 'error';
            redirect('auth/register');
        }
    }
    
    private function updateProfile() {
        try {
            $user = $this->getCurrentUser();
            $data = [
                'name' => $_POST['name'] ?? '',
                'phone' => $_POST['phone'] ?? '',
                'current_password' => $_POST['current_password'] ?? '',
                'new_password' => $_POST['new_password'] ?? '',
                'confirm_password' => $_POST['confirm_password'] ?? ''
            ];
            
            if (empty($data['name']) || empty($data['phone'])) {
                throw new Exception('Vui lòng điền đầy đủ thông tin!');
            }
            
            // Nếu muốn đổi mật khẩu
            if (!empty($data['new_password'])) {
                if (empty($data['current_password'])) {
                    throw new Exception('Vui lòng nhập mật khẩu hiện tại!');
                }
                
                $currentUser = $this->getUserById($user['id']);
                if (!password_verify($data['current_password'], $currentUser['password'])) {
                    throw new Exception('Mật khẩu hiện tại không đúng!');
                }
                
                if (strlen($data['new_password']) < 6) {
                    throw new Exception('Mật khẩu mới phải có ít nhất 6 ký tự!');
                }
                
                if ($data['new_password'] !== $data['confirm_password']) {
                    throw new Exception('Mật khẩu xác nhận không khớp!');
                }
            }
            
            // Cập nhật thông tin
            if ($this->updateUserProfile($user['id'], $data)) {
                // Cập nhật session
                $_SESSION['user_data']['name'] = $data['name'];
                $_SESSION['user_data']['phone'] = $data['phone'];
                
                $_SESSION['message'] = 'Cập nhật thông tin thành công!';
                $_SESSION['message_type'] = 'success';
            } else {
                throw new Exception('Có lỗi xảy ra khi cập nhật!');
            }
            
        } catch (Exception $e) {
            $_SESSION['message'] = $e->getMessage();
            $_SESSION['message_type'] = 'error';
        }
        
        redirect('auth/profile');
    }
    
    private function getUserByEmail($email) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return null;
        }
    }
    
    private function getUserById($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return null;
        }
    }
    
    private function createUser($data) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO users (name, email, phone, password, role, status, created_at) 
                VALUES (?, ?, ?, ?, 'customer', 'active', NOW())
            ");
            
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
            
            $stmt->execute([
                $data['name'],
                $data['email'],
                $data['phone'],
                $hashedPassword
            ]);
            
            return $this->db->lastInsertId();
        } catch (Exception $e) {
            return false;
        }
    }
    
    private function updateLastLogin($userId) {
        try {
            $stmt = $this->db->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
            $stmt->execute([$userId]);
        } catch (Exception $e) {
            // Log error
        }
    }
    
    private function updateUserProfile($userId, $data) {
        try {
            if (!empty($data['new_password'])) {
                $stmt = $this->db->prepare("
                    UPDATE users 
                    SET name = ?, phone = ?, password = ?, updated_at = NOW() 
                    WHERE id = ?
                ");
                $hashedPassword = password_hash($data['new_password'], PASSWORD_DEFAULT);
                $stmt->execute([$data['name'], $data['phone'], $hashedPassword, $userId]);
            } else {
                $stmt = $this->db->prepare("
                    UPDATE users 
                    SET name = ?, phone = ?, updated_at = NOW() 
                    WHERE id = ?
                ");
                $stmt->execute([$data['name'], $data['phone'], $userId]);
            }
            
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            return false;
        }
    }
}
?>
