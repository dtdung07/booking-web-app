<main class="uudai2-page">
    <section class="uudai2-tab-section">
        <div class="container">
            <div class="uudai2-tab-navigation">
                <button class="uudai2-tab-btn active" data-category="all">
                    <span class="uudai2-tab-text">TẤT CẢ</span>
                </button>
                <button class="uudai2-tab-btn" data-category="active">
                    <span class="uudai2-tab-text">ĐANG ÁP DỤNG</span>
                </button>
                <button class="uudai2-tab-btn" data-category="expired">
                    <span class="uudai2-tab-text">ĐÃ KẾT THÚC</span>
                </button>
            </div>
        </div>
    </section>

    <div class="uudai2-container">
        <h2 class="uudai2-title" id="uudai2-category-title">ƯU ĐÃI</h2>

        <div class="uudai2-grid" id="uudai2-grid">
            <!-- Dữ liệu sẽ được load bằng JavaScript -->
            <div class="uudai2-loading">
                <i class="fas fa-spinner fa-spin"></i>
                <p>Đang tải ưu đãi...</p>
            </div>
        </div>
    </div>
</main>

<!-- Modal chi tiết -->
<div id="uudai2-itemModal" class="uudai2-modal">
    <div class="uudai2-modal-box">
        <div class="uudai2-modal-image">
            <img id="uudai2-modalImage" src="" alt="Hình ảnh ưu đãi">
        </div>
        <div class="uudai2-modal-body">
            <div class="uudai2-modal-info">
                <p id="uudai2-modalItemName"></p>
                <div class="uudai2-modal-price-quantity">
                    <p class="uudai2-modal-price"><span id="uudai2-modalDiscount">0%</span></p>
                    <div class="uudai2-modal-status">
                        <span class="uudai2-status-badge" id="uudai2-modalStatus">ĐANG ÁP DỤNG</span>
                    </div>
                </div>
                <hr>
                <div class="uudai2-modal-description">
                    <h4>Mô tả ưu đãi:</h4>
                    <p id="uudai2-modalDescription">...</p>
                </div>
                <div class="uudai2-modal-dates">
                    <div class="uudai2-date-item">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Bắt đầu: <strong id="uudai2-modalStartDate"></strong></span>
                    </div>
                    <div class="uudai2-date-item">
                        <i class="fas fa-calendar-check"></i>
                        <span>Kết thúc: <strong id="uudai2-modalEndDate"></strong></span>
                    </div>
                </div>
                <div class="uudai2-modal-actions">
                    <button class="uudai2-btn-order-now" id="uudai2-usePromotionBtn">Sử dụng ưu đãi</button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.uudai2-page {
    padding: 20px 0;
    background: #f8f9fa;
    min-height: 100vh;
}

.uudai2-tab-section {
    background: white;
    padding: 15px 0;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    position: sticky;
    top: 0;
    z-index: 100;
}

.uudai2-tab-navigation {
    display: flex;
    gap: 10px;
    overflow-x: auto;
    padding: 10px 0;
}

.uudai2-tab-btn {
    padding: 12px 24px;
    border: 2px solid #e9ecef;
    background: white;
    border-radius: 25px;
    cursor: pointer;
    white-space: nowrap;
    transition: all 0.3s ease;
    font-weight: 600;
}

.uudai2-tab-btn.active {
    background: #dc3545;
    color: white;
    border-color: #dc3545;
}

.uudai2-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.uudai2-title {
    text-align: center;
    color: #333;
    margin-bottom: 30px;
    font-size: 2rem;
    font-weight: 700;
}

.uudai2-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
}

.uudai2-card {
    background: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    cursor: pointer;
}

.uudai2-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
}

.uudai2-card img {
    width: 100%;
    height: 160px;
    object-fit: cover;
}

.uudai2-card-content {
    padding: 15px;
}

.uudai2-card-name {
    font-size: 1.1rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
    display: block;
}

.uudai2-card-discount {
    font-size: 1.5rem;
    font-weight: 700;
    color: #dc3545;
    margin-bottom: 8px;
}

.uudai2-card-status {
    font-size: 0.8rem;
    padding: 4px 8px;
    border-radius: 12px;
    color: white;
    display: inline-block;
}

