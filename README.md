# Hệ thống Đặt bàn Nhà hàng Online

Hệ thống quản lý đặt bàn nhà hàng được phát triển bằng PHP thuần với kiến trúc MVC, giao diện responsive và đầy đủ các tính năng cơ bản.

## 🚀 Tính năng chính

### Dành cho Khách hàng
- ✅ Xem thực đơn với phân loại món ăn
- ✅ Đặt bàn online với lựa chọn thời gian
- ✅ Kiểm tra tình trạng bàn trống
- ✅ Đăng ký/Đăng nhập tài khoản
- ✅ Quản lý thông tin cá nhân
- ✅ Xem lịch sử đặt bàn
- ✅ Hủy đặt bàn
- ✅ Liên hệ với nhà hàng

### Dành cho Quản lý
- ✅ Quản lý đặt bàn (xác nhận, hủy)
- ✅ Quản lý thực đơn và danh mục
- ✅ Quản lý bàn ăn
- ✅ Quản lý khách hàng
- ✅ Báo cáo thống kê
- ✅ Cài đặt hệ thống

## 🛠️ Công nghệ sử dụng

- **Backend**: PHP 7.4+ (Pure PHP, không framework)
- **Database**: MySQL 5.7+
- **Frontend**: HTML5, CSS3, JavaScript (jQuery)
- **UI Framework**: Bootstrap 5
- **Icons**: Font Awesome 6
- **Architecture**: MVC Pattern

## 📂 Cấu trúc thư mục

```
booking-web-app/
├── app/
│   ├── controllers/          # Controllers (MVC)
│   │   ├── HomeController.php
│   │   ├── BookingController.php
│   │   ├── AuthController.php
│   │   ├── MenuController.php
│   │   └── ContactController.php
│   ├── models/              # Models (sẽ được thêm)
│   └── views/               # Views (MVC)
│       ├── layouts/         # Layout chung
│       ├── home/           # Trang chủ
│       ├── booking/        # Đặt bàn
│       ├── menu/           # Thực đơn
│       ├── auth/           # Đăng nhập/ký
│       └── contact/        # Liên hệ
├── config/
│   ├── database.php        # Cấu hình database
│   └── config.php          # Cấu hình chung
├── public/                 # Tài nguyên công khai
│   ├── css/
│   ├── js/
│   └── images/
├── database/
│   └── booking_restaurant.sql  # Database schema
├── includes/
│   └── BaseController.php  # Base controller
├── admin/                  # Panel quản trị
│   └── views/
├── index.php              # Điểm vào chính
├── .htaccess             # URL rewriting
└── README.md
```

## ⚙️ Cài đặt và Cấu hình

### 1. Yêu cầu hệ thống
- PHP 7.4 hoặc cao hơn
- MySQL 5.7 hoặc MariaDB 10.2+
- Apache/Nginx với mod_rewrite
- Extension: PDO, PDO_MySQL, mbstring

### 2. Cài đặt

1. **Clone/Download dự án**
```bash
git clone https://github.com/your-username/booking-web-app.git
cd booking-web-app
```

2. **Tạo database**
```sql
-- Import file database/booking_restaurant.sql vào MySQL
mysql -u root -p < database/booking_restaurant.sql
```

3. **Cấu hình database**
Chỉnh sửa file `config/database.php`:
```php
private $host = 'localhost';
private $db_name = 'booking_restaurant';
private $username = 'root';
private $password = 'your_password';
```

4. **Cấu hình URL**
Chỉnh sửa file `config/config.php`:
```php
define('BASE_URL', 'http://localhost/booking-web-app');
```

5. **Thiết lập quyền thư mục**
```bash
chmod 755 public/images/
chmod 755 public/css/
chmod 755 public/js/
```

### 3. Cấu hình Virtual Host (Tùy chọn)

