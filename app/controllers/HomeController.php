<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/CoSo.php';

class HomeController extends BaseController 
{
    private $db;
    private $coSo;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->coSo = new CoSo($this->db);
    }

    public function index() 
    {
        // Lấy dữ liệu cơ sở từ database
        $stmt = $this->coSo->getAll();
        $branches_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Nhóm cơ sở theo địa chỉ
        $grouped_branches = [];
        $location_counts = [];
        
        foreach($branches_data as $branch_data) {
            if(empty($branch_data['TenCoSo'])) continue;
            
            $district = $branch_data['DiaChi'];
            
            // Khởi tạo group nếu chưa có
            if (!isset($grouped_branches[$district])) {
                $grouped_branches[$district] = [];
                $location_counts[$district] = 0;
            }
            
            // Thêm cơ sở vào group
            $grouped_branches[$district][] = [
                'id' => $branch_data['MaCoSo'],
                'name' => $branch_data['TenCoSo'],
                'address' => $branch_data['DiaChi'],
                'phone' => $branch_data['DienThoai'],
                'image' => 'https://storage.quannhautudo.com/data/thumb_1200/Data/images/product/2023/06/202306281114157262.webp'
            ];
            
            $location_counts[$district]++;
            
        }
        
        // Tính tổng số cơ sở
        $total_branches = array_sum($location_counts);

        
        $this->render('home/index', [
            'grouped_branches' => $grouped_branches,
            'location_counts' => $location_counts,
            'total_branches' => $total_branches
        ]);
    }
    
    /**
     * Chuyển đổi trạng thái sang text hiển thị
     */
    private function getStatusText($status) {
        switch($status) {
            case 'Đang mở':
                return 'Đang mở';
            case 'Hết bàn':
                return 'Hết bàn';
            case 'Đang đóng':
                return 'Đang đóng';
            default:
                return 'Đang mở';
        }
    }
    
    public function about() 
    {
        $this->render('home/about');
    }
    
    public function notFound() 
    {
        http_response_code(404);
        $this->render('errors/404');
    }
}
