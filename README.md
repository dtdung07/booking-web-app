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
├── database/              # File SQL
├── public/                # Assets (CSS, JS, Images, Fonts, Videos)
├── includes/              # Dịch vụ gửi Mail
├── libs/                  # Thư viện bên thứ 3 (PHPMailer)
├── sepay/                 # Tích hợp thanh toán trực tuyến QR code (payment, webhook, invoice)
├── .env.example           # File mẫu cấu hình môi trường (copy thành .env để sử dụng)
├── .htaccess              # Cấu hình Apache (rewrite, security, cache)
├── .user.ini              # Cấu hình PHP cho hosting CGI/FastCGI
├── index.php              # File chính điều hướng
├── login.php              # Trang đăng nhập Admin/Nhân viên
└── README.md              # File README hướng dẫn
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

### 1. Cấu hình bằng file `.env` (Khuyến nghị)

**Bước 1: Copy file mẫu `.env.example` thành `.env`**
```bash
# Windows (Command Prompt)
copy .env.example .env

# Windows (PowerShell)
Copy-Item .env.example .env

# Linux/Mac
cp .env.example .env
```

**Bước 2: Chỉnh sửa file `.env` theo môi trường**

**Cho LOCAL (XAMPP) - giữ nguyên giá trị mặc định:**
```env
# CAU HINH DATABASE
DB_HOST=localhost
DB_NAME=booking_restaurant
DB_USER=root
DB_PASS=
DB_PORT=3306

# CAU HINH URL
BASE_URL=http://localhost/booking-web-app

# CAU HINH EMAIL (SMTP)
SMTP_HOST=smtp.gmail.com
SMTP_PORT=465
SMTP_USERNAME=your-email@gmail.com
SMTP_PASSWORD=your-app-password
```

**Cho SERVER/HOSTING - thay đổi theo thông tin hosting:**
```env
# CAU HINH DATABASE - Thay doi theo thong tin hosting
DB_HOST=localhost
DB_NAME=ten_database_tren_hosting
DB_USER=username_database_hosting
DB_PASS=password_database_hosting
DB_PORT=3306

# CAU HINH URL
BASE_URL=https://yourdomain.com/path-to-project

# CAU HINH EMAIL (SMTP)
SMTP_HOST=smtp.gmail.com
SMTP_PORT=465
SMTP_USERNAME=your-email@gmail.com
SMTP_PASSWORD=your-app-password
```

> **Lưu ý:** 
> - File `.env.example` là file mẫu, có thể commit lên git
> - File `.env` chứa thông tin nhạy cảm, đã được thêm vào `.gitignore` để không commit lên git

### 2. Cấu hình thủ công (Tùy chọn)

**Nếu không dùng file `.env`, có thể cấu hình trực tiếp:**

**File: `config/connect.php`**
```php
<?php
    $host = 'localhost';                // MySQL Host
    $user = 'root';                     // MySQL Username
    $pass = '';                         // MySQL Password (XAMPP default: rỗng)
    $database = 'booking_restaurant';   // Tên Database
    $port = '3306';                     // MySQL Port

    $conn = mysqli_connect($host, $user, $pass, $database, $port);
```

**File: `config/database.php`**
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

## VIII. DEPLOY LÊN SERVER/HOSTING

### 1. Chuẩn bị trên Hosting

**Bước 1: Tạo Database trên hosting**
```bash
# Truy cập panel quản trị hosting (cPanel, DirectAdmin, Plesk...)
# Vào phần MySQL Databases:
1. Tạo database mới (VD: username_booking)
2. Tạo user database mới (VD: username_dbuser)
3. Gán quyền ALL PRIVILEGES cho user vào database
4. Ghi lại thông tin: DB_NAME, DB_USER, DB_PASS
```

**Bước 2: Import file SQL**
```bash
# Trong phpMyAdmin của hosting:
1. Chọn database vừa tạo
2. Click tab 'Import'
3. Chọn file: database/booking_restaurant.sql
4. Click 'Go' để import
```

### 2. Upload và Cấu hình

**Bước 1: Upload toàn bộ source code lên hosting**
```bash
# Sử dụng FTP hoặc File Manager của hosting
# Upload vào thư mục public_html hoặc subdomain tương ứng
```

**Bước 2: Tạo file `.env` trên server**
```bash
# Copy file mẫu thành file .env
cp .env.example .env

# Hoặc tạo mới file .env với nội dung:
```
```env
DB_HOST=localhost
DB_NAME=username_booking
DB_USER=username_dbuser
DB_PASS=mat_khau_database
DB_PORT=3306

BASE_URL=https://yourdomain.com

SMTP_HOST=smtp.gmail.com
SMTP_PORT=465
SMTP_USERNAME=your-email@gmail.com
SMTP_PASSWORD=your-app-password
```

**Bước 3: Kiểm tra quyền file/thư mục**
```bash
# Đảm bảo quyền truy cập đúng:
- Thư mục: 755
- File: 644
- File .env: 600 (bảo mật)
```

### 3. Xử lý lỗi thường gặp

**Lỗi HTTP 500:**
- Kiểm tra file `.htaccess` có tương thích với hosting không
- Kiểm tra file `.user.ini` đã được upload chưa
- Xem error log trong panel hosting

**Lỗi kết nối Database:**
- Kiểm tra thông tin DB_HOST, DB_NAME, DB_USER, DB_PASS trong file `.env`
- Đảm bảo user database có quyền truy cập

**Lỗi đường dẫn/URL:**
- Kiểm tra BASE_URL trong file `.env` đã đúng chưa
- Đảm bảo không có dấu `/` ở cuối URL

---

## IX. TEAM

- **Nhóm 03** - WEBSITE ĐẶT BÀN & THỰC ĐƠN NHÀ HÀNG

| STT | Họ và Tên | MSSV |
|-----|-----------|------|
| 1 | Vũ Văn Tín | 2221050564 |
| 2 | Đặng Trí Dũng | 2221050407 |
| 3 | Nguyễn Hữu Tuấn | 2121051096 |
| 4 | Phạm Tuấn Bảo | 2121050893 |

---

**© 2024 - Nhóm 03 - Website đặt bàn & thực đơn nhà hàng**