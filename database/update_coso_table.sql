-- Script cập nhật bảng coso để khớp với cấu trúc trong coso.sql
-- Chạy script này trong phpMyAdmin hoặc MySQL client

USE booking_restaurant;

-- Xóa bảng coso hiện tại nếu có
DROP TABLE IF EXISTS `coso`;

-- Tạo lại bảng coso với cấu trúc đầy đủ
CREATE TABLE `coso` (
  `MaCoSo` int(11) NOT NULL,
  `TenCoSo` varchar(255) NOT NULL,
  `DiaChi` varchar(255) NOT NULL,
  `DienThoai` varchar(15) NOT NULL,
  `Mota` varchar(200) NOT NULL,
  `ThoiGianHoatDong` varchar(100) NOT NULL,
  `SucChua` int(11) NOT NULL,
  `DienTich` int(11) NOT NULL,
  `SoTang` int(11) NOT NULL,
  `TrangThai` enum('Đang mở','Hết bàn','Đang đóng','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert dữ liệu mẫu từ coso.sql
INSERT INTO `coso` (`MaCoSo`, `TenCoSo`, `DiaChi`, `DienThoai`, `Mota`, `ThoiGianHoatDong`, `SucChua`, `DienTich`, `SoTang`, `TrangThai`) VALUES
(11, '67A Phó Đức Chính', 'Ba Đình', '0922782387', '"Chốn ăn chơi" mới của anh em quận Ba Đình', '09:00 - 24:00', 400, 1000, 2, 'Đang mở'),
(12, '10 Nguyễn Văn Huyên', 'Cầu Giấy', '0922782387', 'Quán nhậu sáng nhất quận Cầu Giấy', '09:00 - 24:00', 500, 1100, 2, 'Đang mở'),
(13, '68 Khúc Thừa Dụ', 'Cầu Giấy', '0922782387', 'Quán Nhậu view rooftop chill nhất quận Cầu Giấy', '09:00 - 24:00', 400, 800, 3, 'Đang mở');

-- Thiết lập khóa chính và auto increment
ALTER TABLE `coso`
  ADD PRIMARY KEY (`MaCoSo`);

ALTER TABLE `coso`
  MODIFY `MaCoSo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
