<?php
require_once __DIR__ . '/../../config/database.php';

class MenuController extends BaseController 
{
    private $db;
    private $menuModel; 

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->menuModel = new MenuModel($this->db); 
    }
    public function index() 
    {
        // Lấy danh sách tất cả cơ sở để hiển thị trong modal
        $branches = $this->getBranches();
        
        $this->render('menu/menu', [
            'branches' => $branches
        ]);
    }
    
    public function show() 
    {
        $menuId = $_GET['id'] ?? null;
        $this->render('menu/show', ['menuId' => $menuId]);
    }
    
    public function category() 
    {
        $category = $_GET['category'] ?? 'all';
        $this->render('menu/category', ['category' => $category]);
    }

     public function menu2() 
    {
        // Lấy mã cơ sở
        $maCoSo = $_GET['coso'] ?? 21;
        $selectedCategory = $_GET['category'] ?? 'all';
        
        // === THAY ĐỔI: GỌI DỮ LIỆU TỪ MODEL ===
        $categories = $this->menuModel->findCategoriesByCoSo($maCoSo);
        $menuItems = $this->menuModel->findMenuItemsByCoSoAndCategory($maCoSo, $selectedCategory);
        $groupedMenuItems = $this->menuModel->findMenuItemsGroupedByCategory($maCoSo);
        
        // Script riêng cho menu2
        // $additional_scripts = '<script src="' . asset('js/menu2.js') . '"></script>';
        
        // Truyền dữ liệu cho View
        $this->render('menu2/menu2', [
            'categories' => $categories,
            'menuItems' => $menuItems,
            'groupedMenuItems' => $groupedMenuItems,
            'selectedCategory' => $selectedCategory,
            'maCoSo' => $maCoSo,
            // 'additional_scripts' => $additional_scripts
        ]);
    }
    
    // API endpoint để lấy dữ liệu JSON cho AJAX
    public function getMenuData() 
    {
        header('Content-Type: application/json');
        
        $maCoSo = $_GET['coso'] ?? 11;
        $category = $_GET['category'] ?? 'all';
        
        // === THAY ĐỔI: GỌI DỮ LIỆU TỪ MODEL ===
        if ($category === 'all') {
            $groupedMenuItems = $this->menuModel->findMenuItemsGroupedByCategory($maCoSo);
            echo json_encode([
                'success' => true,
                'data' => $groupedMenuItems,
                'type' => 'grouped'
            ]);
        } else {
            $menuItems = $this->menuModel->findMenuItemsByCoSoAndCategory($maCoSo, $category);
            echo json_encode([
                'success' => true,
                'data' => $menuItems,
                'type' => 'list'
            ]);
        }
    }
    
    /**
     * API: Trả về danh sách cơ sở dạng JSON cho dropdown global
     */
    public function branches()
    {
        header('Content-Type: application/json');
        try {
            $branches = $this->getBranches();
            echo json_encode([
                'success' => true,
                'data' => $branches
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Không thể tải danh sách cơ sở'
            ]);
        }
    }
    
    /**
     * Lấy danh sách tất cả cơ sở
     */
    private function getBranches() 
    {
        $sql = "SELECT MaCoSo, TenCoSo, DiaChi FROM coso WHERE TenCoSo != '' ORDER BY MaCoSo ASC";
        $result = mysqli_query($this->db, $sql);
        $branches = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $branches[] = $row;
        }
        return $branches;
    }
}