<?php

class BranchController extends BaseController
{
    public function index()
    {
        $page_title = "Quán Nhậu Tự Do - Hệ Thống Cơ Sở";
        
        // Dữ liệu mẫu các cơ sở
        $branches = [
            [
                'id' => 1,
                'name' => '67A Phó Đức Chính',
                'address' => '67A Phó Đức Chính, Ngọc Hồ, Ba Đình, Hà Nội',
                'district' => 'Ba Đình',
                'phone' => '*1986',
                'hotline' => '*1986',
                'capacity' => '400 KHÁCH',
                'area' => '1000 M²',
                'floors' => '2 TẦNG',
                'operating_hours' => '09:00 - 24:00',
                'status' => 'HOẠT ĐỘNG',
                'description' => '"Chốn ăn chơi" mới của anh em quận Ba Đình',
                'image' => 'https://storage.quannhautudo.com/data/thumb_800/Data/images/product/2023/08/202308051004475343.webp',
                'gallery' => [
                    'https://storage.quannhautudo.com/data/thumb_800/Data/images/product/2023/05/202305111648358011.webp',
                    'public/images/home-layer-2_v2.webp',
                    'public/images/home-layer-3_v2.webp'
                ],
                'map_link' => 'https://www.google.com/maps/place/Qu%C3%A1n+Nh%E1%BA%ADu+T%E1%BB%B1+Do/@21.0465177,105.8078178,14z',
                'features' => ['Karaoke', 'Không gian VIP', 'Bãi đỗ xe', 'Wifi miễn phí']
            ],
            [
                'id' => 2,
                'name' => '89 Láng Hạ',
                'address' => '89 Láng Hạ, Thành Công, Đống Đa, Hà Nội',
                'district' => 'Đống Đa',
                'phone' => '*1986',
                'hotline' => '*1986',
                'capacity' => '350 KHÁCH',
                'area' => '800 M²',
                'floors' => '3 TẦNG',
                'operating_hours' => '10:00 - 23:30',
                'status' => 'HOẠT ĐỘNG',
                'description' => 'Không gian ẩm thực sôi động giữa lòng Hà Nội',
                'image' => 'https://storage.quannhautudo.com/data/thumb_800/Data/images/product/2023/05/202305111648358011.webp',
                'gallery' => [
                    'public/images/home-layer-2_v2.webp',
                    'public/images/home-layer-3_v2.webp',
                    'public/images/home-layer-4_v2.webp'
                ],
                'map_link' => 'https://www.google.com/maps/place/Qu%C3%A1n+Nh%E1%BA%ADu+T%E1%BB%B1+Do/@21.0181,105.8126,14z',
                'features' => ['Sân thượng', 'Live music', 'Bãi đỗ xe', 'Điều hòa']
            ],
            [
                'id' => 3,
                'name' => '156 Xuân Thủy',
                'address' => '156 Xuân Thủy, Dịch Vọng Hậu, Cầu Giấy, Hà Nội',
                'district' => 'Cầu Giấy',
                'phone' => '*1986',
                'hotline' => '*1986',
                'capacity' => '300 KHÁCH',
                'area' => '700 M²',
                'floors' => '2 TẦNG',
                'operating_hours' => '09:30 - 24:00',
                'status' => 'HOẠT ĐỘNG',
                'description' => 'Điểm hẹn lý tưởng của giới trẻ Cầu Giấy',
                'image' => 'https://storage.quannhautudo.com/data/thumb_800/Data/images/product/2023/05/202305111648358011.webp',
                'gallery' => [
                    'public/images/home-layer-3_v2.webp',
                    'public/images/home-layer-4_v2.webp',
                    'https://storage.quannhautudo.com/data/thumb_800/Data/images/product/2023/05/202305111648358011.webp'
                ],
                'map_link' => 'https://www.google.com/maps/place/Qu%C3%A1n+Nh%E1%BA%ADu+T%E1%BB%B1+Do/@21.0375,105.7953,14z',
                'features' => ['Sân vườn', 'Karaoke', 'Game corner', 'Wifi miễn phí']
            ],
            [
                'id' => 4,
                'name' => '78 Giải Phóng',
                'address' => '78 Giải Phóng, Đồng Tâm, Hai Bà Trưng, Hà Nội',
                'district' => 'Hai Bà Trưng',
                'phone' => '*1986',
                'hotline' => '*1986',
                'capacity' => '250 KHÁCH',
                'area' => '600 M²',
                'floors' => '2 TẦNG',
                'operating_hours' => '10:00 - 23:00',
                'status' => 'HOẠT ĐỘNG',
                'description' => 'Không gian ấm cúng cho các buổi sum họp',
                'image' => 'https://storage.quannhautudo.com/data/thumb_800/Data/images/product/2023/05/202305111648358011.webp',
                'gallery' => [
                    'public/images/home-layer-4_v2.webp',
                    'https://storage.quannhautudo.com/data/thumb_800/Data/images/product/2023/05/202305111648358011.webp',
                    'public/images/home-layer-2_v2.webp'
                ],
                'map_link' => 'https://www.google.com/maps/place/Qu%C3%A1n+Nh%E1%BA%ADu+T%E1%BB%B1+Do/@21.0089,105.8589,14z',
                'features' => ['Phòng riêng', 'Bãi đỗ xe', 'Điều hòa', 'Wifi miễn phí']
            ],
            [
                'id' => 5,
                'name' => '234 Minh Khai',
                'address' => '234 Minh Khai, Minh Khai, Hoàng Mai, Hà Nội',
                'district' => 'Hoàng Mai',
                'phone' => '*1986',
                'hotline' => '*1986',
                'capacity' => '180 KHÁCH',
                'area' => '500 M²',
                'floors' => '2 TẦNG',
                'operating_hours' => '11:00 - 23:30',
                'status' => 'HOẠT ĐỘNG',
                'description' => 'Cơ sở mới với phong cách hiện đại',
                'image' => 'https://storage.quannhautudo.com/data/thumb_800/Data/images/product/2023/05/202305111648358011.webp',
                'gallery' => [
                    'https://storage.quannhautudo.com/data/thumb_800/Data/images/product/2023/05/202305111648358011.webp',
                    'public/images/home-layer-3_v2.webp',
                    'public/images/home-layer-4_v2.webp'
                ],
                'map_link' => 'https://www.google.com/maps/place/Qu%C3%A1n+Nh%E1%BA%ADu+T%E1%BB%B1+Do/@20.9895,105.8756,14z',
                'features' => ['Không gian trẻ trung', 'Karaoke', 'Bãi đỗ xe', 'Take away']
            ]
        ];
        
        // Không lọc ở backend nữa, sẽ để JavaScript xử lý
        $selected_district = $_GET['district'] ?? 'all';
        
        // Render view
        ob_start();
        include 'app/views/branches/index.php';
        $content = ob_get_clean();
        
        include 'app/views/layouts/layout.php';
    }
    