.uudai2-card-status.active {
    background: #28a745;
}

.uudai2-card-status.expired {
    background: #6c757d;
}

/* Modal Styles */
.uudai2-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.8);
    z-index: 1000;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.uudai2-modal-box {
    background: white;
    border-radius: 15px;
    max-width: 400px;
    width: 100%;
    max-height: 90vh;
    overflow-y: auto;
}

.uudai2-modal-image {
    width: 100%;
    height: 200px;
}

.uudai2-modal-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.uudai2-modal-body {
    padding: 20px;
}

.uudai2-modal-info p:first-child {
    font-size: 1.3rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 10px;
}

.uudai2-modal-price-quantity {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.uudai2-modal-price {
    font-size: 1.8rem;
    font-weight: 800;
    color: #dc3545;
    margin: 0;
}

.uudai2-status-badge {
    padding: 6px 12px;
    border-radius: 15px;
    color: white;
    font-size: 0.8rem;
    font-weight: 600;
}

.uudai2-status-badge.active {
    background: #28a745;
}

.uudai2-status-badge.expired {
    background: #6c757d;
}

.uudai2-modal-description {
    margin: 15px 0;
}

.uudai2-modal-description h4 {
    color: #333;
    margin-bottom: 8px;
    font-size: 1rem;
}

.uudai2-modal-description p {
    color: #666;
    line-height: 1.5;
}

.uudai2-modal-dates {
    margin: 15px 0;
}

.uudai2-date-item {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 8px;
    color: #666;
    font-size: 0.9rem;
}

.uudai2-date-item i {
    color: #dc3545;
    width: 16px;
}

.uudai2-modal-actions {
    margin-top: 20px;
}

.uudai2-btn-order-now {
    width: 100%;
    padding: 12px;
    background: #dc3545;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.3s ease;
}

.uudai2-btn-order-now:hover {
    background: #c82333;
}

.uudai2-btn-order-now:disabled {
    background: #6c757d;
    cursor: not-allowed;
}

.uudai2-loading {
    grid-column: 1 / -1;
    text-align: center;
    padding: 40px;
    color: #666;
}

@media (max-width: 768px) {
    .uudai2-grid {
        grid-template-columns: 1fr;
    }
    
    .uudai2-modal-box {
        margin: 10px;
    }
}
</style>

<script>
class Uudai2Manager {
    constructor() {
        this.promotions = [];
        this.currentCategory = 'all';
        this.init();
    }

    init() {
        this.loadPromotions();
        this.setupEventListeners();
    }

    async loadPromotions() {
        // Giả lập dữ liệu
        this.promotions = [
            {
                id: 1,
                name: 'Ưu đãi cuối tuần',
                description: 'Giảm giá đặc biệt cho các đơn đặt bàn cuối tuần',
                image: 'https://via.placeholder.com/400x200/FF6B6B/FFFFFF?text=Weekend+Special',
                discountValue: 20,
                discountType: 'percent',
                startDate: '2024-01-01',
                endDate: '2024-12-31',
                status: 'active'
            },
            {
                id: 2,
                name: 'Combo gia đình',
                description: 'Combo ưu đãi dành cho gia đình 4 người',
                image: 'https://via.placeholder.com/400x200/4ECDC4/FFFFFF?text=Family+Combo',
                discountValue: 150000,
                discountType: 'fixed',
                startDate: '2024-02-01',
                endDate: '2024-06-30',
                status: 'active'
            },
            {
                id: 3,
                name: 'Khuyến mãi sinh nhật',
                description: 'Ưu đãi đặc biệt dành cho khách hàng sinh nhật',
                image: 'https://via.placeholder.com/400x200/45B7D1/FFFFFF?text=Birthday+Special',
                discountValue: 15,
                discountType: 'percent',
                startDate: '2024-03-01',
                endDate: '2024-03-31',
                status: 'expired'
            }
        ];
        
        this.renderPromotions();
    }

    renderPromotions() {
        const grid = document.getElementById('uudai2-grid');
        const filteredPromotions = this.filterPromotionsByCategory();

        if (filteredPromotions.length === 0) {
            grid.innerHTML = '<div class="uudai2-loading"><p>Không có ưu đãi nào</p></div>';
            return;
        }

        grid.innerHTML = filteredPromotions.map(promo => this.createPromotionCard(promo)).join('');
    }

    filterPromotionsByCategory() {
        if (this.currentCategory === 'all') return this.promotions;
        return this.promotions.filter(promo => promo.status === this.currentCategory);
    }

    createPromotionCard(promo) {
        const discountText = promo.discountType === 'percent' 
            ? `${promo.discountValue}%` 
            : `-${this.formatCurrency(promo.discountValue)}`;

        return `
            <div class="uudai2-card" data-action="open-modal" data-id="${promo.id}">
                <img src="${promo.image}" alt="${promo.name}">
                <div class="uudai2-card-content">
                    <span class="uudai2-card-name">${promo.name}</span>
                    <div class="uudai2-card-discount">${discountText}</div>
                    <span class="uudai2-card-status ${promo.status}">
                        ${promo.status === 'active' ? 'ĐANG ÁP DỤNG' : 'ĐÃ KẾT THÚC'}
                    </span>
                </div>
            </div>
        `;
    }

    setupEventListeners() {
        // Tab navigation
        document.querySelectorAll('.uudai2-tab-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const category = e.currentTarget.dataset.category;
                this.switchCategory(category);
            });
        });

        // Card clicks
        document.getElementById('uudai2-grid').addEventListener('click', (e) => {
            const card = e.target.closest('.uudai2-card');
            if (card) {
                const promoId = parseInt(card.dataset.id);
                this.openPromotionModal(promoId);
            }
        });

        // Modal close
        document.getElementById('uudai2-itemModal').addEventListener('click', (e) => {
            if (e.target.id === 'uudai2-itemModal') {
                this.closePromotionModal();
            }
        });

        // Use promotion button
        document.getElementById('uudai2-usePromotionBtn').addEventListener('click', () => {
            this.usePromotion();
        });
    }

    switchCategory(category) {
        document.querySelectorAll('.uudai2-tab-btn').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.category === category);
        });

        const titles = {
            'all': 'ƯU ĐÃI',
            'active': 'ƯU ĐÃI ĐANG ÁP DỤNG',
            'expired': 'ƯU ĐÃI ĐÃ KẾT THÚC'
        };
        document.getElementById('uudai2-category-title').textContent = titles[category];

        this.currentCategory = category;
        this.renderPromotions();
    }

    openPromotionModal(promoId) {
        const promotion = this.promotions.find(p => p.id === promoId);
        if (!promotion) return;

        document.getElementById('uudai2-modalItemName').textContent = promotion.name;
        document.getElementById('uudai2-modalImage').src = promotion.image;
        document.getElementById('uudai2-modalDiscount').textContent = 
            promotion.discountType === 'percent' ? `${promotion.discountValue}%` : `-${this.formatCurrency(promotion.discountValue)}`;
        document.getElementById('uudai2-modalDescription').textContent = promotion.description;
        document.getElementById('uudai2-modalStartDate').textContent = this.formatDate(promotion.startDate);
        document.getElementById('uudai2-modalEndDate').textContent = this.formatDate(promotion.endDate);

        const statusBadge = document.getElementById('uudai2-modalStatus');
        statusBadge.textContent = promotion.status === 'active' ? 'ĐANG ÁP DỤNG' : 'ĐÃ KẾT THÚC';
        statusBadge.className = `uudai2-status-badge ${promotion.status}`;

        const useBtn = document.getElementById('uudai2-usePromotionBtn');
        useBtn.disabled = promotion.status !== 'active';
        useBtn.textContent = promotion.status === 'active' ? 'Sử dụng ưu đãi' : 'Ưu đãi đã kết thúc';

        document.getElementById('uudai2-itemModal').style.display = 'flex';
    }

    closePromotionModal() {
        document.getElementById('uudai2-itemModal').style.display = 'none';
    }

    usePromotion() {
        alert('Ưu đãi đã được áp dụng!');
        this.closePromotionModal();
    }

    formatDate(dateString) {
        return new Date(dateString).toLocaleDateString('vi-VN');
    }

    formatCurrency(amount) {
        return new Intl.NumberFormat('vi-VN').format(amount) + 'đ';
    }
}

// Khởi tạo
const uudai2Manager = new Uudai2Manager();
</script>