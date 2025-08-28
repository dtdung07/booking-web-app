-- Database: booking_restaurant
-- Tạo cơ sở dữ liệu cho hệ thống đặt bàn nhà hàng

-- Tạo database
CREATE DATABASE IF NOT EXISTS booking_restaurant CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE booking_restaurant;

-- Bảng người dùng
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20),
    password VARCHAR(255) NOT NULL,
    role ENUM('customer', 'admin', 'manager') DEFAULT 'customer',
    status ENUM('active', 'inactive', 'banned') DEFAULT 'active',
    email_verified BOOLEAN DEFAULT FALSE,
    remember_token VARCHAR(100),
    last_login DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Bảng danh mục món ăn
CREATE TABLE categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) UNIQUE NOT NULL,
    description TEXT,
    image VARCHAR(255),
    sort_order INT DEFAULT 0,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Bảng món ăn
CREATE TABLE dishes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    category_id INT,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) UNIQUE NOT NULL,
    description TEXT,
    ingredients TEXT,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    is_featured BOOLEAN DEFAULT FALSE,
    is_available BOOLEAN DEFAULT TRUE,
    cooking_time INT DEFAULT 0, -- phút
    calories INT,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Bảng bàn ăn
CREATE TABLE tables (
    id INT PRIMARY KEY AUTO_INCREMENT,
    table_number VARCHAR(10) NOT NULL,
    capacity INT NOT NULL,
    location VARCHAR(100), -- VD: Tầng 1, Khu vườn, VIP, etc.
    description TEXT,
    status ENUM('active', 'maintenance', 'reserved') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Bảng đặt bàn
CREATE TABLE bookings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    table_id INT,
    customer_name VARCHAR(100) NOT NULL,
    customer_phone VARCHAR(20) NOT NULL,
    customer_email VARCHAR(100),
    booking_date DATE NOT NULL,
    booking_time TIME NOT NULL,
    guests INT NOT NULL DEFAULT 1,
    special_requests TEXT,
    status ENUM('pending', 'confirmed', 'completed', 'cancelled', 'no_show') DEFAULT 'pending',
    notes TEXT, -- Ghi chú của admin
    total_amount DECIMAL(10,2) DEFAULT 0,
    deposit_amount DECIMAL(10,2) DEFAULT 0,
    deposit_status ENUM('none', 'paid', 'refunded') DEFAULT 'none',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (table_id) REFERENCES tables(id) ON DELETE SET NULL
);

-- Bảng chi tiết đặt món (nếu có đặt món trước)
CREATE TABLE booking_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    booking_id INT NOT NULL,
    dish_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    price DECIMAL(10,2) NOT NULL,
    notes VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
    FOREIGN KEY (dish_id) REFERENCES dishes(id) ON DELETE CASCADE
);