    public function detail()
    {
        $id = $_GET['id'] ?? 1;
        $page_title = "Chi tiết cơ sở - Quán Nhậu Tự Do";
        
        // Dữ liệu chi tiết cơ sở (trong thực tế sẽ lấy từ database)
        $branch = [
            'id' => $id,
            'name' => '67A Phó Đức Chính',
            'address' => '67A Phó Đức Chính, Ngọc Hồ, Ba Đình, Hà Nội',
            'district' => 'Ba Đình',
            'phone' => '*1986',
            'hotline' => '*1986',
            'capacity' => '400 KHÁCH',
            'area' => '1000 M²',
            'floors' => '2 TẦNG',
            'operating_hours' => '09:00 - 24:00',
            'status' => 'HOẠT ĐỘNG',
            'description' => '"Chốn ăn chơi" mới của anh em quận Ba Đình với không gian rộng rãi, thoáng mát. Menu đa dạng với các món nhậu đặc trưng, phục vụ chu đáo.',
            'image' => 'https://storage.quannhautudo.com/data/thumb_800/Data/images/product/2023/10/202310241151064241.webp',
            'gallery' => [
                'public/images/branch-1-1.webp',
                'public/images/branch-1-2.webp',
                'public/images/branch-1-3.webp',
                'public/images/branch-1-4.webp'
            ],
            'map_link' => 'https://www.google.com/maps/place/Qu%C3%A1n+Nh%E1%BA%ADu+T%E1%BB%B1+Do/@21.0465177,105.8078178,14z',
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
        
        // Render view
        ob_start();
        include 'app/views/branches/detail.php';
        $content = ob_get_clean();
        
        include 'app/views/layouts/layout.php';
    }
}
