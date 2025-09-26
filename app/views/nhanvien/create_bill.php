<?php
// File này được include trong dashboard.php để hiển thị section đơn đặt bàn
// Dữ liệu được truyền từ controller qua biến $bookingsData

// Lấy dữ liệu bookings từ controller
$bookingsList = $bookingsData['bookingsList'] ?? [];
$totalBookings = $bookingsData['totalBookings'] ?? 0;
$totalPages = $bookingsData['totalPages'] ?? 0;
$currentPage = $bookingsData['currentPage'] ?? 1;
$limit = $bookingsData['limit'] ?? 10;

// Lấy các filter parameters
$statusFilter = $_GET['status_filter'] ?? 'all';
$timeFilter = $_GET['time_filter'] ?? 'hom_nay';
$searchKeyword = $_GET['search'] ?? '';
?>

<style>
/* Styles cho phần đơn đặt bàn */
.booking-filters {
    background: white;
    padding: 1.5rem;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin-bottom: 2rem;
}

.filter-row {
    display: flex;
    gap: 1rem;
    align-items: center;
    flex-wrap: wrap;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.filter-group label {
    font-weight: 500;
    color: #374151;
    font-size: 0.9rem;
}

.filter-input, .filter-select {
    padding: 0.5rem;
    border: 2px solid #e5e7eb;
    border-radius: 6px;
    font-size: 0.9rem;
    min-width: 150px;
}

.filter-input:focus, .filter-select:focus {
    outline: none;
    border-color: #21A256;
}

.filter-btn {
    background: #21A256;
    color: white;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.9rem;
    margin-top: auto;
}

.filter-btn:hover {
    background: #1B8B47;
}

.bookings-table {
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    overflow: hidden;
}

.table-header {
    background: #f8fafc;
    padding: 1.5rem;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    justify-content: between;
    align-items: center;
}

.table-title {
    font-size: 1.2rem;
    font-weight: 600;
    color: #1e293b;
}

.table-stats {
    color: #64748b;
    font-size: 0.9rem;
}

.table-container {
    overflow-x: auto;
}


.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
}

.status-badge.pending {
    background: #fef3c7;
    color: #d97706;
}

.status-badge.confirmed {
    background: #dcfce7;
    color: #16a34a;
}

.status-badge.cancelled {
    background: #fecaca;
    color: #dc2626;
}

.status-badge.completed {
    background: #dbeafe;
    color: #2563eb;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    border: none;
    border-radius: 4px;
    font-size: 0.75rem;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
}

.btn-info {
    background: #3b82f6;
    color: white;
}

.btn-success {
    background: #10b981;
    color: white;
}

.btn-danger {
    background: #ef4444;
    color: white;
}

.btn-sm:hover {
    opacity: 0.8;
}

.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 0.5rem;
    padding: 1.5rem;
    background: #f8fafc;
}

.pagination a, .pagination span {
    padding: 0.5rem 0.75rem;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    text-decoration: none;
    color: #374151;
}

.pagination a:hover {
    background: #f3f4f6;
}

.pagination .current {
    background: #21A256;
    color: white;
    border-color: #21A256;
}

.empty-state {
    text-align: center;
    padding: 3rem;
    color: #64748b;
}

.empty-state i {
    font-size: 3rem;
    margin-bottom: 1rem;
    color: #cbd5e1;
}

.booking-detail {
    font-size: 0.875rem;
    line-height: 1.4;
}

.customer-info {
    font-weight: 500;
    color: #1e293b;
}

.booking-time {
    color: #059669;
    font-weight: 500;
}

