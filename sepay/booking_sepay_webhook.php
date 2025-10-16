<?php
/*
File booking_sepay_webhook.php
Webhook SePay cho hệ thống đặt bàn
Endpoint nhận webhook sẽ là: https://yourdomain.com/booking-web-app/sepay/booking_sepay_webhook.php
*/

// Include cấu hình database của hệ thống đặt bàn
include __DIR__ . '/../config/connect.php';
include __DIR__ . '/../config/config.php';
include __DIR__ . '/../includes/EmailService.php';

// Lấy dữ liệu từ webhook SePay
$data = json_decode(file_get_contents('php://input'));

if (!is_object($data)) {
    echo json_encode(['success' => false, 'message' => 'Khong co du lieu']);
    die('No data found!');
}

// Khởi tạo các biến từ webhook SePay
$gateway = $data->gateway;
$transaction_date = $data->transactionDate;  
$account_number = $data->accountNumber;
$sub_account = $data->subAccount;
$transfer_type = $data->transferType;
$transfer_amount = $data->transferAmount;
$accumulated = $data->accumulated;
$code = $data->code;
$transaction_content = $data->content;
$reference_number = $data->referenceCode;
$body = $data->description;

$amount_in = 0;
$amount_out = 0;

// Kiểm tra giao dịch tiền vào hay tiền ra
if ($transfer_type == "in") {
    $amount_in = $transfer_amount;
} else if ($transfer_type == "out") {
    $amount_out = $transfer_amount;
}

// Ghi log thông tin giao dịch vào file (thay vì database)
$logData = [
    'timestamp' => date('Y-m-d H:i:s'),
    'gateway' => $gateway,
    'transaction_date' => $transaction_date,
    'amount_in' => $amount_in,
    'content' => $transaction_content,
    'reference' => $reference_number
];

error_log("SePay Transaction: " . json_encode($logData));

// Chỉ xử lý giao dịch tiền vào
if ($transfer_type !== "in" || $amount_in <= 0) {
    echo json_encode(['success' => false, 'message' => 'Khong phai la giao dich vao (in)']);
    die();
}

// Tách mã đặt bàn từ nội dung giao dịch
// Định dạng: DH123 hoặc DB123 (DH/DB + số ID đặt bàn)
$regex = '/(?:DH|DB)(\d+)/';
preg_match($regex, $transaction_content, $matches);

if (empty($matches[1]) || !is_numeric($matches[1])) {
    echo json_encode(['success' => false, 'message' => 'Khong tim thay Booking ID trong nội dung giao dịch']);
    die();
}

$bookingId = $matches[1];

// Kiểm tra đặt bàn có tồn tại và chưa thanh toán
$checkBookingQuery = "SELECT * FROM dondatban 
                     WHERE MaDon = '$bookingId' 
                     AND TrangThai = 'cho_xac_nhan'";

$result = mysqli_query($conn, $checkBookingQuery);
$booking = mysqli_fetch_assoc($result);

if (!$booking) {
    echo json_encode([
        'success' => false, 
        'message' => 'Khong tim thay don dat ban hoac da duoc xu ly',
        'booking_id' => $bookingId
    ]);
    die();
}

// Lấy tổng tiền món ăn của đơn đặt bàn
$totalQuery = "SELECT SUM(SoLuong * DonGia) as total_food FROM chitietdondatban WHERE MaDon = '$bookingId'";
$totalResult = mysqli_query($conn, $totalQuery);
$totalData = mysqli_fetch_assoc($totalResult);

$expectedAmount = floatval($totalData['total_food'] ?? 0);

// Áp dụng giảm giá nếu đơn có MaUD hợp lệ
if (!empty($booking['MaUD'])) {
    $maUD = intval($booking['MaUD']);
    $udQuery = "SELECT GiaTriGiam, LoaiGiamGia FROM uudai WHERE MaUD = '$maUD' AND NgayBD <= CURDATE() AND NgayKT >= CURDATE()";
    $udResult = mysqli_query($conn, $udQuery);
    if ($udRow = mysqli_fetch_assoc($udResult)) {
        $discountValue = floatval($udRow['GiaTriGiam']);
        if ($udRow['LoaiGiamGia'] === 'phantram') {
            $discountAmount = ($expectedAmount * $discountValue) / 100;
        } else { // sotien
            $discountAmount = $discountValue;
        }
        $discountAmount = min($discountAmount, $expectedAmount);
        $expectedAmount = max(0, $expectedAmount - $discountAmount);
    }
}

