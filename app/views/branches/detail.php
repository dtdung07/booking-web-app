<link rel="stylesheet" href="public/css/pages/branches.css">
<link rel="stylesheet" href="public/css/pages/branch-detail.css">

<main class="branch-detail-page">
    <!-- Hero Section -->
    <section class="detail-hero">
        <div class="hero-background">
            <img src="<?php echo $branch['image']; ?>" alt="<?php echo $branch['name']; ?>">
            <div class="hero-overlay"></div>
        </div>
        <div class="container">
            <div class="hero-content">
                <div class="breadcrumb">
                    <a href="<?php echo url('/'); ?>">Trang chủ</a>
                    <span>›</span>
                    <a href="<?php echo url('?page=branches'); ?>">Cơ sở</a>
                    <span>›</span>
                    <span><?php echo $branch['name']; ?></span>
                </div>
                <h1 class="hero-title"><?php echo $branch['name']; ?></h1>
                <p class="hero-subtitle"><?php echo $branch['address']; ?></p>
                <div class="hero-status">
                    <span class="status-badge active">
                        <i class="fas fa-check-circle"></i>
                        <?php echo $branch['status']; ?>
                    </span>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Info -->
    <section class="quick-info">
        <div class="container">
            <div class="info-grid">
                <div class="info-card">
                    <div class="info-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div class="info-content">
                        <span class="info-label">Hotline</span>
                        <span class="info-value"><?php echo $branch['hotline']; ?></span>
                    </div>
                </div>
                <div class="info-card">
                    <div class="info-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="info-content">
                        <span class="info-label">Giờ hoạt động</span>
                        <span class="info-value"><?php echo $branch['operating_hours']; ?></span>
                    </div>
                </div>
                <div class="info-card">
                    <div class="info-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="info-content">
                        <span class="info-label">Sức chứa</span>
                        <span class="info-value"><?php echo $branch['capacity']; ?></span>
                    </div>
                </div>
                <div class="info-card">
                    <div class="info-icon">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </div>
                    <div class="info-content">
                        <span class="info-label">Diện tích</span>
                        <span class="info-value"><?php echo $branch['area']; ?></span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="main-content">
        <div class="container">
            <div class="content-grid">
                <!-- Left Column -->
                <div class="content-left">
                    <!-- Description -->
                    <div class="content-section">
                        <h2>Giới thiệu</h2>
                        <p class="description"><?php echo $branch['description']; ?></p>
                    </div>

                    <!-- Features -->
                    <div class="content-section">
                        <h2>Tiện ích nổi bật</h2>
                        <div class="features-grid">
                            <?php foreach ($branch['features'] as $feature): ?>
                            <div class="feature-item">
                                <i class="fas fa-check"></i>
                                <span><?php echo $feature; ?></span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Menu Highlights -->
                    <div class="content-section">
                        <h2>Món ăn đặc trưng</h2>
                        <div class="menu-highlights">
                            <?php foreach ($branch['menu_highlights'] as $item): ?>
                            <div class="menu-item">
                                <i class="fas fa-utensils"></i>
                                <span><?php echo $item; ?></span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Gallery -->
                    <div class="content-section">
                        <h2>Hình ảnh không gian</h2>
                        <div class="gallery-grid">
                            <?php foreach ($branch['gallery'] as $index => $image): ?>
                            <div class="gallery-item" onclick="openGallery(<?php echo $index; ?>)">
                                <img src="<?php echo $image; ?>" alt="Hình ảnh cơ sở <?php echo $branch['name']; ?>">
                                <div class="gallery-overlay">
                                    <i class="fas fa-expand"></i>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="content-right">
                    <!-- Booking Card -->
                    <div class="booking-card">
                        <h3>Đặt bàn ngay</h3>
                        <p>Liên hệ hotline hoặc đặt bàn trực tuyến</p>
                        <div class="booking-actions">
                            <a href="tel:<?php echo $branch['phone']; ?>" class="btn btn-primary">
                                <i class="fas fa-phone"></i>
                                Gọi ngay
                            </a>
                            <a href="<?php echo url('?page=booking&branch_id=' . $branch['id']); ?>" class="btn btn-outline">
                                <i class="fas fa-calendar-check"></i>
                                Đặt bàn online
                            </a>
                        </div>
                    </div>

                    <!-- Map Card -->
                    <div class="map-card">
                        <h3>Vị trí</h3>
                        <div class="map-container">
                            <iframe 
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3724.4956287!2d105.8078178!3d21.0465177!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x313435871e467f03%3A0x3393a5979549e769!2sQu%C3%A1n%20Nh%E1%BA%ADu%20T%E1%BB%B1%20Do!5e0!3m2!1svi!2s!4v1629789456789!5m2!1svi!2s"
                                width="100%" 
                                height="200" 
                                style="border:0;" 
                                allowfullscreen="" 
                                loading="lazy">
                            </iframe>
                        </div>
                        <a href="<?php echo $branch['map_link']; ?>" target="_blank" class="btn btn-outline full-width">
                            <i class="fas fa-directions"></i>
                            Xem đường đi
                        </a>
                    </div>

                    <!-- Contact Card -->
                    <div class="contact-card">
                        <h3>Thông tin liên hệ</h3>
                        <div class="contact-info">
                            <div class="contact-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span><?php echo $branch['address']; ?></span>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-phone"></i>
                                <span><?php echo $branch['phone']; ?></span>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-clock"></i>
                                <span><?php echo $branch['operating_hours']; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Related Branches -->
    <section class="related-branches">
        <div class="container">
            <h2>Cơ sở khác</h2>
            <div class="branches-slider">
                <!-- This would be populated with other branches -->
                <div class="slider-nav">
                    <button class="prev-btn"><i class="fas fa-chevron-left"></i></button>
                    <button class="next-btn"><i class="fas fa-chevron-right"></i></button>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- Gallery Modal -->
