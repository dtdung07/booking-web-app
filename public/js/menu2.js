
// === NGUỒN DỮ LIỆU TRUNG TÂM (TOÀN CỤC) ===
let shoppingCart = {}; // { 1: { name: '...', price: 121000, quantity: 2 }, ... }
let totalCartQuantity = 0;
let totalCartPrice = 0;

// === BIẾN TOÀN CỤC CHO MODAL CHI TIẾT ===
let currentItemId = null;
let currentItemPrice = 0;
let currentItemName = '';

// === HÀM HELPER TOÀN CỤC ===
function formatPrice(price) {
    return price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

// --- Hàm SL cho Modal Món Ăn (điều khiển input #quantity) ---
function decreaseQuantity() {
    const quantityInput = document.getElementById('quantity');
    if (parseInt(quantityInput.value) > 1) {
        quantityInput.value = parseInt(quantityInput.value) - 1;
    }
}

function increaseQuantity() {
    const quantityInput = document.getElementById('quantity');
    quantityInput.value = parseInt(quantityInput.value) + 1;
    // Giả sử không giới hạn khi thêm từ modal
}

// --- Hàm SL cho Form Đặt Bàn (điều khiển div .quantity-display) (ĐÃ ĐỔI TÊN) ---
function decreaseBookingGuests() {
    const display = document.querySelector('.booking-form-container .quantity-display');
    let currentValue = parseInt(display.textContent);
    if (currentValue > 1) {
        display.textContent = currentValue - 1;
    }
}

function increaseBookingGuests() {
    const display = document.querySelector('.booking-form-container .quantity-display');
    let currentValue = parseInt(display.textContent);
    if (currentValue < 20) { // Giới hạn tối đa 20 người
        display.textContent = currentValue + 1;
    }
}


// === LOGIC CHÍNH - BAO GỒM TẤT CẢ TRONG DOMCONTENTLOADED ===
document.addEventListener('DOMContentLoaded', function () {

    // === KHAI BÁO BIẾN DOM (Tất cả các hệ thống) ===

    // --- Hệ thống 1: Views (Các "Trang") ---
    const menuPageView = document.querySelector('.menu-page');
    const bookingFormView = document.querySelector('.booking-form-container');

    // --- Hệ thống 2: Menu / Giỏ hàng / Bill ---
    const stickyCartWidget = document.getElementById('sticky-cart-widget');
    const cartCountDisplay = document.getElementById('cart-item-count');
    const cartPriceDisplay = document.getElementById('cart-total-price');
    const itemModal = document.getElementById('itemModal');
    const quantityInputModal = document.getElementById('quantity');
    const billOverlay = document.getElementById('billOverlay');
    const billCloseBtn = document.getElementById('billCloseBtn');
    const billItemsContainer = document.getElementById('billItemsContainer');
    const billTotalPriceDisplay = document.getElementById('billTotalPriceDisplay');
    const billClearAllBtn = document.getElementById('billClearAllBtn');
    const proceedToBookingBtn = document.getElementById('proceedToBookingBtn'); // Nút chuyển tiếp MỚI

    // --- Hệ thống 3: Calendar của Form Đặt Bàn ---
    const dateInput = document.getElementById('date-display-input');
    const dpOverlay = document.getElementById('datePickerOverlay'); // Đổi tên biến để tránh xung đột với billOverlay
    const dpModal = document.getElementById('datePickerModal');
    const dpGrid = document.getElementById('dp-days-grid');
    const dpMonthYearDisplay = document.getElementById('dp-current-month-year');
    const dpPrevMonthBtn = document.getElementById('dp-prev-month');
    const dpNextMonthBtn = document.getElementById('dp-next-month');
    const dpTodayBtn = document.getElementById('dp-today-btn');
    const dpCloseBtn = document.getElementById('dp-close-btn');


    // === HỆ THỐNG 2: LOGIC GIỎ HÀNG & BILL ===

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

    function renderBillItems() {
        billItemsContainer.innerHTML = '';
        if (Object.keys(shoppingCart).length === 0) {
            billItemsContainer.innerHTML = '<p style="text-align:center; color:#6c757d; padding: 20px 0;">Giỏ hàng của bạn đang trống.</p>';
            billTotalPriceDisplay.textContent = '0đ';
            return;
        }
        for (const itemId in shoppingCart) {
            const item = shoppingCart[itemId];
            const itemTotalPrice = item.price * item.quantity;
            const itemHtml = `
                    <div class="bill-item" data-item-id="${itemId}">
                        <div class="item-info">
                            <p class="item-name">${item.name}</p>
                            <p class="item-price">${formatPrice(item.price)}đ</p>
                        </div>
                        <div class="item-controls">
                            <div class="quantity-controls">
                                <button type="button" class="bill-qty-decrease">-</button>
                                <input type="number" value="${item.quantity}" min="1" readonly>
                                <button type="button" class="bill-qty-increase">+</button>
                            </div>
                            <div class="item-total-price">${formatPrice(itemTotalPrice)}đ</div>
                            <div class="delete_item"><i class="fas fa-trash-alt"></i></div>
                        </div>
                    </div>`;
            billItemsContainer.insertAdjacentHTML('beforeend', itemHtml);
        }
        billTotalPriceDisplay.textContent = formatPrice(totalCartPrice) + 'đ';
    }

    function updateAllUI() {
        recalculateCartTotals();
        updateCartWidgetUI();
        if (billOverlay.classList.contains('show')) {
            renderBillItems();
        }
    }

    // Gán hàm vào window để HTML onclick có thể gọi được
    window.addToCart = (itemId, itemName, itemPrice) => {
        if (shoppingCart[itemId]) {
            shoppingCart[itemId].quantity += 1;
        } else {
            shoppingCart[itemId] = { name: itemName, price: itemPrice, quantity: 1 };
        }
        updateAllUI();
    };

    window.orderNow = () => {
        const quantity = parseInt(quantityInputModal.value);
        if (shoppingCart[currentItemId]) {
            shoppingCart[currentItemId].quantity += quantity;
        } else {
            shoppingCart[currentItemId] = { name: currentItemName, price: currentItemPrice, quantity: quantity };
        }
        updateAllUI();
        closeItemModal();
    };

    // Logic Modal Chi Tiết Món Ăn
    window.openModal = (itemId, itemName, itemPrice) => {
        currentItemId = itemId;
        currentItemPrice = itemPrice;
        currentItemName = itemName;
        document.getElementById('modalItemName').textContent = itemName;
        document.getElementById('modalPrice').textContent = formatPrice(itemPrice) + 'đ';
        document.getElementById('modalImage').src = 'https://storage.quannhautudo.com/data/thumb_400/Data/images/product/2025/06/202506271712248578.webp';
        quantityInputModal.value = 1;
        itemModal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    };

    function closeItemModal() {
        itemModal.style.display = 'none';
        if (!billOverlay.classList.contains('show') && !dpOverlay.classList.contains('show')) {
            document.body.style.overflow = 'auto';
        }
    }

    // Logic Bill Modal
    function openBillModal() {
        renderBillItems();
        billOverlay.classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    window.closeBillModal = () => { // Gắn vào window để hàm khác có thể gọi
        billOverlay.classList.remove('show');
        if (itemModal.style.display !== 'block' && !dpOverlay.classList.contains('show')) {
            document.body.style.overflow = 'auto';
        }
    };

    stickyCartWidget.addEventListener('click', openBillModal);
    billCloseBtn.addEventListener('click', closeBillModal);

    billClearAllBtn.addEventListener('click', function (e) {
        e.preventDefault();
        if (confirm('Bạn có chắc chắn muốn xoá tất cả các món trong giỏ hàng tạm?')) {
            shoppingCart = {};
            updateAllUI();
        }
    });

    billItemsContainer.addEventListener('click', function (e) {
        const target = e.target;
        const itemDiv = target.closest('.bill-item');
        if (!itemDiv) return;
        const itemId = itemDiv.dataset.itemId;

        if (target.classList.contains('bill-qty-increase')) {
            shoppingCart[itemId].quantity += 1;
        } else if (target.classList.contains('bill-qty-decrease')) {
            if (shoppingCart[itemId].quantity > 1) {
                shoppingCart[itemId].quantity -= 1;
            } else {
                delete shoppingCart[itemId];
            }
        } else if (target.closest('.delete_item')) {
            delete shoppingCart[itemId];
        }
        updateAllUI();
    });


    // === HỆ THỐNG 3: LOGIC CALENDAR (CHO FORM ĐẶT BÀN) ===
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    const maxDate = new Date(today);
    maxDate.setMonth(maxDate.getMonth() + 2);
    let selectedDate = new Date(today);
    let currentCalendarDate = new Date(today);

    function formatDateForInput(date) {
        const monthName = date.toLocaleString('vi-VN', { month: 'long' });
        const day = ('0' + date.getDate()).slice(-2);
        return `${day} ${monthName}`;
    }

    if (dateInput) { // Chỉ chạy nếu có input này
        dateInput.value = formatDateForInput(today);
    }

    function renderCalendar(year, month) {
        currentCalendarDate.setFullYear(year, month, 1);
        dpGrid.innerHTML = '';
        dpMonthYearDisplay.textContent = currentCalendarDate.toLocaleString('vi-VN', { month: 'long', year: 'numeric' });

        const firstDayOfMonth = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();

        for (let i = 0; i < firstDayOfMonth; i++) {
            dpGrid.insertAdjacentHTML('beforeend', '<div class="dp-day empty"></div>');
        }

        for (let day = 1; day <= daysInMonth; day++) {
            const cellDate = new Date(year, month, day);
            const cell = document.createElement('div');
            cell.className = 'dp-day';
            cell.textContent = day;
            cell.dataset.date = cellDate.toISOString();
            let classes = [];
            if (cellDate < today || cellDate > maxDate) classes.push('disabled');
            if (cellDate.getTime() === today.getTime()) classes.push('today');
            if (selectedDate && cellDate.getTime() === selectedDate.getTime()) classes.push('selected');
            cell.classList.add(...classes);
            dpGrid.appendChild(cell);
        }
        dpPrevMonthBtn.disabled = (year === today.getFullYear() && month === today.getMonth());
        dpNextMonthBtn.disabled = (year === maxDate.getFullYear() && month === maxDate.getMonth());
    }

    function closeDatePickerModal() {
        dpOverlay.classList.remove('show');
        if (itemModal.style.display !== 'block' && !billOverlay.classList.contains('show')) {
            document.body.style.overflow = 'auto';
        }
    }

    if (dateInput) {
        dateInput.addEventListener('click', () => {
            renderCalendar(selectedDate.getFullYear(), selectedDate.getMonth());
            dpOverlay.classList.add('show');
            document.body.style.overflow = 'hidden';
        });
    }

    dpCloseBtn.addEventListener('click', closeDatePickerModal);
    dpOverlay.addEventListener('click', (e) => {
        if (e.target === dpOverlay) closeDatePickerModal();
    });
    dpPrevMonthBtn.addEventListener('click', () => {
        renderCalendar(currentCalendarDate.getFullYear(), currentCalendarDate.getMonth() - 1);
    });
    dpNextMonthBtn.addEventListener('click', () => {
        renderCalendar(currentCalendarDate.getFullYear(), currentCalendarDate.getMonth() + 1);
    });
    dpTodayBtn.addEventListener('click', () => {
        selectedDate = new Date(today);
        dateInput.value = formatDateForInput(selectedDate);
        closeDatePickerModal();
    });
    dpGrid.addEventListener('click', (e) => {
        const target = e.target.closest('.dp-day');
        if (!target || target.classList.contains('empty') || target.classList.contains('disabled')) {
            return;
        }
        selectedDate = new Date(target.dataset.date);
        dateInput.value = formatDateForInput(selectedDate);
        closeDatePickerModal();
    });


    // === LOGIC MỚI: CHUYỂN TIẾP TỪ BILL SANG ĐẶT BÀN ===
    proceedToBookingBtn.addEventListener('click', function () {
        closeBillModal(); // Đóng bill
        showBookingForm(); // Hiển thị booking form với overlay
    });


    // === Đóng modal chung ===
    // Đóng modal khi nhấn ESC
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeItemModal();
            closeBillModal();
            closeDatePickerModal();
            closeBookingForm();
        }
    });

    // Đóng modal khi click outside
    window.onclick = function (event) {
        if (event.target == itemModal) closeItemModal();
        if (event.target == billOverlay) closeBillModal();
        if (event.target == dpOverlay) closeDatePickerModal();
    }

}); // --- KẾT THÚC DOMCONTENTLOADED ---

// === FUNCTIONS TOÀN CỤC CHO BOOKING FORM ===
function showBookingForm() {
    const bookingOverlay = document.getElementById('bookingOverlay');
    if (bookingOverlay) {
        bookingOverlay.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
}

function closeBookingForm() {
    const bookingOverlay = document.getElementById('bookingOverlay');
    if (bookingOverlay) {
        bookingOverlay.classList.remove('show');
        document.body.style.overflow = 'auto';
    }
}

// Click outside để đóng booking form
document.addEventListener('click', function (event) {
    const bookingOverlay = document.getElementById('bookingOverlay');
    if (event.target === bookingOverlay) {
        closeBookingForm();
    }
});