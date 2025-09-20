-- Thêm dữ liệu mẫu nhân viên vào bảng nhanvien
-- Mật khẩu mặc định: 123456 (đã được hash)

USE booking_restaurant;

-- Thêm tài khoản admin
INSERT INTO nhanvien (MaCoSo, TenDN, MatKhau, TenNhanVien, ChucVu) VALUES
(1, 'admin', '111', 'Quản trị viên hệ thống', 'admin')
ON DUPLICATE KEY UPDATE TenDN=TenDN;

-- Thêm một số nhân viên mẫu
INSERT INTO nhanvien (MaCoSo, TenDN, MatKhau, TenNhanVien, ChucVu) VALUES
(1, 'nhanvien1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Nguyễn Văn A', 'nhan_vien'),
(1, 'nhanvien2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Trần Thị B', 'nhan_vien'),
(2, 'nhanvien3', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Lê Văn C', 'nhan_vien')
ON DUPLICATE KEY UPDATE TenDN=TenDN;

-- Kiểm tra dữ liệu đã thêm
SELECT MaNV, TenDN, TenNhanVien, ChucVu, 
       (SELECT TenCoSo FROM coso WHERE coso.MaCoSo = nhanvien.MaCoSo) as TenCoSo
FROM nhanvien;

-- Thông tin đăng nhập:
-- admin / 123456 (Quản trị viên)
-- nhanvien1 / 123456 (Nhân viên)
-- nhanvien2 / 123456 (Nhân viên)
-- nhanvien3 / 123456 (Nhân viên)
