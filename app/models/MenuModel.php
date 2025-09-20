<?php
class MenuModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Lấy danh sách các danh mục có món ăn tại một cơ sở
     */
    public function findCategoriesByCoSo($maCoSo) {
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

    /**
     * Lấy danh sách món ăn theo cơ sở và danh mục
     */
    public function findMenuItemsByCoSoAndCategory($maCoSo, $selectedCategory = 'all') {
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

    /**
     * Lấy danh sách món ăn đã được nhóm theo danh mục
     */
    public function findMenuItemsGroupedByCategory($maCoSo) {
        $menuItems = $this->findMenuItemsByCoSoAndCategory($maCoSo, 'all');
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

    /**
     * Tìm kiếm món ăn theo tên và mã cơ sở
     */
public function searchMenuItems($maCoSo, $tenMon = '') {
    $sql = "SELECT m.MaMon, m.TenMon, m.MoTa, m.HinhAnhURL, mc.Gia, dm.TenDM, dm.MaDM
            FROM menu_coso mc
            JOIN monan m ON mc.MaMon = m.MaMon
            JOIN danhmuc dm ON m.MaDM = dm.MaDM
            WHERE mc.MaCoSo = :maCoSo AND mc.TinhTrang = 'con_hang'";
    
    $params = [':maCoSo' => $maCoSo];
    
    // Thêm điều kiện tìm kiếm theo tên món nếu có
    if (!empty($tenMon)) {
        $sql .= " AND m.TenMon LIKE :tenMon";
        $params[':tenMon'] = '%' . $tenMon . '%';
    }
    
    $sql .= " ORDER BY m.TenMon"; // Vẫn giữ lại sắp xếp
    
    $stmt = $this->db->prepare($sql);
    
    // Bind parameters
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC); // Chỉ trả về mảng kết quả
}


}
?>