<div id="galleryModal" class="modal gallery-modal">
    <div class="modal-content">
        <button class="modal-close">&times;</button>
        <div class="gallery-viewer">
            <button class="gallery-prev"><i class="fas fa-chevron-left"></i></button>
            <img id="galleryImage" src="" alt="">
            <button class="gallery-next"><i class="fas fa-chevron-right"></i></button>
        </div>
        <div class="gallery-counter">
            <span id="galleryCounter">1 / 4</span>
        </div>
    </div>
</div>

<script>
// Gallery functionality
let currentGalleryIndex = 0;
const galleryImages = <?php echo json_encode($branch['gallery']); ?>;

function openGallery(index) {
    currentGalleryIndex = index;
    const modal = document.getElementById('galleryModal');
    const image = document.getElementById('galleryImage');
    const counter = document.getElementById('galleryCounter');
    
    image.src = galleryImages[index];
    counter.textContent = `${index + 1} / ${galleryImages.length}`;
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeGallery() {
    const modal = document.getElementById('galleryModal');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
}

function nextGalleryImage() {
    currentGalleryIndex = (currentGalleryIndex + 1) % galleryImages.length;
    openGallery(currentGalleryIndex);
}

function prevGalleryImage() {
    currentGalleryIndex = (currentGalleryIndex - 1 + galleryImages.length) % galleryImages.length;
    openGallery(currentGalleryIndex);
}

// Event listeners
document.querySelector('.gallery-next').addEventListener('click', nextGalleryImage);
document.querySelector('.gallery-prev').addEventListener('click', prevGalleryImage);
document.querySelector('#galleryModal .modal-close').addEventListener('click', closeGallery);

// Keyboard navigation
document.addEventListener('keydown', function(e) {
    if (document.getElementById('galleryModal').style.display === 'flex') {
        if (e.key === 'ArrowRight') nextGalleryImage();
        if (e.key === 'ArrowLeft') prevGalleryImage();
        if (e.key === 'Escape') closeGallery();
    }
});

// Close modal when clicking outside
document.getElementById('galleryModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeGallery();
    }
});

// Scroll animations
document.addEventListener('DOMContentLoaded', function() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in');
            }
        });
    }, observerOptions);
    
    document.querySelectorAll('.content-section, .info-card, .booking-card, .map-card, .contact-card').forEach(el => {
        observer.observe(el);
    });
});
</script>
