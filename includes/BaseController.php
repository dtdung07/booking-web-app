<?php
/**
 * Base Controller - Lớp cha cho tất cả controllers
 */

class BaseController {
    protected $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }
    
    protected function render($view, $data = []) {
        extract($data);
        
        // Bao gồm header
        include APP_PATH . '/views/layouts/header.php';
        
        // Bao gồm view chính
        $viewFile = APP_PATH . '/views/' . $view . '.php';
        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            echo "<p>View không tồn tại: $view</p>";
        }
        
        // Bao gồm footer
        include APP_PATH . '/views/layouts/footer.php';
    }
    
    protected function renderPartial($view, $data = []) {
        extract($data);
        $viewFile = APP_PATH . '/views/' . $view . '.php';
        
        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            echo "<p>View không tồn tại: $view</p>";
        }
    }
    
    protected function json($data) {
        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit();
    }
    
    protected function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
    
    protected function getCurrentUser() {
        if ($this->isLoggedIn()) {
            return $_SESSION['user_data'] ?? null;
        }
        return null;
    }
    
    protected function requireLogin() {
        if (!$this->isLoggedIn()) {
            redirect('auth/login');
        }
    }
}
?>
