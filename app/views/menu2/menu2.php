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

