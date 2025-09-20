-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th9 06, 2025 lúc 02:14 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `booking_restaurant`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `coso`
--

CREATE TABLE `coso` (
  `MaCoSo` int(11) NOT NULL,
  `TenCoSo` varchar(255) NOT NULL,
  `DienThoai` varchar(15) NOT NULL,
  `DiaChi` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `coso`
--

INSERT INTO `coso` (`MaCoSo`, `TenCoSo`, `DienThoai`, `DiaChi`) VALUES
(11, '67A Phó Đức Chính', '0922782387', 'Ba Đình'),
(12, '10 Nguyễn Văn Huyên', '0922782387', 'Cầu Giấy'),
(21, '68 Láng Thượng', '0922782387', 'Đống Đa'),
(22, '505 Minh Khai', '0922782387', 'Hai Bà Trưng'),
(23, 'Nguyễn Hữu Thọ (Linh Đàm)', '0922782387', 'Hoàng Mai');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `coso`
--
ALTER TABLE `coso`
  ADD PRIMARY KEY (`MaCoSo`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `coso`
--
ALTER TABLE `coso`
  MODIFY `MaCoSo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
