<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bill Tạm Tính</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        /* --- Cài đặt chung & Font chữ --- */
        body {
            font-family: Arial, sans-serif; /* Hoặc font chữ bạn đang dùng */
            background-color: #f0f0f0; /* Màu nền xám nhạt để làm nổi bill */
            margin: 0;
            padding: 20px;
        }

        /* --- Lớp phủ mờ phía sau --- */
        .bill-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6); /* Màu đen mờ 60% */
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        /* --- Khung Bill chính --- */
        .bill-modal {
            background-color: #ffffff;
            width: 90%;
            max-width: 500px; /* Chiều rộng tối đa của bill */
            max-height: 90vh; /* Giới hạn chiều cao tối đa là 90% viewport */
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
            animation: fadeIn 0.3s ease-out;
            display: flex;
            flex-direction: column; /* Sắp xếp header, body, footer theo cột */
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* --- Header của Bill (phần màu cam) --- */
        .bill-header {
            background-color: #f39c12; /* Màu cam đặc trưng */
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-shrink: 0; /* Không cho header co lại */
            border-radius: 12px 12px 0 0; /* Bo tròn góc trên */
        }

        .bill-title {
            color: #212529; /* Màu chữ đen */
            font-size: 1.5rem;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 10px; /* Khoảng cách giữa icon và chữ */
        }
        
        .header-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .save-button {
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

        .close-button {
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

        /* --- Thân của Bill (chứa thông tin) --- */
        .bill-body {
            padding: 25px;
            flex: 1; /* Chiếm toàn bộ không gian còn lại */
            overflow-y: auto; /* Cho phép scroll dọc */
            min-height: 0; /* Để flex hoạt động đúng */
            scroll-behavior: smooth; /* Scroll mượt mà */
        }

        /* Custom scrollbar cho phần body */
        .bill-body::-webkit-scrollbar {
            width: 4px;
        }

        .bill-body::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .bill-body::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }

        .bill-body::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        /* Phần tổng tiền */
        .bill-total-summary {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .total-left .total-title {
            font-size: 1.2rem;
            font-weight: bold;
            margin: 0;
        }

        .total-left .total-note {
            font-size: 11px;
            color: #6c757d;
            margin-top: 5px;
            max-width: 250px;
        }
        
        .total-right .total-price {
            font-size: 1.2rem;
            font-weight: bold;
            color: #212529;
            text-align: right;
        }

        .total-right .clear-bill {
            font-size: 0.8rem;
            color: #6c757d;
            text-decoration: none;
            display: inline-block;
            margin-top: 5px;
            cursor: pointer;
        }
        .clear-bill i {
            margin-right: 5px;
        }

        /* Danh sách món ăn */
        .bill-items {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef; /* Đường kẻ ngang */
        }

        .bill-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0; /* Thêm padding trên dưới */
            border-bottom: 1px solid #f1f1f1; /* Đường kẻ nhạt để phân biệt items */
        }

        .bill-item:last-child {
            border-bottom: none; /* Bỏ border cho item cuối */
        }

        .item-info .item-name {
            font-weight: bold;
            margin: 0;
        }
        .item-info .item-price {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .item-controls {
            display: flex;
            align-items: center;
            gap: 15px;
        }



        /* --- Footer của Bill (chứa nút đặt bàn) --- */
        .bill-footer {
            background-color: #f8f9fa;
            padding: 20px 25px;
            text-align: center;
            border-top: 1px solid #e9ecef;
            flex-shrink: 0; /* Không cho footer co lại */
            border-radius: 0 0 12px 12px; /* Bo tròn góc dưới */
        }

        .cta-button {
            background-color: #f39c12;
            color: black;
            border: none;
            padding: 15px;
            border-radius: 30px; /* Bo tròn nhiều */
            cursor: pointer;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .footer-note {
            font-size: 0.9rem;
            color: #6c757d;
        }

        .footer-note span {
            font-weight: bold;
            color: #212529;
        }
        .quantity-controls {
  display: flex;
  align-items: center;
}

.quantity-controls button {
  width: 32px;
  height: 32px;
  border: 1px solid #ddd;
  background: #fff;
  font-size: 16px;
}

.quantity-controls input {
  width: 46px;
  height: 32px;
  text-align: center;
  border-top: 1px solid #ddd;
  border-bottom: 1px solid #ddd;
  border-left: none;
  border-right: none;
  font-size: 16px;
}

input:focus {
  outline: none;
  box-shadow: none;
}

input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

input[type=number] {
  -moz-appearance: textfield;
}

    </style>
</head>
<body>

    <div class="bill-overlay">
        <div class="bill-modal">
            <header class="bill-header">
                <div class="bill-title">
                    <i class="fas fa-receipt"></i>
                    <span>Tạm tính</span>
                </div>
                <div class="header-actions">
                    <button class="save-button">
                        <i class="fas fa-download"></i>
                        LƯU VỀ MÁY
                    </button>
                    <button class="close-button">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </header>

            <section class="bill-body">
                <div class="bill-total-summary">
                    <div class="total-left">
                        <h3 class="total-title">Tổng tiền</h3>
                        <p class="total-note">Đơn giá tạm tính chỉ mang tính chất tham khảo. <br> Liên hệ hotline để nhà hàng có thể tư vấn cho bạn chu đáo nhất! </p>
                    </div>
                    <div class="total-right">
                        <div class="total-price">189.000</div>
                        <a href="#" class="clear-bill">
                            <i class="fas fa-trash-alt"></i>
                            Xoá hết tạm tính
                        </a>
                    </div>
                </div>

                <div class="bill-items">
  <?php 
    $totalItems = 1; // số lượng dữ liệu mẫu muốn tạo
    for ($i = 1; $i <= $totalItems; $i++): 
      // Tạo dữ liệu mẫu
      $itemName = "Món nhậu số " . $i;
      $itemPrice = 100000 + ($i * 15000); 
      $itemTotal = $itemPrice; 
  ?>
    <div class="bill-item">
      <div class="item-info">
        <p class="item-name"><?= $itemName ?></p>
        <p class="item-price"><?= number_format($itemPrice, 0, ',', '.') ?>đ</p>
      </div>

      <div class="item-controls">
        <div class="quantity-controls">
          <button type="button" onclick="decreaseQuantity()">-</button>
          <input type="number" id="quantity-<?= $i ?>" value="1" min="1">
          <button type="button" onclick="increaseQuantity()">+</button>
        </div>

        <div class="item-total-price"><?= number_format($itemTotal, 0, ',', '.') ?>đ</div>
        <div class="delete_item"><i class="fas fa-trash-alt"></i></div>
      </div>
    </div>
  <?php endfor; ?>
</div>

                
            </section>

            <footer class="bill-footer">
                <button class="cta-button">ĐẶT BÀN VỚI THỰC ĐƠN NÀY</button>
                <p class="footer-note">Hoặc gọi <span>*1986</span> để đặt bàn</p>
            </footer>
        </div>
    </div>

</body>
</html>