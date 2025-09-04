<!-- Menu Category Page -->
<section class="menu-hero">
    <div class="container">
        <h1 class="page-title"><?php echo ucfirst($category ?? 'Tất cả'); ?></h1>
        <p class="page-subtitle">Khám phá các món ăn trong danh mục này</p>
    </div>
</section>

<section class="menu-items">
    <div class="container">
        <div class="breadcrumb">
            <a href="<?php echo url('?page=menu'); ?>">Thực đơn</a> 
            <span class="separator">></span> 
            <span class="current"><?php echo ucfirst($category ?? 'Tất cả'); ?></span>
        </div>
        
        <div class="menu-grid">
            <!-- Category-specific menu items will be loaded here -->
            <p>Đang tải món ăn cho danh mục: <strong><?php echo $category ?? 'Tất cả'; ?></strong></p>
        </div>
    </div>
</section>
