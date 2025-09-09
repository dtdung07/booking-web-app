<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu & Bill Tạm Tính</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
     <link rel="stylesheet" href="../../../public/css/pages/menu2.css">
     <script src="../../../public/js/menu2.js"></script>
</head>
<body>
    <main class="menu-page">
        <div class="menu-container">
            <h2>Món Nhậu</h2>
            <div class="menu-grid">
                <?php
                $totalItems = 36;
                $itemName = "Ếch sốt tiêu gừng chua cay";
                for ($i = 1; $i <= $totalItems; $i++): 
                    $itemPrice = 120000 + ($i * 1000);
                ?>
                    <div class="menu-card" onclick="openModal(<?= $i ?>, '<?= $itemName ?> (<?= $i ?>)', <?= $itemPrice ?>)">
                        <img src="https://storage.quannhautudo.com/data/thumb_400/Data/images/product/2025/06/202506271712248578.webp" alt="">
                        <div class="menu-card-content">
                            <span class="menu-card-name"><?= $itemName ?> (<?= $i ?>)</span>
                            <div class="menu-card-price">
                                <?= number_format($itemPrice, 0, ',', '.'); ?>đ
                            </div>
                            <div class="menu-card-actions">
                                <div class="btn-add-to-cart" onclick="event.stopPropagation(); addToCart(<?= $i ?>, '<?= $itemName ?> (<?= $i ?>)', <?= $itemPrice ?>)">+ Đặt</div>
                            </div>
                        </div>
                    </div>
                <?php endfor; ?>
            </div>
        </div>
    </main>

    <!-- Booking Form Overlay -->
    <div id="bookingOverlay" class="booking-overlay">
        <div class="booking-form-container">
            <h1 class="form-title">Đặt bàn</h1>
            <form>
                <div class="form-section">
                    <h3 class="form-section-title"><i class="fas fa-user"></i>Thông tin của bạn</h3>
                    <div class="form-group">
                        <input type="text" class="form-input" placeholder="Tên của bạn">
                    </div>
                    <div class="form-group">
                        <input type="tel" class="form-input" placeholder="Số điện thoại">
                    </div>
                </div>

                <div class="form-section">
                    <h3 class="form-section-title"><i class="fas fa-calendar-check"></i>Thông tin đặt bàn</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="number-of-people">Số lượng người</label>
                            <div class="quantity-selector">
                                <button type="button" onclick="decreaseBookingGuests()">-</button>
                                <div class="quantity-display">1</div>
                                <button type="button" onclick="increaseBookingGuests()">+</button>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="date">Chọn ngày</label>
                            <div class="input-with-icon">
                                <input type="text" class="form-input" id="date-display-input" readonly>
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="time">Chọn giờ</label>
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
                    <button type="button" class="btn btn-secondary" onclick="closeBookingForm()">Đóng</button>
                    <button type="submit" class="btn btn-primary">Đặt bàn ngay</button>
                </div>
            </form>
        </div>
    </div>

    <div id="itemModal" class="modal">
        <div class="modal-box">
             <div class="modal-image">
                 <img id="modalImage" src="" alt="">
             </div>
             <div class="modal-body">
                 <div class="modal-info">
                     <p id="modalItemName"></p>
                     <div class="modal-price-quantity">
                         <p class="modal-price"><span id="modalPrice">0đ</span></p>
                         <div class="modal-quantity">
                            <div class="quantity-controls">
                                 <button type="button" onclick="decreaseQuantity()">-</button>
                                 <input type="number" id="quantity" value="1" min="1">
                                 <button type="button" onclick="increaseQuantity()">+</button>
                             </div>
                         </div>
                     </div>
                     <hr>
                     <div class="modal-description">
                         <h4>Mô tả món ăn:</h4>
                         <p id="modalDescription">...</p>
                     </div>
                     <div class="modal-actions">
                         <button class="btn-order-now" onclick="orderNow()">Thêm vào giỏ</button>
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
                <div id="billItemsContainer" class="bill-items">
                    </div>
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

</body>
</html>