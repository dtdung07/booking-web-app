    </main>

    <!-- Footer -->
    <footer class="bg-dark text-light mt-5">
        <div class="container py-5">
            <div class="row">
                <div class="col-md-4">
                    <h5><i class="fas fa-utensils"></i> <?= APP_NAME ?></h5>
                    <p class="text-muted">
                        Nhà hàng chuyên phục vụ các món ăn truyền thống Việt Nam 
                        với không gian ấm cúng và dịch vụ tận tình.
                    </p>
                    <div class="social-links">
                        <a href="#" class="text-light me-3"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-light me-3"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-light me-3"><i class="fab fa-youtube"></i></a>
                        <a href="#" class="text-light"><i class="fab fa-tiktok"></i></a>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <h5>Thông tin liên hệ</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-map-marker-alt"></i>
                            123 Đường ABC, Quận 1, TP.HCM
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-phone"></i>
                            <a href="tel:02833334444" class="text-light text-decoration-none">
                                028 3333 4444
                            </a>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-envelope"></i>
                            <a href="mailto:info@restaurant.com" class="text-light text-decoration-none">
                                info@restaurant.com
                            </a>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-clock"></i>
                            10:00 - 22:00 (Hàng ngày)
                        </li>
                    </ul>
                </div>
                
                <div class="col-md-4">
                    <h5>Menu nhanh</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <a href="<?= BASE_URL ?>" class="text-light text-decoration-none">
                                <i class="fas fa-home"></i> Trang chủ
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="<?= BASE_URL ?>/index.php?page=menu" class="text-light text-decoration-none">
                                <i class="fas fa-list"></i> Thực đơn
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="<?= BASE_URL ?>/index.php?page=booking" class="text-light text-decoration-none">
                                <i class="fas fa-calendar-check"></i> Đặt bàn
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="<?= BASE_URL ?>/index.php?page=contact" class="text-light text-decoration-none">
                                <i class="fas fa-envelope"></i> Liên hệ
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="border-top border-secondary">
            <div class="container py-3">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p class="mb-0 text-muted">
                            &copy; <?= date('Y') ?> <?= APP_NAME ?>. Tất cả quyền được bảo lưu.
                        </p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <p class="mb-0 text-muted">
                            Phiên bản <?= APP_VERSION ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Custom JS -->
    <script src="<?= asset('js/script.js') ?>"></script>
</body>
</html>
