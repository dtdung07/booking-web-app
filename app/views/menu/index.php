<!-- Menu Page Content -->
<section class="menu-hero">
    <div class="container">
        <h1 class="page-title">Thực Đơn</h1>
        <p class="page-subtitle">Khám phá hương vị đặc trưng của Quán Nhậu Tự Do</p>
    </div>
</section>

<!-- Menu Categories -->
<section class="menu-categories">
    <div class="container">
        <div class="category-tabs">
            <button class="category-tab active" data-category="all">Tất cả</button>
            <button class="category-tab" data-category="appetizers">Khai vị</button>
            <button class="category-tab" data-category="main-course">Món chính</button>
            <button class="category-tab" data-category="seafood">Hải sản</button>
            <button class="category-tab" data-category="grilled">Nướng</button>
            <button class="category-tab" data-category="hotpot">Lẩu</button>
            <button class="category-tab" data-category="drinks">Đồ uống</button>
        </div>
    </div>
</section>

<!-- Menu Items Grid -->
<section class="menu-items">
    <div class="container">
        <div class="menu-grid" id="menuGrid">
            
            <!-- Appetizers -->
            <div class="menu-item" data-category="appetizers">
                <div class="item-image">
                    <img src="<?php echo asset('images/menu/goi-bo-ca-phao.webp'); ?>" alt="Gỏi bò cà pháo đồng quê">
                    <div class="item-overlay">
                        <button class="btn-view-detail">Xem chi tiết</button>
                    </div>
                </div>
                <div class="item-info">
                    <h3 class="item-name">Gỏi bò cà pháo đồng quê</h3>
                    <p class="item-description">Món gỏi tươi mát với thịt bò mềm và cà pháo chua giòn</p>
                    <div class="item-meta">
                        <span class="item-price">169.000đ</span>
                        <button class="btn-add-to-cart">
                            <i class="fas fa-plus"></i> Thêm
                        </button>
                    </div>
                </div>
            </div>

            <!-- Main Course -->
            <div class="menu-item" data-category="main-course">
                <div class="item-image">
                    <img src="<?php echo asset('images/menu/thit-nuong-la-chuoi.webp'); ?>" alt="Thịt nướng lá chuối">
                    <div class="item-overlay">
                        <button class="btn-view-detail">Xem chi tiết</button>
                    </div>
                </div>
                <div class="item-info">
                    <h3 class="item-name">Thịt nướng lá chuối</h3>
                    <p class="item-description">Thịt ba chỉ nướng thơm lừng trong lá chuối</p>
                    <div class="item-meta">
                        <span class="item-price">189.000đ</span>
                        <button class="btn-add-to-cart">
                            <i class="fas fa-plus"></i> Thêm
                        </button>
                    </div>
                </div>
            </div>

            <!-- Seafood -->
            <div class="menu-item" data-category="seafood">
                <div class="item-image">
                    <img src="<?php echo asset('images/menu/tom-nuong-tu-nhien.webp'); ?>" alt="Tôm nướng tự nhiên">
                    <div class="item-overlay">
                        <button class="btn-view-detail">Xem chi tiết</button>
                    </div>
                </div>
                <div class="item-info">
                    <h3 class="item-name">Tôm nướng tự nhiên</h3>
                    <p class="item-description">Tôm sú tươi ngon nướng với gia vị đặc biệt</p>
                    <div class="item-meta">
                        <span class="item-price">259.000đ</span>
                        <button class="btn-add-to-cart">
                            <i class="fas fa-plus"></i> Thêm
                        </button>
                    </div>
                </div>
            </div>

            <!-- Grilled -->
            <div class="menu-item" data-category="grilled">
                <div class="item-image">
                    <img src="<?php echo asset('images/menu/suon-nuong-mo-chai.webp'); ?>" alt="Sườn nướng mở chai">
                    <div class="item-overlay">
                        <button class="btn-view-detail">Xem chi tiết</button>
                    </div>
                </div>
                <div class="item-info">
                    <h3 class="item-name">Sườn nướng mở chai</h3>
                    <p class="item-description">Sườn non nướng thơm phức, ăn kèm với rau sống</p>
                    <div class="item-meta">
                        <span class="item-price">229.000đ</span>
                        <button class="btn-add-to-cart">
                            <i class="fas fa-plus"></i> Thêm
                        </button>
                    </div>
                </div>
            </div>

            <!-- Hotpot -->
            <div class="menu-item" data-category="hotpot">
                <div class="item-image">
                    <img src="<?php echo asset('images/menu/lau-thai-tom-yum.webp'); ?>" alt="Lẩu Thái Tom Yum">
                    <div class="item-overlay">
                        <button class="btn-view-detail">Xem chi tiết</button>
                    </div>
                </div>
                <div class="item-info">
                    <h3 class="item-name">Lẩu Thái Tom Yum</h3>
                    <p class="item-description">Lẩu chua cay đậm đà với hải sản tươi ngon</p>
                    <div class="item-meta">
                        <span class="item-price">389.000đ</span>
                        <button class="btn-add-to-cart">
                            <i class="fas fa-plus"></i> Thêm
                        </button>
                    </div>
                </div>
            </div>

            <!-- Drinks -->
            <div class="menu-item" data-category="drinks">
                <div class="item-image">
                    <img src="<?php echo asset('images/menu/bia-tuoi-saigon.webp'); ?>" alt="Bia tươi Sài Gòn">
                    <div class="item-overlay">
                        <button class="btn-view-detail">Xem chi tiết</button>
                    </div>
                </div>
                <div class="item-info">
                    <h3 class="item-name">Bia tươi Sài Gòn</h3>
                    <p class="item-description">Bia tươi mát lạnh, thưởng thức cùng món nhậu</p>
                    <div class="item-meta">
                        <span class="item-price">25.000đ</span>
                        <button class="btn-add-to-cart">
                            <i class="fas fa-plus"></i> Thêm
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Menu Search & Filter -->
<section class="menu-search">
    <div class="container">
        <div class="search-box">
            <input type="text" id="menuSearch" placeholder="Tìm kiếm món ăn...">
            <button class="btn-search">
                <i class="fas fa-search"></i>
            </button>
        </div>
        <div class="filter-options">
            <label>
                <input type="checkbox" id="filterSpicy"> Món cay
            </label>
            <label>
                <input type="checkbox" id="filterVegetarian"> Món chay
            </label>
            <label>
                <input type="checkbox" id="filterSignature"> Món đặc trưng
            </label>
        </div>
    </div>
</section>

<script>
// Menu category filtering
document.addEventListener('DOMContentLoaded', function() {
    const categoryTabs = document.querySelectorAll('.category-tab');
    const menuItems = document.querySelectorAll('.menu-item');
    
    categoryTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            // Remove active class from all tabs
            categoryTabs.forEach(t => t.classList.remove('active'));
            // Add active class to clicked tab
            this.classList.add('active');
            
            const selectedCategory = this.dataset.category;
            
            // Filter menu items
            menuItems.forEach(item => {
                if (selectedCategory === 'all' || item.dataset.category === selectedCategory) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
    
    // Menu search functionality
    const searchInput = document.getElementById('menuSearch');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            menuItems.forEach(item => {
                const itemName = item.querySelector('.item-name').textContent.toLowerCase();
                const itemDesc = item.querySelector('.item-description').textContent.toLowerCase();
                
                if (itemName.includes(searchTerm) || itemDesc.includes(searchTerm)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }
});
</script>
