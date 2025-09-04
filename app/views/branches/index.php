<link rel="stylesheet" href="public/css/pages/branches.css">

<main class="branches-page">

    <!-- Tab Navigation -->
    <section class="tab-section">
        <div class="container">
            <div class="tab-navigation">
                <button class="tab-btn active" data-district="all">
                    <span class="tab-text">TẤT CẢ</span>
                </button>
                <button class="tab-btn" data-district="ba-dinh">
                    <span class="tab-text">BA ĐÌNH</span>
                </button>
                <button class="tab-btn" data-district="cau-giay">
                    <span class="tab-text">CẦU GIẤY</span>
                </button>
                <button class="tab-btn" data-district="dong-da">
                    <span class="tab-text">ĐỐNG ĐA</span>
                </button>
                <button class="tab-btn" data-district="hai-ba-trung">
                    <span class="tab-text">HAI BÀ TRƯNG</span>
                </button>
                <button class="tab-btn" data-district="hoang-mai">
                    <span class="tab-text">HOÀNG MAI</span>
                </button>
            </div>
        </div>
    </section>

    <!-- Branches Grid -->
<section class="branches-section">
  <div class="container">
    <div class="branches-grid" id="branchesGrid">
      <?php foreach ($branches as $branch): ?>
        <div class="branch-card" data-district="<?php echo strtolower(str_replace(' ', '-', $branch['district'])); ?>">
         
          <!-- Nội dung bên trái -->
          <div class="branch-content">
            <div class="branch-header">
              <h3 class="branch-name"><?php echo $branch['name']; ?></h3>
              <p class="branch-description"><?php echo $branch['description']; ?></p>
            </div>

            <div class="branch-info">
                <div class="state">
                    <span><?php echo $branch['status']; ?></span>
                </div>
                <div class="info-item">
                    <span>HOẠT ĐỘNG TỪ <?php echo $branch['operating_hours']; ?></span>
                </div>
            </div>

            <div class="branch-stats">
              <div class="stat-item">
                <span class="stat-label">Sức chứa</span>
                <span class="stat-value"><?php echo $branch['capacity']; ?></span>
              </div>
              <div class="stat-item">
                <span class="stat-label">Diện tích</span>
                <span class="stat-value"><?php echo $branch['area']; ?></span>
              </div>
              <div class="stat-item">
                <span class="stat-label">Số tầng</span>
                <span class="stat-value"><?php echo $branch['floors']; ?></span>
              </div>
            </div>

            <div class="branch-actions">
              <a href="<?php echo $branch['map_link']; ?>" target="_blank" class="btn btn-outline">
                <i class="fas fa-map-marker-alt"></i>
                Xem bản đồ
              </a>
              <a href="?page=branches&action=detail&id=<?php echo $branch['id']; ?>" class="btn btn-outline">
                <i class="fas fa-info-circle"></i>
                Xem chi tiết
              </a>
              <button class="btn btn-outline" onclick="openBookingModal(<?php echo $branch['id']; ?>)">
                <i class="fas fa-calendar-check"></i>
                Đặt bàn ngay
              </button>
            </div>
             <div class="info-item">
                <i class="fas fa-phone"></i>
                <span><?php echo $branch['hotline']; ?></span>
              </div>
          </div> <!-- end branch-content -->

          <!-- Ảnh bên phải -->
          <div class="branch-image">
            <img src="<?php echo $branch['image']; ?>" alt="<?php echo $branch['name']; ?>" loading="lazy">
          </div>

        </div> <!-- end branch-card -->
        
      <?php endforeach; ?>
    </div>

    <?php if (empty($branches)): ?>
      <div class="no-results">
        <i class="fas fa-search"></i>
        <h3>Không tìm thấy cơ sở nào</h3>
        <p>Vui lòng thử lại với bộ lọc khác</p>
      </div>
    <?php endif; ?>
  </div>
</section>



</main>

<script>
// Data and tab management
const branches = <?php echo json_encode($branches); ?>;
let currentDistrict = 'all';

// Initialize tab counts and functionality
document.addEventListener('DOMContentLoaded', function() {
    initializeTabs();
    initializeAnimations();
});

