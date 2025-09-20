-- Thêm dữ liệu mẫu cho menu

-- Thêm danh mục
INSERT INTO `danhmuc` (`TenDM`) VALUES
('TẤT CẢ'),
('DÊ TƯƠI'),
('ĐỒ NƯỚNG'),
('THIẾT BẢN'),
('RAU XANH'),
('HẢI SẢN'),
('ĐỒ UỐNG');

-- Thêm món ăn
INSERT INTO `monan` (`MaDM`, `TenMon`, `MoTa`, `HinhAnhURL`) VALUES
-- Dê tươi
(2, 'Dê nướng tỏi', 'Thịt dê tươi nướng với tỏi thơm ngon', 'https://storage.quannhautudo.com/data/thumb_400/Data/images/product/2025/06/202506271712248578.webp'),
(2, 'Dê tái chanh', 'Thịt dê tái chanh tươi mát', 'https://storage.quannhautudo.com/data/thumb_400/Data/images/product/2025/06/202506271712248578.webp'),
(2, 'Dê nướng lá chuối', 'Dê nướng lá chuối thơm ngon', 'https://storage.quannhautudo.com/data/thumb_400/Data/images/product/2025/06/202506271712248578.webp'),

-- Đồ nướng
(3, 'Sườn nướng mật ong', 'Sườn heo nướng với mật ong ngọt đậm đà', 'https://storage.quannhautudo.com/data/thumb_400/Data/images/product/2025/06/202506271712248578.webp'),
(3, 'Gà nướng muối ớt', 'Gà ta nướng với muối ớt đặc biệt', 'https://storage.quannhautudo.com/data/thumb_400/Data/images/product/2025/06/202506271712248578.webp'),
(3, 'Cá lóc nướng trui', 'Cá lóc nướng trui thơm ngon', 'https://storage.quannhautudo.com/data/thumb_400/Data/images/product/2025/06/202506271712248578.webp'),

-- Thiết bản
(4, 'Tiết canh dê', 'Tiết canh dê tươi ngon', 'https://storage.quannhautudo.com/data/thumb_400/Data/images/product/2025/06/202506271712248578.webp'),
(4, 'Thiết bò', 'Thiết bò tươi sạch', 'https://storage.quannhautudo.com/data/thumb_400/Data/images/product/2025/06/202506271712248578.webp'),
(4, 'Ếch sốt tiêu gừng chua cay', 'Ếch tươi sốt tiêu gừng chua cay đậm đà', 'https://storage.quannhautudo.com/data/thumb_400/Data/images/product/2025/06/202506271712248578.webp'),

-- Rau xanh
(5, 'Bắp cải nướng', 'Bắp cải nướng thơm ngon', 'https://storage.quannhautudo.com/data/thumb_400/Data/images/product/2025/06/202506271712248578.webp'),
(5, 'Rau muống xào tỏi', 'Rau muống xào tỏi giòn ngon', 'https://storage.quannhautudo.com/data/thumb_400/Data/images/product/2025/06/202506271712248578.webp'),
(5, 'Măng chua nướng', 'Măng chua nướng thơm ngon', 'https://storage.quannhautudo.com/data/thumb_400/Data/images/product/2025/06/202506271712248578.webp'),

-- Hải sản
(6, 'Tôm nướng muối ớt', 'Tôm sú nướng muối ớt thơm ngon', 'https://storage.quannhautudo.com/data/thumb_400/Data/images/product/2025/06/202506271712248578.webp'),
(6, 'Cua rang me', 'Cua biển rang me chua ngọt', 'https://storage.quannhautudo.com/data/thumb_400/Data/images/product/2025/06/202506271712248578.webp'),
(6, 'Mực nướng sa tế', 'Mực tươi nướng sa tế cay ngon', 'https://storage.quannhautudo.com/data/thumb_400/Data/images/product/2025/06/202506271712248578.webp'),

-- Đồ uống
(7, 'Bia hơi', 'Bia hơi tươi mát', 'https://storage.quannhautudo.com/data/thumb_400/Data/images/product/2025/06/202506271712248578.webp'),
(7, 'Bia chai', 'Bia chai các loại', 'https://storage.quannhautudo.com/data/thumb_400/Data/images/product/2025/06/202506271712248578.webp'),
(7, 'Nước ngọt', 'Nước ngọt các loại', 'https://storage.quannhautudo.com/data/thumb_400/Data/images/product/2025/06/202506271712248578.webp');

-- Thêm menu cho cơ sở (giả sử cơ sở có MaCoSo = 1)
INSERT INTO `menu_coso` (`MaCoSo`, `MaMon`, `Gia`, `TinhTrang`) VALUES
-- Dê tươi
(1, 1, 180000, 'con_hang'),
(1, 2, 170000, 'con_hang'),
(1, 3, 185000, 'con_hang'),

-- Đồ nướng  
(1, 4, 150000, 'con_hang'),
(1, 5, 140000, 'con_hang'),
(1, 6, 160000, 'con_hang'),

-- Thiết bản
(1, 7, 50000, 'con_hang'),
(1, 8, 60000, 'con_hang'),
(1, 9, 120000, 'con_hang'),

-- Rau xanh
(1, 10, 40000, 'con_hang'),
(1, 11, 35000, 'con_hang'),
(1, 12, 45000, 'con_hang'),

-- Hải sản
(1, 13, 200000, 'con_hang'),
(1, 14, 250000, 'con_hang'),
(1, 15, 180000, 'con_hang'),

-- Đồ uống
(1, 16, 15000, 'con_hang'),
(1, 17, 25000, 'con_hang'),
(1, 18, 10000, 'con_hang');