.table-count {
    color: #6b7280;
    font-size: 0.8rem;
}
  .menu2-grid {
        display: flex;
        flex-direction: column;
        gap: 30px;
    }
    
    /* For specific category tabs - use grid layout */
    .menu2-grid.menu2-category-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 22px;
         margin: 22px;
    }
    
    /* Category sections for "Tất Cả" tab */
    .menu2-category-section {
        margin-bottom: 30px;
    }
    
    .menu2-category-title {
        font-size: 24px;
        font-weight: 600;
        color: #333;
        margin-bottom: 20px;
        padding-left: 10px;
        border-left: 4px solid var(--colorYellow);
    }
    
    .menu2-category-items {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 22px;
    }
    
    .menu2-no-items {
        text-align: center;
        padding: 40px 20px;
        background: #fff;
        border-radius: 10px;
        color: #666;
        font-size: 16px;
    }

    .menu2-card {
        background: #fff;
        display: flex;
        height: 110px;
        border-radius: 10px;
        align-items: center;
        cursor: pointer;
    }

    .menu2-card img {
        width: 110px;
        height: 100%;
        object-fit: cover;
        border-radius: 10px 0 0 10px;
        margin-right: 15px;
    }

    .menu2-card-content {
        padding: 10px 0;
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .menu2-card-name {
        margin-bottom: 6px;
        font-size: 17px;
        color: #333;
    }

    .menu2-card-price {
        font-weight: 500;
        color: #1B4E30;
        margin-bottom: 8px;
    }

    .menu2-card-actions {
        text-align: right;
        padding: 0 10px;
    }

    .menu2-btn-add-to-cart {
        border: 1px solid gainsboro;
        padding: 3px 12px;
        border-radius: 16px;
        font-size: 12px;
        cursor: pointer;
        display: inline-block;
    }

    .menu2-btn-add-to-cart:hover {
        background: orange;
        border: none;
    }

/* sticky-cart-widget ----------------- */

    #sticky-cart-widget {
position: fixed;
top: 50%;
right: 0px;                 /* bám mép phải */
left: auto !important;    /* NGẮT mọi left cũ gây kéo dãn */
transform: translateY(-50%);
display: inline-flex;     /* co theo nội dung */
background: #1B4E30;
color: #fff;
border-radius: 8px 0 0 8px;
box-shadow: 0 4px 15px rgba(0,0,0,.2);
cursor: pointer; overflow: hidden; opacity: 0; transition: opacity 0.3s ease, transform 0.3s ease;
padding: 12px 16px;
}

#sticky-cart-widget.show {
  opacity: 1;
  transform: translateY(-50%);
}

.cart-info {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
}

#cart-item-count {
  font-size: 14px;
  opacity: 0.8;
  font-weight: 500;
}

#cart-total-price {
  font-size: 19px;
  font-weight: 700;
}

 
    /* === CSS CHO BILL MODAL (MỚI THÊM TỪ FILE CỦA BẠN) === */

    /* --- Lớp phủ mờ phía sau --- */
    .create-bill {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.6);
        justify-content: center;
        align-items: center;
        z-index: var(--z-index-modal); /* Cao hơn modal chi tiết */

        /* Logic ẩn/hiện */
        display: none; 
        opacity: 0;
        transition: opacity 0.3s ease-out;
    }
    .create-bill.show {
        display: flex;
        opacity: 1;
    }

    /* --- Khung Bill chính --- */
    .create-bill-modal {
        background-color: #ffffff;
        width: 90%;
        max-width: 500px;
        max-height: 90vh;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
        animation: billFadeIn 0.3s ease-out; /* Đổi tên animation để tránh trùng */
        display: flex;
        flex-direction: column;
    }

    @keyframes billFadeIn {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }

    /* --- Header của Bill --- */
    .create-bill-header {
        background-color: #f39c12;
        padding: 15px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-shrink: 0;
        border-radius: 12px 12px 0 0;
    }

    .create-bill-title {
        color: #212529;
        font-size: 1.5rem;
        font-weight: bold;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .create-bill-header-actions {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .bill-save-button {
        background-color: transparent;
        border: 1px solid #212529;
        color: #212529;
        padding: 6px 12px;
        border-radius: 6px;
        font-weight: bold;
        font-size: 0.8rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .menu2-close-button {
        background-color: #ffffff;
        border: none;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        font-size: 1rem;
        font-weight: bold;
        color: #212529;
        cursor: pointer;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    /* --- Thân của Bill --- */
    .bill-body {
        padding: 25px;
        flex: 1;
        overflow-y: auto;
        min-height: 0;
        scroll-behavior: smooth;
    }

    /* Custom scrollbar */
    .bill-body::-webkit-scrollbar { width: 4px; }
    .bill-body::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 4px; }
    .bill-body::-webkit-scrollbar-thumb { background: #c1c1c1; border-radius: 4px; }
    .bill-body::-webkit-scrollbar-thumb:hover { background: #a8a8a8; }

    /* Phần tổng tiền */
    .bill-total-summary {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .bill-total-left .bill-total-title {
        font-size: 1.2rem;
        font-weight: bold;
        margin: 0;
    }

    .bill-total-left .bill-total-note {
        font-size: 11px;
        color: #6c757d;
        margin-top: 5px;
        max-width: 250px;
    }
    
    .bill-total-right .bill-total-price {
        font-size: 1.2rem;
        font-weight: bold;
        color: #212529;
        text-align: right;
    }

    .bill-total-right .bill-clear-bill {
        font-size: 0.8rem;
        color: #6c757d;
        text-decoration: none;
        display: inline-block;
        margin-top: 5px;
        cursor: pointer;
    }
    .bill-clear-bill i {
        margin-right: 5px;
    }
    .bill-clear-bill:hover {
        color: #c0392b; /* Màu đỏ khi hover */
    }

    /* Danh sách món ăn */
    .bill-items {
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid #e9ecef;
    }

    .menu2-bill-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #f1f1f1;
    }

    .menu2-bill-item:last-child {
        border-bottom: none;
    }

    .menu2-item-info { width: 40%; }
    .menu2-item-info .menu2-item-name {
        font-weight: bold;
        margin: 0;
        font-size: 0.95rem; /* Giảm kích thước chữ 1 chút */
    }
    .menu2-item-info .menu2-item-price {
        color: #6c757d;
        font-size: 0.9rem;
    }
    .menu2-item-total-price {
        font-weight: bold;
        font-size: 0.9rem;
    }
    .menu2-delete_item {
        cursor: pointer;
        padding: 5px 10px;
        color: #c0392b; /* Màu đỏ cho dễ thấy */
        font-size: 0.9rem;
    }

    .menu2-item-controls {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .menu2-btn-increase, .menu2-btn-decrease {
        background: #f39c12;
        color: white;
        border: none;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 14px;
    }

    .menu2-btn-increase:hover, .menu2-btn-decrease:hover {
        background: #e67e22;
    }

    .menu2-btn-decrease:disabled {
        background: #bdc3c7;
        cursor: not-allowed;
    }

    .menu2-quantity {
        min-width: 30px;
        text-align: center;
        font-weight: bold;
        font-size: 14px;
    }

    /* --- Footer của Bill --- */
    .bill-footer {
        background-color: #f8f9fa;
        padding: 20px 25px;
        text-align: center;
        border-top: 1px solid #e9ecef;
        flex-shrink: 0;
        border-radius: 0 0 12px 12px;
    }

    .bill-cta-button {
        background-color: #f39c12;
        color: black;
        border: none;
        padding: 15px;
        width: 100%; /* Cho nút full-width */
        border-radius: 30px;
        cursor: pointer;
        text-transform: uppercase;
        margin-bottom: 10px;
        font-weight: bold;
    }

    .bill-footer-note {
        font-size: 0.9rem;
        color: #6c757d;
    }

    .bill-footer-note span {
        font-weight: bold;
        color: #212529;
    }

    /* Styles cho modal thông tin khách hàng */
    #customer-info-modal {
        z-index: 1100; /* Cao hơn modal bill */
    }

    #customer-info-modal .create-bill-modal {
        max-width: 600px;
        max-height: 95vh;
    }

    #customer-info-modal .bill-body {
        max-height: 70vh;
    }

    #customer-info-modal input:focus,
    #customer-info-modal textarea:focus {
        outline: none;
        border-color: #21A256;
        box-shadow: 0 0 0 2px rgba(33, 162, 86, 0.2);
    }

    #customer-info-modal input:invalid {
        border-color: #ef4444;
    }

    #customer-info-modal input:invalid:focus {
        border-color: #ef4444;
        box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.2);
    }

    #customer-info-modal .bill-cta-button:disabled {
        background-color: #bdc3c7;
        cursor: not-allowed;
    }



