<?php
$page_title = "Quán Nhậu Tự Do - Thực Đơn";
include '../../../includes/header.php';
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quán Nhậu Tự Do - Website Đặt Bàn</title>
    <link rel="stylesheet" href="../../../public/css/style-menu.css">
</head>

<!-- Main -->
<main class="main-content">
    <!-- Combo món -->
    <div class="container">
        <section id="combo-section">
            <!-- Combo 1 -->
            <h2 class="section-title">Combo</h2>
            <div class="menu-grid">
                <div class="menu-card" data-category="combo">
                    <div class="menu-card-image">
                        <span class="combo-badge">Combo 1</span>
                        <span class="price-badge">1104K</span>
                        <i class="fas fa-utensils"></i>
                    </div>
                    <div class="menu-card-content">
                        <h3>Combo 1 - 1104K</h3>
                        <div class="menu-card-price">1.104.000đ</div>
                        <p class="menu-card-description">
                            Tôm hùm Alaska, Cá hồi Na Uy sốt Dầu hào, Nai nướng Cây đinh lăng, 
                            Gà nướng Ớt hiểm, Rau muống xào tỏi, Canh chua cá lóc.
                        </p>
                        <button class="order-btn" onclick="addToTempCart('combo1', 'Combo 1', 1104000)">
                            <i class="fas fa-plus"></i> Đặt
                        </button>
                    </div>
                </div>
                <!-- Combo 2 -->
                <div class="menu-card" data-category="combo">
                    <div class="menu-card-image">
                        <span class="combo-badge">Combo 2</span>
                        <span class="price-badge">1324K</span>
                        <i class="fas fa-utensils"></i>
                    </div>
                    <div class="menu-card-content">
                        <h3>Combo 2 - 1324K</h3>
                        <div class="menu-card-price">1.324.000đ</div>
                        <p class="menu-card-description">
                            Tôm hùm nướng phô mai, Cua hoàng đế Alaska, Bò Úc nướng, 
                            Gà ta nướng muối ớt, Cháo hến, Canh chua tôm đất.
                        </p>
                        <button class="order-btn" onclick="addToTempCart('combo2', 'Combo 2', 1324000)">
                            <i class="fas fa-plus"></i> Đặt
                        </button>
                    </div>
                </div>
                <!-- Combo 3 -->
                <div class="menu-card" data-category="combo">
                    <div class="menu-card-image">
                        <span class="combo-badge">Combo 3</span>
                        <span class="price-badge">1392K</span>
                        <i class="fas fa-utensils"></i>
                    </div>
                    <div class="menu-card-content">
                        <h3>Combo 3 - 1392K</h3>
                        <div class="menu-card-price">1.392.000đ</div>
                        <p class="menu-card-description">
                            Cua hoàng đế nướng bơ tỏi, Tôm hùm sốt me, Bò nướng lá lốt, 
                            Gà nướng ngũ vị, Cơm chiên dương châu, Canh chua cá basa.
                        </p>
                        <button class="order-btn" onclick="addToTempCart('combo3', 'Combo 3', 1392000)">
                            <i class="fas fa-plus"></i> Đặt
                        </button>
                    </div>
                </div>
                <!-- Combo 4 -->
                <div class="menu-card" data-category="combo">
                    <div class="menu-card-image">
                        <span class="combo-badge">Combo 4</span>
                        <span class="price-badge">1483K</span>
                        <i class="fas fa-utensils"></i>
                    </div>
                    <div class="menu-card-content">
                        <h3>Combo 4 - 1483K</h3>
                        <div class="menu-card-price">1.483.000đ</div>
                        <p class="menu-card-description">
                            Tôm hùm nướng phô mai, Cua hoàng đế sốt ớt, Bò Wagyu nướng, 
                            Gà ta nướng mật ong, Cơm chiên hải sản, Canh chua tôm sú.
                        </p>
                        <button class="order-btn" onclick="addToTempCart('combo4', 'Combo 4', 1483000)">
                            <i class="fas fa-plus"></i> Đặt
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Món Mới -->
        <section id="mon-moi-section">
            <!-- khoai chiên -->
            <h2 class="section-title">Món mới ra lò</h2>
            <div class="menu-grid">
                <div class="menu-card" data-category="mon-moi">
                    <div class="menu-card-image">
                        <i class="fas fa-fire"></i>
                    </div>
                    <div class="menu-card-content">
                        <h3>Khoai tây chiên Hongkong</h3>
                        <div class="menu-card-price">58.000đ</div>
                        <p class="menu-card-description">
                            Khoai tây chiên giòn tan với gia vị đặc biệt theo phong cách Hongkong.
                        </p>
                        <button class="order-btn" onclick="addToTempCart('khoai-tay-hk', 'Khoai tây chiên Hongkong', 58000)">
                            <i class="fas fa-plus"></i> Đặt
                        </button>
                    </div>
                </div>
                <!-- Gỏi bò -->
                <div class="menu-card" data-category="mon-moi">
                    <div class="menu-card-image">
                        <i class="fas fa-leaf"></i>
                    </div>
                    <div class="menu-card-content">
                        <h3>Gỏi bò cà pháo đồng quê</h3>
                        <div class="menu-card-price">169.000đ</div>
                        <p class="menu-card-description">
                            Gỏi bò tươi ngon kết hợp cà pháo đồng quê, vị chua cay đặc trưng.
                        </p>
                        <button class="order-btn" onclick="addToTempCart('goi-bo-ca-phao', 'Gỏi bò cà pháo đồng quê', 169000)">
                            <i class="fas fa-plus"></i> Đặt
                        </button>
                    </div>
                </div>
                <!-- Nầm -->
                <div class="menu-card" data-category="mon-moi">
                    <div class="menu-card-image">
                        <i class="fas fa-cheese"></i>
                    </div>
                    <div class="menu-card-content">
                        <h3>Nầm sữa cháy tỏi</h3>
                        <div class="menu-card-price">199.000đ</div>
                        <p class="menu-card-description">
                            Nầm sữa tươi ngon nướng cháy tỏi thơm phức, đặc sản không thể bỏ qua.
                        </p>
                        <button class="order-btn" onclick="addToTempCart('nam-sua-chay-toi', 'Nầm sữa cháy tỏi', 199000)">
                            <i class="fas fa-plus"></i> Đặt
                        </button>
                    </div>
                </div>
                <!-- Salad -->
                <div class="menu-card" data-category="mon-moi">
                    <div class="menu-card-image">
                        <i class="fas fa-fish"></i>
                    </div>
                    <div class="menu-card-content">
                        <h3>Salad xà lách hải sản</h3>
                        <div class="menu-card-price">179.000đ</div>
                        <p class="menu-card-description">
                            Salad tươi mát với hải sản tươi ngon, sốt đặc biệt của nhà.
                        </p>
                        <button class="order-btn" onclick="addToTempCart('salad-hai-san', 'Salad xà lách hải sản', 179000)">
                            <i class="fas fa-plus"></i> Đặt
                        </button>
                    </div>
                </div>

                <div class="menu-card" data-category="mon-moi">
                    <div class="menu-card-image">
                        <i class="fas fa-fish"></i>
                    </div>
                    <div class="menu-card-content">
                        <h3>Cá dưa chua tứ xuyên</h3>
                        <div class="menu-card-price">469.000đ</div>
                        <p class="menu-card-description">
                            Cá tươi ngon nấu với dưa chua theo phong cách Tứ Xuyên cay nồng.
                        </p>
                        <button class="order-btn" onclick="addToTempCart('ca-dua-chua', 'Cá dưa chua tứ xuyên', 469000)">
                            <i class="fas fa-plus"></i> Đặt
                        </button>
                    </div>
                </div>

                <div class="menu-card" data-category="mon-moi">
                    <div class="menu-card-image">
                        <i class="fas fa-drumstick-bite"></i>
                    </div>
                    <div class="menu-card-content">
                        <h3>Trâu tươi cháy tỏi</h3>
                        <div class="menu-card-price">189.000đ</div>
                        <p class="menu-card-description">
                            Thịt trâu tươi ngon nướng cháy tỏi thơm lừng, đặc sản miền núi.
                        </p>
                        <button class="order-btn" onclick="addToTempCart('trau-tuoi-chay-toi', 'Trâu tươi cháy tỏi', 189000)">
                            <i class="fas fa-plus"></i> Đặt
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Xem thêm món ăn -->
        <section class="cta-section" style="text-align: center; padding: 50px 0; background: white; border-radius: 15px; margin: 40px 0;">
            <h2 style="color: #2C5F2D; margin-bottom: 20px;">Xem thêm món ngon</h2>
            <a href="menu.php" class="btn btn-primary" style="padding: 15px 40px; font-size: 16px;">
                XEM THỰC ĐƠN HOÀN CHỈNH
            </a>
        </section>
    </div>
</main>

<!-- Cơ sở -->
<section class="features">
    <div class="container">
        <div class="features-grid">
            <div class="feature-card fade-in">
                <div class="feature-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <h3>17 Cơ sở</h3>
                <p>Quán Nhậu Tự Do tự hào khi có số lượng cơ sở khắp các quận cùng đội ngũ nhân viên nhiệt tình chu đáo sẵn sàng phục vụ quý thực khách.</p>
            </div>
            
            <div class="feature-card fade-in">
                <div class="feature-icon">
                    <i class="fas fa-utensils"></i>
                </div>
                <h3>Ẩm thực vùng miền quá đa dạng</h3>
                <p>Bia ngon, rượu say với bạn hiền. Cảnh đẹp, quán vui quên lối về.</p>
            </div>
            
            <div class="feature-card fade-in">
                <div class="feature-icon">
                    <i class="fas fa-glass-cheers"></i>
                </div>
                <h3>Dịch vụ tiệc tùng hết nấc</h3>
                <p>Loa, mic, video, hô là có. Phòng VIP riêng tư luôn sẵn sàng. Hỗ trợ nhiệt tình mọi yêu cầu.</p>
            </div>
        </div>
    </div>
</section>

