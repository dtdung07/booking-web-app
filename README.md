## Cấu trúc hiện tại
```
booking-web-app/
├── app/                                # Mã nguồn chính (MVC)
│   ├── controllers/                    # Controller điều hướng luồng, gọi model + render view
│   │   ├── AdminController.php         # Bảo vệ truy cập admin, render dashboard
│   │   ├── AuthController.php          # Luồng đăng nhập/đăng xuất/middleware (khung sẵn)
│   │   ├── BookingController.php       # Khung đặt bàn (index/create/store/success)
│   │   ├── BranchController.php        # Danh sách cơ sở, API JSON, chi tiết, admin view
│   │   ├── ContactController.php       # Khung liên hệ (index/send)
│   │   ├── HomeController.php          # Trang chủ: load cơ sở, nhóm theo địa chỉ
│   │   └── MenuController.php          # Menu + trang menu2, API JSON lọc danh mục
│   ├── models/                         # Model thao tác CSDL (PDO)
│   │   ├── CoSo.php                    # CRUD bảng coso (getAll/getByAddress/getById/…)
│   │   └── User.php                    # Bảng nhanvien: login, đổi mật khẩu, branch name
│   └── views/                          # View hiển thị (kèm layout)
│       ├── admin/                      # Khu vực quản trị (dashboard + module CRUD)
│       │   ├── branches/               # Quản lý cơ sở (admin)
│       │   ├── categories/             # Quản lý danh mục: form + process-*.php
│       │   ├── menu/                   # Quản lý món/menu: form + process-*.php
│       │   └── user/                   # Quản lý người dùng: form + process-*.php
│       ├── branches/                   # Trang danh sách/chi tiết cơ sở (frontend)
│       ├── coso/                       # Tài liệu HTML liên quan cơ sở (tĩnh)
│       ├── datban/                     # Trang đặt bàn (tĩnh/khung)
│       ├── home/                       # Trang chủ
│       ├── layouts/                    # Layout, header, footer, khai báo CSS/JS
│       ├── menu/                       # Trang menu cơ bản
│       └── menu2/                      # Trang menu nâng cao (filter, AJAX)
├── config/                             # Cấu hình ứng dụng
│   ├── config.php                      # Hằng số, helper (vd: asset())
│   └── database.php                    # Kết nối PDO (UTF-8, ERRMODE_EXCEPTION)
├── database/                           # Script SQL dựng dữ liệu/mẫu
│   ├── booking_restaurant.sql          # Schema chính
│   ├── add_sample_users.sql            # Dữ liệu mẫu user/nhân viên
│   ├── add_sample_menu_data.sql        # Dữ liệu mẫu menu
│   ├── add_more_menu_items.sql         # Thêm món mẫu
│   ├── coso.sql                        # Dữ liệu mẫu cơ sở
│   └── update_*.sql                    # Các script cập nhật bảng
├── includes/                           # Thành phần dùng chung
│   ├── BaseController.php              # Base controller: render/redirect/json
│   └── auth.php                        # (Hiện trống/chưa dùng)
├── public/                             # Tài nguyên tĩnh (static assets)
│   ├── css/                            # CSS chia theo pages/components/layout
│   ├── fonts/                          # Phông chữ
│   ├── images/                         # Ảnh nền/ảnh trang
│   ├── js/                             # JS frontend (menu2.js, script.js)
│   └── videos/                         # Video nền/trình chiếu
├── ADMIN_GUIDE.md                      # Hướng dẫn quản trị
├── README.md                           # Hướng dẫn dự án (cấu trúc, chạy, mô tả)
├── index.php                           # Entry point + router (?page=&action=)
└── login.php                           # Trang đăng nhập Admin độc lập (POST + UI)
```