@media (max-width: 768px) {
    .filter-row {
        flex-direction: column;
        align-items: stretch;
    }
    
    .filter-group {
        width: 100%;
    }
    
    .filter-input, .filter-select {
        min-width: auto;
        width: 100%;
    }
    
    .table-container {
        font-size: 0.875rem;
    }
    
    th, td {
        padding: 0.75rem 0.5rem;
    }
    
}
</style>
<!-- Booking Filters -->
<div class="booking-filters">
    <form id="menu-search-form" method="GET" action="">
        <input type="hidden" name="page" value="nhanvien">
        <input type="hidden" name="action" value="dashboard">
        <input type="hidden" name="section" value="bookings">
        
        <div class="filter-row">
            <div class="filter-group">
                <label for="search">Tìm kiếm món ăn</label>
                <input type="text" id="search" name="search" class="filter-input" 
                       placeholder="Nhập tên món ăn..." 
                       value="<?php echo htmlspecialchars($searchKeyword); ?>">
            </div>
            
            <button type="button" id="search-btn" class="filter-btn">
                <i class="fas fa-search"></i>
                Tìm
            </button>
        </div>
    </form>
</div>

<!-- Bookings Table -->
<div class="bookings-table">
    <div class="table-header">
        <h3 class="table-title">Danh sách món ăn</h3>
    </div>
    
    <!-- Loading indicator -->
    <div id="loading-indicator" style="display: none; text-align: center; padding: 2rem;">
        <i class="fas fa-spinner fa-spin" style="font-size: 2rem; color: #21A256;"></i>
        <p style="margin-top: 1rem; color: #666;">Đang tìm kiếm...</p>
    </div>
    
    <div class="table-container">
         <?php
// Tạm thời ẩn dữ liệu mẫu - sẽ được thay thế bằng kết quả tìm kiếm AJAX
?>