// So sánh theo số tiền phải thu sau giảm (nếu có)
if (floatval($amount_in) < floatval($expectedAmount)) {
    echo json_encode([
        'success' => false, 
        'message' => 'Insufficient payment amount',
        'expected' => $expectedAmount,
        'received' => $amount_in,
        'booking_id' => $bookingId
    ]);
    die();
}

// Cập nhật trạng thái đặt bàn thành đã xác nhận (đã thanh toán)
$updateBookingQuery = "UPDATE dondatban 
                      SET TrangThai = 'da_xac_nhan' 
                      WHERE MaDon = '$bookingId'";

if (mysqli_query($conn, $updateBookingQuery)) {
    // Thêm log ghi chú về việc thanh toán
    $paymentNote = "\n[Thanh toán - " . date('d/m/Y H:i') . "]: Đã thanh toán " . number_format($amount_in) . "đ qua quét mã QR";
    $updateNoteQuery = "UPDATE dondatban 
                       SET GhiChu = CONCAT(IFNULL(GhiChu, ''), '$paymentNote')
                       WHERE MaDon = '$bookingId'";
    
    mysqli_query($conn, $updateNoteQuery);
    
    // GỬI EMAIL THÔNG BÁO THANH TOÁN THÀNH CÔN
    try {
        // Lấy thông tin đơn đặt bàn
        $emailQuery = "SELECT d.*, kh.TenKH, kh.SDT, kh.Email, cs.TenCoSo,
                              GROUP_CONCAT(CONCAT(b.TenBan, ' (', b.SucChua, ' chỗ)') SEPARATOR ', ') as DanhSachBan
                       FROM dondatban d
                       LEFT JOIN khachhang kh ON d.MaKH = kh.MaKH  
                       LEFT JOIN coso cs ON d.MaCoSo = cs.MaCoSo
                       LEFT JOIN dondatban_ban ddb ON d.MaDon = ddb.MaDon
                       LEFT JOIN ban b ON ddb.MaBan = b.MaBan
                       WHERE d.MaDon = '$bookingId'
                       GROUP BY d.MaDon";
        
        $emailResult = mysqli_query($conn, $emailQuery);
        $thongTinDon = mysqli_fetch_assoc($emailResult);
        
        if ($thongTinDon && !empty($thongTinDon['Email'])) {
            
            // Lấy danh sách món ăn đơn giản
            $monQuery = "SELECT m.TenMon, ct.SoLuong, ct.DonGia
                         FROM chitietdondatban ct
                         JOIN monan m ON ct.MaMon = m.MaMon
                         WHERE ct.MaDon = '$bookingId'";
            
            $monResult = mysqli_query($conn, $monQuery);
            $danhSachMon = [];
            
            while ($mon = mysqli_fetch_assoc($monResult)) {
                $danhSachMon[] = $mon;
            }
            
            // Gọi hàm gửi email đơn giản
            $emailThanhCong = gui_email_thanh_toan_thanh_cong(
                $thongTinDon['Email'],
                $thongTinDon['TenKH'], 
                $bookingId,
                $thongTinDon['TenCoSo'],
                date('d/m/Y H:i', strtotime($thongTinDon['ThoiGianBatDau'])),
                $thongTinDon['SoLuongKH'],
                $thongTinDon['DanhSachBan'] ?: 'Sẽ sắp xếp khi đến',
                $danhSachMon,
                $amount_in,
                $thongTinDon['GhiChu'] ?: ''
            );
            
            if ($emailThanhCong) {
                error_log("Gửi email thành công cho đơn #{$bookingId}");
            } else {
                error_log("Gửi email thất bại cho đơn #{$bookingId}");
            }
        }
        
    } catch (Exception $e) {
        error_log("Lỗi gửi email đơn #{$bookingId}: " . $e->getMessage());
        // Không ảnh hưởng đến webhook chính
    }
    
    echo json_encode([
        'success' => true, 
        'message' => 'Thanh toan don dat ban thanh cong',
        'booking_id' => $bookingId,
        'amount_paid' => $amount_in
    ]);
    
} else {
    echo json_encode([
        'success' => false, 
        'message' => 'Loi khi cap nhat trang thai don dat ban',
        'booking_id' => $bookingId
    ]);
}

?>