-- Bảng liên hệ
CREATE TABLE contacts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('new', 'processing', 'replied', 'closed') DEFAULT 'new',
    admin_reply TEXT,
    replied_by INT,
    replied_at DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (replied_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Bảng tin tức/bài viết
CREATE TABLE posts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    content TEXT NOT NULL,
    excerpt TEXT,
    featured_image VARCHAR(255),
    author_id INT,
    status ENUM('draft', 'published', 'archived') DEFAULT 'draft',
    is_featured BOOLEAN DEFAULT FALSE,
    views INT DEFAULT 0,
    published_at DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Bảng đánh giá
CREATE TABLE reviews (
    id INT PRIMARY KEY AUTO_INCREMENT,
    booking_id INT,
    user_id INT,
    customer_name VARCHAR(100) NOT NULL,
    customer_email VARCHAR(100),
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    title VARCHAR(200),
    comment TEXT,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE SET NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Bảng cài đặt hệ thống
CREATE TABLE settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    key_name VARCHAR(100) UNIQUE NOT NULL,
    value TEXT,
    description VARCHAR(255),
    type ENUM('text', 'number', 'boolean', 'json') DEFAULT 'text',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Bảng log hoạt động
CREATE TABLE activity_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    action VARCHAR(100) NOT NULL,
    description TEXT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Chèn dữ liệu mẫu

-- Admin user
INSERT INTO users (name, email, phone, password, role, status, email_verified) VALUES
('Admin', 'admin@restaurant.com', '0123456789', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'active', TRUE);

-- Danh mục món ăn
INSERT INTO categories (name, slug, description, sort_order) VALUES
('Món khai vị', 'mon-khai-vi', 'Các món ăn nhẹ để bắt đầu bữa ăn', 1),
('Món chính', 'mon-chinh', 'Các món ăn chính của nhà hàng', 2),
('Món tráng miệng', 'mon-trang-mieng', 'Các món ngọt kết thúc bữa ăn', 3),
('Đồ uống', 'do-uong', 'Nước uống, trà, cà phê', 4),
('Món chay', 'mon-chay', 'Các món ăn chay thanh đạm', 5);

-- Món ăn mẫu
INSERT INTO dishes (category_id, name, slug, description, price, is_featured, is_available) VALUES
(1, 'Gỏi cuốn tôm thịt', 'goi-cuon-tom-thit', 'Gỏi cuốn tươi với tôm, thịt ba chỉ, bún và rau thơm', 45000, TRUE, TRUE),
(1, 'Chả cá Lã Vọng', 'cha-ca-la-vong', 'Món chả cá truyền thống Hà Nội với thì là và bánh tráng', 120000, TRUE, TRUE),
(2, 'Phở bò tái', 'pho-bo-tai', 'Phở bò với thịt tái, nước dùng đậm đà', 65000, TRUE, TRUE),
(2, 'Bún bò Huế', 'bun-bo-hue', 'Bún bò Huế cay nồng đặc trưng miền Trung', 70000, TRUE, TRUE),
(2, 'Cơm tấm sườn nướng', 'com-tam-suon-nuong', 'Cơm tấm với sườn nướng, chả trứng, bì', 80000, FALSE, TRUE),
(3, 'Chè ba màu', 'che-ba-mau', 'Chè truyền thống với đậu xanh, đậu đỏ, thạch', 25000, FALSE, TRUE),
(4, 'Trà đá', 'tra-da', 'Trà đá truyền thống', 10000, FALSE, TRUE),
(4, 'Nước mía', 'nuoc-mia', 'Nước mía tươi mát', 20000, FALSE, TRUE);

-- Bàn ăn mẫu
INSERT INTO tables (table_number, capacity, location, status) VALUES
('B01', 2, 'Tầng 1', 'active'),
('B02', 4, 'Tầng 1', 'active'),
('B03', 4, 'Tầng 1', 'active'),
('B04', 6, 'Tầng 1', 'active'),
('B05', 8, 'Tầng 1', 'active'),
('VIP01', 10, 'Phòng VIP', 'active'),
('VIP02', 12, 'Phòng VIP', 'active');

-- Cài đặt hệ thống
INSERT INTO settings (key_name, value, description, type) VALUES
('restaurant_name', 'Nhà hàng ABC', 'Tên nhà hàng', 'text'),
('restaurant_address', '123 Đường ABC, Quận 1, TP.HCM', 'Địa chỉ nhà hàng', 'text'),
('restaurant_phone', '028 3333 4444', 'Số điện thoại nhà hàng', 'text'),
('restaurant_email', 'info@restaurant.com', 'Email nhà hàng', 'text'),
('opening_hours', '10:00 - 22:00', 'Giờ mở cửa', 'text'),
('booking_advance_days', '30', 'Số ngày có thể đặt trước', 'number'),
('min_booking_hours', '2', 'Số giờ tối thiểu trước khi đặt bàn', 'number'),
('max_guests_per_booking', '20', 'Số khách tối đa cho một lần đặt', 'number'),
('require_deposit', '0', 'Yêu cầu đặt cọc hay không', 'boolean'),
('deposit_percentage', '20', 'Phần trăm tiền cọc', 'number');

-- Index để tối ưu hiệu suất
CREATE INDEX idx_bookings_date_time ON bookings(booking_date, booking_time);
CREATE INDEX idx_bookings_status ON bookings(status);
CREATE INDEX idx_dishes_category ON dishes(category_id);
CREATE INDEX idx_dishes_featured ON dishes(is_featured);
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_role ON users(role);