<div class="menu2-grid menu2-category-grid" id="menu2-grid">
    <!-- Kết quả tìm kiếm sẽ được hiển thị ở đây bằng JavaScript -->
    <div class="empty-state" style="grid-column: 1 / -1;">
        <i class="fas fa-search" style="font-size: 3rem; margin-bottom: 1rem; color: #cbd5e1;"></i>
        <h3>Hãy tìm kiếm món ăn</h3>
        <p>Nhập tên món ăn vào ô tìm kiếm để bắt đầu</p>
    </div>
</div>


<div id="create-bill" class="create-bill">
    <div class="create-bill-modal">
        <header class="create-bill-header">
            <div class="create-bill-title"><i class="fas fa-receipt"></i><span>Tạm tính</span></div>
            <div class="create-bill-header-actions">
                <button class="bill-save-button"><i class="fas fa-download"></i> LƯU VỀ MÁY</button>
                <button id="bill-CloseBtn" class="menu2-close-button"><i class="fas fa-times"></i></button>
            </div>
        </header>
        <section class="bill-body">
            <div class="bill-total-summary">
                <div class="bill-total-left">
                    <h3 class="bill-total-title">Tổng tiền</h3>
                    <p class="bill-total-note">Đơn giá tạm tính chỉ mang tính chất tham khảo.</p>
                </div>
                <div class="bill-total-right">
                    <div id="bill-TotalPriceDisplay" class="bill-total-price">0đ</div>
                    <a id="bill-ClearAllBtn" href="#" class="bill-clear-bill"><i class="fas fa-trash-alt"></i> Xoá hết tạm tính</a>
                </div>
            </div>
            <div id="bill-temsContainer" class="bill-items"></div>
        </section>
        <footer class="bill-footer">
            <button id="bill-proceedToBookingBtn" class="bill-cta-button">Tạo đơn</button>
            <p class="bill-footer-note">Hoặc gọi <span>*1986</span> để đặt bàn</p>
        </footer>
    </div>
</div>

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

<div id="sticky-cart-widget">
    <div class="cart-info">
        <span id="cart-item-count">0 món tạm tính</span>
        <strong id="cart-total-price">0đ</strong>
    </div>
</div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=nhanvien&action=dashboard&section=bookings&booking_page=<?php echo $page - 1; ?>&status_filter=<?php echo urlencode($statusFilter); ?>&search=<?php echo urlencode($searchKeyword); ?>&time_filter=<?php echo urlencode($timeFilter); ?>">
                    <i class="fas fa-chevron-left"></i> Trước
                </a>
            <?php endif; ?>
            
            <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                <?php if ($i === $page): ?>
                    <span class="current"><?php echo $i; ?></span>
                <?php else: ?>
                    <a href="?page=nhanvien&action=dashboard&section=bookings&booking_page=<?php echo $i; ?>&status_filter=<?php echo urlencode($statusFilter); ?>&search=<?php echo urlencode($searchKeyword); ?>&time_filter=<?php echo urlencode($timeFilter); ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endif; ?>
            <?php endfor; ?>
            
            <?php if ($page < $totalPages): ?>
                <a href="?page=nhanvien&action=dashboard&section=bookings&booking_page=<?php echo $page + 1; ?>&status_filter=<?php echo urlencode($statusFilter); ?>&search=<?php echo urlencode($searchKeyword); ?>&time_filter=<?php echo urlencode($timeFilter); ?>">
                    Sau <i class="fas fa-chevron-right"></i>
                </a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<script>
// JavaScript functions cho các thao tác đơn đặt bàn
function viewBookingDetail(maDon) {
    // Chuyển hướng đến trang chi tiết
    window.location.href = 'index.php?page=nhanvien&action=viewBookingDetail&id=' + maDon;
}

// Tự động submit form khi thay đổi thời gian (chỉ nếu element tồn tại)
const timeFilterElement = document.getElementById('time_filter');
if (timeFilterElement) {
    timeFilterElement.addEventListener('change', function() {
        this.closest('form').submit();
    });
}

// Tự động submit form khi thay đổi trạng thái (chỉ nếu element tồn tại)
const statusFilterElement = document.getElementById('status_filter');
if (statusFilterElement) {
    statusFilterElement.addEventListener('change', function() {
        this.closest('form').submit();
    });
}

function confirmBooking(maDon) {
    if (confirm('Bạn có chắc chắn muốn xác nhận đơn đặt bàn #' + maDon + '?')) {
        // TODO: Implement confirm booking
        updateBookingStatus(maDon, 'da_xac_nhan');
    }
}

function cancelBooking(maDon) {
    const reason = prompt('Lý do hủy đơn đặt bàn #' + maDon + ':');
    if (reason !== null && reason.trim() !== '') {
        // TODO: Implement cancel booking
        updateBookingStatus(maDon, 'da_huy', reason);
    }
}

