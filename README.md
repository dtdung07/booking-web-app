# HƯỚNG DẪN CÀI ĐẶT VÀ CẤU HÌNH DỰ ÁN
## Website đặt bàn & thực đơn nhà hàng - Booking Web App

### I. TỔNG QUAN DỰ ÁN

Đây là một hệ thống đặt bàn nhà hàng xây dựng bằng PHP với mô hình MVC, hỗ trợ:

- **Quản lý đa cơ sở nhà hàng**
- **Hệ thống đặt bàn online**  
- **Menu theo từng cơ sở**
- **Quản lý nhân viên và admin**
- **Tích hợp thanh toán trực tuyến QR code**
- **Quản lý khuyến mãi và ưu đãi**

---

## II. YÊU CẦU HỆ THỐNG

### Server Requirements
- **PHP**: >= 7.4 (Khuyến nghị 8.0)
- **MySQL/MariaDB**: >= 5.7 
- **Apache/Nginx**: Web server
- **XAMPP/LAMP**: Cho môi trường phát triển
---

## III. CÀI ĐẶT MÔI TRƯỜNG

### 1. Cài đặt XAMPP (Windows)
```bash
# Tải XAMPP
# Cài đặt và khởi động:
- Apache (Port 80)
- MySQL (Port 3306)
```

### 2. Clone/Copy dự án
```bash
# Đặt dự án vào thư mục htdocs của XAMPP
C:\xampp\htdocs\booking-web-app\
```

### 3. Cấu trúc thư mục chính
```
booking-web-app/
├── app/                    # Mã nguồn MVC
│   ├── controllers/        # Các Controller (Admin, Auth, Booking, Menu...)
│   ├── models/            # Các Model (Booking, Branch, Menu, Table...)  
│   └── views/             # Các View (admin (nhân viên), client)
├── config/                # File cấu hình (database, config, connect)
├── database/              # FIle SQL
├── public/                # Assets (CSS, JS, Images, Fonts, Videos)
├── includes/              # Dịch vụ gửi Mail
├── libs/                  # Thư viện bên thứ 3 (PHPMailer)
├── sepay/                 # Tích hợp thanh toán trực tuyến QR code (payment, webhook, invoice)
├── index.php              # File chính điều hướng
├── login.php              # Trang đăng nhập Admin/Nhân viên
└── *README.md                   # File README hướng dẫn
```

---

## IV. CẤU HÌNH DATABASE

### 1. Tạo Database
```sql
-- Truy cập phpMyAdmin: http://localhost/phpmyadmin
-- Tạo database mới tên là `booking_restaurant`
```

### 2. Import file SQL
```bash
# Import file SQL chính:
1. Mở phpMyAdmin
2. Chọn database 'booking_restaurant'  
3. Click tab 'Import'
4. Chọn file: database/booking_restaurant.sql
5. Click Ok để import
```

### 3. Cấu trúc Database chính

**Các bảng quan trọng:**

**Quản lý cơ sở:**
- `coso` - Thông tin các cơ sở nhà hàng (tên, địa chỉ, SĐT, hình ảnh)
- `ban` - Danh sách bàn theo từng cơ sở (tên bàn, sức chứa)

**Quản lý menu:**
- `danhmuc` - Danh mục món ăn
- `monan` - Danh sách món ăn tổng (tên món, mô tả, hình ảnh)
- `menu_coso` - Giá món ăn và tình trạng theo từng cơ sở (hỗ trợ giá khác nhau cho cùng món)

**Quản lý đặt bàn:**
- `khachhang` - Thông tin khách hàng (tên, email, SĐT)
- `dondatban` - Đơn đặt bàn chính (khách hàng, cơ sở, thời gian, trạng thái, ghi chú)
- `dondatban_ban` - Liên kết đơn đặt với bàn cụ thể (hỗ trợ đặt nhiều bàn)
- `chitietdondatban` - Chi tiết món ăn trong đơn (món, số lượng, đơn giá)

