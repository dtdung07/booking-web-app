<?php
require_once __DIR__ . '/../../config/database.php';

class MenuController extends BaseController 
{
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
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
        // Lấy mã cơ sở (mặc định là 11, có thể lấy từ session hoặc parameter)
        $maCoSo = $_GET['coso'] ?? 21;
        
        // Lấy danh sách các danh mục có món ăn trong cơ sở
        $categories = $this->getCategories($maCoSo);
        
        // Lấy danh sách món ăn theo danh mục
        $selectedCategory = $_GET['category'] ?? 'all';
        $menuItems = $this->getMenuItems($maCoSo, $selectedCategory);
        
        // Lấy món ăn nhóm theo danh mục cho tab "Tất Cả"
        $groupedMenuItems = $this->getMenuItemsGroupedByCategory($maCoSo);
        
        // Script riêng cho menu2
        $additional_scripts = '<script src="' . asset('js/menu2.js') . '"></script>';
        
        $this->render('menu2/menu2', [
            'categories' => $categories,
            'menuItems' => $menuItems,
            'groupedMenuItems' => $groupedMenuItems,
            'selectedCategory' => $selectedCategory,
            'maCoSo' => $maCoSo,
            'additional_scripts' => $additional_scripts
        ]);
    }
    
    private function getCategories($maCoSo) 
    {
        $sql = "SELECT DISTINCT dm.MaDM, dm.TenDM
                FROM menu_coso mc
                JOIN monan m ON mc.MaMon = m.MaMon
                JOIN danhmuc dm ON m.MaDM = dm.MaDM
                WHERE mc.MaCoSo = :maCoSo
                ORDER BY dm.MaDM";
                
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':maCoSo', $maCoSo);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    private function getMenuItems($maCoSo, $selectedCategory = 'all') 
    {
        if ($selectedCategory === 'all') {
            $sql = "SELECT m.MaMon, m.TenMon, m.MoTa, m.HinhAnhURL, mc.Gia, dm.TenDM, dm.MaDM
                    FROM menu_coso mc
                    JOIN monan m ON mc.MaMon = m.MaMon
                    JOIN danhmuc dm ON m.MaDM = dm.MaDM
                    WHERE mc.MaCoSo = :maCoSo AND mc.TinhTrang = 'con_hang'
                    ORDER BY dm.MaDM, m.TenMon";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':maCoSo', $maCoSo);
        } else {
            $sql = "SELECT m.MaMon, m.TenMon, m.MoTa, m.HinhAnhURL, mc.Gia, dm.TenDM, dm.MaDM
                    FROM menu_coso mc
                    JOIN monan m ON mc.MaMon = m.MaMon
                    JOIN danhmuc dm ON m.MaDM = dm.MaDM
                    WHERE mc.MaCoSo = :maCoSo AND dm.MaDM = :category AND mc.TinhTrang = 'con_hang'
                    ORDER BY m.TenMon";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':maCoSo', $maCoSo);
            $stmt->bindParam(':category', $selectedCategory);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    private function getMenuItemsGroupedByCategory($maCoSo) 
    {
        $sql = "SELECT m.MaMon, m.TenMon, m.MoTa, m.HinhAnhURL, mc.Gia, dm.TenDM, dm.MaDM
                FROM menu_coso mc
                JOIN monan m ON mc.MaMon = m.MaMon
                JOIN danhmuc dm ON m.MaDM = dm.MaDM
                WHERE mc.MaCoSo = :maCoSo AND mc.TinhTrang = 'con_hang'
                ORDER BY dm.MaDM, m.TenMon";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':maCoSo', $maCoSo);
        $stmt->execute();
        
        $menuItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Nhóm các món theo danh mục
        $groupedItems = [];
        foreach ($menuItems as $item) {
            $categoryName = $item['TenDM'];
            if (!isset($groupedItems[$categoryName])) {
                $groupedItems[$categoryName] = [];
            }
            $groupedItems[$categoryName][] = $item;
        }
        
        return $groupedItems;
    }
    
    // API endpoint để lấy dữ liệu JSON cho AJAX
    public function getMenuData() 
    {
        header('Content-Type: application/json');
        
        $maCoSo = $_GET['coso'] ?? 11;
        $category = $_GET['category'] ?? 'all';
        
        if ($category === 'all') {
            $groupedMenuItems = $this->getMenuItemsGroupedByCategory($maCoSo);
            echo json_encode([
                'success' => true,
                'data' => $groupedMenuItems,
                'type' => 'grouped'
            ]);
        } else {
            $menuItems = $this->getMenuItems($maCoSo, $category);
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
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}