function completeBooking(maDon) {
    if (confirm('Đánh dấu đơn đặt bàn #' + maDon + ' đã hoàn thành?')) {
        // TODO: Implement complete booking
        updateBookingStatus(maDon, 'hoan_thanh');
    }
}

function updateBookingStatus(maDon, status, reason = '') {
    // Tạo form ẩn để submit
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = 'index.php?page=nhanvien&action=updateBookingStatus';
    
    const maDonInput = document.createElement('input');
    maDonInput.type = 'hidden';
    maDonInput.name = 'maDon';
    maDonInput.value = maDon;
    form.appendChild(maDonInput);
    
    const statusInput = document.createElement('input');
    statusInput.type = 'hidden';
    statusInput.name = 'status';
    statusInput.value = status;
    form.appendChild(statusInput);
    
    if (reason) {
        const reasonInput = document.createElement('input');
        reasonInput.type = 'hidden';
        reasonInput.name = 'reason';
        reasonInput.value = reason;
        form.appendChild(reasonInput);
    }
    
    document.body.appendChild(form);
    form.submit();
}

// === KHỞI TẠO DỮ LIỆU GIỎ HÀNG ===
const shoppingCart = {}; // { 1: { name: '...', price: 121000, quantity: 2 }, ... }
let totalCartQuantity = 0;
let totalCartPrice = 0;

