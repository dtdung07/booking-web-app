<?php
require_once 'config/database.php';

try {
    $database = new Database();
    $db = $database->getConnection();
    
    // Test the SQL query
    $sql = "SELECT DISTINCT dm.MaDM, dm.TenDM
            FROM menu_coso mc
            JOIN monan m ON mc.MaMon = m.MaMon
            JOIN danhmuc dm ON m.MaDM = dm.MaDM
            WHERE mc.MaCoSo = 11
            ORDER BY dm.MaDM";
            
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Categories found:\n";
    foreach ($categories as $category) {
        echo "ID: " . $category['MaDM'] . " - Name: " . $category['TenDM'] . "\n";
    }
    
    // Test menu items
    $sql2 = "SELECT m.MaMon, m.TenMon, m.MoTa, m.HinhAnhURL, mc.Gia, dm.TenDM
             FROM menu_coso mc
             JOIN monan m ON mc.MaMon = m.MaMon
             JOIN danhmuc dm ON m.MaDM = dm.MaDM
             WHERE mc.MaCoSo = 11 AND mc.TinhTrang = 'con_hang'
             ORDER BY dm.MaDM, m.TenMon";
             
    $stmt2 = $db->prepare($sql2);
    $stmt2->execute();
    $menuItems = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    
    echo "\nMenu items found:\n";
    foreach ($menuItems as $item) {
        echo "ID: " . $item['MaMon'] . " - Name: " . $item['TenMon'] . " - Price: " . $item['Gia'] . " - Category: " . $item['TenDM'] . "\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
