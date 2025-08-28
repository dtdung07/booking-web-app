<?php
/**
 * Contact Controller - Xử lý liên hệ
 */

class ContactController extends BaseController {
    
    public function index() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processContact();
        } else {
            $data = [
                'title' => 'Liên hệ - ' . APP_NAME,
                'restaurant_info' => $this->getRestaurantInfo()
            ];
            $this->render('contact/index', $data);
        }
    }
    
    private function processContact() {
        try {
            $data = [
                'name' => $_POST['name'] ?? '',
                'email' => $_POST['email'] ?? '',
                'phone' => $_POST['phone'] ?? '',
                'subject' => $_POST['subject'] ?? '',
                'message' => $_POST['message'] ?? ''
            ];
            
            // Validation
            if (empty($data['name']) || empty($data['email']) || 
                empty($data['subject']) || empty($data['message'])) {
                throw new Exception('Vui lòng điền đầy đủ thông tin!');
            }
            
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Email không hợp lệ!');
            }
            
            // Lưu vào database
            if ($this->saveContact($data)) {
                $_SESSION['message'] = 'Gửi liên hệ thành công! Chúng tôi sẽ phản hồi sớm nhất.';
                $_SESSION['message_type'] = 'success';
            } else {
                throw new Exception('Có lỗi xảy ra khi gửi liên hệ!');
            }
            
        } catch (Exception $e) {
            $_SESSION['message'] = $e->getMessage();
            $_SESSION['message_type'] = 'error';
        }
        
        redirect('contact');
    }
    
    private function saveContact($data) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO contacts (name, email, phone, subject, message, status, created_at) 
                VALUES (?, ?, ?, ?, ?, 'new', NOW())
            ");
            
            $stmt->execute([
                $data['name'],
                $data['email'],
                $data['phone'],
                $data['subject'],
                $data['message']
            ]);
            
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    
    private function getRestaurantInfo() {
        return [
            'name' => 'Nhà hàng ABC',
            'address' => '123 Đường ABC, Quận 1, TP.HCM',
            'phone' => '028 3xxx xxxx',
            'email' => 'info@restaurant.com',
            'opening_hours' => '10:00 - 22:00',
            'map_embed' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.4609026767023!2d106.69957931472074!3d10.774932092320236!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752f38f9ed887b%3A0x14aded5703768989!2zUXXhuq1uIDEsIFRow6BuaCBwaOG7kSBI4buTIENow60gTWluaCwgVmnhu4d0IE5hbQ!5e0!3m2!1svi!2s!4v1631234567890!5m2!1svi!2s'
        ];
    }
}
?>