// === HÀM HELPER ===
function formatPrice(price) {
    price = Math.round(price);
    return price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

document.addEventListener('DOMContentLoaded', function () {
    
    const stickyCartWidget = document.getElementById('sticky-cart-widget');
    const cartCountDisplay = document.getElementById('cart-item-count');
    const cartPriceDisplay = document.getElementById('cart-total-price');
    
    // Kiểm tra xem các element có tồn tại không
    if (!stickyCartWidget || !cartCountDisplay || !cartPriceDisplay) {
        console.warn('Một hoặc nhiều element của sticky cart widget không tồn tại');
        return;
    }
    
    // === HÀM TÍNH TOÁN GIỎ HÀNG ===
    function recalculateCartTotals() {
        totalCartQuantity = 0;
        totalCartPrice = 0;
        for (const itemId in shoppingCart) {
            const item = shoppingCart[itemId];
            totalCartQuantity += item.quantity;
            totalCartPrice += (item.price * item.quantity);
        }
    }
    
    function updateCartWidgetUI() {
        if (totalCartQuantity > 0) {
            cartCountDisplay.textContent = `${totalCartQuantity} món tạm tính`;
            cartPriceDisplay.textContent = formatPrice(totalCartPrice) + 'đ';
            stickyCartWidget.classList.add('show');
        } else {
            stickyCartWidget.classList.remove('show');
        }
    }
    
    function updateAllUI() {
        recalculateCartTotals();
        updateCartWidgetUI();
        // Kiểm tra xem modal bill có đang mở không trước khi update
        const billOverlay = document.getElementById('create-bill');
        if (billOverlay && billOverlay.classList.contains('show')) {
            updateBillModalContent();
        }
    }

    function addToCart(itemId, itemName, itemPrice, quantity = 1) {
        if (shoppingCart[itemId]) {
            shoppingCart[itemId].quantity += quantity;
        } else {
            shoppingCart[itemId] = { name: itemName, price: parseFloat(itemPrice), quantity: quantity };
        }
        updateAllUI();
    }
    
    // === HÀM MỞ MODAL BILL ===
    function openBillModal() {
        if (totalCartQuantity > 0) {
            updateBillModalContent();
            showBillModal();
        } else {
            alert('Giỏ hàng trống!');
        }
    }

    // === HÀM CẬP NHẬT NỘI DUNG MODAL BILL ===
    function updateBillModalContent() {
        const billTotalDisplay = document.getElementById('bill-TotalPriceDisplay');
        const billItemsContainer = document.getElementById('bill-temsContainer');
        
        if (billTotalDisplay) {
            billTotalDisplay.textContent = formatPrice(totalCartPrice) + 'đ';
        }
        
        if (billItemsContainer) {
            billItemsContainer.innerHTML = '';
            
            for (const itemId in shoppingCart) {
                const item = shoppingCart[itemId];
                const itemTotal = item.price * item.quantity;
                
                const billItem = document.createElement('div');
                billItem.className = 'menu2-bill-item';
                billItem.innerHTML = `
                    <div class="menu2-item-info">
                        <p class="menu2-item-name">${item.name}</p>
                        <p class="menu2-item-price">${formatPrice(item.price)}đ/món</p>
                    </div>
                    <div class="menu2-item-controls">
                        <button class="menu2-btn-decrease" data-id="${itemId}">-</button>
                        <span class="menu2-quantity">${item.quantity}</span>
                        <button class="menu2-btn-increase" data-id="${itemId}">+</button>
                    </div>
                    <div class="menu2-item-total-price">${formatPrice(itemTotal)}đ</div>
                    <div class="menu2-delete_item" data-id="${itemId}">
                        <i class="fas fa-trash"></i>
                    </div>
                `;
                
                billItemsContainer.appendChild(billItem);
            }
        }
    }

    // === HÀM HIỂN THỊ/ẨN MODAL BILL ===
    function showBillModal() {
        const billOverlay = document.getElementById('create-bill');
        if (billOverlay) {
            billOverlay.classList.add('show');
            document.body.style.overflow = 'hidden'; // Ngăn scroll trang chính
        }
    }

    function hideBillModal() {
        const billOverlay = document.getElementById('create-bill');
        if (billOverlay) {
            billOverlay.classList.remove('show');
            document.body.style.overflow = ''; // Cho phép scroll lại
        }
    }

    // === HÀM XỬ LÝ THAY ĐỔI SỐ LƯỢNG TRONG MODAL ===
    function increaseQuantity(itemId) {
        if (shoppingCart[itemId]) {
            shoppingCart[itemId].quantity += 1;
            updateAllUI();
            updateBillModalContent();
        }
    }

    function decreaseQuantity(itemId) {
        if (shoppingCart[itemId] && shoppingCart[itemId].quantity > 1) {
            shoppingCart[itemId].quantity -= 1;
            updateAllUI();
            updateBillModalContent();
        } else if (shoppingCart[itemId] && shoppingCart[itemId].quantity === 1) {
            // Nếu chỉ còn 1 món, xóa khỏi giỏ hàng
            removeFromCart(itemId);
        }
    }

    function removeFromCart(itemId) {
        if (shoppingCart[itemId]) {
            delete shoppingCart[itemId];
            updateAllUI();
            updateBillModalContent();
            
            // Nếu giỏ hàng trống, đóng modal
            if (totalCartQuantity === 0) {
                hideBillModal();
            }
        }
    }

    function clearAllCart() {
        if (confirm('Bạn có chắc chắn muốn xóa tất cả món ăn trong tạm tính?')) {
            Object.keys(shoppingCart).forEach(key => delete shoppingCart[key]);
            updateAllUI();
            hideBillModal();
        }
    }

    function clearCartSilently() {
       Object.keys(shoppingCart).forEach(key => delete shoppingCart[key]);
        updateAllUI();
    }
    
    // === XỬ LÝ SỰ KIỆN ===
    // Xử lý click nút "+ Đặt"
    document.addEventListener('click', function (e) {
        const target = e.target;
        
        // Xử lý nút "+ Đặt" 
        if (target.closest('.menu2-btn-add-to-cart')) {
            e.stopPropagation(); 
            e.preventDefault(); 
            const btn = target.closest('.menu2-btn-add-to-cart');
            addToCart(btn.dataset.id, btn.dataset.name, btn.dataset.price, 1);
            return; 
        }
        
        // Xử lý nút tăng số lượng trong modal
        if (target.closest('.menu2-btn-increase')) {
            e.stopPropagation();
            e.preventDefault();
            const btn = target.closest('.menu2-btn-increase');
            increaseQuantity(btn.dataset.id);
            return;
        }
        
        // Xử lý nút giảm số lượng trong modal
        if (target.closest('.menu2-btn-decrease')) {
            e.stopPropagation();
            e.preventDefault();
            const btn = target.closest('.menu2-btn-decrease');
            decreaseQuantity(btn.dataset.id);
            return;
        }
        
        // Xử lý nút xóa món trong modal
        if (target.closest('.menu2-delete_item')) {
            e.stopPropagation();
            e.preventDefault();
            const btn = target.closest('.menu2-delete_item');
            removeFromCart(btn.dataset.id);
            return;
        }
        
        // Xử lý nút đóng modal
        if (target.closest('#bill-CloseBtn')) {
            e.stopPropagation();
            e.preventDefault();
            hideBillModal();
            return;
        }
        
        // Xử lý nút xóa tất cả trong modal
        if (target.closest('#bill-ClearAllBtn')) {
            e.stopPropagation();
            e.preventDefault();
            clearAllCart();
            return;
        }
        
        // Xử lý click vào overlay để đóng modal
        if (target.closest('.create-bill') && !target.closest('.create-bill-modal')) {
            hideBillModal();
            return;
        }
    });
    
    // Xử lý phím ESC để đóng modal
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const billOverlay = document.getElementById('create-bill');
            if (billOverlay && billOverlay.classList.contains('show')) {
                hideBillModal();
            }
        }
    });
    
    stickyCartWidget.addEventListener('click', openBillModal);
    
    // Xử lý nút "Tạo đơn"
    const proceedToBookingBtn = document.getElementById('bill-proceedToBookingBtn');
    if (proceedToBookingBtn) {
        proceedToBookingBtn.addEventListener('click', function() {
            if (totalCartQuantity > 0) {
                handleCreateOrderDirect();
            } else {
                alert('Giỏ hàng trống! Vui lòng thêm món ăn trước khi tạo đơn.');
            }
        });
    }
    
});

