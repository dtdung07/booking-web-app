<main class="menu2-page">
    <section class="menu2-tab-section">
        <div class="container">
            <div class="menu2-tab-navigation">
                <button class="menu2-tab-btn <?= empty($selectedCategory) || $selectedCategory === 'all' ? 'active' : '' ?>" 
                        data-category="all">
                    <span class="menu2-tab-text">TẤT CẢ</span>
                </button>
                <?php if (isset($categories) && is_array($categories)): ?>
                    <?php foreach ($categories as $category): ?>
                        <?php if ($category['TenDM'] !== 'TẤT CẢ'): ?>
                            <button class="menu2-tab-btn <?= $selectedCategory == $category['MaDM'] ? 'active' : '' ?>" 
                                    data-category="<?= $category['MaDM'] ?>">
                                <span class="menu2-tab-text"><?= htmlspecialchars($category['TenDM']) ?></span>
                            </button>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <div class="menu2-container">
        <h2 class="menu2-title">
            <?php 
                if (!empty($selectedCategory) && $selectedCategory !== 'all') {
                    $currentCategoryName = 'Danh mục';
                    if (isset($categories) && is_array($categories)) {
                        foreach ($categories as $category) {
                            if ($category['MaDM'] == $selectedCategory) {
                                $currentCategoryName = $category['TenDM'];
                                break;
                            }
                        }
                    }
                    echo htmlspecialchars($currentCategoryName);
                }
            ?>
        </h2>
        <div class="menu2-grid<?= (!empty($selectedCategory) && $selectedCategory !== 'all') ? ' menu2-category-grid' : '' ?>" id="menu2-grid">
            <?php if (empty($selectedCategory) || $selectedCategory === 'all'): ?>
                <?php if (isset($groupedMenuItems) && is_array($groupedMenuItems) && count($groupedMenuItems) > 0): ?>
                    <?php foreach ($groupedMenuItems as $categoryName => $items): ?>
                        <div class="menu2-category-section">
                            <h3 class="menu2-category-title"><?= htmlspecialchars($categoryName) ?></h3>
                            <div class="menu2-category-items">
                                <?php foreach ($items as $item): ?>
                                    <div class="menu2-card" 
                                         data-action="open-modal"
                                         data-id="<?= $item['MaMon'] ?>"
                                         data-name="<?= htmlspecialchars($item['TenMon']) ?>"
                                         data-price="<?= $item['Gia'] ?>"
                                         data-description="<?= htmlspecialchars($item['MoTa']) ?>"
                                         data-image-url="<?= $item['HinhAnhURL'] ?>">
                                        <img src="<?= $item['HinhAnhURL'] ?>" alt="<?= htmlspecialchars($item['TenMon']) ?>" onerror="this.src='https://storage.quannhautudo.com/data/thumb_400/Data/images/product/2025/06/202506271712248578.webp'">
                                        <div class="menu2-card-content">
                                            <span class="menu2-card-name"><?= htmlspecialchars($item['TenMon']) ?></span>
                                            <div class="menu2-card-price">
                                                <?= number_format($item['Gia'], 0, ',', '.') ?>đ
                                            </div>
                                            <div class="menu2-card-actions">
                                                <div class="menu2-btn-add-to-cart" 
                                                     data-action="add-to-cart"
                                                     data-id="<?= $item['MaMon'] ?>"
                                                     data-name="<?= htmlspecialchars($item['TenMon']) ?>"
                                                     data-price="<?= $item['Gia'] ?>">+ Đặt</div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="menu2-no-items"><p>Không có món ăn nào trong cơ sở này.</p></div>
                <?php endif; ?>
            <?php else: ?>
                <?php if (isset($menuItems) && is_array($menuItems) && count($menuItems) > 0): ?>
                    <?php foreach ($menuItems as $item): ?>
                         <div class="menu2-card" 
                              data-action="open-modal"
                              data-id="<?= $item['MaMon'] ?>"
                              data-name="<?= htmlspecialchars($item['TenMon']) ?>"
                              data-price="<?= $item['Gia'] ?>"
                              data-description="<?= htmlspecialchars($item['MoTa']) ?>"
                              data-image-url="<?= $item['HinhAnhURL'] ?>">
                            <img src="<?= $item['HinhAnhURL'] ?>" alt="<?= htmlspecialchars($item['TenMon']) ?>" onerror="this.src='https://storage.quannhautudo.com/data/thumb_400/Data/images/product/2025/06/202506271712248578.webp'">
                            <div class="menu2-card-content">
                                <span class="menu2-card-name"><?= htmlspecialchars($item['TenMon']) ?></span>
                                <div class="menu2-card-price">
                                     <?= number_format($item['Gia'], 0, ',', '.') ?>đ
                                </div>
                                <div class="menu2-card-actions">
                                    <div class="menu2-btn-add-to-cart" 
                                         data-action="add-to-cart"
                                         data-id="<?= $item['MaMon'] ?>"
                                         data-name="<?= htmlspecialchars($item['TenMon']) ?>"
                                         data-price="<?= $item['Gia'] ?>">+ Đặt</div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="menu2-no-items"><p>Không có món ăn nào trong danh mục này.</p></div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</main>

