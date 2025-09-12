<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/CoSo.php';
require_once __DIR__ . '/../../includes/BaseController.php';

class BranchController extends BaseController
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
        $page_title = "Quán Nhậu Tự Do - Hệ Thống Cơ Sở";
        
        // Lấy dữ liệu cơ sở từ database
        $stmt = $this->coSo->getAll();
        $branches_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Chuyển đổi dữ liệu database thành format hiển thị
        $branches = [];
        foreach($branches_data as $branch_data) {
            $branches[] = [
                'id' => $branch_data['MaCoSo'],
                'name' => $branch_data['TenCoSo'],
                'address' => $branch_data['DiaChi'],
                'district' => $this->extractDistrict($branch_data['DiaChi']),
                'phone' => $branch_data['DienThoai'],
                'hotline' => $branch_data['DienThoai'],
                'image' => 'https://storage.quannhautudo.com/data/thumb_800/Data/images/product/2023/08/202308051004475343.webp', // Default image
                'gallery' => [
                    'https://storage.quannhautudo.com/data/thumb_800/Data/images/product/2023/05/202305111648358011.webp',
                    'public/images/home-layer-2_v2.webp',
                    'public/images/home-layer-3_v2.webp'
                ],
                'map_link' => 'https://maps.google.com/?q=' . urlencode($branch_data['DiaChi']),
                'features' => ['Karaoke', 'Không gian VIP', 'Bãi đỗ xe', 'Wifi miễn phí']
            ];
        }
        
        // Fallback to sample data if no data in database
        if(empty($branches)) {
            $branches = [
                [
                    'id' => 1,
                    'name' => '67A Phó Đức Chính',
                    'address' => '67A Phó Đức Chính, Ngọc Hồ, Ba Đình, Hà Nội',
                    'district' => 'Ba Đình',
                    'phone' => '*1986',
                    'hotline' => '*1986',
                    'image' => 'https://storage.quannhautudo.com/data/thumb_800/Data/images/product/2023/08/202308051004475343.webp',
                    'gallery' => [
                        'https://storage.quannhautudo.com/data/thumb_800/Data/images/product/2023/05/202305111648358011.webp',
                        'public/images/home-layer-2_v2.webp',
                        'public/images/home-layer-3_v2.webp'
                    ],
                    'map_link' => 'https://maps.google.com/?q=67A+Phó+Đức+Chính,+Ba+Đình,+Hà+Nội',
                    'features' => ['Karaoke', 'Không gian VIP', 'Bãi đỗ xe', 'Wifi miễn phí']
                ]
            ];
        }
        
        // **[SỬA]** Tạo danh sách các quận để tạo tab lọc
        $branch_districts = [];
        foreach($branches as $branch) {
            if(!empty($branch['district']) && !in_array($branch['district'], $branch_districts)) {
                $branch_districts[] = $branch['district'];
            }
        }
        sort($branch_districts); // Sắp xếp tên quận theo alphabet cho dễ nhìn

        // **[SỬA]** Lấy quận được chọn từ URL
        $selected_district = $_GET['district'] ?? 'all';
        
        // Render view
        $this->render('branches/index', [
            'page_title' => $page_title,
            'branches' => $branches,
            'branch_districts' => $branch_districts, // Truyền danh sách quận
            'selected_district' => $selected_district
        ]);
    }

    /**
     * API endpoint để lấy dữ liệu cơ sở theo địa chỉ
     */
    public function api()
    {
        header('Content-Type: application/json');
        
        $address = $_GET['address'] ?? 'all';
        
        // Lấy dữ liệu từ database
        if ($address === 'all') {
            $stmt = $this->coSo->getAll();
        } else {
            // Lấy dữ liệu theo địa chỉ cụ thể
            $stmt = $this->coSo->getByAddress($address);
        }
        
        $branches_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Chuyển đổi dữ liệu
        $branches = [];
        foreach($branches_data as $branch_data) {
            $branches[] = [
                'id' => $branch_data['MaCoSo'],
                'name' => $branch_data['TenCoSo'],
                'address' => $branch_data['DiaChi'],
                'district' => $this->extractDistrict($branch_data['DiaChi']),
                'phone' => $branch_data['DienThoai'],
                'hotline' => $branch_data['DienThoai'],
                'image' => 'https://storage.quannhautudo.com/data/thumb_800/Data/images/product/2023/08/202308051004475343.webp',
                'gallery' => [
                    'https://storage.quannhautudo.com/data/thumb_800/Data/images/product/2023/05/202305111648358011.webp',
                    'public/images/home-layer-2_v2.webp',
                    'public/images/home-layer-3_v2.webp'
                ],
                'map_link' => 'https://www.google.com/maps/search/' . urlencode($branch_data['DiaChi']),
                'features' => ['Karaoke', 'Không gian VIP', 'Bãi đỗ xe', 'Wifi miễn phí']
            ];
        }

           $stmtAddresses = $this->coSo->getAddressSummary();
    $addresses = $stmtAddresses->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode([
            'success' => true,
            'data' => $branches,
            'count' => count($branches),
            'address' => $address
        ], JSON_UNESCAPED_UNICODE);
    }

    public function detail()
    {
        $id = $_GET['id'] ?? 1;
        
        // Lấy thông tin cơ sở từ database
        if($this->coSo->getById($id)) {
            $branch = [
                'id' => $this->coSo->MaCoSo,
                'name' => $this->coSo->TenCoSo,
                'address' => $this->coSo->DiaChi,
                'phone' => $this->coSo->DienThoai,
                'image' => 'https://storage.quannhautudo.com/data/thumb_800/Data/images/product/2023/10/202310241151064241.webp',
                'gallery' => [
                    'public/images/branch-1-1.webp',
                    'public/images/branch-1-2.webp',
                    'public/images/branch-1-3.webp',
                    'public/images/branch-1-4.webp'
                ],
                'map_link' => 'https://maps.google.com/?q=' . urlencode($this->coSo->DiaChi),
                'features' => [
                    'Karaoke với âm thanh chuyên nghiệp',
                    'Không gian VIP riêng tư',
                    'Bãi đỗ xe rộng rãi',
                    'Wifi miễn phí tốc độ cao',
                    'Điều hòa trong từng phòng',
                    'Phục vụ 24/7'
                ],
                'menu_highlights' => [
                    'Nem nướng Nha Trang',
                    'Chả cá Lã Vọng',
                    'Thịt nướng lá chuối',
                    'Bia tươi Sài Gòn'
                ]
            ];
        } else {
            // Fallback data nếu không tìm thấy trong database
            $branch = [
                'id' => 1,
                'name' => '67A Phó Đức Chính',
                'address' => '67A Phó Đức Chính, Ngọc Hồ, Ba Đình, Hà Nội',
                'phone' => '*1986',
                 'image' => 'https://storage.quannhautudo.com/data/thumb_800/Data/images/product/2023/10/202310241151064241.webp',
                'gallery' => [
                    'public/images/branch-1-1.webp',
                    'public/images/branch-1-2.webp',
                    'public/images/branch-1-3.webp',
                    'public/images/branch-1-4.webp'
                ],
                'map_link' => 'https://maps.google.com/?q=67A+Phó+Đức+Chính,+Ba+Đình,+Hà+Nội',
                'features' => [
                    'Karaoke với âm thanh chuyên nghiệp',
                    'Không gian VIP riêng tư',
                    'Bãi đỗ xe rộng rãi',
                    'Wifi miễn phí tốc độ cao',
                    'Điều hòa trong từng phòng',
                    'Phục vụ 24/7'
                ],
                'menu_highlights' => [
                    'Nem nướng Nha Trang',
                    'Chả cá Lã Vọng',
                    'Thịt nướng lá chuối',
                    'Bia tươi Sài Gòn'
                ]
            ];
        }
        
        $page_title = "Quán Nhậu - " . $branch['name'];
        
        // Render view
        $this->render('branches/detail', [
            'page_title' => $page_title,
            'branch' => $branch
        ]);
    }

    /**
     * Trích xuất quận từ địa chỉ
     */
    private function extractDistrict($address) {
        // Danh sách các quận/huyện để tìm kiếm
        $districts = [
            'Ba Đình', 'Hoàn Kiếm', 'Hai Bà Trưng', 'Đống Đa', 'Tây Hồ', 'Cầu Giấy', 
            'Thanh Xuân', 'Hoàng Mai', 'Long Biên', 'Hà Đông', 'Nam Từ Liêm', 'Bắc Từ Liêm'
        ];
        
        foreach($districts as $district) {
            if(stripos($address, $district) !== false) {
                return $district;
            }
        }
        
        return 'Khác';
    }

    /**
     * Admin view - Quản lý cơ sở
     */
    public function admin()
    {
        $page_title = "Quản lý cơ sở - Admin";
        
        // Render admin view trực tiếp
        include __DIR__ . '/../views/admin/branches/index.php';
    }

    /**
     * API để lấy dữ liệu cơ sở cho admin (JSON response)
     */
    public function getAdminData()
    {
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            $stmt = $this->coSo->getAll();
            $branches = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo json_encode([
                'success' => true,
                'data' => $branches,
                'count' => count($branches)
            ], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Lỗi khi lấy dữ liệu: ' . $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * Thêm cơ sở mới
     */
    public function add()
    {
        header('Content-Type: application/json; charset=utf-8');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Phương thức không được hỗ trợ']);
            return;
        }

        $tenCoSo = $_POST['tenCoSo'] ?? '';
        $diaChi = $_POST['diaChi'] ?? '';
        $dienThoai = $_POST['dienThoai'] ?? '';
        $anhUrl = $_POST['anhUrl'] ?? '';

        if (empty($tenCoSo) || empty($diaChi) || empty($dienThoai)) {
            echo json_encode(['success' => false, 'message' => 'Vui lòng điền đầy đủ thông tin bắt buộc']);
            return;
        }

        try {
            // Thêm vào database
            $query = "INSERT INTO coso (TenCoSo, DiaChi, DienThoai, AnhUrl) VALUES (?, ?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $result = $stmt->execute([$tenCoSo, $diaChi, $dienThoai, $anhUrl]);

            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Thêm cơ sở thành công']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Lỗi khi thêm cơ sở']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Lỗi database: ' . $e->getMessage()]);
        }
    }

    /**
     * Cập nhật cơ sở
     */
    public function update()
    {
        header('Content-Type: application/json; charset=utf-8');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Phương thức không được hỗ trợ']);
            return;
        }

        $maCoSo = $_POST['maCoSo'] ?? '';
        $tenCoSo = $_POST['tenCoSo'] ?? '';
        $diaChi = $_POST['diaChi'] ?? '';
        $dienThoai = $_POST['dienThoai'] ?? '';
        $anhUrl = $_POST['anhUrl'] ?? '';

        if (empty($maCoSo) || empty($tenCoSo) || empty($diaChi) || empty($dienThoai)) {
            echo json_encode(['success' => false, 'message' => 'Vui lòng điền đầy đủ thông tin bắt buộc']);
            return;
        }

        try {
            $query = "UPDATE coso SET TenCoSo = ?, DiaChi = ?, DienThoai = ?, AnhUrl = ? WHERE MaCoSo = ?";
            $stmt = $this->db->prepare($query);
            $result = $stmt->execute([$tenCoSo, $diaChi, $dienThoai, $anhUrl, $maCoSo]);

            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Cập nhật cơ sở thành công']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Lỗi khi cập nhật cơ sở']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Lỗi database: ' . $e->getMessage()]);
        }
    }

    /**
     * Xóa cơ sở
     */
    public function delete()
    {
        header('Content-Type: application/json; charset=utf-8');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Phương thức không được hỗ trợ']);
            return;
        }

        $maCoSo = $_POST['maCoSo'] ?? '';

        if (empty($maCoSo)) {
            echo json_encode(['success' => false, 'message' => 'Mã cơ sở không được để trống']);
            return;
        }

        try {
            $query = "DELETE FROM coso WHERE MaCoSo = ?";
            $stmt = $this->db->prepare($query);
            $result = $stmt->execute([$maCoSo]);

            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Xóa cơ sở thành công']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Lỗi khi xóa cơ sở']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Lỗi database: ' . $e->getMessage()]);
        }
    }

    /**
     * Chuyển đổi trạng thái từ database sang text hiển thị
     */
    private function getStatusText($status) {
        switch($status) {
            case 'Đang mở':
                return 'HOẠT ĐỘNG';
            case 'Hết bàn':
                return 'HẾT BÀN';
            case 'Đang đóng':
                return 'ĐÓNG CỬA';
            default:
                return 'HOẠT ĐỘNG';
        }
    }
}