// === CHỨC NĂNG TẠO ĐƠN TRỰC TIẾP ===
async function handleCreateOrderDirect() {
    // Kiểm tra giỏ hàng không trống
    if (totalCartQuantity === 0) {
        alert('Giỏ hàng trống! Vui lòng thêm món ăn trước khi tạo đơn.');
        return;
    }
    
    // Xác nhận tạo đơn
    if (!confirm('Bạn có chắc chắn muốn tạo đơn đặt món này không?')) {
        return;
    }
    
    // Chuẩn bị dữ liệu giỏ hàng
    const cartItems = [];
    for (const itemId in shoppingCart) {
        const item = shoppingCart[itemId];
        cartItems.push({
            id: itemId,
            name: item.name,
            price: item.price,
            quantity: item.quantity
        });
    }
    
    // Chuẩn bị dữ liệu gửi lên server (không cần thông tin khách hàng)
    const orderData = {
        customerInfo: {
            name: 'Khách hàng tại quán',
            phone: '',
            email: '',
            notes: 'Đặt món tại quán'
        },
        cartItems: cartItems
    };
    
    // Hiển thị loading trên nút
    const proceedBtn = document.getElementById('bill-proceedToBookingBtn');
    const originalText = proceedBtn.innerHTML;
    proceedBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang tạo đơn...';
    proceedBtn.disabled = true;
    try {
        // Gửi request tạo đơn
        const response = await fetch('index.php?page=nhanvien&action=createOrder', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(orderData)
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Thành công
            alert(`Tạo đơn đặt món thành công! Mã đơn: ${result.data.maDon}`);
            
            // Reset giỏ hàng và đóng modal
            Object.keys(shoppingCart).forEach(key => delete shoppingCart[key]);
            
            // Cập nhật UI trực tiếp thay vì gọi updateAllUI()
            totalCartQuantity = 0;
            totalCartPrice = 0;
            const stickyCartWidget = document.getElementById('sticky-cart-widget');
            if (stickyCartWidget) {
                stickyCartWidget.classList.remove('show');
            }
            
            // Đóng modal trực tiếp thay vì gọi hideBillModal()
            const billOverlay = document.getElementById('create-bill');
            if (billOverlay) {
                billOverlay.classList.remove('show');
                document.body.style.overflow = '';
            }
            
            // Có thể chuyển hướng đến trang chi tiết đơn hoặc danh sách đơn
            if (confirm('Bạn có muốn xem chi tiết đơn đặt món vừa tạo không?')) {
                window.location.href = `index.php?page=nhanvien&action=viewBookingDetail&id=${result.data.maDon}`;
            }
            
        } else {
            // Lỗi từ server
            alert('Có lỗi xảy ra: ' + (result.error || 'Không thể tạo đơn đặt món'));
        }
        
    } catch (error) {
        console.error('Error creating order:', error);
        alert('Có lỗi xảy ra khi tạo đơn. Vui lòng thử lại!');
    } finally {
        // Khôi phục nút
        proceedBtn.innerHTML = originalText;
        proceedBtn.disabled = false;
    }
}

// === CHỨC NĂNG TÌM KIẾM MENU ===
let currentSearchQuery = '';
let isSearching = false;

