<header>
    <div class="main-header">
        <div class="container">
            <a href="<?php echo url('/'); ?>" class="logo">
                <i class="fas fa-star" aria-hidden="true"></i>
                <div class="logo-text">
                    <span class="logo-top">QUÁN NHẬU </span>
                    <span class="logo-bottom">TỰ DO</span>
                </div>
            </a>
            
            <div class="hotline">
                <p>HOTLINE</p>
                <strong>*1986</strong>
            </div>
            <nav class="main-nav" role="navigation" aria-label="Main navigation">
                <ul>
                    <li><a href="<?php echo url('?page=menu'); ?>" class="<?php echo isActivePage('menu'); ?>">THỰC ĐƠN</a></li>
                    <li><a href="<?php echo url('?page=branches'); ?>" class="<?php echo isActivePage('branches'); ?>">CƠ SỞ</a></li>
                    <li><a href="<?php echo url('?page=promotions'); ?>" class="<?php echo isActivePage('promotions'); ?>">ƯU ĐÃI</a></li>
                    <li><a href="<?php echo url('?page=contact'); ?>" class="<?php echo isActivePage('contact'); ?>">LIÊN HỆ</a></li>
                </ul>
            </nav>
            
            <a href="<?php echo url('?page=booking'); ?>" class="btn-booking" role="button">ĐẶT BÀN</a>
        </div>
    </div>
</header>
