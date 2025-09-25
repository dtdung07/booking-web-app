<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Quán Nhậu Trật Tự'; ?></title>
   
    <!-- Critical CSS - Design tokens and variables (highest priority) -->
    <link rel="preload" href="<?php echo asset('css/constants.css'); ?>" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="stylesheet" href="<?php echo asset('css/layout/header.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('css/layout/footer.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('css/components/buttons.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('css/pages/home.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('css/pages/menu.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('css/style-menu.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('css/pages/menu2.css'); ?>">
    
    <!-- Page-specific CSS -->
    <?php if (isset($additional_css)): ?>
        <?php echo $additional_css; ?>
    <?php endif; ?>

    
    
    <!-- External CSS (lowest priority) -->
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></noscript>

    <!-- Page-specific head content -->
    <?php if (isset($additional_head)): ?>
        <?php echo $additional_head; ?>
    <?php endif; ?>
</head>
<body>
    <!-- Include Header Component -->
    <?php include 'header.php'; ?>
    
    <!-- Main Content -->
    <main class="main-content">
        <?php echo $content; ?>
    </main>
    
    <!-- Include Footer Component -->
    <?php include 'footer.php'; ?>
    
    <!-- Global Sticky Cart Widget -->
    <div id="menu2-sticky-cart-widget">
        <div class="menu2-cart-info">
            <span id="menu2-cart-item-count">0 món tạm tính</span>
            <strong id="menu2-cart-total-price">0đ</strong>
        </div>
    </div>

    <!-- Global Bill Modal -->
    <div id="menu2-billOverlay" class="menu2-bill-overlay">
        <div class="menu2-bill-modal">
            <header class="menu2-bill-header">
                <div class="menu2-bill-title"><i class="fas fa-receipt"></i><span>Tạm tính</span></div>
                <div class="menu2-header-actions">
                    <button class="menu2-save-button"><i class="fas fa-download"></i> LƯU VỀ MÁY</button>
                    <button id="menu2-billCloseBtn" class="menu2-close-button"><i class="fas fa-times"></i></button>
                </div>
            </header>
            <section class="menu2-bill-body">
                <div class="menu2-bill-total-summary">
                    <div class="menu2-total-left">
                        <h3 class="menu2-total-title">Tổng tiền</h3>
                        <p class="menu2-total-note">Đơn giá tạm tính chỉ mang tính chất tham khảo.</p>
                    </div>
                    <div class="menu2-total-right">
                        <div id="menu2-billTotalPriceDisplay" class="menu2-total-price">0đ</div>
                        <a id="menu2-billClearAllBtn" href="#" class="menu2-clear-bill"><i class="fas fa-trash-alt"></i> Xoá hết tạm tính</a>
                    </div>
                </div>
                <div id="menu2-billItemsContainer" class="menu2-bill-items"></div>
            </section>
            <footer class="menu2-bill-footer">
               <div style="display: flex; gap: 10px;">
                 <button id="menu2-proceedBookingOnsite" class="menu2-cta-button" style="border: 2px solid var(--colorYellow); color: black; background: white;">ĐẶT Tại Bàn</button>
                <button id="menu2-proceedToBookingBtn" class="menu2-cta-button">ĐẶT Online</button>
               </div>
                <p class="menu2-footer-note">Hoặc gọi <span>*1986</span> để đặt bàn</p>
            </footer>
        </div>
    </div>
    
    <!-- Cart Cookies Manager - Load on all pages -->
    <script src="<?php echo asset('js/cart-cookies.js'); ?>"></script>
    <script src="<?php echo asset('js/global-cart-manager.js'); ?>"></script>
    
    <!-- Page-specific scripts -->
    <?php if (isset($additional_scripts)): ?>
        <?php echo $additional_scripts; ?>
    <?php endif; ?>
</body>
</html>
