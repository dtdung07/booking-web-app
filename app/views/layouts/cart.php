
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