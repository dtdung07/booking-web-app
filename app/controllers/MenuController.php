<?php
/**
 * Menu Controller - Xử lý thực đơn
 */

class MenuController extends BaseController {
    
    public function index() {
        $categories = $this->getCategories();
        $dishes = $this->getDishes();
        
        $data = [
            'title' => 'Thực đơn - ' . APP_NAME,
            'categories' => $categories,
            'dishes' => $dishes
        ];
        
        $this->render('menu/index', $data);
    }
    
    public function category() {
        $categoryId = $_GET['id'] ?? 0;
        $category = $this->getCategoryById($categoryId);
        
        if (!$category) {
            redirect('menu');
        }
        
        $dishes = $this->getDishesByCategory($categoryId);
        
        $data = [
            'title' => $category['name'] . ' - Thực đơn - ' . APP_NAME,
            'category' => $category,
            'dishes' => $dishes
        ];
        
        $this->render('menu/category', $data);
    }
    
    public function dish() {
        $dishId = $_GET['id'] ?? 0;
        $dish = $this->getDishById($dishId);
        
        if (!$dish) {
            redirect('menu');
        }
        
        $relatedDishes = $this->getRelatedDishes($dish['category_id'], $dishId);
        
        $data = [
            'title' => $dish['name'] . ' - Thực đơn - ' . APP_NAME,
            'dish' => $dish,
            'related_dishes' => $relatedDishes
        ];
        
        $this->render('menu/dish', $data);
    }
    
    public function search() {
        $keyword = $_GET['q'] ?? '';
        $categoryId = $_GET['category'] ?? 0;
        
        $dishes = $this->searchDishes($keyword, $categoryId);
        $categories = $this->getCategories();
        
        $data = [
            'title' => 'Tìm kiếm món ăn - ' . APP_NAME,
            'keyword' => $keyword,
            'category_id' => $categoryId,
            'dishes' => $dishes,
            'categories' => $categories
        ];
        
        $this->render('menu/search', $data);
    }
    
    private function getCategories() {
        try {
            $stmt = $this->db->prepare("
                SELECT * FROM categories 
                WHERE status = 'active' 
                ORDER BY sort_order ASC, name ASC
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }
    
    private function getDishes($limit = null) {
        try {
            $sql = "
                SELECT d.*, c.name as category_name 
                FROM dishes d
                LEFT JOIN categories c ON d.category_id = c.id
                WHERE d.status = 'active'
                ORDER BY d.is_featured DESC, d.name ASC
            ";
            
            if ($limit) {
                $sql .= " LIMIT " . (int)$limit;
            }
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }
    
    private function getCategoryById($id) {
        try {
            $stmt = $this->db->prepare("
                SELECT * FROM categories 
                WHERE id = ? AND status = 'active'
            ");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return null;
        }
    }
    
    private function getDishesByCategory($categoryId) {
        try {
            $stmt = $this->db->prepare("
                SELECT * FROM dishes 
                WHERE category_id = ? AND status = 'active'
                ORDER BY is_featured DESC, name ASC
            ");
            $stmt->execute([$categoryId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }
    
    private function getDishById($id) {
        try {
            $stmt = $this->db->prepare("
                SELECT d.*, c.name as category_name 
                FROM dishes d
                LEFT JOIN categories c ON d.category_id = c.id
                WHERE d.id = ? AND d.status = 'active'
            ");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return null;
        }
    }
    
    private function getRelatedDishes($categoryId, $excludeId, $limit = 4) {
        try {
            $stmt = $this->db->prepare("
                SELECT * FROM dishes 
                WHERE category_id = ? AND id != ? AND status = 'active'
                ORDER BY is_featured DESC, RAND()
                LIMIT ?
            ");
            $stmt->execute([$categoryId, $excludeId, $limit]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }
    
    private function searchDishes($keyword, $categoryId = 0) {
        try {
            $sql = "
                SELECT d.*, c.name as category_name 
                FROM dishes d
                LEFT JOIN categories c ON d.category_id = c.id
                WHERE d.status = 'active'
            ";
            $params = [];
            
            if (!empty($keyword)) {
                $sql .= " AND (d.name LIKE ? OR d.description LIKE ?)";
                $params[] = "%$keyword%";
                $params[] = "%$keyword%";
            }
            
            if ($categoryId > 0) {
                $sql .= " AND d.category_id = ?";
                $params[] = $categoryId;
            }
            
            $sql .= " ORDER BY d.is_featured DESC, d.name ASC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }
}
?>
