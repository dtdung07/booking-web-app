-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th9 04, 2025 lúc 05:33 AM
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
-- Cấu trúc bảng cho bảng `ban`
--

CREATE TABLE `ban` (
  `MaBan` int(11) NOT NULL,
  `MaCoSo` int(11) NOT NULL,
  `TenBan` varchar(50) NOT NULL,
  `SucChua` int(11) NOT NULL CHECK (`SucChua` > 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chitietdondatban`
--

CREATE TABLE `chitietdondatban` (
  `MaDon` int(11) NOT NULL,
  `MaMon` int(11) NOT NULL,
  `SoLuong` int(11) NOT NULL CHECK (`SoLuong` > 0),
  `DonGia` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `coso`
--

CREATE TABLE `coso` (
  `MaCoSo` int(11) NOT NULL,
  `TenCoSo` varchar(255) NOT NULL,
  `DiaChi` varchar(255) NOT NULL,
  `DienThoai` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `coso`
--

INSERT INTO `coso` (`MaCoSo`, `TenCoSo`, `DiaChi`, `DienThoai`) VALUES
(1, 'HN01', 'Bắc Từ Liêm, Hà Nội', '0987654321');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `danhmuc`
--

CREATE TABLE `danhmuc` (
  `MaDM` int(11) NOT NULL,
  `TenDM` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `dondatban`
--

CREATE TABLE `dondatban` (
  `MaDon` int(11) NOT NULL,
  `MaKH` int(11) NOT NULL,
  `MaCoSo` int(11) NOT NULL,
  `MaUD` int(11) DEFAULT NULL,
  `MaNV_XacNhan` int(11) DEFAULT NULL,
  `SoLuongKH` int(11) NOT NULL CHECK (`SoLuongKH` > 0),
  `ThoiGianBatDau` datetime NOT NULL,
  `GhiChu` text DEFAULT NULL,
  `TrangThai` enum('cho_xac_nhan','da_xac_nhan','da_huy','hoan_thanh') NOT NULL DEFAULT 'cho_xac_nhan',
  `ThoiGianTao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `dondatban_ban`
--

CREATE TABLE `dondatban_ban` (
  `MaDon` int(11) NOT NULL,
  `MaBan` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `khachhang`
--

CREATE TABLE `khachhang` (
  `MaKH` int(11) NOT NULL,
  `TenKH` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `SDT` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `menu_coso`
--

CREATE TABLE `menu_coso` (
  `MaCoSo` int(11) NOT NULL,
  `MaMon` int(11) NOT NULL,
  `Gia` decimal(10,2) NOT NULL CHECK (`Gia` >= 0),
  `TinhTrang` enum('con_hang','het_hang') NOT NULL DEFAULT 'con_hang'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `monan`
--

CREATE TABLE `monan` (
  `MaMon` int(11) NOT NULL,
  `MaDM` int(11) NOT NULL,
  `TenMon` varchar(255) NOT NULL,
  `MoTa` text DEFAULT NULL,
  `HinhAnhURL` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nhanvien`
--

CREATE TABLE `nhanvien` (
  `MaNV` int(11) NOT NULL,
  `MaCoSo` int(11) NOT NULL,
  `TenDN` varchar(50) NOT NULL,
  `MatKhau` varchar(255) NOT NULL,
  `TenNhanVien` varchar(100) NOT NULL,
  `ChucVu` enum('admin','nhan_vien') NOT NULL DEFAULT 'nhan_vien'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `nhanvien`
--

INSERT INTO `nhanvien` (`MaNV`, `MaCoSo`, `TenDN`, `MatKhau`, `TenNhanVien`, `ChucVu`) VALUES
(1, 1, 'admin', '$2y$10$p2xMBCPYswhhhQ1x.oEE0.V9sauinL1EV2AcOg/DSTKTVoz4N7o6a', 'Admin', 'admin');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `uudai`
--

CREATE TABLE `uudai` (
  `MaUD` int(11) NOT NULL,
  `MoTa` text NOT NULL,
  `GiaTriGiam` decimal(10,2) NOT NULL,
  `LoaiGiamGia` enum('phantram','sotien') NOT NULL,
  `DieuKien` text DEFAULT NULL,
  `NgayBD` date NOT NULL,
  `NgayKT` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `ban`
--
ALTER TABLE `ban`
  ADD PRIMARY KEY (`MaBan`),
  ADD KEY `MaCoSo` (`MaCoSo`);

--
-- Chỉ mục cho bảng `chitietdondatban`
--
ALTER TABLE `chitietdondatban`
  ADD PRIMARY KEY (`MaDon`,`MaMon`),
  ADD KEY `MaMon` (`MaMon`);

--
-- Chỉ mục cho bảng `coso`
--
ALTER TABLE `coso`
  ADD PRIMARY KEY (`MaCoSo`);

--
-- Chỉ mục cho bảng `danhmuc`
--
ALTER TABLE `danhmuc`
  ADD PRIMARY KEY (`MaDM`),
  ADD UNIQUE KEY `TenDM` (`TenDM`);

--
-- Chỉ mục cho bảng `dondatban`
--
ALTER TABLE `dondatban`
  ADD PRIMARY KEY (`MaDon`),
  ADD KEY `MaKH` (`MaKH`),
  ADD KEY `MaCoSo` (`MaCoSo`),
  ADD KEY `MaUD` (`MaUD`),
  ADD KEY `MaNV_XacNhan` (`MaNV_XacNhan`);

--
-- Chỉ mục cho bảng `dondatban_ban`
--
ALTER TABLE `dondatban_ban`
  ADD PRIMARY KEY (`MaDon`,`MaBan`),
  ADD KEY `MaBan` (`MaBan`);

--
-- Chỉ mục cho bảng `khachhang`
--
ALTER TABLE `khachhang`
  ADD PRIMARY KEY (`MaKH`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD UNIQUE KEY `SDT` (`SDT`);

--
-- Chỉ mục cho bảng `menu_coso`
--
ALTER TABLE `menu_coso`
  ADD PRIMARY KEY (`MaCoSo`,`MaMon`),
  ADD KEY `MaMon` (`MaMon`);

--
-- Chỉ mục cho bảng `monan`
--
ALTER TABLE `monan`
  ADD PRIMARY KEY (`MaMon`),
  ADD KEY `MaDM` (`MaDM`);

--
-- Chỉ mục cho bảng `nhanvien`
--
ALTER TABLE `nhanvien`
  ADD PRIMARY KEY (`MaNV`),
  ADD UNIQUE KEY `TenDN` (`TenDN`),
  ADD KEY `MaCoSo` (`MaCoSo`);

--
-- Chỉ mục cho bảng `uudai`
--
ALTER TABLE `uudai`
  ADD PRIMARY KEY (`MaUD`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `ban`
--
ALTER TABLE `ban`
  MODIFY `MaBan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `coso`
--
ALTER TABLE `coso`
  MODIFY `MaCoSo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `danhmuc`
--
ALTER TABLE `danhmuc`
  MODIFY `MaDM` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `dondatban`
--
ALTER TABLE `dondatban`
  MODIFY `MaDon` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `khachhang`
--
ALTER TABLE `khachhang`
  MODIFY `MaKH` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `monan`
--
ALTER TABLE `monan`
  MODIFY `MaMon` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `nhanvien`
--
ALTER TABLE `nhanvien`
  MODIFY `MaNV` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `uudai`
--
ALTER TABLE `uudai`
  MODIFY `MaUD` int(11) NOT NULL AUTO_INCREMENT;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `ban`
--
ALTER TABLE `ban`
  ADD CONSTRAINT `ban_ibfk_1` FOREIGN KEY (`MaCoSo`) REFERENCES `coso` (`MaCoSo`);

--
-- Các ràng buộc cho bảng `chitietdondatban`
--
ALTER TABLE `chitietdondatban`
  ADD CONSTRAINT `chitietdondatban_ibfk_1` FOREIGN KEY (`MaDon`) REFERENCES `dondatban` (`MaDon`) ON DELETE CASCADE,
  ADD CONSTRAINT `chitietdondatban_ibfk_2` FOREIGN KEY (`MaMon`) REFERENCES `monan` (`MaMon`);

--
-- Các ràng buộc cho bảng `dondatban`
--
ALTER TABLE `dondatban`
  ADD CONSTRAINT `dondatban_ibfk_1` FOREIGN KEY (`MaKH`) REFERENCES `khachhang` (`MaKH`),
  ADD CONSTRAINT `dondatban_ibfk_2` FOREIGN KEY (`MaCoSo`) REFERENCES `coso` (`MaCoSo`),
  ADD CONSTRAINT `dondatban_ibfk_3` FOREIGN KEY (`MaUD`) REFERENCES `uudai` (`MaUD`),
  ADD CONSTRAINT `dondatban_ibfk_4` FOREIGN KEY (`MaNV_XacNhan`) REFERENCES `nhanvien` (`MaNV`);

--
-- Các ràng buộc cho bảng `dondatban_ban`
--
ALTER TABLE `dondatban_ban`
  ADD CONSTRAINT `dondatban_ban_ibfk_1` FOREIGN KEY (`MaDon`) REFERENCES `dondatban` (`MaDon`) ON DELETE CASCADE,
  ADD CONSTRAINT `dondatban_ban_ibfk_2` FOREIGN KEY (`MaBan`) REFERENCES `ban` (`MaBan`);

--
-- Các ràng buộc cho bảng `menu_coso`
--
ALTER TABLE `menu_coso`
  ADD CONSTRAINT `menu_coso_ibfk_1` FOREIGN KEY (`MaCoSo`) REFERENCES `coso` (`MaCoSo`),
  ADD CONSTRAINT `menu_coso_ibfk_2` FOREIGN KEY (`MaMon`) REFERENCES `monan` (`MaMon`);

--
-- Các ràng buộc cho bảng `monan`
--
ALTER TABLE `monan`
  ADD CONSTRAINT `monan_ibfk_1` FOREIGN KEY (`MaDM`) REFERENCES `danhmuc` (`MaDM`);

--
-- Các ràng buộc cho bảng `nhanvien`
--
ALTER TABLE `nhanvien`
  ADD CONSTRAINT `nhanvien_ibfk_1` FOREIGN KEY (`MaCoSo`) REFERENCES `coso` (`MaCoSo`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