**Quản lý hệ thống:**
- `nhanvien` - Tài khoản nhân viên/admin (username, password, chức vụ, cơ sở làm việc)
- `uudai` - Mã giảm giá và ưu đãi (tên mã, giá trị giảm, loại giảm theo % hoặc số tiền, điều kiện, thời gian hiệu lực)

---

## V. CẤU HÌNH ỨNG DỤNG

### 1. Cấu hình Database Connection

**Cần cấu hình các trường kết nối ở file: `config/database.php`**
```php
<?php
class Database {
    private $host = "localhost";        // MySQL Host
    private $db_name = "booking_restaurant";  // Tên Database  
    private $username = "root";         // MySQL Username
    private $password = "";             // MySQL Password (XAMPP default: rỗng)
    public $conn;
}
```

### 2. Cấu hình URL và SMTP

**File: `config/config.php`** 
```php
// Cập nhật URL base cho môi trường của bạn:
define('BASE_URL', 'http://localhost/booking-web-app');  // URL webside
define('ASSETS_URL', BASE_URL . '/public');

// Cấu hình email SMTP (cần thiết để gửi mail thanh toán):
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 465);
define('SMTP_USERNAME', 'your-email@gmail.com');
define('SMTP_PASSWORD', 'password');
```

### 3. Tài khoản mặc định trong Dashboard quản trị (user/pass) như sau:

**Tài khoản Admin:** admin/admin

**Tài khoản Nhân viên:** dung/dung

---

## VI. CHẠY DỰ ÁN

### 1. Khởi động Services
```bash
# Khởi động XAMPP:
1. Mở XAMPP Control Panel
2. Start Apache  
3. Start MySQL
4. Kiểm tra trạng thái: Running và hiện Port
```

### 2. Truy cập ứng dụng

**Frontend (Client):**
```
http://localhost/booking-web-app/

hoặc

https://mydomain.com (cần tự cấu hình)
```

**Admin Panel:**
```
http://localhost/booking-web-app/login.php

hoặc

http://localhost/booking-web-app/?page=admin

- Username: admin
- Password: admin
```
---

## VII. HƯỚNG DẪN SỬ DỤNG

### A. Khách hàng (Frontend)

**1. Xem danh sách cơ sở**
- Truy cập: `http://localhost/booking-web-app/`
- Các cơ sở được nhóm theo địa chỉ

**2. Xem menu**  
- Click "Xem menu" tại cơ sở
- Menu được phân theo danh mục món

**3. Đặt bàn**
- Chọn cơ sở muốn đặt
- Điền thông tin: Tên, SĐT, Email (để nhận mail thanh toán), Số khách, Thời gian
- Chọn món ăn
- Áp dụng mã giảm giá (tùy chọn)

### B. Admin/Nhân viên (Backend)

**1. Đăng nhập Admin**
```
URL: /login.php
Username: admin  
Password: admin123
```

**2. Dashboard chính**
- Tổng quan thống kê
- Chức năng thao tác nhanh

**3. Quản lý cơ sở**
- Thêm/sửa/xóa cơ sở
- Quản lý thông tin liên hệ

**4. Quản lý danh mục, menu món**
- Tạo danh mục món ăn
- Thêm món ăn mới  
- Thiết lập giá theo từng cơ sở
- Trạng thái còn hàng/hết hàng

**5. Quản lý đặt bàn**
- Xem danh sách đơn đặt
- Xác nhận/hủy đơn
- Quản lý trạng thái bàn
- Tạo hóa đơn (Nhân viên)

**6. Quản lý khuyến mãi**
- Tạo mới/Sửa/Xóa mã giảm giá
---

## VIII. Team

- **Nhóm 03** - WEBSITE ĐẶT BÀN & THỰC ĐƠN NHÀ HÀNG
1.	Vũ Văn Tín	MSSV: 2221050564
2.	Đặng Trí Dũng	MSSV: 2221050407
3.  Nguyễn Hữu Tuấn MSSV: 2121051096
4.  Phạm Tuấn Bảo MSSV: 2121050893