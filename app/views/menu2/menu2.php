<main class="menu-page">
    <section class="tab-section-menu">
        <div class="container">
            <div class="tab-navigation">
                <button class="tab-btn <?= empty($selectedCategory) || $selectedCategory === 'all' ? 'active' : '' ?>" 
                        data-category="all">
                    <span class="tab-text">TẤT CẢ</span>
                </button>
                <?php if (isset($categories) && is_array($categories)): ?>
                    <?php foreach ($categories as $category): ?>
                        <?php if ($category['TenDM'] !== 'TẤT CẢ'): ?>
                            <button class="tab-btn <?= $selectedCategory == $category['MaDM'] ? 'active' : '' ?>" 
                                    data-category="<?= $category['MaDM'] ?>">
                                <span class="tab-text"><?= htmlspecialchars($category['TenDM']) ?></span>
                            </button>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <div class="menu-container">
        <h2 class="menu-title">
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
        <div class="menu-grid<?= (!empty($selectedCategory) && $selectedCategory !== 'all') ? ' category-grid' : '' ?>" id="menu-grid">
            <?php if (empty($selectedCategory) || $selectedCategory === 'all'): ?>
                <?php if (isset($groupedMenuItems) && is_array($groupedMenuItems) && count($groupedMenuItems) > 0): ?>
                    <?php foreach ($groupedMenuItems as $categoryName => $items): ?>
                        <div class="category-section">
                            <h3 class="category-title"><?= htmlspecialchars($categoryName) ?></h3>
                            <div class="category-items">
                                <?php foreach ($items as $item): ?>
                                    <div class="menu-card" 
                                         data-action="open-modal"
                                         data-id="<?= $item['MaMon'] ?>"
                                         data-name="<?= htmlspecialchars($item['TenMon']) ?>"
                                         data-price="<?= $item['Gia'] ?>"
                                         data-description="<?= htmlspecialchars($item['MoTa']) ?>"
                                         data-image-url="<?= $item['HinhAnhURL'] ?>">
                                        <img src="<?= $item['HinhAnhURL'] ?>" alt="<?= htmlspecialchars($item['TenMon']) ?>" onerror="this.src='https://storage.quannhautudo.com/data/thumb_400/Data/images/product/2025/06/202506271712248578.webp'">
                                        <div class="menu-card-content">
                                            <span class="menu-card-name"><?= htmlspecialchars($item['TenMon']) ?></span>
                                            <div class="menu-card-price">
                                                <?= number_format($item['Gia'], 0, ',', '.') ?>đ
                                            </div>
                                            <div class="menu-card-actions">
                                                <div class="btn-add-to-cart" 
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
                    <div class="no-items"><p>Không có món ăn nào trong cơ sở này.</p></div>
                <?php endif; ?>
            <?php else: ?>
                <?php if (isset($menuItems) && is_array($menuItems) && count($menuItems) > 0): ?>
                    <?php foreach ($menuItems as $item): ?>
                         <div class="menu-card" 
                              data-action="open-modal"
                              data-id="<?= $item['MaMon'] ?>"
                              data-name="<?= htmlspecialchars($item['TenMon']) ?>"
                              data-price="<?= $item['Gia'] ?>"
                              data-description="<?= htmlspecialchars($item['MoTa']) ?>"
                              data-image-url="<?= $item['HinhAnhURL'] ?>">
                            <img src="<?= $item['HinhAnhURL'] ?>" alt="<?= htmlspecialchars($item['TenMon']) ?>" onerror="this.src='https://storage.quannhautudo.com/data/thumb_400/Data/images/product/2025/06/202506271712248578.webp'">
                            <div class="menu-card-content">
                                <span class="menu-card-name"><?= htmlspecialchars($item['TenMon']) ?></span>
                                <div class="menu-card-price">
                                     <?= number_format($item['Gia'], 0, ',', '.') ?>đ
                                </div>
                                <div class="menu-card-actions">
                                    <div class="btn-add-to-cart" 
                                         data-action="add-to-cart"
                                         data-id="<?= $item['MaMon'] ?>"
                                         data-name="<?= htmlspecialchars($item['TenMon']) ?>"
                                         data-price="<?= $item['Gia'] ?>">+ Đặt</div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-items"><p>Không có món ăn nào trong danh mục này.</p></div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</main>