function formatPrice(price) {
    price = Math.round(price);
    return price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

function renderMenuItems(items) {
    const menuGrid = document.getElementById('menu2-grid');
    if (!menuGrid) return;
    
    menuGrid.innerHTML = '';
    
    if (items.length === 0) {
        menuGrid.innerHTML = `
            <div class="empty-state" style="grid-column: 1 / -1;">
                <i class="fas fa-search" style="font-size: 3rem; margin-bottom: 1rem; color: #cbd5e1;"></i>
                <h3>Không tìm thấy món ăn nào</h3>
                <p>Thử tìm kiếm với từ khóa khác hoặc nhấn nút "Xóa" để reset</p>
            </div>
        `;
        return;
    }
    
    items.forEach(item => {
        const menuCard = document.createElement('div');
        menuCard.className = 'menu2-card';
        menuCard.setAttribute('data-action', 'open-modal');
        menuCard.setAttribute('data-id', item.MaMon);
        menuCard.setAttribute('data-name', item.TenMon);
        menuCard.setAttribute('data-price', item.Gia);
        menuCard.setAttribute('data-description', item.MoTa || '');
        menuCard.setAttribute('data-image-url', item.HinhAnhURL || 'https://storage.quannhautudo.com/data/thumb_400/Data/images/product/2025/06/202506271712248578.webp');
        
        menuCard.innerHTML = `
            <img src="${item.HinhAnhURL || 'https://storage.quannhautudo.com/data/thumb_400/Data/images/product/2025/06/202506271712248578.webp'}" 
                 alt="${item.TenMon}"
                 onerror="this.src='https://storage.quannhautudo.com/data/thumb_400/Data/images/product/2025/06/202506271712248578.webp'">
            
            <div class="menu2-card-content">
                <span class="menu2-card-name">${item.TenMon}</span>
                <div class="menu2-card-price">
                    ${formatPrice(item.Gia)}đ
                </div>
                <div class="menu2-card-actions">
                    <div class="menu2-btn-add-to-cart" 
                         data-action="add-to-cart"
                         data-id="${item.MaMon}"
                         data-name="${item.TenMon}"
                         data-price="${item.Gia}">+ Đặt</div>
                </div>
            </div>
        `;
        
        menuGrid.appendChild(menuCard);
    });
}

function updateSearchInfo(totalItems, query) {
    const searchInfo = document.getElementById('search-info');
    if (!searchInfo) return;
    
    if (query.trim() === '') {
        searchInfo.textContent = 'Hãy tìm kiếm món ăn';
    } else {
        searchInfo.textContent = `Tìm thấy ${totalItems} món ăn cho "${query}"`;
    }
}

function showLoading(show = true) {
    const loadingIndicator = document.getElementById('loading-indicator');
    const menuGrid = document.getElementById('menu2-grid');
    
    if (loadingIndicator) {
        loadingIndicator.style.display = show ? 'block' : 'none';
    }
    
    if (menuGrid && show) {
        menuGrid.style.opacity = '0.5';
    } else if (menuGrid) {
        menuGrid.style.opacity = '1';
    }
}

async function performSearch(query) {
    if (isSearching) return;
    
    try {
        isSearching = true;
        showLoading(true);
        
        const params = new URLSearchParams({
            tenMon: query.trim()
        });
        
        const url = `index.php?page=nhanvien&action=searchMenu&${params}`;
        const response = await fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json'
            }
        });
        
        
        if (!response.ok) {
            const errorText = await response.text();
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        
        const data = await response.json();
        
        if (data.success) {
            renderMenuItems(data.data.items);
            updateSearchInfo(data.data.items.length, query);
            currentSearchQuery = query;
        } else {
            throw new Error(data.error || 'Có lỗi xảy ra khi tìm kiếm');
        }
        
    } catch (error) {
        console.error('Search error:', error);
        
        // Hiển thị thông báo lỗi
        const menuGrid = document.getElementById('menu2-grid');
        if (menuGrid) {
            menuGrid.innerHTML = `
                <div class="empty-state" style="grid-column: 1 / -1;">
                    <i class="fas fa-exclamation-triangle" style="font-size: 3rem; margin-bottom: 1rem; color: #ef4444;"></i>
                    <h3>Có lỗi xảy ra</h3>
                    <p>${error.message}</p>
                    <button onclick="performSearch('${query}')" 
                            style="margin-top: 1rem; padding: 0.5rem 1rem; background: #21A256; color: white; border: none; border-radius: 6px; cursor: pointer;">
                        Thử lại
                    </button>
                </div>
            `;
        }
        
        updateSearchInfo(0, query);
        
    } finally {
        isSearching = false;
        showLoading(false);
    }
}

// Xử lý sự kiện tìm kiếm
document.addEventListener('DOMContentLoaded', function() {
    const billOverlay = document.getElementById('create-bill');
    const searchBtn = document.getElementById('search-btn');
    const searchInput = document.getElementById('search');
    
    if (searchBtn) {
        searchBtn.addEventListener('click', function() {
            const query = searchInput ? searchInput.value.trim() : '';
            if (query === '') {
                alert('Vui lòng nhập tên món ăn để tìm kiếm');
                return;
            }
            performSearch(query);
        });
    }
    

    
    if (searchInput) {
        // Tìm kiếm khi nhấn Enter
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const query = this.value.trim();
                performSearch(query);
            }
        });
    }
    
    // Khởi tạo giao diện ban đầu
    const menuGrid = document.getElementById('menu2-grid');
    if (menuGrid) {
        menuGrid.innerHTML = `
            <div class="empty-state" style="grid-column: 1 / -1;">
                <i class="fas fa-search" style="font-size: 3rem; margin-bottom: 1rem; color: #cbd5e1;"></i>
                <h3>Hãy tìm kiếm món ăn</h3>
                <p>Nhập tên món ăn và nhấn nút "Tìm" hoặc phím Enter</p>
            </div>
        `;
    }
});
</script>