**Apache:**
```apache
<VirtualHost *:80>
    ServerName booking.local
    DocumentRoot "C:/path/to/booking-web-app"
    <Directory "C:/path/to/booking-web-app">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

Thêm vào file hosts:
```
127.0.0.1 booking.local
```

## 🗃️ Database Schema

### Bảng chính:
- `users` - Thông tin người dùng
- `categories` - Danh mục món ăn
- `dishes` - Món ăn
- `tables` - Bàn ăn
- `bookings` - Đặt bàn
- `booking_items` - Chi tiết đặt món
- `contacts` - Liên hệ
- `reviews` - Đánh giá
- `settings` - Cài đặt hệ thống

### Tài khoản mặc định:
- **Admin**: admin@restaurant.com / password

## 🎯 Hướng dẫn sử dụng

### Khách hàng:
1. Truy cập trang chủ để xem thông tin nhà hàng
2. Xem thực đơn tại `/thuc-don`
3. Đặt bàn tại `/dat-ban`
4. Đăng ký tài khoản để quản lý đặt bàn

### Quản trị viên:
1. Truy cập `/admin` để vào panel quản trị
2. Đăng nhập với tài khoản admin
3. Quản lý đặt bàn, thực đơn, khách hàng

## 🔧 Tùy chỉnh

### Thay đổi giao diện:
- Chỉnh sửa file CSS tại `public/css/style.css`
- Thay đổi layout tại `app/views/layouts/`

### Thêm tính năng:
1. Tạo Controller mới trong `app/controllers/`
2. Tạo View tương ứng trong `app/views/`
3. Cập nhật routing trong `index.php`

### Cấu hình email:
Chỉnh sửa trong `config/config.php`:
```php
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_USERNAME', 'your-email@gmail.com');
define('SMTP_PASSWORD', 'your-password');
```

## 🚦 URL Routes

- `/` - Trang chủ
- `/dat-ban` - Đặt bàn
- `/thuc-don` - Thực đơn
- `/dang-nhap` - Đăng nhập
- `/dang-ky` - Đăng ký
- `/lien-he` - Liên hệ
- `/admin` - Quản trị

## 🔒 Bảo mật

- Sử dụng PDO Prepared Statements
- Password hashing với bcrypt
- CSRF protection (cần implement)
- Input validation và sanitization
- SQL injection prevention
- XSS protection

## 📱 Responsive Design

Giao diện được thiết kế responsive, tương thích với:
- Desktop (1200px+)
- Tablet (768px - 1199px)
- Mobile (< 768px)

## 🔄 API Endpoints (Dự kiến)

- `GET /api/tables` - Danh sách bàn
- `POST /api/bookings` - Tạo đặt bàn
- `GET /api/dishes` - Danh sách món ăn
- `POST /api/contact` - Gửi liên hệ

## 🐛 Troubleshooting

### Lỗi thường gặp:

1. **Lỗi 500 - Internal Server Error**
   - Kiểm tra file .htaccess
   - Kiểm tra quyền thư mục
   - Kiểm tra log Apache/PHP

2. **Không kết nối được database**
   - Kiểm tra thông tin trong config/database.php
   - Đảm bảo MySQL đang chạy
   - Kiểm tra tên database

3. **CSS/JS không load**
   - Kiểm tra đường dẫn BASE_URL
   - Kiểm tra quyền thư mục public/

## 📝 TODO List

- [ ] Thêm tính năng thanh toán online
- [ ] Implement WebSocket cho real-time updates
- [ ] Thêm notification system
- [ ] Mobile app API
- [ ] Multi-language support
- [ ] Email templates
- [ ] SMS notifications
- [ ] Advanced reporting
- [ ] Inventory management
- [ ] Staff management

## 🤝 Đóng góp

1. Fork dự án
2. Tạo feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Tạo Pull Request

## 📄 License

Dự án này được phát hành dưới [MIT License](LICENSE).

## 👨‍💻 Tác giả

- **Nhóm 03** - Dự án Website đặt bàn & thực đơn nhà hàng

## 📞 Hỗ trợ

Nếu bạn gặp vấn đề hoặc có câu hỏi:

- 📧 Email: support@restaurant.com
- 🐛 Issues: [GitHub Issues](https://github.com/your-username/booking-web-app/issues)

---

⭐ Nếu dự án hữu ích, hãy cho một star để ủng hộ!