<div id="bookingOverlay" class="booking-overlay">
    <div class="booking-form-container">
        <h1 class="form-title">Đặt bàn</h1>
        <form id="bookingForm">
            <div class="form-section">
                <h3 class="form-section-title"><i class="fas fa-user"></i>Thông tin của bạn</h3>
                <div class="form-group">
                    <input type="text" class="form-input" placeholder="Tên của bạn" required>
                </div>
                <div class="form-group">
                    <input type="tel" class="form-input" placeholder="Số điện thoại" required>
                </div>
            </div>

            <div class="form-section">
                <h3 class="form-section-title"><i class="fas fa-calendar-check"></i>Thông tin đặt bàn</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label>Số lượng người</label>
                        <div class="quantity-selector">
                            <button type="button" data-action="decrease-guests">-</button>
                            <div class="quantity-display" id="booking-guests-display">1</div>
                            <button type="button" data-action="increase-guests">+</button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="date-display-input">Chọn ngày</label>
                        <div class="input-with-icon">
                            <input type="text" class="form-input" id="date-display-input" readonly>
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Chọn giờ</label>
                        <select class="form-select" required>
                            <option value="" selected disabled>Chọn giờ</option>
                            <option value="17:00">17:00</option>
                            <option value="17:30">17:30</option>
                            <option value="18:00">18:00</option>
                            <option value="21:00">21:00</option>
                        </select>
                    </div>
                </div>
            </div>
            <textarea class="form-textarea" placeholder="Ghi chú"></textarea>
            
            <div class="form-actions">
                <button type="button" class="btn btn-secondary" data-action="close-booking-form">Đóng</button>
                <button type="submit" class="btn btn-primary">Đặt bàn ngay</button>
            </div>
        </form>
    </div>
</div>

<div id="itemModal" class="modal">
    <div class="modal-box">
         <div class="modal-image">
             <img id="modalImage" src="" alt="Hình ảnh món ăn">
         </div>
         <div class="modal-body">
             <div class="modal-info">
                 <p id="modalItemName"></p>
                 <div class="modal-price-quantity">
                     <p class="modal-price"><span id="modalPrice">0đ</span></p>
                     <div class="modal-quantity">
                        <div class="quantity-controls">
                             <button type="button" data-action="decrease-quantity">-</button>
                             <input type="number" id="quantity" value="1" min="1" readonly>
                             <button type="button" data-action="increase-quantity">+</button>
                         </div>
                     </div>
                 </div>
                 <hr>
                 <div class="modal-description">
                     <h4>Mô tả món ăn:</h4>
                     <p id="modalDescription">...</p>
                 </div>
                 <div class="modal-actions">
                     <button class="btn-order-now" id="orderNowBtn">Thêm vào giỏ</button>
                 </div>
             </div>
         </div>
    </div>
</div>

<div id="sticky-cart-widget">
    <div class="cart-info">
        <span id="cart-item-count">0 món tạm tính</span>
        <strong id="cart-total-price">0đ</strong>
    </div>
</div>

<div id="billOverlay" class="bill-overlay">
    <div class="bill-modal">
        <header class="bill-header">
            <div class="bill-title"><i class="fas fa-receipt"></i><span>Tạm tính</span></div>
            <div class="header-actions">
                <button class="save-button"><i class="fas fa-download"></i> LƯU VỀ MÁY</button>
                <button id="billCloseBtn" class="close-button"><i class="fas fa-times"></i></button>
            </div>
        </header>
        <section class="bill-body">
            <div class="bill-total-summary">
                <div class="total-left">
                    <h3 class="total-title">Tổng tiền</h3>
                    <p class="total-note">Đơn giá tạm tính chỉ mang tính chất tham khảo.</p>
                </div>
                <div class="total-right">
                    <div id="billTotalPriceDisplay" class="total-price">0đ</div>
                    <a id="billClearAllBtn" href="#" class="clear-bill"><i class="fas fa-trash-alt"></i> Xoá hết tạm tính</a>
                </div>
            </div>
            <div id="billItemsContainer" class="bill-items"></div>
        </section>
        <footer class="bill-footer">
            <button id="proceedToBookingBtn" class="cta-button">ĐẶT BÀN VỚI THỰC ĐƠN NÀY</button>
            <p class="footer-note">Hoặc gọi <span>*1986</span> để đặt bàn</p>
        </footer>
    </div>
</div>

<div id="datePickerOverlay" class="date-picker-overlay">
    <div id="datePickerModal" class="date-picker-modal">
        <div class="dp-header">
            <button type="button" id="dp-prev-month" class="dp-nav"><i class="fas fa-chevron-left"></i></button>
            <span id="dp-current-month-year"></span>
            <button type="button" id="dp-next-month" class="dp-nav"><i class="fas fa-chevron-right"></i></button>
        </div>
        <div class="dp-weekdays">
            <div>CN</div><div>T2</div><div>T3</div><div>T4</div><div>T5</div><div>T6</div><div>T7</div>
        </div>
        <div id="dp-days-grid" class="dp-days-grid"></div>
        <div class="dp-footer">
            <button type="button" id="dp-today-btn" class="dp-btn dp-btn-secondary">Hôm nay</button>
            <button type="button" id="dp-close-btn" class="dp-btn dp-btn-primary">Đóng</button>
        </div>
    </div>
</div>