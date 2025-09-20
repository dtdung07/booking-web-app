# HƯỚNG DẪN SỬ DỤNG HỆ THỐNG ADMIN

## 🔐 Hệ thống đăng nhập Admin đã hoàn thành

### Tính năng đã triển khai:

#### 1. **Giao diện đăng nhập Admin độc lập**
- **File**: `app/views/auth/admin-login.php`
- **URL**: `http://localhost/booking-web-app/?page=auth&action=login`
- **Đặc điểm**: 
  - Giao diện hoàn toàn tách biệt khỏi website chính
  - Thiết kế hiện đại với gradient background
  - Responsive design cho mobile và desktop
  - Form validation với JavaScript
  - Loading states và transitions

#### 2. **Admin Dashboard**
- **File**: `app/views/admin/dashboard.php`
- **URL**: `http://localhost/booking-web-app/?page=admin&action=dashboard`
- **Tính năng**:
  - Sidebar navigation với các menu chính
  - Dashboard cards hiển thị thống kê
  - Quick actions cho các thao tác nhanh
  - User info với avatar và logout button
  - Responsive design

#### 3. **System Controllers**
- **AuthController**: Xử lý đăng nhập/đăng xuất
- **AdminController**: Quản lý admin dashboard
- **User Model**: Tương tác với database nhanvien

### 🚀 Cách sử dụng:

#### Bước 1: Chuẩn bị Database
```sql
-- Import file database/booking_restaurant.sql
-- Chạy file database/add_sample_users.sql để tạo tài khoản test
```

#### Bước 2: Truy cập Admin Login
```
URL: http://localhost/booking-web-app/?page=auth&action=login
```

#### Bước 3: Đăng nhập với tài khoản mẫu
```
Username: admin
Password: admin123

-- hoặc --

Username: manager
Password: manager123
```

#### Bước 4: Truy cập Dashboard
Sau khi đăng nhập thành công, hệ thống sẽ tự động chuyển đến:
```
URL: http://localhost/booking-web-app/?page=admin&action=dashboard
```

### 📁 Cấu trúc file đã tạo:

```
booking-web-app/
├── app/
│   ├── controllers/
│   │   ├── AuthController.php      # Xử lý authentication
│   │   └── AdminController.php     # Quản lý admin functions
│   ├── models/
│   │   └── User.php               # Model cho nhanvien table
│   └── views/
│       ├── auth/
│       │   ├── admin-login.php    # Standalone admin login
│       │   └── profile.php        # User profile management
│       └── admin/
│           └── dashboard.php      # Admin dashboard
├── database/
│   ├── booking_restaurant.sql     # Database schema
│   └── add_sample_users.sql       # Sample users
└── index.php                     # Main routing file
```

### 🔧 Cấu hình và Customization:

#### Thay đổi styling:
- CSS được embed trực tiếp trong file PHP
- Có thể tách ra thành file CSS riêng nếu cần
- Color scheme sử dụng gradient #667eea đến #764ba2

#### Thêm menu mới:
Trong file `app/views/admin/dashboard.php`, thêm vào sidebar:
```html
<li class="nav-item">
    <a href="?page=new_page" class="nav-link">
        <i class="fas fa-icon"></i>
        Menu Name
    </a>
</li>
```

#### Security Features:
- Session-based authentication
- Password hashing (hỗ trợ cả plain text và hash)
- Remember me functionality
- Auto-redirect sau khi đăng nhập
- Logout với session cleanup

### 🎯 Next Steps:

1. **Database Integration**: Import database và test login
2. **Feature Development**: Phát triển các chức năng quản lý
3. **Security Enhancement**: Thêm role-based access control
4. **UI/UX Improvement**: Tùy chỉnh giao diện theo ý muốn

### ⚠️ Lưu ý quan trọng:

- Admin login hoàn toàn tách biệt khỏi website chính
- Không sử dụng layout của website chính
- Tự động redirect đến dashboard sau login
- Session timeout có thể cấu hình trong PHP settings
- Remember me cookie expires sau 30 ngày

### 🔍 Troubleshooting:

#### Lỗi không đăng nhập được:
1. Kiểm tra database connection
2. Verify user credentials trong database
3. Check PHP session configuration

#### Lỗi routing:
1. Đảm bảo file index.php có admin routing
2. Check .htaccess nếu sử dụng
3. Verify controller files exist

#### Styling issues:
1. Check CSS conflicts
2. Verify Font Awesome CDN
3. Test responsive breakpoints