<div id="menu2-bookingOverlay" class="menu2-booking-overlay">
    <div class="menu2-booking-form-container">
        <h1 class="menu2-form-title">Đặt bàn</h1>
        <form id="menu2-bookingForm">
            <div class="menu2-form-section">
                <h3 class="menu2-form-section-title"><i class="fas fa-user"></i>Thông tin của bạn</h3>
                <div class="menu2-form-group">
                    <input type="text" class="menu2-form-input" placeholder="Tên của bạn" required>
                </div>
                <div class="menu2-form-group">
                    <input type="tel" class="menu2-form-input" placeholder="Số điện thoại" required>
                </div>
            </div>

            <div class="menu2-form-section">
                <h3 class="menu2-form-section-title"><i class="fas fa-calendar-check"></i>Thông tin đặt bàn</h3>
                <div class="menu2-form-row">
                    <div class="menu2-form-group">
                        <label>Số lượng người</label>
                        <div class="menu2-quantity-selector">
                            <button type="button" data-action="decrease-guests">-</button>
                            <div class="menu2-quantity-display" id="menu2-booking-guests-display">1</div>
                            <button type="button" data-action="increase-guests">+</button>
                        </div>
                    </div>
                    <div class="menu2-form-group">
                        <label for="menu2-date-display-input">Chọn ngày</label>
                        <div class="menu2-input-with-icon">
                            <input type="text" class="menu2-form-input" id="menu2-date-display-input" readonly>
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                    </div>
                    <div class="menu2-form-group">
                        <label>Chọn giờ</label>
                        <select class="menu2-form-select" required>
                            <option value="" selected disabled>Chọn giờ</option>
                            <option value="17:00">17:00</option>
                            <option value="17:30">17:30</option>
                            <option value="18:00">18:00</option>
                            <option value="21:00">21:00</option>
                        </select>
                    </div>
                </div>
            </div>
            <textarea class="menu2-form-textarea" placeholder="Ghi chú"></textarea>
            
            <div class="menu2-form-actions">
                <button type="button" class="menu2-btn menu2-btn-secondary" data-action="close-booking-form">Đóng</button>
                <button type="submit" class="menu2-btn menu2-btn-primary">Đặt bàn ngay</button>
            </div>
        </form>
    </div>
</div>

<div id="menu2-itemModal" class="menu2-modal">
    <div class="menu2-modal-box">
         <div class="menu2-modal-image">
             <img id="menu2-modalImage" src="" alt="Hình ảnh món ăn">
         </div>
         <div class="menu2-modal-body">
             <div class="menu2-modal-info">
                 <p id="menu2-modalItemName"></p>
                 <div class="menu2-modal-price-quantity">
                     <p class="menu2-modal-price"><span id="menu2-modalPrice">0đ</span></p>
                     <div class="menu2-modal-quantity">
                        <div class="menu2-quantity-controls">
                             <button type="button" data-action="decrease-quantity">-</button>
                             <input type="number" id="menu2-quantity" value="1" min="1" readonly>
                             <button type="button" data-action="increase-quantity">+</button>
                         </div>
                     </div>
                 </div>
                 <hr>
                 <div class="menu2-modal-description">
                     <h4>Mô tả món ăn:</h4>
                     <p id="menu2-modalDescription">...</p>
                 </div>
                 <div class="menu2-modal-actions">
                     <button class="menu2-btn-order-now" id="menu2-orderNowBtn">Thêm vào giỏ</button>
                 </div>
             </div>
         </div>
    </div>
</div>

<div id="menu2-datePickerOverlay" class="menu2-date-picker-overlay">
    <div id="menu2-datePickerModal" class="menu2-date-picker-modal">
        <div class="menu2-dp-header">
            <button type="button" id="menu2-dp-prev-month" class="menu2-dp-nav"><i class="fas fa-chevron-left"></i></button>
            <span id="menu2-dp-current-month-year"></span>
            <button type="button" id="menu2-dp-next-month" class="menu2-dp-nav"><i class="fas fa-chevron-right"></i></button>
        </div>
        <div class="menu2-dp-weekdays">
            <div>CN</div><div>T2</div><div>T3</div><div>T4</div><div>T5</div><div>T6</div><div>T7</div>
        </div>
        <div id="menu2-dp-days-grid" class="menu2-dp-days-grid"></div>
        <div class="menu2-dp-footer">
            <button type="button" id="menu2-dp-today-btn" class="menu2-dp-btn menu2-dp-btn-secondary">Hôm nay</button>
            <button type="button" id="menu2-dp-close-btn" class="menu2-dp-btn menu2-dp-btn-primary">Đóng</button>
        </div>
    </div>
</div>