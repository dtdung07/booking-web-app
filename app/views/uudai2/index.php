$page = $_GET['page'] ?? 'home';

switch ($page) {
    case 'home':
        include 'app/views/home.php';
        break;
    case 'menu2':
        include 'app/views/menu2/index.php';
        break;
    case 'promotions':
        include 'app/views/uudai2/index.php';  // Thêm dòng này
        break;
    default:
        include 'app/views/home.php';
}
3. Truy cập trang