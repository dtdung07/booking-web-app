<div class="hero-section bg-primary text-white text-center py-5" style="margin-top: 76px;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">
                    Chào mừng đến với <?= APP_NAME ?>
                </h1>
                <p class="lead mb-4">
                    Trải nghiệm ẩm thực Việt Nam đặc sắc trong không gian ấm cúng. 
                    Đặt bàn ngay hôm nay để thưởng thức những món ăn tuyệt vời nhất!
                </p>
                <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                    <a href="<?= BASE_URL ?>/index.php?page=booking" class="btn btn-warning btn-lg px-4">
                        <i class="fas fa-calendar-check"></i> Đặt bàn ngay
                    </a>
                    <a href="<?= BASE_URL ?>/index.php?page=menu" class="btn btn-outline-light btn-lg px-4">
                        <i class="fas fa-list"></i> Xem thực đơn
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <img src="<?= asset('images/hero-restaurant.jpg') ?>" alt="Restaurant" class="img-fluid rounded shadow" 
                     onerror="this.src='https://via.placeholder.com/600x400/007bff/ffffff?text=Restaurant+Image'">
            </div>
        </div>
    </div>
</div>

<!-- Features Section -->
<section class="py-5">
    <div class="container">
        <div class="row text-center">
            <div class="col-lg-4 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                             style="width: 60px; height: 60px;">
                            <i class="fas fa-utensils fa-lg"></i>
                        </div>
                        <h4 class="card-title">Món ăn ngon</h4>
                        <p class="card-text text-muted">
                            Các món ăn được chế biến từ nguyên liệu tươi ngon, 
                            đảm bảo hương vị đặc trưng của ẩm thực Việt Nam.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                             style="width: 60px; height: 60px;">
                            <i class="fas fa-clock fa-lg"></i>
                        </div>
                        <h4 class="card-title">Phục vụ nhanh</h4>
                        <p class="card-text text-muted">
                            Đội ngũ nhân viên chuyên nghiệp, phục vụ nhanh chóng 
                            và chu đáo để mang đến trải nghiệm tốt nhất.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="bg-warning text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                             style="width: 60px; height: 60px;">
                            <i class="fas fa-home fa-lg"></i>
                        </div>
                        <h4 class="card-title">Không gian ấm cúng</h4>
                        <p class="card-text text-muted">
                            Thiết kế nội thất hiện đại kết hợp truyền thống, 
                            tạo không gian ấm cúng cho gia đình và bạn bè.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Dishes Section -->
<?php if (!empty($featured_dishes)): ?>
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold">Món ăn nổi bật</h2>
            <p class="lead text-muted">Những món ăn được yêu thích nhất tại nhà hàng</p>
        </div>
        
        <div class="row">
            <?php foreach (array_slice($featured_dishes, 0, 6) as $dish): ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <img src="<?= asset('images/dishes/' . ($dish['image'] ?? 'default.jpg')) ?>" 
                         class="card-img-top" alt="<?= htmlspecialchars($dish['name']) ?>"
                         style="height: 200px; object-fit: cover;"
                         onerror="this.src='https://via.placeholder.com/300x200/28a745/ffffff?text=<?= urlencode($dish['name']) ?>'">
                    
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?= htmlspecialchars($dish['name']) ?></h5>
                        <p class="card-text text-muted flex-grow-1">
                            <?= htmlspecialchars(substr($dish['description'] ?? '', 0, 100)) ?>...
                        </p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="h5 text-primary mb-0">
                                <?= number_format($dish['price'], 0, ',', '.') ?> VNĐ
                            </span>
                            <a href="<?= BASE_URL ?>/index.php?page=menu&action=dish&id=<?= $dish['id'] ?>" 
                               class="btn btn-outline-primary btn-sm">
                                Xem chi tiết
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-4">
            <a href="<?= BASE_URL ?>/index.php?page=menu" class="btn btn-primary btn-lg">
                <i class="fas fa-list"></i> Xem toàn bộ thực đơn
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Restaurant Info Section -->
<section class="py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h2 class="display-5 fw-bold mb-4">Về chúng tôi</h2>
                <p class="lead text-muted mb-4">
                    <?= htmlspecialchars($restaurant_info['description']) ?>
                </p>
                
                <div class="row">
                    <div class="col-sm-6 mb-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-map-marker-alt text-primary me-3"></i>
                            <div>
                                <strong>Địa chỉ:</strong><br>
                                <small class="text-muted"><?= htmlspecialchars($restaurant_info['address']) ?></small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-sm-6 mb-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-phone text-primary me-3"></i>
                            <div>
                                <strong>Điện thoại:</strong><br>
                                <small class="text-muted"><?= htmlspecialchars($restaurant_info['phone']) ?></small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-sm-6 mb-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-envelope text-primary me-3"></i>
                            <div>
                                <strong>Email:</strong><br>
                                <small class="text-muted"><?= htmlspecialchars($restaurant_info['email']) ?></small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-sm-6 mb-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-clock text-primary me-3"></i>
                            <div>
                                <strong>Giờ mở cửa:</strong><br>
                                <small class="text-muted"><?= htmlspecialchars($restaurant_info['opening_hours']) ?></small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <a href="<?= BASE_URL ?>/index.php?page=contact" class="btn btn-primary">
                        <i class="fas fa-envelope"></i> Liên hệ với chúng tôi
                    </a>
                </div>
            </div>
            
            <div class="col-lg-6">
                <img src="<?= asset('images/restaurant-interior.jpg') ?>" alt="Restaurant Interior" 
                     class="img-fluid rounded shadow"
                     onerror="this.src='https://via.placeholder.com/600x400/6c757d/ffffff?text=Restaurant+Interior'">
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-primary text-white">
    <div class="container text-center">
        <h2 class="display-5 fw-bold mb-4">Sẵn sàng để đặt bàn?</h2>
        <p class="lead mb-4">
            Hãy đặt bàn ngay hôm nay để không bỏ lỡ cơ hội thưởng thức những món ăn tuyệt vời!
        </p>
        <a href="<?= BASE_URL ?>/index.php?page=booking" class="btn btn-warning btn-lg px-5">
            <i class="fas fa-calendar-check"></i> Đặt bàn ngay
        </a>
    </div>
</section>
