<?php
/*
File sepay_payment.php  
Trang thanh toán SePay cho hệ thống đặt bàn từ menu2
URL: sepay_payment.php?booking_id={booking_id}&amount={amount}
*/

// Include config database
include __DIR__ . '/../config/connect.php';

// Lấy thông tin từ URL
$bookingId = $_GET['booking_id'] ?? null;
$amount = $_GET['amount'] ?? 50000; // Mặc định 50k nếu không có

if (!$bookingId || !is_numeric($bookingId)) {
    die('Booking ID không hợp lệ!');
}

// Lấy thông tin đặt bàn
$query = "SELECT d.*, kh.TenKH, kh.SDT, kh.Email, cs.TenCoSo, cs.DiaChi 
          FROM dondatban d
          LEFT JOIN khachhang kh ON d.MaKH = kh.MaKH  
          LEFT JOIN coso cs ON d.MaCoSo = cs.MaCoSo
          WHERE d.MaDon = '$bookingId' AND d.TrangThai = 'cho_xac_nhan'";

$result = mysqli_query($conn, $query);
$booking = mysqli_fetch_assoc($result);

if (!$booking) {
    die('Không tìm thấy đơn đặt bàn hoặc đã được thanh toán!');
}

?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Thanh toán đặt bàn - SePay</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .payment-container { max-width: 800px; margin: 0 auto; padding: 20px; }
        .qr-container { text-align: center; padding: 30px; border: 2px solid #e9ecef; border-radius: 15px; background: #f8f9fa; }
        .qr-container img { max-width: 300px; width: 100%; height: auto; border: 3px solid #fff; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .info-card { background: #fff; border-radius: 15px; padding: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .success-box { background: linear-gradient(135deg, #28a745, #20c997); color: white; text-align: center; padding: 30px; border-radius: 15px; display: none; }
        .bank-logo { max-height: 60px; margin: 10px 0; }
        .amount-highlight { font-size: 1.5rem; font-weight: bold; color: #dc3545; }
    </style>
</head>
<body class="bg-light">
    
    <div class="payment-container">
        
        <!-- Header -->
        <div class="info-card text-center">
            <h1 class="text-success mb-3">
                <i class="fas fa-check-circle"></i>
                Đặt bàn thành công
            </h1>
            <h4 class="text-muted mb-0">Mã đặt bàn: #DH<?= $booking['MaDon'] ?></h4>
        </div>

        <!-- Thông báo thanh toán thành công (ẩn ban đầu) -->
        <div id="success_pay_box" class="success-box">
            <h2><i class="fas fa-check-circle"></i> Thanh toán thành công!</h2>
            <p class="mb-3">Chúng tôi đã nhận được thanh toán. Bàn của bạn đã được xác nhận!</p>
            <a href="index.php?page=menu&coso=<?= $booking['MaCoSo'] ?>" class="btn btn-light btn-lg">
                Về trang menu
            </a>
        </div>

        <!-- Giao diện thanh toán -->
        <div id="checkout_box" class="row">
            
            <!-- Cột QR Code -->
            <div class="col-lg-8">
                <div class="info-card">
                    <h5 class="text-center mb-4">Thanh toán qua chuyển khoản ngân hàng</h5>
                    
                    <div class="row">
                        <!-- QR Code -->
                        <div class="col-md-6">
                            <div class="qr-container">
                                <p class="fw-bold mb-3">Cách 1: Quét mã QR</p>
                                <img src="https://qr.sepay.vn/img?bank=MBBank&acc=200409999&template=compact&amount=<?= intval($amount) ?>&des=DH<?= $booking['MaDon'] ?>" 
                                     class="img-fluid" alt="QR Thanh toán">
                                
                                <div class="mt-3">
                                    <span class="text-warning">
                                        <i class="fas fa-clock"></i>
                                        Trạng thái: Chờ thanh toán...
                                        <div class="spinner-border spinner-border-sm text-warning ms-2" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Thông tin chuyển khoản -->
                        <div class="col-md-6">
                            <div class="text-center mb-3">
                                <p class="fw-bold">Cách 2: Chuyển khoản thủ công</p>
                                <img src="https://qr.sepay.vn/assets/img/banklogo/MB.png" class="bank-logo" alt="MBBank">
                                <h5 class="text-primary">Ngân hàng MBBank</h5>
                            </div>
                            
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <td><strong>Chủ tài khoản:</strong></td>
                                        <td><strong class="text-primary">DANG TRI DUNG</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Số tài khoản:</strong></td>
                                        <td><strong class="text-primary">200409999</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Số tiền:</strong></td>
                                        <td><span class="amount-highlight"><?= number_format($amount) ?>đ</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Nội dung CK:</strong></td>
                                        <td><strong class="text-danger">DH<?= $booking['MaDon'] ?></strong></td>
                                    </tr>
                                </tbody>
                            </table>
                            
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                <strong>Lưu ý:</strong> Vui lòng giữ nguyên nội dung chuyển khoản <strong>DH<?= $booking['MaDon'] ?></strong> để hệ thống tự động xác nhận thanh toán.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Thông tin đặt bàn -->
            <div class="col-lg-4">
                <div class="info-card">
                    <h5 class="text-primary mb-8">
                        <i class="fas fa-info-circle"></i>
                        Thông tin đặt bàn
                    </h5>
                    
                    <table class="table table-borderless small">
                        <tbody>
                            <tr>
                                <td><i class="fas fa-user text-muted me-2"></i>Khách hàng:</td>
                                <td><strong><?= htmlspecialchars($booking['TenKH']) ?></strong></td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-phone text-muted me-2"></i>SĐT:</td>
                                <td><strong><?= htmlspecialchars($booking['SDT']) ?></strong></td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-map-marker-alt text-muted me-2"></i>Chi nhánh:</td>
                                <td><strong><?= htmlspecialchars($booking['TenCoSo']) ?></strong></td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-calendar text-muted me-2"></i>Thời gian:</td>
                                <td><strong><?= date('d/m/Y H:i', strtotime($booking['ThoiGianBatDau'])) ?></strong></td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-users text-muted me-2"></i>Số người:</td>
                                <td><strong><?= $booking['SoLuongKH'] ?> người</strong></td>
                            </tr>
                            <tr class="border-top pt-3">
                                <td><strong>Tổng tiền:</strong></td>
                                <td><span class="amount-highlight"><?= number_format($amount) ?>đ</span></td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <?php if (!empty($booking['GhiChu'])): ?>
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="fas fa-comment me-2"></i>
                            <strong>Ghi chú:</strong><br>
                            <?= nl2br(htmlspecialchars($booking['GhiChu'])) ?>
                        </small>
                    </div>
                    <?php endif; ?>
                </div>
                
                <!-- Thông tin hỗ trợ -->
                <div class="info-card">
                    <h6 class="text-muted mb-3">
                        <i class="fas fa-headset"></i>
                        Hỗ trợ khách hàng
                    </h6>
                    <p class="small mb-2">
                        <i class="fas fa-phone me-2"></i>
                        Hotline: <strong>0987.654.321</strong>
                    </p>
                    <p class="small mb-0">
                        <i class="fas fa-clock me-2"></i>
                        Hỗ trợ 24/7
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Nút quay lại -->
        <div class="text-center mt-4">
            <a href="index.php?page=menu&coso=<?= $booking['MaCoSo'] ?>" class="text-decoration-none">
            <i class="fa-solid fa-arrow-left"></i>
                Quay lại menu
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        var paymentStatus = 'Unpaid';
        
        // Hàm kiểm tra trạng thái thanh toán
        function checkPaymentStatus() {
            if (paymentStatus === 'Unpaid') {
                $.ajax({
                    type: "POST",
                    data: {booking_id: <?= $booking['MaDon'] ?>},
                    url: "check_booking_status.php",
                    dataType: "json",
                    success: function(data) {
                        if (data.payment_status === "Paid") {
                            $("#checkout_box").fadeOut(500, function() {
                                $("#success_pay_box").fadeIn(500);
                            });
                            paymentStatus = 'Paid';
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log('Error checking payment status:', error);
                    }
                });
            }
        }
        
        // Kiểm tra trạng thái thanh toán mỗi 2 giây một lần
        setInterval(checkPaymentStatus, 2000);
        
        // Kiểm tra ngay khi trang load
        $(document).ready(function() {
            setTimeout(checkPaymentStatus, 1000);
        });
    </script>
</body>
</html>
