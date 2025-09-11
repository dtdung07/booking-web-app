<?php
// Test the menu API endpoint
require_once 'app/controllers/MenuController.php';

$controller = new MenuController();

// Simulate the GET parameters
$_GET['coso'] = '11';
$_GET['category'] = 'all';

echo "Testing MenuController::getMenuData() with coso=11 and category=all\n\n";

$controller->getMenuData();
?>
