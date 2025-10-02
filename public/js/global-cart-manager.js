/**
 * Global Cart Manager
 * Quản lý giỏ hàng toàn cục và sticky widget trên tất cả các trang
 */
(function () {
    'use strict';

    // Chờ DOM và CartCookies sẵn sàng
    document.addEventListener('DOMContentLoaded', function () {
        // Kiểm tra xem CartCookies có sẵn không
        if (typeof window.CartCookies === 'undefined') {
            console.warn('CartCookies not loaded, global cart manager disabled');
            return;
        }

        initGlobalCart();
    });

    function initGlobalCart() {
        const stickyCartWidget = document.getElementById('menu2-sticky-cart-widget');
        const cartCountDisplay = document.getElementById('menu2-cart-item-count');
        const cartPriceDisplay = document.getElementById('menu2-cart-total-price');

        if (!stickyCartWidget || !cartCountDisplay || !cartPriceDisplay) {
            console.warn('Global cart elements not found');
            return;
        }

        // Khôi phục và hiển thị giỏ hàng từ cookies
        updateGlobalCartDisplay();

        // Xử lý click vào sticky cart widget để mở bill modal
        stickyCartWidget.addEventListener('click', function () {
            openGlobalBillModal();
        });

        // Xử lý đóng bill modal
        const billCloseBtn = document.getElementById('menu2-billCloseBtn');
        const billOverlay = document.getElementById('menu2-billOverlay');
        const billClearAllBtn = document.getElementById('menu2-billClearAllBtn');

        if (billCloseBtn) {
            billCloseBtn.addEventListener('click', closeGlobalBillModal);
        }

        if (billOverlay) {
            // Đóng modal khi click vào overlay
            billOverlay.addEventListener('click', function (e) {
                if (e.target === billOverlay) {
                    closeGlobalBillModal();
                }
            });
        }

        if (billClearAllBtn) {
            billClearAllBtn.addEventListener('click', function (e) {
                e.preventDefault();
                if (confirm('Bạn có chắc chắn muốn xoá tất cả các món trong giỏ hàng tạm?')) {
                    window.CartCookies.clearCart();
                    updateGlobalCartDisplay();
                    renderGlobalBillItems();
                }
            });
        }

        // Cập nhật hiển thị định kỳ (trong trường hợp có thay đổi từ tab khác)
        setInterval(updateGlobalCartDisplay, 5000);
    }

    function updateGlobalCartDisplay() {
        const summary = window.CartCookies.getCartSummary();
        const stickyCartWidget = document.getElementById('menu2-sticky-cart-widget');
        const cartCountDisplay = document.getElementById('menu2-cart-item-count');
        const cartPriceDisplay = document.getElementById('menu2-cart-total-price');

        if (!stickyCartWidget || !cartCountDisplay || !cartPriceDisplay) {
            return;
        }

        if (summary.totalQuantity > 0) {
            cartCountDisplay.textContent = `${summary.totalQuantity} món tạm tính`;
            cartPriceDisplay.textContent = formatPrice(summary.totalPrice) + 'đ';
            stickyCartWidget.classList.add('show');
        } else {
            stickyCartWidget.classList.remove('show');
        }
    }

    // Helper function to format price
    function formatPrice(price) {
        price = Math.round(price);
        return price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    // Functions xử lý bill modal
    function openGlobalBillModal() {
        const billOverlay = document.getElementById('menu2-billOverlay');
        if (billOverlay) {
            renderGlobalBillItems();
            billOverlay.classList.add('show');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeGlobalBillModal() {
        const billOverlay = document.getElementById('menu2-billOverlay');
        if (billOverlay) {
            billOverlay.classList.remove('show');
            document.body.style.overflow = 'auto';
        }
    }

    function renderGlobalBillItems() {
        const billItemsContainer = document.getElementById('menu2-billItemsContainer');
        const billTotalPriceDisplay = document.getElementById('menu2-billTotalPriceDisplay');

        if (!billItemsContainer || !billTotalPriceDisplay) return;

        const cartData = window.CartCookies.loadCart() || {};
        const summary = window.CartCookies.getCartSummary();

        billItemsContainer.innerHTML = '';

        if (Object.keys(cartData).length === 0) {
            billItemsContainer.innerHTML = '<p style="text-align:center; color:#6c757d; padding: 20px 0;">Giỏ hàng của bạn đang trống.</p>';
            billTotalPriceDisplay.textContent = '0đ';
            return;
        }

        for (const itemId in cartData) {
            const item = cartData[itemId];
            const itemTotalPrice = item.price * item.quantity;
            const itemHtml = `
                <div class="menu2-bill-item" data-item-id="${itemId}">
                    <div class="menu2-item-info">
                        <p class="menu2-item-name">${escapeHtml(item.name)}</p>
                        <p class="menu2-item-price">${formatPrice(item.price)}đ</p>
                    </div>
                    <div class="menu2-item-controls">
                        <div class="menu2-quantity-controls">
                            <button type="button" class="menu2-bill-qty-decrease" data-action="bill-qty-decrease">-</button>
                            <input type="number" value="${item.quantity}" min="1" readonly>
                            <button type="button" class="menu2-bill-qty-increase" data-action="bill-qty-increase">+</button>
                        </div>
                        <div class="menu2-item-total-price">${formatPrice(itemTotalPrice)}đ</div>
                        <div class="menu2-delete_item" data-action="delete-item"><i class="fas fa-trash-alt"></i></div>
                    </div>
                </div>`;
            billItemsContainer.insertAdjacentHTML('beforeend', itemHtml);
        }

        billTotalPriceDisplay.textContent = formatPrice(summary.totalPrice) + 'đ';

        // Add event listeners cho các nút trong bill items
        addBillItemEventListeners();
    }

    function addBillItemEventListeners() {
        const billItemsContainer = document.getElementById('menu2-billItemsContainer');
        if (!billItemsContainer) return;

        // Remove existing listeners để tránh duplicate
        const existingContainer = billItemsContainer.cloneNode(true);
        billItemsContainer.parentNode.replaceChild(existingContainer, billItemsContainer);

        existingContainer.addEventListener('click', function (e) {
            const actionTarget = e.target.closest('[data-action]');
            if (!actionTarget) return;

            const action = actionTarget.dataset.action;
            const itemDiv = e.target.closest('.menu2-bill-item');
            if (!itemDiv) return;

            const itemId = itemDiv.dataset.itemId;
            const cartData = window.CartCookies.loadCart() || {};

            if (!cartData[itemId]) return;

            if (action === 'bill-qty-increase') {
                window.CartCookies.updateItemQuantity(itemId, cartData[itemId].quantity + 1);
            } else if (action === 'bill-qty-decrease') {
                if (cartData[itemId].quantity > 1) {
                    window.CartCookies.updateItemQuantity(itemId, cartData[itemId].quantity - 1);
                } else {
                    window.CartCookies.removeItem(itemId);
                }
            } else if (action === 'delete-item') {
                window.CartCookies.removeItem(itemId);
            }

            updateGlobalCartDisplay();
            renderGlobalBillItems();
        });
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Expose global functions
    window.updateGlobalCart = updateGlobalCartDisplay;
    window.openGlobalBillModal = openGlobalBillModal;
    window.closeGlobalBillModal = closeGlobalBillModal;

})();