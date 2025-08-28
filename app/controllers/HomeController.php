<?php
/**
 * Home Controller - Xử lý trang chủ
 */

class HomeController extends BaseController {
    
    public function index() {
        $data = [
            'title' => 'Trang chủ - ' . APP_NAME,
            'featured_dishes' => $this->getFeaturedDishes(),
            'restaurant_info' => $this->getRestaurantInfo()
        ];
        
        $this->render('home/index', $data);
    }
    
    public function about() {
        $data = [
            'title' => 'Giới thiệu - ' . APP_NAME
        ];
        
        $this->render('home/about', $data);
    }
    
    public function notFound() {
        http_response_code(404);
        $data = [
            'title' => 'Trang không tồn tại - ' . APP_NAME
        ];
        
        $this->render('errors/404', $data);
    }
    
    private function getFeaturedDishes() {
        try {
            $stmt = $this->db->prepare("
                SELECT * FROM dishes 
                WHERE is_featured = 1 AND status = 'active' 
                ORDER BY created_at DESC 
                LIMIT 6
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }
    
    private function getRestaurantInfo() {
        return [
            'name' => 'Nhà hàng ABC',
            'address' => '123 Đường ABC, Quận 1, TP.HCM',
            'phone' => '028 3xxx xxxx',
            'email' => 'info@restaurant.com',
            'opening_hours' => '10:00 - 22:00',
            'description' => 'Nhà hàng chuyên phục vụ các món ăn truyền thống Việt Nam với không gian ấm cúng.'
        ];
    }
}
?>