function initializeTabs() {
    // Count branches by district
    const districtCounts = {
        'all': branches.length,
        'ba-dinh': 0,
        'cau-giay': 0,
        'dong-da': 0,
        'hai-ba-trung': 0,
        'hoang-mai': 0
    };
    
    branches.forEach(branch => {
        const district = branch.district.toLowerCase().replace(' ', '-');
        if (districtCounts[district] !== undefined) {
            districtCounts[district]++;
        }
    });
    
    // Update tab counts
    Object.keys(districtCounts).forEach(district => {
        const countElement = document.getElementById(`count-${district}`);
        if (countElement) {
            countElement.textContent = districtCounts[district];
        }
    });
    
    // Add tab click listeners
    const tabButtons = document.querySelectorAll('.tab-btn');
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Add click animation
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 150);
            
            const district = this.dataset.district;
            switchTab(district);
        });
        
        // Add hover effects
        button.addEventListener('mouseenter', function() {
            if (!this.classList.contains('active')) {
                this.style.transform = 'translateY(-2px)';
            }
        });
        
        button.addEventListener('mouseleave', function() {
            if (!this.classList.contains('active')) {
                this.style.transform = 'translateY(0)';
            }
        });
    });
    
    // Set initial state
    switchTab('all');
}

function switchTab(district) {
    currentDistrict = district;
    
    // Update active tab
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    document.querySelector(`[data-district="${district}"]`).classList.add('active');
    
    // Filter and animate branches
    filterBranches(district);
    
    // Update URL without reload
    const url = new URL(window.location);
    if (district === 'all') {
        url.searchParams.delete('district');
    } else {
        url.searchParams.set('district', district);
    }
    window.history.pushState({}, '', url);
}

function filterBranches(district) {
    const branchCards = document.querySelectorAll('.branch-card');
    const grid = document.getElementById('branchesGrid');
    
    // Add loading state
    grid.classList.add('loading');
    
    // First hide all cards
    branchCards.forEach(card => {
        card.classList.add('hiding');
    });
    
    setTimeout(() => {
        let visibleCount = 0;
        
        branchCards.forEach((card, index) => {
            const cardDistrict = card.dataset.district;
            const shouldShow = district === 'all' || cardDistrict === district;
            
            if (shouldShow) {
                card.style.display = 'block';
                visibleCount++;
                // Show cards with staggered animation
                setTimeout(() => {
                    card.classList.remove('hiding');
                    card.classList.add('showing');
                }, (visibleCount - 1) * 50);
            } else {
                card.style.display = 'none';
                card.classList.remove('showing');
            }
        });
        
        // Remove loading state
        setTimeout(() => {
            grid.classList.remove('loading');
        }, 300);
        
        // Handle no results
        setTimeout(() => {
            const noResults = document.querySelector('.no-results');
            if (noResults) noResults.remove();
            
            if (visibleCount === 0) {
                showNoResults();
            }
        }, 400);
        
    }, 150);
}

function showNoResults() {
    const grid = document.getElementById('branchesGrid');
    const noResultsHTML = `
        <div class="no-results">
            <i class="fas fa-search"></i>
            <h3>Không tìm thấy cơ sở nào</h3>
            <p>Chưa có cơ sở nào tại quận này</p>
        </div>
    `;
    grid.insertAdjacentHTML('afterend', noResultsHTML);
}

function initializeAnimations() {
    // Set initial card states for animation
    const cards = document.querySelectorAll('.branch-card');
    cards.forEach(card => {
        card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
    });
    
    // Animate cards on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting && entry.target.style.display !== 'none') {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);
    
    cards.forEach(card => {
        observer.observe(card);
    });
}

// Modal functions
function openBookingModal(branchId) {
    const modal = document.getElementById('bookingModal');
    const branchNameSpan = document.getElementById('selectedBranchName');
    const confirmBtn = document.getElementById('confirmBookingBtn');
    
    const branch = branches.find(b => b.id == branchId);
    
    if (branch) {
        branchNameSpan.textContent = branch.name;
        confirmBtn.href = `?page=booking&branch_id=${branchId}`;
    }
    
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeBookingModal() {
    const modal = document.getElementById('bookingModal');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Close modal when clicking outside
document.getElementById('bookingModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeBookingModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeBookingModal();
    }
});

// Filter animations
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.branch-card');
    
    // Animate cards on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);
    
    cards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(card);
    });
});
</script>
